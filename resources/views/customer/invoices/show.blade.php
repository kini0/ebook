@extends('layouts.customer')
@section('title', 'Facture ' . $invoice->number)

@section('customer_content')
<div class="flex items-center justify-between mb-6">
    <h1 class="font-display text-2xl">Facture {{ $invoice->number }}</h1>
    <a href="{{ route('customer.invoices.download', $invoice) }}" class="btn-gold">Télécharger le PDF</a>
</div>

<div class="card p-6">
    <div class="grid md:grid-cols-2 gap-6 mb-6">
        <div>
            <div class="text-sm text-gray-500">Émise le</div>
            <div class="font-medium">{{ $invoice->issue_date->translatedFormat('d F Y') }}</div>
        </div>
        <div>
            <div class="text-sm text-gray-500">Commande associée</div>
            <a href="{{ route('customer.orders.show', $invoice->order) }}" class="font-medium text-brand-700">{{ $invoice->order->reference }}</a>
        </div>
    </div>

    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left">
            <tr>
                <th class="px-3 py-2">Article</th>
                <th class="px-3 py-2 text-right">Qté</th>
                <th class="px-3 py-2 text-right">Prix unitaire</th>
                <th class="px-3 py-2 text-right">Total</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach ($invoice->order->items as $item)
                <tr>
                    <td class="px-3 py-2">{{ $item->title_snapshot }}</td>
                    <td class="px-3 py-2 text-right">{{ $item->quantity }}</td>
                    <td class="px-3 py-2 text-right">{{ number_format($item->unit_price_cents, 0, ',', ' ') }} FCFA</td>
                    <td class="px-3 py-2 text-right">{{ $item->formatted_total }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot class="font-medium">
            <tr><td colspan="3" class="px-3 py-2 text-right">Sous-total</td><td class="px-3 py-2 text-right">{{ number_format($invoice->subtotal_cents, 0, ',', ' ') }} FCFA</td></tr>
            <tr><td colspan="3" class="px-3 py-2 text-right">TVA</td><td class="px-3 py-2 text-right">{{ number_format($invoice->tax_cents, 0, ',', ' ') }} FCFA</td></tr>
            <tr class="text-lg"><td colspan="3" class="px-3 py-2 text-right">Total TTC</td><td class="px-3 py-2 text-right text-brand-800 font-bold">{{ $invoice->formatted_total }}</td></tr>
        </tfoot>
    </table>
</div>
@endsection
