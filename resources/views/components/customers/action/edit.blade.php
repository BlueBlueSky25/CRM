<!-- Modal Edit Customer -->
<div id="editCustomerModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden animate-modal-in">

        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-white">Edit Customer</h2>
                    <p class="text-indigo-100 text-sm mt-1">Perbarui data pelanggan di formulir berikut</p>
                </div>
                <button onclick="closeEditCustomerModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Body -->
        <div class="overflow-y-auto max-h-[calc(90vh-140px)] p-6">
            <form id="editCustomerForm" action="#" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_customer_id" name="customer_id">

                <!-- Nama Customer -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Customer <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="edit_customer_name" name="customer_name"
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 
                                   focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                            required>
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="email" id="edit_customer_email" name="email"
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 
                                   focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    </div>
                </div>

                <!-- Telepon -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Telepon</label>
                    <div class="relative">
                        <i class="fas fa-phone absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="edit_customer_phone" name="phone"
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 
                                   focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    </div>
                </div>

                <!-- Alamat -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                    <div class="relative">
                        <i class="fas fa-map-marker-alt absolute left-3 top-4 text-gray-400"></i>
                        <textarea id="edit_customer_address" name="address" rows="3"
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 
                                   focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none transition-all"></textarea>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="edit_customer_status" name="status"
                        class="w-full border border-gray-300 rounded-lg py-3 focus:ring-2 
                               focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 mt-6">
                    <button type="button" onclick="closeEditCustomerModal()" 
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
function openEditCustomerModal(id, name, email, phone, address, status) {
    document.getElementById('edit_customer_id').value = id;
    document.getElementById('edit_customer_name').value = name;
    document.getElementById('edit_customer_email').value = email ?? '';
    document.getElementById('edit_customer_phone').value = phone ?? '';
    document.getElementById('edit_customer_address').value = address ?? '';
    document.getElementById('edit_customer_status').value = status ?? 'inactive';

    document.getElementById('editCustomerModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditCustomerModal() {
    document.getElementById('editCustomerModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('editCustomerForm').reset();
}
</script>