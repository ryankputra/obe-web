<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cpl extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'kode_cpl',
        'deskripsi'
    ];

    public function cpmks()
    {
        return $this->hasMany(Cpmk::class, 'kode_cpl', 'kode_cpl');
    }
}
