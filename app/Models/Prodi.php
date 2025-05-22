<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $table = 'prodis';
    protected $fillable = ['nama_prodi', 'jumlah_mahasiswa', 'status'];

    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class);
    }
}