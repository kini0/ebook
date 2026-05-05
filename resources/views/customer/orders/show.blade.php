@extends('layouts.customer')
@section('title', 'Commande ' . $order->reference)

@section('customer_content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="font-display text-2xl">Commande {{ $order->reference }}</h1>
        <div class="text-gray-500 text-sm">{{ $order->created_at->translatedFormat('d F Y') }}</div>
    </div>
    <span class="{{ $order->status->badgeClass() }}">{{ $order->status->label() }}</span>
</div>

<div class="grid lg:grid-cols-[1fr_320px] gap-6">
    <div class="card p-5">
        <h3 class="font-display text-lg mb-3">Articles</h3>
        <div class="divide-y divide-gray-100">
            @foreach ($order->items as $item)
                <div class="py-3 flex items-center justify-between gap-3">
                    <div>
                        <div class="font-medium">{{ $item->title_snapshot }}</div>
                        <div class="text-sm text-gray-500">{{ $item->author_snapshot }}</div>
                    </div>
                    <div class="text-right">
                        <div class="font-semibold">{{ $item->formatted_total }}</div>
                        @if ($order->isPaid())
                            <a href="{{ route('customer.downloads.request', [$order, $item->ebook]) }}" class="text-sm text-brand-700 hover:underline">Télécharger</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <aside class="space-y-4">
        <div class="card p-5">
            <h4 class="font-display text-base mb-3">Récapitulatif</h4>
            <div class="text-sm space-y-1">
                <div class="flex justify-between"><span>Sous-total</span><span>{{ $order->formatted_subtotal }}</span></div>
                <div class="flex justify-between text-gray-500"><span>TVA</span><span>{{ number_format($order->tax_cents, 0, ',', ' ') }} FCFA</span></div>
                <div class="flex justify-between font-bold border-t border-gray-100 pt-2 mt-2 text-base">
                    <span>Total</span><span class="text-brand-800">{{ $order->formatted_total }}</span>
                </div>
            </div>
        </div>

        @if ($order->invoice)
            <div class="card p-5">
                <h4 class="font-display text-base mb-2">Facture</h4>
                <div class="text-sm text-gray-600 mb-3">{{ $order->invoice->number }}</div>
                <a href="{{ route('customer.invoices.download', $order->invoice) }}" class="btn-gold w-full">Télécharger la facture</a>
            </div>
        @endif

        <div class="card p-5">
            <h4 class="font-display text-base mb-2">Méthode de paiement</h4>
            <div class="text-sm text-gray-700">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</div>
        </div>
    </aside>
</div>
@endsection
