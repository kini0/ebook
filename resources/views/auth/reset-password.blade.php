@extends('layouts.auth')
@section('title', 'Réinitialisation du mot de passe')

@section('content')
    <h1 class="font-display text-2xl mb-1">Nouveau mot de passe</h1>
    <p class="text-gray-500 text-sm mb-6">Choisissez un mot de passe sûr (8 caractères minimum).</p>

    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div>
            <label class="label">Adresse e-mail</label>
            <input type="email" name="email" value="{{ $email ?? old('email') }}" class="input" required>
        </div>
        <div>
            <label class="label">Nouveau mot de passe</label>
            <input type="password" name="password" class="input" required>
        </div>
        <div>
            <label class="label">Confirmation</label>
            <input type="password" name="password_confirmation" class="input" required>
        </div>
        <button class="btn-gold w-full">Réinitialiser le mot de passe</button>
    </form>
@endsection
