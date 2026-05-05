# Guide d'extension des passerelles de paiement

Le système utilise le **Strategy pattern** : tous les drivers implémentent
`App\Payments\Contracts\PaymentGatewayInterface` et sont résolus à la volée
par `App\Payments\PaymentManager` à partir de `config/payment.php`.

## Cycle de vie d'un paiement

```
1. CheckoutController::process()
   └── OrderService::createFromCheckout()      → Order (status: pending)
       └── PaymentService::initiate()
           └── PaymentManager::gateway($key)   → driver concret
               └── $driver->initiate($order, $payload)
                   └── retourne PaymentResult (success | pending | redirect | failed)

2. (Asynchrone) Webhook fournisseur
   └── PaymentWebhookController::handle($gateway)
       └── $driver->handleWebhook($request)    → PaymentResult
       └── PaymentService::applyResultToTransaction()
           └── met à jour Transaction + Order (PaymentSucceeded / PaymentFailed event)
```

## Ajouter un nouveau driver — exemple : « PayPal »

### 1. Créer le driver

`app/Payments/Drivers/PaypalDriver.php` :

```php
<?php

namespace App\Payments\Drivers;

use App\DTOs\PaymentResult;
use App\Models\Order;
use Illuminate\Http\Request;

class PaypalDriver extends AbstractPaymentDriver
{
    public function key(): string
    {
        return 'paypal';
    }

    public function initiate(Order $order, array $payload = []): PaymentResult
    {
        // Appeler le SDK PayPal pour créer un PaymentIntent
        // ...
        return PaymentResult::redirect(
            url: 'https://www.paypal.com/checkoutnow?token=...',
            providerRef: $this->buildProviderReference($order->reference),
        );
    }

    public function verify(string $providerReference): PaymentResult
    {
        // GET /v2/checkout/orders/{ref}
        return PaymentResult::pending($providerReference);
    }

    public function handleWebhook(Request $request): PaymentResult
    {
        // Vérifier la signature + parser
        $event = $request->input('event_type');
        return match ($event) {
            'CHECKOUT.ORDER.APPROVED'  => PaymentResult::success($request->input('resource.id'), $request->all()),
            'CHECKOUT.ORDER.CANCELLED' => PaymentResult::failed('Annulé', $request->all()),
            default => PaymentResult::pending($request->input('resource.id'), $request->all()),
        };
    }
}
```

### 2. Enregistrer le driver dans la configuration

`config/payment.php`, dans le tableau `drivers` :

```php
'paypal' => [
    'driver'   => App\Payments\Drivers\PaypalDriver::class,
    'label'    => 'PayPal',
    'icon'     => 'paypal',
    'enabled'  => true,
    'client_id'     => env('PAYPAL_CLIENT_ID'),
    'client_secret' => env('PAYPAL_CLIENT_SECRET'),
],
```

### 3. Variables `.env`

```
PAYPAL_CLIENT_ID=...
PAYPAL_CLIENT_SECRET=...
```

### 4. Mettre à jour le formulaire de checkout

Dans `CheckoutRequest::rules()` :

```php
'payment_method' => ['required', 'in:card,mobile_money,bank_transfer,paypal'],
```

Le sélecteur de méthode dans `resources/views/public/checkout/index.blade.php`
itère déjà sur `$paymentMethods = $paymentManager->available()`, donc le nouveau driver s'y affichera automatiquement dès qu'il est `enabled`.

### 5. Configurer le webhook côté PayPal

URL à fournir : `https://votre-domaine.com/webhooks/payments/paypal`.

C'est tout. Aucune autre couche du système ne change : le contrôleur, le service de paiement, l'invoice, l'event, les e-mails — rien à toucher.

## Bonnes pratiques

- **Toujours retourner un `PaymentResult` typé** — pas de tableaux libres.
- **Vérifier la signature du webhook** dans `handleWebhook()` (HMAC, JWT, etc.).
- **Idempotence** : un même `provider_reference` ne doit jamais créer 2 transactions. La table `transactions.provider_reference` est indexée pour faciliter la déduplication.
- **Conserver la réponse brute** dans `Transaction::gateway_response` (JSON) pour audit.
- **Tests** : ajoutez un test unitaire dans `tests/Unit/PaymentDriverTest.php` pour valider le mapping des événements.

## Sécurité

- Webhooks : whitelistez l'IP du fournisseur si possible (`Http\Middleware`).
- Pas de secrets côté client : tout passe par `config/payment.php` côté serveur.
- Activez `APP_DEBUG=false` en production.
