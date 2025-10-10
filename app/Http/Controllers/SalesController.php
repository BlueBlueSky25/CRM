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

    // CASCADE DROPDOWN METHODS
    
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

    /**
     * Search & Filter Sales (AJAX)
     */
    public function search(Request $request)
    {
        try {
            \Log::info('Sales Search Request', $request->all());

            $salesRole = Role::where('role_name', 'Sales')->first();
            
            if (!$salesRole) {
                \Log::error('Sales role not found');
                return response()->json(['error' => 'Role sales tidak ditemukan'], 404);
            }

            \Log::info('Sales Role Found', ['role_id' => $salesRole->role_id]);

            $query = User::with('role', 'province', 'regency', 'district', 'village')
                         ->where('role_id', $salesRole->role_id);

            // Filter search
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('username', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
                \Log::info('Search applied', ['search' => $search]);
            }

            // Filter by status
            if ($request->filled('status')) {
                $isActive = $request->status === 'active' ? 1 : 0;
                $query->where('is_active', $isActive);
                \Log::info('Status filter applied', ['status' => $request->status]);
            }

            // Filter by province
            if ($request->filled('province_id')) {
                $query->where('province_id', $request->province_id);
                \Log::info('Province filter applied', ['province_id' => $request->province_id]);
            }

            $users = $query->paginate(5);
            \Log::info('Users found', ['count' => $users->count()]);

            $items = $users->map(function($user, $index) use ($users) {
                \Log::info('Processing user', ['user_id' => $user->user_id]);

                // Format alamat
                $alamatWilayah = collect([
                    optional($user->village)->name,
                    optional($user->district)->name,
                    optional($user->regency)->name,
                    optional($user->province)->name,
                ])->filter()->implode(', ');
                
                $alamatDisplay = $alamatWilayah ?: ($user->address ?? '-');
                if ($alamatWilayah && $user->address) {
                    $alamatDisplay = $alamatWilayah . ' - ' . $user->address;
                }

                $actions = [];
                try {
                    $actions = $this->getSalesActions($user);
                    \Log::info('Actions generated', ['user_id' => $user->user_id, 'actions_count' => count($actions)]);
                } catch (\Exception $e) {
                    \Log::error('Error generating actions for user', [
                        'user_id' => $user->user_id,
                        'error' => $e->getMessage()
                    ]);
                }

                return [
                    'number' => $users->firstItem() + $index,
                    'user' => [
                        'username' => $user->username ?? '-',
                        'email' => $user->email ?? '-'
                    ],
                    'phone' => $user->phone ?? '-',
                    'date_birth' => $user->birth_date 
                        ? \Carbon\Carbon::parse($user->birth_date)->format('d M Y') 
                        : '-',
                    'alamat' => $alamatDisplay,
                    'role' => optional($user->role)->role_name ?? 'No Role',
                    'status' => $user->is_active ? 'Active' : 'Inactive',
                    'actions' => $actions
                ];
            })->toArray();

            \Log::info('Response prepared successfully');

            return response()->json([
                'items' => $items,
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'from' => $users->firstItem(),
                    'to' => $users->lastItem(),
                    'total' => $users->total()
                ]
            ]);

        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Sales Search Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            \Log::error('File: ' . $e->getFile());
            \Log::error('Line: ' . $e->getLine());
            
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * Aksi untuk tiap baris Sales
     */
    private function getSalesActions($user)
    {
        $actions = [];

        // Cek apakah user login
        if (!auth()->check()) {
            return $actions;
        }

        // TEMPORARY: Bypass permission checking untuk debug
        // Nanti aktifkan lagi setelah masalah resolved
        $canEdit = true;
        $canDelete = true;

        // COMMENTED OUT - Untuk production nanti aktifkan ini:
    
        try {
            $currentMenuId = view()->shared('currentMenuId') ?? 1;
            $canEdit = auth()->user()->canAccess($currentMenuId, 'edit');
            $canDelete = auth()->user()->canAccess($currentMenuId, 'delete');
        } catch (\Exception $e) {
            \Log::error('Error checking permissions: ' . $e->getMessage());
            $canEdit = true;
            $canDelete = true;
        }
        

        if ($canEdit) {
            $actions[] = [
                'type' => 'edit', 
                'onclick' => "openEditSalesModal(
                    '{$user->user_id}',
                    '" . addslashes($user->username ?? '') . "',
                    '" . addslashes($user->email ?? '') . "',
                    '" . addslashes($user->phone ?? '') . "',
                    '" . addslashes($user->birth_date ?? '') . "',
                    '" . addslashes($user->address ?? '') . "',
                    '{$user->province_id}',
                    '{$user->regency_id}',
                    '{$user->district_id}',
                    '{$user->village_id}'
                )",
                'title' => 'Edit Sales'
            ];
        }

        if ($canDelete) {
            $csrfToken = csrf_token();
            $deleteRoute = route('marketing.sales.destroy', $user->user_id);
            
            $actions[] = [
                'type' => 'delete',
                'onclick' => "deleteSales('{$user->user_id}', '{$deleteRoute}', '{$csrfToken}')",
                'title' => 'Delete Sales'
            ];
        }

        return $actions;
    }
}