<?php

namespace Database\Factories;

use App\Models\Kurir;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kurir>
 */
class KurirFactory extends Factory
{
    /**
     * Menentukan data bawaan model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'surel' => fake()->unique()->safeEmail(),
            'telepon' => fake()->unique()->numerify('08##########'),
            'tingkat' => fake()->numberBetween(1, 5),
            'status' => fake()->randomElement(['aktif', 'nonaktif', 'ditangguhkan']),
            'jenis_kendaraan' => fake()->randomElement(['motor', 'mobil', 'van']),
            'plat_kendaraan' => strtoupper(fake()->bothify('?? #### ??')),
            'terdaftar_pada' => fake()->dateTimeBetween('-1 year', 'now'),
            'alamat' => fake()->address(),
        ];
    }
}
