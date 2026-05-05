<footer class="bg-brand-900 text-brand-100 mt-20">
    <div class="container-x py-12 grid sm:grid-cols-2 md:grid-cols-4 gap-8 text-sm">
        <div>
            <div class="font-display text-xl text-gold-400 mb-3">{{ config('app.name') }}</div>
            <p class="text-brand-200">{{ env('BRAND_TAGLINE', 'La bibliothèque numérique premium.') }}</p>
        </div>
        <div>
            <div class="font-semibold text-white mb-3">Navigation</div>
            <ul class="space-y-1.5">
                <li><a href="{{ route('home') }}"         class="hover:text-gold-400">Accueil</a></li>
                <li><a href="{{ route('ebooks.index') }}" class="hover:text-gold-400">Catalogue</a></li>
                <li><a href="{{ route('about') }}"        class="hover:text-gold-400">À propos</a></li>
                <li><a href="{{ route('faq') }}"          class="hover:text-gold-400">FAQ</a></li>
            </ul>
        </div>
        <div>
            <div class="font-semibold text-white mb-3">Aide</div>
            <ul class="space-y-1.5">
                <li><a href="{{ route('contact') }}" class="hover:text-gold-400">Nous contacter</a></li>
                <li>Mentions légales</li>
                <li>Politique de confidentialité</li>
                <li>CGV</li>
            </ul>
        </div>
        <div>
            <div class="font-semibold text-white mb-3">Paiements acceptés</div>
            <p class="text-brand-200">Carte bancaire · Mobile Money (Orange, MTN, Wave) · Virement bancaire.</p>
        </div>
    </div>
    <div class="border-t border-brand-800 py-4 text-center text-xs text-brand-300">
        © {{ now()->year }} {{ config('app.name') }} — Tous droits réservés.
    </div>
</footer>
