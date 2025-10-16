<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    // GET page customers
    public function index()
    {
        $provinces = Province::orderBy('name')->get();
        return view('pages.customers', compact('provinces'));
    }

    // GET all customers (AJAX)
    public function customers()
    {
        $customers = Customer::with(['province', 'regency', 'district', 'village'])->get();
        return response()->json($customers);
    }

    // GET single customer (AJAX)
    public function show($id)
    {
        $customer = Customer::with(['province', 'regency', 'district', 'village'])->findOrFail($id);
        return response()->json($customer);
    }

    // Cascade Address Methods
    public function getRegencies($provinceId)
    {
        try {
            if (empty($provinceId)) {
                return response()->json(['error' => 'Province ID required'], 400);
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

    // POST create customer (AJAX)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Personal,Company',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'province_id' => 'nullable|exists:provinces,id',
            'regency_id' => 'nullable|exists:regencies,id',
            'district_id' => 'nullable|exists:districts,id',
            'village_id' => 'nullable|exists:villages,id',
            'status' => 'required|in:Lead,Prospect,Active,Inactive',
            'source' => 'nullable|string|max:100',
            'pic' => 'required|string|max:100',
            'notes' => 'nullable|string',
            'contact_person_name' => 'nullable|string|max:255',
            'contact_person_email' => 'nullable|email',
            'contact_person_phone' => 'nullable|string|max:20',
        ]);

        $customer = Customer::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Customer berhasil ditambahkan',
            'data' => $customer->load(['province', 'regency', 'district', 'village'])
        ], 201);
    }

    // PUT update customer (AJAX)
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Personal,Company',
            'email' => 'required|email|unique:customers,email,' . $id,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'province_id' => 'nullable|exists:provinces,id',
            'regency_id' => 'nullable|exists:regencies,id',
            'district_id' => 'nullable|exists:districts,id',
            'village_id' => 'nullable|exists:villages,id',
            'status' => 'required|in:Lead,Prospect,Active,Inactive',
            'source' => 'nullable|string|max:100',
            'pic' => 'required|string|max:100',
            'notes' => 'nullable|string',
            'contact_person_name' => 'nullable|string|max:255',
            'contact_person_email' => 'nullable|email',
            'contact_person_phone' => 'nullable|string|max:20',
        ]);

        $customer->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Customer berhasil diperbarui',
            'data' => $customer->load(['province', 'regency', 'district', 'village'])
        ]);
    }

    // DELETE customer (AJAX)
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Customer berhasil dihapus'
        ]);
    }

    // BULK DELETE (AJAX)
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:customers,id'
        ]);

        $count = count($validated['ids']);
        Customer::whereIn('id', $validated['ids'])->delete();

        return response()->json([
            'success' => true,
            'message' => $count . ' customer berhasil dihapus'
        ]);
    }

    // IMPORT dari CSV/Excel
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:5120'
        ]);

        try {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('imports', $fileName, 'local');

            $filePath = storage_path('app/' . $path);
            
            // Detect file type
            $ext = $file->getClientOriginalExtension();
            
            if ($ext === 'csv') {
                $rows = array_map('str_getcsv', file($filePath));
            } else {
                // Untuk xlsx/xls, gunakan SimpleXLSX atau baca manual
                $rows = $this->readExcelFile($filePath);
            }

            if (empty($rows)) {
                Storage::delete($path);
                return response()->json([
                    'success' => false,
                    'message' => 'File kosong atau format tidak valid'
                ], 400);
            }

            $header = array_shift($rows);
            $imported = 0;
            $errors = [];

            foreach ($rows as $key => $row) {
                if (empty(array_filter($row))) continue;

                try {
                    $data = array_combine($header, $row);
                    
                    if (!$data || empty($data['email'] ?? null)) {
                        $errors[] = "Baris " . ($key + 2) . ": Email tidak boleh kosong";
                        continue;
                    }

                    // Skip jika email sudah ada
                    if (Customer::where('email', $data['email'])->exists()) {
                        $errors[] = "Baris " . ($key + 2) . ": Email sudah terdaftar";
                        continue;
                    }

                    Customer::create([
                        'customer_id' => $data['customer_id'] ?? null,
                        'name' => $data['name'] ?? 'N/A',
                        'type' => $data['type'] ?? 'Personal',
                        'email' => $data['email'],
                        'phone' => $data['phone'] ?? '',
                        'address' => $data['address'] ?? null,
                        'province_id' => $data['province_id'] ?? null,
                        'regency_id' => $data['regency_id'] ?? null,
                        'district_id' => $data['district_id'] ?? null,
                        'village_id' => $data['village_id'] ?? null,
                        'status' => $data['status'] ?? 'Lead',
                        'source' => $data['source'] ?? null,
                        'pic' => $data['pic'] ?? 'Admin',
                        'notes' => $data['notes'] ?? null,
                        'contact_person_name' => $data['contact_person_name'] ?? null,
                        'contact_person_email' => $data['contact_person_email'] ?? null,
                        'contact_person_phone' => $data['contact_person_phone'] ?? null,
                    ]);

                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Baris " . ($key + 2) . ": " . $e->getMessage();
                }
            }

            // Hapus file
            Storage::delete($path);

            return response()->json([
                'success' => true,
                'message' => "$imported data customer berhasil diimpor",
                'imported' => $imported,
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // Helper untuk baca Excel
    private function readExcelFile($filePath)
    {
        $rows = [];
        // Jika menggunakan library, sesuaikan di sini
        // Contoh dengan PhpOffice/PhpSpreadsheet
        return $rows;
    }

    // EXPORT ke CSV
    public function export()
    {
        $customers = Customer::with(['province', 'regency', 'district', 'village'])->get();

        $filename = 'customers-' . date('Y-m-d-His') . '.csv';
        $handle = fopen('php://memory', 'w');

        // Header
        fputcsv($handle, [
            'ID', 'Customer ID', 'Nama', 'Tipe', 'Email', 'Telepon',
            'Alamat', 'Provinsi', 'Kabupaten', 'Kecamatan', 'Kelurahan',
            'Status', 'Source', 'PIC', 'Notes',
            'Contact Person Nama', 'Contact Person Email', 'Contact Person Telepon'
        ]);

        // Data
        foreach ($customers as $customer) {
            fputcsv($handle, [
                $customer->id,
                $customer->customer_id,
                $customer->name,
                $customer->type,
                $customer->email,
                $customer->phone,
                $customer->address,
                $customer->province->name ?? '',
                $customer->regency->name ?? '',
                $customer->district->name ?? '',
                $customer->village->name ?? '',
                $customer->status,
                $customer->source,
                $customer->pic,
                $customer->notes,
                $customer->contact_person_name,
                $customer->contact_person_email,
                $customer->contact_person_phone,
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}