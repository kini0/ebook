<x-mail::message>
# Merci pour votre commande !

Bonjour **{{ $order->billing_name }}**,

Votre commande **{{ $order->reference }}** est confirmée. Vos ebooks sont disponibles immédiatement dans votre espace client.

**Récapitulatif :**

@foreach ($order->items as $item)
- {{ $item->title_snapshot }} — {{ number_format($item->total_cents, 0, ',', ' ') }} FCFA
@endforeach

**Total :** {{ number_format($order->total_cents, 0, ',', ' ') }} FCFA

<x-mail::button :url="$url" color="primary">
Accéder à mes téléchargements
</x-mail::button>

Une question ? Répondez simplement à cet e-mail, notre équipe est à votre disposition.

Cordialement,<br>
L'équipe {{ config('app.name') }}
</x-mail::message>
