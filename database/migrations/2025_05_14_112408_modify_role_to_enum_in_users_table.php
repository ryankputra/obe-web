<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Langkah 1: Pemetaan data mahasiswa ke dosen
        DB::table('users')
            ->where('role', 'mahasiswa')
            ->update(['role' => 'dosen']);

        // Langkah 2: Ubah kolom role menjadi ENUM
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'dosen', 'kaprodi'])
                  ->default('dosen')
                  ->change();
        });
    }

    public function down()
    {
        // Kembalikan ke tipe VARCHAR dengan nilai default lama
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default(null)->change();
        });

        // Opsional: Kembalikan data mahasiswa jika perlu
        DB::table('users')
            ->where('role', 'dosen')
            ->whereNotIn('id', function ($query) {
                $query->select('id')->from('users')->where('role', 'admin');
            })
            ->update(['role' => 'mahasiswa']);
    }
};