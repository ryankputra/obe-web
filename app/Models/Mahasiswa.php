<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $fillable = [
        'prodi_id',
        'nim',
        'nama',
        'angkatan',
        'email',
        'no_hp',
        'jenis_kelamin',
        'alamat'
    ];

    protected $primaryKey = 'nim';
    public $incrementing = false;
    protected $keyType = 'string'; // Changed to string (NIM usually contains letters)

    public function prodi()
    {
        return $this->belongsTo(Prodi::class); // Removed second parameter as it follows convention
    }

    public function mataKuliahs()
    {
        return $this->belongsToMany(
            \App\Models\MataKuliah::class,
            'mahasiswa_matakuliah',
            'mahasiswa_nim',        // foreign key di pivot untuk Mahasiswa
            'mata_kuliah_kode_mk',  // foreign key di pivot untuk MataKuliah
            'nim',                  // local key di Mahasiswa
            'kode_mk'               // local key di MataKuliah
        );
    }
    
    public function penilaian()
    {
        return $this->hasOne(Penilaian::class, 'mahasiswa_nim', 'nim'); // Sesuaikan dengan kolom mahasiswa_nim
    }
}
