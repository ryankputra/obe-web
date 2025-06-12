<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventAkademik extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'events_akademik';

    // Kolom yang dapat diisi secara massal (mass assignable)
    protected $fillable = [
        'tanggal_event',
        'deskripsi',
        'tipe',
    ];
}