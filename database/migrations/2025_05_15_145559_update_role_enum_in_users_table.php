<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateRoleEnumInUsersTable extends Migration
{
    public function up()
    {
        // Langkah 1: Ubah role kaprodi menjadi dosen (atau admin, tergantung kebutuhan)
        DB::table('users')
            ->where('role', 'kaprodi')
            ->update(['role' => 'dosen']);

        // Langkah 2: Ubah kolom role menjadi ENUM dengan hanya admin dan dosen
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'dosen') DEFAULT 'dosen'");
    }

    public function down()
    {
        // Kembalikan kolom role ke ENUM dengan admin, dosen, kaprodi
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'dosen', 'kaprodi') DEFAULT 'dosen'");
    }
}