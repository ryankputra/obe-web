<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Pastikan ini ada
use Illuminate\Database\Eloquent\Model;

class BobotPenilaian extends Model
{
    use HasFactory; // Tambahkan ini jika Anda menggunakan Factory untuk seeding/testing

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cpmk_jenis_penilaian_bobot'; // <-- PENTING: Tentukan nama tabel yang benar

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cpmk_id',
        'jenis_penilaian',
        'bobot',
        // 'created_at', // Biasanya diisi otomatis oleh Laravel
        // 'updated_at', // Biasanya diisi otomatis oleh Laravel
    ];

    /**
     * Get the CPMK that owns the BobotPenilaian.
     */
    public function cpmk()
    {
        return $this->belongsTo(Cpmk::class, 'cpmk_id');
    }
}