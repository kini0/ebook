@extends('layouts.customer')
@section('title', 'Mes téléchargements')

@section('customer_content')
<h1 class="font-display text-3xl mb-6">Mes téléchargements</h1>

@forelse ($orders as $order)
    <div class="card p-5 mb-4">
        <div class="flex justify-between items-center mb-3">
            <div>
                <div class="font-medium">Commande {{ $order->reference }}</div>
                <div class="text-sm text-gray-500">{{ $order->created_at->translatedFormat('d F Y') }}</div>
            </div>
            <span class="{{ $order->status->badgeClass() }}">{{ $order->status->label() }}</span>
        </div>
        <div class="divide-y divide-gray-100">
            @foreach ($order->items as $item)
                <div class="py-3 flex items-center justify-between gap-3">
                    <div>
                        <div class="font-medium">{{ $item->title_snapshot }}</div>
                        <div class="text-sm text-gray-500">{{ $item->author_snapshot }} · {{ strtoupper($item->ebook->file_format ?? 'PDF') }}</div>
                    </div>
                    <a href="{{ route('customer.downloads.request', [$order, $item->ebook]) }}" class="btn-gold text-sm">Télécharger</a>
                </div>
            @endforeach
        </div>
    </div>
@empty
    <div class="card p-10 text-center text-gray-500">
        Aucun téléchargement disponible. Une fois une commande payée, vos ebooks apparaîtront ici.
    </div>
@endforelse

<div class="mt-6">{{ $orders->links() }}</div>
@endsection
