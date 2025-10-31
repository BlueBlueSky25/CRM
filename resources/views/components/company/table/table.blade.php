@props(['companies', 'currentMenuId'])

<div style="margin: 0; padding: 0;">
    <!-- Table ONLY (No Search Here!) -->
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
}
</style>