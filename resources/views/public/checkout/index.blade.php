@extends('layouts.app')
@section('title', 'Paiement sécurisé')

@section('content')
<section class="container-x py-10">
    <h1 class="font-display text-3xl mb-6">Paiement sécurisé</h1>

    <form method="POST" action="{{ route('checkout.process') }}" class="grid lg:grid-cols-[1fr_360px] gap-8">
        @csrf

        <div class="space-y-6">

            {{-- Billing --}}
            <div class="card p-6">
                <h3 class="font-display text-xl mb-4">Coordonnées de facturation</h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="label">Nom complet</label>
                        <input name="billing_name" value="{{ old('billing_name', auth()->user()->full_name) }}" class="input" required>
                    </div>
                    <div>
                        <label class="label">Adresse e-mail</label>
                        <input type="email" name="billing_email" value="{{ old('billing_email', auth()->user()->email) }}" class="input" required>
                    </div>
                    <div>
                        <label class="label">Téléphone</label>
                        <input name="billing_phone" value="{{ old('billing_phone', auth()->user()->phone) }}" class="input">
                    </div>
                    <div>
                        <label class="label">Pays</label>
                        <input name="billing_country" value="{{ old('billing_country', auth()->user()->country) }}" class="input">
                    </div>
                    <div>
                        <label class="label">Ville</label>
                        <input name="billing_city" value="{{ old('billing_city', auth()->user()->city) }}" class="input">
                    </div>
                    <div>
                        <label class="label">Adresse</label>
                        <input name="billing_address" value="{{ old('billing_address', auth()->user()->address) }}" class="input">
                    </div>
                </div>
            </div>

            {{-- Payment method --}}
            <div class="card p-6" x-data="{ method: '{{ old('payment_method', 'card') }}' }">
                <h3 class="font-display text-xl mb-4">Méthode de paiement</h3>

                <div class="grid sm:grid-cols-3 gap-3 mb-4">
                    @foreach ($paymentMethods as $key => $pm)
                        <label class="card p-4 cursor-pointer transition"
                               :class="method === '{{ $key }}' ? 'ring-2 ring-gold-500 border-gold-300' : ''">
                            <input type="radio" name="payment_method" value="{{ $key }}"
                                   x-model="method" class="sr-only" {{ old('payment_method', 'card') === $key ? 'checked' : '' }}>
                            <div class="font-semibold">{{ $pm['label'] }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                @if ($key === 'card') Visa, Mastercard
                                @elseif ($key === 'mobile_money') Orange · MTN · Wave
                                @else Référence : votre N° de commande
                                @endif
                            </div>
                        </label>
                    @endforeach
                </div>

                {{-- Mobile Money options --}}
                <div x-show="method === 'mobile_money'" x-cloak class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="label">Opérateur</label>
                        <select name="mobile_operator" class="input">
                            @foreach ($mobileOperators as $opKey => $op)
                                <option value="{{ $opKey }}">{{ $op['label'] ?? ucfirst($opKey) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label">Numéro Mobile Money</label>
                        <input name="mobile_phone" class="input" value="{{ old('mobile_phone', auth()->user()->phone) }}">
                    </div>
                </div>
            </div>

            <label class="flex items-start gap-2 text-sm">
                <input type="checkbox" name="terms" value="1" class="mt-1 rounded">
                <span>J'accepte les <a href="#" class="text-brand-700 underline">conditions de vente</a> et confirme que mon achat est définitif (produits numériques).</span>
            </label>
        </div>

        {{-- Summary --}}
        <aside class="card p-6 h-fit sticky top-24">
            <h3 class="font-display text-lg mb-4">Récapitulatif</h3>
            <div class="divide-y divide-gray-100 mb-4">
                @foreach ($items as $it)
                    <div class="py-2 flex justify-between text-sm">
                        <div class="flex-1 mr-2 truncate">{{ $it['title'] }}</div>
                        <div class="font-medium">{{ number_format($it['total_cents'], 0, ',', ' ') }} FCFA</div>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-between text-sm mb-2"><span>Sous-total</span><span>{{ number_format($subtotal, 0, ',', ' ') }} FCFA</span></div>
            <div class="flex justify-between text-sm text-gray-500 mb-3"><span>TVA</span><span>Incluse</span></div>
            <div class="flex justify-between font-bold text-lg border-t border-gray-100 pt-3">
                <span>Total</span>
                <span class="text-brand-800">{{ number_format($subtotal, 0, ',', ' ') }} FCFA</span>
            </div>
            <button class="btn-gold w-full mt-5">Payer {{ number_format($subtotal, 0, ',', ' ') }} FCFA</button>
            <p class="text-xs text-gray-500 text-center mt-3">🔒 Paiement chiffré · Téléchargement instantané</p>
        </aside>
    </form>
</section>
@endsection
