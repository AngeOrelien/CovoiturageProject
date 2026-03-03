<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Hash;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_page_is_accessible_for_guests(): void
    {
        $this->get(route('register'))->assertStatus(200);
    }

    public function test_register_page_is_redirected_for_authenticated_users(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('register'))
            ->assertRedirect(route('dashboard'));
    }

    public function test_user_can_register_with_valid_data(): void
    {
        $response = $this->post(route('register'), [
            'first_name'            => 'Jean',
            'last_name'             => 'Dupont',
            'email'                 => 'jean@test.cm',
            'password'              => 'Password1!',
            'password_confirmation' => 'Password1!',
            'phone'                 => '+237690000000',
            'gender'                => 'male',
            'bio'                   => 'Je suis un testeur.',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('users', ['email' => 'jean@test.cm']);
        $this->assertAuthenticated();
    }

    public function test_wallet_is_created_automatically_on_register(): void
    {
        $this->post(route('register'), [
            'first_name'            => 'Marie',
            'last_name'             => 'Nguembo',
            'email'                 => 'marie@test.cm',
            'password'              => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);

        $user = User::where('email', 'marie@test.cm')->first();
        $this->assertNotNull($user);
        $this->assertDatabaseHas('wallets', ['user_id' => $user->id, 'balance' => 0]);
    }

    public function test_register_requires_first_name(): void
    {
        $this->post(route('register'), [
            'last_name'             => 'Dupont',
            'email'                 => 'jean@test.cm',
            'password'              => 'Password1!',
            'password_confirmation' => 'Password1!',
        ])->assertSessionHasErrors('first_name');
    }

    public function test_register_requires_last_name(): void
    {
        $this->post(route('register'), [
            'first_name'            => 'Jean',
            'email'                 => 'jean@test.cm',
            'password'              => 'Password1!',
            'password_confirmation' => 'Password1!',
        ])->assertSessionHasErrors('last_name');
    }

    public function test_register_requires_valid_email(): void
    {
        $this->post(route('register'), [
            'first_name'            => 'Jean',
            'last_name'             => 'Dupont',
            'email'                 => 'not-an-email',
            'password'              => 'Password1!',
            'password_confirmation' => 'Password1!',
        ])->assertSessionHasErrors('email');
    }

    public function test_register_rejects_duplicate_email(): void
    {
        User::factory()->create(['email' => 'jean@test.cm']);

        $this->post(route('register'), [
            'first_name'            => 'Jean',
            'last_name'             => 'Dupont',
            'email'                 => 'jean@test.cm',
            'password'              => 'Password1!',
            'password_confirmation' => 'Password1!',
        ])->assertSessionHasErrors('email');
    }

    public function test_register_requires_password_confirmation(): void
    {
        $this->post(route('register'), [
            'first_name'            => 'Jean',
            'last_name'             => 'Dupont',
            'email'                 => 'jean@test.cm',
            'password'              => 'Password1!',
            'password_confirmation' => 'WrongPassword1!',
        ])->assertSessionHasErrors('password');
    }

    public function test_register_requires_strong_password(): void
    {
        $this->post(route('register'), [
            'first_name'            => 'Jean',
            'last_name'             => 'Dupont',
            'email'                 => 'jean@test.cm',
            'password'              => 'weakpass',
            'password_confirmation' => 'weakpass',
        ])->assertSessionHasErrors('password');
    }

    public function test_register_password_is_hashed(): void
    {
        $this->post(route('register'), [
            'first_name'            => 'Jean',
            'last_name'             => 'Dupont',
            'email'                 => 'jean@test.cm',
            'password'              => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);

        $user = User::where('email', 'jean@test.cm')->first();
        $this->assertTrue(Hash::check('Password1!', $user->password));
        $this->assertNotEquals('Password1!', $user->password);
    }

    public function test_new_user_has_passenger_role_by_default(): void
    {
        $this->post(route('register'), [
            'first_name'            => 'Jean',
            'last_name'             => 'Dupont',
            'email'                 => 'jean@test.cm',
            'password'              => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);

        $user = User::where('email', 'jean@test.cm')->first();
        $this->assertEquals('passenger', $user->role);
    }

    public function test_register_shows_success_toast(): void
    {
        $this->post(route('register'), [
            'first_name'            => 'Jean',
            'last_name'             => 'Dupont',
            'email'                 => 'jean@test.cm',
            'password'              => 'Password1!',
            'password_confirmation' => 'Password1!',
        ])->assertSessionHas('toast.type', 'success');
    }

    public function test_login_page_is_accessible_for_guests(): void
    {
        $this->get(route('login'))->assertStatus(200);
    }

    public function test_login_page_is_redirected_for_authenticated_users(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('login'))
            ->assertRedirect(route('dashboard'));
    }

    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'email'    => 'jean@test.cm',
            'password' => Hash::make('Password1!'),
        ]);

        $this->post(route('login'), [
            'email'    => 'jean@test.cm',
            'password' => 'Password1!',
        ])
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        User::factory()->create([
            'email'    => 'jean@test.cm',
            'password' => Hash::make('Password1!'),
        ]);

        $this->post(route('login'), [
            'email'    => 'jean@test.cm',
            'password' => 'WrongPassword!',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_fails_with_nonexistent_email(): void
    {
        $this->post(route('login'), [
            'email'    => 'ghost@test.cm',
            'password' => 'Password1!',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_requires_email(): void
    {
        $this->post(route('login'), [
            'password' => 'Password1!',
        ])->assertSessionHasErrors('email');
    }

    public function test_login_requires_password(): void
    {
        $this->post(route('login'), [
            'email' => 'jean@test.cm',
        ])->assertSessionHasErrors('password');
    }

    public function test_login_shows_success_toast(): void
    {
        User::factory()->create([
            'email'    => 'jean@test.cm',
            'password' => Hash::make('Password1!'),
        ]);

        $this->post(route('login'), [
            'email'    => 'jean@test.cm',
            'password' => 'Password1!',
        ])->assertSessionHas('toast.type', 'success');
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_logout_shows_info_toast(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertSessionHas('toast.type', 'info');
    }

    public function test_guest_cannot_access_dashboard(): void
    {
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertStatus(200);
    }

    public function test_logout_requires_post_method(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/logout')
            ->assertStatus(405);
    }
}
