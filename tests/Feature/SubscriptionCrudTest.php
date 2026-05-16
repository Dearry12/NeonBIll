<?php

namespace Tests\Feature;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('subscriptions.index'))
            ->assertRedirect(route('login'));
    }

    public function test_dashboard_lists_subscriptions(): void
    {
        Subscription::factory()->for($this->user)->create([
            'name' => 'Netflix',
            'next_due_date' => now()->addDay(),
        ]);

        $this->actingAs($this->user)
            ->withoutVite()
            ->get(route('subscriptions.index'))
            ->assertOk()
            ->assertSee('Netflix')
            ->assertSee('Monthly spend')
            ->assertSee('Display currency');
    }

    public function test_user_cannot_view_another_users_subscription(): void
    {
        $other = Subscription::factory()->create();

        $this->actingAs($this->user)
            ->get(route('subscriptions.show', $other))
            ->assertNotFound();
    }

    public function test_dashboard_switches_display_currency(): void
    {
        Subscription::factory()->for($this->user)->create([
            'price' => 16_000,
            'currency' => 'IDR',
            'billing_cycle' => 'Monthly',
            'is_active' => true,
        ]);

        Subscription::factory()->for($this->user)->create([
            'name' => 'Local Bill',
            'price' => 65_000,
            'currency' => 'IDR',
            'billing_cycle' => 'Monthly',
            'is_active' => true,
        ]);

        $this->actingAs($this->user)
            ->withoutVite()
            ->get(route('subscriptions.index', ['currency' => 'USD']))
            ->assertOk()
            ->assertSee('$ 1', false)
            ->assertSee('$ 4.0625', false)
            ->assertSee('Originally Rp 65.000');
    }

    public function test_can_view_subscription_details(): void
    {
        $subscription = Subscription::factory()->for($this->user)->create([
            'name' => 'Netflix',
            'currency' => 'USD',
            'price' => 15,
        ]);

        $this->actingAs($this->user)
            ->withoutVite()
            ->get(route('subscriptions.show', $subscription))
            ->assertOk()
            ->assertSee('Netflix')
            ->assertSee('Monthly equivalent')
            ->assertSee('Converted estimates');
    }

    public function test_can_create_subscription(): void
    {
        $payload = [
            'name' => 'Disney+',
            'category' => 'Streaming',
            'price' => 79900,
            'currency' => 'IDR',
            'billing_cycle' => 'Monthly',
            'next_due_date' => now()->addWeek()->format('Y-m-d'),
            'is_active' => '1',
        ];

        $this->actingAs($this->user)
            ->post(route('subscriptions.store'), $payload)
            ->assertRedirect(route('subscriptions.index'));

        $this->assertDatabaseHas('subscriptions', [
            'user_id' => $this->user->id,
            'name' => 'Disney+',
            'price' => 79900,
            'currency' => 'IDR',
        ]);
    }

    public function test_create_validates_required_fields(): void
    {
        $this->actingAs($this->user)
            ->post(route('subscriptions.store'), [])
            ->assertSessionHasErrors(['name', 'category', 'price', 'currency', 'billing_cycle', 'next_due_date']);
    }

    public function test_can_update_subscription(): void
    {
        $subscription = Subscription::factory()->for($this->user)->create([
            'price' => 50000,
            'currency' => 'IDR',
        ]);

        $this->actingAs($this->user)
            ->put(route('subscriptions.update', $subscription), [
                'name' => $subscription->name,
                'category' => 'Software',
                'price' => 75000,
                'currency' => 'USD',
                'billing_cycle' => 'Monthly',
                'next_due_date' => $subscription->next_due_date->format('Y-m-d'),
                'is_active' => '1',
            ])->assertRedirect(route('subscriptions.index'));

        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'user_id' => $this->user->id,
            'price' => 75000,
            'currency' => 'USD',
        ]);
    }

    public function test_can_delete_subscription(): void
    {
        $subscription = Subscription::factory()->for($this->user)->create();

        $this->actingAs($this->user)
            ->delete(route('subscriptions.destroy', $subscription))
            ->assertRedirect(route('subscriptions.index'));

        $this->assertDatabaseMissing('subscriptions', ['id' => $subscription->id]);
    }
}
