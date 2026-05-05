@extends('layouts.customer')
@section('title', 'Mon profil')

@section('customer_content')
<h1 class="font-display text-3xl mb-6">Profil & sécurité</h1>

<div class="grid lg:grid-cols-2 gap-6">
    <form method="POST" action="{{ route('customer.profile.update') }}" class="card p-6 space-y-4">
        @csrf @method('PUT')
        <h3 class="font-display text-lg">Informations personnelles</h3>

        <div class="grid grid-cols-2 gap-3">
            <div><label class="label">Prénom</label><input name="first_name" value="{{ old('first_name', $user->first_name) }}" class="input" required></div>
            <div><label class="label">Nom</label><input name="last_name" value="{{ old('last_name', $user->last_name) }}" class="input" required></div>
        </div>
        <div><label class="label">E-mail</label><input type="email" name="email" value="{{ old('email', $user->email) }}" class="input" required></div>
        <div><label class="label">Téléphone</label><input name="phone" value="{{ old('phone', $user->phone) }}" class="input"></div>
        <div class="grid grid-cols-2 gap-3">
            <div><label class="label">Pays</label><input name="country" value="{{ old('country', $user->country) }}" class="input"></div>
            <div><label class="label">Ville</label><input name="city" value="{{ old('city', $user->city) }}" class="input"></div>
        </div>
        <div><label class="label">Adresse</label><input name="address" value="{{ old('address', $user->address) }}" class="input"></div>
        <button class="btn-gold">Enregistrer les modifications</button>
    </form>

    <form method="POST" action="{{ route('customer.profile.password') }}" class="card p-6 space-y-4">
        @csrf @method('PUT')
        <h3 class="font-display text-lg">Changer de mot de passe</h3>
        <div><label class="label">Mot de passe actuel</label><input type="password" name="current_password" class="input" required></div>
        <div><label class="label">Nouveau mot de passe</label><input type="password" name="password" class="input" required></div>
        <div><label class="label">Confirmation</label><input type="password" name="password_confirmation" class="input" required></div>
        <button class="btn-primary">Mettre à jour</button>
    </form>
</div>
@endsection
