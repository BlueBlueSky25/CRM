<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // ini yang harus dipakai

class Akun extends Authenticatable
{
    use HasFactory;

    protected $table = 'akun';
    protected $primaryKey = 'user_id'; 
    public $timestamps = true;

    protected $fillable = [
        'username',
        'password_hash',
        'role_id',
        'is_active',
    ];

    // kasih tau laravel kolom password mana yang dipakai
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // relasi ke role
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }
}
