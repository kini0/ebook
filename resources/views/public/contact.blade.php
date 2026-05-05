@extends('layouts.app')
@section('title', 'Contact')

@section('content')
<section class="container-x py-16 max-w-2xl">
    <h1 class="font-display text-4xl mb-3">Contactez-nous</h1>
    <p class="text-gray-600 mb-8">Une question, une suggestion ? Notre équipe répond sous 24 heures.</p>

    <form method="POST" action="{{ route('contact.send') }}" class="card p-6 space-y-4">
        @csrf
        <div>
            <label class="label">Nom complet</label>
            <input name="name" value="{{ old('name') }}" class="input" required>
        </div>
        <div>
            <label class="label">Adresse e-mail</label>
            <input type="email" name="email" value="{{ old('email') }}" class="input" required>
        </div>
        <div>
            <label class="label">Sujet</label>
            <input name="subject" value="{{ old('subject') }}" class="input" required>
        </div>
        <div>
            <label class="label">Message</label>
            <textarea name="message" rows="6" class="input" required>{{ old('message') }}</textarea>
        </div>
        <button class="btn-gold">Envoyer le message</button>
    </form>
</section>
@endsection
