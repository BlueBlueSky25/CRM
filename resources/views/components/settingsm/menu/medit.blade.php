<!-- Edit Menu Modal -->
<div id="editMenuModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden animate-modal-in">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold text-white">Edit Menu</h3>
                <button onclick="closeEditMenuModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>
        <div class="overflow-y-auto max-h-[calc(90vh-140px)] p-6">
            <form id="editMenuForm" action="{{ route('menus.update', ['id' => 0]) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" id="editMenuId" name="menu_id">
                
                <div class="space-y-4">
                    <div>
                        <label for="editMenuName" class="block text-sm font-medium text-gray-700 mb-1">Menu Name *</label>
                        <input type="text" id="editMenuName" name="nama_menu" class="w-full border border-gray-300 rounded-lg pl-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
                    </div>
                    
                    <div>
                        <label for="editMenuRoute" class="block text-sm font-medium text-gray-700 mb-1">Route</label>
                        <input type="text" id="editMenuRoute" name="route" class="w-full border border-gray-300 rounded-lg pl-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="e.g., users.index">
                    </div>

                    <div>
                        <label for="editMenuOrder" class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                        <input type="number" id="editMenuOrder" name="order" class="w-full border border-gray-300 rounded-lg pl-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Order Number">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Parent Menu</label>
                        <select name="parent_id" id="editMenuParent" class="w-full border border-gray-300 rounded-lg pl-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">                         
                            <option value="">-- No Parent (Main Menu) --</option>
                            @foreach(App\Models\Menu::whereNull('parent_id')->get() as $menu)
                                <option value="{{ $menu->menu_id }}">{{ $menu->nama_menu }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="editMenuIcon" class="block text-sm font-medium text-gray-700 mb-1">Icon</label>
                        <div class="relative">
                            <input type="text" id="editMenuIcon" name="icon" class="w-full border border-gray-300 rounded-lg pl-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="e.g., fas fa-users">
                            <div class="absolute right-3 top-2.5">
                                <i id="iconPreview" class="text-gray-400"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Use FontAwesome classes (e.g., fas fa-users)</p>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeEditMenuModal()" class="px-6 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 transform hover:scale-105 font-medium shadow-md hover:shadow-lg">Update Menu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openEditMenuModal(menuId, menuName, route, icon, parentId = null, order = null) {
        console.log('Opening edit modal with:', {menuId, menuName, route, icon, parentId, order});
        
        document.getElementById('editMenuId').value = menuId;
        document.getElementById('editMenuName').value = menuName;
        document.getElementById('editMenuRoute').value = route || '';
        document.getElementById('editMenuIcon').value = icon || '';
        document.getElementById('editMenuOrder').value = order || '';
        
        const parentSelect = document.getElementById('editMenuParent');
        if (parentSelect) {
            parentSelect.value = parentId || '';
        }
        
        const iconPreview = document.getElementById('iconPreview');
        if (icon) {
            iconPreview.className = icon;
        } else {
            iconPreview.className = 'text-gray-400';
        }
        
        document.getElementById('editMenuModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeEditMenuModal() {
        document.getElementById('editMenuModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('editMenuForm').reset();
        document.getElementById('iconPreview').className = 'text-gray-400';
    }

    // Handle form submission
    document.addEventListener('DOMContentLoaded', function() {
        const editForm = document.getElementById('editMenuForm');
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const menuId = document.getElementById('editMenuId').value;
                this.action = `/menus/${menuId}`;
                this.submit();
            });
        }

        // Icon preview untuk edit modal
        const editIconInput = document.getElementById('editMenuIcon');
        if (editIconInput) {
            editIconInput.addEventListener('input', function(e) {
                const iconPreview = document.getElementById('iconPreview');
                const iconValue = e.target.value.trim();
                iconPreview.className = iconValue || 'text-gray-400';
            });
        }
    });

    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('editMenuModal');
        if (e.target === modal) {
            closeEditMenuModal();
        }
    });

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('editMenuModal');
            if (modal && !modal.classList.contains('hidden')) {
                closeEditMenuModal();
            }
        }
    });
</script>