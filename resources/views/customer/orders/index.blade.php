@extends('layouts.customer')
@section('title', 'Mes commandes')

@section('customer_content')
<h1 class="font-display text-3xl mb-6">Mes commandes</h1>

<div class="card divide-y divide-gray-100">
    @forelse ($orders as $order)
        <div class="p-5 flex flex-col md:flex-row md:items-center gap-3">
            <div class="flex-1">
                <div class="font-medium">{{ $order->reference }}</div>
                <div class="text-sm text-gray-500">{{ $order->created_at->translatedFormat('d F Y · H:i') }} · {{ $order->items->count() }} ebook(s)</div>
            </div>
            <span class="{{ $order->status->badgeClass() }}">{{ $order->status->label() }}</span>
            <div class="font-bold text-brand-800">{{ $order->formatted_total }}</div>
            <a href="{{ route('customer.orders.show', $order) }}" class="btn-outline text-sm">Détails</a>
        </div>
    @empty
        <div class="p-10 text-center text-gray-500">
            Aucune commande pour le moment.
            <a href="{{ route('ebooks.index') }}" class="text-brand-700 ml-2">Découvrir le catalogue →</a>
        </div>
    @endforelse
</div>
<div class="mt-6">{{ $orders->links() }}</div>
@endsection
