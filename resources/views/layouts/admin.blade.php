<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tableau de bord') — Admin {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 text-brand-900">

<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="w-64 bg-brand-900 text-white flex-shrink-0 hidden md:flex flex-col">
        <div class="px-6 py-5 border-b border-brand-800">
            <a href="{{ route('admin.dashboard') }}" class="font-display text-xl text-gold-400">
                {{ config('app.name') }}
            </a>
            <div class="text-xs text-brand-200 mt-0.5">Espace administrateur</div>
        </div>
        <nav class="flex-1 px-3 py-4 space-y-1 text-sm">
            @php
                $links = [
                    ['admin.dashboard',     'Tableau de bord',  'home'],
                    ['admin.ebooks.index',  'Ebooks',           'book'],
                    ['admin.orders.index',  'Commandes',        'shopping-bag'],
                    ['admin.transactions.index', 'Transactions','credit-card'],
                    ['admin.users.index',   'Utilisateurs',     'users'],
                    ['admin.settings.edit', 'Paramètres',       'cog'],
                ];
            @endphp
            @foreach ($links as [$route, $label, $icon])
                <a href="{{ route($route) }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition
                          {{ request()->routeIs($route . '*') ? 'bg-brand-700 text-gold-300' : 'text-brand-100 hover:bg-brand-800' }}">
                    <span class="w-2 h-2 rounded-full {{ request()->routeIs($route . '*') ? 'bg-gold-400' : 'bg-brand-400' }}"></span>
                    {{ $label }}
                </a>
            @endforeach
        </nav>
        <div class="px-4 py-4 border-t border-brand-800 text-sm">
            <div class="font-medium">{{ auth()->user()->full_name }}</div>
            <div class="text-brand-300 text-xs">Administrateur</div>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button class="text-gold-400 hover:text-gold-300 text-xs">Se déconnecter</button>
            </form>
        </div>
    </aside>

    {{-- Main content --}}
    <div class="flex-1 flex flex-col">
        <header class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between">
            <h1 class="font-display text-xl">@yield('page_title', 'Tableau de bord')</h1>
            <a href="{{ route('home') }}" class="text-sm text-brand-700 hover:text-brand-900">← Voir le site</a>
        </header>

        @if (session('success'))
            <div class="mx-6 mt-4 px-4 py-3 rounded bg-green-50 text-green-800 border border-green-200 text-sm">{{ session('success') }}</div>
        @endif
        @if (session('error') || $errors->any())
            <div class="mx-6 mt-4 px-4 py-3 rounded bg-red-50 text-red-800 border border-red-200 text-sm">
                {{ session('error') }}
                @foreach ($errors->all() as $err) <div>{{ $err }}</div> @endforeach
            </div>
        @endif

        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
