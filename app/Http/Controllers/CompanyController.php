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

        // Filter search - nama perusahaan
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('company_name', 'like', "%{$search}%");
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('company_type_id', $request->type);
        }

        // Filter by tier
        if ($request->filled('tier')) {
            $query->where('tier', $request->tier);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Pagination
        $perPage = 10;
        $companies = $query->orderBy('company_name', 'asc')->paginate($perPage);

        // Format response untuk AJAX
        return response()->json([
            'data' => $companies->items(),
            'meta' => [
                'current_page' => $companies->currentPage(),
                'last_page' => $companies->lastPage(),
                'per_page' => $companies->perPage(),
                'total' => $companies->total(),
                'from' => $companies->firstItem() ?? 0,
                'to' => $companies->lastItem() ?? 0,
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
}