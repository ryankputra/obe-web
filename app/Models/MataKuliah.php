<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliahs'; // Update table name to match database

    // DIUBAH: Menambahkan 'status_mata_kuliah' ke daftar yang boleh diisi
    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'sks_teori',
        'sks_praktik',
        'semester',
        'deskripsi',
        'status_mata_kuliah' // <-- TAMBAHKAN BARIS INI
    ];

    protected $primaryKey = 'kode_mk';
    public $incrementing = false;
    protected $keyType = 'string';

    public function getRouteKeyName()
    {
        return 'kode_mk';
    }

    public function dosens()
    {
        return $this->belongsToMany(Dosen::class, 'dosen_matakuliah', 'mata_kuliah_kode_mk', 'dosen_id');
    }

    public function mahasiswas()
    {
        return $this->belongsToMany(
            \App\Models\Mahasiswa::class,
            'mahasiswa_matakuliah',
            'mata_kuliah_kode_mk',  // foreign key di pivot untuk MataKuliah
            'mahasiswa_nim',        // foreign key di pivot untuk Mahasiswa
            'kode_mk',              // local key di MataKuliah
            'nim'                   // local key di Mahasiswa
        );
    }

    /**
     * Mendefinisikan relasi bahwa satu Mata Kuliah memiliki banyak CPMK.
     * Relasi ini didasarkan pada kolom 'mata_kuliah' di tabel 'cpmks'
     * yang merujuk ke kolom 'kode_mk' (primary key) di tabel 'mata_kuliahs'.
     */
    public function cpmks(): HasMany
    {
        // Parameter kedua adalah foreign key di tabel cpmks (defaultnya mata_kuliah_kode_mk jika mengikuti konvensi)
        // Parameter ketiga adalah local key di tabel mata_kuliahs (defaultnya kode_mk karena itu primaryKey model ini)
        return $this->hasMany(Cpmk::class, 'mata_kuliah', 'kode_mk');
    }
}