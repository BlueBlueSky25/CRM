<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users'; // <- GANTI JADI 'users'
    protected $primaryKey = 'user_id';
    public $timestamps = true;

    protected $fillable = [
        'username', 'role_id', 'is_active', 'email', 'password_hash'
    ];

    protected $hidden = ['password_hash'];

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }
}