<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'dosen_id',
        'avatar', // Tambahkan ini agar bisa diisi secara massal
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function dosen()
    {
        return $this->belongsTo(\App\Models\Dosen::class, 'dosen_id');
    }
    public function mataKuliahYangDiampu()
    {
        // Only works if user is dosen and has dosen_id
        if ($this->role === 'dosen' && $this->dosen) {
            return $this->dosen->mataKuliahs();
        }
        // Return empty query if not dosen
        return \App\Models\MataKuliah::query()->whereRaw('0=1');
    }
}
