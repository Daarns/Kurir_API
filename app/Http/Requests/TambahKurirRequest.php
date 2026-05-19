<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TambahKurirRequest extends FormRequest
{
    /**
     * Menentukan apakah request diizinkan.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Mendapatkan aturan validasi request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'surel' => ['required', 'email:rfc', 'max:255', 'unique:kurir,surel'],
            'telepon' => ['required', 'string', 'max:30', 'regex:/^[0-9+\-\s()]+$/', 'unique:kurir,telepon'],
            'tingkat' => ['required', 'integer', 'between:1,5'],
            'status' => ['required', 'string', 'in:aktif,nonaktif,ditangguhkan'],
            'jenis_kendaraan' => ['required', 'string', 'max:50'],
            'plat_kendaraan' => ['nullable', 'string', 'max:20'],
            'terdaftar_pada' => ['nullable', 'date'],
            'alamat' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
