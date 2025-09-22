<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users'; // <- GANTI JADI 'users'
    protected $primaryKey = 'user_id';
    public $timestamps = true;

    protected $fillable = [
        'username', 'role_id', 'is_active', 'email', 'password_hash','is_active'
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




   public function canAccess($menuId, $action)
{
    if ($this->role && $this->role->role_name === 'superadmin') {
        return true;
    }

    if (!$this->role) return false;

    // PERBAIKAN: Gunakan where() bukan wherePivot()
    $roleMenu = $this->role->menus()
                          ->where('menu.menu_id', $menuId)
                          ->first();

    if (!$roleMenu) return false;

    return match($action) {
        'view' => (bool) $roleMenu->pivot->can_view,
        'create' => (bool) $roleMenu->pivot->can_create,
        'edit' => (bool) $roleMenu->pivot->can_edit,
        'delete' => (bool) $roleMenu->pivot->can_delete,
        'assign' => (bool) $roleMenu->pivot->can_assign,
        default => false
    };
}





public function canAccessCurrent($action)
{
    $menuId = currentMenuId();
    if (!$menuId) return false;
    return $this->canAccess($menuId, $action);
}


    // TAMBAHAN: Helper method untuk cek multiple permissions sekaligus
    public function hasAnyAccess($menuId)
    {
        return $this->canAccess($menuId, 'view') || 
               $this->canAccess($menuId, 'create') || 
               $this->canAccess($menuId, 'edit') || 
               $this->canAccess($menuId, 'delete');
    }

}