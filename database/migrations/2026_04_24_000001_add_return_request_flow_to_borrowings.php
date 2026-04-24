<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->timestamp('return_requested_at')->nullable()->after('due_at');
        });

        Schema::table('borrowing_items', function (Blueprint $table) {
            $table->string('return_photo_path')->nullable()->after('return_condition');
        });

        DB::statement("ALTER TABLE borrowings MODIFY status ENUM('requested', 'approved', 'borrowed', 'return_requested', 'returned', 'rejected', 'late', 'paid') DEFAULT 'requested'");
    }

    public function down(): void
    {
        DB::statement("UPDATE borrowings SET status = 'borrowed' WHERE status = 'return_requested'");
        DB::statement("ALTER TABLE borrowings MODIFY status ENUM('requested', 'approved', 'borrowed', 'returned', 'rejected', 'late', 'paid') DEFAULT 'requested'");

        Schema::table('borrowing_items', function (Blueprint $table) {
            $table->dropColumn('return_photo_path');
        });

        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn('return_requested_at');
        });
    }
};
