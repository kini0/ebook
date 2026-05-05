@extends('layouts.app')

@section('content')
<div class="container-x py-10">
    <div class="grid md:grid-cols-[260px_1fr] gap-8">
        <aside class="card p-4 h-fit sticky top-24">
            <div class="px-2 pb-3 mb-3 border-b border-gray-100">
                <div class="font-display text-lg">{{ auth()->user()->full_name }}</div>
                <div class="text-sm text-gray-500">{{ auth()->user()->email }}</div>
            </div>
            <nav class="space-y-1 text-sm">
                @php
                    $items = [
                        ['customer.dashboard',         'Tableau de bord'],
                        ['customer.orders.index',      'Mes commandes'],
                        ['customer.downloads.index',   'Mes téléchargements'],
                        ['customer.profile.edit',      'Profil & sécurité'],
                    ];
                @endphp
                @foreach ($items as [$route, $label])
                    <a href="{{ route($route) }}"
                       class="block px-3 py-2 rounded-lg transition
                              {{ request()->routeIs($route . '*') ? 'bg-brand-50 text-brand-800 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                        {{ $label }}
                    </a>
                @endforeach
                <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-gray-100 mt-2">
                    @csrf
                    <button class="block w-full text-left px-3 py-2 rounded-lg text-red-600 hover:bg-red-50">
                        Se déconnecter
                    </button>
                </form>
            </nav>
        </aside>

        <section>
            @yield('customer_content')
        </section>
    </div>
</div>
@endsection
