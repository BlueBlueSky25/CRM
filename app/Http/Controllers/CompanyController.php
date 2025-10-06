<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyType;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::with('companyType')->paginate(5);
        $types = CompanyType::where('is_active', true)->get();
        return view('layout.company', compact('companies', 'types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required',
            'company_type_id' => 'required',
            'tier' => 'nullable|string',
            'status' => 'nullable|string'
        ]);

        Company::create($request->all());
        return redirect()->back()->with('success', 'Perusahaan berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $company = Company::findOrFail($id);
        $company->update($request->all());
        return redirect()->back()->with('success', 'Data perusahaan diperbarui');
    }

    public function destroy($id)
    {
        Company::destroy($id);
        return redirect()->back()->with('success', 'Perusahaan dihapus');
    }
}