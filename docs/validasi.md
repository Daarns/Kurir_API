# Dokumentasi Validasi

Validasi tambah kurir berada di [TambahKurirRequest.php](../app/Http/Requests/TambahKurirRequest.php).

Validasi ubah kurir berada di [UbahKurirRequest.php](../app/Http/Requests/UbahKurirRequest.php).

## Aturan Utama

- `nama`, `surel`, `telepon`, `tingkat`, `status`, dan `jenis_kendaraan` wajib diisi saat tambah.
- `surel` dan `telepon` harus unik.
- `tingkat` harus berada pada angka `1` sampai `5`.
- `status` hanya boleh `aktif`, `nonaktif`, atau `ditangguhkan`.
- Ubah kurir boleh partial update, tetapi field yang dikirim tetap harus valid.

## Catatan Security

- Parameter `urut` di index dibatasi hanya `nama` dan `terdaftar_pada`.
- Parameter `tingkat` dibatasi hanya angka `1` sampai `5`.
- Data yang disimpan dibatasi oleh `$fillable` pada [Kurir.php](../app/Models/Kurir.php).
- Query memakai Eloquent/query builder, bukan raw SQL dari input user.
