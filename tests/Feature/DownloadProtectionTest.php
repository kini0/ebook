<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\Ebook;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DownloadProtectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_unsigned_download_is_rejected(): void
    {
        $user  = User::factory()->create(['email_verified_at' => now()]);
        $cat   = Category::factory()->create();
        $ebook = Ebook::factory()->state(['category_id' => $cat->id])->create();
        $order = Order::factory()->for($user)->state(['status' => OrderStatus::PAID->value])->create();

        $this->actingAs($user)
            ->get("/d/{$order->reference}/{$ebook->slug}")
            ->assertForbidden();
    }

    public function test_unauthorized_user_cannot_access_others_orders(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create(['email_verified_at' => now()]);
        $order = Order::factory()->for($owner)->state(['status' => OrderStatus::PAID->value])->create();

        $this->actingAs($other)
            ->get(route('customer.orders.show', $order))
            ->assertForbidden();
    }
}
