<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_prodi',
        'jumlah_mahasiswa'
    ];

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class); // Also works without second parameter
    }
}
