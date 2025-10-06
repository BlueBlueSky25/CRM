@props(['companies','types'])
<div class="fade-in">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        No
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nama Perusahaan
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Jenis
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tier
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Deskripsi
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @foreach($companies as $index => $company)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $company->company_name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $company->companyType->type_name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $company->tier ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $company->description ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-3 py-1 rounded-full text-xs font-medium 
                            {{ $company->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($company->status ?? '-') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            @if(auth()->user()->canAccess($currentMenuId, 'edit'))
                                <button 
                                    onclick="openEditCompanyModal('{{ $company->company_id }}', '{{ $company->company_name }}', '{{ $company->company_type_id }}', '{{ $company->tier }}', '{{ $company->status }}')" 
                                    class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 flex items-center" 
                                    title="Edit Company">
                                    <i class="fas fa-edit"></i>
                                </button>
                            @endif

                            @if(auth()->user()->canAccess($currentMenuId, 'delete'))
                                <form action="{{ route('companies.destroy', $company->company_id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        class="text-red-600 hover:text-red-900 p-2 flex items-center" 
                                        title="Hapus Perusahaan" 
                                        onclick="return confirm('Yakin ingin menghapus perusahaan ini?')">
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


 <!-- Modal Edit Company -->
<div id="editCompanyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden animate-modal-in">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-white">Edit Company</h2>
                    <p class="text-indigo-100 text-sm mt-1">Perbarui data perusahaan di formulir berikut</p>
                </div>
                <button onclick="closeEditCompanyModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Body -->
        <div class="overflow-y-auto max-h-[calc(90vh-140px)] p-6">
            <form id="editCompanyForm" action="#" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_company_id" name="company_id">

                <!-- Nama Perusahaan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Perusahaan <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <i class="fas fa-building absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="edit_company_name" name="company_name"
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 
                                   focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                            required>
                    </div>
                </div>

                <!-- Jenis Perusahaan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Perusahaan</label>
                    <select id="edit_company_type_id" name="company_type_id"
                        class="w-full border border-gray-300 rounded-lg py-3 focus:ring-2 
                               focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        @foreach($types as $type)
                            <option value="{{ $type->company_type_id }}">{{ $type->type_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tier -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tier</label>
                    <input type="text" id="edit_tier" name="tier"
                        class="w-full border border-gray-300 rounded-lg px-3 py-3 
                               focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <div class="relative">
                        <i class="fas fa-align-left absolute left-3 top-4 text-gray-400"></i>
                        <textarea id="edit_description" name="description" rows="3"
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 
                                   focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none transition-all"></textarea>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="edit_status" name="status"
                        class="w-full border border-gray-300 rounded-lg py-3 focus:ring-2 
                               focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 mt-6">
                    <button type="button" onclick="closeEditCompanyModal()" 
                        class="px-6 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 
                               transition-colors font-medium">
                        Batal
                    </button>
                    <button type="submit" 
                        class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 
                               transition-all duration-200 transform hover:scale-105 font-medium shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script -->
<script>
function openEditCompanyModal(id, name, typeId, tier, description, status) {
    document.getElementById('edit_company_id').value = id;
    document.getElementById('edit_company_name').value = name;
    document.getElementById('edit_company_type_id').value = typeId ?? '';
    document.getElementById('edit_tier').value = tier ?? '';
    document.getElementById('edit_description').value = description ?? '';
    document.getElementById('edit_status').value = status ?? 'inactive';

    document.getElementById('editCompanyModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditCompanyModal() {
    document.getElementById('editCompanyModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('editCompanyForm').reset();
}
</script>