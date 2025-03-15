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
        Schema::table('mata_kuliahs', function (Blueprint $table) {
            $table->string('status_mata_kuliah')->after('sks_praktik'); // Replace 'existing_column_name' with the column you want to place it after
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the column if the migration is rolled back
        Schema::table('mata_kuliahs', function (Blueprint $table) {
            $table->dropColumn('status_mata_kuliah');
        });
    }
};