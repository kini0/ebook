@extends('layouts.admin')
@section('title', 'Transactions')
@section('page_title', 'Transactions')

@section('content')
<form method="GET" class="flex gap-2 mb-4">
    <select name="status" class="input">
        <option value="">Tous statuts</option>
        @foreach ($statuses as $s)
            <option value="{{ $s->value }}" @selected(($filters['status'] ?? null) === $s->value)>{{ $s->label() }}</option>
        @endforeach
    </select>
    <select name="gateway" class="input">
        <option value="">Toutes passerelles</option>
        <option value="card"          @selected(($filters['gateway'] ?? null) === 'card')>Carte</option>
        <option value="mobile_money"  @selected(($filters['gateway'] ?? null) === 'mobile_money')>Mobile Money</option>
        <option value="bank_transfer" @selected(($filters['gateway'] ?? null) === 'bank_transfer')>Virement</option>
    </select>
    <button class="btn-outline">Filtrer</button>
    <a href="{{ route('admin.export.transactions') }}" class="btn-gold ml-auto">Export CSV</a>
</form>

<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left">
            <tr>
                <th class="px-4 py-3">Référence</th>
                <th class="px-4 py-3">Commande</th>
                <th class="px-4 py-3">Passerelle</th>
                <th class="px-4 py-3">Montant</th>
                <th class="px-4 py-3">Statut</th>
                <th class="px-4 py-3">Date</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach ($transactions as $tx)
                <tr>
                    <td class="px-4 py-3"><a href="{{ route('admin.transactions.show', $tx) }}" class="text-brand-700">{{ $tx->reference }}</a></td>
                    <td class="px-4 py-3"><a href="{{ route('admin.orders.show', $tx->order) }}">{{ $tx->order?->reference }}</a></td>
                    <td class="px-4 py-3">{{ $tx->gateway }} <span class="text-gray-400">/ {{ $tx->provider }}</span></td>
                    <td class="px-4 py-3">{{ number_format($tx->amount_cents, 0, ',', ' ') }} FCFA</td>
                    <td class="px-4 py-3">{{ $tx->status?->label() }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $transactions->links() }}</div>
@endsection
