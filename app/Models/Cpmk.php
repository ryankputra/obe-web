<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cpmk extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'kode_cpl',
        'kode_cpmk',
        'mata_kuliah',
        'deskripsi',
        'pic'
    ];

    public function cpl()
    {
        return $this->belongsTo(Cpl::class, 'kode_cpl', 'kode_cpl');
    }
}