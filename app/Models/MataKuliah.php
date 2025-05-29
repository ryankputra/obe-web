<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;
    protected $table = 'mata_kuliahs';

    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'deskripsi',
        'semester',
        'sks_teori',
        'sks_praktik',
        'status_mata_kuliah',
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
}
