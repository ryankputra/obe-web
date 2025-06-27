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
        Schema::table('penilaians', function (Blueprint $table) {
            // Drop foreign keys
            $table->dropForeign('penilaians_mahasiswa_nim_foreign');
            $table->dropForeign('penilaians_mata_kuliah_kode_mk_foreign');

            // Drop unique index lama
            $table->dropUnique('penilaians_mahasiswa_nim_mata_kuliah_kode_mk_unique');

            // Tambah unique index baru
            $table->unique(['mahasiswa_nim', 'mata_kuliah_kode_mk', 'cpmk_id'], 'penilaians_mahasiswa_nim_mk_cpmk_unique');

            // Tambah foreign key kembali
            $table->foreign('mahasiswa_nim')->references('nim')->on('mahasiswas')->onDelete('cascade');
            $table->foreign('mata_kuliah_kode_mk')->references('kode_mk')->on('mata_kuliahs')->onDelete('cascade');
            // (Opsional) Jika ingin, tambahkan juga FK untuk cpmk_id:
            $table->foreign('cpmk_id')->references('id')->on('cpmks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilaians', function (Blueprint $table) {
            $table->dropForeign(['mahasiswa_nim']);
            $table->dropForeign(['mata_kuliah_kode_mk']);
            $table->dropForeign(['cpmk_id']); // jika FK cpmk_id ditambahkan

            $table->dropUnique('penilaians_mahasiswa_nim_mk_cpmk_unique');
            $table->unique(['mahasiswa_nim', 'mata_kuliah_kode_mk'], 'penilaians_mahasiswa_nim_mata_kuliah_kode_mk_unique');

            $table->foreign('mahasiswa_nim')->references('nim')->on('mahasiswas')->onDelete('cascade');
            $table->foreign('mata_kuliah_kode_mk')->references('kode_mk')->on('mata_kuliahs')->onDelete('cascade');
        });
    }
};
