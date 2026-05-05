@extends('layouts.admin')
@section('title', 'Paramètres')
@section('page_title', 'Paramètres système')

@section('content')
<form method="POST" action="{{ route('admin.settings.update') }}" class="card p-6 space-y-4 max-w-3xl">
    @csrf @method('PUT')

    <h3 class="font-display text-lg">Identité</h3>
    <div class="grid md:grid-cols-2 gap-3">
        <div><label class="label">Nom du site</label><input name="site_name" value="{{ old('site_name', $values['site_name']->value ?? config('app.name')) }}" class="input"></div>
        <div><label class="label">E-mail support</label><input type="email" name="support_email" value="{{ old('support_email', $values['support_email']->value ?? '') }}" class="input"></div>
        <div class="md:col-span-2"><label class="label">Slogan</label><input name="site_tagline" value="{{ old('site_tagline', $values['site_tagline']->value ?? '') }}" class="input"></div>
        <div><label class="label">Téléphone support</label><input name="support_phone" value="{{ old('support_phone', $values['support_phone']->value ?? '') }}" class="input"></div>
    </div>

    <h3 class="font-display text-lg pt-4">Facturation</h3>
    <div class="grid md:grid-cols-2 gap-3">
        <div><label class="label">Raison sociale</label><input name="company_name" value="{{ old('company_name', $values['company_name']->value ?? '') }}" class="input"></div>
        <div><label class="label">N° fiscal</label><input name="company_taxid" value="{{ old('company_taxid', $values['company_taxid']->value ?? '') }}" class="input"></div>
        <div class="md:col-span-2"><label class="label">Adresse</label><input name="company_address" value="{{ old('company_address', $values['company_address']->value ?? '') }}" class="input"></div>
        <div><label class="label">Taux de TVA (%)</label><input type="number" name="tax_rate" value="{{ old('tax_rate', $values['tax_rate']->value ?? 0) }}" class="input" min="0" max="100"></div>
    </div>

    <button class="btn-gold mt-2">Enregistrer</button>
</form>
@endsection
