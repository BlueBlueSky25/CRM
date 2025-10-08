
<!-- MODAL EDIT PIC -->
<div id="editPICModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden animate-modal-in">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-white">Edit PIC</h2>
            <button onclick="closeEditPICModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <!-- Form -->
        <form id="editPICForm" action="#" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama PIC</label>
                <input type="text" id="edit_pic_name" name="name"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
                <input type="text" id="edit_pic_position" name="position"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="edit_pic_email" name="email"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Telepon</label>
                    <input type="text" id="edit_pic_phone" name="phone"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeEditPICModal()"
                    class="px-5 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium">Cancel</button>
                <button type="button"
                    class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium shadow-md hover:shadow-lg">
                    <i class="fas fa-save mr-2"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditPICModal(name, position, email, phone) {
        document.getElementById('edit_pic_name').value = name;
        document.getElementById('edit_pic_position').value = position;
        document.getElementById('edit_pic_email').value = email;
        document.getElementById('edit_pic_phone').value = phone;
        document.getElementById('editPICModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeEditPICModal() {
        document.getElementById('editPICModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('editPICForm').reset();
    }
</script>