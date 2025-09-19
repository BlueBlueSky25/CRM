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
        'order',
    ];

    /**
     * Relasi ke Role
     * Asumsi kamu punya tabel pivot: role_menu (role_id, menu_id)
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_menu', 'menu_id', 'role_id')
                    ->withPivot('can_view', 'can_create', 'can_edit', 'can_delete', 'can_assign');
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id', 'menu_id');
    }

    // TAMBAH INI: Relationship untuk child menu
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id', 'menu_id')->orderBy('order');
    }

    // TAMBAH INI: Scope untuk ambil parent menu saja
    public function scopeParentOnly($query)
    {
        return $query->whereNull('parent_id');
    }

    // TAMBAH INI: Helper method untuk cek apakah menu punya children
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    // TAMBAH INI: Helper method untuk get semua children yang aktif
    public function getActiveChildren()
    {
        return $this->children()->where('is_active', true)->get();
    }
}
