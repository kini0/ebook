@extends('layouts.auth')
@section('title', 'Mot de passe oublié')

@section('content')
    <h1 class="font-display text-2xl mb-1">Mot de passe oublié</h1>
    <p class="text-gray-500 text-sm mb-6">Saisissez votre e-mail, nous vous enverrons un lien de réinitialisation.</p>

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf
        <div>
            <label class="label">Adresse e-mail</label>
            <input type="email" name="email" class="input" required autofocus>
        </div>
        <button class="btn-gold w-full">Envoyer le lien</button>
    </form>
    <p class="text-sm text-center mt-6 text-gray-600">
        <a href="{{ route('login') }}" class="text-brand-700 hover:underline">← Retour à la connexion</a>
    </p>
@endsection
