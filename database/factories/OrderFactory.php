<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id'        => User::factory(),
            'billing_name'   => $this->faker->name(),
            'billing_email'  => $this->faker->safeEmail(),
            'status'         => OrderStatus::PENDING->value,
            'subtotal_cents' => 5000,
            'tax_cents'      => 0,
            'total_cents'    => 5000,
            'currency'       => 'XOF',
            'payment_method' => 'bank_transfer',
            'placed_at'      => now(),
        ];
    }
}
