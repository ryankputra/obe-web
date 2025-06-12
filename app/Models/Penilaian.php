<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // DIUBAH: Pastikan semua kolom yang akan disimpan ada di sini
    protected $fillable = [
        'mahasiswa_nim',
        'mata_kuliah_kode_mk',
        'keaktifan',
        'tugas',
        'proyek',       // <-- Memperbaiki Proyek yang tidak tersimpan
        'kuis',         // <-- Menambahkan Kuis
        'uts',
        'uas',
        'nilai_akhir',  // <-- Menambahkan nilai_akhir agar bisa disimpan
    ];

    /**
     * Get the mahasiswa that owns the penilaian.
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_nim', 'nim');
    }

    /**
     * Get the mata kuliah that a penilaian belongs to.
     */
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_kode_mk', 'kode_mk');
    }
}