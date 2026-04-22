<?php

namespace Tests\Feature;

use App\Models\FinePayment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfilePageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_profile_page_renders_main_tabs_and_top_up_form(): void
    {
        $user = User::factory()->create([
            'role' => 'peminjam',
            'balance' => 15000,
        ]);

        $response = $this->actingAs($user)->get(route('profile'));

        $response->assertOk();
        $response->assertSeeText('Informasi Akun');
        $response->assertSeeText('Pembayaran');
        $response->assertSeeText('Denda');
        $response->assertSeeText('Riwayat');
        $response->assertSee('name="amount"', false);
        $response->assertSee(route('wallet.topup'), false);
        $response->assertDontSee('data-tab="settings"', false);
    }

    public function test_user_can_top_up_balance_from_profile(): void
    {
        $user = User::factory()->create([
            'role' => 'peminjam',
            'balance' => 1000,
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)
            ->from(route('profile'))
            ->post(route('wallet.topup'), [
                'amount' => 5000,
            ]);

        $response->assertRedirect(route('profile'));

        $user->refresh();

        $this->assertSame('6000.00', $user->balance);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'module' => 'wallet',
            'action' => 'top_up',
        ]);
    }

    public function test_top_up_auto_pays_pending_fines_and_unlocks_account(): void
    {
        $user = User::factory()->create([
            'role' => 'peminjam',
            'balance' => 2000,
            'status' => 'locked',
            'locked_reason' => 'Menunggu pelunasan denda.',
        ]);

        $fine = FinePayment::query()->create([
            'user_id' => $user->id,
            'borrowing_id' => null,
            'amount' => 7000,
            'status' => 'pending',
            'method' => 'wallet',
        ]);

        $this->actingAs($user)
            ->from(route('profile'))
            ->post(route('wallet.topup'), [
                'amount' => 10000,
            ])
            ->assertRedirect(route('profile'));

        $user->refresh();
        $fine->refresh();

        $this->assertSame('5000.00', $user->balance);
        $this->assertSame('active', $user->status);
        $this->assertNull($user->locked_reason);
        $this->assertSame('paid', $fine->status);
        $this->assertNotNull($fine->paid_at);
    }

    public function test_guest_cannot_access_profile_page(): void
    {
        $this->get(route('profile'))
            ->assertRedirect(route('login'));
    }

    public function test_guest_cannot_top_up_balance(): void
    {
        $this->post(route('wallet.topup'), [
            'amount' => 10000,
        ])->assertRedirect(route('login'));
    }

    public function test_top_up_rejects_amount_below_minimum(): void
    {
        $user = User::factory()->create([
            'role' => 'peminjam',
            'balance' => 25000,
        ]);

        $this->actingAs($user)
            ->from(route('profile'))
            ->post(route('wallet.topup'), [
                'amount' => 500,
            ])
            ->assertRedirect(route('profile'))
            ->assertSessionHasErrors('amount');

        $user->refresh();

        $this->assertSame('25000.00', $user->balance);
        $this->assertDatabaseMissing('activity_logs', [
            'user_id' => $user->id,
            'module' => 'wallet',
            'action' => 'top_up',
        ]);
    }

    public function test_top_up_rejects_empty_amount(): void
    {
        $user = User::factory()->create([
            'role' => 'peminjam',
            'balance' => 25000,
        ]);

        $this->actingAs($user)
            ->from(route('profile'))
            ->post(route('wallet.topup'), [])
            ->assertRedirect(route('profile'))
            ->assertSessionHasErrors('amount');

        $user->refresh();

        $this->assertSame('25000.00', $user->balance);
        $this->assertDatabaseMissing('activity_logs', [
            'user_id' => $user->id,
            'module' => 'wallet',
            'action' => 'top_up',
        ]);
    }

    public function test_top_up_rejects_non_numeric_amount(): void
    {
        $user = User::factory()->create([
            'role' => 'peminjam',
            'balance' => 25000,
        ]);

        $this->actingAs($user)
            ->from(route('profile'))
            ->post(route('wallet.topup'), [
                'amount' => 'abc',
            ])
            ->assertRedirect(route('profile'))
            ->assertSessionHasErrors('amount');

        $user->refresh();

        $this->assertSame('25000.00', $user->balance);
        $this->assertDatabaseMissing('activity_logs', [
            'user_id' => $user->id,
            'module' => 'wallet',
            'action' => 'top_up',
        ]);
    }
}
