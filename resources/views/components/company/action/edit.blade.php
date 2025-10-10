<!-- ===== MODAL EDIT COMPANY ===== -->
<div id="editCompanyModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg mx-4">
        <div class="flex justify-between items-center border-b p-4">
            <h2 class="text-lg font-semibold text-gray-700">Edit Company</h2>
            <button onclick="closeEditCompanyModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <!-- === FORM EDIT === -->
        <form id="editCompanyForm" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_company_id" name="company_id">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Perusahaan</label>
                <input type="text" id="edit_company_name" name="company_name"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis</label>
                <select id="edit_company_type_id" name="company_type_id"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500" required>
                    <option value="">Pilih Jenis Perusahaan</option>
                    @foreach($types as $type)
                        <option value="{{ $type->company_type_id }}">{{ $type->type_name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tier</label>
                <select id="edit_tier" name="tier"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    <option value="">Pilih Tier</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea id="edit_description" name="description" rows="3"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 resize-none"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="edit_status" name="status"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeEditCompanyModal()"
                    class="px-5 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium">Cancel</button>
                <button type="submit"
                    class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium shadow-md hover:shadow-lg">
                    <i class="fas fa-save mr-2"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditCompanyModal(companyId, companyName, companyTypeId, tier, description, status) {
    console.log('=== DEBUG OPEN EDIT MODAL ===');
    console.log({ companyId, companyName, companyTypeId, tier, description, status });

    // Set form action dinamis (biar bisa update langsung)
    const form = document.getElementById('editCompanyForm');
    form.action = `/company/${companyId}`; // pastiin route update-nya begini

    document.getElementById('edit_company_id').value = companyId || '';
    document.getElementById('edit_company_name').value = companyName || '';
    document.getElementById('edit_description').value = description || '';
    document.getElementById('edit_status').value = status || 'active';

    // Jenis
    const typeSelect = document.getElementById('edit_company_type_id');
    for (let opt of typeSelect.options) {
        opt.selected = (opt.value == companyTypeId);
    }

    // Tier
    const tierSelect = document.getElementById('edit_tier');
    for (let opt of tierSelect.options) {
        opt.selected = (opt.value.toLowerCase() === String(tier).toLowerCase());
    }

    // Show modal
    document.getElementById('editCompanyModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditCompanyModal() {
    document.getElementById('editCompanyModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('editCompanyForm').reset();
}
</script>
