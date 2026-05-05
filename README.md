# EbookSaaS — Plateforme e-commerce premium d'ebooks

Application Laravel 11 production-ready, conçue pour vendre des ebooks numériques.
Architecture clean (SOLID), couche Service, pattern Repository, abstraction de paiement extensible.

## Stack

- **Backend** : Laravel 11 · PHP 8.2+ · MySQL/MariaDB · Sanctum (API tokens)
- **Frontend** : Blade · TailwindCSS 3 · Alpine.js · Chart.js
- **PDF** : barryvdh/laravel-dompdf
- **Queue** : database driver (recommandé en prod : Redis)
- **Paiements** : Carte (Stripe / CinetPay) · Mobile Money (Orange / MTN / Wave) · Virement bancaire

## Documentation

| Fichier                                              | Contenu                                                  |
| ---------------------------------------------------- | -------------------------------------------------------- |
| [`docs/INSTALL.md`](docs/INSTALL.md)                 | Guide d'installation locale (Laragon / WSL / Docker)     |
| [`docs/DEPLOY.md`](docs/DEPLOY.md)                   | Déploiement sur VPS, Forge, ou conteneurs                |
| [`docs/PAYMENT-DRIVERS.md`](docs/PAYMENT-DRIVERS.md) | Comment ajouter un nouveau driver de paiement            |

## Architecture en bref

```
app/
├── DTOs/              CheckoutData, PaymentResult (immutables)
├── Enums/             UserRole, OrderStatus, PaymentStatus, PaymentMethod
├── Events/            OrderPlaced, PaymentSucceeded, PaymentFailed
├── Exceptions/        PaymentException, DownloadException
├── Http/
│   ├── Controllers/   Public/, Auth/, Customer/, Admin/, Api/
│   ├── Middleware/    EnsureUserIsAdmin
│   ├── Requests/      Form Requests (validation centralisée)
│   └── Resources/     API JSON Resources
├── Jobs/              GenerateInvoicePdf, SendOrderConfirmationEmail
├── Listeners/         GenerateInvoiceForOrder, SendOrderConfirmation, NotifyAdminPaymentFailed
├── Mail/              OrderConfirmationMail (markdown FR)
├── Models/            User, Category, Ebook, Order, OrderItem, Transaction, Invoice, Download...
├── Payments/
│   ├── Contracts/     PaymentGatewayInterface
│   ├── Drivers/       CardDriver, MobileMoneyDriver, BankTransferDriver
│   └── PaymentManager.php   (Strategy pattern + DI)
├── Policies/          EbookPolicy, OrderPolicy
├── Providers/         AppServiceProvider, AuthServiceProvider, EventServiceProvider,
│                      PaymentServiceProvider, RepositoryServiceProvider
├── Repositories/
│   ├── Contracts/     *RepositoryInterface
│   └── Eloquent/      Implémentations
└── Services/          AuthService, EbookService, CartService, OrderService,
                       PaymentService, InvoiceService, DownloadService, AnalyticsService
```

## Démarrage rapide

```bash
composer install
cp .env.example .env
php artisan key:generate
# configurer DB_* dans .env, puis :
php artisan migrate --seed
php artisan storage:link
npm install && npm run dev
```

Comptes par défaut : `admin@ebooksaas.test` / `client@ebooksaas.test` — mot de passe `password`.

## Sécurité

- CSRF actif sur toutes les routes web
- XSS : Blade échappe par défaut
- Mass assignment : `$fillable` strict + Form Requests
- Politiques d'accès (Policies) sur Ebook & Order
- Téléchargements : URL signées temporaires (expiration 15 min par défaut)
- Rate limiting : 5 req/min sur connexion, 6 req/min sur webhooks
- Vérification e-mail obligatoire avant téléchargement

## Tests

```bash
php artisan test
```

Couvre : authentification, flux de checkout, drivers de paiement (unitaire), service de commande, protection des téléchargements.

---

© EbookSaaS — Conçu pour l'Afrique de l'Ouest et au-delà.
