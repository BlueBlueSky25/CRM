<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyType;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::with('companyType')->paginate(10);
        $types = CompanyType::where('is_active', true)->get();

        // KPI Company
        $totalCompanies   = Company::count();
        $jenisCompanies   = Company::distinct('company_type_id')->count('company_type_id');
        $tierCompanies    = Company::distinct('tier')->count('tier');
        $activeCompanies  = Company::where('status', 'active')->count();

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

    // Filter by type - HANDLE BOTH ID DAN NAME
    if ($request->filled('type')) {
        $type = $request->type;
        
        // Cek apakah numeric (ID) atau string (name)
        if (is_numeric($type)) {
            $query->where('company_type_id', $type);
        } else {
            // Search by type_name (case-insensitive)
            $query->whereHas('companyType', function($q) use ($type) {
                $q->whereRaw('LOWER(type_name) LIKE ?', ['%' . strtolower($type) . '%']);
            });
        }
    }

    // Filter by tier - CASE INSENSITIVE
    if ($request->filled('tier')) {
        $query->whereRaw('LOWER(tier) = ?', [strtolower($request->tier)]);
    }

    // Filter by status
    if ($request->filled('status')) {
        $query->whereRaw('LOWER(status) = ?', [strtolower($request->status)]);
    }

    // Pagination
    $companies = $query->orderBy('company_name', 'asc')->paginate(10);

    // Format response untuk AJAX
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

    public function store(Request $request)
    {
        $request->validate([
            'company_name'     => 'required|string|max:255',
            'company_type_id'  => 'required|exists:company_types,company_type_id',
            'tier'             => 'nullable|string|in:A,B,C,D',
            'description'      => 'nullable|string',
            'status'           => 'nullable|in:active,inactive'
        ]);

        Company::create([
            'company_name'    => $request->company_name,
            'company_type_id' => $request->company_type_id,
            'tier'            => $request->tier,
            'description'     => $request->description,
            'status'          => $request->status ?? 'active',
        ]);

        return redirect()->route('company')->with('success', 'Perusahaan berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'company_name'     => 'required|string|max:255',
            'company_type_id'  => 'required|exists:company_types,company_type_id',
            'tier'             => 'nullable|string|in:A,B,C,D',
            'description'      => 'nullable|string',
            'status'           => 'nullable|in:active,inactive'
        ]);

        $company = Company::findOrFail($id);
        
        $company->update([
            'company_name'    => $request->company_name,
            'company_type_id' => $request->company_type_id,
            'tier'            => $request->tier,
            'description'     => $request->description,
            'status'          => $request->status ?? 'active',
        ]);

        return redirect()->route('company')->with('success', 'Data perusahaan berhasil diperbarui');
    }

    public function destroy($id)
    {
        try {
            $company = Company::findOrFail($id);
            $company->delete();
            
            return redirect()->route('company')->with('success', 'Perusahaan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('company')->with('error', 'Gagal menghapus perusahaan');
        }
    }

    private function getCompanyActions($company)
    {
        $currentMenuId = view()->shared('currentMenuId', null);
        
        $canEdit = auth()->check() && auth()->user()->canAccess($currentMenuId ?? 1, 'edit');
        $canDelete = auth()->check() && auth()->user()->canAccess($currentMenuId ?? 1, 'delete');

        $actions = [];

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