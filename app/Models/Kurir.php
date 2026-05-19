<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kurir extends Model
{
    /** @use HasFactory<\Database\Factories\KurirFactory> */
    use HasFactory;

    protected $table = 'kurir';

    protected $fillable = [
        'nama',
        'surel',
        'telepon',
        'tingkat',
        'status',
        'jenis_kendaraan',
        'plat_kendaraan',
        'terdaftar_pada',
        'alamat',
    ];

    protected function casts(): array
    {
        return [
            'tingkat' => 'integer',
            'terdaftar_pada' => 'datetime',
        ];
    }
}
