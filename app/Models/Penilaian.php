<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_nim',
        'mata_kuliah_kode_mk',
        'tugas',
        'keaktifan',
        'uts',
        'uas',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_nim', 'nim'); // Relasi ke model Mahasiswa
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_kode_mk', 'kode_mk'); // Relasi ke model MataKuliah
    }
}
