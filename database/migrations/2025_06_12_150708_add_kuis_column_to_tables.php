<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Menambahkan kolom 'kuis' ke tabel penilaians
        Schema::table('penilaians', function (Blueprint $table) {
            $table->decimal('kuis', 5, 2)->nullable()->after('proyek');
        });

        // Menambahkan kolom 'kuis' ke tabel bobot_penilaians
        Schema::table('bobot_penilaians', function (Blueprint $table) {
            $table->decimal('kuis', 5, 2)->default(0)->after('proyek');
        });
    }

    public function down(): void
    {
        Schema::table('penilaians', function (Blueprint $table) {
            $table->dropColumn('kuis');
        });

        Schema::table('bobot_penilaians', function (Blueprint $table) {
            $table->dropColumn('kuis');
        });
    }
};