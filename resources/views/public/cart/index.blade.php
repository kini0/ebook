@extends('layouts.app')
@section('title', 'Mon panier')

@section('content')
<section class="container-x py-10">
    <h1 class="font-display text-3xl mb-6">Mon panier</h1>

    @if (empty($items))
        <div class="card p-10 text-center text-gray-500">
            <p class="mb-4">Votre panier est vide.</p>
            <a href="{{ route('ebooks.index') }}" class="btn-primary">Découvrir le catalogue</a>
        </div>
    @else
        <div class="grid lg:grid-cols-[1fr_360px] gap-8">
            <div class="card divide-y divide-gray-100">
                @foreach ($items as $item)
                    <div class="p-4 flex items-center gap-4">
                        <img src="{{ $item['cover_url'] }}" class="w-16 h-20 object-cover rounded" alt="" onerror="this.style.display='none'">
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('ebooks.show', $item['slug']) }}" class="font-medium hover:text-brand-700">{{ $item['title'] }}</a>
                            <div class="text-sm text-gray-500">{{ $item['author'] }}</div>
                        </div>
                        <div class="text-right">
                            <div class="font-semibold">{{ number_format($item['total_cents'], 0, ',', ' ') }} FCFA</div>
                            <form method="POST" action="{{ route('cart.remove', $item['ebook_id']) }}" class="mt-1">
                                @csrf @method('DELETE')
                                <button class="text-xs text-red-600 hover:text-red-700">Retirer</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <aside class="card p-6 h-fit">
                <h3 class="font-display text-lg mb-4">Récapitulatif</h3>
                <div class="flex justify-between mb-2 text-sm"><span>Sous-total</span><span>{{ $subtotal }}</span></div>
                <div class="flex justify-between mb-4 text-sm text-gray-500"><span>TVA</span><span>Incluse</span></div>
                <div class="border-t border-gray-100 pt-3 flex justify-between font-bold text-lg">
                    <span>Total</span><span class="text-brand-800">{{ $subtotal }}</span>
                </div>
                @auth
                    <a href="{{ route('checkout.index') }}" class="btn-gold w-full mt-5">Passer au paiement</a>
                @else
                    <a href="{{ route('login') }}" class="btn-gold w-full mt-5">Connectez-vous pour payer</a>
                @endauth
                <a href="{{ route('ebooks.index') }}" class="block text-center text-sm text-brand-700 mt-3">← Continuer mes achats</a>
            </aside>
        </div>
    @endif
</section>
@endsection
