<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password = null;

    public function definition(): array
    {
        return [
            'first_name'        => $this->faker->firstName(),
            'last_name'         => $this->faker->lastName(),
            'email'             => $this->faker->unique()->safeEmail(),
            'phone'             => '+225 0' . $this->faker->numberBetween(1, 9) . ' ' . $this->faker->numerify('## ## ## ##'),
            'country'           => 'Côte d\'Ivoire',
            'city'              => $this->faker->city(),
            'address'           => $this->faker->streetAddress(),
            'role'              => UserRole::CUSTOMER->value,
            'email_verified_at' => now(),
            'password'          => static::$password ??= Hash::make('password'),
            'is_active'         => true,
            'remember_token'    => Str::random(10),
        ];
    }

    public function admin(): self
    {
        return $this->state(['role' => UserRole::ADMIN->value]);
    }

    public function unverified(): self
    {
        return $this->state(['email_verified_at' => null]);
    }
}
