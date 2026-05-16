<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $this->withoutVite()
            ->post(route('register'), [
                'name' => 'Jane Doe',
                'email' => 'jane@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
            ])
            ->assertRedirect(route('subscriptions.index'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'jane@example.com']);
    }

    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => 'secret-password',
        ]);

        $this->withoutVite()
            ->post(route('login'), [
                'email' => 'login@example.com',
                'password' => 'secret-password',
            ])
            ->assertRedirect(route('subscriptions.index'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_authenticated_user_is_redirected_from_login(): void
    {
        $this->actingAs(User::factory()->create())
            ->withoutVite()
            ->get(route('login'))
            ->assertRedirect(route('subscriptions.index'));
    }
}
