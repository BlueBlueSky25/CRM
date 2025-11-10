@props(['currentMenuId', 'salesUsers', 'provinces', 'types' => []])

<!-- Tambah Kunjungan Modal -->
<div id="visitModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[95vh] overflow-hidden animate-fadeIn">
        
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #3b82f6 100%);">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-route text-white text-lg"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-white">Tambah Kunjungan Sales</h2>
                </div>
                <button onclick="closeVisitModal()" class="text-white hover:text-gray-200 transition-colors p-2">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <form action="{{ route('salesvisit.store') }}" method="POST" class="overflow-y-auto max-h-[calc(95vh-140px)]" id="visitForm">
            @csrf

            @php
                $userRole = strtolower(auth()->user()->role->role_name ?? '');
                $isSales = $userRole === 'sales';
            @endphp

            <div class="px-4 py-4 space-y-4">
                <!-- Basic Information -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-id-card text-blue-500 mr-2"></i>
                        Informasi Dasar
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <!-- Sales -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                Sales <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user-tie text-gray-400 text-xs"></i>
                                </div>
                                @if($isSales)
                                    <input type="text" 
                                        value="{{ auth()->user()->username }} - {{ auth()->user()->email }}"
                                        class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed"
                                        readonly>
                                    <input type="hidden" name="sales_id" value="{{ auth()->user()->user_id }}">
                                @else
                                    <select name="sales_id" id="visit-sales"
                                        class="w-full pl-9 pr-8 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none bg-white"
                                        required>
                                        <option value="">-- Pilih Sales --</option>
                                        @foreach($salesUsers as $sales)
                                            <option value="{{ $sales->user_id }}">{{ $sales->username }} - {{ $sales->email }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Customer Name -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                Customer Name <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400 text-xs"></i>
                                </div>
                                <input type="text" name="customer_name"
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    placeholder="Masukkan nama customer" required>
                            </div>
                        </div>

                        <!-- Company with Dropdown + Add New -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                Company
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                                    <i class="fas fa-building text-gray-400 text-xs"></i>
                                </div>
                                
                                <input type="hidden" name="company_id" id="create-company-id">
                                
                                <input type="text" 
                                    id="create-company-search" 
                                    placeholder="Ketik atau pilih company..."
                                    autocomplete="off"
                                    class="w-full pl-9 pr-20 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                
                                <div id="create-company-dropdown" 
                                    class="hidden absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                    <div id="create-company-options" class="py-1"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Visit Date -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                Visit Date <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar text-gray-400 text-xs"></i>
                                </div>
                                <input type="date" name="visit_date"
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Section with Collapsible -->
                <div class="bg-gradient-to-br bg-blue-50 from-blue-50 to-indigo-50 rounded-lg border border-blue-200 overflow-hidden">
                    <!-- Header - Always Visible -->
                    <div class="p-3 cursor-pointer hover:bg-blue-100 transition-colors" onclick="toggleAddressSection()">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-map-marker-alt text-indigo-600 mr-2"></i>
                                Informasi Lokasi
                            </h4>
                            <div class="flex items-center gap-2">
                                <span id="address-status" class="text-xs text-gray-500">Belum diisi</span>
                                <i id="address-toggle-icon" class="fas fa-chevron-down text-gray-600 transition-transform duration-300"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Collapsible Content -->
                    <div id="address-content" class="hidden">
                        <div class="px-3 pb-3 space-y-3">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                        Province <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-map text-gray-400 text-xs"></i>
                                        </div>
                                        <select name="province_id" id="create-province"
                                            class="w-full pl-9 pr-8 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all appearance-none bg-white"
                                            onchange="checkAddressCompletion()"
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

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1.5">Regency</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-city text-gray-400 text-xs"></i>
                                        </div>
                                        <select name="regency_id" id="create-regency"
                                            class="w-full pl-9 pr-8 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all appearance-none bg-white"
                                            onchange="checkAddressCompletion()">
                                            <option value="">-- Pilih Kabupaten/Kota --</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1.5">District</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-map-signs text-gray-400 text-xs"></i>
                                        </div>
                                        <select name="district_id" id="create-district"
                                            class="w-full pl-9 pr-8 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all appearance-none bg-white"
                                            onchange="checkAddressCompletion()">
                                            <option value="">-- Pilih Kecamatan --</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1.5">Village</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-home text-gray-400 text-xs"></i>
                                        </div>
                                        <select name="village_id" id="create-village"
                                            class="w-full pl-9 pr-8 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all appearance-none bg-white"
                                            onchange="checkAddressCompletion()">
                                            <option value="">-- Pilih Kelurahan/Desa --</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-xs font-medium text-gray-700 mb-1.5">Address</label>
                                    <div class="relative">
                                        <div class="absolute top-2 left-3 pointer-events-none">
                                            <i class="fas fa-map-marked-alt text-gray-400 text-xs"></i>
                                        </div>
                                        <textarea name="address" id="create-address" rows="2"
                                            class="w-full pl-9 pr-3 py-2 text-sm bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-none"
                                            placeholder="Contoh: Jl. Merdeka No. 123, RT 01/RW 02"
                                            oninput="checkAddressCompletion()"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Visit Details -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-cog text-blue-500 mr-2"></i>
                        Detail Kunjungan
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                <i class="fas fa-bullseye mr-1"></i>Purpose <span class="text-red-500">*</span>
                            </label>
                            <textarea name="visit_purpose" rows="2"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none"
                                placeholder="Masukkan tujuan kunjungan..." required></textarea>
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                <i class="fas fa-tasks mr-1"></i>Follow Up
                            </label>
                            <div class="flex gap-2">
                                <label class="relative flex-1 cursor-pointer group">
                                    <input type="radio" name="is_follow_up" value="1" class="peer sr-only">
                                    <div class="w-full px-2 py-2 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-lg transition-all peer-checked:bg-green-500 peer-checked:border-green-500 peer-checked:text-white group-hover:border-green-400 flex items-center justify-center gap-1">
                                        <i class="fas fa-check text-[10px]"></i>
                                        <span>Ya</span>
                                    </div>
                                </label>
                                <label class="relative flex-1 cursor-pointer group">
                                    <input type="radio" name="is_follow_up" value="0" class="peer sr-only" checked>
                                    <div class="w-full px-2 py-2 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-lg transition-all peer-checked:bg-red-500 peer-checked:border-red-500 peer-checked:text-white group-hover:border-red-400 flex items-center justify-center gap-1">
                                        <i class="fas fa-times text-[10px]"></i>
                                        <span>Tidak</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                <button type="button" onclick="closeVisitModal()" 
                    class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-xs font-medium text-gray-700 hover:bg-gra-100 transition-colors flex items-center gap-2">
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

<!-- Add Company Modal -->
<div id="addCompanyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[60] p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-hidden animate-modal-in">
        <div style="background: linear-gradient(to right, #4f46e5, #7c3aed); padding: 1rem 1.25rem;">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-semibold text-white">Tambah Perusahaan Baru</h3>
                    <p class="text-xs text-indigo-100 mt-0.5">Lengkapi formulir berikut untuk menambahkan perusahaan</p>
                </div>
                <button onclick="closeAddCompanyModal()" 
                    class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <div class="overflow-y-auto max-h-[calc(90vh-120px)]" style="background-color: #f3f4f6; padding: 1rem;">
            <form id="addCompanyForm" class="space-y-4">
                @csrf
                <input type="hidden" id="company-form-type" value="create">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">
                            Nama Perusahaan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="company_name" 
                            class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                            placeholder="Masukkan nama perusahaan" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">
                            Jenis Perusahaan <span class="text-red-500">*</span>
                        </label>
                        <select name="company_type_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm appearance-none bg-white"
                                required>
                            <option value="">-- Pilih Jenis --</option>
                            @foreach($types as $type)
                                <option value="{{ $type->company_type_id }}">{{ $type->type_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Tier</label>
                        <select name="tier" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm appearance-none bg-white">
                            <option value="">-- Pilih Tier --</option>
                            <option value="A">Tier A</option>
                            <option value="B">Tier B</option>
                            <option value="C">Tier C</option>
                            <option value="D">Tier D</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm appearance-none bg-white">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="description" rows="3" 
                                  class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm resize-none"
                                  placeholder="Tambahkan keterangan tentang perusahaan..."></textarea>
                    </div>
                </div>
                
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeAddCompanyModal()" 
                            class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700 transition-colors flex items-center gap-2 shadow-lg shadow-indigo-500/30">
                        <i class="fas fa-save"></i>
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.95) translateY(-20px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-fadeIn { animation: fadeIn 0.3s ease-out; }

@keyframes modal-in {
    from { opacity: 0; transform: scale(0.95) translateY(-20px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-modal-in { animation: modal-in 0.3s ease-out; }
</style>

<script>
let companyDropdownTimeout = null;
let currentCompanies = [];

// ========== ADDRESS SECTION TOGGLE ==========
function toggleAddressSection() {
    const content = document.getElementById('address-content');
    const icon = document.getElementById('address-toggle-icon');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

function checkAddressCompletion() {
    const province = document.getElementById('create-province').value;
    const address = document.getElementById('create-address').value.trim();
    const statusText = document.getElementById('address-status');
    const content = document.getElementById('address-content');
    const icon = document.getElementById('address-toggle-icon');
    
    // Cek apakah province dan address sudah diisi
    if (province && address) {
        statusText.textContent = 'Sudah diisi';
        statusText.classList.remove('text-gray-500');
        statusText.classList.add('text-green-600', 'font-medium');
        
        // Auto collapse setelah 800ms
        setTimeout(() => {
            if (!content.classList.contains('hidden')) {
                content.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        }, 800);
    } else {
        statusText.textContent = 'Belum diisi';
        statusText.classList.remove('text-green-600', 'font-medium');
        statusText.classList.add('text-gray-500');
    }
}

// ========== COMPANY DROPDOWN ==========
async function loadCompanies(search = '') {
    try {
        const response = await fetch('/company/get-companies-dropdown');
        const data = await response.json();
        if (data.success) {
            currentCompanies = data.companies;
            updateCompanyDropdown(search);
        }
    } catch (error) {
        console.error('Error loading companies:', error);
    }
}

function updateCompanyDropdown(search = '') {
    const dropdown = document.getElementById('create-company-options');
    const searchTerm = search.toLowerCase();
    const filteredCompanies = currentCompanies.filter(company => 
        company.name.toLowerCase().includes(searchTerm)
    );
    
    dropdown.innerHTML = '';
    
    if (filteredCompanies.length === 0 && search.trim() !== '') {
        const addOption = document.createElement('div');
        addOption.className = 'px-3 py-2 text-sm text-green-600 hover:bg-green-50 cursor-pointer flex items-center gap-2';
        addOption.innerHTML = `<i class="fas fa-plus text-xs"></i><span>Tambah "${search}" sebagai company baru</span>`;
        addOption.onclick = () => {
            document.querySelector('#addCompanyModal input[name="company_name"]').value = search;
            showAddCompanyModal('create');
        };
        dropdown.appendChild(addOption);
    } else {
        filteredCompanies.forEach(company => {
            const option = document.createElement('div');
            option.className = 'px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 cursor-pointer';
            option.textContent = company.name;
            option.onclick = () => selectCompany(company.id, company.name);
            dropdown.appendChild(option);
        });
    }
    
    const dropdownContainer = document.getElementById('create-company-dropdown');
    if (filteredCompanies.length > 0 || search.trim() !== '') {
        dropdownContainer.classList.remove('hidden');
    } else {
        dropdownContainer.classList.add('hidden');
    }
}

function selectCompany(companyId, companyName) {
    document.getElementById('create-company-id').value = companyId;
    document.getElementById('create-company-search').value = companyName;
    document.getElementById('create-company-dropdown').classList.add('hidden');
}

function showAddCompanyModal(type = 'create') {
    document.getElementById('company-form-type').value = type;
    document.getElementById('addCompanyForm').reset();
    document.getElementById('addCompanyModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    setTimeout(() => {
        document.querySelector('#addCompanyModal input[name="company_name"]').focus();
    }, 300);
}

function closeAddCompanyModal() {
    document.getElementById('addCompanyModal').classList.add('hidden');
    document.getElementById('addCompanyForm').reset();
    document.body.style.overflow = 'auto';
}

document.getElementById('addCompanyForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    try {
        const response = await fetch('/company/store-company-ajax', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        });
        
        if (response.status === 422) {
            const errorData = await response.json();
            let errorMessages = [];
            if (errorData.errors) {
                for (const field in errorData.errors) {
                    errorMessages.push(`${field}: ${errorData.errors[field].join(', ')}`);
                }
            }
            alert('Validasi gagal:\n' + errorMessages.join('\n'));
            return;
        }
        
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        
        const data = await response.json();
        
        if (data.success) {
            const newCompany = data.company;
            selectCompany(newCompany.id, newCompany.name);
            loadCompanies();
            closeAddCompanyModal();
            alert('Company berhasil ditambahkan!');
        } else {
            throw new Error(data.message || 'Gagal menambahkan company');
        }
    } catch (error) {
        console.error('Error adding company:', error);
        alert('Gagal menambahkan company: ' + error.message);
    }
});

function initCompanyDropdown() {
    const searchInput = document.getElementById('create-company-search');
    const dropdown = document.getElementById('create-company-dropdown');
    
    searchInput.addEventListener('focus', () => loadCompanies(searchInput.value));
    
    searchInput.addEventListener('input', (e) => {
        clearTimeout(companyDropdownTimeout);
        companyDropdownTimeout = setTimeout(() => updateCompanyDropdown(e.target.value), 300);
    });
    
    searchInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            const searchValue = searchInput.value.trim();
            if (searchValue) {
                const matchedCompany = currentCompanies.find(company => 
                    company.name.toLowerCase() === searchValue.toLowerCase()
                );
                if (!matchedCompany) {
                    document.querySelector('#addCompanyModal input[name="company_name"]').value = searchValue;
                    showAddCompanyModal('create');
                }
            }
        }
    });
    
    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
    
    loadCompanies();
}

const originalOpenVisitModal = window.openVisitModal;
window.openVisitModal = function() {
    if (originalOpenVisitModal) originalOpenVisitModal();
    setTimeout(() => initCompanyDropdown(), 100);
};

window.closeVisitModal = function() {
    document.getElementById('visitModal').classList.add('hidden');
    document.getElementById('visitForm').reset();
    document.body.style.overflow = 'auto';
    
    // Reset company dropdown
    document.getElementById('create-company-id').value = '';
    document.getElementById('create-company-search').value = '';
    document.getElementById('create-company-dropdown').classList.add('hidden');
    
    // Reset address collapse state
    const content = document.getElementById('address-content');
    const icon = document.getElementById('address-toggle-icon');
    const statusText = document.getElementById('address-status');
    
    content.classList.add('hidden');
    icon.style.transform = 'rotate(0deg)';
    statusText.textContent = 'Belum diisi';
    statusText.classList.remove('text-green-600', 'font-medium');
    statusText.classList.add('text-gray-500');
};

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        if (!document.getElementById('addCompanyModal').classList.contains('hidden')) {
            closeAddCompanyModal();
        }
    }
});

document.addEventListener('click', (e) => {
    if (e.target.id === 'addCompanyModal') {
        closeAddCompanyModal();
    }
});
</script>