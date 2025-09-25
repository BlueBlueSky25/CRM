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
        $users = User::with('role')->get();
        $roles = Role::all(); 
        

        
        return view('layout.user', compact('users', 'roles'));
    }


//     public function salesManagement()
//     {
//         // Ambil role Sales
//         $salesRole = Role::where('role_name', 'Sales')->first();
    
//     if (!$salesRole) {
//         return redirect()->back()->with('error', 'Role Sales tidak ditemukan!');
//     }

//     // Ambil semua user dengan role Sales
//     $salesUsers = User::with('role')
//                      ->where('role_id', $salesRole->role_id)
//                      ->get();

//     return view('layout.marketing', compact('salesUsers', 'salesRole'));
//     }


//    public function storeSales(Request $request)
// {
//     try {
//         // 1. Cari role Sales dulu
//         $salesRole = Role::where('role_name', 'Sales')->first();
        
//         if (!$salesRole) {
//             dd('Role Sales tidak ditemukan!');
//         }

//         // 2. Validasi
//         $validated = $request->validate([
//             'full_name' => 'required|string|max:100|unique:users,username',
//             'email' => 'required|email|unique:users,email',
//             'password' => 'required|min:6',
//             'phone' => 'nullable|string|max:20',
//             'birth_date' => 'nullable|date|before_or_equal:today',
//             'address' => 'nullable|string|max:1000',
//             'status' => 'required|in:active,inactive',
//         ]);

//         dd('Validation passed', $validated, 'Sales Role ID: ' . $salesRole->role_id);

//         // 3. Create user
//         $user = User::create([
//             'username'      => $request->full_name,
//             'email'         => $request->email,
//             'password_hash' => Hash::make($request->password),
//             'phone'         => $request->phone,
//             'birth_date'    => $request->birth_date,
//             'address'       => $request->address,
//             'is_active'     => $request->status === 'active' ? 1 : 0,
//             'role_id'       => $salesRole->role_id,
//         ]);

//         dd('User created successfully', $user);

//     } catch (\Illuminate\Validation\ValidationException $e) {
//         dd('Validation Error', $e->errors());
//     } catch (\Exception $e) {
//         dd('General Error', $e->getMessage(), $e->getTrace());
//     }

//     return redirect()->back()->with('success', 'Sales berhasil ditambahkan!');
// }





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
        'role_id'        => $request->role_id,                        // ✅ Dari form
        'is_active'      => $request->input('is_active', true),       // ✅ Default true
    ]);

    return redirect()->route('user')->with('success', 'User berhasil ditambahkan!');
}

public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'username' => 'required|string|max:100|unique:users,username,' . $id . ',user_id', // <- GANTI 'akun' JADI 'users'
        'email'    => 'nullable|email|unique:users,email,' . $id . ',user_id', // <- GANTI 'akun' JADI 'users'
        'role_id'  => 'required|exists:roles,role_id',
        'is_active' => 'sometimes|boolean',
        'phone'      => 'nullable|string|max:20',                    // Hapus regex yang terlalu ketat
        'birth_date' => 'nullable|date|before_or_equal:today',       // Ganti "before" jadi "before_or_equal"  
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