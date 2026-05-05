@extends('layouts.app')
@section('title', 'Paiement réussi')

@section('content')
<section class="container-x py-16">
    <div class="card p-10 max-w-2xl mx-auto text-center">
        <div class="w-16 h-16 bg-green-100 text-green-700 rounded-full flex items-center justify-center mx-auto mb-5 text-3xl">✓</div>
        <h1 class="font-display text-3xl mb-3">Paiement confirmé</h1>
        <p class="text-gray-600 mb-2">Merci pour votre achat. Votre commande <strong>{{ $order->reference }}</strong> est validée.</p>
        <p class="text-gray-600 mb-6">Un email de confirmation et la facture vous ont été envoyés.</p>
        <div class="flex justify-center gap-3">
            <a href="{{ route('customer.downloads.index') }}" class="btn-gold">Télécharger mes ebooks</a>
            <a href="{{ route('customer.orders.show', $order) }}" class="btn-outline">Voir la commande</a>
        </div>
    </div>
</section>
@endsection
