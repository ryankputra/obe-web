<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $fillable = [
        'nidn',
        'nama',
        'gelar',
        'jenis_kelamin',
        'email',
        'kontak',
        'jabatan',
        'kompetensi',
        'prodi',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'kewarganegaraan',
    ];

    public function mataKuliahs()
    {
        return $this->belongsToMany(
            \App\Models\MataKuliah::class,
            'dosen_matakuliah',
            'dosen_id',
            'mata_kuliah_kode_mk',
            'id',
            'kode_mk'
        );
    }
}
