<?php

namespace App\Services;

use App\DTOs\CheckoutData;
use App\Enums\OrderStatus;
use App\Events\OrderPlaced;
use App\Models\Ebook;
use App\Models\Order;
use App\Models\User;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        protected OrderRepositoryInterface $orders,
    ) {}

    /**
     * Atomically create an Order with its items, snapshotted from current ebook prices.
     */
    public function createFromCheckout(User $user, CheckoutData $data): Order
    {
        return DB::transaction(function () use ($user, $data) {
            $subtotal = 0;
            $items = [];

            foreach ($data->items as $line) {
                /** @var Ebook $ebook */
                $ebook = Ebook::query()->lockForUpdate()->findOrFail($line['ebook_id']);
                $unit  = (int) $ebook->price_cents;
                $qty   = max(1, (int) ($line['quantity'] ?? 1));
                $total = $unit * $qty;
                $subtotal += $total;

                $items[] = [
                    'ebook_id'         => $ebook->id,
                    'title_snapshot'   => $ebook->title,
                    'author_snapshot'  => $ebook->author,
                    'unit_price_cents' => $unit,
                    'quantity'         => $qty,
                    'total_cents'      => $total,
                ];
            }

            $taxRate = (int) (\App\Models\Setting::get('tax_rate', 0));
            $tax     = (int) round($subtotal * $taxRate / 100);

            $order = Order::create([
                'user_id'         => $user->id,
                'billing_email'   => $data->billingEmail,
                'billing_name'    => $data->billingName,
                'billing_phone'   => $data->billingPhone,
                'billing_country' => $data->billingCountry,
                'billing_city'    => $data->billingCity,
                'billing_address' => $data->billingAddress,
                'status'          => OrderStatus::PENDING->value,
                'subtotal_cents'  => $subtotal,
                'tax_cents'       => $tax,
                'total_cents'     => $subtotal + $tax,
                'payment_method'  => $data->paymentMethod,
                'placed_at'       => now(),
            ]);

            $order->items()->createMany($items);

            event(new OrderPlaced($order));

            return $order->fresh(['items.ebook']);
        });
    }

    public function markAsPaid(Order $order): Order
    {
        if ($order->isPaid()) {
            return $order;
        }

        $order->update([
            'status'  => OrderStatus::PAID->value,
            'paid_at' => now(),
        ]);

        event(new \App\Events\PaymentSucceeded($order));

        return $order->refresh();
    }

    public function markAsFailed(Order $order, ?string $reason = null): Order
    {
        $order->update([
            'status' => OrderStatus::FAILED->value,
            'notes'  => $reason ? trim(($order->notes ? $order->notes . "\n" : '') . $reason) : $order->notes,
        ]);

        event(new \App\Events\PaymentFailed($order, $reason));

        return $order->refresh();
    }

    public function cancel(Order $order): Order
    {
        $order->update([
            'status'       => OrderStatus::CANCELLED->value,
            'cancelled_at' => now(),
        ]);
        return $order->refresh();
    }
}
