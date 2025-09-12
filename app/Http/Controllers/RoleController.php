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
        $roles = Role::all();
        $users = User::all();
        $menus = Menu::all();
        
        return view('layout.role', compact('roles', 'users', 'menus'));
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

        $request->validate([
            'role_name'   => 'required|string|max:100|unique:roles,role_name,' . $id . ',role_id',
            'description' => 'nullable|string'
        ]);

        $role->update([
            'role_name'   => $request->role_name,
            'description' => $request->description,
        ]);

        return redirect()->route('role')->with('success', 'Role berhasil diupdate');
    }

    /**
     * Hapus role
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('role')->with('success', 'Role berhasil dihapus');
    }

    /**
     * Assign menu + permission ke role
     */
    public function assignMenu(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        // format request: menu_id => [can_view, can_create, can_edit, can_delete]
        $syncData = [];
        foreach ($request->menus as $menuId => $perms) {
            $syncData[$menuId] = [
                'can_view'   => in_array('view', $perms),
                'can_create' => in_array('create', $perms),
                'can_edit'   => in_array('edit', $perms),
                'can_delete' => in_array('delete', $perms),
            ];
        }

        $role->menus()->sync($syncData);

        return redirect()->back()->with('success', 'Menu & permission berhasil diupdate');
    }
}