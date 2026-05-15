<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'mahasiswa_id', 'dosen_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }
    
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }
    
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    
    public function isDosen()
    {
        return $this->role === 'dosen';
    }
    
    public function isMahasiswa()
    {
        return $this->role === 'mahasiswa';
    }
}