@extends('layouts.app')
@section('title', 'Accueil')

@section('content')
{{-- HERO (AIDA — Attention) --}}
<section class="bg-gradient-to-br from-brand-900 via-brand-800 to-brand-700 text-white relative overflow-hidden">
    <div class="container-x py-20 md:py-28 relative z-10">
        <div class="max-w-3xl">
            <span class="badge-gold mb-4">Bibliothèque premium</span>
            <h1 class="font-display text-4xl md:text-6xl leading-tight mb-6">
                La connaissance qui transforme,
                <span class="text-gold-400">à portée de clic.</span>
            </h1>
            <p class="text-lg md:text-xl text-brand-100 mb-8 max-w-2xl">
                Découvrez une collection soigneusement sélectionnée d'ebooks signés par des experts.
                Téléchargement instantané · Paiement sécurisé · Sans abonnement.
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('ebooks.index') }}" class="btn-gold text-base px-7 py-3">Explorer le catalogue</a>
                <a href="{{ route('about') }}" class="btn-outline text-base px-7 py-3 border-white text-white hover:bg-white/10">En savoir plus</a>
            </div>
            <div class="flex gap-8 mt-10 text-sm text-brand-200">
                <div><span class="text-2xl text-gold-400 font-bold">+200</span><br>Ebooks premium</div>
                <div><span class="text-2xl text-gold-400 font-bold">+5 000</span><br>Lecteurs satisfaits</div>
                <div><span class="text-2xl text-gold-400 font-bold">100%</span><br>Téléchargement instantané</div>
            </div>
        </div>
    </div>
</section>

{{-- Categories --}}
<section class="container-x py-14">
    <div class="flex items-end justify-between mb-6">
        <h2 class="font-display text-3xl">Parcourir par catégorie</h2>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-3">
        @foreach ($categories as $cat)
            <a href="{{ route('ebooks.index', ['category' => $cat->slug]) }}"
               class="card px-4 py-5 text-center hover:border-gold-400 transition">
                <div class="text-sm font-medium">{{ $cat->name }}</div>
            </a>
        @endforeach
    </div>
</section>

{{-- Featured (AIDA — Interest) --}}
<section class="container-x py-10">
    <div class="flex items-end justify-between mb-6">
        <div>
            <h2 class="font-display text-3xl">Sélection de la rédaction</h2>
            <p class="text-gray-600 mt-1">Les titres incontournables choisis par notre équipe.</p>
        </div>
        <a href="{{ route('ebooks.index') }}" class="text-brand-700 font-medium text-sm">Voir tout →</a>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-5">
        @foreach ($featured as $ebook)
            <x-ebook-card :ebook="$ebook" />
        @endforeach
    </div>
</section>

{{-- Bestsellers (AIDA — Desire) --}}
<section class="bg-brand-50 py-14 mt-10">
    <div class="container-x">
        <h2 class="font-display text-3xl mb-6">Meilleures ventes</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-5">
            @foreach ($bestsellers as $ebook)
                <x-ebook-card :ebook="$ebook" />
            @endforeach
        </div>
    </div>
</section>

{{-- Trust / Why us --}}
<section class="container-x py-16">
    <div class="grid md:grid-cols-3 gap-6">
        <div class="card p-6">
            <div class="text-gold-500 font-bold text-lg mb-2">Téléchargement instantané</div>
            <p class="text-gray-600">Dès le paiement validé, vos ebooks sont disponibles dans votre espace client.</p>
        </div>
        <div class="card p-6">
            <div class="text-gold-500 font-bold text-lg mb-2">Paiement 100 % sécurisé</div>
            <p class="text-gray-600">Carte bancaire, Orange Money, MTN MoMo, Wave : choisissez votre méthode favorite.</p>
        </div>
        <div class="card p-6">
            <div class="text-gold-500 font-bold text-lg mb-2">Support réactif</div>
            <p class="text-gray-600">Une équipe à votre écoute, qui répond sous 24 h via le formulaire de contact.</p>
        </div>
    </div>
</section>

{{-- CTA (AIDA — Action) --}}
<section class="container-x pb-20">
    <div class="card bg-brand-900 text-white p-10 md:p-14 text-center relative overflow-hidden">
        <h2 class="font-display text-3xl md:text-4xl mb-3">Prêt à enrichir votre bibliothèque ?</h2>
        <p class="text-brand-100 mb-7">Inscrivez-vous gratuitement et accédez à des promotions exclusives.</p>
        <a href="{{ route('register') }}" class="btn-gold text-base px-8 py-3">Créer mon compte</a>
    </div>
</section>
@endsection
