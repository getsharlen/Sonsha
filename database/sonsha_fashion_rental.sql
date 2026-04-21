-- Sonsha Fashion Rental
-- Database deliverable untuk proyek peminjaman barang bertema fashion

CREATE DATABASE IF NOT EXISTS sonsha_fashion_rental;
USE sonsha_fashion_rental;

START TRANSACTION;

DROP TABLE IF EXISTS fine_payments;
DROP TABLE IF EXISTS borrowing_items;
DROP TABLE IF EXISTS borrowings;
DROP TABLE IF EXISTS activity_logs;
DROP TABLE IF EXISTS assets;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'petugas', 'peminjam') NOT NULL DEFAULT 'peminjam',
    phone VARCHAR(30) NULL,
    balance DECIMAL(12,2) NOT NULL DEFAULT 0,
    status ENUM('active', 'locked') NOT NULL DEFAULT 'active',
    locked_reason VARCHAR(255) NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

CREATE TABLE assets (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT UNSIGNED NOT NULL,
    code VARCHAR(255) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    brand VARCHAR(255) NULL,
    stock_total INT UNSIGNED NOT NULL DEFAULT 0,
    stock_available INT UNSIGNED NOT NULL DEFAULT 0,
    condition ENUM('baik', 'rusak', 'maintenance') NOT NULL DEFAULT 'baik',
    rent_fee DECIMAL(12,2) NOT NULL DEFAULT 0,
    description TEXT NULL,
    image_url VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT fk_assets_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE borrowings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    borrowing_code VARCHAR(255) NOT NULL UNIQUE,
    user_id BIGINT UNSIGNED NOT NULL,
    approved_by BIGINT UNSIGNED NULL,
    returned_by BIGINT UNSIGNED NULL,
    borrowed_at TIMESTAMP NULL,
    due_at TIMESTAMP NULL,
    returned_at TIMESTAMP NULL,
    status ENUM('requested', 'approved', 'borrowed', 'returned', 'rejected', 'late', 'paid') NOT NULL DEFAULT 'requested',
    purpose TEXT NOT NULL,
    total_fine DECIMAL(12,2) NOT NULL DEFAULT 0,
    notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT fk_borrowings_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_borrowings_approver FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT fk_borrowings_returner FOREIGN KEY (returned_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE borrowing_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    borrowing_id BIGINT UNSIGNED NOT NULL,
    asset_id BIGINT UNSIGNED NOT NULL,
    quantity INT UNSIGNED NOT NULL DEFAULT 1,
    unit_fee DECIMAL(12,2) NOT NULL DEFAULT 0,
    fine_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
    status ENUM('pending', 'borrowed', 'returned') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT fk_items_borrowing FOREIGN KEY (borrowing_id) REFERENCES borrowings(id) ON DELETE CASCADE,
    CONSTRAINT fk_items_asset FOREIGN KEY (asset_id) REFERENCES assets(id) ON DELETE CASCADE
);

CREATE TABLE activity_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    module VARCHAR(255) NOT NULL,
    action VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    payload JSON NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT fk_logs_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE fine_payments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    borrowing_id BIGINT UNSIGNED NULL,
    amount DECIMAL(12,2) NOT NULL DEFAULT 0,
    method VARCHAR(255) NOT NULL DEFAULT 'wallet',
    status ENUM('pending', 'paid') NOT NULL DEFAULT 'pending',
    note TEXT NULL,
    paid_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT fk_payments_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_payments_borrowing FOREIGN KEY (borrowing_id) REFERENCES borrowings(id) ON DELETE SET NULL
);

DELIMITER $$

CREATE FUNCTION fn_calculate_fine(days_late INT)
RETURNS DECIMAL(12,2)
DETERMINISTIC
BEGIN
    RETURN GREATEST(days_late, 0) * 5000;
END$$

CREATE PROCEDURE sp_create_borrowing(
    IN p_user_id BIGINT UNSIGNED,
    IN p_purpose TEXT,
    IN p_due_at DATETIME
)
BEGIN
    INSERT INTO borrowings (borrowing_code, user_id, due_at, status, purpose, created_at, updated_at)
    VALUES (CONCAT('PMJ-', DATE_FORMAT(NOW(), '%Y%m%d%H%i%s')), p_user_id, p_due_at, 'requested', p_purpose, NOW(), NOW());
END$$

CREATE PROCEDURE sp_return_borrowing(
    IN p_borrowing_id BIGINT UNSIGNED,
    IN p_returned_by BIGINT UNSIGNED
)
BEGIN
    DECLARE v_due_at DATETIME;
    DECLARE v_days_late INT DEFAULT 0;
    DECLARE v_fine DECIMAL(12,2) DEFAULT 0;
    DECLARE v_user_id BIGINT UNSIGNED;
    DECLARE v_balance DECIMAL(12,2);

    SELECT due_at, user_id INTO v_due_at, v_user_id
    FROM borrowings
    WHERE id = p_borrowing_id;

    SET v_days_late = GREATEST(DATEDIFF(CURDATE(), DATE(v_due_at)), 0);
    SET v_fine = fn_calculate_fine(v_days_late);

    SELECT balance INTO v_balance FROM users WHERE id = v_user_id FOR UPDATE;

    UPDATE borrowings
    SET returned_at = NOW(),
        returned_by = p_returned_by,
        total_fine = v_fine,
        status = IF(v_fine > 0, 'late', 'returned'),
        updated_at = NOW()
    WHERE id = p_borrowing_id;

    IF v_fine > 0 THEN
        IF v_balance >= v_fine THEN
            UPDATE users
            SET balance = balance - v_fine,
                status = 'active',
                locked_reason = NULL,
                updated_at = NOW()
            WHERE id = v_user_id;

            INSERT INTO fine_payments (user_id, borrowing_id, amount, method, status, note, paid_at, created_at, updated_at)
            VALUES (v_user_id, p_borrowing_id, v_fine, 'wallet', 'paid', 'Auto debit dari saldo', NOW(), NOW(), NOW());
        ELSE
            UPDATE users
            SET balance = 0,
                status = 'locked',
                locked_reason = 'Saldo tidak cukup untuk membayar denda',
                updated_at = NOW()
            WHERE id = v_user_id;

            INSERT INTO fine_payments (user_id, borrowing_id, amount, method, status, note, created_at, updated_at)
            VALUES (v_user_id, p_borrowing_id, v_fine - v_balance, 'wallet', 'pending', 'Menunggu top up saldo', NOW(), NOW());
        END IF;
    END IF;
END$$

CREATE TRIGGER trg_borrowings_lock_user_after_update
AFTER UPDATE ON borrowings
FOR EACH ROW
BEGIN
    IF NEW.status = 'late' AND NEW.total_fine > 0 THEN
        UPDATE users
        SET status = IF(balance > 0, 'active', 'locked'),
            locked_reason = IF(balance > 0, NULL, 'Menunggu pelunasan denda')
        WHERE id = NEW.user_id;
    END IF;
END$$

DELIMITER ;

COMMIT;

-- Untuk rollback manual:
-- ROLLBACK;
-- DROP DATABASE sonsha_fashion_rental;