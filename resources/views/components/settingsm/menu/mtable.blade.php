<!-- Menu Management Table with Fixed Actions -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Menu Management</h3>
            <p class="text-sm text-gray-600 mt-1">Manage application menus and navigation</p>
        </div>
        @if(auth()->user()->canAccess($currentMenuId, 'create'))
        <button onclick="openMenuModal()" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Add New Menu</span>
        </button>
        @endif
    </div>

    <!-- Notifikasi -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mx-6 mt-4">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No.</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Menu Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Route</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Icon</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($menus as $index => $menu)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $menu->nama_menu }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $menu->route ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        @if($menu->icon)
                            <i class="{{ $menu->icon }}"></i> {{ $menu->icon }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            @if(auth()->user()->canAccess($currentMenuId, 'edit'))
                            <button onclick="openEditMenuModal('{{ $menu->menu_id }}', '{{ $menu->nama_menu }}', '{{ $menu->route }}', '{{ $menu->icon }}')" 
                                    class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 transition-colors" 
                                    title="Edit Menu">
                                <i class="fas fa-edit"></i>
                            </button>
                            @endif

                            @if(auth()->user()->canAccess($currentMenuId, 'delete'))
                            <form action="{{ route('menus.destroy', $menu->menu_id) }}" method="POST" class="inline-flex">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition-colors" 
                                        title="Delete Menu" onclick="return confirm('Are you sure you want to delete this menu?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Menu Modal -->
<div id="editMenuModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-50" style="backdrop-filter: blur(4px);">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-xl shadow-xl max-w-lg w-full" style="animation: modalSlideIn 0.3s ease-out;">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Edit Menu</h3>
                    <button onclick="closeEditMenuModal()" class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <form id="editMenuForm" action="{{ route('menus.update', ['id' => 0]) }}" method="POST" class="px-6 py-4">
                @csrf
                @method('PUT')
                <input type="hidden" id="editMenuId" name="menu_id">
                
                <div class="space-y-4">
                    <div>
                        <label for="editMenuName" class="block text-sm font-medium text-gray-700 mb-1">Menu Name *</label>
                        <input type="text" id="editMenuName" name="nama_menu" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none" required>
                    </div>
                    
                    <div>
                        <label for="editMenuRoute" class="block text-sm font-medium text-gray-700 mb-1">Route</label>
                        <input type="text" id="editMenuRoute" name="route" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none" placeholder="e.g., users.index">
                    </div>
                    
                    <div>
                        <label for="editMenuIcon" class="block text-sm font-medium text-gray-700 mb-1">Icon</label>
                        <div class="relative">
                            <input type="text" id="editMenuIcon" name="icon" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none" placeholder="e.g., fas fa-users">
                            <div class="absolute right-3 top-2.5">
                                <i id="iconPreview" class="text-gray-400"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Use FontAwesome classes (e.g., fas fa-users)</p>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeEditMenuModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 transition-colors">
                        Update Menu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Menu Modal -->
<div id="addMenuModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-50" style="backdrop-filter: blur(4px);">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-xl shadow-xl max-w-lg w-full" style="animation: modalSlideIn 0.3s ease-out;">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Add New Menu</h3>
                    <button onclick="closeAddMenuModal()" class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <form id="addMenuForm" action="{{ route('menus.store') }}" method="POST" class="px-6 py-4">
                @csrf
                
                <div class="space-y-4">
                    <div>
                        <label for="addMenuName" class="block text-sm font-medium text-gray-700 mb-1">Menu Name</label>
                        <input type="text" id="addMenuName" name="nama_menu" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none" placeholder="Enter menu name" required>
                    </div>
                    
                    <div>
                        <label for="addMenuRoute" class="block text-sm font-medium text-gray-700 mb-1">Route</label>
                        <input type="text" id="addMenuRoute" name="route" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none" placeholder="e.g., users.index">
                    </div>

                    <div>
                        <label for="addMenuOrder" class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                        <input type="number" id="addMenuOrder" name="order" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none" placeholder="Order Number">
                    </div>

                    <div>
                        <label for="addMenuIcon" class="block text-sm font-medium text-gray-700 mb-1">Icon</label>
                        <div class="relative">
                            <input type="text" id="addMenuIcon" name="icon" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none" placeholder="e.g., fas fa-users">
                            <div class="absolute right-3 top-2.5">
                                <i id="addIconPreview" class="text-gray-400"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Use FontAwesome classes (e.g., fas fa-users)</p>
                    </div>

                    
                </div>
                
                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeAddMenuModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 transition-colors">
                        Add Menu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript untuk Modal -->
<script>
    // Function to open edit menu modal
    function openEditMenuModal(menuId, menuName, route, icon) {
        document.getElementById('editMenuId').value = menuId;
        document.getElementById('editMenuName').value = menuName;
        document.getElementById('editMenuRoute').value = route || '';
        document.getElementById('editMenuIcon').value = icon || '';
        
        // Update icon preview
        const iconPreview = document.getElementById('iconPreview');
        if (icon) {
            iconPreview.className = icon;
        } else {
            iconPreview.className = 'text-gray-400';
        }
        
        document.getElementById('editMenuModal').classList.remove('hidden');
    }

    // Function to close edit menu modal
    function closeEditMenuModal() {
        document.getElementById('editMenuModal').classList.add('hidden');
        document.getElementById('editMenuForm').reset();
        document.getElementById('iconPreview').className = 'text-gray-400';
    }

    // Function to open add menu modal
    function openMenuModal() {
        document.getElementById('addMenuModal').classList.remove('hidden');
    }

    // Function to close add menu modal
    function closeAddMenuModal() {
        document.getElementById('addMenuModal').classList.add('hidden');
        document.getElementById('addMenuForm').reset();
        document.getElementById('addIconPreview').className = 'text-gray-400';
    }

    // Handle edit menu form submission
    document.getElementById('editMenuForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const menuId = document.getElementById('editMenuId').value;
        const form = document.getElementById('editMenuForm');
        form.action = `/menus/${menuId}`;  // Adjust this URL to match your Laravel route
        form.submit();
    });

    // Icon preview for edit modal
    document.getElementById('editMenuIcon').addEventListener('input', function(e) {
        const iconPreview = document.getElementById('iconPreview');
        const iconValue = e.target.value.trim();
        
        if (iconValue) {
            iconPreview.className = iconValue;
        } else {
            iconPreview.className = 'text-gray-400';
        }
    });

    // Icon preview for add modal
    document.getElementById('addMenuIcon').addEventListener('input', function(e) {
        const iconPreview = document.getElementById('addIconPreview');
        const iconValue = e.target.value.trim();
        
        if (iconValue) {
            iconPreview.className = iconValue;
        } else {
            iconPreview.className = 'text-gray-400';
        }
    });

    // Close modal when clicking outside
    document.getElementById('editMenuModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditMenuModal();
        }
    });

    document.getElementById('addMenuModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAddMenuModal();
        }
    });

    // Handle ESC key to close modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEditMenuModal();
            closeAddMenuModal();
        }
    });
</script>

<!-- CSS untuk animasi modal -->
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
</style>