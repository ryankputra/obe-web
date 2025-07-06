<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('penilaians', function (Blueprint $table) {
            $table->unsignedBigInteger('cpmk_id')->after('mata_kuliah_kode_mk');

            // (opsional) tambahkan foreign key kalau ada tabel cpmks
            // $table->foreign('cpmk_id')->references('id')->on('cpmks')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('penilaians', function (Blueprint $table) {
            $table->dropColumn('cpmk_id');
        });
    }
};
