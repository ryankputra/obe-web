<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToProdisTable extends Migration
{
    public function up()
    {
        Schema::table('prodis', function (Blueprint $table) {
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->after('jumlah_mahasiswa');
        });
    }

    public function down()
    {
        Schema::table('prodis', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}