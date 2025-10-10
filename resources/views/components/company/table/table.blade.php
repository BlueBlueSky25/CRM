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
        <table id="companyTable" class="w-full">
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
                @forelse($companies as $index => $company)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $companies->firstItem() + $index }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $company->company_name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $company->companyType->type_name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $company->tier ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $company->description ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-3 py-1 rounded-full text-xs font-medium 
                            {{ $company->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($company->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            @if(auth()->user()->canAccess($currentMenuId, 'edit'))
                            <button 
                                onclick="openEditCompanyModal('{{ $company->company_id }}', '{{ addslashes($company->company_name) }}', '{{ $company->company_type_id }}', '{{ $company->tier }}', '{{ addslashes($company->description ?? '') }}', '{{ $company->status }}')" 
                                class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 flex items-center" 
                                title="Edit Perusahaan">
                                <i class="fas fa-edit"></i>
                            </button>
                            @endif

                            @if(auth()->user()->canAccess($currentMenuId, 'delete'))
                            <form action="{{ route('company.destroy', $company->company_id) }}" method="POST" class="inline">
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
                @empty
                <tr>
                    <td colspan="7" class="text-center py-6 text-gray-500">Tidak ada data perusahaan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>



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