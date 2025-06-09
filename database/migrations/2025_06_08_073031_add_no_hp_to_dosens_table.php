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
        Schema::table('dosens', function (Blueprint $table) {
            // Hapus kolom 'no_hp' jika ada
            if (Schema::hasColumn('dosens', 'no_hp')) {
                $table->dropColumn('no_hp');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosens', function (Blueprint $table) {
            // Tambahkan kembali kolom 'no_hp' jika migrasi di-rollback
            $table->string('no_hp')->nullable()->after('kontak'); // Sesuaikan posisi jika perlu
        });
    }
};
