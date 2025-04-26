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
        'nohp',
        'jenis_kelamin'
    ];

    protected $primaryKey = 'nim';
    public $incrementing = false;
    protected $keyType = 'string'; // Changed to string (NIM usually contains letters)

    public function prodi()
    {
        return $this->belongsTo(Prodi::class); // Removed second parameter as it follows convention
    }
}