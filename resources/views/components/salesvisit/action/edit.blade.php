<!-- EDIT INDUSTRY MODAL -->
<div id="editIndustryModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6">
        <h2 class="text-lg font-semibold mb-4">Edit Data Industri</h2>
        <form action="" method="POST"> <!-- kosongin route dulu -->
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block mb-1 font-medium">Nama Industri</label>
                <input type="text" name="name" class="w-full border rounded-lg px-3 py-2" placeholder="Masukkan nama industri">
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Jenis Industri</label>
                <input type="text" name="type" class="w-full border rounded-lg px-3 py-2" placeholder="Contoh: Teknologi, Manufaktur">
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">No. Telepon</label>
                <input type="text" name="phone" class="w-full border rounded-lg px-3 py-2" placeholder="+62 8xx-xxxx-xxxx">
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Email</label>
                <input type="email" name="email" class="w-full border rounded-lg px-3 py-2" placeholder="contoh@email.com">
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Alamat</label>
                <textarea name="address" class="w-full border rounded-lg px-3 py-2" rows="3" placeholder="Masukkan alamat industri..."></textarea>
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Kota</label>
                <input type="text" name="city" class="w-full border rounded-lg px-3 py-2" placeholder="Masukkan kota industri">
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditIndustryModal()" class="border px-4 py-2 rounded-lg hover:bg-gray-100">Batal</button>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditIndustryModal() {
    document.getElementById('editIndustryModal').classList.remove('hidden');
}
function closeEditIndustryModal() {
    document.getElementById('editIndustryModal').classList.add('hidden');
}
</script>