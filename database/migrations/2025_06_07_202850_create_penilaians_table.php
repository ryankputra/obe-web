// database/migrations/xxxx_xx_xx_xxxxxx_create_penilaians_table.php
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
        Schema::create('penilaians', function (Blueprint $table) {
            $table->id();
        
            $table->string('mahasiswa_nim');
            $table->string('mata_kuliah_kode_mk');

            $table->decimal('tugas', 5, 2)->nullable();
            $table->decimal('keaktifan', 5, 2)->nullable();
            $table->decimal('uts', 5, 2)->nullable();
            $table->decimal('uas', 5, 2)->nullable();
            $table->timestamps();

            // Definisi foreign key secara eksplisit
            $table->foreign('mahasiswa_nim')->references('nim')->on('mahasiswas')->onDelete('cascade');
            $table->foreign('mata_kuliah_kode_mk')->references('kode_mk')->on('mata_kuliahs')->onDelete('cascade');

            // Tambahkan unique constraint untuk memastikan kombinasi mahasiswa dan mata kuliah unik
            $table->unique(['mahasiswa_nim', 'mata_kuliah_kode_mk']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaians');
    }
};
