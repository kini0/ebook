@extends('layouts.app')
@section('title', 'À propos')

@section('content')
<section class="container-x py-16">
    <div class="max-w-3xl">
        <span class="badge-gold mb-3">Notre mission</span>
        <h1 class="font-display text-4xl mb-6">Démocratiser l'accès au savoir premium.</h1>
        <div class="prose max-w-none text-gray-700">
            <p>{{ config('app.name') }} est née d'une conviction simple : un excellent livre numérique doit être accessible, partout, en quelques secondes — sans abonnement, sans friction, sans intermédiaire.</p>
            <p>Nous travaillons avec des auteurs et des experts pour publier une sélection rigoureuse d'ebooks dans les domaines du développement personnel, de l'entrepreneuriat, de la finance, de la technologie et plus.</p>
            <h3>Pourquoi nous choisir ?</h3>
            <ul>
                <li><strong>Curation premium :</strong> chaque titre est sélectionné pour sa qualité et son impact.</li>
                <li><strong>Téléchargement instantané :</strong> votre achat est disponible immédiatement.</li>
                <li><strong>Paiement local :</strong> Mobile Money, carte bancaire, virement — vous choisissez.</li>
                <li><strong>Support humain :</strong> une équipe à votre écoute, sous 24 heures.</li>
            </ul>
        </div>
    </div>
</section>
@endsection
