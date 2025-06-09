<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\CpmkJenisPenilaianBobot; // Tambahkan import ini
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Cpmk extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'kode_cpl',
        'kode_cpmk',
        'mata_kuliah',
        'deskripsi',
        'pic',
        'bobot',
    ];
    

    public function cpl()
    {
        return $this->belongsTo(Cpl::class, 'kode_cpl', 'kode_cpl');
    }

    /**
     * Mendefinisikan relasi bahwa satu CPMK memiliki banyak bobot jenis penilaian.
     */
    public function jenisPenilaianBobot(): HasMany
    {
        // Asumsi foreign key di tabel cpmk_jenis_penilaian_bobot adalah 'cpmk_id'
        return $this->hasMany(CpmkJenisPenilaianBobot::class, 'cpmk_id', 'id');
    }
}