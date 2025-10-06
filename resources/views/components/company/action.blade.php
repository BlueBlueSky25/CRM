<!-- Quick Actions Section -->
<div class="fade-in">
    <div class="flex justify-between items-center mb-2">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2"></h3>
            <div class="flex gap-3">
                <button onclick="openCompanyModal()"
                    class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
                    <i class="fas fa-plus"></i>
                    Tambah Company
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Company -->
<div id="companyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden animate-modal-in">

        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-white">Tambah Company Baru</h2>
                    <p class="text-indigo-100 text-sm mt-1">Lengkapi formulir berikut untuk menambahkan perusahaan</p>
                </div>
                <button onclick="closeCompanyModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Body -->
        <div class="overflow-y-auto max-h-[calc(90vh-140px)] p-6">
            <form action="{{ route('companies.store') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Nama Perusahaan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Perusahaan <span class="text-red-500">*</span></label>
                    <input type="text" name="company_name"
                        class="w-full border border-gray-300 rounded-lg px-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                        placeholder="Masukkan nama perusahaan" required>
                </div>

                <!-- Jenis Perusahaan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Perusahaan <span class="text-red-500">*</span></label>
                    <select name="company_type_id"
                        class="w-full border border-gray-300 rounded-lg py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
                        <option value="">-- Pilih Jenis --</option>
                        @foreach($types as $type)
                            <option value="{{ $type->company_type_id }}">{{ $type->type_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tier -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tier</label>
                    <input type="text" name="tier"
                        class="w-full border border-gray-300 rounded-lg px-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                        placeholder="Contoh: A, B, C">
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="description" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none transition-all"
                        placeholder="Tambahkan keterangan tentang perusahaan..."></textarea>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status"
                        class="w-full border border-gray-300 rounded-lg py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <!-- Tombol -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 mt-6">
                    <button type="button" onclick="closeCompanyModal()" class="px-6 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 transform hover:scale-105 font-medium shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openCompanyModal() {
    document.getElementById('companyModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeCompanyModal() {
    document.getElementById('companyModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
</script>