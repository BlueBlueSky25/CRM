{{-- FILE: resources/views/components/company/table/table.blade.php --}}
@props(['companies','types'])

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden fade-in">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Company Management</h3>
            <p class="text-sm text-gray-600 mt-1">Kelola data perusahaan dan informasinya</p>
        </div>
        @if(auth()->user()->canAccess($currentMenuId, 'create'))
        <button onclick="openAddCompanyModal()" 
            class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
            <i class="fas fa-plus"></i>
            Tambah Perusahaan
        </button>
        @endif
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Nama Perusahaan</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Tier</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($companies as $index => $company)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $company->company_name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $company->companyType->type_name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $company->tier ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $company->description ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $company->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($company->status ?? '-') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            @if(auth()->user()->canAccess($currentMenuId, 'edit'))
                            <button onclick="openEditCompanyModal('{{ $company->company_id }}', '{{ $company->company_name }}', '{{ $company->company_type_id }}', '{{ $company->tier }}', '{{ $company->description }}', '{{ $company->status }}')" 
                                class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 flex items-center" title="Edit Company">
                                <i class="fas fa-edit"></i>
                            </button>
                            @endif
                            @if(auth()->user()->canAccess($currentMenuId, 'delete'))
                            <form action="{{ route('companies.destroy', $company->company_id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 p-2 flex items-center" title="Hapus Perusahaan" onclick="return confirm('Yakin ingin menghapus perusahaan ini?')">
                                    <i class="fas fa-trash"></i>
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
</div>

<!-- Modal Add Company -->
<div id="addCompanyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden animate-modal-in">
        <!-- Header -->
        <div style="background: linear-gradient(to right, #4f46e5, #7c3aed); padding: 1.25rem 1.5rem;">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-semibold text-white">Tambah Perusahaan Baru</h3>
                    <p class="text-sm text-indigo-100 mt-1">Lengkapi formulir berikut untuk menambahkan perusahaan</p>
                </div>
                <button onclick="closeAddCompanyModal()" 
                    class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Body -->
        <div class="overflow-y-auto max-h-[calc(90vh-140px)]" style="background-color: #f3f4f6; padding: 1.5rem;">
            <form id="addCompanyForm" action="{{ route('companies.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 1rem;">
                @csrf
                
                <!-- Nama -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Nama Perusahaan <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" 
                           name="company_name" 
                           style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.875rem;" 
                           required>
                </div>
                
                <!-- Jenis -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Jenis Perusahaan <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="company_type_id" 
                            style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.875rem;" 
                            required>
                        <option value="">-- Pilih Jenis --</option>
                        @foreach($types as $type)
                        <option value="{{ $type->company_type_id }}">{{ $type->type_name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Tier -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Tier
                    </label>
                    <input type="text" 
                           name="tier" 
                           style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.875rem;" 
                           placeholder="Contoh: A, B, C">
                </div>
                
                <!-- Deskripsi -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Deskripsi
                    </label>
                    <textarea name="description" 
                              rows="3" 
                              style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.875rem; resize: none;" 
                              placeholder="Tambahkan keterangan tentang perusahaan..."></textarea>
                </div>
                
                <!-- Status -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Status
                    </label>
                    <select name="status" 
                            style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.875rem;">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                
                <!-- Tombol -->
                <div style="display: flex; justify-content: flex-end; gap: 0.75rem; padding-top: 1rem; border-top: 1px solid #e5e7eb; margin-top: 1.5rem;">
                    <button type="button" 
                            onclick="closeAddCompanyModal()" 
                            style="background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.625rem 1.5rem; font-weight: 500; font-size: 0.875rem; cursor: pointer; transition: all 0.2s;">
                        Batal
                    </button>
                    <button type="submit" 
                            style="background-color: #4f46e5; color: white; border: none; border-radius: 0.5rem; padding: 0.625rem 1.5rem; font-weight: 500; font-size: 0.875rem; cursor: pointer; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); transition: all 0.2s;">
                        <i class="fas fa-save" style="margin-right: 0.5rem;"></i>
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAddCompanyModal() {
    document.getElementById('addCompanyModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAddCompanyModal() {
    document.getElementById('addCompanyModal').classList.add('hidden');
    document.getElementById('addCompanyForm').reset();
    document.body.style.overflow = 'auto';
}
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
</style>