@extends('layouts.app')
@section('title', 'Paiement en attente')

@section('content')
<section class="container-x py-16">
    <div class="card p-10 max-w-2xl mx-auto text-center">
        <div class="w-16 h-16 bg-amber-100 text-amber-700 rounded-full flex items-center justify-center mx-auto mb-5 text-3xl">⏳</div>
        <h1 class="font-display text-3xl mb-3">Paiement en cours de validation</h1>
        <p class="text-gray-600 mb-2">Votre commande <strong>{{ $order->reference }}</strong> est en attente de confirmation.</p>
        <p class="text-gray-600 mb-6">
            Si vous payez par <strong>Mobile Money</strong>, validez l'opération sur votre téléphone via le code USSD.
            Pour un <strong>virement bancaire</strong>, utilisez la référence ci-dessus comme libellé.
        </p>
        <a href="{{ route('customer.orders.show', $order) }}" class="btn-primary">Suivre ma commande</a>
    </div>
</section>
@endsection
