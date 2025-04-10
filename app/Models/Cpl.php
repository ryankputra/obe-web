<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cpl extends Model
{
    /** @use HasFactory<\Database\Factories\CplFactory> */
    use HasFactory;
    protected $fillable = [
        'kode_cpl',
        'deskripsi'
    ];
}
