<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UbahKurirRequest extends FormRequest
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
            'nama' => ['sometimes', 'required', 'string', 'max:255'],
            'surel' => [
                'sometimes',
                'required',
                'email:rfc',
                'max:255',
                Rule::unique('kurir', 'surel')->ignore($this->route('kurir')),
            ],
            'telepon' => [
                'sometimes',
                'required',
                'string',
                'max:30',
                'regex:/^[0-9+\-\s()]+$/',
                Rule::unique('kurir', 'telepon')->ignore($this->route('kurir')),
            ],
            'tingkat' => ['sometimes', 'required', 'integer', 'between:1,5'],
            'status' => ['sometimes', 'required', 'string', 'in:aktif,nonaktif,ditangguhkan'],
            'jenis_kendaraan' => ['sometimes', 'required', 'string', 'max:50'],
            'plat_kendaraan' => ['nullable', 'string', 'max:20'],
            'terdaftar_pada' => ['nullable', 'date'],
            'alamat' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
