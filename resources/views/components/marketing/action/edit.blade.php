<!-- Modal Edit User -->
<div id="editUserModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-hidden animate-modal-in">
        <!-- Header -->
        <div style="background: linear-gradient(to right, #4f46e5, #7c3aed); padding: 1rem 1.25rem;">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-semibold text-white">Edit User</h3>
                    <p class="text-xs text-indigo-100 mt-0.5">Perbarui data user di formulir berikut</p>
                </div>
                <button onclick="closeEditSalesModal()" 
                    class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Body -->
        <div class="overflow-y-auto max-h-[calc(90vh-120px)]" style="background-color: #f3f4f6; padding: 1rem;">
            <form id="editUserForm" action="#" method="POST" style="display: flex; flex-direction: column; gap: 0.75rem;">
                @csrf
                @method('PUT')
                <input type="hidden" id="editUserId" name="user_id">
                
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem;">
                    <!-- Username -->
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                            Username <span style="color: #ef4444;">*</span>
                        </label>
                        <div style="position: relative;">
                            <i class="fas fa-user" style="position: absolute; left: 0.625rem; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 0.75rem;"></i>
                            <input type="text" 
                                id="editUsername"
                                name="username" 
                                style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem 0.5rem 2rem; font-size: 0.875rem;" 
                                placeholder="Masukkan username"
                                required>
                        </div>
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                            Email <span style="color: #ef4444;">*</span>
                        </label>
                        <div style="position: relative;">
                            <i class="fas fa-envelope" style="position: absolute; left: 0.625rem; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 0.75rem;"></i>
                            <input type="email" 
                                id="editEmail"
                                name="email" 
                                style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem 0.5rem 2rem; font-size: 0.875rem;" 
                                placeholder="email@example.com"
                                required>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                            No. Telepon
                        </label>
                        <div style="position: relative;">
                            <i class="fas fa-phone" style="position: absolute; left: 0.625rem; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 0.75rem;"></i>
                            <input type="text" 
                                id="editPhone"
                                name="phone" 
                                style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem 0.5rem 2rem; font-size: 0.875rem;"
                                placeholder="08xxxxxxxxxx">
                        </div>
                    </div>

                    <!-- Birth Date -->
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                            Tanggal Lahir
                        </label>
                        <div style="position: relative;">
                            <i class="fas fa-calendar" style="position: absolute; left: 0.625rem; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 0.75rem;"></i>
                            <input type="date" 
                                id="editBirthDate"
                                name="birth_date" 
                                style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem 0.5rem 2rem; font-size: 0.875rem;"
                                max="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                </div>

                <!-- Address Section -->
                <div style="background: linear-gradient(to bottom right, #eff6ff, #e0e7ff); border: 1px solid #c7d2fe; border-radius: 0.5rem; padding: 0.75rem;">
                    <h4 style="font-size: 0.875rem; font-weight: 600; color: #1f2937; margin-bottom: 0.75rem; display: flex; align-items: center;">
                        <i class="fas fa-map-marker-alt" style="color: #6366f1; margin-right: 0.5rem; font-size: 0.875rem;"></i>
                        Informasi Alamat
                    </h4>
                    
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem;">
                        <!-- Provinsi -->
                        <div>
                            <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                                Provinsi <span style="color: #ef4444;">*</span>
                            </label>
                            <select id="edit-province" name="province_id" 
                                    style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem;" 
                                    required>
                                <option value="">-- Pilih Provinsi --</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Kabupaten/Kota -->
                        <div>
                            <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                                Kabupaten/Kota <span style="color: #ef4444;">*</span>
                            </label>
                            <select id="edit-regency" name="regency_id" 
                                    style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem;" 
                                    required disabled>
                                <option value="">-- Pilih Kabupaten/Kota --</option>
                            </select>
                        </div>

                        <!-- Kecamatan -->
                        <div>
                            <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                                Kecamatan <span style="color: #ef4444;">*</span>
                            </label>
                            <select id="edit-district" name="district_id" 
                                    style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem;" 
                                    required disabled>
                                <option value="">-- Pilih Kecamatan --</option>
                            </select>
                        </div>

                        <!-- Kelurahan/Desa -->
                        <div>
                            <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                                Kelurahan/Desa <span style="color: #ef4444;">*</span>
                            </label>
                            <select id="edit-village" name="village_id" 
                                    style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem;" 
                                    required disabled>
                                <option value="">-- Pilih Kelurahan/Desa --</option>
                            </select>
                        </div>

                        <!-- Detail Alamat (full width) -->
                        <div style="grid-column: span 2;">
                            <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                                Detail Alamat
                            </label>
                            <div style="position: relative;">
                                <i class="fas fa-home" style="position: absolute; left: 0.625rem; top: 0.625rem; color: #9ca3af; font-size: 0.75rem;"></i>
                                <textarea id="editAlamat" name="address" rows="2" 
                                        placeholder="Contoh: Jl. Merdeka No. 123, RT 01/RW 02" 
                                        style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem 0.5rem 2rem; font-size: 0.875rem; resize: none;"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tombol -->
                <div style="display: flex; justify-content: flex-end; gap: 0.5rem; padding-top: 0.75rem; border-top: 1px solid #e5e7eb; margin-top: 0.5rem;">
                    <button type="button" 
                            onclick="closeEditSalesModal()" 
                            style="background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 1.25rem; font-weight: 500; font-size: 0.75rem; cursor: pointer; transition: all 0.2s;">
                        <i class="fas fa-times" style="margin-right: 0.375rem;"></i>
                        Batal
                    </button>
                    <button type="submit" 
                            style="background-color: #4f46e5; color: white; border: none; border-radius: 0.5rem; padding: 0.5rem 1.25rem; font-weight: 500; font-size: 0.75rem; cursor: pointer; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); transition: all 0.2s;">
                        <i class="fas fa-save" style="margin-right: 0.375rem;"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Modal Animation */
    @keyframes modal-in {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(-20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .animate-modal-in {
        animation: modal-in 0.3s ease-out;
    }

    /* Custom Scrollbar for Modal */
    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }

    .overflow-y-auto::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Enhanced focus states */
    input:focus, textarea:focus, select:focus {
        outline: none;
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
    }

    select:disabled {
        background-color: #f3f4f6;
        cursor: not-allowed;
        opacity: 0.6;
    }

    /* Responsive adjustments */
    @media (max-width: 640px) {
        #editUserModal .bg-white {
            margin: 1rem;
            max-width: calc(100% - 2rem);
        }
    }
</style>

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