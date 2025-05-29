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
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom jika belum ada
            if (!Schema::hasColumn('users', 'dosen_id')) {
                $table->unsignedBigInteger('dosen_id')->nullable()->after('role');
                $table->foreign('dosen_id')->references('id')->on('dosens')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus foreign key jika ada, lalu kolomnya
            if (Schema::hasColumn('users', 'dosen_id')) {
                // Drop foreign key jika ada
                try {
                    $table->dropForeign(['dosen_id']);
                } catch (\Exception $e) {
                    // Ignore if foreign key doesn't exist
                }
                $table->dropColumn('dosen_id');
            }
        });
    }
};
