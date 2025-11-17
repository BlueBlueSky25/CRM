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
    <div style="background-color: white; border-radius: 0.5rem; width: 90%; max-width: 800px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
        {{-- Modal Header --}}
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.25rem; background: linear-gradient(to right, #2563eb, #3b82f6);">
            <h3 style="font-size: 1.125rem; font-weight: 600; color: white; margin: 0;">
                <i class="fas fa-user-tie" style="margin-right: 0.5rem;"></i>
                Detail Sales User
            </h3>
            <button onclick="closeSalesDetailModal()" style="color: white; background: transparent; border: none; font-size: 1.5rem; cursor: pointer; padding: 0; line-height: 1; opacity: 0.9; transition: opacity 0.15s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.9'">
                <i class="fas fa-times"></i>
            </button>
        </div>

        {{-- Modal Body --}}
        <div style="padding: 1rem;">
            {{-- Personal Info --}}
            <div style="background-color: #f9fafb; padding: 0.875rem; border-radius: 0.375rem; margin-bottom: 1rem;">
                <h4 style="font-size: 0.9375rem; font-weight: 600; color: #111827; margin: 0 0 0.75rem 0; display: flex; align-items: center; gap: 0.375rem;">
                    <i class="fas fa-id-card" style="color: #2563eb;"></i>
                    Personal Information
                </h4>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem;">
                    <div>
                        <label style="display: block; font-size: 0.6875rem; font-weight: 500; color: #6b7280; text-transform: uppercase; margin-bottom: 0.125rem;">Username</label>
                        <p id="detailUsername" style="font-size: 0.8125rem; font-weight: 600; color: #111827; margin: 0;">-</p>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.6875rem; font-weight: 500; color: #6b7280; text-transform: uppercase; margin-bottom: 0.125rem;">Email</label>
                        <p id="detailEmail" style="font-size: 0.8125rem; color: #111827; margin: 0;">-</p>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.6875rem; font-weight: 500; color: #6b7280; text-transform: uppercase; margin-bottom: 0.125rem;">Phone</label>
                        <p id="detailPhone" style="font-size: 0.8125rem; color: #111827; margin: 0;">-</p>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.6875rem; font-weight: 500; color: #6b7280; text-transform: uppercase; margin-bottom: 0.125rem;">Birth Date</label>
                        <p id="detailBirthDate" style="font-size: 0.8125rem; color: #111827; margin: 0;">-</p>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.6875rem; font-weight: 500; color: #6b7280; text-transform: uppercase; margin-bottom: 0.125rem;">Role</label>
                        <span id="detailRole" style="display: inline-flex; align-items: center; padding: 0.125rem 0.5rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 500; background-color: #dbeafe; color: #1e40af;">-</span>
                    </div>
                </div>
            </div>

            {{-- Address Info --}}
            <div style="background-color: #f9fafb; padding: 0.875rem; border-radius: 0.375rem; margin-bottom: 1rem;">
                <h4 style="font-size: 0.9375rem; font-weight: 600; color: #111827; margin: 0 0 0.75rem 0; display: flex; align-items: center; gap: 0.375rem;">
                    <i class="fas fa-map-marker-alt" style="color: #059669;"></i>
                    Address Information
                </h4>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem;">
                    <div>
                        <label style="display: block; font-size: 0.6875rem; font-weight: 500; color: #6b7280; text-transform: uppercase; margin-bottom: 0.125rem;">Province</label>
                        <p id="detailProvince" style="font-size: 0.8125rem; color: #111827; margin: 0; font-weight: 500;">-</p>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.6875rem; font-weight: 500; color: #6b7280; text-transform: uppercase; margin-bottom: 0.125rem;">Regency</label>
                        <p id="detailRegency" style="font-size: 0.8125rem; color: #111827; margin: 0; font-weight: 500;">-</p>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.6875rem; font-weight: 500; color: #6b7280; text-transform: uppercase; margin-bottom: 0.125rem;">District</label>
                        <p id="detailDistrict" style="font-size: 0.8125rem; color: #111827; margin: 0; font-weight: 500;">-</p>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.6875rem; font-weight: 500; color: #6b7280; text-transform: uppercase; margin-bottom: 0.125rem;">Village</label>
                        <p id="detailVillage" style="font-size: 0.8125rem; color: #111827; margin: 0; font-weight: 500;">-</p>
                    </div>
                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; font-size: 0.6875rem; font-weight: 500; color: #6b7280; text-transform: uppercase; margin-bottom: 0.125rem;">Full Address</label>
                        <p id="detailAddress" style="font-size: 0.8125rem; color: #111827; margin: 0;">-</p>
                    </div>
                </div>
            </div>

            {{-- Visit History Section --}}
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                    <h4 style="font-size: 0.9375rem; font-weight: 600; color: #111827; margin: 0;">
                        <i class="fas fa-map-marked-alt" style="margin-right: 0.375rem; color: #7c3aed;"></i>
                        Riwayat Kunjungan
                    </h4>
                    <span id="visitCount" style="font-size: 0.8125rem; color: #6b7280; background-color: #f3f4f6; padding: 0.25rem 0.625rem; border-radius: 9999px; font-weight: 500;">0 Visits</span>
                </div>

                <div id="visitsContainer" style="background-color: #f9fafb; border-radius: 0.375rem; overflow: hidden;">
                    {{-- Visits will be loaded here --}}
                </div>
            </div>
        </div>

        {{-- Modal Footer --}}
        <div style="display: flex; justify-content: flex-end; padding: 0.75rem 1rem; border-top: 1px solid #e5e7eb; background-color: #f9fafb;">
            <button onclick="closeSalesDetailModal()" style="padding: 0.5rem 1.25rem; background-color: #6b7280; color: white; border: none; border-radius: 0.375rem; font-size: 0.8125rem; font-weight: 500; cursor: pointer; transition: all 0.15s;">
                Tutup
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
        <div style="text-align: center; padding: 2rem; color: #6b7280;">
            <i class="fas fa-spinner fa-spin" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
            <p style="margin: 0;">Loading...</p>
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
            
            // ===== FILL ADDRESS INFO - PALING PENTING =====
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
            
            // ===== FILL VISITS =====
            const visits = data.visits || [];
            document.getElementById('visitCount').textContent = visits.length + ' Visits';
            
            if (visits.length === 0) {
                visitsContainer.innerHTML = `
                    <div style="text-align: center; padding: 2rem; color: #9ca3af;">
                        <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
                        <p style="margin: 0;">Tidak ada riwayat kunjungan</p>
                    </div>
                `;
            } else {
                let visitsHTML = '<div style="overflow-x: auto;"><table style="width: 100%; font-size: 0.75rem;">';
                visitsHTML += `
                    <thead>
                        <tr style="background-color: #f3f4f6; border-bottom: 1px solid #e5e7eb;">
                            <th style="padding: 0.625rem; text-align: left; font-weight: 600; color: #374151;">Tanggal</th>
                            <th style="padding: 0.625rem; text-align: left; font-weight: 600; color: #374151;">Perusahaan</th>
                            <th style="padding: 0.625rem; text-align: left; font-weight: 600; color: #374151;">PIC</th>
                            <th style="padding: 0.625rem; text-align: left; font-weight: 600; color: #374151;">Lokasi</th>
                            <th style="padding: 0.625rem; text-align: left; font-weight: 600; color: #374151;">Tujuan</th>
                            <th style="padding: 0.625rem; text-align: center; font-weight: 600; color: #374151;">Follow Up</th>
                        </tr>
                    </thead>
                    <tbody>
                `;
                
                visits.forEach((visit, index) => {
                    const rowBg = index % 2 === 0 ? '#ffffff' : '#f9fafb';
                    const followUpBg = visit.is_follow_up === 'Ya' ? '#dcfce7' : '#fee2e2';
                    const followUpColor = visit.is_follow_up === 'Ya' ? '#166534' : '#991b1b';
                    
                    visitsHTML += `
                        <tr style="background-color: ${rowBg}; border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 0.625rem; color: #374151;">${visit.visit_date}</td>
                            <td style="padding: 0.625rem; color: #374151;">${visit.company_name}</td>
                            <td style="padding: 0.625rem; color: #374151;">${visit.pic_name}</td>
                            <td style="padding: 0.625rem; color: #374151; font-size: 0.7rem;">${visit.location}</td>
                            <td style="padding: 0.625rem; color: #374151; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${visit.visit_purpose}">${visit.visit_purpose}</td>
                            <td style="padding: 0.625rem; text-align: center;">
                                <span style="background-color: ${followUpBg}; color: ${followUpColor}; padding: 0.25rem 0.625rem; border-radius: 9999px; font-weight: 500; font-size: 0.7rem;">
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
                <div style="text-align: center; padding: 2rem; color: #dc2626; background-color: #fee2e2; border-radius: 0.5rem;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
                    <p style="margin: 0;">Error: ${data.error || 'Data tidak valid'}</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        visitsContainer.innerHTML = `
            <div style="text-align: center; padding: 2rem; color: #dc2626; background-color: #fee2e2; border-radius: 0.5rem;">
                <i class="fas fa-exclamation-triangle" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
                <p style="margin: 0;">Error: ${error.message}</p>
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

.animate-modal-in { 
    animation: modalSlideIn 0.25s ease-out; 
}

.fade-in { 
    animation: fadeIn 0.3s ease-in; 
}

@keyframes fadeIn { 
    from { opacity: 0; } 
    to { opacity: 1; } 
}

/* Responsive improvements */
@media (max-width: 1024px) {
    #salesTable {
        font-size: 0.8125rem;
    }
}

@media (max-width: 768px) {
    #salesTable {
        font-size: 0.75rem;
    }
    
    #salesDetailModal > div {
        width: 95%;
        margin: 1rem;
    }
    
    #salesDetailModal > div > div:nth-child(2) > div > div {
        grid-template-columns: 1fr !important;
    }
}
</style>