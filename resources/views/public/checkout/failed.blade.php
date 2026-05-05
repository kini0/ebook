@extends('layouts.app')
@section('title', 'Paiement échoué')

@section('content')
<section class="container-x py-16">
    <div class="card p-10 max-w-2xl mx-auto text-center">
        <div class="w-16 h-16 bg-red-100 text-red-700 rounded-full flex items-center justify-center mx-auto mb-5 text-3xl">✕</div>
        <h1 class="font-display text-3xl mb-3">Paiement refusé</h1>
        <p class="text-gray-600 mb-6">{{ session('error') ?? 'Une erreur est survenue lors du paiement. Vérifiez votre méthode de paiement et réessayez.' }}</p>
        <div class="flex justify-center gap-3">
            <a href="{{ route('cart.index') }}" class="btn-primary">Retourner au panier</a>
            <a href="{{ route('contact') }}" class="btn-outline">Contacter le support</a>
        </div>
    </div>
</section>
@endsection
