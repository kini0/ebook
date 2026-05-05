@php $cartCount = app(\App\Services\CartService::class)->count(); @endphp
<header class="border-b border-gray-100 bg-white sticky top-0 z-40">
    <div class="container-x flex items-center justify-between py-4">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <span class="font-display text-2xl text-brand-800">{{ config('app.name') }}</span>
            <span class="hidden sm:inline-block badge-gold">Premium</span>
        </a>

        <nav class="hidden md:flex items-center gap-7 text-sm font-medium">
            <a href="{{ route('home') }}"           class="hover:text-brand-700">Accueil</a>
            <a href="{{ route('ebooks.index') }}"   class="hover:text-brand-700">Catalogue</a>
            <a href="{{ route('about') }}"          class="hover:text-brand-700">À propos</a>
            <a href="{{ route('faq') }}"            class="hover:text-brand-700">FAQ</a>
            <a href="{{ route('contact') }}"        class="hover:text-brand-700">Contact</a>
        </nav>

        <div class="flex items-center gap-3">
            <a href="{{ route('cart.index') }}"
               class="relative px-3 py-2 rounded-lg hover:bg-gray-50 text-sm">
                Panier
                @if ($cartCount > 0)
                    <span class="absolute -top-1 -right-1 bg-gold-500 text-brand-900 text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>

            @auth
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn-outline text-sm">Admin</a>
                @else
                    <a href="{{ route('customer.dashboard') }}" class="btn-outline text-sm">Mon compte</a>
                @endif
            @else
                <a href="{{ route('login') }}"    class="btn-ghost text-sm">Connexion</a>
                <a href="{{ route('register') }}" class="btn-gold text-sm">S'inscrire</a>
            @endauth
        </div>
    </div>
</header>
