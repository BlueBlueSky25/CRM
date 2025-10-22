<?php

namespace App\Http\Controllers;

use App\Models\SalesVisit;
use App\Models\User;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SalesVisitController extends Controller
{
    public function index(Request $request)
{
    $user = Auth::user();
    $visitsQuery = SalesVisit::with(['sales', 'province']);

    if ($user->role_id == 1) {
        // Superadmin - bisa lihat semua
    } elseif (in_array($user->role_id, [7, 11])) {
        $visitsQuery->whereHas('sales', function ($q) {
            $q->where('role_id', 12);
        });
    } elseif ($user->role_id == 12) {
        $visitsQuery->where('sales_id', $user->user_id);
    } else {
        $visitsQuery->whereNull('id');
    }

    $salesVisits = $visitsQuery->orderBy('visit_date', 'desc')
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    // ==================== PERBAIKI BAGIAN INI ====================
    // Debug: cek apakah ada user dengan role sales
    $salesRoleCheck = \App\Models\Role::where('role_name', 'LIKE', '%sales%')->first();
    \Log::info('Sales Role Found:', [$salesRoleCheck]);

    // Query sales users yang diperbaiki
    $salesUsers = User::whereHas('role', function ($query) {
        $query->where('role_name', 'LIKE', '%sales%')
              ->orWhere('role_name', 'LIKE', '%Sales%');
    })
    ->select('user_id', 'username', 'email')
    ->orderBy('username')
    ->get();

    // Jika tidak ada hasil, coba query alternatif
    if ($salesUsers->isEmpty()) {
        \Log::warning('No sales users found with role query, trying alternative...');
        
        // Coba berdasarkan role_id jika diketahui
        $salesUsers = User::where('role_id', 12) // Ganti dengan role_id yang sesuai
            ->select('user_id', 'username', 'email')
            ->orderBy('username')
            ->get();
            
        // Jika masih kosong, ambil semua user sebagai fallback
        if ($salesUsers->isEmpty()) {
            $salesUsers = User::select('user_id', 'username', 'email')
                ->orderBy('username')
                ->limit(10) // Batasi untuk testing
                ->get();
        }
    }

    \Log::info('Final Sales Users Count: ' . $salesUsers->count());
    \Log::info('Final Sales Users:', $salesUsers->toArray());
    // ==================== END PERBAIKAN ====================

    $provinces = Province::orderBy('name')->get();
    $totalVisits = $visitsQuery->count();
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

    public function search(Request $request)
    {
        $user = Auth::user();
        $query = SalesVisit::with(['sales.role', 'province']);

        if ($user->role_id == 1) {
        } elseif (in_array($user->role_id, [7, 11])) {
            $query->whereHas('sales', function ($q) {
                $q->where('role_id', 12);
            });
        } elseif ($user->role_id == 12) {
            $query->where('sales_id', $user->user_id);
        } else {
            $query->whereNull('id');
        }

        $search = $request->input('search') ?? $request->input('query');

        if ($search) {
            $searchLower = strtolower($search);
            $query->where(function ($q) use ($searchLower) {
                $q->whereRaw('LOWER(customer_name) LIKE ?', ["%{$searchLower}%"])
                    ->orWhereRaw('LOWER(company) LIKE ?', ["%{$searchLower}%"])
                    ->orWhereRaw('LOWER(purpose) LIKE ?', ["%{$searchLower}%"])
                    ->orWhereHas('sales', function ($qt) use ($searchLower) {
                        $qt->whereRaw('LOWER(username) LIKE ?', ["%{$searchLower}%"])
                            ->orWhereRaw('LOWER(email) LIKE ?', ["%{$searchLower}%"]);
                    })
                    ->orWhereHas('province', function ($qt) use ($searchLower) {
                        $qt->whereRaw('LOWER(name) LIKE ?', ["%{$searchLower}%"]);
                    });
            });
        }

        if ($request->filled('sales_id')) {
            $query->where('sales_id', $request->sales_id);
        }

        if ($request->filled('province_id')) {
            $query->where('province_id', $request->province_id);
        }

        if ($request->filled('is_follow_up')) {
            $followUp = $request->is_follow_up;
            $query->where('is_follow_up', in_array($followUp, ['Ya', '1', 1], true));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('visit_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('visit_date', '<=', $request->date_to);
        }

        $salesVisits = $query->orderBy('visit_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'items' => $salesVisits->map(function ($visit, $index) use ($salesVisits) {
                return [
                    'number' => $salesVisits->firstItem() + $index,
                    'visit_id' => $visit->id,
                    'sales_name' => $visit->sales->username ?? '-',
                    'customer_name' => $visit->customer_name ?? '-',
                    'company' => $visit->company ?? '-',
                    'province' => $visit->province->name ?? '-',
                    'visit_date' => $visit->visit_date ? $visit->visit_date->format('d-m-Y') : '-',
                    'purpose' => $visit->purpose ?? '-',
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

    public function store(Request $request)
{
    // Debug request data
    \Log::info('Store Request Data:', $request->all());

    $validated = $request->validate([
        'sales_id' => 'required|exists:users,user_id',
        'customer_name' => 'required|string|max:255',
        'company' => 'nullable|string|max:255',
        'province_id' => 'required|exists:provinces,id',
        'regency_id' => 'nullable|exists:regencies,id',
        'district_id' => 'nullable|exists:districts,id',
        'village_id' => 'nullable|exists:villages,id',
        'address' => 'nullable|string',
        'visit_date' => 'required|date',
        'purpose' => 'required|string',
        'is_follow_up' => 'nullable|boolean',
    ]);

    $user = Auth::user();
    $allowedRoles = ['superadmin', 'admin', 'marketing', 'sales'];
    $userRoleName = strtolower($user->role->role_name ?? '');

    if (!in_array($userRoleName, $allowedRoles)) {
        return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menambah kunjungan.');
    }

    try {
        $visit = SalesVisit::create([
            'sales_id' => $request->sales_id,
            'customer_name' => $request->customer_name,
            'company' => $request->company,
            'province_id' => $request->province_id,
            'regency_id' => $request->regency_id,
            'district_id' => $request->district_id,
            'village_id' => $request->village_id,
            'address' => $request->address,
            'visit_date' => $request->visit_date,
            'purpose' => $request->purpose,
            'is_follow_up' => $request->has('is_follow_up') ? 1 : 0,
        ]);

        \Log::info('Visit Created:', $visit->toArray());

        return redirect()->route('salesvisit')
            ->with('success', 'Data kunjungan sales berhasil ditambahkan!');
    } catch (\Exception $e) {
        \Log::error('Error creating visit: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return redirect()->back()
            ->with('error', 'Gagal menambahkan data: ' . $e->getMessage())
            ->withInput();
    }
}

   public function edit($id)
{
    $visit = SalesVisit::with(['sales', 'province'])->findOrFail($id);

    $salesUsers = User::whereHas('role', function ($query) {
        $query->where('role_name', 'sales')
            ->orWhere('role_name', 'like', '%sales%');
    })
    ->select('user_id', 'username', 'email')
    ->orderBy('username')
    ->get();

    $provinces = Province::orderBy('name')->get();

    return response()->json([
        'success' => true,
        'data' => $visit,
        'salesUsers' => $salesUsers,
        'provinces' => $provinces,
    ]);
}


    public function update(Request $request, $id)
{
    
    \Log::info('Update Request Data:', $request->all());

    $validated = $request->validate([
        'sales_id' => 'required|exists:users,user_id',
        'customer_name' => 'required|string|max:255',
        'company' => 'nullable|string|max:255',
        'province_id' => 'required|exists:provinces,id',
        'regency_id' => 'nullable|exists:regencies,id',
        'district_id' => 'nullable|exists:districts,id',
        'village_id' => 'nullable|exists:villages,id',
        'address' => 'nullable|string',
        'visit_date' => 'required|date',
        'purpose' => 'required|string',
        'is_follow_up' => 'nullable|boolean',
    ]);

    $user = Auth::user();
    $visit = SalesVisit::findOrFail($id);
    $userRoleName = strtolower($user->role->role_name ?? '');

    if ($userRoleName === 'sales' && $visit->sales_id !== $user->user_id) {
        return redirect()->back()->with('error', 'Anda tidak boleh mengedit data kunjungan milik sales lain.');
    }

    try {
        $visit->update([
            'sales_id' => $request->sales_id,
            'customer_name' => $request->customer_name,
            'company' => $request->company,
            'province_id' => $request->province_id,
            'regency_id' => $request->regency_id,
            'district_id' => $request->district_id,
            'village_id' => $request->village_id,
            'address' => $request->address,
            'visit_date' => $request->visit_date,
            'purpose' => $request->purpose,
            'is_follow_up' => $request->has('is_follow_up') ? 1 : 0,
        ]);

        \Log::info('Visit Updated:', $visit->toArray());

        return redirect()->route('salesvisit')
            ->with('success', 'Data kunjungan sales berhasil diupdate!');
    } catch (\Exception $e) {
        \Log::error('Error updating visit: ' . $e->getMessage());
        
        return redirect()->back()
            ->with('error', 'Gagal mengupdate data: ' . $e->getMessage())
            ->withInput();
    }
}

    public function destroy($id)
    {
        $user = Auth::user();
        $visit = SalesVisit::findOrFail($id);
        $userRoleName = strtolower($user->role->role_name ?? '');

        if ($userRoleName === 'sales' && $visit->sales_id !== $user->user_id) {
            return redirect()->route('pages.salesvisit')->with('error', 'Anda tidak boleh menghapus data kunjungan milik sales lain.');
        }

        try {
            $visit->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data kunjungan sales berhasil dihapus!'
                ]);
            }

            return redirect()->route('pages.salesvisit')
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

    private function getVisitActions($visit)
    {
        $currentMenuId = view()->shared('currentMenuId', null);
        $canEdit = auth()->check() && auth()->user()->canAccess($currentMenuId ?? 1, 'edit');
        $canDelete = auth()->check() && auth()->user()->canAccess($currentMenuId ?? 1, 'delete');
        $actions = [];

        if ($canEdit) {
            $actions[] = [
                'type' => 'edit',
                'onclick' => "openEditVisitModal(
                    '{$visit->id}',
                    '{$visit->sales_id}',
                    '" . addslashes($visit->customer_name) . "',
                    '" . addslashes($visit->company ?? '') . "',
                    '{$visit->province_id}',
                    '{$visit->visit_date->format('Y-m-d')}',
                    '" . addslashes($visit->purpose) . "',
                    '{$visit->is_follow_up}'
                )",
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
