<?php

namespace App\Http\Controllers;

use App\Models\SalesVisit;
use App\Models\User;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class SalesVisitController extends Controller
{
    /**
     * Display a listing of sales visits
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Base query dengan relasi
        $visitsQuery = SalesVisit::with(['sales', 'province', 'regency', 'district', 'village']);

        // 🔹 SUPERADMIN (role_id = 1) → lihat semua data
        if ($user->role_id == 1) {
            // tanpa filter apa pun
        }

        // 🔹 ADMIN (role_id = 7) & MARKETING (role_id = 11)
        //     → lihat semua data milik SALES (role_id = 12)
        elseif (in_array($user->role_id, [7, 11])) {
            $visitsQuery->whereHas('sales', function ($q) {
                $q->where('role_id', 12); // hanya user sales
            });
        }

        // 🔹 SALES (role_id = 12) → hanya data milik sendiri
        elseif ($user->role_id == 12) {
            $visitsQuery->where('sales_id', $user->user_id);
        }

        // kalau role lain (misalnya belum dikategorikan)
        else {
            $visitsQuery->whereNull('id'); // tampil kosong aja
        }

        // Ambil data final dengan pagination
        $salesVisits = $visitsQuery->orderBy('visit_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get sales users (users dengan role sales) - FIXED
        $salesUsers = User::join('roles', 'users.role_id', '=', 'roles.role_id')
                        ->where('roles.role_name', 'Sales')
                        ->select('users.user_id', 'users.username', 'users.email')
                        ->orderBy('users.username')
                        ->get();

        // Get all provinces
        $provinces = Province::orderBy('name')->get();

        // KPI Sales Visit (berdasarkan query filter di atas)
        $totalVisits = (clone $visitsQuery)->count();
        $followUpVisits = (clone $visitsQuery)->where('is_follow_up', true)->count();
        $uniqueCustomers = (clone $visitsQuery)->distinct('customer_name')->count('customer_name');
        $uniqueSales = (clone $visitsQuery)->distinct('sales_id')->count('sales_id');

        return view('pages.salesvisit', compact(
            'salesVisits',
            'salesUsers',
            'provinces',
            'totalVisits',
            'followUpVisits',
            'uniqueCustomers',
            'uniqueSales'
        ));
    }

    /**
     * Search and filter sales visits (AJAX)
     */
    public function search(Request $request)
    {
        $user = Auth::user();
        $query = SalesVisit::with(['sales.role', 'province', 'regency', 'district', 'village']);

        // 🔹 Role-based filtering
        if ($user->role_id == 1) {
            // Superadmin: lihat semua
        } elseif (in_array($user->role_id, [7, 11])) {
            // Admin & Marketing: lihat data sales
            $query->whereHas('sales', function ($q) {
                $q->where('role_id', 12);
            });
        } elseif ($user->role_id == 12) {
            // Sales: hanya data sendiri
            $query->where('sales_id', $user->user_id);
        } else {
            $query->whereNull('id');
        }

        // Search
        $search = $request->input('search') ?? $request->input('query');

        if ($search) {
            $query->search($search);
        }

        // Filter by sales
        if ($request->filled('sales_id')) {
            $query->where('sales_id', $request->sales_id);
        }

        // Filter by province
        if ($request->filled('province_id')) {
            $query->where('province_id', $request->province_id);
        }

        // Filter by regency
        if ($request->filled('regency_id')) {
            $query->where('regency_id', $request->regency_id);
        }

        // Filter by follow up
        if ($request->filled('follow_up')) {
            $followUp = strtolower($request->follow_up);
            if (in_array($followUp, ['ya', 'yes', '1'])) {
                $query->where('is_follow_up', true);
            } elseif (in_array($followUp, ['tidak', 'no', '0'])) {
                $query->where('is_follow_up', false);
            }
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('visit_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('visit_date', '<=', $request->date_to);
        }

        // Pagination
        $salesVisits = $query->orderBy('visit_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Format response untuk AJAX
        return response()->json([
            'items' => $salesVisits->map(function($visit, $index) use ($salesVisits) {
                return [
                    'number' => $salesVisits->firstItem() + $index,
                    'id' => $visit->id,
                    'sales_name' => $visit->sales->username ?? '-',
                    'customer_name' => $visit->customer_name ?? '-',
                    'company_name' => $visit->company_name ?? '-',
                    'province' => $visit->province->name ?? '-',
                    'regency' => $visit->regency->name ?? '-',
                    'district' => $visit->district->name ?? '-',
                    'village' => $visit->village->name ?? '-',
                    'address' => $visit->address ?? '-',
                    'visit_date' => $visit->visit_date ? $visit->visit_date->format('d-m-Y') : '-',
                    'visit_purpose' => $visit->visit_purpose ?? '-',
                    'is_follow_up' => $visit->is_follow_up ? 'Ya' : 'Tidak',
                    'actions' => $this->getVisitActions($visit)
                ];
            })->toArray(),
            'pagination' => [
                'current_page' => $salesVisits->currentPage(),
                'last_page' => $salesVisits->lastPage(),
                'from' => $salesVisits->firstItem(),
                'to' => $salesVisits->lastItem(),
                'total' => $salesVisits->total()
            ]
        ]);
    }

    /**
     * Store a newly created sales visit
     * 🔥 FIXED: Sesuaikan validation dengan name attribute di form
     */
    public function store(Request $request)
        {
            // 🔍 Normalisasi input dari form (karena form pakai nama seperti create-province)
            $data = $request->all();

            // Mapping dari form ke kolom DB
            if (isset($data['create-province'])) {
                $data['province_id'] = $data['create-province'];
            }
            if (isset($data['create-regency'])) {
                $data['regency_id'] = $data['create-regency'];
            }
            if (isset($data['create-district'])) {
                $data['district_id'] = $data['create-district'];
            }
            if (isset($data['create-village'])) {
                $data['village_id'] = $data['create-village'];
            }

            // Replace request dengan data yang sudah dimapping
            $request->merge($data);

            // 🔒 Validasi input
            $request->validate([
                'sales_id' => 'required|exists:users,user_id',
                'customer_name' => 'required|string|max:255',
                'company_name' => 'nullable|string|max:255',
                'province_id' => 'required|exists:provinces,id',
                'regency_id' => 'nullable|exists:regencies,id',
                'district_id' => 'nullable|exists:districts,id',
                'village_id' => 'nullable|exists:villages,id',
                'address' => 'nullable|string',
                'visit_date' => 'required|date',
                'visit_purpose' => 'required|string',
                'is_follow_up' => 'nullable|boolean',
            ]);

            $user = Auth::user();
            $allowedRoles = ['superadmin', 'admin', 'marketing', 'sales'];
            $userRoleName = strtolower($user->role->role_name ?? '');

            // 🔧 Role check
            if (!in_array($userRoleName, $allowedRoles)) {
                return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menambah kunjungan.');
            }

            try {
                // 🧩 Simpan ke database
                $visit = SalesVisit::create([
                    'sales_id' => $request->sales_id,
                    'user_id' => auth()->id(),
                    'customer_name' => $request->customer_name,
                    'company_name' => $request->company_name ?? null,
                    'province_id' => $request->province_id,
                    'regency_id' => $request->regency_id ?? null,
                    'district_id' => $request->district_id ?? null,
                    'village_id' => $request->village_id ?? null,
                    'address' => $request->address ?? null,
                    'visit_date' => $request->visit_date,
                    'visit_purpose' => $request->visit_purpose,
                    'is_follow_up' => $request->is_follow_up ?? 0,
                ]);

                // Kalau request dari AJAX
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Data kunjungan sales berhasil ditambahkan!',
                        'data' => $visit
                    ]);
                }

                // Kalau form biasa
                return redirect()->route('salesvisit')
                    ->with('success', 'Data kunjungan sales berhasil ditambahkan!');

            } catch (\Exception $e) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal menambahkan data: ' . $e->getMessage()
                    ], 500);
                }

                return redirect()->back()
                    ->with('error', 'Gagal menambahkan data: ' . $e->getMessage())
                    ->withInput();
            }
        }


    /**
     * Show the form for editing the specified sales visit
     */
    public function edit($id)
    {
        $visit = SalesVisit::with(['sales', 'province', 'regency', 'district', 'village'])->findOrFail($id);
        
        // Get sales users - FIXED
        $salesUsers = User::join('roles', 'users.role_id', '=', 'roles.role_id')
                        ->where('roles.role_name', 'Sales')
                        ->select('users.user_id', 'users.username', 'users.email')
                        ->orderBy('users.username')
                        ->get();

        // Get all provinces
        $provinces = Province::orderBy('name')->get();
        
        return response()->json([
            'success' => true,
            'data' => $visit,
            'salesUsers' => $salesUsers,
            'provinces' => $provinces
        ]);
    }

    /**
     * Update the specified sales visit
     * 🔥 FIXED: Sesuaikan validation dengan name attribute di form
     */
    public function update(Request $request, $id)
    {
        // 🔥 FIXED: Normalize field names dari form
        $data = $request->all();
        
        // Map field names dari form ke database column names
        if (isset($data['edit-province'])) {
            $data['province_id'] = $data['edit-province'];
        }
        if (isset($data['edit-regency'])) {
            $data['regency_id'] = $data['edit-regency'];
        }
        if (isset($data['edit-district'])) {
            $data['district_id'] = $data['edit-district'];
        }
        if (isset($data['edit-village'])) {
            $data['village_id'] = $data['edit-village'];
        }

        // Replace request data
        $request->merge($data);

        $request->validate([
            'sales_id' => 'required|exists:users,user_id',
            'customer_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'province_id' => 'required|exists:provinces,id',
            'regency_id' => 'nullable|exists:regencies,id',
            'district_id' => 'nullable|exists:districts,id',
            'village_id' => 'nullable|exists:villages,id',
            'address' => 'nullable|string',
            'visit_date' => 'required|date',
            'visit_purpose' => 'required|string',
            'is_follow_up' => 'nullable|boolean',
        ]);

        $user = Auth::user();
        $visit = SalesVisit::findOrFail($id);

        // 🔧 Check permission
        $userRoleName = strtolower($user->role->role_name ?? '');
        
        // 🔹 Sales hanya boleh edit data miliknya sendiri
        if ($userRoleName === 'sales' && $visit->sales_id !== $user->user_id) {
            return redirect()->back()->with('error', 'Anda tidak boleh mengedit data kunjungan milik sales lain.');
        }

        try {
            $visit->update([
                'sales_id' => $request->sales_id,
                'user_id' => auth()->id(),
                'customer_name' => $request->customer_name,
                'company_name' => $request->company_name ?? null,
                'province_id' => $request->province_id,
                'regency_id' => $request->regency_id ?? null,
                'district_id' => $request->district_id ?? null,
                'village_id' => $request->village_id ?? null,
                'address' => $request->address ?? null,
                'visit_date' => $request->visit_date,
                'visit_purpose' => $request->visit_purpose,
                'is_follow_up' => $request->is_follow_up ?? 0,
            ]);

            return redirect()->route('salesvisit')
                ->with('success', 'Data kunjungan sales berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengupdate data: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified sales visit
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $visit = SalesVisit::findOrFail($id);

        // 🔧 Check permission
        $userRoleName = strtolower($user->role->role_name ?? '');
        
        // 🔹 Sales hanya bisa hapus data miliknya sendiri
        if ($userRoleName === 'sales' && $visit->sales_id !== $user->user_id) {
            return redirect()->route('salesvisit')->with('error', 'Anda tidak boleh menghapus data kunjungan milik sales lain.');
        }

        try {
            $visit->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data kunjungan sales berhasil dihapus!'
                ]);
            }

            return redirect()->route('salesvisit')
                ->with('success', 'Data kunjungan sales berhasil dihapus!');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus data: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * CASCADE DROPDOWN - Get Regencies by Province
     */
    public function getRegencies($provinceId)
    {
        $regencies = Regency::where('province_id', $provinceId)
            ->orderBy('name')
            ->get(['id', 'name']);
        
        return response()->json($regencies);
    }

    /**
     * CASCADE DROPDOWN - Get Districts by Regency
     */
    public function getDistricts($regencyId)
    {
        $districts = District::where('regency_id', $regencyId)
            ->orderBy('name')
            ->get(['id', 'name']);
        
        return response()->json($districts);
    }

    /**
     * CASCADE DROPDOWN - Get Villages by District
     */
    public function getVillages($districtId)
    {
        $villages = Village::where('district_id', $districtId)
            ->orderBy('name')
            ->get(['id', 'name']);
        
        return response()->json($villages);
    }

    /**
     * EXPORT to CSV
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        $query = SalesVisit::with(['sales', 'province', 'regency', 'district', 'village']);

        // Role-based filtering (sama seperti index)
        if ($user->role_id == 1) {
            // Superadmin: all data
        } elseif (in_array($user->role_id, [7, 11])) {
            $query->whereHas('sales', function ($q) {
                $q->where('role_id', 12);
            });
        } elseif ($user->role_id == 12) {
            $query->where('sales_id', $user->user_id);
        } else {
            $query->whereNull('id');
        }

        $visits = $query->orderBy('visit_date', 'desc')->get();

        $filename = 'sales_visits_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($visits) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, [
                'No',
                'Sales',
                'Customer Name',
                'Company',
                'Province',
                'Regency',
                'District',
                'Village',
                'Address',
                'Visit Date',
                'Purpose',
                'Follow Up'
            ]);

            // Data rows
            foreach ($visits as $index => $visit) {
                fputcsv($file, [
                    $index + 1,
                    $visit->sales->username ?? '-',
                    $visit->customer_name ?? '-',
                    $visit->company_name ?? '-',
                    $visit->province->name ?? '-',
                    $visit->regency->name ?? '-',
                    $visit->district->name ?? '-',
                    $visit->village->name ?? '-',
                    $visit->address ?? '-',
                    $visit->visit_date ? $visit->visit_date->format('d-m-Y') : '-',
                    $visit->visit_purpose ?? '-',
                    $visit->is_follow_up ? 'Ya' : 'Tidak'
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * IMPORT from CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:5120', // max 5MB
        ]);

        try {
            $file = $request->file('file');
            $path = $file->getRealPath();
            $data = array_map('str_getcsv', file($path));
            
            // Skip header row
            $header = array_shift($data);
            
            $imported = 0;
            $errors = [];

            DB::beginTransaction();

            foreach ($data as $index => $row) {
                $rowNumber = $index + 2; // +2 karena header di row 1 dan mulai dari row 2
                
                try {
                    // Validasi minimal data
                    if (count($row) < 8) {
                        $errors[] = "Row $rowNumber: Data tidak lengkap";
                        continue;
                    }

                    // Cari sales by username
                    $sales = User::where('username', trim($row[1]))->first();
                    if (!$sales) {
                        $errors[] = "Row $rowNumber: Sales '{$row[1]}' tidak ditemukan";
                        continue;
                    }

                    // Cari province by name
                    $province = Province::where('name', 'like', '%' . trim($row[4]) . '%')->first();
                    if (!$province) {
                        $errors[] = "Row $rowNumber: Province '{$row[4]}' tidak ditemukan";
                        continue;
                    }

                    // Optional: regency, district, village
                    $regency = null;
                    $district = null;
                    $village = null;

                    if (!empty(trim($row[5]))) {
                        $regency = Regency::where('province_id', $province->id)
                            ->where('name', 'like', '%' . trim($row[5]) . '%')
                            ->first();
                    }

                    if ($regency && !empty(trim($row[6]))) {
                        $district = District::where('regency_id', $regency->id)
                            ->where('name', 'like', '%' . trim($row[6]) . '%')
                            ->first();
                    }

                    if ($district && !empty(trim($row[7]))) {
                        $village = Village::where('district_id', $district->id)
                            ->where('name', 'like', '%' . trim($row[7]) . '%')
                            ->first();
                    }

                    // Parse date
                    $visitDate = null;
                    if (!empty(trim($row[9]))) {
                        try {
                            $visitDate = \Carbon\Carbon::createFromFormat('d-m-Y', trim($row[9]))->format('Y-m-d');
                        } catch (\Exception $e) {
                            $visitDate = date('Y-m-d');
                        }
                    }

                    // Parse follow up
                    $followUp = in_array(strtolower(trim($row[11] ?? '')), ['ya', 'yes', '1', 'true']);

                    SalesVisit::create([
                        'sales_id' => $sales->user_id,
                        'user_id' => auth()->id(),
                        'customer_name' => trim($row[2]),
                        'company_name' => trim($row[3]) ?: null,
                        'province_id' => $province->id,
                        'regency_id' => $regency?->id,
                        'district_id' => $district?->id,
                        'village_id' => $village?->id,
                        'address' => trim($row[8]) ?: null,
                        'visit_date' => $visitDate ?: date('Y-m-d'),
                        'visit_purpose' => trim($row[10]),
                        'is_follow_up' => $followUp,
                    ]);

                    $imported++;

                } catch (\Exception $e) {
                    $errors[] = "Row $rowNumber: " . $e->getMessage();
                }
            }

            DB::commit();

            $message = "Berhasil import $imported data.";
            if (count($errors) > 0) {
                $message .= " " . count($errors) . " data gagal: " . implode(', ', array_slice($errors, 0, 3));
            }

            return redirect()->route('salesvisit')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    /**
     * Get actions for visit based on user permissions
     */
    private function getVisitActions($visit)
    {
        $currentMenuId = view()->shared('currentMenuId', null);
        
        $canEdit = auth()->check() && auth()->user()->canAccess($currentMenuId ?? 1, 'edit');
        $canDelete = auth()->check() && auth()->user()->canAccess($currentMenuId ?? 1, 'delete');

        $actions = [];

        if ($canEdit) {
            $actions[] = [
                'type' => 'edit',
                'onclick' => "openEditVisitModal({
    id: {$visit->id},
    salesId: {$visit->sales_id},
    customerName: '" . addslashes($visit->customer_name) . "',
    company: '" . addslashes($visit->company_name ?? '') . "',
    provinceId: {$visit->province_id},
    regencyId: " . ($visit->regency_id ?? 'null') . ",
    districtId: " . ($visit->district_id ?? 'null') . ",
    villageId: " . ($visit->village_id ?? 'null') . ",
    address: '" . addslashes($visit->address ?? '') . "',
    visitDate: '{$visit->visit_date->format('Y-m-d')}',
    purpose: '" . addslashes($visit->visit_purpose) . "',
    followUp: " . ($visit->is_follow_up ? 1 : 0) . "
})",
                'title' => 'Edit Visit'
            ];
        }

        if ($canDelete) {
            $csrfToken = csrf_token();
            $deleteRoute = route('salesvisit.destroy', $visit->id);
            
            $actions[] = [
                'type' => 'delete',
                'onclick' => "deleteVisit('{$visit->id}', '{$deleteRoute}', '{$csrfToken}')",
                'title' => 'Delete Visit'
            ];
        }

        return $actions;
    }
}