@extends('layouts.auth')
@section('title', 'Connexion')

@section('content')
    <h1 class="font-display text-2xl mb-1">Bienvenue</h1>
    <p class="text-gray-500 text-sm mb-6">Connectez-vous pour accéder à votre bibliothèque.</p>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <div>
            <label class="label">Adresse e-mail</label>
            <input type="email" name="email" value="{{ old('email') }}" class="input" required autofocus>
        </div>
        <div>
            <label class="label">Mot de passe</label>
            <input type="password" name="password" class="input" required>
        </div>
        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="remember" value="1" class="rounded">
                Se souvenir de moi
            </label>
            <a href="{{ route('password.request') }}" class="text-brand-700 hover:underline">Mot de passe oublié ?</a>
        </div>
        <button class="btn-gold w-full">Se connecter</button>
    </form>

    <p class="text-sm text-center mt-6 text-gray-600">
        Pas encore de compte ?
        <a href="{{ route('register') }}" class="text-brand-700 font-semibold hover:underline">Créer un compte</a>
    </p>
@endsection
