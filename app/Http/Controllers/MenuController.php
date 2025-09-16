<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Role;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('roles')->orderBy('order', 'asc')->get();
        $roles = Role::all();
        
        return view('layout.menu', compact('menus', 'roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
        'nama_menu' => 'required|string|max:100',
        'route'     => 'nullable|string|max:255',
        'icon'      => 'nullable|string|max:50',
        'order'     => 'nullable|integer|min:1'
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
    ]);


        if (!empty($data['route'])) {
        $data['route'] = ltrim($data['route'], '/');
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

        return redirect()->route('menu')->with('success', 'Menu berhasil dihapus');
    }
}