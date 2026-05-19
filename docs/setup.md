# Setup Project

## Instalasi

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

## Menjalankan Server Lokal

```bash
php artisan serve
```

Endpoint tersedia di:

```text
http://127.0.0.1:8000/api/kurir
```
