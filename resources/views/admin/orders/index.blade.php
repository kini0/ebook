@extends('layouts.admin')
@section('title', 'Commandes')
@section('page_title', 'Commandes')

@section('content')
<form method="GET" class="flex gap-2 mb-4">
    <input name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Référence, client, e-mail..." class="input flex-1">
    <select name="status" class="input">
        <option value="">Tous statuts</option>
        @foreach ($statuses as $s)
            <option value="{{ $s->value }}" @selected(($filters['status'] ?? null) === $s->value)>{{ $s->label() }}</option>
        @endforeach
    </select>
    <button class="btn-outline">Filtrer</button>
    <a href="{{ route('admin.export.orders') }}" class="btn-gold">Export CSV</a>
</form>

<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left">
            <tr>
                <th class="px-4 py-3">Référence</th>
                <th class="px-4 py-3">Client</th>
                <th class="px-4 py-3">Articles</th>
                <th class="px-4 py-3">Total</th>
                <th class="px-4 py-3">Statut</th>
                <th class="px-4 py-3">Date</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($orders as $order)
                <tr>
                    <td class="px-4 py-3"><a href="{{ route('admin.orders.show', $order) }}" class="text-brand-700">{{ $order->reference }}</a></td>
                    <td class="px-4 py-3">
                        <div>{{ $order->billing_name }}</div>
                        <div class="text-xs text-gray-500">{{ $order->billing_email }}</div>
                    </td>
                    <td class="px-4 py-3">{{ $order->items->count() }}</td>
                    <td class="px-4 py-3">{{ $order->formatted_total }}</td>
                    <td class="px-4 py-3"><span class="{{ $order->status->badgeClass() }}">{{ $order->status->label() }}</span></td>
                    <td class="px-4 py-3 text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">Aucune commande.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $orders->links() }}</div>
@endsection
