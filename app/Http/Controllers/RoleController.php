<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * List semua role
     */
    public function index()
    {
        $roles = Role::paginate(5);
        $users = User::all();
        $menus = Menu::all();

        
        
        // Ambil semua permissions yang sudah ada, digroup berdasarkan role_id
        $rolePermissions = [];
        foreach ($roles as $role) {
            $permissions = $role->menus()->get();
            $rolePermissions[$role->role_id] = [];
            
            foreach ($permissions as $menu) {
                if ($menu->pivot->can_view) {
                    $rolePermissions[$role->role_id][] = [
                        'menu_id' => $menu->menu_id,
                        'permission_type' => 'view'
                    ];
                }
                if ($menu->pivot->can_create) {
                    $rolePermissions[$role->role_id][] = [
                        'menu_id' => $menu->menu_id,
                        'permission_type' => 'create'
                    ];
                }
                if ($menu->pivot->can_edit) {
                    $rolePermissions[$role->role_id][] = [
                        'menu_id' => $menu->menu_id,
                        'permission_type' => 'edit'
                    ];
                }
                if ($menu->pivot->can_delete) {
                    $rolePermissions[$role->role_id][] = [
                        'menu_id' => $menu->menu_id,
                        'permission_type' => 'delete'
                    ];
                if ($menu->pivot->can_assign) {
                    $rolePermissions[$role->role_id][] = [
                        'menu_id' => $menu->menu_id,
                        'permission_type' => 'assign'
                    ];
                    
                    }
                    
                }
            }
        }
        
        return view('layout.role', compact('roles', 'users', 'menus', 'rolePermissions'));
    }

    /**
     * Simpan role baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'role_name'   => 'required|string|max:100|unique:roles,role_name',
            'description' => 'nullable|string'
        ]);

        $role = Role::create([
            'role_name'   => $request->role_name,
            'description' => $request->description,
        ]);

        return redirect()->route('role')->with('success', 'Role berhasil ditambahkan');
    }

    /**
     * Update role
     */
    public function update(Request $request, $id)
{
    $role = Role::findOrFail($id);

    // Super Admin tidak bisa diedit
    if ($role->role_name === 'superadmin') {
        return redirect()->back()->with('error', 'Cannot edit Super Admin role');
    }

    $request->validate([
        'role_name'   => 'required|string|max:100|unique:roles,role_name,' . $id . ',role_id',
        'description' => 'nullable|string'
    ]);

    $role->update([
        'role_name'   => $request->role_name,
        'description' => $request->description,
    ]);

    return redirect()->back()->with('success', 'Role berhasil diupdate');
}

public function destroy($id)
{
    $role = Role::findOrFail($id);

    // Super Admin tidak bisa dihapus
    if ($role->role_name === 'superadmin') {
        return redirect()->back()->with('error', 'Cannot delete Super Admin role');
    }

    $role->delete();

    return redirect()->back()->with('success', 'Role berhasil dihapus');
}
    /**
     * Assign menu + permission ke role
     */
    public function assignMenu(Request $request, $id)
{   
    
    $role = Role::findOrFail($id);

    $syncData = [];
    foreach ($request->menus as $menuId => $perms) {
        $syncData[$menuId] = [
            'can_view'   => in_array('view', $perms),
            'can_create' => in_array('create', $perms),
            'can_edit'   => in_array('edit', $perms),
            'can_delete' => in_array('delete', $perms),
            'can_assign' => in_array('assign', $perms),
        ];
    }

    $role->menus()->sync($syncData);

    return redirect()->back()->with('success', 'Permissions updated successfully!');
}

public function assignAllPermissionsToSuperAdmin()
{
    $superAdmin = Role::where('role_name', 'superadmin')->first();
    $menus = Menu::all();

    $syncData = [];
    foreach ($menus as $menu) {
        $syncData[$menu->menu_id] = [
            'can_view'   => true,
            'can_create' => true,
            'can_edit'   => true,
            'can_delete' => true,
            'can_assign' => true,
        ];
    }

    $superAdmin->menus()->sync($syncData);
}
}