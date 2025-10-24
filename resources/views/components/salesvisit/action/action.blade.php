@props(['currentMenuId', 'salesUsers', 'provinces'])

<!-- Quick Actions Section -->
<div class="fade-in mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Quick Actions</h3>
            <div class="flex gap-3 flex-wrap">
                @if(auth()->user()->canAccess($currentMenuId, 'create'))
                <button onclick="openVisitModal()"
                    class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
                    <i class="fas fa-plus"></i>
                    Tambah Kunjungan
                </button>
                @endif

                <!-- Export Button -->
                <a href="{{ route('salesvisit.export') }}" 
                    class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
                    <i class="fas fa-file-export"></i>
                    Export CSV
                </a>

                <!-- Import Button -->
                <button onclick="openImportModal()"
                    class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
                    <i class="fas fa-file-import"></i>
                    Import CSV
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Tambah Kunjungan Modal -->
<div id="visitModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden animate-modal-in">
        
        <!-- Modal Header -->
        <div class="px-6 py-5 border-b border-gray-200" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #3b82f6 100%);">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-white">Tambah Kunjungan Sales</h2>
                    <p class="text-indigo-100 text-sm mt-1">Isi data untuk menambahkan kunjungan sales</p>
                </div>
                <button onclick="closeVisitModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="overflow-y-auto max-h-[calc(90vh-140px)] p-6">
            <form action="{{ route('salesvisit.store') }}" method="POST" class="space-y-4">
    @csrf

    @php
        $userRole = strtolower(auth()->user()->role->role_name ?? '');
        $isSales = $userRole === 'sales';
    @endphp

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Sales <span class="text-red-500">*</span></label>
        <div class="relative">
            <i class="fas fa-user-tie absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            @if($isSales)
                <input type="text" 
                    value="{{ auth()->user()->username }} - {{ auth()->user()->email }}"
                    class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 bg-gray-100 cursor-not-allowed transition-all"
                    readonly>
                <input type="hidden" name="sales_id" value="{{ auth()->user()->user_id }}">
            @else
                <select name="sales_id" id="visit-sales"
                    class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                    required>
                    <option value="">Pilih Sales</option>
                    @foreach($salesUsers as $sales)
                        <option value="{{ $sales->user_id }}">{{ $sales->username }} - {{ $sales->email }}</option>
                    @endforeach
                </select>
            @endif
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Customer Name <span class="text-red-500">*</span></label>
        <div class="relative">
            <i class="fas fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="text" name="customer_name"
                class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                placeholder="Masukkan nama customer" required>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Company</label>
        <div class="relative">
            <i class="fas fa-building absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="text" name="company_name"
                class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                placeholder="Masukkan nama perusahaan">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Province <span class="text-red-500">*</span></label>
        <div class="relative">
            <i class="fas fa-map-marked-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <select name="province_id" id="create-province"
                class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                required>
                <option value="">Pilih Provinsi</option>
                @foreach($provinces as $province)
                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Regency</label>
        <div class="relative">
            <i class="fas fa-map-marker-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <select name="regency_id" id="create-regency"
                class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                <option value="">Pilih Kabupaten/Kota</option>
            </select>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">District</label>
        <div class="relative">
            <i class="fas fa-map-pin absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <select name="district_id" id="create-district"
                class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                <option value="">Pilih Kecamatan</option>
            </select>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Village</label>
        <div class="relative">
            <i class="fas fa-location-dot absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <select name="village_id" id="create-village"
                class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                <option value="">Pilih Kelurahan/Desa</option>
            </select>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
        <div class="relative">
            <i class="fas fa-map-location-dot absolute left-3 top-4 text-gray-400"></i>
            <textarea name="address" rows="2"
                class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none transition-all"
                placeholder="Masukkan alamat lengkap..."></textarea>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Visit Date <span class="text-red-500">*</span></label>
        <div class="relative">
            <i class="fas fa-calendar absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="date" name="visit_date"
                class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                required>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Purpose <span class="text-red-500">*</span></label>
        <div class="relative">
            <i class="fas fa-bullseye absolute left-3 top-4 text-gray-400"></i>
            <textarea name="visit_purpose" rows="3"
                class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none transition-all"
                placeholder="Masukkan tujuan kunjungan..." required></textarea>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Follow Up</label>
        <div class="flex items-center gap-4">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="is_follow_up" value="1" class="w-4 h-4 text-indigo-600 focus:ring-indigo-500">
                <span class="text-sm text-gray-700">Ya</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="is_follow_up" value="0" class="w-4 h-4 text-indigo-600 focus:ring-indigo-500" checked>
                <span class="text-sm text-gray-700">Tidak</span>
            </label>
        </div>
    </div>

    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 mt-6">
        <button type="button" onclick="closeVisitModal()" class="px-6 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">Batal</button>
        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 transform hover:scale-105 font-medium shadow-md hover:shadow-lg">
            <i class="fas fa-save mr-2"></i>
            Simpan Data
        </button>
    </div>
</form>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div id="importModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md animate-modal-in">
        <div class="bg-gradient-to-r from-blue-600 to-cyan-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-white">Import Data CSV</h2>
                <button onclick="closeImportModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>
        
        <form action="{{ route('salesvisit.import') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih File CSV</label>
                <input type="file" name="file" accept=".csv,.txt" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p class="text-xs text-gray-500 mt-2">Format: CSV, maksimal 5MB</p>
            </div>
            
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeImportModal()" 
                    class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-upload mr-2"></i>
                    Import
                </button>
            </div>
        </form>
    </div>
</div>

<style>
@keyframes modalSlideIn {
    from { 
        opacity: 0; 
        transform: translateY(-20px) scale(0.95); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0) scale(1); 
    }
}

.animate-modal-in { 
    animation: modalSlideIn 0.25s ease-out; 
}

.fade-in { 
    animation: fadeIn 0.3s ease-in; 
}

@keyframes fadeIn { 
    from { opacity: 0; } 
    to { opacity: 1; } 
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