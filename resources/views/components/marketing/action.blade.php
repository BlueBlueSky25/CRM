    <!-- Action Buttons -->
    <div class="fade-in">
    <div class="flex justify-between items-center mb-8">
        <div>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Quick Actions</h3>
        <div class="flex gap-3">
            <!-- Import -->
            <!-- <button onclick="openImportModal()"
            class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="fas fa-upload text-gray-600"></i>
            Import
            </button> -->

            <!-- Export -->
            <!-- <button onclick="exportCustomers()"
            class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="fas fa-download text-gray-600"></i>
            Export
            </button> -->

            <!-- Tambah Customer -->
            <button onclick="openSalesModal()"
            class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus"></i>
            Tambah Sales
            </button>
        </div>
        </div>
    </div>
    </div>

    <!-- ================= Modal Tambah Customer ================= -->
    <div id="salesModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Tambah Sales</h2>
            <button onclick="closeSalesModal()" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>
            <form action="{{ route('marketing.sales.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                    <input type="text" id="username" name="username" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan nama lengkap" required>
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" id="email" name="email" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="contoh@email.com" required>
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                    <input type="password" id="password" name="password" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Minimal 6 karakter" required>
                    <p class="text-xs text-gray-500 mt-1">Password minimal 6 karakter</p>
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                    <input type="text" id="phone" name="phone" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: +62 812-3456-7890">
                </div>
                
                <div>
                    <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                    <input type="date" id="birth_date" name="birth_date" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" max="{{ date('Y-m-d') }}">
                </div>
                
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                    <textarea id="address" name="address" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none" placeholder="Masukkan alamat lengkap..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeSalesModal()" class="px-4 py-2 border rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
                </div>
            </form>
    </div>
</div>


    <!-- ================= Modal Import ================= -->
    <!-- <div id="importModal"
    class="hidden, fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold">Import Customer</h2>
        <button onclick="closeImportModal()" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>
        <form id="importForm" class="space-y-4">
        <input type="file" accept=".csv,.xlsx" class="w-full border rounded-lg px-3 py-2" required>
        <div class="flex justify-end gap-3">
            <button type="button" onclick="closeImportModal()"
            class="px-4 py-2 border rounded-lg">Batal</button>
            <button type="submit"
            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Upload</button>
        </div>
        </form>
    </div>
    </div> -->

    <!-- ================= Script ================= -->
    <script>
    // ===== Modal Tambah Customer =====
    function openSalesModal() {
    document.getElementById('salesModal').classList.remove('hidden');
    }
    function closeSalesModal() {
    document.getElementById('salesModal').classList.add('hidden');
    }

    // ===== Modal Import =====
    // function openImportModal() {
    //     document.getElementById('importModal').classList.remove('hidden');
    // }
    // function closeImportModal() {
    //     document.getElementById('importModal').classList.add('hidden');
    // }

    // ===== Export Placeholder =====
    // function exportCustomers() {
    //     alert('Fungsi export belum dihubungkan ke backend 🚀');
    // }

    // ===== Form Handler (demo) =====
    // document.getElementById('customerForm').addEventListener('submit', function (e) {
    //     e.preventDefault();
    //     alert('Customer berhasil ditambahkan ✅ (ini masih demo)');
    //     closeSalesModal();
    // });

    // document.getElementById('importForm').addEventListener('submit', function (e) {
    //     e.preventDefault();
    //     alert('File berhasil diupload ✅ (ini masih demo)');
    //     closeImportModal();
    // });
    </script>
