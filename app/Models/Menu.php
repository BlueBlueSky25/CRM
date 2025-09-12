<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu'; // sesuaikan nama tabel kamu (menus / menu)
    protected $primaryKey = 'menu_id'; // ganti kalau pk kamu beda
    public $timestamps = false; // matikan kalau tabel tidak punya created_at & updated_at

    protected $fillable = [
        'nama_menu',
        'route',
        'icon',
    ];

    /**
     * Relasi ke Role
     * Asumsi kamu punya tabel pivot: role_menu (role_id, menu_id)
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_menu', 'menu_id', 'role_id');
    }
}
