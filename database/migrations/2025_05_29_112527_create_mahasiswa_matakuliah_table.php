<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mahasiswa_matakuliah', function (Blueprint $table) {
            $table->id();
            $table->string('mahasiswa_nim'); // ganti dari mahasiswa_id ke mahasiswa_nim
            $table->string('mata_kuliah_kode_mk');
            $table->timestamps();

            $table->foreign('mahasiswa_nim')->references('nim')->on('mahasiswas')->onDelete('cascade');
            $table->foreign('mata_kuliah_kode_mk')->references('kode_mk')->on('mata_kuliahs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa_matakuliah');
    }
};
