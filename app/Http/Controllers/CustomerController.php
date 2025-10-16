<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    // Tampilkan halaman utama Customers
    public function customers(Request $request)
    {
        // Jika ada filter via AJAX
        if ($request->ajax()) {
            $customers = Customer::query()
                ->search($request->search)
                ->byType($request->type)
                ->byStatus($request->status)
                ->bySource($request->source)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json($customers);
        }

        // Load halaman dengan data awal
        $customers = Customer::orderBy('created_at', 'desc')->get();
        return view('pages.customers', compact('customers'));
    }

    // Get all customers (untuk AJAX)
    public function getCustomers(Request $request)
    {
        $customers = Customer::query()
            ->search($request->search)
            ->byType($request->type)
            ->byStatus($request->status)
            ->bySource($request->source)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($customers);
    }

    // Show single customer
    public function show($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            
            // Add contact_person attribute for Company type
            $customerData = $customer->toArray();
            if ($customer->type === 'Company') {
                $customerData['contact_person'] = $customer->contact_person;
            }
            
            return response()->json($customerData);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Customer tidak ditemukan'
            ], 404);
        }
    }

    // Simpan data baru
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|in:Personal,Company',
                'email' => 'required|email|unique:customers,email',
                'phone' => 'required|string|max:20',
                'address' => 'required|string',
                'status' => 'required|in:Lead,Prospect,Active,Inactive',
                'source' => 'required|in:Website,Referral,Ads,Walk-in,Social Media',
                'pic' => 'required|string|max:255',
                'notes' => 'nullable|string',
                // Contact Person (opsional untuk Company)
                'contact_person_name' => 'nullable|string|max:255',
                'contact_person_email' => 'nullable|email',
                'contact_person_phone' => 'nullable|string|max:20',
            ]);

            $customer = Customer::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Customer berhasil ditambahkan!',
                'data' => $customer
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // Update data
    public function update(Request $request, $id)
    {
        try {
            $customer = Customer::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|in:Personal,Company',
                'email' => "required|email|unique:customers,email,{$id}",
                'phone' => 'required|string|max:20',
                'address' => 'required|string',
                'status' => 'required|in:Lead,Prospect,Active,Inactive',
                'source' => 'required|in:Website,Referral,Ads,Walk-in,Social Media',
                'pic' => 'required|string|max:255',
                'notes' => 'nullable|string',
                // Contact Person
                'contact_person_name' => 'nullable|string|max:255',
                'contact_person_email' => 'nullable|email',
                'contact_person_phone' => 'nullable|string|max:20',
            ]);

            $customer->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Customer berhasil diupdate!',
                'data' => $customer
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // Hapus data
    public function destroy($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->delete();

            return response()->json([
                'success' => true,
                'message' => 'Customer berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus customer: ' . $e->getMessage()
            ], 500);
        }
    }

    // Bulk delete
    public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->input('ids', []);
            
            if (empty($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada customer yang dipilih'
                ], 400);
            }
            
            $deleted = Customer::whereIn('id', $ids)->delete();

            return response()->json([
                'success' => true,
                'message' => $deleted . ' customer berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus customer: ' . $e->getMessage()
            ], 500);
        }
    }

    // Export to CSV
    public function export()
    {
        $customers = Customer::orderBy('created_at', 'desc')->get();

        $filename = 'customers_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($customers) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, [
                'Customer ID', 'Nama', 'Tipe', 'Email', 'Telepon', 
                'Alamat', 'Status', 'Source', 'PIC', 'Notes', 
                'Contact Person Name', 'Contact Person Email', 'Contact Person Phone',
                'Tanggal Dibuat'
            ]);

            // Data
            foreach ($customers as $customer) {
                fputcsv($file, [
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
                    $customer->contact_person_name ?? '',
                    $customer->contact_person_email ?? '',
                    $customer->contact_person_phone ?? '',
                    $customer->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Import from CSV
    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:csv,txt|max:2048'
            ]);

            $file = $request->file('file');
            $path = $file->getRealPath();
            $data = array_map('str_getcsv', file($path));
            
            // Skip header
            $header = array_shift($data);
            
            $imported = 0;
            $errors = [];
            
            foreach ($data as $index => $row) {
                try {
                    // Skip empty rows
                    if (empty(array_filter($row))) {
                        continue;
                    }
                    
                    Customer::create([
                        'name' => $row[0] ?? '',
                        'type' => $row[1] ?? 'Personal',
                        'email' => $row[2] ?? '',
                        'phone' => $row[3] ?? '',
                        'address' => $row[4] ?? '',
                        'status' => $row[5] ?? 'Lead',
                        'source' => $row[6] ?? 'Website',
                        'pic' => $row[7] ?? '',
                        'notes' => $row[8] ?? null,
                        'contact_person_name' => $row[9] ?? null,
                        'contact_person_email' => $row[10] ?? null,
                        'contact_person_phone' => $row[11] ?? null,
                    ]);
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
                    continue;
                }
            }

            $message = "$imported customer berhasil diimport!";
            if (!empty($errors)) {
                $message .= " (" . count($errors) . " baris gagal)";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'imported' => $imported,
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal import: ' . $e->getMessage()
            ], 500);
        }
    }
}