@extends('layouts.auth')
@section('title', 'Vérification de l\'e-mail')

@section('content')
    <h1 class="font-display text-2xl mb-2">Vérifiez votre adresse e-mail</h1>
    <p class="text-gray-600 text-sm mb-6">
        Un lien de vérification a été envoyé à <strong>{{ auth()->user()?->email }}</strong>.
        Cliquez sur le lien pour activer votre compte.
    </p>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button class="btn-gold w-full">Renvoyer le lien</button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-3">
        @csrf
        <button class="btn-ghost w-full text-sm">Se déconnecter</button>
    </form>
@endsection
