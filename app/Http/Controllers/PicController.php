<?php

namespace App\Http\Controllers;

use App\Models\CompanyPic;
use App\Models\Company;
use Illuminate\Http\Request;

class PicController extends Controller
{
    /**
     * List semua PIC
     */
    public function index()
    {
        $pics = CompanyPic::with('company')->paginate(5);
        $companies = Company::orderBy('company_name')->get();
        $currentMenuId = 10; // Sesuaikan dengan menu ID PIC di database
        
        return view('pages.pic', compact('pics', 'companies', 'currentMenuId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:company,company_id',
            'name'       => 'required|string|max:255',
            'position'   => 'nullable|string|max:255',
            'phone'      => 'nullable|string|max:20',
            'email'      => 'nullable|email|max:255',
        ]);

        CompanyPic::create([
            'company_id' => $request->company_id,
            'name'       => $request->name,
            'position'   => $request->position,
            'phone'      => $request->phone,
            'email'      => $request->email,
        ]);

        return redirect()->route('pic')->with('success', 'PIC berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $pic = CompanyPic::findOrFail($id);

        $request->validate([
            'company_id' => 'required|exists:company,company_id',
            'name'       => 'required|string|max:255',
            'position'   => 'nullable|string|max:255',
            'phone'      => 'nullable|string|max:20',
            'email'      => 'nullable|email|max:255',
        ]);

        $pic->update([
            'company_id' => $request->company_id,
            'name'       => $request->name,
            'position'   => $request->position,
            'phone'      => $request->phone,
            'email'      => $request->email,
        ]);

        return redirect()->back()->with('success', 'PIC berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $pic = CompanyPic::findOrFail($id);
        $pic->delete();

        return redirect()->route('pic')->with('success', 'PIC berhasil dihapus!');
    }

    public function search(Request $request)
    {
        $query = CompanyPic::with('company');

        // Filter search
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        // Filter by company
        if ($request->filled('company')) {
            $companyId = $request->company;
            $query->where('company_id', $companyId);
        }

        $pics = $query->paginate(5);

        return response()->json([
            'items' => $pics->map(function($pic, $index) use ($pics) {
                return [
                    'number' => $pics->firstItem() + $index,
                    'pic' => [
                        'name' => $pic->name ?? '-',
                        'position' => $pic->position ?? '-'
                    ],
                    'company' => $pic->company->company_name ?? '-',
                    'phone' => $pic->phone ?? '-',
                    'email' => $pic->email ?? '-',
                    'actions' => $this->getPicActions($pic)
                ];
            })->toArray(),
            'pagination' => [
                'current_page' => $pics->currentPage(),
                'last_page' => $pics->lastPage(),
                'from' => $pics->firstItem(),
                'to' => $pics->lastItem(),
                'total' => $pics->total()
            ]
        ]);
    }

    private function getPicActions($pic)
    {
        $currentMenuId = 10; // Sesuaikan dengan menu ID
        $canEdit = auth()->user()->canAccess($currentMenuId, 'edit');
        $canDelete = auth()->user()->canAccess($currentMenuId, 'delete');

        $actions = [];

        if ($canEdit) {
            $actions[] = [
                'type' => 'edit',
                'onclick' => "openEditPICModal(
                    '{$pic->pic_id}',
                    '{$pic->company_id}',
                    '" . addslashes($pic->name) . "',
                    '" . addslashes($pic->position ?? '') . "',
                    '" . addslashes($pic->email ?? '') . "',
                    '" . addslashes($pic->phone ?? '') . "'
                )",
                'title' => 'Edit PIC'
            ];
        }

        if ($canDelete) {
            $csrfToken = csrf_token();
            $deleteRoute = route('pics.destroy', $pic->pic_id);
            
            $actions[] = [
                'type' => 'delete',
                'onclick' => "deletePIC('{$pic->pic_id}', '{$deleteRoute}', '{$csrfToken}')",
                'title' => 'Delete PIC'
            ];
        }

        return $actions;
    }
}