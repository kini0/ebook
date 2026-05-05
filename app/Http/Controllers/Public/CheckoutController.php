<?php

namespace App\Http\Controllers\Public;

use App\DTOs\CheckoutData;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Payments\PaymentManager;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cart,
        protected OrderService $orderService,
        protected PaymentService $paymentService,
        protected PaymentManager $paymentManager,
    ) {}

    public function index()
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Votre panier est vide.');
        }

        return view('public.checkout.index', [
            'items'           => $this->cart->items(),
            'subtotal'        => $this->cart->subtotalCents(),
            'paymentMethods'  => $this->paymentManager->available(),
            'mobileOperators' => config('payment.drivers.mobile_money.operators', []),
        ]);
    }

    public function process(CheckoutRequest $request)
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        $items = array_map(
            fn ($it) => ['ebook_id' => $it['ebook_id'], 'quantity' => $it['quantity'], 'unit_price_cents' => $it['unit_price_cents']],
            $this->cart->items()
        );

        $data = new CheckoutData(
            billingName:    $request->input('billing_name'),
            billingEmail:   $request->input('billing_email'),
            billingPhone:   $request->input('billing_phone'),
            billingCountry: $request->input('billing_country'),
            billingCity:    $request->input('billing_city'),
            billingAddress: $request->input('billing_address'),
            paymentMethod:  $request->input('payment_method'),
            paymentPayload: $request->paymentPayload(),
            items:          $items,
        );

        $order = $this->orderService->createFromCheckout($request->user(), $data);

        $payment = $this->paymentService->initiate($order, $data->paymentMethod, $data->paymentPayload);
        $result  = $payment['result'];

        $this->cart->clear();

        if ($result->shouldRedirect()) {
            return redirect()->away($result->redirectUrl);
        }
        if ($result->isSuccessful()) {
            return redirect()->route('checkout.success', $order->reference);
        }
        if ($result->isPending()) {
            return redirect()->route('checkout.pending', $order->reference);
        }
        return redirect()->route('checkout.failed', $order->reference)
            ->with('error', $result->message ?? 'Paiement refusé.');
    }

    public function success(Order $order, Request $request)
    {
        abort_unless($order->user_id === $request->user()?->id, 403);
        return view('public.checkout.success', compact('order'));
    }

    public function pending(Order $order, Request $request)
    {
        abort_unless($order->user_id === $request->user()?->id, 403);
        return view('public.checkout.pending', compact('order'));
    }

    public function failed(?Order $order = null, Request $request)
    {
        return view('public.checkout.failed', compact('order'));
    }
}
