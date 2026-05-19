# Dokumentasi API

## Endpoint

```text
GET    /api/kurir
POST   /api/kurir
GET    /api/kurir/{kurir}
PATCH  /api/kurir/{kurir}
PUT    /api/kurir/{kurir}
DELETE /api/kurir/{kurir}
```

## Query Daftar Kurir

```text
GET /api/kurir?per_halaman=10&halaman=1
GET /api/kurir?urut=terdaftar_pada
GET /api/kurir?cari=budi+agung
GET /api/kurir?tingkat=2,3
```

Ketentuan:

- Default urutan: `nama`.
- `urut` hanya menerima `nama` atau `terdaftar_pada`.
- `tingkat` hanya menerima angka `1` sampai `5`, bisa dipisah koma.
- `per_halaman` maksimal `100`.

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

## Kolom Tabel

Tabel yang digunakan adalah `kurir`.

```text
id, nama, surel, telepon, tingkat, status, jenis_kendaraan,
plat_kendaraan, terdaftar_pada, alamat, created_at, updated_at
```
