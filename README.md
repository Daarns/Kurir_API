# API Kurir

Project Laravel sederhana untuk master data kurir. Project ini hanya berisi satu modul, yaitu `kurir`, tanpa UI.

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

## Struktur File Penting

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

- Controller API: `app/Http/Controllers/Api/KurirController.php`
- Validasi tambah kurir: `app/Http/Requests/TambahKurirRequest.php`
- Validasi ubah kurir: `app/Http/Requests/UbahKurirRequest.php`
- Model kurir: `app/Models/Kurir.php`
- Migration tabel kurir: `database/migrations/2026_05_19_104751_create_kurir_table.php`
- Factory test: `database/factories/KurirFactory.php`
- Rute API: `routes/api.php`
- Feature test: `tests/Feature/ApiKurir.php`
- Unit test: `tests/Unit/ValidasiKurir.php`

## Rute dan Endpoint API

```text
GET    /api/kurir
POST   /api/kurir
GET    /api/kurir/{kurir}
PATCH  /api/kurir/{kurir}
PUT    /api/kurir/{kurir}
DELETE /api/kurir/{kurir}
```

Rute didefinisikan dengan:

```php
Route::apiResource('kurir', KurirController::class);
```

## Query Daftar Kurir

Daftar kurir mendukung paginasi, urutan, pencarian, dan filter tingkat.

```text
GET /api/kurir?per_halaman=10&halaman=1
GET /api/kurir?urut=terdaftar_pada
GET /api/kurir?cari=budi+agung
GET /api/kurir?tingkat=2,3
```

Ketentuan query:

- `per_halaman`: jumlah data per halaman, maksimal `100`.
- `halaman`: nomor halaman.
- `urut`: hanya menerima `nama` atau `terdaftar_pada`.
- Default `urut`: `nama`.
- `cari`: mencari nama kurir per kata.
- `tingkat`: menerima nilai `1` sampai `5`, bisa lebih dari satu dengan koma.

## Kolom Tabel Kurir

Tabel yang digunakan adalah `kurir`.

```text
id
nama
surel
telepon
tingkat
status
jenis_kendaraan
plat_kendaraan
terdaftar_pada
alamat
created_at
updated_at
```

Keterangan:

- `tingkat`: angka `1` sampai `5`.
- `status`: `aktif`, `nonaktif`, atau `ditangguhkan`.
- `terdaftar_pada`: tanggal kurir didaftarkan.

## Contoh Payload Tambah Kurir

```json
{
  "nama": "Budi Agung",
  "surel": "budi.agung@example.com",
  "telepon": "081234567890",
  "tingkat": 2,
  "status": "aktif",
  "jenis_kendaraan": "motor",
  "plat_kendaraan": "B 1234 AG",
  "terdaftar_pada": "2026-05-19 10:00:00",
  "alamat": "Jl. Melati No. 10"
}
```

## Contoh Response Daftar Kurir

```json
{
  "data": [
    {
      "id": 1,
      "nama": "Budi Agung",
      "surel": "budi.agung@example.com",
      "telepon": "081234567890",
      "tingkat": 2,
      "status": "aktif",
      "jenis_kendaraan": "motor",
      "plat_kendaraan": "B 1234 AG",
      "terdaftar_pada": "2026-05-19T03:00:00.000000Z",
      "alamat": "Jl. Melati No. 10",
      "dibuat_pada": "2026-05-19T03:00:00.000000Z",
      "diperbarui_pada": "2026-05-19T03:00:00.000000Z"
    }
  ],
  "metadata": {
    "halaman_saat_ini": 1,
    "per_halaman": 15,
    "total": 1,
    "halaman_terakhir": 1
  }
}
```

## Validasi

Validasi tambah kurir berada di `TambahKurirRequest`:

- `nama`: wajib, string, maksimal 255 karakter.
- `surel`: wajib, format email, unik.
- `telepon`: wajib, format nomor telepon sederhana, unik.
- `tingkat`: wajib, integer, 1 sampai 5.
- `status`: wajib, salah satu dari `aktif`, `nonaktif`, `ditangguhkan`.
- `jenis_kendaraan`: wajib, string, maksimal 50 karakter.
- `plat_kendaraan`: opsional, string, maksimal 20 karakter.
- `terdaftar_pada`: opsional, tanggal.
- `alamat`: opsional, string, maksimal 1000 karakter.

Validasi ubah kurir berada di `UbahKurirRequest`. Semua field memakai `sometimes`, sehingga ubah data boleh partial, tetapi field yang dikirim tetap harus valid.

## Menjalankan Test

Jalankan semua test:

```bash
php artisan test
```

Jalankan unit test validasi kurir saja:

```bash
php artisan test tests/Unit/ValidasiKurir.php
```

Jalankan feature test API kurir saja:

```bash
php artisan test tests/Feature/ApiKurir.php
```

Perintah lebih singkat melalui Composer:

```bash
composer test:unit-kurir
composer test:fitur-kurir
composer test:kurir
```

## Unit Test

File: `tests/Unit/ValidasiKurir.php`

Test yang dilakukan:

- `validasi tambah kurir mewajibkan field inti dan batasan nilai`
  - Memastikan validasi tambah kurir mewajibkan field inti.
  - Memastikan `surel` dan `telepon` unik.
  - Memastikan `tingkat` dibatasi 1 sampai 5.
  - Memastikan `status` hanya boleh `aktif`, `nonaktif`, atau `ditangguhkan`.

- `validasi ubah kurir mendukung partial update dan tetap menjaga constraint`
  - Memastikan ubah kurir menggunakan `sometimes` agar bisa partial update.
  - Memastikan constraint penting tetap ada saat field dikirim.
  - Memastikan unique rule untuk `surel` dan `telepon` tetap tersedia.

## Feature Test

File: `tests/Feature/ApiKurir.php`

Test yang dilakukan:

- `crud index berhasil menampilkan daftar kurir`
  - Memastikan endpoint index bisa menampilkan daftar kurir.

- `index memiliki pagination dengan metadata halaman`
  - Memastikan response daftar kurir memiliki data per halaman dan metadata pagination.

- `index default diurutkan berdasarkan nama kurir`
  - Memastikan default sort adalah `nama`.

- `index bisa diurutkan berdasarkan tanggal didaftarkan`
  - Memastikan `?urut=terdaftar_pada` mengganti default sort.

- `cari budi agung berhasil menemukan nama budiono hadi agung`
  - Memastikan pencarian `?cari=budi+agung` dapat match dengan `Budiono Hadi Agung`.

- `filter tingkat 2 3 hanya menampilkan kurir tingkat 2 dan 3`
  - Memastikan `?tingkat=2,3` hanya mengembalikan kurir tingkat 2 dan 3.

- `show mengembalikan semua data pada kurir`
  - Memastikan endpoint detail mengembalikan semua field utama pada kurir.

- `store memvalidasi input dan menyimpan data ke database`
  - Memastikan tambah kurir berhasil menyimpan data valid ke database.
  - Memastikan response `201 Created`.
  - Memastikan validasi menolak `tingkat` di luar 1 sampai 5.

- `update memvalidasi input dan menyimpan perubahan ke database`
  - Memastikan ubah kurir berhasil menyimpan perubahan ke database.
  - Memastikan validasi menolak `tingkat` tidak valid.

- `destroy menghapus data kurir dari database`
  - Memastikan hapus kurir menghapus data dari database.
  - Memastikan data yang sudah dihapus tidak bisa diakses lagi.

- `security parameter urut tidak valid ditolak`
  - Memastikan client tidak bisa mengirim nama kolom bebas untuk sorting.

- `security filter tingkat tidak valid ditolak`
  - Memastikan filter tingkat di luar 1 sampai 5 ditolak sebelum query database.

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```
