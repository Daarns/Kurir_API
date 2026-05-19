# Dokumentasi Test

## Menjalankan Semua Test

```bash
php artisan test
```

atau:

```bash
composer test:kurir
```

## Unit Test

File: [tests/Unit/ValidasiKurir.php](../tests/Unit/ValidasiKurir.php)

Menguji aturan validasi:

- Tambah kurir mewajibkan field inti dan batasan nilai.
- Ubah kurir mendukung partial update dan tetap menjaga constraint.

Jalankan:

```bash
php artisan test tests/Unit/ValidasiKurir.php
```

atau:

```bash
composer test:unit-kurir
```

## Feature Test

File: [tests/Feature/ApiKurir.php](../tests/Feature/ApiKurir.php)

Menguji requirement brief:

- CRUD index menampilkan daftar kurir.
- pagination, default sort `nama`, sort `terdaftar_pada`, pencarian `budi+agung`, dan filter `tingkat=2,3`.
- show mengembalikan semua data kurir.
- store dan update memvalidasi input serta menyimpan data ke database.
- destroy menghapus data dari database.
- parameter `urut` dan `tingkat` tidak valid ditolak.

Jalankan:

```bash
php artisan test tests/Feature/ApiKurir.php
```

atau:

```bash
composer test:fitur-kurir
```
