@props(['salesVisits', 'currentMenuId'])

<!-- Table Only (No Header, No Pagination) -->
<div class="overflow-x-auto" style="margin: 0; padding: 0;">
    <table id="salesVisitTable" class="w-full" style="margin: 0; border-collapse: collapse;">
        <thead style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
            <tr>
                <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 0.7rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;">No</th>
                <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 0.7rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;">Visit Date</th>
                <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 0.7rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;">Company</th>
                <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 0.7rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;">PIC Name</th>
                <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 0.7rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;">Location</th>
                <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 0.7rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;">Purpose</th>
                <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 0.7rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;">Sales</th>
                <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 0.7rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;">Follow Up</th>
                <th style="padding: 0.5rem 0.75rem; text-align: right; font-size: 0.7rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;">Aksi</th>
            </tr>
        </thead>
        <tbody style="background-color: #ffffff; border-top: 1px solid #e5e7eb;">
            @forelse($salesVisits as $index => $visit)
            <tr style="border-bottom: 1px solid #e5e7eb; transition: background-color 0.15s;" onmouseover="this.style.backgroundColor='#f9fafb'" onmouseout="this.style.backgroundColor='#ffffff'">
                <!-- No -->
                <td style="padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #111827; white-space: nowrap;">
                    <span style="font-weight: 500;">{{ $salesVisits->firstItem() + $index }}</span>
                </td>
                
                <!-- Visit Date -->
                <td style="padding: 0.5rem 0.75rem; white-space: nowrap;">
                    <div style="font-size: 0.8125rem; color: #111827;">
                        @if($visit->visit_date)
                            <div style="display: flex; align-items: center; gap: 0.375rem;">
                                <i class="fas fa-calendar" style="color: #9ca3af; font-size: 0.6875rem;"></i>
                                <span>{{ $visit->visit_date->format('d/m/Y') }}</span>
                            </div>
                        @else
                            <span style="color: #9ca3af;">-</span>
                        @endif
                    </div>
                </td>
                
                <!-- Company - Ambil dari relasi -->
                <td style="padding: 0.5rem 0.75rem;">
                    <div style="font-size: 0.8125rem; color: #111827;">
                        @if($visit->company)
                            <span style="display: flex; align-items: center; gap: 0.25rem;">
                                <i class="fas fa-building" style="color: #9ca3af; font-size: 0.6875rem;"></i>
                                {{ $visit->company->company_name }}
                            </span>
                        @else
                            <span style="color: #9ca3af;">-</span>
                        @endif
                    </div>
                </td>
                
                <!-- PIC Name -->
                <td style="padding: 0.5rem 0.75rem; white-space: nowrap;">
                    <div style="font-size: 0.8125rem; font-weight: 500; color: #111827;">{{ $visit->pic_name ?? '-' }}</div>
                </td>
                
                <!-- Location - ✅ UPDATED -->
                <td style="padding: 0.5rem 0.75rem;">
                    <div style="font-size: 0.8125rem; color: #111827;">
                        @php
                            // Build location dengan format yang sama kayak search
                            $province = optional($visit->province)->name ?? '';
                            $regency = optional($visit->regency)->name ?? '';
                            $district = optional($visit->district)->name ?? '';
                            $village = optional($visit->village)->name ?? '';
                            
                            // Main location (Province)
                            $locationMain = $province ?: '-';
                            
                            // Sub location (Regency, District, Village)
                            $locationSub = collect([$regency, $district, $village])
                                ->filter()
                                ->implode(', ');
                        @endphp
                        
                        @if($locationMain !== '-')
                            <div style="display: flex; align-items: center; gap: 0.25rem;">
                                <i class="fas fa-map-marker-alt" style="color: #9ca3af; font-size: 0.6875rem;"></i>
                                <span>{{ $locationMain }}</span>
                            </div>
                            @if($locationSub)
                                <div style="font-size: 0.6875rem; color: #6b7280; margin-top: 0.125rem;">
                                    {{ $locationSub }}
                                </div>
                            @endif
                        @else
                            <span style="color: #9ca3af;">-</span>
                        @endif
                    </div>
                </td>
                
                <!-- Purpose -->
                <td style="padding: 0.5rem 0.75rem;">
                    <div style="font-size: 0.8125rem; color: #374151; max-width: 16rem;">
                        @if($visit->visit_purpose)
                            <span style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" title="{{ $visit->visit_purpose }}">
                                {{ Str::limit($visit->visit_purpose, 45) }}
                            </span>
                        @else
                            <span style="color: #9ca3af;">-</span>
                        @endif
                    </div>
                </td>
                
                <!-- Sales -->
                <td style="padding: 0.5rem 0.75rem; white-space: nowrap;">
                    <div style="display: flex; align-items: center;">
                        <div style="width: 2rem; height: 2rem; flex-shrink: 0;">
                            <div style="width: 2rem; height: 2rem; border-radius: 9999px; background-color: #e0e7ff; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user-tie" style="color: #6366f1; font-size: 0.75rem;"></i>
                            </div>
                        </div>
                        <div style="margin-left: 0.5rem;">
                            <div style="font-size: 0.8125rem; font-weight: 500; color: #111827;">{{ $visit->sales->username ?? '-' }}</div>
                            <div style="font-size: 0.6875rem; color: #6b7280;">{{ $visit->sales->email ?? 'No email' }}</div>
                        </div>
                    </div>
                </td>
                
                <!-- Follow Up -->
                <td style="padding: 0.5rem 0.75rem; white-space: nowrap;">
                    @if($visit->is_follow_up)
                        <span style="display: inline-flex; align-items: center; padding: 0.125rem 0.5rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 500; background-color: #d1fae5; color: #065f46;">
                            <i class="fas fa-check-circle" style="margin-right: 0.25rem; font-size: 0.625rem;"></i>
                            Ya
                        </span>
                    @else
                        <span style="display: inline-flex; align-items: center; padding: 0.125rem 0.5rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 500; background-color: #f3f4f6; color: #374151;">
                            <i class="fas fa-times-circle" style="margin-right: 0.25rem; font-size: 0.625rem;"></i>
                            Tidak
                        </span>
                    @endif
                </td>
                
                <!-- Aksi -->
                <td style="padding: 0.5rem 0.75rem; font-size: 0.8125rem; font-weight: 500; text-align: right; white-space: nowrap;">
                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.375rem;">
                        @if(auth()->user()->canAccess($currentMenuId, 'edit'))
                        <button 
                            onclick="openEditVisitModal({{ json_encode([
                                'id' => $visit->id,
                                'salesId' => $visit->sales_id,
                                'picName' => $visit->pic_name,
                                'companyId' => $visit->company_id,
                                'companyName' => optional($visit->company)->company_name ?? '',
                                'provinceId' => $visit->province_id,
                                'regencyId' => $visit->regency_id,
                                'districtId' => $visit->district_id,
                                'villageId' => $visit->village_id,
                                'address' => $visit->address ?? '',
                                'visitDate' => $visit->visit_date->format('Y-m-d'),
                                'purpose' => $visit->visit_purpose,
                                'followUp' => $visit->is_follow_up ? 1 : 0
                            ]) }})"
                            style="color: #2563eb; background: transparent; border: none; padding: 0.375rem; border-radius: 0.375rem; cursor: pointer; transition: all 0.15s; font-size: 0.875rem;"
                            onmouseover="this.style.backgroundColor='#dbeafe'; this.style.color='#1e40af';"
                            onmouseout="this.style.backgroundColor='transparent'; this.style.color='#2563eb';"
                            title="Edit Visit">
                            <i class="fas fa-edit"></i>
                        </button>
                        @endif
                        
                        @if(auth()->user()->canAccess($currentMenuId, 'delete'))
                        <button type="button" 
                            onclick="deleteVisit({{ $visit->id }}, '{{ route('salesvisit.destroy', $visit->id) }}', '{{ csrf_token() }}')"
                            style="color: #dc2626; background: transparent; border: none; padding: 0.375rem; border-radius: 0.375rem; cursor: pointer; transition: all 0.15s; font-size: 0.875rem;"
                            onmouseover="this.style.backgroundColor='#fee2e2'; this.style.color='#991b1b';"
                            onmouseout="this.style.backgroundColor='transparent'; this.style.color='#dc2626';"
                            title="Hapus Visit">
                            <i class="fas fa-trash"></i>
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="padding: 3rem 1.5rem; text-align: center;">
                    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <div style="width: 6rem; height: 6rem; border-radius: 9999px; background-color: #f3f4f6; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                            <i class="fas fa-inbox" style="font-size: 3rem; color: #d1d5db;"></i>
                        </div>
                        <h3 style="font-size: 1.125rem; font-weight: 500; color: #111827; margin: 0 0 0.25rem 0;">Belum Ada Data</h3>
                        <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">Belum ada data kunjungan sales yang tersedia</p>
                        @if(auth()->user()->canAccess($currentMenuId, 'create'))
                        <button onclick="openVisitModal()" style="margin-top: 1rem; padding: 0.5rem 1rem; background-color: #6366f1; color: white; border: none; border-radius: 0.5rem; cursor: pointer; transition: background-color 0.2s;">
                            <i class="fas fa-plus" style="margin-right: 0.5rem;"></i>
                            Tambah Kunjungan
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<style>
/* Responsive improvements */
@media (max-width: 1024px) {
    #salesVisitTable {
        font-size: 0.8125rem;
    }
    
    #salesVisitTable th,
    #salesVisitTable td {
        padding: 0.4rem 0.65rem;
    }
}

@media (max-width: 768px) {
    #salesVisitTable {
        font-size: 0.75rem;
    }
    
    #salesVisitTable th,
    #salesVisitTable td {
        padding: 0.375rem 0.5rem;
    }
}
</style>