<!-- Modal Edit Company -->
<div id="editCompanyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden animate-modal-in">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-white">Edit Company</h2>
            <button onclick="closeEditCompanyModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <form id="editCompanyForm" action="#" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_company_id" name="company_id">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                <input type="text" id="edit_name" name="name" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Industry</label>
                <input type="text" id="edit_industry" name="industry" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                    <input type="text" id="edit_phone" name="phone" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="edit_email" name="email" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                <textarea id="edit_address" name="address" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 resize-none"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                <input type="text" id="edit_city" name="city" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeEditCompanyModal()" class="px-5 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium">Cancel</button>
                <button type="button" class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium shadow-md hover:shadow-lg">
                    <i class="fas fa-save mr-2"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditCompanyModal(id, name, industry, phone, email, address, city) {
        document.getElementById('edit_company_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_industry').value = industry;
        document.getElementById('edit_phone').value = phone;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_address').value = address;
        document.getElementById('edit_city').value = city;
        document.getElementById('editCompanyModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeEditCompanyModal() {
        document.getElementById('editCompanyModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('editCompanyForm').reset();
    }
</script>