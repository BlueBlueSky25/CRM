<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\SalesVisit;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PipelineController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        
        // 1️⃣ LEAD - dari Customer dengan status 'Lead'
        $leadQuery = Customer::with(['province', 'regency', 'user'])
            ->where('status', 'Lead');
        
        // Role-based filtering untuk Lead
        if ($user->role_id == 1) {
            // superadmin - lihat semua
        } elseif (in_array($user->role_id, [7, 11])) {
            $leadQuery->whereHas('user', function ($q) {
                $q->where('role_id', 12);
            });
        } elseif ($user->role_id == 12) {
            $leadQuery->where('user_id', $user->user_id);
        } else {
            $leadQuery->whereNull('id');
        }
        
        $leads = $leadQuery->orderBy('created_at', 'desc')->get();
        
        // 2️⃣ VISIT - dari SalesVisit
        $visitQuery = SalesVisit::with(['sales', 'company', 'province', 'regency', 'pic']);
        
        if ($user->role_id == 1) {
            // superadmin
        } elseif (in_array($user->role_id, [7, 11])) {
            $visitQuery->whereHas('sales', function ($q) {
                $q->where('role_id', 12);
            });
        } elseif ($user->role_id == 12) {
            $visitQuery->where('sales_id', $user->user_id);
        } else {
            $visitQuery->whereNull('id');
        }
        
        $visits = $visitQuery->orderBy('visit_date', 'desc')->get();
        
        // 3️⃣ PENAWARAN - dari Transaksi
        $penawaranQuery = Transaksi::with(['sales', 'company', 'pic']);
        
        if ($user->role_id == 1) {
            // superadmin
        } elseif (in_array($user->role_id, [7, 11])) {
            $penawaranQuery->whereHas('sales', function ($q) {
                $q->where('role_id', 12);
            });
        } elseif ($user->role_id == 12) {
            $penawaranQuery->where('sales_id', $user->user_id);
        } else {
            $penawaranQuery->whereNull('id');
        }
        
        $penawaran = $penawaranQuery->orderBy('created_at', 'desc')->get();
        
        // 4️⃣ FOLLOW UP - dari SalesVisit dengan is_follow_up = true
        $followUpQuery = SalesVisit::with(['sales', 'company', 'province', 'regency', 'pic'])
            ->where('is_follow_up', true);
        
        if ($user->role_id == 1) {
            // superadmin
        } elseif (in_array($user->role_id, [7, 11])) {
            $followUpQuery->whereHas('sales', function ($q) {
                $q->where('role_id', 12);
            });
        } elseif ($user->role_id == 12) {
            $followUpQuery->where('sales_id', $user->user_id);
        } else {
            $followUpQuery->whereNull('id');
        }
        
        $followUps = $followUpQuery->orderBy('visit_date', 'desc')->get();
        
        $currentMenuId = 17;
        
        return view('pages.pipeline', compact('leads', 'visits', 'penawaran', 'followUps', 'currentMenuId'));
    }

    // ========== DETAIL UNTUK LEAD ==========
    public function showLead($id)
    {
        try {
            $lead = Customer::with(['province', 'regency', 'district', 'village', 'user'])
                ->findOrFail($id);
            
            // Get last visit untuk lead ini
            $lastVisit = SalesVisit::where('company_id', $lead->id)
                ->latest('visit_date')
                ->first();
            
            return response()->json([
                'success' => true,
                'type' => 'lead',
                'data' => [
                    'id' => $lead->id,
                    'nama' => $lead->name,
                    'email' => $lead->email,
                    'phone' => $lead->phone,
                    'pic' => $lead->pic ?? '-',
                    'address' => $lead->address ?? '-',
                    'location' => collect([
                        optional($lead->province)->name,
                        optional($lead->regency)->name,
                        optional($lead->district)->name,
                        optional($lead->village)->name
                    ])->filter()->implode(', ') ?: '-',
                    'status' => $lead->status,
                    'source' => $lead->source ?? '-',
                    'notes' => $lead->notes ?? '-',
                    'contact_person_name' => $lead->contact_person_name ?? '-',
                    'contact_person_email' => $lead->contact_person_email ?? '-',
                    'contact_person_phone' => $lead->contact_person_phone ?? '-',
                    'created_at' => $lead->created_at->format('d/m/Y'),
                    'created_by' => optional($lead->user)->username ?? '-',
                    'last_visit' => $lastVisit ? $lastVisit->visit_date->format('d/m/Y') : '-'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan: ' . $e->getMessage()
            ], 404);
        }
    }

    // ========== DETAIL UNTUK VISIT ==========
    public function showVisit($id)
    {
        try {
            $visit = SalesVisit::with(['sales', 'company', 'province', 'regency', 'district', 'village', 'pic'])
                ->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'type' => 'visit',
                'data' => [
                    'id' => $visit->id,
                    'company' => optional($visit->company)->company_name ?? '-',
                    'pic_name' => $visit->pic_name ?? '-',
                    'pic_phone' => optional($visit->pic)->phone ?? '-',
                    'pic_email' => optional($visit->pic)->email ?? '-',
                    'pic_position' => optional($visit->pic)->position ?? '-',
                    'sales_name' => optional($visit->sales)->username ?? '-',
                    'sales_email' => optional($visit->sales)->email ?? '-',
                    'visit_date' => $visit->visit_date->format('d/m/Y'),
                    'location' => collect([
                        optional($visit->province)->name,
                        optional($visit->regency)->name,
                        optional($visit->district)->name,
                        optional($visit->village)->name
                    ])->filter()->implode(', ') ?: '-',
                    'address' => $visit->address ?? '-',
                    'visit_purpose' => $visit->visit_purpose ?? '-',
                    'is_follow_up' => $visit->is_follow_up ? 'Ya' : 'Tidak',
                    'created_at' => $visit->created_at->format('d/m/Y')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan: ' . $e->getMessage()
            ], 404);
        }
    }

    // ========== DETAIL UNTUK PENAWARAN ==========
    public function showPenawaran($id)
    {
        try {
            $transaksi = Transaksi::with(['sales', 'company', 'pic', 'salesVisit'])
                ->findOrFail($id);
            
            // Calculate work duration
            $workDuration = $this->calculateWorkDuration(
                $transaksi->tanggal_mulai_kerja,
                $transaksi->tanggal_selesai_kerja
            );
            
            // Get last visit untuk perusahaan ini
            $lastVisit = SalesVisit::where('company_id', $transaksi->company_id)
                ->latest('visit_date')
                ->first();
            
            return response()->json([
                'success' => true,
                'type' => 'penawaran',
                'data' => [
                    'id' => $transaksi->id,
                    'nama_perusahaan' => $transaksi->nama_perusahaan,
                    'pic_name' => $transaksi->pic_name ?? '-',
                    'pic_phone' => optional($transaksi->pic)->phone ?? '-',
                    'pic_email' => optional($transaksi->pic)->email ?? '-',
                    'nama_sales' => $transaksi->nama_sales,
                    'sales_email' => optional($transaksi->sales)->email ?? '-',
                    'nilai_proyek' => 'Rp ' . number_format($transaksi->nilai_proyek, 0, ',', '.'),
                    'status' => $transaksi->status,
                    'tanggal_mulai_kerja' => $transaksi->tanggal_mulai_kerja ? Carbon::parse($transaksi->tanggal_mulai_kerja)->format('d/m/Y') : '-',
                    'tanggal_selesai_kerja' => $transaksi->tanggal_selesai_kerja ? Carbon::parse($transaksi->tanggal_selesai_kerja)->format('d/m/Y') : '-',
                    'work_duration' => $workDuration,
                    'keterangan' => $transaksi->keterangan ?? '-',
                    'bukti_spk' => $transaksi->bukti_spk ?? null,
                    'bukti_dp' => $transaksi->bukti_dp ?? null,
                    'created_at' => $transaksi->created_at->format('d/m/Y'),
                    'last_visit' => $lastVisit ? $lastVisit->visit_date->format('d/m/Y') : '-'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan: ' . $e->getMessage()
            ], 404);
        }
    }

    // ========== DETAIL UNTUK FOLLOW UP ==========
    public function showFollowUp($id)
    {
        return $this->showVisit($id);
    }

    // ========== HELPER: Calculate Work Duration ==========
    private function calculateWorkDuration($startDate, $endDate)
    {
        if (!$startDate || !$endDate) {
            return '-';
        }

        try {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
            
            $days = $start->diffInDays($end);
            $hours = $start->diffInHours($end) % 24;
            
            if ($days > 0 && $hours > 0) {
                return "$days hari $hours jam";
            } elseif ($days > 0) {
                return "$days hari";
            } elseif ($hours > 0) {
                return "$hours jam";
            } else {
                return "0 jam";
            }
        } catch (\Exception $e) {
            return '-';
        }
    }
}