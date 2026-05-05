<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture {{ $invoice->number }}</title>
    <style>
        * { font-family: DejaVu Sans, sans-serif; box-sizing: border-box; }
        body { font-size: 12px; color: #0B1E4F; margin: 0; padding: 32px; }
        .header { display: table; width: 100%; margin-bottom: 28px; }
        .brand  { color: #C9A24C; font-size: 28px; font-weight: bold; }
        .meta   { float: right; text-align: right; font-size: 11px; }
        .panels { display: table; width: 100%; margin-bottom: 24px; }
        .panel  { display: table-cell; width: 50%; vertical-align: top; padding-right: 12px; }
        .panel h4 { margin: 0 0 6px; font-size: 11px; text-transform: uppercase; color: #888; letter-spacing: 1px; }
        table.items { width: 100%; border-collapse: collapse; margin-top: 16px; }
        table.items th, table.items td { padding: 9px 8px; text-align: left; border-bottom: 1px solid #eee; }
        table.items th { background: #F4F6FB; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #555; }
        table.items td.right, table.items th.right { text-align: right; }
        .totals { float: right; width: 50%; margin-top: 12px; border-collapse: collapse; }
        .totals td { padding: 6px 8px; }
        .totals .label { color: #555; }
        .totals .grand { background: #0B1E4F; color: #fff; font-weight: bold; font-size: 14px; }
        .footer { margin-top: 60px; font-size: 10px; color: #888; border-top: 1px solid #eee; padding-top: 12px; text-align: center; }
        .stamp  { display: inline-block; padding: 4px 10px; border: 2px solid #2c8a4d; color: #2c8a4d; font-weight: bold; transform: rotate(-6deg); margin-top: 8px; font-size: 14px; }
    </style>
</head>
<body>

<div class="header">
    <div class="meta">
        <div><strong>Facture</strong></div>
        <div>N° {{ $invoice->number }}</div>
        <div>Émise le : {{ $invoice->issue_date->format('d/m/Y') }}</div>
        <div>Réf. commande : {{ $order->reference }}</div>
    </div>
    <div class="brand">{{ $company['name'] ?? config('app.name') }}</div>
    <div style="font-size: 10px; color: #666; margin-top: 4px;">{{ $company['address'] ?? '' }}</div>
    <div style="font-size: 10px; color: #666;">{{ $company['email'] ?? '' }} · {{ $company['phone'] ?? '' }}</div>
    <div style="font-size: 10px; color: #666;">{{ $company['taxid'] ?? '' }}</div>
</div>

<div class="panels">
    <div class="panel">
        <h4>Facturé à</h4>
        <div><strong>{{ $order->billing_name }}</strong></div>
        <div>{{ $order->billing_email }}</div>
        <div>{{ $order->billing_phone }}</div>
        <div>{{ $order->billing_address }}</div>
        <div>{{ $order->billing_city }} · {{ $order->billing_country }}</div>
    </div>
    <div class="panel" style="text-align: right;">
        <h4>Méthode de paiement</h4>
        <div>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</div>
        @if ($order->isPaid())
            <div class="stamp">PAYÉE</div>
        @endif
    </div>
</div>

<table class="items">
    <thead>
        <tr>
            <th>Description</th>
            <th class="right">Qté</th>
            <th class="right">Prix unitaire</th>
            <th class="right">Total HT</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $item)
            <tr>
                <td>
                    <strong>{{ $item->title_snapshot }}</strong><br>
                    <span style="color: #888; font-size: 10px;">par {{ $item->author_snapshot }}</span>
                </td>
                <td class="right">{{ $item->quantity }}</td>
                <td class="right">{{ number_format($item->unit_price_cents, 0, ',', ' ') }} FCFA</td>
                <td class="right">{{ number_format($item->total_cents, 0, ',', ' ') }} FCFA</td>
            </tr>
        @endforeach
    </tbody>
</table>

<table class="totals">
    <tr><td class="label">Sous-total</td><td class="right">{{ number_format($invoice->subtotal_cents, 0, ',', ' ') }} FCFA</td></tr>
    <tr><td class="label">TVA</td><td class="right">{{ number_format($invoice->tax_cents, 0, ',', ' ') }} FCFA</td></tr>
    <tr class="grand"><td>Total TTC</td><td class="right">{{ number_format($invoice->total_cents, 0, ',', ' ') }} FCFA</td></tr>
</table>

<div style="clear: both;"></div>

<div class="footer">
    Merci pour votre confiance. Cette facture est générée électroniquement et fait office de justificatif.
    <br>{{ $company['name'] ?? config('app.name') }} · {{ $company['address'] ?? '' }} · {{ $company['taxid'] ?? '' }}
</div>

</body>
</html>
