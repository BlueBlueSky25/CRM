<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyType;
use App\Models\CompanyPic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



class CompanyController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // base query dengan relasi
        $companiesQuery = Company::with('companyType', 'user');

        // Role-based filtering
        if ($user->role_id == 1) {
            // superadmin - all data
        } elseif (in_array($user->role_id, [7, 11])) {
            // admin & marketing
            $companiesQuery->whereHas('user', function ($q) {
                $q->where('role_id', 12);
            });
        } elseif ($user->role_id == 12) {
            // sales - own data only
            $companiesQuery->where('user_id', $user->user_id);
        } else {
            $companiesQuery->whereNull('company_id');
        }

        // KPI calculations BEFORE pagination
        $totalCompanies   = (clone $companiesQuery)->count();
        $jenisCompanies   = (clone $companiesQuery)->distinct('company_type_id')->count('company_type_id');
        $tierCompanies    = (clone $companiesQuery)->distinct('tier')->count('tier');
        $activeCompanies  = (clone $companiesQuery)->where('status', 'active')->count();

        // NOW paginate
        $companies = $companiesQuery->paginate(10);

        // dropdown company type aktif
        $types = CompanyType::where('is_active', true)->get();

        return view('pages.company', compact(
            'companies',
            'types',
            'totalCompanies',
            'jenisCompanies',
            'tierCompanies',
            'activeCompanies'
        ));
    }

    public function search(Request $request)
    {
        $query = Company::with('companyType');

        // Search
        $search = $request->input('search') ?? $request->input('query');

        if ($search) {
            $searchLower = strtolower($search);
            
            $query->where(function($q) use ($searchLower) {
                $q->whereRaw('LOWER(company_name) LIKE ?', ["%{$searchLower}%"])
                  ->orWhereRaw('LOWER(description) LIKE ?', ["%{$searchLower}%"])
                  ->orWhereHas('companyType', function($qt) use ($searchLower) {
                      $qt->whereRaw('LOWER(type_name) LIKE ?', ["%{$searchLower}%"]);
                  });
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $type = $request->type;
            
            if (is_numeric($type)) {
                $query->where('company_type_id', $type);
            } else {
                $query->whereHas('companyType', function($q) use ($type) {
                    $q->whereRaw('LOWER(type_name) LIKE ?', ['%' . strtolower($type) . '%']);
                });
            }
        }

        // Filter by tier
        if ($request->filled('tier')) {
            $query->whereRaw('LOWER(tier) = ?', [strtolower($request->tier)]);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->whereRaw('LOWER(status) = ?', [strtolower($request->status)]);
        }

        // Pagination
        $companies = $query->orderBy('company_name', 'asc')->paginate(10);

        return response()->json([
            'items' => $companies->map(function($company, $index) use ($companies) {
                return [
                    'number' => $companies->firstItem() + $index,
                    'company_id' => $company->company_id,
                    'company_name' => $company->company_name ?? '-',
                    'company_type' => $company->companyType->type_name ?? '-',
                    'tier' => $company->tier ?? '-',
                    'description' => $company->description ?? '-',
                    'status' => ucfirst($company->status ?? 'inactive'),
                    'actions' => $this->getCompanyActions($company)
                ];
            })->toArray(),
            'pagination' => [
                'current_page' => $companies->currentPage(),
                'last_page' => $companies->lastPage(),
                'from' => $companies->firstItem(),
                'to' => $companies->lastItem(),
                'total' => $companies->total()
            ]
        ]);
    }

    // NEW: Show company detail with PICs
    public function show($id)
    {
        try {
            $company = Company::with(['companyType', 'user'])->findOrFail($id);
            $pics = CompanyPic::where('company_id', $id)
                ->orderBy('pic_name')
                ->get();

            return response()->json([
                'success' => true,
                'company' => [
                    'company_id' => $company->company_id,
                    'company_name' => $company->company_name,
                    'company_type' => $company->companyType->type_name ?? '-',
                    'tier' => $company->tier ?? '-',
                    'description' => $company->description ?? '-',
                    'status' => ucfirst($company->status),
                    'created_by' => $company->user->name ?? '-'
                ],
                'pics' => $pics->map(function($pic) {
                    return [
                        'pic_id' => $pic->pic_id,
                        'pic_name' => $pic->pic_name,
                        'position' => $pic->position ?? '-',
                        'phone' => $pic->phone ?? '-',
                        'email' => $pic->email ?? '-'
                    ];
                })
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetching company detail: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat detail perusahaan'
            ], 500);
        }
    }

   public function store(Request $request)
{
    \Log::info('📥 Store Request Data:', $request->all());
    
    $validated = $request->validate([
        'company_name' => 'required|string|max:255',
        'company_type_id' => 'required|exists:company_type,company_type_id',
        'tier' => 'nullable|string|in:A,B,C,D',
        'description' => 'nullable|string',
        'status' => 'required|in:active,inactive',
        'pics' => 'nullable|array',
        'pics.*.pic_name' => 'required_with:pics|string|max:255',
        'pics.*.position' => 'nullable|string|max:255',
        'pics.*.phone' => 'nullable|string|max:20',
        'pics.*.email' => 'nullable|email|max:255',
    ]);

    try {
        DB::beginTransaction();

        // Create company
        $company = Company::create([
            'company_name' => $validated['company_name'],
            'company_type_id' => $validated['company_type_id'],
            'tier' => $validated['tier'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'user_id' => auth()->id(),
        ]);
        
        \Log::info('✅ Company created:', ['company_id' => $company->company_id]);
        
        // ✅ Create PICs if any
        if ($request->has('pics') && is_array($request->pics)) {
            foreach ($request->pics as $index => $picData) {
                // Skip empty PIC entries
                if (empty($picData['pic_name'])) {
                    \Log::warning("⚠️ Skipping empty PIC at index {$index}");
                    continue;
                }
                
                $pic = CompanyPic::create([
                    'company_id' => $company->company_id,
                    'pic_name' => $picData['pic_name'],
                    'position' => $picData['position'] ?? null,
                    'phone' => $picData['phone'] ?? null,
                    'email' => $picData['email'] ?? null,
                ]);
                
                \Log::info('✅ PIC created:', [
                    'pic_id' => $pic->pic_id,
                    'pic_name' => $pic->pic_name
                ]);
            }
        }

        DB::commit();
        
        \Log::info('✅ Transaction committed successfully');

        return redirect()->back()->with('success', 'Company dan PIC berhasil ditambahkan');
        
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('❌ Error storing company: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return redirect()->back()
            ->with('error', 'Gagal menambahkan company: ' . $e->getMessage())
            ->withInput();
    }
}

  public function update(Request $request, $id)
{
    \Log::info('📝 Update Request Data:', $request->all());
    
    $company = Company::findOrFail($id);
    
    $validated = $request->validate([
        'company_name' => 'required|string|max:255',
        'company_type_id' => 'required|exists:company_type,company_type_id',
        'tier' => 'nullable|string|in:A,B,C,D',
        'description' => 'nullable|string',
        'status' => 'required|in:active,inactive',
        'pics' => 'nullable|array',
        'pics.*.pic_name' => 'required_with:pics|string|max:255',
        'pics.*.position' => 'nullable|string|max:255',
        'pics.*.phone' => 'nullable|string|max:20',
        'pics.*.email' => 'nullable|email|max:255',
    ]);

    try {
        DB::beginTransaction();

        // Update company data
        $company->update([
            'company_name' => $validated['company_name'],
            'company_type_id' => $validated['company_type_id'],
            'tier' => $validated['tier'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
        ]);
        
        \Log::info('✅ Company updated:', ['company_id' => $company->company_id]);
        
        // ✅ Delete old PICs and create new ones
        CompanyPic::where('company_id', $company->company_id)->delete();
        \Log::info('🗑️ Old PICs deleted');
        
        if ($request->has('pics') && is_array($request->pics)) {
            foreach ($request->pics as $index => $picData) {
                // Skip empty PIC entries
                if (empty($picData['pic_name'])) {
                    \Log::warning("⚠️ Skipping empty PIC at index {$index}");
                    continue;
                }
                
                $pic = CompanyPic::create([
                    'company_id' => $company->company_id,
                    'pic_name' => $picData['pic_name'],
                    'position' => $picData['position'] ?? null,
                    'phone' => $picData['phone'] ?? null,
                    'email' => $picData['email'] ?? null,
                ]);
                
                \Log::info('✅ New PIC created:', [
                    'pic_id' => $pic->pic_id,
                    'pic_name' => $pic->pic_name
                ]);
            }
        }

        DB::commit();
        
        \Log::info('✅ Update transaction committed successfully');

        return redirect()->back()->with('success', 'Company dan PIC berhasil diupdate');
        
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('❌ Error updating company: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return redirect()->back()
            ->with('error', 'Gagal mengupdate company: ' . $e->getMessage())
            ->withInput();
    }
}

public function getPICsByCompany($companyId)
{
    try {
        $pics = CompanyPic::where('company_id', $companyId)
            ->select('pic_id as id', 'pic_name as name', 'position', 'phone', 'email')
            ->orderBy('pic_name', 'asc')
            ->get();
        
        return response()->json([
            'success' => true,
            'pics' => $pics
        ]);
        
    } catch (\Exception $e) {
        \Log::error("Error fetching PICs for company {$companyId}: " . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => 'Failed to load PICs'
        ], 500);
    }
}

    public function getCompaniesForDropdown()
    {
        try {
            $user = Auth::user();
            
            $companiesQuery = Company::query();
            
            if ($user->role_id == 1) {
                // superadmin - all data
            } elseif (in_array($user->role_id, [7, 11])) {
                $companiesQuery->whereHas('user', function ($q) {
                    $q->where('role_id', 12);
                });
            } elseif ($user->role_id == 12) {
                $companiesQuery->where('user_id', $user->user_id);
            } else {
                $companiesQuery->whereNull('company_id');
            }
            
            $companies = $companiesQuery
                ->where('status', 'active')
                ->select('company_id as id', 'company_name as name')
                ->orderBy('company_name', 'asc')
                ->get();
            
            return response()->json([
                'success' => true,
                'companies' => $companies
            ]);
            
        } catch (\Exception $e) {
            \Log::error("Error fetching companies: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to load companies'
            ], 500);
        }
    }

    public function storeCompanyAjax(Request $request)
    {
        try {
            $validated = $request->validate([
                'company_name' => 'required|string|max:255|unique:company,company_name',
                'company_type_id' => 'required|exists:company_type,company_type_id',
                'tier' => 'nullable|string|in:A,B,C,D',
                'description' => 'nullable|string',
                'status' => 'nullable|in:active,inactive'
            ]);
            
            $user = Auth::user();
            
            $allowedRoles = ['superadmin', 'admin', 'marketing', 'sales'];
            $userRoleName = strtolower($user->role->role_name ?? '');
            
            if (!in_array($userRoleName, $allowedRoles)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk menambah company'
                ], 403);
            }
            
            $company = Company::create([
                'company_name' => trim($validated['company_name']),
                'company_type_id' => $validated['company_type_id'],
                'tier' => $validated['tier'] ?? null,
                'description' => $validated['description'] ?? null,
                'status' => $validated['status'] ?? 'active',
                'user_id' => $user->user_id
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Company berhasil ditambahkan!',
                'company' => [
                    'id' => $company->company_id,
                    'name' => $company->company_name
                ]
            ], 201);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            \Log::error('Error storing company via AJAX: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan company',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    private function getCompanyActions($company)
    {
        $currentMenuId = view()->shared('currentMenuId', null);
        
        $canEdit = auth()->check() && auth()->user()->canAccess($currentMenuId ?? 1, 'edit');
        $canDelete = auth()->check() && auth()->user()->canAccess($currentMenuId ?? 1, 'delete');
        $canView = auth()->check() && auth()->user()->canAccess($currentMenuId ?? 1, 'view');

        $actions = [];

        // Show detail action
        if ($canView) {
            $actions[] = [
                'type' => 'view',
                'onclick' => "showCompanyDetail('{$company->company_id}')",
                'title' => 'Show Detail'
            ];
        }

        if ($canEdit) {
            $actions[] = [
                'type' => 'edit',
                'onclick' => "openEditCompanyModal(
    '{$company->company_id}',
    '" . addslashes($company->company_name) . "',
    '{$company->company_type_id}',
    '{$company->tier}',
    '" . addslashes($company->description ?? '') . "',
    '{$company->status}'
)",
                'title' => 'Edit Company'
            ];
        }

        if ($canDelete) {
            $csrfToken = csrf_token();
            $deleteRoute = route('company.destroy', $company->company_id);
            
            $actions[] = [
                'type' => 'delete',
                'onclick' => "deleteCompany('{$company->company_id}', '{$deleteRoute}', '{$csrfToken}')",
                'title' => 'Delete Company'
            ];
        }

        return $actions;
    }
}