<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Role;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with(['roles', 'parent'])->orderBy('order', 'asc')->get();
        $roles = Role::all();
        
        return view('layout.menu', compact('menus', 'roles'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $data = $request->validate([
        'nama_menu' => 'required|string|max:100',
        'route'     => 'nullable|string|max:255',
        'icon'      => 'nullable|string|max:50',
        'order'     => 'nullable|integer|min:1',
        'parent_id' => 'nullable|exists:menu,menu_id'
    ]);

        if (!empty($data['route'])) {
        $data['route'] = ltrim($data['route'], '/');
    }

    // Kalau order kosong → taruh di paling bawah
        $data['order'] = $data['order'] ?? (Menu::max('order') + 1);
        $menu = Menu::create($data);

        return redirect()->route('menu')->with('success', 'Menu berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $data = $request->validate([
        'nama_menu' => 'required|string|max:100',
        'route'     => 'nullable|string|max:255',
        'icon'      => 'nullable|string|max:50',
        'order'     => 'nullable|integer|min:1',
        'parent_id' => 'nullable|exists:menu,menu_id'
    ]);


        if (!empty($data['route'])) {
        $data['route'] = ltrim($data['route'], '/');
    }

    // TAMBAH VALIDASI: Cegah menu jadi parent dari dirinya sendiri
        if ($data['parent_id'] == $menu->menu_id) {
            return redirect()->route('menu')->with('error', 'Menu tidak bisa menjadi parent dari dirinya sendiri!');
        }

        // TAMBAH VALIDASI: Cegah circular reference (child jadi parent dari parent-nya)
        if ($data['parent_id'] && $this->wouldCreateCircularReference($menu->menu_id, $data['parent_id'])) {
            return redirect()->route('menu')->with('error', 'Tidak bisa membuat referensi melingkar pada menu!');
        }

    // Kalau order kosong → taruh di paling bawah
        $data['order'] = $data['order'] ?? (Menu::max('order') + 1);

        $menu->update($data);

        return redirect()->route('menu')->with('success', 'Menu berhasil diperbarui');
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();

         if ($menu->hasChildren()) {
            return redirect()->route('menu')->with('error', 'Tidak bisa menghapus menu yang masih memiliki sub-menu!');
        }

        return redirect()->route('menu')->with('success', 'Menu berhasil dihapus');
    }
    private function wouldCreateCircularReference($menuId, $parentId)
    {
        if (!$parentId) return false;

        $parent = Menu::find($parentId);
        while ($parent) {
            if ($parent->menu_id == $menuId) {
                return true;
            }
            $parent = $parent->parent;
        }
        return false;
    }

    // TAMBAH FUNCTION INI: API endpoint untuk ambil parent menu (buat AJAX kalau diperlukan)
    public function getParentMenus()
    {
        $parentMenus = Menu::whereNull('parent_id')
                          ->select('menu_id', 'nama_menu')
                          ->orderBy('nama_menu')
                          ->get();
        
        return response()->json($parentMenus);
    }



    
}