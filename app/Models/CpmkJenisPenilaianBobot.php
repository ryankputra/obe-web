<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CpmkJenisPenilaianBobot extends Model
{
    use HasFactory;

    protected $table = 'cpmk_jenis_penilaian_bobot'; // Sesuaikan jika nama tabel Anda berbeda

    protected $fillable = [
        'cpmk_id',
        'jenis_penilaian',
        'bobot',
    ];
}