<!-- Modal Edit User - Sesuai dengan user-modal.js -->
<div id="editUserModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden animate-modal-in">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-white">Edit User</h2>
                    <p class="text-indigo-100 text-sm mt-1">Perbarui data user di formulir berikut</p>
                </div>
                <button type="button" onclick="closeEditSalesModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="overflow-y-auto max-h-[calc(90vh-140px)] p-6">
            <form id="editUserForm" action="#" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" id="editUserId" name="user_id">
                
                <!-- Username -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="editUsername" name="username"
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                            required>
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="email" id="editEmail" name="email"
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                            required>
                    </div>
                </div>

                <!-- No. Telepon -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                    <div class="relative">
                        <i class="fas fa-phone absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="editPhone" name="phone"
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    </div>
                </div>

                <!-- Tanggal Lahir -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                    <div class="relative">
                        <i class="fas fa-calendar absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="date" id="editBirthDate" name="birth_date"
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    </div>
                </div>

                <!-- Detail Alamat -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Detail Alamat</label>
                    <div class="relative">
                        <i class="fas fa-home absolute left-3 top-3 text-gray-400"></i>
                        <textarea id="editAlamat" name="address" rows="3" placeholder="Contoh: Jl. Merdeka No. 123, RT 01/RW 02" 
                                class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-none"></textarea>
                    </div>
                    <small class="text-gray-500">Isi dengan detail alamat seperti nama jalan, nomor rumah, RT/RW</small>
                </div>

                <!-- Address Section -->
                <div class="mb-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center border-b pb-2">
                        <i class="fas fa-map-marker-alt text-indigo-500 mr-2"></i>
                        Informasi Wilayah
                    </h4>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        <!-- Provinsi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi <span class="text-red-500">*</span></label>
                            <select id="edit-province" name="province_id" class="w-full border border-gray-300 rounded-lg px-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
                                <option value="">-- Pilih Provinsi --</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Kabupaten/Kota -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten/Kota <span class="text-red-500">*</span></label>
                            <select id="edit-regency" name="regency_id" class="w-full border border-gray-300 rounded-lg px-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
                                <option value="">-- Pilih Kabupaten/Kota --</option>
                            </select>
                        </div>

                        <!-- Kecamatan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan <span class="text-red-500">*</span></label>
                            <select id="edit-district" name="district_id" class="w-full border border-gray-300 rounded-lg px-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
                                <option value="">-- Pilih Kecamatan --</option>
                            </select>
                        </div>

                        <!-- Kelurahan/Desa -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kelurahan/Desa <span class="text-red-500">*</span></label>
                            <select id="edit-village" name="village_id" class="w-full border border-gray-300 rounded-lg px-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
                                <option value="">-- Pilih Kelurahan/Desa --</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 mt-6">
                    <button type="button" onclick="closeEditSalesModal()" class="px-6 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </button>
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
let editCascadeInstance = null;

function openEditSalesModal(user_id, username, email, phone, birth_date, address, provinceId, regencyId, districtId, villageId) {
    console.log('Opening edit modal with data:', {user_id, username, email, phone, birth_date, address, provinceId, regencyId, districtId, villageId});
    
    document.getElementById('editUserId').value = user_id;
    document.getElementById('editUsername').value = username;
    document.getElementById('editEmail').value = email;
    document.getElementById('editPhone').value = phone ?? '';
    document.getElementById('editBirthDate').value = birth_date ?? '';
    document.getElementById('editAlamat').value = address ?? '';
    document.getElementById('editUserModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Initialize cascade dengan data wilayah
    setTimeout(() => {
        initEditAddressCascade(provinceId, regencyId, districtId, villageId);
    }, 300);
}

function initEditAddressCascade(provinceId, regencyId, districtId, villageId) {
    console.log('Initializing EDIT cascade with data:', {provinceId, regencyId, districtId, villageId});
    
    // Destroy previous instance if exists
    if (editCascadeInstance) {
        console.log('Destroying previous cascade instance');
        editCascadeInstance.destroy();
    }
    
    // Create new instance
    editCascadeInstance = new AddressCascade({
        provinceId: 'edit-province',
        regencyId: 'edit-regency',
        districtId: 'edit-district',
        villageId: 'edit-village'
    });
    
    console.log('Cascade instance created');
    
    // Set values with cascade
    if (provinceId && provinceId !== 'null' && provinceId !== '') {
        console.log('Setting province:', provinceId);
        document.getElementById('edit-province').value = provinceId;
        document.getElementById('edit-province').dispatchEvent(new Event('change'));
        
        if (regencyId && regencyId !== 'null' && regencyId !== '') {
            setTimeout(() => {
                console.log('Setting regency:', regencyId);
                document.getElementById('edit-regency').value = regencyId;
                document.getElementById('edit-regency').dispatchEvent(new Event('change'));
                
                if (districtId && districtId !== 'null' && districtId !== '') {
                    setTimeout(() => {
                        console.log('Setting district:', districtId);
                        document.getElementById('edit-district').value = districtId;
                        document.getElementById('edit-district').dispatchEvent(new Event('change'));
                        
                        if (villageId && villageId !== 'null' && villageId !== '') {
                            setTimeout(() => {
                                console.log('Setting village:', villageId);
                                document.getElementById('edit-village').value = villageId;
                            }, 500);
                        }
                    }, 500);
                }
            }, 500);
        }
    }
}

function closeEditSalesModal() {
    console.log('Closing edit modal');
    document.getElementById('editUserModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    
    // Destroy cascade instance
    if (editCascadeInstance) {
        console.log('Destroying cascade on close');
        editCascadeInstance.destroy();
        editCascadeInstance = null;
    }
    
    // Reset form
    document.getElementById('editUserForm').reset();
    
    // Reset dropdowns
    document.getElementById('edit-regency').innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
    document.getElementById('edit-district').innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
    document.getElementById('edit-village').innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('editUserModal');
    if (e.target === modal) {
        closeEditSalesModal();
    }
});

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('editUserModal');
        if (modal && !modal.classList.contains('hidden')) {
            closeEditSalesModal();
        }
    }
});
</script>