<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\Ebook;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_creates_order_with_snapshot(): void
    {
        $user  = User::factory()->create(['email_verified_at' => now()]);
        $cat   = Category::factory()->create();
        $ebook = Ebook::factory()->state(['category_id' => $cat->id, 'price_cents' => 7500])->create();

        $this->actingAs($user);

        // Add to cart
        app(CartService::class)->add($ebook);

        $response = $this->post(route('checkout.process'), [
            'billing_name'   => $user->full_name,
            'billing_email'  => $user->email,
            'payment_method' => 'bank_transfer',
            'terms'          => '1',
        ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'status'  => OrderStatus::PENDING->value,
            'total_cents' => 7500,
        ]);

        $this->assertDatabaseHas('order_items', [
            'ebook_id'         => $ebook->id,
            'unit_price_cents' => 7500,
            'title_snapshot'   => $ebook->title,
        ]);
    }
}
