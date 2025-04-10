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
        // Use Schema::table to modify an existing table
        Schema::table('dosens', function (Blueprint $table) {
            $table->string('gelar')->after('nama'); // Replace 'existing_column_name' with the column you want to place it after
            $table->string('jenis_kelamin')->after('gelar');
            $table->integer('kontak')->after('email')->nullable();
            $table->string('prodi')->after('kompetensi'); // Replace 'existing_column_name' with the column you want to place it after
            // Replace 'existing_column_name' with the column you want to place it after

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the column if the migration is rolled back
        Schema::table('dosens', function (Blueprint $table) {
            $table->dropColumn('gelar'); // Replace 'existing_column_name' with the column you want to place it after
            $table->dropColumn('jenis_kelamin');
            $table->dropColumn('kontak');
            $table->dropColumn('prodi');
        });
    }
};
