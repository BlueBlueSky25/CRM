@props(['currentMenuId', 'salesUsers', 'provinces'])

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
        <form action="{{ route('salesvisit.store') }}" method="POST" class="overflow-y-auto max-h-[calc(95vh-140px)]">
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

                        <!-- Company -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                Company
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-building text-gray-400 text-xs"></i>
                                </div>
                                <input type="text" name="company_name"
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    placeholder="Masukkan nama perusahaan">
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

                <!-- Address Section -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-3 border border-blue-200">
                    <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-map-marker-alt text-indigo-600 mr-2"></i>
                        Informasi Lokasi
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <!-- Province -->
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

                        <!-- Regency -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                Regency
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-city text-gray-400 text-xs"></i>
                                </div>
                                <select name="regency_id" id="create-regency"
                                    class="w-full pl-9 pr-8 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all appearance-none bg-white">
                                    <option value="">-- Pilih Kabupaten/Kota --</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <!-- District -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                District
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-map-signs text-gray-400 text-xs"></i>
                                </div>
                                <select name="district_id" id="create-district"
                                    class="w-full pl-9 pr-8 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all appearance-none bg-white">
                                    <option value="">-- Pilih Kecamatan --</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Village -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                Village
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-home text-gray-400 text-xs"></i>
                                </div>
                                <select name="village_id" id="create-village"
                                    class="w-full pl-9 pr-8 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all appearance-none bg-white">
                                    <option value="">-- Pilih Kelurahan/Desa --</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Address - Full Width -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                Address
                            </label>
                            <div class="relative">
                                <div class="absolute top-2 left-3 pointer-events-none">
                                    <i class="fas fa-map-marked-alt text-gray-400 text-xs"></i>
                                </div>
                                <textarea name="address" rows="2"
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-none"
                                    placeholder="Contoh: Jl. Merdeka No. 123, RT 01/RW 02"></textarea>
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
                    
                    <div class="space-y-3">
                        <!-- Purpose -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                <i class="fas fa-bullseye mr-1"></i>Purpose <span class="text-red-500">*</span>
                            </label>
                            <textarea name="visit_purpose" rows="2"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none"
                                placeholder="Masukkan tujuan kunjungan..." required></textarea>
                        </div>

                        <!-- Follow Up -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                <i class="fas fa-tasks mr-1"></i>Follow Up
                            </label>
                            <div class="flex items-center gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="is_follow_up" value="1" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                    <span class="text-xs text-gray-700">Ya</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="is_follow_up" value="0" class="w-4 h-4 text-blue-600 focus:ring-blue-500" checked>
                                    <span class="text-xs text-gray-700">Tidak</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                <button type="button" onclick="closeVisitModal()" 
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

<!-- EDIT SALES VISIT MODAL -->
<div id="editVisitModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[95vh] overflow-hidden animate-fadeIn">
        
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #3b82f6 100%);">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-edit text-white text-lg"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-white">Edit Kunjungan Sales</h2>
                </div>
                <button onclick="closeEditVisitModal()" class="text-white hover:text-gray-200 transition-colors p-2">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <form id="editVisitForm" method="POST" class="overflow-y-auto max-h-[calc(95vh-140px)]">
            @csrf
            @method('PUT')
            
            <input type="hidden" id="editVisitId" name="visit_id">
            <input type="hidden" id="editIsSalesRole" value="{{ strtolower(auth()->user()->role->role_name ?? '') === 'sales' ? '1' : '0' }}">

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
                                <select name="sales_id" id="editSalesId"
                                    class="w-full pl-9 pr-8 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none bg-white"
                                    required>
                                    <option value="">-- Pilih Sales --</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </div>
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
                                <input type="text" name="customer_name" id="editCustomerName"
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    placeholder="Masukkan nama customer" required>
                            </div>
                        </div>

                        <!-- Company -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                Company
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-building text-gray-400 text-xs"></i>
                                </div>
                                <input type="text" name="company_name" id="editCompany"
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    placeholder="Masukkan nama perusahaan">
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
                                <input type="date" name="visit_date" id="editVisitDate"
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Section -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-3 border border-blue-200">
                    <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-map-marker-alt text-indigo-600 mr-2"></i>
                        Informasi Lokasi
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <!-- Province -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                Province <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-map text-gray-400 text-xs"></i>
                                </div>
                                <select name="province_id" id="edit-province"
                                    class="w-full pl-9 pr-8 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all appearance-none bg-white"
                                    required>
                                    <option value="">-- Pilih Provinsi --</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Regency -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                Regency
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-city text-gray-400 text-xs"></i>
                                </div>
                                <select name="regency_id" id="edit-regency"
                                    class="w-full pl-9 pr-8 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all appearance-none bg-white">
                                    <option value="">-- Pilih Kabupaten/Kota --</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <!-- District -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                District
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-map-signs text-gray-400 text-xs"></i>
                                </div>
                                <select name="district_id" id="edit-district"
                                    class="w-full pl-9 pr-8 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all appearance-none bg-white">
                                    <option value="">-- Pilih Kecamatan --</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Village -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                Village
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-home text-gray-400 text-xs"></i>
                                </div>
                                <select name="village_id" id="edit-village"
                                    class="w-full pl-9 pr-8 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all appearance-none bg-white">
                                    <option value="">-- Pilih Kelurahan/Desa --</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Address - Full Width -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                Address
                            </label>
                            <div class="relative">
                                <div class="absolute top-2 left-3 pointer-events-none">
                                    <i class="fas fa-map-marked-alt text-gray-400 text-xs"></i>
                                </div>
                                <textarea name="address" id="editAddress" rows="2"
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-none"
                                    placeholder="Contoh: Jl. Merdeka No. 123, RT 01/RW 02"></textarea>
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
                    
                    <div class="space-y-3">
                        <!-- Purpose -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                <i class="fas fa-bullseye mr-1"></i>Purpose <span class="text-red-500">*</span>
                            </label>
                            <textarea name="visit_purpose" id="editPurpose" rows="2"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none"
                                placeholder="Masukkan tujuan kunjungan..." required></textarea>
                        </div>

                        <!-- Follow Up -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                <i class="fas fa-tasks mr-1"></i>Follow Up
                            </label>
                            <div class="flex items-center gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="is_follow_up" id="editFollowUpYes" value="1" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                    <span class="text-xs text-gray-700">Ya</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="is_follow_up" id="editFollowUpNo" value="0" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                    <span class="text-xs text-gray-700">Tidak</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                <button type="button" onclick="closeEditVisitModal()" 
                    class="px-4 py-2 border border-gray-300 rounded-lg text-xs font-medium text-gray-700 hover:bg-gray-100 transition-colors flex items-center gap-2">
                    <i class="fas fa-times"></i>
                    Batal
                </button>
                <button type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg text-xs font-medium hover:bg-blue-700 transition-colors flex items-center gap-2 shadow-lg shadow-blue-500/30">
                    <i class="fas fa-save"></i>
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Import Modal -->
<div id="importModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md animate-fadeIn">
        <div class="px-6 py-4 border-b border-gray-200" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #3b82f6 100%);">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file-import text-white text-lg"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-white">Import Data CSV</h2>
                </div>
                <button onclick="closeImportModal()" class="text-white hover:text-gray-200 transition-colors p-2">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <form action="{{ route('salesvisit.import') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-2">
                    <i class="fas fa-file-csv mr-1 text-green-600"></i>
                    Pilih File CSV
                </label>
                <div class="relative">
                    <input type="file" name="file" accept=".csv,.txt" required
                        class="w-full border-2 border-dashed border-gray-300 rounded-lg px-4 py-6 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                </div>
                <div class="mt-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-xs text-blue-800 flex items-start">
                        <i class="fas fa-info-circle mr-2 mt-0.5"></i>
                        <span><strong>Format:</strong> CSV, maksimal 5MB</span>
                    </p>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeImportModal()" 
                    class="px-4 py-2 border border-gray-300 rounded-lg text-xs font-medium text-gray-700 hover:bg-gray-100 transition-colors flex items-center gap-2">
                    <i class="fas fa-times"></i>
                    Batal
                </button>
                <button type="submit" 
                    class="px-4 py-2 bg-green-600 text-white rounded-lg text-xs font-medium hover:bg-green-700 transition-colors flex items-center gap-2 shadow-lg shadow-green-500/30">
                    <i class="fas fa-upload"></i>
                    Import
                </button>
            </div>
        </form>
    </div>
</div>

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

/* Custom Scrollbar */
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

/* Responsive */
@media (max-width: 768px) {
    .grid-cols-2 {
        grid-template-columns: 1fr !important;
    }
}
</style>

<script>
function openImportModal() {
    document.getElementById('importModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeImportModal() {
    document.getElementById('importModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
</script>