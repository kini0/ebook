<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@ebooksaas.test'],
            [
                'first_name'        => 'Super',
                'last_name'         => 'Admin',
                'phone'             => '+225 07 00 00 00 00',
                'country'           => 'Côte d\'Ivoire',
                'city'              => 'Abidjan',
                'role'              => UserRole::ADMIN->value,
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active'         => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'client@ebooksaas.test'],
            [
                'first_name'        => 'Jean',
                'last_name'         => 'Dupont',
                'phone'             => '+225 07 11 22 33 44',
                'country'           => 'Côte d\'Ivoire',
                'city'              => 'Abidjan',
                'role'              => UserRole::CUSTOMER->value,
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active'         => true,
            ]
        );

        User::factory()->count(15)->create();
    }
}
