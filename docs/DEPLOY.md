# Déploiement — EbookSaaS

## VPS Ubuntu 22.04 (Nginx + PHP-FPM)

### 1. Provisionnement

```bash
sudo apt update && sudo apt install -y nginx mysql-server redis-server \
    php8.2-fpm php8.2-{cli,mbstring,xml,curl,gd,mysql,zip,bcmath,intl} \
    composer git unzip
```

### 2. Déploiement initial

```bash
cd /var/www
git clone <repo> ebook && cd ebook
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
nano .env   # renseigner DB, mail, paiements

php artisan migrate --force
php artisan db:seed --class=SettingSeeder --force
php artisan storage:link

# Optimisations
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

npm ci && npm run build
```

### 3. Permissions

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### 4. Nginx vhost

```nginx
server {
    listen 80;
    server_name ebooksaas.com www.ebooksaas.com;
    root /var/www/ebook/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
    client_max_body_size 60M;     # ebook uploads
}
```

Puis activez TLS :

```bash
sudo certbot --nginx -d ebooksaas.com -d www.ebooksaas.com
```

### 5. Queue worker (systemd)

`/etc/systemd/system/ebook-queue.service` :

```ini
[Unit]
Description=EbookSaaS queue worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /var/www/ebook/artisan queue:work --tries=3 --timeout=120

[Install]
WantedBy=multi-user.target
```

```bash
sudo systemctl enable --now ebook-queue
```

### 6. Cron Laravel scheduler

```cron
* * * * * cd /var/www/ebook && php artisan schedule:run >> /dev/null 2>&1
```

### 7. Webhooks de paiement

Exposer à votre fournisseur :

```
POST https://ebooksaas.com/webhooks/payments/{gateway}
```

Où `{gateway}` ∈ `card`, `mobile_money`, `bank_transfer`.

> ⚠️ Pour Stripe : configurer `STRIPE_WEBHOOK_SECRET` et vérifier la signature dans le driver.

### 8. Sauvegarde

- **Base** : `mysqldump --single-transaction ebook | gzip > backup-$(date +%F).sql.gz`
- **Fichiers** : `tar -czf storage-$(date +%F).tar.gz storage/app/`

Recommandé : `spatie/laravel-backup` ou rclone vers S3 / Wasabi.

## Conteneurs (Docker)

Un `Dockerfile` n'est pas inclus ; nous recommandons [Laravel Sail](https://laravel.com/docs/sail) pour le développement et une image PHP-FPM Alpine sur mesure pour la production.
