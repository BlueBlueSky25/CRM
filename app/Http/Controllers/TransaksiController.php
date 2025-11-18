<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\SalesVisit;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksi = Transaksi::with(['sales', 'company', 'salesVisit'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // FIXED: Ambil semua user, biarkan select di frontend filter
        $sales = User::all();
        
        $companies = Company::all();
        $salesVisits = SalesVisit::with('company')->get();
        $currentMenuId = 17;
        
        return view('pages.transaksi', compact('transaksi', 'sales', 'companies', 'salesVisits', 'currentMenuId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sales_visit_id' => 'nullable|exists:sales_visits,id',
            'sales_id' => 'required|exists:users,user_id',
            'company_id' => 'required|exists:company,company_id',
            'nama_sales' => 'required|string|max:255',
            'nama_perusahaan' => 'required|string|max:255',
            'nilai_proyek' => 'required|numeric|min:0',
            'status' => 'required|in:Deals,Fails',
            'bukti_spk' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'bukti_dp' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'tanggal_mulai_kerja' => 'nullable|date',
            'tanggal_selesai_kerja' => 'nullable|date|after_or_equal:tanggal_mulai_kerja',
            'keterangan' => 'nullable|string',
        ]);

        if ($request->hasFile('bukti_spk')) {
            $validated['bukti_spk'] = $request->file('bukti_spk')->store('transaksi/spk', 'public');
        }

        if ($request->hasFile('bukti_dp')) {
            $validated['bukti_dp'] = $request->file('bukti_dp')->store('transaksi/dp', 'public');
        }

        Transaksi::create($validated);

        return redirect()->route('transaksi')
            ->with('success', 'Transaksi berhasil ditambahkan!');
    }

    public function show($id)
    {
        $item = Transaksi::with(['sales', 'company', 'salesVisit'])->findOrFail($id);
        return response()->json($item);
    }

    public function edit($id)
    {
        $item = Transaksi::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $validated = $request->validate([
            'sales_visit_id' => 'nullable|exists:sales_visits,id',
            'sales_id' => 'required|exists:users,user_id',
            'company_id' => 'required|exists:company,company_id',
            'nama_sales' => 'required|string|max:255',
            'nama_perusahaan' => 'required|string|max:255',
            'nilai_proyek' => 'required|numeric|min:0',
            'status' => 'required|in:Deals,Fails',
            'bukti_spk' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'bukti_dp' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'tanggal_mulai_kerja' => 'nullable|date',
            'tanggal_selesai_kerja' => 'nullable|date|after_or_equal:tanggal_mulai_kerja',
            'keterangan' => 'nullable|string',
        ]);

        if ($request->hasFile('bukti_spk')) {
            if ($transaksi->bukti_spk) {
                Storage::disk('public')->delete($transaksi->bukti_spk);
            }
            $validated['bukti_spk'] = $request->file('bukti_spk')->store('transaksi/spk', 'public');
        }

        if ($request->hasFile('bukti_dp')) {
            if ($transaksi->bukti_dp) {
                Storage::disk('public')->delete($transaksi->bukti_dp);
            }
            $validated['bukti_dp'] = $request->file('bukti_dp')->store('transaksi/dp', 'public');
        }

        $transaksi->update($validated);

        return redirect()->route('transaksi')
            ->with('success', 'Transaksi berhasil diupdate!');
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->bukti_spk) {
            Storage::disk('public')->delete($transaksi->bukti_spk);
        }
        if ($transaksi->bukti_dp) {
            Storage::disk('public')->delete($transaksi->bukti_dp);
        }

        $transaksi->delete();

        return redirect()->route('transaksi')
            ->with('success', 'Transaksi berhasil dihapus!');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $transaksi = Transaksi::with(['sales', 'company'])
            ->where('nama_sales', 'like', "%{$search}%")
            ->orWhere('nama_perusahaan', 'like', "%{$search}%")
            ->orWhere('status', 'like', "%{$search}%")
            ->orderBy('created_at', 'desc')
            ->get();

        $sales = User::all();
        $companies = Company::all();
        $salesVisits = SalesVisit::with('company')->get();
        $currentMenuId = 17;

        return view('pages.transaksi', compact('transaksi', 'sales', 'companies', 'salesVisits', 'currentMenuId'));
    }
}