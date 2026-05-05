@extends('layouts.app')
@section('title', 'FAQ')

@section('content')
<section class="container-x py-16 max-w-3xl">
    <h1 class="font-display text-4xl mb-8">Questions fréquentes</h1>

    @php
        $faqs = [
            ['Comment recevoir mon ebook après l\'achat ?',
             'Dès le paiement validé, votre ebook est disponible dans votre espace client, onglet « Mes téléchargements ». Un email de confirmation contenant la facture est également envoyé.'],
            ['Quels moyens de paiement acceptez-vous ?',
             'Carte bancaire (Visa, Mastercard), Mobile Money (Orange Money, MTN MoMo, Wave) et virement bancaire pour les commandes supérieures à 25 000 FCFA.'],
            ['Le téléchargement est-il limité ?',
             'Pour des raisons de sécurité, chaque ebook peut être téléchargé jusqu\'à 10 fois par commande. Les liens sont signés et expirent au bout de 15 minutes.'],
            ['Puis-je obtenir un remboursement ?',
             'En raison de la nature numérique du produit, les ventes sont définitives. En cas de problème technique, contactez-nous : nous ferons le nécessaire.'],
            ['Mes données sont-elles protégées ?',
             'Oui. Nous ne stockons jamais vos coordonnées bancaires et utilisons des passerelles de paiement certifiées PCI-DSS.'],
        ];
    @endphp

    <div class="space-y-3" x-data>
        @foreach ($faqs as [$q, $a])
            <details class="card p-4 group">
                <summary class="cursor-pointer font-medium flex items-center justify-between">
                    <span>{{ $q }}</span>
                    <span class="text-gold-500 group-open:rotate-45 transition">＋</span>
                </summary>
                <p class="text-gray-700 mt-3">{{ $a }}</p>
            </details>
        @endforeach
    </div>
</section>
@endsection
