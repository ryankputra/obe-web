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
        Schema::table('mahasiswas', function (Blueprint $table) {
            // Mengubah tipe kolom 'no_hp' dari integer menjadi string (VARCHAR) dengan panjang 20.
            // Gunakan ->change() untuk memodifikasi kolom yang sudah ada.
            // Ini akan memerlukan library 'doctrine/dbal'.
            $table->string('no_hp', 20)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            // Mengembalikan 'no_hp' ke tipe integer (jika diperlukan untuk rollback).
            // HATI-HATI: Jika ada data nomor telepon yang lebih dari 10 digit atau
            // mengandung non-digit setelah diubah ke string, ini akan menyebabkan error
            // saat rollback ke integer.
            $table->integer('no_hp')->change();
        });
    }
};