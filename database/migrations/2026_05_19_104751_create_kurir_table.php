<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration.
     */
    public function up(): void
    {
        Schema::create('kurir', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('surel')->unique();
            $table->string('telepon', 30)->unique();
            $table->unsignedTinyInteger('tingkat');
            $table->string('status', 20)->default('aktif');
            $table->string('jenis_kendaraan', 50);
            $table->string('plat_kendaraan', 20)->nullable();
            $table->timestamp('terdaftar_pada')->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();

            $table->index('nama');
            $table->index('tingkat');
            $table->index('terdaftar_pada');
        });
    }

    /**
     * Membatalkan migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('kurir');
    }
};
