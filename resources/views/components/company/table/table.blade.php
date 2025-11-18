@props(['companies', 'currentMenuId'])

<div class="fade-in" style="margin: 0; padding: 0;">
    <!-- Table ONLY -->
    <div class="overflow-x-auto" style="margin: 0; padding: 0;">
        <table id="companyTable" class="w-full" style="margin: 0; border-collapse: collapse;">
            <thead style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                <tr>
                    <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 0.7rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;">No</th>
                    <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 0.7rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;">Nama Perusahaan</th>
                    <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 0.7rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;">Jenis</th>
                    <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 0.7rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;">Tier</th>
                    <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 0.7rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;">Deskripsi</th>
                    <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 0.7rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;">Status</th>
                    <th style="padding: 0.5rem 0.75rem; text-align: right; font-size: 0.7rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;">Aksi</th>
                </tr>
            </thead>
            <tbody style="background-color: #ffffff; border-top: 1px solid #e5e7eb;">
                @forelse($companies as $index => $company)
                <tr style="border-bottom: 1px solid #e5e7eb; transition: background-color 0.15s;" onmouseover="this.style.backgroundColor='#f9fafb'" onmouseout="this.style.backgroundColor='#ffffff'">
                    <td style="padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #111827; white-space: nowrap;">
                        <span style="font-weight: 500;">{{ $companies->firstItem() + $index }}</span>
                    </td>
                    <td style="padding: 0.5rem 0.75rem; font-size: 0.8125rem; font-weight: 500; color: #111827;">
                        {{ $company->company_name }}
                    </td>
                    <td style="padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #111827;">
                        {{ $company->companyType->type_name ?? '-' }}
                    </td>
                    <td style="padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #111827;">
                        {{ $company->tier ?? '-' }}
                    </td>
                    <td style="padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #111827;">
                        {{ $company->description ?? '-' }}
                    </td>
                    <td style="padding: 0.5rem 0.75rem; white-space: nowrap;">
                        <span style="display: inline-flex; align-items: center; padding: 0.125rem 0.5rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 500; {{ $company->status == 'active' ? 'background-color: #d1fae5; color: #065f46;' : 'background-color: #fee2e2; color: #991b1b;' }}">
                            {{ ucfirst($company->status) }}
                        </span>
                    </td>
                    <td style="padding: 0.5rem 0.75rem; font-size: 0.8125rem; font-weight: 500; text-align: right; white-space: nowrap;">
                        <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.375rem;">
                            {{-- Show Detail Button --}}
                            @if(auth()->user()->canAccess($currentMenuId, 'view'))
                            <button 
                                onclick="showCompanyDetail('{{ $company->company_id }}')" 
                                style="color: #059669; background: transparent; border: none; padding: 0.375rem; border-radius: 0.375rem; cursor: pointer; transition: all 0.15s; font-size: 0.875rem;"
                                onmouseover="this.style.backgroundColor='#d1fae5'; this.style.color='#047857';"
                                onmouseout="this.style.backgroundColor='transparent'; this.style.color='#059669';"
                                title="Show Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            @endif

                            {{-- Edit Button --}}
                            @if(auth()->user()->canAccess($currentMenuId, 'edit'))
                            <button 
                                onclick="openEditCompanyModal('{{ $company->company_id }}', '{{ addslashes($company->company_name) }}', '{{ $company->company_type_id }}', '{{ $company->tier }}', '{{ addslashes($company->description ?? '') }}', '{{ $company->status }}')" 
                                style="color: #2563eb; background: transparent; border: none; padding: 0.375rem; border-radius: 0.375rem; cursor: pointer; transition: all 0.15s; font-size: 0.875rem;"
                                onmouseover="this.style.backgroundColor='#dbeafe'; this.style.color='#1e40af';"
                                onmouseout="this.style.backgroundColor='transparent'; this.style.color='#2563eb';"
                                title="Edit Perusahaan">
                                <i class="fas fa-edit"></i>
                            </button>
                            @endif

                            {{-- Delete Button --}}
                            @if(auth()->user()->canAccess($currentMenuId, 'delete'))
                            <form action="{{ route('company.destroy', $company->company_id) }}" method="POST" style="display: inline; margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                    style="color: #dc2626; background: transparent; border: none; padding: 0.375rem; border-radius: 0.375rem; cursor: pointer; transition: all 0.15s; font-size: 0.875rem;"
                                    onmouseover="this.style.backgroundColor='#fee2e2'; this.style.color='#991b1b';"
                                    onmouseout="this.style.backgroundColor='transparent'; this.style.color='#dc2626';"
                                    title="Hapus Perusahaan" 
                                    onclick="return confirm('Yakin ingin menghapus perusahaan ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 3rem 1.5rem; text-align: center;">
                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                            <div style="width: 6rem; height: 6rem; border-radius: 9999px; background-color: #f3f4f6; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                                <i class="fas fa-inbox" style="font-size: 3rem; color: #d1d5db;"></i>
                            </div>
                            <h3 style="font-size: 1.125rem; font-weight: 500; color: #111827; margin: 0 0 0.25rem 0;">Belum Ada Data</h3>
                            <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">Belum ada data perusahaan yang tersedia</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Company Detail Modal --}}
<div id="companyDetailModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background-color: white; border-radius: 0.5rem; width: 90%; max-width: 800px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
        {{-- Modal Header --}}
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.25rem; background: linear-gradient(to right, #4f46e5, #7c3aed);">
            <h3 style="font-size: 1.125rem; font-weight: 600; color: white; margin: 0;">
                <i class="fas fa-building" style="margin-right: 0.5rem;"></i>
                Detail Perusahaan
            </h3>
            <button onclick="closeCompanyDetailModal()" style="color: white; background: transparent; border: none; font-size: 1.5rem; cursor: pointer; padding: 0; line-height: 1; opacity: 0.9; transition: opacity 0.15s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.9'">
                <i class="fas fa-times"></i>
            </button>
        </div>

        {{-- Modal Body --}}
        <div style="padding: 1rem;">
            {{-- Company Info --}}
            <div style="background-color: #f9fafb; padding: 0.875rem; border-radius: 0.375rem; margin-bottom: 1rem;">
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem;">
                    <div>
                        <label style="display: block; font-size: 0.6875rem; font-weight: 500; color: #6b7280; text-transform: uppercase; margin-bottom: 0.125rem;">Nama Perusahaan</label>
                        <p id="detailCompanyName" style="font-size: 0.8125rem; font-weight: 600; color: #111827; margin: 0;">-</p>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.6875rem; font-weight: 500; color: #6b7280; text-transform: uppercase; margin-bottom: 0.125rem;">Jenis Perusahaan</label>
                        <p id="detailCompanyType" style="font-size: 0.8125rem; color: #111827; margin: 0;">-</p>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.6875rem; font-weight: 500; color: #6b7280; text-transform: uppercase; margin-bottom: 0.125rem;">Tier</label>
                        <p id="detailCompanyTier" style="font-size: 0.8125rem; color: #111827; margin: 0;">-</p>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.6875rem; font-weight: 500; color: #6b7280; text-transform: uppercase; margin-bottom: 0.125rem;">Status</label>
                        <span id="detailCompanyStatus" style="display: inline-flex; align-items: center; padding: 0.125rem 0.5rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 500;">-</span>
                    </div>
                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; font-size: 0.6875rem; font-weight: 500; color: #6b7280; text-transform: uppercase; margin-bottom: 0.125rem;">Deskripsi</label>
                        <p id="detailCompanyDescription" style="font-size: 0.8125rem; color: #111827; margin: 0;">-</p>
                    </div>
                </div>
            </div>

            {{-- PICs Section --}}
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                    <h4 style="font-size: 0.9375rem; font-weight: 600; color: #111827; margin: 0;">
                        <i class="fas fa-users" style="margin-right: 0.375rem; color: #2563eb;"></i>
                        Daftar PIC
                    </h4>
                    <span id="picCount" style="font-size: 0.8125rem; color: #6b7280; background-color: #f3f4f6; padding: 0.25rem 0.625rem; border-radius: 9999px; font-weight: 500;">0 PIC</span>
                </div>

                <div id="picsContainer">
                    {{-- PICs will be loaded here --}}
                </div>
            </div>
        </div>

        {{-- Modal Footer --}}
        <div style="display: flex; justify-content: flex-end; padding: 0.75rem 1rem; border-top: 1px solid #e5e7eb; background-color: #f9fafb;">
            <button onclick="closeCompanyDetailModal()" style="padding: 0.5rem 1.25rem; background-color: #6b7280; color: white; border: none; border-radius: 0.375rem; font-size: 0.8125rem; font-weight: 500; cursor: pointer; transition: all 0.15s;">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
// Show Company Detail Modal
function showCompanyDetail(companyId) {
    const modal = document.getElementById('companyDetailModal');
    modal.style.display = 'flex';
    
    // Show loading
    document.getElementById('picsContainer').innerHTML = `
        <div style="text-align: center; padding: 2rem; color: #6b7280;">
            <i class="fas fa-spinner fa-spin" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
            <p style="margin: 0;">Loading...</p>
        </div>
    `;
    
    // Fetch company detail
    fetch(`/company/${companyId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Fill company info
                document.getElementById('detailCompanyName').textContent = data.company.company_name;
                document.getElementById('detailCompanyType').textContent = data.company.company_type;
                document.getElementById('detailCompanyTier').textContent = data.company.tier;
                document.getElementById('detailCompanyDescription').textContent = data.company.description;
                
                // Status badge
                const statusBadge = document.getElementById('detailCompanyStatus');
                statusBadge.textContent = data.company.status;
                if (data.company.status.toLowerCase() === 'active') {
                    statusBadge.style.backgroundColor = '#d1fae5';
                    statusBadge.style.color = '#065f46';
                } else {
                    statusBadge.style.backgroundColor = '#fee2e2';
                    statusBadge.style.color = '#991b1b';
                }
                
                // Fill PICs
                const picsContainer = document.getElementById('picsContainer');
                const picCount = document.getElementById('picCount');
                
                if (data.pics.length > 0) {
                    picCount.textContent = `${data.pics.length} PIC`;
                    
                    picsContainer.innerHTML = data.pics.map(pic => `
                        <div style="border: 1px solid #e5e7eb; border-radius: 0.375rem; padding: 0.75rem; margin-bottom: 0.5rem; background-color: white; transition: all 0.15s;" onmouseover="this.style.borderColor='#2563eb'; this.style.backgroundColor='#eff6ff';" onmouseout="this.style.borderColor='#e5e7eb'; this.style.backgroundColor='white';">
                            <div style="display: flex; align-items: start; gap: 0.75rem;">
                                <div style="flex-shrink: 0; width: 2.5rem; height: 2.5rem; border-radius: 9999px; background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 1rem;">
                                    ${pic.pic_name.charAt(0).toUpperCase()}
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <h5 style="font-size: 0.875rem; font-weight: 600; color: #111827; margin: 0 0 0.125rem 0;">${pic.pic_name}</h5>
                                    <p style="font-size: 0.75rem; color: #6b7280; margin: 0 0 0.375rem 0;">${pic.position}</p>
                                    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                                        <div style="display: flex; align-items: center; gap: 0.375rem;">
                                            <i class="fas fa-phone" style="color: #059669; font-size: 0.6875rem;"></i>
                                            <span style="font-size: 0.75rem; color: #374151;">${pic.phone}</span>
                                        </div>
                                        <div style="display: flex; align-items: center; gap: 0.375rem;">
                                            <i class="fas fa-envelope" style="color: #2563eb; font-size: 0.6875rem;"></i>
                                            <span style="font-size: 0.75rem; color: #374151;">${pic.email}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `).join('');
                } else {
                    picCount.textContent = '0 PIC';
                    picsContainer.innerHTML = `
                        <div style="text-align: center; padding: 3rem; background-color: #f9fafb; border-radius: 0.5rem; border: 2px dashed #e5e7eb;">
                            <div style="width: 4rem; height: 4rem; border-radius: 9999px; background-color: #f3f4f6; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                                <i class="fas fa-user-slash" style="font-size: 1.5rem; color: #d1d5db;"></i>
                            </div>
                            <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">Belum ada PIC untuk perusahaan ini</p>
                        </div>
                    `;
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('picsContainer').innerHTML = `
                <div style="text-align: center; padding: 2rem; color: #dc2626; background-color: #fee2e2; border-radius: 0.5rem;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
                    <p style="margin: 0;">Gagal memuat data</p>
                </div>
            `;
        });
}

// Close Modal
function closeCompanyDetailModal() {
    document.getElementById('companyDetailModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('companyDetailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCompanyDetailModal();
    }
});
</script>

<style>
/* Responsive improvements */
@media (max-width: 1024px) {
    #companyTable {
        font-size: 0.8125rem;
    }
    
    #companyTable th,
    #companyTable td {
        padding: 0.4rem 0.65rem;
    }
}

@media (max-width: 768px) {
    #companyTable {
        font-size: 0.75rem;
    }
    
    #companyTable th,
    #companyTable td {
        padding: 0.375rem 0.5rem;
    }
    
    #companyDetailModal > div {
        width: 95%;
        margin: 1rem;
    }
    
    #companyDetailModal > div > div:nth-child(2) > div:first-child > div {
        grid-template-columns: 1fr !important;
    }
}
</style>