<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

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
}
