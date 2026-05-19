<?php

namespace Tests\Feature;

use App\Models\Kurir;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class ApiKurir extends TestCase
{
    use RefreshDatabase;

    #[TestDox('CRUD index berhasil menampilkan daftar kurir')]
    public function test_poin_3_crud_index_berhasil_menampilkan_daftar_kurir(): void
    {
        Kurir::factory()->create(['nama' => 'Budi Agung']);

        $this->getJson('/api/kurir')
            ->assertOk()
            ->assertJsonPath('data.0.nama', 'Budi Agung');
    }

    #[TestDox('index memiliki pagination dengan metadata halaman')]
    public function test_poin_4_index_memiliki_pagination_dengan_metadata_halaman(): void
    {
        Kurir::factory()->count(3)->create();

        $this->getJson('/api/kurir?per_halaman=2')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('metadata.halaman_saat_ini', 1)
            ->assertJsonPath('metadata.per_halaman', 2)
            ->assertJsonPath('metadata.total', 3);
    }

    #[TestDox('index default diurutkan berdasarkan nama kurir')]
    public function test_poin_4_index_default_diurutkan_berdasarkan_nama_kurir(): void
    {
        Kurir::factory()->create(['nama' => 'Citra']);
        Kurir::factory()->create(['nama' => 'Andi']);
        Kurir::factory()->create(['nama' => 'Budi']);

        $this->getJson('/api/kurir')
            ->assertOk()
            ->assertJsonPath('data.0.nama', 'Andi')
            ->assertJsonPath('data.1.nama', 'Budi')
            ->assertJsonPath('data.2.nama', 'Citra');
    }

    #[TestDox('index bisa diurutkan berdasarkan tanggal didaftarkan')]
    public function test_poin_4_index_bisa_diurutkan_berdasarkan_tanggal_didaftarkan(): void
    {
        Kurir::factory()->create([
            'nama' => 'Kurir Baru',
            'terdaftar_pada' => '2026-01-03 10:00:00',
        ]);
        Kurir::factory()->create([
            'nama' => 'Kurir Lama',
            'terdaftar_pada' => '2026-01-01 10:00:00',
        ]);

        $this->getJson('/api/kurir?urut=terdaftar_pada')
            ->assertOk()
            ->assertJsonPath('data.0.nama', 'Kurir Lama')
            ->assertJsonPath('data.1.nama', 'Kurir Baru');
    }

    #[TestDox('cari budi+agung berhasil menemukan nama Budiono Hadi Agung')]
    public function test_poin_4_cari_budi_agung_berhasil_menemukan_nama_budiono_hadi_agung(): void
    {
        Kurir::factory()->create(['nama' => 'Budiono Hadi Agung', 'tingkat' => 2]);
        Kurir::factory()->create(['nama' => 'Budi Santoso', 'tingkat' => 3]);
        Kurir::factory()->create(['nama' => 'Yusuf Agung', 'tingkat' => 4]);

        $this->getJson('/api/kurir?cari=budi+agung')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.nama', 'Budiono Hadi Agung');
    }

    #[TestDox('filter tingkat=2,3 hanya menampilkan kurir tingkat 2 dan 3')]
    public function test_poin_4_filter_tingkat_2_3_hanya_menampilkan_kurir_tingkat_2_dan_3(): void
    {
        Kurir::factory()->create(['nama' => 'Kurir Tingkat Dua', 'tingkat' => 2]);
        Kurir::factory()->create(['nama' => 'Kurir Tingkat Tiga', 'tingkat' => 3]);
        Kurir::factory()->create(['nama' => 'Kurir Tingkat Empat', 'tingkat' => 4]);

        $this->getJson('/api/kurir?tingkat=2,3&per_halaman=10')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.tingkat', 2)
            ->assertJsonPath('data.1.tingkat', 3);
    }

    #[TestDox('show mengembalikan semua data pada kurir')]
    public function test_poin_5_show_mengembalikan_semua_data_pada_kurir(): void
    {
        $kurir = Kurir::factory()->create([
            'nama' => 'Budi Agung',
            'surel' => 'budi.agung@example.com',
            'telepon' => '081234567890',
            'tingkat' => 2,
            'status' => 'aktif',
            'jenis_kendaraan' => 'motor',
            'plat_kendaraan' => 'B 1234 AG',
            'alamat' => 'Jl. Melati No. 10',
        ]);

        $this->getJson("/api/kurir/{$kurir->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $kurir->id)
            ->assertJsonPath('data.nama', 'Budi Agung')
            ->assertJsonPath('data.surel', 'budi.agung@example.com')
            ->assertJsonPath('data.telepon', '081234567890')
            ->assertJsonPath('data.tingkat', 2)
            ->assertJsonPath('data.status', 'aktif')
            ->assertJsonPath('data.jenis_kendaraan', 'motor')
            ->assertJsonPath('data.plat_kendaraan', 'B 1234 AG')
            ->assertJsonPath('data.alamat', 'Jl. Melati No. 10');
    }

    #[TestDox('store memvalidasi input dan menyimpan data ke database')]
    public function test_poin_6_store_memvalidasi_input_dan_menyimpan_data_ke_database(): void
    {
        $payload = $this->payloadValid();

        $this->postJson('/api/kurir', $payload)
            ->assertCreated()
            ->assertHeader('Location')
            ->assertJsonPath('data.nama', 'Budi Agung');

        $this->assertDatabaseHas('kurir', [
            'nama' => 'Budi Agung',
            'surel' => 'budi.agung@example.com',
            'tingkat' => 2,
        ]);

        $this->postJson('/api/kurir', array_merge($payload, ['tingkat' => 6]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('tingkat');
    }

    #[TestDox('update memvalidasi input dan menyimpan perubahan ke database')]
    public function test_poin_6_update_memvalidasi_input_dan_menyimpan_perubahan_ke_database(): void
    {
        $kurir = Kurir::factory()->create([
            'surel' => 'lama@example.com',
            'telepon' => '081111111111',
            'tingkat' => 1,
        ]);

        $this->patchJson("/api/kurir/{$kurir->id}", [
            'surel' => 'baru@example.com',
            'telepon' => '082222222222',
            'tingkat' => 5,
        ])
            ->assertOk()
            ->assertJsonPath('data.surel', 'baru@example.com')
            ->assertJsonPath('data.tingkat', 5);

        $this->assertDatabaseHas('kurir', [
            'id' => $kurir->id,
            'surel' => 'baru@example.com',
            'telepon' => '082222222222',
            'tingkat' => 5,
        ]);

        $this->patchJson("/api/kurir/{$kurir->id}", ['tingkat' => 0])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('tingkat');
    }

    #[TestDox('destroy menghapus data kurir dari database')]
    public function test_poin_7_destroy_menghapus_data_kurir_dari_database(): void
    {
        $kurir = Kurir::factory()->create();

        $this->deleteJson("/api/kurir/{$kurir->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('kurir', ['id' => $kurir->id]);
        $this->getJson("/api/kurir/{$kurir->id}")->assertNotFound();
    }

    #[TestDox('Security: parameter urut tidak valid ditolak agar nama kolom tidak bebas dari client')]
    public function test_security_parameter_urut_tidak_valid_ditolak(): void
    {
        $this->getJson('/api/kurir?urut=id')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('urut');
    }

    #[TestDox('Security: filter tingkat tidak valid ditolak sebelum query database')]
    public function test_security_filter_tingkat_tidak_valid_ditolak(): void
    {
        $this->getJson('/api/kurir?tingkat=2,9')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('tingkat');
    }

    /**
     * @return array<string, mixed>
     */
    private function payloadValid(): array
    {
        return [
            'nama' => 'Budi Agung',
            'surel' => 'budi.agung@example.com',
            'telepon' => '081234567890',
            'tingkat' => 2,
            'status' => 'aktif',
            'jenis_kendaraan' => 'motor',
            'plat_kendaraan' => 'B 1234 AG',
            'terdaftar_pada' => '2026-05-19 10:00:00',
            'alamat' => 'Jl. Melati No. 10',
        ];
    }
}
