<?php

namespace Tests\Unit;

use App\Http\Requests\TambahKurirRequest;
use App\Http\Requests\UbahKurirRequest;
use Illuminate\Validation\Rules\Unique;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

class ValidasiKurir extends TestCase
{
    #[TestDox('Validasi tambah kurir mewajibkan field inti dan batasan nilai')]
    public function test_validasi_tambah_kurir_mewajibkan_field_inti_dan_batasan_nilai(): void
    {
        $rules = (new TambahKurirRequest)->rules();

        $this->assertContains('required', $rules['nama']);
        $this->assertContains('required', $rules['surel']);
        $this->assertContains('unique:kurir,surel', $rules['surel']);
        $this->assertContains('required', $rules['telepon']);
        $this->assertContains('unique:kurir,telepon', $rules['telepon']);
        $this->assertContains('between:1,5', $rules['tingkat']);
        $this->assertContains('in:aktif,nonaktif,ditangguhkan', $rules['status']);
        $this->assertContains('required', $rules['jenis_kendaraan']);
    }

    #[TestDox('Validasi ubah kurir mendukung partial update dan tetap menjaga constraint')]
    public function test_validasi_ubah_kurir_mendukung_partial_update_dan_tetap_menjaga_constraint(): void
    {
        $rules = (new UbahKurirRequest)->rules();

        $this->assertContains('sometimes', $rules['nama']);
        $this->assertContains('sometimes', $rules['surel']);
        $this->assertContains('sometimes', $rules['telepon']);
        $this->assertContains('sometimes', $rules['tingkat']);
        $this->assertContains('between:1,5', $rules['tingkat']);
        $this->assertContains('in:aktif,nonaktif,ditangguhkan', $rules['status']);
        $this->assertContainsOnlyInstancesOf(Unique::class, array_filter(
            $rules['surel'],
            fn (mixed $rule): bool => $rule instanceof Unique,
        ));
        $this->assertContainsOnlyInstancesOf(Unique::class, array_filter(
            $rules['telepon'],
            fn (mixed $rule): bool => $rule instanceof Unique,
        ));
    }
}
