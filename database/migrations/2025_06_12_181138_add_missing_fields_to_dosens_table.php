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
            // Mengubah tipe kolom 'nidn' menjadi string (VARCHAR) dan memastikan unik.
            // Ini untuk NIDN yang bisa > 10 digit.
            // Pastikan Anda sudah menginstal 'doctrine/dbal' jika belum: composer require doctrine/dbal
            $table->string('nidn')->unique()->change();

            // Mengubah tipe kolom 'kontak' menjadi string (VARCHAR) dengan panjang 20, dan bisa nullable.
            // Ini untuk nomor telepon yang bisa > 10 digit dan mengandung karakter non-numeric.
            // Gunakan ->change() karena kolom 'kontak' sudah ada di database.
            $table->string('kontak', 20)->nullable()->change();

            // Kolom 'gelar', 'jenis_kelamin', 'prodi'
            // Diasumsikan kolom-kolom ini sudah ada di tabel 'dosens' dan tidak perlu diubah lagi.
            // Jika ada masalah lain dengan kolom-kolom ini, perlu diinvestigasi secara terpisah.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosens', function (Blueprint $table) {
            // Mengembalikan 'nidn' ke tipe integer (jika diperlukan untuk rollback)
            // HATI-HATI: Jika ada NIDN yang lebih dari 10 digit, ini akan menyebabkan error
            // saat rollback jika tipe integer tidak dapat menampungnya.
            $table->integer('nidn')->change();

            // Mengembalikan 'kontak' ke tipe sebelumnya (jika diperlukan untuk rollback)
            // Ini bisa jadi rumit jika data yang disimpan adalah string dan tidak bisa diubah kembali ke integer.
            // Jika Anda tidak yakin dengan tipe sebelumnya, mungkin lebih aman tidak menyertakan rollback untuk 'kontak' ini.
            // Contoh jika sebelumnya integer: $table->integer('kontak')->nullable()->change();
            // Contoh jika sebelumnya string dengan panjang berbeda: $table->string('kontak', 10)->nullable()->change();
        });
    }
};