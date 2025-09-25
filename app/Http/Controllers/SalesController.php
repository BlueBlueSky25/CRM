<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SalesController extends Controller
{
    public function index()
{
    
    $salesRole = Role::where('role_name', 'Sales')->first();
    
    if (!$salesRole) {
        
        return redirect()->back()->with('error', 'Role sales tidak ditemukan!');
    }
    
    
    $salesUsers = User::where('role_id', $salesRole->role_id)->get();
    
    $currentMenuId = view()->shared('currentMenuId', null);

    return view('layout.marketing', [
        'salesUsers' => $salesUsers,
        'salesRole' => $salesRole, 
        'currentMenuId' => $currentMenuId
    ]);
}

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:100|unique:users,username',
            'email'    => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone'    => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'address'   => 'nullable|string|max:255',
        ]);

        // Dapatkan role sales
        $salesRole = Role::where('role_name', 'Sales')->first();

        User::create([
            'username'       => $request->username,
            'email'          => $request->email,
            'password_hash'  => Hash::make($request->password),
            'role_id'        => $salesRole->role_id, // Otomatis role sales
            'phone'          => $request->phone,
            'birth_date'     => $request->birth_date,
            'address'        => $request->address,
            'is_active'      => true,
        ]);

        return redirect()->route('marketing')->with('success', 'Sales user berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:100|unique:users,username,' . $id . ',user_id',
            'email'    => 'nullable|email|unique:users,email,' . $id . ',user_id',
            'phone'    => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'address'   => 'nullable|string|max:255',
        ]);

        $data = [
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'birth_date' => $request->birth_date,
            'address' => $request->address,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ];

        if ($request->filled('password')) {
            $data['password_hash'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Sales user berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('marketing')->with('success', 'Sales user berhasil dihapus!');
    }
}