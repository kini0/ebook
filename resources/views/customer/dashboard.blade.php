@extends('layouts.customer')
@section('title', 'Tableau de bord')

@section('customer_content')
<h1 class="font-display text-3xl mb-6">Bonjour {{ auth()->user()->first_name }} 👋</h1>

<div class="grid sm:grid-cols-3 gap-4 mb-8">
    <div class="card p-5">
        <div class="text-sm text-gray-500">Commandes</div>
        <div class="font-display text-3xl text-brand-800 mt-1">{{ $orderCount }}</div>
    </div>
    <div class="card p-5">
        <div class="text-sm text-gray-500">Téléchargements</div>
        <div class="font-display text-3xl text-gold-500 mt-1">{{ $downloadCount }}</div>
    </div>
    <div class="card p-5 bg-brand-900 text-white">
        <div class="text-sm text-brand-200">Plus à découvrir</div>
        <a href="{{ route('ebooks.index') }}" class="font-display text-xl text-gold-300 mt-1 inline-block">Voir le catalogue →</a>
    </div>
</div>

<h3 class="font-display text-xl mb-3">Commandes récentes</h3>
<div class="card divide-y divide-gray-100">
    @forelse ($recentOrders as $order)
        <div class="p-4 flex items-center justify-between gap-4">
            <div>
                <div class="font-medium">{{ $order->reference }}</div>
                <div class="text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }} · {{ $order->items->count() }} article(s)</div>
            </div>
            <div class="text-right">
                <span class="{{ $order->status->badgeClass() }}">{{ $order->status->label() }}</span>
                <div class="font-semibold mt-1">{{ $order->formatted_total }}</div>
            </div>
            <a href="{{ route('customer.orders.show', $order) }}" class="btn-ghost text-sm">Détails →</a>
        </div>
    @empty
        <div class="p-8 text-center text-gray-500">Aucune commande pour le moment.</div>
    @endforelse
</div>
@endsection
