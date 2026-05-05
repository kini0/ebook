@extends('layouts.auth')
@section('title', 'Créer un compte')

@section('content')
    <h1 class="font-display text-2xl mb-1">Créer mon compte</h1>
    <p class="text-gray-500 text-sm mb-6">Rejoignez {{ config('app.name') }} en moins d'une minute.</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="label">Prénom</label>
                <input name="first_name" value="{{ old('first_name') }}" class="input" required>
            </div>
            <div>
                <label class="label">Nom</label>
                <input name="last_name" value="{{ old('last_name') }}" class="input" required>
            </div>
        </div>
        <div>
            <label class="label">Adresse e-mail</label>
            <input type="email" name="email" value="{{ old('email') }}" class="input" required>
        </div>
        <div>
            <label class="label">Téléphone (Mobile Money de préférence)</label>
            <input name="phone" value="{{ old('phone') }}" class="input" placeholder="+225 07 ...">
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="label">Mot de passe</label>
                <input type="password" name="password" class="input" required>
            </div>
            <div>
                <label class="label">Confirmation</label>
                <input type="password" name="password_confirmation" class="input" required>
            </div>
        </div>
        <label class="flex items-start gap-2 text-sm">
            <input type="checkbox" name="terms" value="1" class="mt-1 rounded">
            <span>J'accepte les <a href="#" class="text-brand-700 underline">CGU</a> et la <a href="#" class="text-brand-700 underline">politique de confidentialité</a>.</span>
        </label>
        <button class="btn-gold w-full">Créer mon compte</button>
    </form>

    <p class="text-sm text-center mt-6 text-gray-600">
        Déjà inscrit ?
        <a href="{{ route('login') }}" class="text-brand-700 font-semibold hover:underline">Se connecter</a>
    </p>
@endsection
