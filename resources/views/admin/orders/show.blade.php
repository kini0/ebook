@extends('layouts.admin')
@section('title', $order->reference)
@section('page_title', 'Commande ' . $order->reference)

@section('content')
<div class="grid lg:grid-cols-[1fr_320px] gap-6">
    <div class="card p-5">
        <h3 class="font-display text-lg mb-3">Articles</h3>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left">
                <tr><th class="px-3 py-2">Titre</th><th class="px-3 py-2">Auteur</th><th class="px-3 py-2 text-right">Total</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($order->items as $i)
                    <tr>
                        <td class="px-3 py-2">{{ $i->title_snapshot }}</td>
                        <td class="px-3 py-2">{{ $i->author_snapshot }}</td>
                        <td class="px-3 py-2 text-right">{{ $i->formatted_total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h3 class="font-display text-lg mt-8 mb-3">Transactions</h3>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left">
                <tr><th class="px-3 py-2">Référence</th><th>Passerelle</th><th>Statut</th><th>Montant</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($order->transactions as $tx)
                    <tr>
                        <td class="px-3 py-2"><a href="{{ route('admin.transactions.show', $tx) }}" class="text-brand-700">{{ $tx->reference }}</a></td>
                        <td>{{ $tx->gateway }}</td>
                        <td>{{ $tx->status?->value }}</td>
                        <td>{{ number_format($tx->amount_cents, 0, ',', ' ') }} FCFA</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <aside class="space-y-4">
        <div class="card p-5">
            <h4 class="font-display text-base mb-2">Statut</h4>
            <span class="{{ $order->status->badgeClass() }}">{{ $order->status->label() }}</span>
            <div class="text-sm text-gray-500 mt-2">Total : <strong class="text-brand-800">{{ $order->formatted_total }}</strong></div>
        </div>
        <div class="card p-5 space-y-2">
            <h4 class="font-display text-base mb-1">Actions</h4>
            @if (! $order->isPaid())
                <form method="POST" action="{{ route('admin.orders.markPaid', $order) }}">
                    @csrf <button class="btn-gold w-full">Marquer comme payée</button>
                </form>
            @endif
            <form method="POST" action="{{ route('admin.orders.cancel', $order) }}">
                @csrf <button class="btn-outline w-full">Annuler la commande</button>
            </form>
        </div>
        <div class="card p-5">
            <h4 class="font-display text-base mb-2">Client</h4>
            <div>{{ $order->billing_name }}</div>
            <div class="text-sm text-gray-600">{{ $order->billing_email }}</div>
            <div class="text-sm text-gray-600">{{ $order->billing_phone }}</div>
            <div class="text-sm text-gray-600">{{ $order->billing_address }}, {{ $order->billing_city }} ({{ $order->billing_country }})</div>
        </div>
    </aside>
</div>
@endsection
