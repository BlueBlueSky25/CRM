@props(['salesUsers', 'provinces', 'currentMenuId'])

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden fade-in">

    <!-- Table -->
    <div class="overflow-x-auto">
        <table id="salesTable" class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-3 py-2 text-left text-[10px] font-medium text-gray-500 uppercase tracking-tight">No</th>
                    <th class="px-3 py-2 text-left text-[10px] font-medium text-gray-500 uppercase tracking-tight">Name</th>
                    <th class="px-3 py-2 text-left text-[10px] font-medium text-gray-500 uppercase tracking-tight">Phone</th>
                    <th class="px-3 py-2 text-left text-[10px] font-medium text-gray-500 uppercase tracking-tight">Birth Date</th>
                    <th class="px-3 py-2 text-left text-[10px] font-medium text-gray-500 uppercase tracking-tight">Address</th>
                    <th class="px-3 py-2 text-left text-[10px] font-medium text-gray-500 uppercase tracking-tight">Roles</th>
                    <th class="px-3 py-2 text-left text-[10px] font-medium text-gray-500 uppercase tracking-tight">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($salesUsers as $index => $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 text-xs text-gray-900">{{ $loop->iteration }}</td>
                    <td class="px-3 py-2">
                        <div class="flex items-center">
                            <div>
                                <div class="text-xs font-medium text-gray-900">{{ $user->username }}</div>
                                <div class="text-[10px] text-gray-500">{{ $user->email ?? 'No email' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-3 py-2 text-xs text-gray-900">{{ $user->phone ?? '-' }}</td>
                    <td class="px-3 py-2 text-xs text-gray-900">
                        {{ $user->birth_date ? date('d-m-Y', strtotime($user->birth_date)) : '-' }}
                    </td>
                    <td class="px-3 py-2 max-w-xs">
                        <div class="text-xs text-gray-900">
                            @if($user->province || $user->regency || $user->district || $user->village || $user->address)
                                @php
                                    $alamatWilayah = collect([
                                        $user->village->name ?? null,
                                        $user->district->name ?? null, 
                                        $user->regency->name ?? null,
                                        $user->province->name ?? null
                                    ])->filter()->implode(', ');
                                @endphp
                                
                                @if($alamatWilayah)
                                    <div class="font-medium truncate" title="{{ $alamatWilayah }}">{{ $alamatWilayah }}</div>
                                    @if($user->address)
                                        <div class="text-[10px] text-gray-600 truncate" title="{{ $user->address }}">{{ $user->address }}</div>
                                    @endif
                                @else
                                    {{ $user->address ?? '-' }}
                                @endif
                            @else
                                -
                            @endif
                        </div>
                    </td>
                    <td class="px-3 py-2 text-xs">
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-blue-100 text-blue-800">
                            {{ $user->role->role_name ?? 'No Role' }}
                        </span>
                    </td>
                    <td class="px-3 py-2 text-xs font-medium">
                        <div class="flex items-center space-x-1">
                            {{-- Show Detail Button --}}
                            @if(auth()->user()->canAccess($currentMenuId, 'view'))
                            <button 
                                onclick="showSalesDetail('{{ $user->user_id }}')" 
                                class="text-green-600 hover:text-green-900 p-1.5 rounded-lg hover:bg-green-50 flex items-center"
                                title="Show Detail">
                                <i class="fas fa-eye text-xs"></i>
                            </button>
                            @endif

                            @if(auth()->user()->canAccess($currentMenuId, 'edit'))
                            <button onclick="openEditSalesModal('{{ $user->user_id }}', '{{ $user->username }}', '{{ $user->email }}', '{{ $user->phone }}', '{{ $user->birth_date }}', '{{ $user->address }}', '{{ $user->province_id }}', '{{ $user->regency_id }}', '{{ $user->district_id }}', '{{ $user->village_id }}')" 
                                class="text-blue-600 hover:text-blue-900 p-1.5 rounded-lg hover:bg-blue-50 flex items-center" 
                                title="Edit User">
                                <i class="fas fa-edit text-xs"></i>
                            </button>
                            @endif
                            
                            @if(auth()->user()->canAccess($currentMenuId, 'delete'))
                            <form action="{{ route('marketing.sales.destroy', $user->user_id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 p-1.5 flex items-center" title="Hapus User" onclick="return confirm('Yakin ingin menghapus user ini?')">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <x-globals.pagination :paginator="$salesUsers" />
</div>

{{-- Sales User Detail Modal --}}
<div id="salesDetailModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background-color: white; border-radius: 0.5rem; width: 95%; height: 90vh; max-width: 1600px; display: flex; flex-direction: column; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
        
        {{-- Modal Header --}}
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.25rem; background: linear-gradient(to right, #2563eb, #3b82f6); flex-shrink: 0; border-radius: 0.5rem 0.5rem 0 0;">
            <h3 style="font-size: 1.125rem; font-weight: 600; color: white; margin: 0;">
                <i class="fas fa-user-tie" style="margin-right: 0.5rem;"></i>
                Detail Sales User
            </h3>
            <button onclick="closeSalesDetailModal()" style="color: white; background: transparent; border: none; font-size: 1.5rem; cursor: pointer; padding: 0; line-height: 1; opacity: 0.9; transition: opacity 0.15s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.9'">
                <i class="fas fa-times"></i>
            </button>
        </div>

        {{-- Modal Body: Split Layout --}}
        <div style="display: flex; flex: 1; overflow: hidden; gap: 0;">
            
            {{-- LEFT SIDE: 1/4 (Personal & Address Info) --}}
            <div style="width: 25%; border-right: 1px solid #e5e7eb; overflow-y: auto; padding: 1rem; background-color: #fafbfc;">
                
                {{-- Personal Info --}}
                <div style="background-color: white; padding: 0.875rem; border-radius: 0.5rem; margin-bottom: 1rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                    <h4 style="font-size: 0.9375rem; font-weight: 600; color: #111827; margin: 0 0 0.75rem 0; display: flex; align-items: center; gap: 0.5rem; border-bottom: 2px solid #2563eb; padding-bottom: 0.5rem;">
                        <i class="fas fa-id-card" style="color: #2563eb;"></i>
                        Personal Info
                    </h4>
                    <div style="display: grid; grid-template-columns: 1fr; gap: 0.875rem;">
                        <div>
                            <label style="display: block; font-size: 0.65rem; font-weight: 600; color: #6b7280; text-transform: uppercase; margin-bottom: 0.25rem; letter-spacing: 0.5px;">Username</label>
                            <p id="detailUsername" style="font-size: 0.875rem; font-weight: 600; color: #111827; margin: 0;">-</p>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.65rem; font-weight: 600; color: #6b7280; text-transform: uppercase; margin-bottom: 0.25rem; letter-spacing: 0.5px;">Email</label>
                            <p id="detailEmail" style="font-size: 0.8125rem; color: #2563eb; margin: 0; word-break: break-all;">-</p>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.65rem; font-weight: 600; color: #6b7280; text-transform: uppercase; margin-bottom: 0.25rem; letter-spacing: 0.5px;">Phone</label>
                            <p id="detailPhone" style="font-size: 0.8125rem; color: #111827; margin: 0;">-</p>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.65rem; font-weight: 600; color: #6b7280; text-transform: uppercase; margin-bottom: 0.25rem; letter-spacing: 0.5px;">Birth Date</label>
                            <p id="detailBirthDate" style="font-size: 0.8125rem; color: #111827; margin: 0;">-</p>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.65rem; font-weight: 600; color: #6b7280; text-transform: uppercase; margin-bottom: 0.25rem; letter-spacing: 0.5px;">Role</label>
                            <span id="detailRole" style="display: inline-flex; align-items: center; padding: 0.25rem 0.625rem; border-radius: 0.375rem; font-size: 0.75rem; font-weight: 600; background-color: #dbeafe; color: #1e40af; border: 1px solid #93c5fd;">-</span>
                        </div>
                    </div>
                </div>

                {{-- Address Info --}}
                <div style="background-color: white; padding: 0.875rem; border-radius: 0.5rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                    <h4 style="font-size: 0.9375rem; font-weight: 600; color: #111827; margin: 0 0 0.75rem 0; display: flex; align-items: center; gap: 0.5rem; border-bottom: 2px solid #059669; padding-bottom: 0.5rem;">
                        <i class="fas fa-map-marker-alt" style="color: #059669;"></i>
                        Address Info
                    </h4>
                    <div style="display: grid; grid-template-columns: 1fr; gap: 0.875rem;">
                        <div>
                            <label style="display: block; font-size: 0.65rem; font-weight: 600; color: #6b7280; text-transform: uppercase; margin-bottom: 0.25rem; letter-spacing: 0.5px;">Province</label>
                            <p id="detailProvince" style="font-size: 0.8125rem; color: #111827; margin: 0; font-weight: 500;">-</p>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.65rem; font-weight: 600; color: #6b7280; text-transform: uppercase; margin-bottom: 0.25rem; letter-spacing: 0.5px;">Regency</label>
                            <p id="detailRegency" style="font-size: 0.8125rem; color: #111827; margin: 0; font-weight: 500;">-</p>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.65rem; font-weight: 600; color: #6b7280; text-transform: uppercase; margin-bottom: 0.25rem; letter-spacing: 0.5px;">District</label>
                            <p id="detailDistrict" style="font-size: 0.8125rem; color: #111827; margin: 0; font-weight: 500;">-</p>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.65rem; font-weight: 600; color: #6b7280; text-transform: uppercase; margin-bottom: 0.25rem; letter-spacing: 0.5px;">Village</label>
                            <p id="detailVillage" style="font-size: 0.8125rem; color: #111827; margin: 0; font-weight: 500;">-</p>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.65rem; font-weight: 600; color: #6b7280; text-transform: uppercase; margin-bottom: 0.25rem; letter-spacing: 0.5px;">Full Address</label>
                            <p id="detailAddress" style="font-size: 0.8125rem; color: #111827; margin: 0; word-break: break-word; line-height: 1.4;">-</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT SIDE: 3/4 (KPI + Visits Table) --}}
            <div style="flex: 1; display: flex; flex-direction: column; overflow: hidden;">
                
                {{-- KPI Cards --}}
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; padding: 1rem; background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); border-bottom: 1px solid #e5e7eb; flex-shrink: 0;">
                    {{-- KPI 1: Total Visits --}}
                    <div style="background-color: white; padding: 1rem; border-radius: 0.5rem; border-left: 4px solid #2563eb; box-shadow: 0 1px 3px rgba(0,0,0,0.08); transition: all 0.3s;">
                        <p style="font-size: 0.65rem; color: #6b7280; text-transform: uppercase; font-weight: 700; margin: 0 0 0.375rem 0; letter-spacing: 0.5px;">
                            <i class="fas fa-calendar-check" style="margin-right: 0.375rem; color: #2563eb;"></i>Total Visits
                        </p>
                        <p id="kpiTotalVisits" style="font-size: 2rem; font-weight: 800; color: #111827; margin: 0;">0</p>
                    </div>
                    
                    {{-- KPI 2: Follow Up Count --}}
                    <div style="background-color: white; padding: 1rem; border-radius: 0.5rem; border-left: 4px solid #059669; box-shadow: 0 1px 3px rgba(0,0,0,0.08); transition: all 0.3s;">
                        <p style="font-size: 0.65rem; color: #6b7280; text-transform: uppercase; font-weight: 700; margin: 0 0 0.375rem 0; letter-spacing: 0.5px;">
                            <i class="fas fa-check-circle" style="margin-right: 0.375rem; color: #059669;"></i>Follow Up
                        </p>
                        <p id="kpiFollowUp" style="font-size: 2rem; font-weight: 800; color: #111827; margin: 0;">0</p>
                    </div>
                    
                    {{-- KPI 3: Last Visit --}}
                    <div style="background-color: white; padding: 1rem; border-radius: 0.5rem; border-left: 4px solid #7c3aed; box-shadow: 0 1px 3px rgba(0,0,0,0.08); transition: all 0.3s;">
                        <p style="font-size: 0.65rem; color: #6b7280; text-transform: uppercase; font-weight: 700; margin: 0 0 0.375rem 0; letter-spacing: 0.5px;">
                            <i class="fas fa-history" style="margin-right: 0.375rem; color: #7c3aed;"></i>Last Visit
                        </p>
                        <p id="kpiLastVisit" style="font-size: 1rem; font-weight: 600; color: #111827; margin: 0;">-</p>
                    </div>
                </div>

                {{-- Visits Table (Scrollable) --}}
                <div style="flex: 1; overflow-y: auto; padding: 1rem; background-color: white;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <h4 style="font-size: 0.9375rem; font-weight: 600; color: #111827; margin: 0;">
                            <i class="fas fa-map-marked-alt" style="margin-right: 0.5rem; color: #7c3aed;"></i>
                            Riwayat Kunjungan
                        </h4>
                    </div>

                    <div id="visitsContainer" style="background-color: #f9fafb; border-radius: 0.5rem; overflow: hidden; border: 1px solid #e5e7eb;">
                        {{-- Visits will be loaded here --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Footer --}}
        <div style="display: flex; justify-content: flex-end; padding: 1rem 1.25rem; border-top: 1px solid #e5e7eb; background-color: #f9fafb; flex-shrink: 0; border-radius: 0 0 0.5rem 0.5rem;">
            <button onclick="closeSalesDetailModal()" style="padding: 0.625rem 1.5rem; background-color: #6b7280; color: white; border: none; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 600; cursor: pointer; transition: all 0.15s; box-shadow: 0 1px 2px rgba(0,0,0,0.05);" onmouseover="this.style.backgroundColor='#5a6470'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.1)'" onmouseout="this.style.backgroundColor='#6b7280'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.05)'">
                <i class="fas fa-times" style="margin-right: 0.375rem;"></i>Tutup
            </button>
        </div>
    </div>
</div>

<script>
// ========== SHOW DETAIL MODAL ==========
function showSalesDetail(userId) {
    console.log('Opening detail for user:', userId);
    const modal = document.getElementById('salesDetailModal');
    const visitsContainer = document.getElementById('visitsContainer');
    
    // Show loading
    modal.style.display = 'flex';
    visitsContainer.innerHTML = `
        <div style="text-align: center; padding: 3rem 2rem; color: #6b7280;">
            <i class="fas fa-spinner fa-spin" style="font-size: 2.5rem; margin-bottom: 0.75rem;"></i>
            <p style="margin: 0; font-size: 0.9375rem;">Loading...</p>
        </div>
    `;
    
    // Fetch data
    fetch(`/marketing/sales/${userId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success && data.user) {
            // ===== FILL PERSONAL INFO =====
            document.getElementById('detailUsername').textContent = data.user.username || '-';
            document.getElementById('detailEmail').textContent = data.user.email || '-';
            document.getElementById('detailPhone').textContent = data.user.phone || '-';
            document.getElementById('detailBirthDate').textContent = data.user.birth_date || '-';
            document.getElementById('detailRole').textContent = data.user.role || '-';
            
            // ===== FILL ADDRESS INFO =====
            const province = data.user.province || '-';
            const regency = data.user.regency || '-';
            const district = data.user.district || '-';
            const village = data.user.village || '-';
            
            console.log('Setting address data:', {province, regency, district, village});
            
            document.getElementById('detailProvince').textContent = province;
            document.getElementById('detailRegency').textContent = regency;
            document.getElementById('detailDistrict').textContent = district;
            document.getElementById('detailVillage').textContent = village;
            document.getElementById('detailAddress').textContent = data.user.address || '-';
            
            // ===== FILL VISITS & KPI =====
            const visits = data.visits || [];
            
            // KPI Updates
            document.getElementById('kpiTotalVisits').textContent = visits.length;
            
            const followUpCount = visits.filter(v => v.is_follow_up === 'Ya').length;
            document.getElementById('kpiFollowUp').textContent = followUpCount;
            
            if (visits.length > 0) {
                const lastVisit = visits[0]?.visit_date || '-';
                document.getElementById('kpiLastVisit').textContent = lastVisit;
            } else {
                document.getElementById('kpiLastVisit').textContent = '-';
            }
            
            // ===== VISITS TABLE =====
            if (visits.length === 0) {
                visitsContainer.innerHTML = `
                    <div style="text-align: center; padding: 3rem 2rem; color: #9ca3af;">
                        <i class="fas fa-inbox" style="font-size: 2.5rem; margin-bottom: 0.75rem;"></i>
                        <p style="margin: 0; font-size: 0.9375rem; font-weight: 500;">Tidak ada riwayat kunjungan</p>
                    </div>
                `;
            } else {
                let visitsHTML = '<div style="overflow-x: auto;"><table style="width: 100%; font-size: 0.8125rem; border-collapse: collapse;">';
                visitsHTML += `
                    <thead>
                        <tr style="background-color: #f3f4f6; border-bottom: 2px solid #e5e7eb; position: sticky; top: 0; z-index: 10;">
                            <th style="padding: 0.75rem 0.875rem; text-align: left; font-weight: 700; color: #374151; white-space: nowrap;">Tanggal</th>
                            <th style="padding: 0.75rem 0.875rem; text-align: left; font-weight: 700; color: #374151; white-space: nowrap;">Perusahaan</th>
                            <th style="padding: 0.75rem 0.875rem; text-align: left; font-weight: 700; color: #374151; white-space: nowrap;">PIC</th>
                            <th style="padding: 0.75rem 0.875rem; text-align: left; font-weight: 700; color: #374151; white-space: nowrap;">Lokasi</th>
                            <th style="padding: 0.75rem 0.875rem; text-align: left; font-weight: 700; color: #374151; min-width: 150px;">Tujuan</th>
                            <th style="padding: 0.75rem 0.875rem; text-align: center; font-weight: 700; color: #374151; white-space: nowrap;">Follow Up</th>
                        </tr>
                    </thead>
                    <tbody>
                `;
                
                visits.forEach((visit, index) => {
                    const rowBg = index % 2 === 0 ? '#ffffff' : '#f9fafb';
                    const followUpBg = visit.is_follow_up === 'Ya' ? '#d1fae5' : '#fee2e2';
                    const followUpColor = visit.is_follow_up === 'Ya' ? '#065f46' : '#991b1b';
                    
                    visitsHTML += `
                        <tr style="background-color: ${rowBg}; border-bottom: 1px solid #e5e7eb; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#eff6ff'" onmouseout="this.style.backgroundColor='${rowBg}'">
                            <td style="padding: 0.75rem 0.875rem; color: #374151; font-weight: 500;">${visit.visit_date}</td>
                            <td style="padding: 0.75rem 0.875rem; color: #374151; white-space: nowrap;">${visit.company_name}</td>
                            <td style="padding: 0.75rem 0.875rem; color: #374151; white-space: nowrap;">${visit.pic_name}</td>
                            <td style="padding: 0.75rem 0.875rem; color: #374151; font-size: 0.75rem; max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${visit.location}">${visit.location}</td>
                            <td style="padding: 0.75rem 0.875rem; color: #374151; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${visit.visit_purpose}">${visit.visit_purpose}</td>
                            <td style="padding: 0.75rem 0.875rem; text-align: center;">
                                <span style="background-color: ${followUpBg}; color: ${followUpColor}; padding: 0.375rem 0.75rem; border-radius: 0.375rem; font-weight: 600; font-size: 0.75rem; display: inline-block;">
                                    ${visit.is_follow_up}
                                </span>
                            </td>
                        </tr>
                    `;
                });
                
                visitsHTML += '</tbody></table></div>';
                visitsContainer.innerHTML = visitsHTML;
            }
            
            console.log('Detail modal updated successfully');
        } else {
            console.error('Success is false or user data missing');
            visitsContainer.innerHTML = `
                <div style="text-align: center; padding: 3rem 2rem; color: #dc2626; background-color: #fee2e2; border-radius: 0.5rem;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 2.5rem; margin-bottom: 0.75rem;"></i>
                    <p style="margin: 0; font-size: 0.9375rem; font-weight: 500;">Error: ${data.error || 'Data tidak valid'}</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        visitsContainer.innerHTML = `
            <div style="text-align: center; padding: 3rem 2rem; color: #dc2626; background-color: #fee2e2; border-radius: 0.5rem;">
                <i class="fas fa-exclamation-triangle" style="font-size: 2.5rem; margin-bottom: 0.75rem;"></i>
                <p style="margin: 0; font-size: 0.9375rem; font-weight: 500;">Error: ${error.message}</p>
            </div>
        `;
    });
}

// Close modal
function closeSalesDetailModal() {
    document.getElementById('salesDetailModal').style.display = 'none';
}

// Close when clicking outside
document.getElementById('salesDetailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeSalesDetailModal();
    }
});
</script>

<style>
@keyframes modalSlideIn {
    from { 
        opacity: 0; 
        transform: translateY(-20px) scale(0.95); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0) scale(1); 
    }
}

@keyframes fadeIn { 
    from { opacity: 0; } 
    to { opacity: 1; } 
}

.animate-modal-in { 
    animation: modalSlideIn 0.25s ease-out; 
}

.fade-in { 
    animation: fadeIn 0.3s ease-in; 
}

/* Scrollbar styling */
#salesDetailModal div[style*="overflow-y: auto"] {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 #f1f5f9;
}

#salesDetailModal div[style*="overflow-y: auto"]::-webkit-scrollbar {
    width: 6px;
}

#salesDetailModal div[style*="overflow-y: auto"]::-webkit-scrollbar-track {
    background: #f1f5f9;
}

#salesDetailModal div[style*="overflow-y: auto"]::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

#salesDetailModal div[style*="overflow-y: auto"]::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Responsive improvements */
@media (max-width: 1280px) {
    #salesDetailModal > div {
        width: 98%;
        height: 95vh;
    }
}

@media (max-width: 1024px) {
    #salesDetailModal > div {
        width: 98%;
        height: 95vh;
    }
    
    #salesDetailModal > div > div:last-of-type > div:nth-child(2) {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}

@media (max-width: 768px) {
    #salesDetailModal > div {
        width: 99%;
        height: 98vh;
        flex-direction: column;
    }
    
    #salesDetailModal > div > div:nth-child(2) {
        width: 100% !important;
        border-right: none !important;
        border-bottom: 1px solid #e5e7eb;
        max-height: 40%;
    }
    
    #salesDetailModal > div > div:nth-child(3) {
        flex: 1 !important;
        width: 100% !important;
    }
    
    #salesDetailModal > div > div:nth-child(3) > div:first-child {
        grid-template-columns: 1fr !important;
    }
}

@media (max-width: 640px) {
    #salesDetailModal > div {
        width: 100%;
        height: 100vh;
        border-radius: 0;
    }
    
    #salesDetailModal > div > div:nth-child(2) {
        width: 100% !important;
        max-height: 50%;
    }
}
</style>