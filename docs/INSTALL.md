# Guide d'installation — EbookSaaS

## Prérequis

| Outil    | Version |
|----------|---------|
| PHP      | 8.2+    |
| Composer | 2.6+    |
| Node.js  | 18+     |
| MySQL    | 8.0+ (ou MariaDB 10.6+) |

Extensions PHP requises : `bcmath`, `ctype`, `fileinfo`, `gd`, `json`, `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`, `zip`.

## 1. Installation locale (Laragon — Windows)

```bash
# 1. Cloner / placer le projet dans C:\laragon\www\ebook

# 2. Installer les dépendances PHP
cd C:\laragon\www\ebook
composer install

# 3. Copier l'env, générer la clé applicative
cp .env.example .env
php artisan key:generate

# 4. Créer la base
mysql -u root -e "CREATE DATABASE ebook CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 5. Migrer + seeder
php artisan migrate --seed

# 6. Lien de stockage public (couvertures)
php artisan storage:link

# 7. Installer Sanctum si nécessaire
php artisan install:api

# 8. Front-end
npm install
npm run dev   # ou npm run build pour la prod
```

Visitez `http://ebook.test` (Laragon génère automatiquement le vhost).

### Comptes par défaut (après seed)

| Rôle  | E-mail                   | Mot de passe |
|-------|--------------------------|--------------|
| Admin | `admin@ebooksaas.test`   | `password`   |
| Client| `client@ebooksaas.test`  | `password`   |

## 2. Configuration `.env`

Variables clés à renseigner :

```
APP_NAME=EbookSaaS
APP_URL=http://ebook.test
APP_LOCALE=fr

CURRENCY=XOF
CURRENCY_SYMBOL=FCFA

DB_DATABASE=ebook
DB_USERNAME=root
DB_PASSWORD=

PAYMENT_DEFAULT=card

# Stripe / CinetPay
STRIPE_KEY=...
STRIPE_SECRET=...
STRIPE_WEBHOOK_SECRET=...
CINETPAY_API_KEY=...
CINETPAY_SITE_ID=...

# Mobile Money
ORANGE_MONEY_CLIENT_ID=...
MTN_MOMO_API_KEY=...
WAVE_API_KEY=...

# Bank
BANK_NAME="Ecobank Côte d'Ivoire"
BANK_IBAN="CI05 ..."
```

## 3. Workers de queue

En développement, `QUEUE_CONNECTION=sync` est suffisant. En production :

```bash
QUEUE_CONNECTION=database     # ou redis
php artisan queue:work --tries=3
```

À démarrer comme service `supervisor` ou `systemd`.

## 4. Stockage des fichiers

- **Couvertures** → disque `public` (lien symbolique vers `public/storage`).
- **Fichiers ebooks** → disque par défaut (privé, `local`). Jamais accessible directement, toujours via URL signée.

## 5. Tests

```bash
php artisan test
```

Le `phpunit.xml` est préconfiguré avec SQLite en mémoire pour des tests rapides.
