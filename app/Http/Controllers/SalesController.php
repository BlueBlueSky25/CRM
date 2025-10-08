<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Province; 
use App\Models\Regency;
use App\Models\District; 
use App\Models\Village;
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
        
        $salesUsers = User::where('role_id', $salesRole->role_id)->paginate(5);
        $currentMenuId = view()->shared('currentMenuId', null);
        $provinces = Province::orderBy('name')->get();

        return view('pages.marketing', [
            'salesUsers' => $salesUsers,
            'salesRole' => $salesRole,
            'provinces' => $provinces,
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
            'province_id' => 'required|exists:provinces,id',
            'regency_id' => 'required|exists:regencies,id',
            'district_id' => 'required|exists:districts,id',
            'village_id' => 'required|exists:villages,id',
        ]);

        $salesRole = Role::where('role_name', 'Sales')->first();

        User::create([
            'username'       => $request->username,
            'email'          => $request->email,
            'password_hash'  => Hash::make($request->password),
            'role_id'        => $salesRole->role_id,
            'phone'          => $request->phone,
            'birth_date'     => $request->birth_date,
            'address'        => $request->address,
            'province_id'    => $request->province_id,
            'regency_id'     => $request->regency_id,
            'district_id'    => $request->district_id,
            'village_id'     => $request->village_id,
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
            'province_id' => 'required|exists:provinces,id',
            'regency_id' => 'required|exists:regencies,id',
            'district_id' => 'required|exists:districts,id',
            'village_id' => 'required|exists:villages,id',
        ]);

        $data = [
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'birth_date' => $request->birth_date,
            'address' => $request->address,
            'province_id' => $request->province_id,
            'regency_id' => $request->regency_id,
            'district_id' => $request->district_id,
            'village_id' => $request->village_id,
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

    // CASCADE DROPDOWN METHODS (COPY DARI UserController)
    
    public function getRegencies($provinceId)
    {
        try {
            if (empty($provinceId)) {
                return response()->json(['error' => 'Province ID required'], 400);
            }

            $provinceExists = Province::where('id', $provinceId)->exists();
            if (!$provinceExists) {
                return response()->json(['error' => 'Province not found'], 404);
            }

            $regencies = Regency::where('province_id', $provinceId)
                               ->orderBy('name', 'asc')
                               ->select('id', 'name', 'province_id')
                               ->get();
            
            return response()->json($regencies);
            
        } catch (\Exception $e) {
            \Log::error("Error fetching regencies: " . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    public function getDistricts($regencyId)
    {
        try {
            if (empty($regencyId)) {
                return response()->json(['error' => 'Regency ID required'], 400);
            }

            $regencyExists = Regency::where('id', $regencyId)->exists();
            if (!$regencyExists) {
                return response()->json(['error' => 'Regency not found'], 404);
            }

            $districts = District::where('regency_id', $regencyId)
                               ->orderBy('name', 'asc')
                               ->select('id', 'name', 'regency_id')
                               ->get();
            
            return response()->json($districts);
            
        } catch (\Exception $e) {
            \Log::error("Error fetching districts: " . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    public function getVillages($districtId)
    {
        try {
            if (empty($districtId)) {
                return response()->json(['error' => 'District ID required'], 400);
            }

            $districtExists = District::where('id', $districtId)->exists();
            if (!$districtExists) {
                return response()->json(['error' => 'District not found'], 404);
            }

            $villages = Village::where('district_id', $districtId)
                             ->orderBy('name', 'asc') 
                             ->select('id', 'name', 'district_id')
                             ->get();
            
            return response()->json($villages);
            
        } catch (\Exception $e) {
            \Log::error("Error fetching villages: " . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }
}