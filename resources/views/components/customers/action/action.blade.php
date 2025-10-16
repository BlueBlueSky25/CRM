<!-- Quick Actions -->
<div class="fade-in">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Quick Actions</h3>
            <div class="flex gap-3">
                <!-- Import -->
                <button onclick="openImportModal()"
                    class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-upload text-gray-600"></i>
                    Import
                </button>

                <!-- Export -->
                <a href="{{ route('customers.export') }}"
                    class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-download text-gray-600"></i>
                    Export
                </a>

                <!-- Tambah Customer -->
                <button onclick="openModal('add')"
                    class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus"></i>
                    Tambah Customer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Customer -->
<div id="customerModal"
    class="modal hidden fixed inset-0 bg-black bg-opacity-40 items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto animate-fadeIn">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 id="modalTitle" class="text-xl font-semibold text-gray-900">Tambah Customer</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="customerForm" class="px-6 py-4">
            @csrf
            <input type="hidden" id="customerId">
            <input type="hidden" id="formMethod" value="POST">

            <!-- Customer Type -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Customer *</label>
                <div class="flex gap-4">
                    <label class="flex items-center">
                        <input type="radio" name="customerType" value="Personal" checked onchange="toggleCompanyFields()" class="mr-2">
                        <span>Personal</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="customerType" value="Company" onchange="toggleCompanyFields()" class="mr-2">
                        <span>Company</span>
                    </label>
                </div>
            </div>

            <!-- Nama -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <span id="nameLabel">Nama Lengkap</span> *
                </label>
                <input type="text" id="customerName" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <!-- Email & Phone -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" id="customerEmail" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Telepon *</label>
                    <input type="tel" id="customerPhone" name="phone" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <!-- Address Section -->
                <div class="mb-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt text-indigo-500 mr-2"></i>
                        Address Information
                    </h4>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi <span class="text-red-500">*</span></label>
                        <select id="create-province" name="province_id" class="cascade-province w-full border border-gray-300 rounded-lg py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province->id }}">{{ $province->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kabupaten/Kota -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten/Kota <span class="text-red-500">*</span></label>
                        <select id="create-regency" name="regency_id" class="cascade-regency w-full border border-gray-300 rounded-lg py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
                            <option value="">-- Pilih Kabupaten/Kota --</option>
                        </select>
                    </div>

                    <!-- Kecamatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan <span class="text-red-500">*</span></label>
                        <select id="create-district" name="district_id" class="cascade-district w-full border border-gray-300 rounded-lg py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
                            <option value="">-- Pilih Kecamatan --</option>
                        </select>
                    </div>

                    <!-- Kelurahan/Desa -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kelurahan/Desa <span class="text-red-500">*</span></label>
                        <select id="create-village" name="village_id" class="cascade-village w-full border border-gray-300 rounded-lg py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
                            <option value="">-- Pilih Kelurahan/Desa --</option>
                        </select>
                    </div>
        

                    <!-- Detail Alamat (full width) -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Detail Alamat</label>
                        <div class="relative">
                            <i class="fas fa-home absolute left-3 top-3 text-gray-400"></i>
                            <textarea id="customerAddress" name="address" rows="3" placeholder="Contoh: Jl. Merdeka No. 123, RT 01/RW 02" 
                                    class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-none"></textarea>
                        </div>
                        <small class="text-gray-500">Isi dengan detail alamat seperti nama jalan, nomor rumah, RT/RW</small>
                    </div>
                </div>

            <!-- Company Fields -->
            <div id="companyFields" class="hidden">
                <div class="border-t border-gray-200 pt-4 mb-4">
                    <h4 class="font-medium text-gray-900 mb-3">Informasi Contact Person</h4>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Contact Person</label>
                        <input type="text" id="contactPersonName" name="contact_person_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Contact Person</label>
                            <input type="email" id="contactPersonEmail" name="contact_person_email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Telepon Contact Person</label>
                            <input type="tel" id="contactPersonPhone" name="contact_person_phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status & Source -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select id="customerStatus" name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="Lead">Lead</option>
                        <option value="Prospect">Prospect</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Source *</label>
                    <select id="customerSource" name="source" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="Website">Website</option>
                        <option value="Referral">Referral</option>
                        <option value="Ads">Ads</option>
                        <option value="Walk-in">Walk-in</option>
                        <option value="Social Media">Social Media</option>
                    </select>
                </div>
            </div>

            <!-- PIC -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Assigned PIC *</label>
                <select id="customerPIC" name="pic" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="John Doe">John Doe</option>
                    <option value="Jane Smith">Jane Smith</option>
                    <option value="Michael Johnson">Michael Johnson</option>
                    <option value="Sarah Williams">Sarah Williams</option>
                </select>
            </div>

            <!-- Notes -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea id="customerNotes" name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Import -->
<div id="importModal"
    class="modal hidden fixed inset-0 bg-black bg-opacity-40 items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 animate-fadeIn">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Import Customer</h2>
            <button onclick="closeImportModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>
        <form id="importForm" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Upload File (CSV/Excel)</label>
                <input type="file" name="file" accept=".csv,.xlsx" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <p class="text-xs text-gray-500 mt-2">Format: Nama, Tipe, Email, Telepon, Alamat, Status, Source, PIC</p>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeImportModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-upload mr-2"></i>Upload
                </button>
            </div>
        </form>
    </div>
</div>

<!-- JS -->
<script>
function openModal(mode) {
    const modal = document.getElementById('customerModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.classList.add('overflow-hidden');
    document.getElementById('modalTitle').textContent = mode === 'add' ? 'Tambah Customer' : 'Edit Customer';
}

function closeModal() {
    const modal = document.getElementById('customerModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.classList.remove('overflow-hidden');
}

function openImportModal() {
    const modal = document.getElementById('importModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.classList.add('overflow-hidden');
}

function closeImportModal() {
    const modal = document.getElementById('importModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.classList.remove('overflow-hidden');
}

function toggleCompanyFields() {
    const type = document.querySelector('input[name="customerType"]:checked').value;
    const companyFields = document.getElementById('companyFields');
    const nameLabel = document.getElementById('nameLabel');
    companyFields.classList.toggle('hidden', type === 'Personal');
    nameLabel.textContent = type === 'Company' ? 'Nama Perusahaan' : 'Nama Lengkap';
}
</script>

<!-- Optional Fade Animation -->
<style>
@keyframes fadeIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
.animate-fadeIn { animation: fadeIn 0.2s ease-out; }
</style>
