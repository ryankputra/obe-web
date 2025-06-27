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
            $table->unsignedBigInteger('cpmk_id')->nullable()->after('mata_kuliah_kode_mk');
            // Jika ingin relasi, bisa tambahkan foreign key:
            // $table->foreign('cpmk_id')->references('id')->on('cpmks')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilaians', function (Blueprint $table) {
            $table->dropColumn('cpmk_id');
        });
    }
};
