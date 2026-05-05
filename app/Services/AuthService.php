<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function register(array $data): User
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'phone'      => $data['phone'] ?? null,
            'country'    => $data['country'] ?? 'Côte d\'Ivoire',
            'city'       => $data['city'] ?? null,
            'address'    => $data['address'] ?? null,
            'role'       => UserRole::CUSTOMER->value,
            'password'   => Hash::make($data['password']),
            'is_active'  => true,
        ]);

        event(new Registered($user));

        return $user;
    }

    public function attemptLogin(array $credentials, bool $remember = false): User
    {
        $field = filter_var($credentials['email'] ?? null, FILTER_VALIDATE_EMAIL) ? 'email' : 'email';

        if (! Auth::attempt([
            $field      => $credentials['email'],
            'password'  => $credentials['password'],
            'is_active' => true,
        ], $remember)) {
            throw ValidationException::withMessages([
                'email' => __('Identifiants incorrects.'),
            ]);
        }

        $user = Auth::user();
        $user->forceFill(['last_login_at' => now()])->save();

        return $user;
    }

    public function logout(): void
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}
