<!-- Modal Tambah/Edit Customer -->
<div id="customerModal"
    class="modal hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[95vh] overflow-hidden animate-fadeIn">
        <!-- Modal Header - WARNA BARU PURPLE BLUE -->
        <div class="px-6 py-4 border-b border-gray-200" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #3b82f6 100%);">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-plus text-white text-lg"></i>
                    </div>
                    <h3 id="modalTitle" class="text-xl font-semibold text-white">Tambah Customer</h3>
                </div>
                <button id="closeModalBtn" class="text-white hover:text-gray-200 transition-colors p-2">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <form id="customerForm" class="overflow-y-auto max-h-[calc(95vh-140px)]">
            @csrf
            <input type="hidden" id="customerId">
            <input type="hidden" id="formMethod" value="POST">

            <div class="px-4 py-4 space-y-4">
                <!-- Customer Type Section -->
                <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                    <label class="block text-xs font-semibold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-users text-blue-500 mr-2 text-sm"></i>
                        Tipe Customer <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="flex gap-4">
                        <label class="flex items-center cursor-pointer group">
                            <input type="radio" name="customerType" value="Personal" checked 
                                class="mr-2 w-4 h-4 text-blue-600 focus:ring-2 focus:ring-blue-500">
                            <span class="text-xs font-medium text-gray-700 group-hover:text-blue-600 transition-colors">
                                <i class="fas fa-user mr-1"></i>Personal
                            </span>
                        </label>
                        <label class="flex items-center cursor-pointer group">
                            <input type="radio" name="customerType" value="Company" 
                                class="mr-2 w-4 h-4 text-blue-600 focus:ring-2 focus:ring-blue-500">
                            <span class="text-xs font-medium text-gray-700 group-hover:text-blue-600 transition-colors">
                                <i class="fas fa-building mr-1"></i>Company
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Basic Information -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-id-card text-blue-500 mr-2"></i>
                        Informasi Dasar
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <!-- Nama -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                <span id="nameLabel">Nama Lengkap</span> <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400 text-xs"></i>
                                </div>
                                <input type="text" id="customerName" name="name" required 
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    placeholder="Masukkan nama lengkap">
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400 text-xs"></i>
                                </div>
                                <input type="email" id="customerEmail" name="email" required 
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    placeholder="email@example.com">
                            </div>
                        </div>

                        <!-- Telepon -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                Telepon <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-phone text-gray-400 text-xs"></i>
                                </div>
                                <input type="tel" id="customerPhone" name="phone" required 
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    placeholder="08xxxxxxxxxx">
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-tag text-gray-400 text-xs"></i>
                                </div>
                                <select id="customerStatus" name="status" required 
                                    class="w-full pl-9 pr-8 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none bg-white">
                                    <option value="Lead">Lead</option>
                                    <option value="Prospect">Prospect</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Source -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                Source <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-bullseye text-gray-400 text-xs"></i>
                                </div>
                                <select id="customerSource" name="source" required 
                                    class="w-full pl-9 pr-8 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none bg-white">
                                    <option value="Website">Website</option>
                                    <option value="Referral">Referral</option>
                                    <option value="Ads">Ads</option>
                                    <option value="Walk-in">Walk-in</option>
                                    <option value="Social Media">Social Media</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <!-- PIC -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                Assigned PIC <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user-shield text-gray-400 text-xs"></i>
                                </div>
                                <select id="customerPIC" name="pic" required 
                                    class="w-full pl-9 pr-8 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none bg-white">
                                    <option value="John Doe">John Doe</option>
                                    <option value="Jane Smith">Jane Smith</option>
                                    <option value="Michael Johnson">Michael Johnson</option>
                                    <option value="Sarah Williams">Sarah Williams</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Section -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-3 border border-blue-200">
                    <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-map-marker-alt text-indigo-600 mr-2"></i>
                        Informasi Alamat
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <!-- Provinsi -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                Provinsi <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-map text-gray-400 text-xs"></i>
                                </div>
                                <select id="create-province" name="province_id" 
                                    class="cascade-province w-full pl-9 pr-8 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all appearance-none bg-white" 
                                    required>
                                    <option value="">-- Pilih Provinsi --</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Kabupaten/Kota -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                Kabupaten/Kota <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-city text-gray-400 text-xs"></i>
                                </div>
                                <select id="create-regency" name="regency_id" 
                                    class="cascade-regency w-full pl-9 pr-8 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all appearance-none bg-white" 
                                    required disabled>
                                    <option value="">-- Pilih Kabupaten/Kota --</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Kecamatan -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                Kecamatan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-map-signs text-gray-400 text-xs"></i>
                                </div>
                                <select id="create-district" name="district_id" 
                                    class="cascade-district w-full pl-9 pr-8 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all appearance-none bg-white" 
                                    required disabled>
                                    <option value="">-- Pilih Kecamatan --</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Kelurahan/Desa -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                Kelurahan/Desa <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-home text-gray-400 text-xs"></i>
                                </div>
                                <select id="create-village" name="village_id" 
                                    class="cascade-village w-full pl-9 pr-8 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all appearance-none bg-white" 
                                    required disabled>
                                    <option value="">-- Pilih Kelurahan/Desa --</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Alamat - Full Width -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                Detail Alamat
                            </label>
                            <div class="relative">
                                <div class="absolute top-2 left-3 pointer-events-none">
                                    <i class="fas fa-map-marked-alt text-gray-400 text-xs"></i>
                                </div>
                                <textarea id="customerAddress" name="address" rows="2" 
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-none" 
                                    placeholder="Contoh: Jl. Merdeka No. 123, RT 01/RW 02"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Company Fields -->
                <div id="companyFields" class="hidden bg-purple-50 rounded-lg p-3 border border-purple-200">
                    <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-user-tie text-purple-600 mr-2"></i>
                        Informasi Contact Person
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Nama Contact Person</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user-circle text-gray-400 text-xs"></i>
                                </div>
                                <input type="text" id="contactPersonName" name="contact_person_name" 
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
                                    placeholder="Nama contact person">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Email Contact Person</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400 text-xs"></i>
                                </div>
                                <input type="email" id="contactPersonEmail" name="contact_person_email" 
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
                                    placeholder="email@example.com">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Telepon Contact Person</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-phone text-gray-400 text-xs"></i>
                                </div>
                                <input type="tel" id="contactPersonPhone" name="contact_person_phone" 
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
                                    placeholder="08xxxxxxxxxx">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-cog text-blue-500 mr-2"></i>
                        Pengaturan Lainnya
                    </h4>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-sticky-note mr-1"></i>Notes
                        </label>
                        <textarea id="customerNotes" name="notes" rows="2" 
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none"
                            placeholder="Tambahkan catatan tambahan..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                <button type="button" id="cancelModalBtn" 
                    class="px-4 py-2 border border-gray-300 rounded-lg text-xs font-medium text-gray-700 hover:bg-gray-100 transition-colors flex items-center gap-2">
                    <i class="fas fa-times"></i>
                    Batal
                </button>
                <button type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg text-xs font-medium hover:bg-blue-700 transition-colors flex items-center gap-2 shadow-lg shadow-blue-500/30">
                    <i class="fas fa-save"></i>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Import - WARNA BARU PURPLE BLUE -->
<div id="importModal"
    class="modal hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md animate-fadeIn">
        <div class="px-6 py-4 border-b border-gray-200" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #3b82f6 100%);">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file-import text-white text-lg"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-white">Import Customer</h2>
                </div>
                <button id="closeImportModalBtn" class="text-white hover:text-gray-200 transition-colors p-2">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <form id="importForm" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    <i class="fas fa-file-excel mr-1 text-green-600"></i>
                    Upload File (CSV/Excel)
                </label>
                <div class="relative">
                    <input type="file" name="file" accept=".csv,.xlsx" required 
                        class="w-full border-2 border-dashed border-gray-300 rounded-lg px-4 py-6 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                </div>
                <div class="mt-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-xs text-blue-800 flex items-start">
                        <i class="fas fa-info-circle mr-2 mt-0.5"></i>
                        <span><strong>Format:</strong> Nama, Tipe, Email, Telepon, Alamat, Status, Source, PIC</span>
                    </p>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" id="cancelImportBtn" 
                    class="px-5 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors flex items-center gap-2">
                    <i class="fas fa-times"></i>
                    Batal
                </button>
                <button type="submit" 
                    class="px-5 py-2.5 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors flex items-center gap-2 shadow-lg shadow-green-500/30">
                    <i class="fas fa-upload"></i>
                    Upload
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Styles -->
<style>
@keyframes fadeIn { 
    from { 
        opacity: 0; 
        transform: scale(0.95) translateY(-10px); 
    } 
    to { 
        opacity: 1; 
        transform: scale(1) translateY(0); 
    } 
}

.animate-fadeIn { 
    animation: fadeIn 0.3s ease-out; 
}

/* Custom select dropdown arrow hide default */
select::-ms-expand {
    display: none;
}

/* Smooth transitions for all inputs */
input:focus, select:focus, textarea:focus {
    outline: none;
}

/* Disabled state styling */
select:disabled {
    background-color: #f3f4f6;
    cursor: not-allowed;
    opacity: 0.6;
}
</style>