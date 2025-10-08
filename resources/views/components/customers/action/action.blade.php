    <!-- Action Buttons -->
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
            <button onclick="exportCustomers()"
            class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="fas fa-download text-gray-600"></i>
            Export
            </button>

            <!-- Tambah Customer -->
            <button onclick="openCustomerModal()"
            class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus"></i>
            Tambah Customer
            </button>
        </div>
        </div>
    </div>
    </div>

    <!-- ================= Modal Tambah Customer ================= -->
    <div id="customerModal"
    class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6">
        <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold">Tambah Customer</h2>
        <button onclick="closeCustomerModal()" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>
        <form id="customerForm" class="space-y-4">
        <input type="text" placeholder="Nama Customer" class="w-full border rounded-lg px-3 py-2" required>
        <input type="text" placeholder="Kategori" class="w-full border rounded-lg px-3 py-2">
        <input type="text" placeholder="Lokasi" class="w-full border rounded-lg px-3 py-2">
        <input type="text" placeholder="Kontak" class="w-full border rounded-lg px-3 py-2">
        <input type="number" placeholder="Lead Score" class="w-full border rounded-lg px-3 py-2">
        <select class="w-full border rounded-lg px-3 py-2">
            <option value="">Pilih Status</option>
            <option value="Prospek">Prospek</option>
            <option value="Negosiasi">Negosiasi</option>
            <option value="Closed">Closed</option>
        </select>
        <input type="number" placeholder="Revenue" class="w-full border rounded-lg px-3 py-2">
        <div class="flex justify-end gap-3">
            <button type="button" onclick="closeCustomerModal()"
            class="px-4 py-2 border rounded-lg">Batal</button>
            <button type="submit"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
        </div>
        </form>
    </div>
    </div>

    <!-- ================= Modal Import ================= -->
    <div id="importModal"
    class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
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
    </div>

    <!-- ================= Script ================= -->
    <script>
    // ===== Modal Tambah Customer =====
    function openCustomerModal() {
        document.getElementById('customerModal').classList.remove('hidden');
    }
    function closeCustomerModal() {
        document.getElementById('customerModal').classList.add('hidden');
    }

    // ===== Modal Import =====
    function openImportModal() {
        document.getElementById('importModal').classList.remove('hidden');
    }
    function closeImportModal() {
        document.getElementById('importModal').classList.add('hidden');
    }

    // ===== Export Placeholder =====
    function exportCustomers() {
        alert('Fungsi export belum dihubungkan ke backend 🚀');
    }

    // ===== Form Handler (demo) =====
    document.getElementById('customerForm').addEventListener('submit', function (e) {
        e.preventDefault();
        alert('Customer berhasil ditambahkan ✅ (ini masih demo)');
        closeCustomerModal();
    });

    document.getElementById('importForm').addEventListener('submit', function (e) {
        e.preventDefault();
        alert('File berhasil diupload ✅ (ini masih demo)');
        closeImportModal();
    });
    </script>
