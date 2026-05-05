<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_renders(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Bienvenue');
    }

    public function test_user_can_register(): void
    {
        $payload = [
            'first_name'            => 'Awa',
            'last_name'             => 'Diop',
            'email'                 => 'awa@example.test',
            'phone'                 => '+225 07 11 22 33 44',
            'password'              => 'Password1!',
            'password_confirmation' => 'Password1!',
            'terms'                 => '1',
        ];

        $this->post(route('register'), $payload)
            ->assertRedirect(route('verification.notice'));

        $this->assertDatabaseHas('users', ['email' => 'awa@example.test', 'role' => 'customer']);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create(['email' => 'jean@test.fr']);

        $this->post(route('login'), [
            'email'    => 'jean@test.fr',
            'password' => 'password',
        ])->assertRedirect();

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        User::factory()->create(['email' => 'jean@test.fr']);

        $this->from(route('login'))
            ->post(route('login'), [
                'email'    => 'jean@test.fr',
                'password' => 'wrong-password',
            ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post(route('logout'))->assertRedirect(route('home'));
        $this->assertGuest();
    }
}
