<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    // GET page customers
    public function index()
    {
        return view('pages.customers'); // Sesuaikan dengan nama view mu
    }

    // GET all customers (AJAX)
    public function customers()
    {
        $customers = Customer::all();
        return response()->json($customers);
    }

    // GET single customer (AJAX)
    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return response()->json($customer);
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
            'data' => $customer
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
            'data' => $customer
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
        $customers = Customer::all();

        $filename = 'customers-' . date('Y-m-d-His') . '.csv';
        $handle = fopen('php://memory', 'w');

        // Header
        fputcsv($handle, [
            'ID', 'Customer ID', 'Nama', 'Tipe', 'Email', 'Telepon',
            'Alamat', 'Status', 'Source', 'PIC', 'Notes',
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