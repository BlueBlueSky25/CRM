 <!-- Modal Edit Sales - Satu Tema Header Gradient -->
<div id="editSalesModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden animate-modal-in">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-white">Edit Sales</h2>
                    <p class="text-indigo-100 text-sm mt-1">Perbarui data sales di formulir berikut</p>
                </div>
                <button onclick="closeEditSalesModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>
        <!-- Modal Body -->
        <div class="overflow-y-auto max-h-[calc(90vh-140px)] p-6">
            <form id="editSalesForm" action="#" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_user_id" name="user_id">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="edit_username" name="username"
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                            required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="email" id="edit_email" name="email"
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                            required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                    <div class="relative">
                        <i class="fas fa-phone absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="edit_phone" name="phone"
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                    <div class="relative">
                        <i class="fas fa-calendar absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="date" id="edit_birth_date" name="birth_date"
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                            max="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi <span class="text-red-500">*</span></label>
                    <select id="province" name="province" class="w-full border border-gray-300 rounded-lg py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
                        <option value="">-- Pilih Provinsi --</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province->id }}">{{ $province->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten/Kota <span class="text-red-500">*</span></label>
                    <select id="regency" name="regency" class="w-full border border-gray-300 rounded-lg py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
                        <option value="">-- Pilih Kabupaten/Kota --</option>
                        
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan <span class="text-red-500">*</span></label>
                    <select id="district" name="district" class="w-full border border-gray-300 rounded-lg py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
                        <option value="">-- Pilih Kecamatan --</option>
                        
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kelurahan/Desa <span class="text-red-500">*</span></label>
                    <select id="village" name="village" class="w-full border border-gray-300 rounded-lg py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
                        <option value="">-- Pilih Kelurahan/Desa --</option>
                        
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                    <div class="relative">
                        <i class="fas fa-map-marker-alt absolute left-3 top-4 text-gray-400"></i>
                        <textarea id="edit_address" name="address" rows="3"
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none transition-all"></textarea>
                    </div>
                </div>
                
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 mt-6">
                    <button type="button" onclick="closeEditSalesModal()" class="px-6 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 transform hover:scale-105 font-medium shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openEditSalesModal(user_id, username, email, phone, birth_date, address) {
    document.getElementById('edit_user_id').value = user_id;
    document.getElementById('edit_username').value = username;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_phone').value = phone ?? '';
    document.getElementById('edit_birth_date').value = birth_date ?? '';
    document.getElementById('edit_address').value = address ?? '';
    document.getElementById('editSalesModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeEditSalesModal() {
    document.getElementById('editSalesModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('editSalesForm').reset();
}
</script>