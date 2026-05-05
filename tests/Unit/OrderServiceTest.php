<?php

namespace Tests\Unit;

use App\DTOs\CheckoutData;
use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\Ebook;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_service_creates_order_with_correct_totals(): void
    {
        $user  = User::factory()->create();
        $cat   = Category::factory()->create();
        $a     = Ebook::factory()->state(['category_id' => $cat->id, 'price_cents' => 3000])->create();
        $b     = Ebook::factory()->state(['category_id' => $cat->id, 'price_cents' => 5500])->create();

        $service = app(OrderService::class);

        $data = new CheckoutData(
            billingName:    $user->full_name,
            billingEmail:   $user->email,
            billingPhone:   null,
            billingCountry: null,
            billingCity:    null,
            billingAddress: null,
            paymentMethod:  'bank_transfer',
            paymentPayload: [],
            items: [
                ['ebook_id' => $a->id, 'quantity' => 1, 'unit_price_cents' => 3000],
                ['ebook_id' => $b->id, 'quantity' => 1, 'unit_price_cents' => 5500],
            ],
        );

        $order = $service->createFromCheckout($user, $data);

        $this->assertSame(OrderStatus::PENDING, $order->status);
        $this->assertSame(8500, $order->subtotal_cents);
        $this->assertSame(8500, $order->total_cents);
        $this->assertCount(2, $order->items);
        $this->assertNotEmpty($order->reference);
    }
}
