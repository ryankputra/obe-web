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
    ];

    public function mataKuliahs()
    {
        return $this->belongsToMany(MataKuliah::class, 'dosen_matakuliah', 'dosen_id', 'mata_kuliah_kode_mk');
    }
}
