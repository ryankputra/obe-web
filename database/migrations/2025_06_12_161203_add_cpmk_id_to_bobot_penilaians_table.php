<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bobot_penilaians', function (Blueprint $table) {
            // Menambahkan kolom cpmk_id yang akan terhubung ke tabel cpmks
            $table->foreignId('cpmk_id')->nullable()->constrained('cpmks')->onDelete('cascade')->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('bobot_penilaians', function (Blueprint $table) {
            $table->dropForeign(['cpmk_id']);
            $table->dropColumn('cpmk_id');
        });
    }
};