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
        Schema::create('cpmk_jenis_penilaian_bobot', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cpmk_id')->constrained('cpmks')->onDelete('cascade');
            $table->string('jenis_penilaian'); // e.g., 'keaktifan', 'tugas', 'uts', etc.
            $table->unsignedTinyInteger('bobot')->nullable();
            $table->timestamps();

            $table->unique(['cpmk_id', 'jenis_penilaian']); // Pastikan kombinasi cpmk_id dan jenis_penilaian unik
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cpmk_jenis_penilaian_bobot');
    }
};
