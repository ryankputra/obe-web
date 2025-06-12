<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bobot_penilaians', function (Blueprint $table) {
            $table->id();

            // --- PERUBAHAN DI BAGIAN INI ---
            // Menggunakan tipe data string yang sama dengan kolom kode_mk
            $table->string('mata_kuliah_kode_mk');

            // Membuat foreign key yang merujuk ke kolom 'kode_mk' di tabel 'mata_kuliahs'
            $table->foreign('mata_kuliah_kode_mk')
                  ->references('kode_mk')->on('mata_kuliahs')
                  ->onDelete('cascade');
            // --- AKHIR PERUBAHAN ---

            $table->decimal('keaktifan', 5, 2)->default(0);
            $table->decimal('tugas', 5, 2)->default(0);
            $table->decimal('proyek', 5, 2)->default(0);
            $table->decimal('uts', 5, 2)->default(0);
            $table->decimal('uas', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bobot_penilaians');
    }
};