# API Kurir

Project Laravel sederhana untuk master data kurir. Project ini hanya berisi satu modul, yaitu `kurir`, tanpa UI.

## Teknologi

- Laravel 13
- PHP 8.5
- SQLite untuk testing

## Ringkasan Fitur

- CRUD data kurir melalui REST API.
- Endpoint berbahasa Indonesia: `/api/kurir`.
- Kolom tabel dan field request/response berbahasa Indonesia.
- Paginasi pada daftar kurir.
- Default urutan daftar kurir berdasarkan `nama`.
- Urutan opsional berdasarkan tanggal daftar dengan `?urut=terdaftar_pada`.
- Pencarian nama multi-kata, contoh `?cari=budi+agung` dapat menemukan `Budiono Hadi Agung`.
- Filter tingkat kurir, contoh `?tingkat=2,3`.
- Validasi input lengkap untuk tambah dan ubah kurir.
- Unit test untuk aturan validasi.
- Feature test untuk alur API dan penyimpanan database.

## Struktur File

```text
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       └── KurirController.php
│   └── Requests/
│       ├── TambahKurirRequest.php
│       └── UbahKurirRequest.php
└── Models/
    └── Kurir.php

database/
├── factories/
│   └── KurirFactory.php
└── migrations/
    └── 2026_05_19_104751_create_kurir_table.php

routes/
└── api.php

tests/
├── Feature/
│   └── ApiKurir.php
└── Unit/
    └── ValidasiKurir.php
```

## Letak Implementasi

- Controller API: [app/Http/Controllers/Api/KurirController.php](app/Http/Controllers/Api/KurirController.php)
- Validasi tambah kurir: [app/Http/Requests/TambahKurirRequest.php](app/Http/Requests/TambahKurirRequest.php)
- Validasi ubah kurir: [app/Http/Requests/UbahKurirRequest.php](app/Http/Requests/UbahKurirRequest.php)
- Model kurir: [app/Models/Kurir.php](app/Models/Kurir.php)
- Migration tabel kurir: [database/migrations/2026_05_19_104751_create_kurir_table.php](database/migrations/2026_05_19_104751_create_kurir_table.php)
- Factory test: [database/factories/KurirFactory.php](database/factories/KurirFactory.php)
- Rute API: [routes/api.php](routes/api.php)
- Feature test: [tests/Feature/ApiKurir.php](tests/Feature/ApiKurir.php)
- Unit test: [tests/Unit/ValidasiKurir.php](tests/Unit/ValidasiKurir.php)

## Dokumentasi Lanjutan

- [Dokumentasi API](docs/api.md)
- [Dokumentasi validasi](docs/validasi.md)
- [Dokumentasi test](docs/testing.md)
- [Setup project](docs/setup.md)
