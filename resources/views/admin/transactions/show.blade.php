@extends('layouts.admin')
@section('title', $transaction->reference)
@section('page_title', 'Transaction ' . $transaction->reference)

@section('content')
<div class="card p-6 mb-4">
    <div class="grid md:grid-cols-3 gap-4">
        <div><div class="text-sm text-gray-500">Commande</div><a href="{{ route('admin.orders.show', $transaction->order) }}" class="font-medium text-brand-700">{{ $transaction->order?->reference }}</a></div>
        <div><div class="text-sm text-gray-500">Passerelle</div><div class="font-medium">{{ $transaction->gateway }} / {{ $transaction->provider }}</div></div>
        <div><div class="text-sm text-gray-500">Statut</div><div class="font-medium">{{ $transaction->status?->label() }}</div></div>
        <div><div class="text-sm text-gray-500">Montant</div><div class="font-medium">{{ number_format($transaction->amount_cents, 0, ',', ' ') }} {{ $transaction->currency }}</div></div>
        <div><div class="text-sm text-gray-500">Référence fournisseur</div><div class="font-mono text-sm">{{ $transaction->provider_reference ?? '—' }}</div></div>
        <div><div class="text-sm text-gray-500">Traitée le</div><div class="font-medium">{{ optional($transaction->processed_at)->format('d/m/Y H:i') ?? '—' }}</div></div>
    </div>
</div>

@if ($transaction->failure_reason)
    <div class="card p-4 bg-red-50 border-red-200 text-red-800 text-sm mb-4">
        <strong>Motif d'échec :</strong> {{ $transaction->failure_reason }}
    </div>
@endif

<div class="card p-5 mb-4">
    <h4 class="font-display text-base mb-2">Réponse de la passerelle</h4>
    <pre class="text-xs bg-gray-50 p-3 rounded overflow-auto">{{ json_encode($transaction->gateway_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
</div>

<form method="POST" action="{{ route('admin.transactions.reconcile', $transaction) }}">
    @csrf <button class="btn-primary">Reconciler depuis le fournisseur</button>
</form>
@endsection
