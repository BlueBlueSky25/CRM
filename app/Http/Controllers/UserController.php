<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * List semua user
     */
    public function index()
    {
        $users = User::with('role')->paginate(5);
        $roles = Role::all(); 
        

        
        return view('layout.user', compact('users', 'roles'));
    }





   public function store(Request $request)
{
    $request->validate([
        'username' => 'required|string|max:100|unique:users,username',   
        'email'    => 'nullable|email|unique:users,email', 
        'password' => 'required|string|min:6',
        'role_id'  => 'required|exists:roles,role_id',
        'is_active' => 'sometimes|boolean',
        'phone'      => 'nullable|string|max:20',
        'birth_date' => 'nullable|date|before_or_equal:today',
        'address'    => 'nullable|string|max:1000',
    ]);

    User::create([
        'username'       => $request->username,                        
        'email'          => $request->email,
        'password_hash'  => Hash::make($request->password),
        'phone'          => $request->phone,
        'birth_date'     => $request->birth_date,
        'address'        => $request->address,
        'role_id'        => $request->role_id,                        
        'is_active'      => $request->input('is_active', true),       
    ]);

    return redirect()->route('user')->with('success', 'User berhasil ditambahkan!');
}

public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'username' => 'required|string|max:100|unique:users,username,' . $id . ',user_id', 
        'email'    => 'nullable|email|unique:users,email,' . $id . ',user_id', 
        'role_id'  => 'required|exists:roles,role_id',
        'is_active' => 'sometimes|boolean',
        'phone'      => 'nullable|string|max:20',                    
        'birth_date' => 'nullable|date|before_or_equal:today',        
        'address'    => 'nullable|string|max:1000',
    ]);

    $data = [
        'username' => $request->username,
        'email' => $request->email,
        'role_id' => $request->role_id,
        'is_active' => $request->has('is_active') ? 1 : 0,
        'phone'          => $request->phone,
        'birth_date'     => $request->birth_date,
        'address'        => $request->address,
    ];

    
    if ($request->filled('password')) {
        $data['password_hash'] = Hash::make($request->password);
    }

    $user->update($data);

    // REDIRECT KE HALAMAN YANG BENAR
    return redirect()->back()->with('success', 'User berhasil diperbarui!');
}

    /**
     * Hapus user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('user')->with('success', 'User berhasil dihapus!');
    }
}