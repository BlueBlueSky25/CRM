<?php

namespace App\Http\Controllers;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District; 
use App\Models\Village;
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
        $users = User::with(['role', 'province', 'regency', 'district', 'village'])->paginate(5);
        $roles = Role::all(); 
        $provinces = Province::orderBy('name')->get();
        

        
        return view('layout.user', compact('users', 'roles', 'provinces'));
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
            'province_id' => 'nullable|exists:provinces,id',
            'regency_id'  => 'nullable|exists:regencies,id', 
            'district_id' => 'nullable|exists:districts,id',
            'village_id'  => 'nullable|exists:villages,id',
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
            'province_id'    => $request->province_id,
            'regency_id'     => $request->regency_id,
            'district_id'    => $request->district_id,
            'village_id'     => $request->village_id,        
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
            'province_id' => 'nullable|exists:provinces,id',
            'regency_id'  => 'nullable|exists:regencies,id',
            'district_id' => 'nullable|exists:districts,id', 
            'village_id'  => 'nullable|exists:villages,id',
        ]);

        $data = [
            'username' => $request->username,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'phone'          => $request->phone,
            'birth_date'     => $request->birth_date,
            'address'        => $request->address,
            'province_id'    => $request->province_id,
            'regency_id'     => $request->regency_id,
            'district_id'    => $request->district_id,
            'village_id'     => $request->village_id,
        ];

        
        if ($request->filled('password')) {
            $data['password_hash'] = Hash::make($request->password);
        }

        $user->update($data);

        
        return redirect()->back()->with('success', 'User berhasil diperbarui!');
    }
 
    public function getRegencies($provinceId)
        {
            try {
                // Validasi input
                if (empty($provinceId)) {
                    return response()->json(['error' => 'Province ID required'], 400);
                }

                // Cek apakah province exist
                $provinceExists = Province::where('id', $provinceId)->exists();
                if (!$provinceExists) {
                    return response()->json(['error' => 'Province not found'], 404);
                }

                $regencies = Regency::where('province_id', $provinceId)
                                ->orderBy('name', 'asc')
                                ->select('id', 'name', 'province_id')
                                ->get();
                
                \Log::info("Fetched regencies for province {$provinceId}: " . $regencies->count());
                
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
            
            \Log::info("Fetched districts for regency {$regencyId}: " . $districts->count());
            
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
            
            \Log::info("Fetched villages for district {$districtId}: " . $villages->count());
            
            return response()->json($villages);
            
        } catch (\Exception $e) {
            \Log::error("Error fetching villages: " . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('user')->with('success', 'User berhasil dihapus!');
    }
    

public function search(Request $request)
    {
        $query = User::with('role');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $role = $request->role;
            $query->whereHas('role', function($q) use ($role) {
                $q->whereRaw('LOWER(role_name) = ?', [$role]);
            });
        }

        $users = $query->paginate(5);

        return response()->json([
            'users' => $users->items(),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page'    => $users->lastPage(),
                'per_page'     => $users->perPage(),
                'from'         => $users->firstItem(),
                'to'           => $users->lastItem(),
            ]
        ]);
    }

}