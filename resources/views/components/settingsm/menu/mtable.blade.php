<!-- Menu Management Table with Fixed Actions -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Menu Management</h3>
            <p class="text-sm text-gray-600 mt-1">Manage application menus and navigation</p>
        </div>
        @if(auth()->user()->canAccess($currentMenuId, 'create'))
        <button onclick="openMenuModal()" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Add New Menu</span>
        </button>
        @endif
    </div>

    <!-- Notification -->
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Parent</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($menus as $index => $menu)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $menus->firstItem() + $loop->index }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $menu->nama_menu }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $menu->route ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        @if($menu->icon)
                            <i class="{{ $menu->icon }}"></i> {{ $menu->icon }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $menu->parent?->nama_menu ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            @if(auth()->user()->canAccess($currentMenuId, 'edit'))
                            <button onclick="openEditMenuModal('{{ $menu->menu_id }}', '{{ $menu->nama_menu }}', '{{ $menu->route }}', '{{ $menu->icon }}', '{{ $menu->parent_id }}', '{{ $menu->order }}')" 
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
    
    <!-- Pagination langsung di dalam wrapper -->
    <x-globalr.pagination :paginator="$menus" />
    
</div>

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

<!-- Add Menu Modal -->
<div id="addMenuModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden animate-modal-in">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold text-white">Add New Menu</h3>
                <button onclick="closeAddMenuModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <div class="overflow-y-auto max-h-[calc(90vh-140px)] p-6">
            <form id="addMenuForm" action="{{ route('menus.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div class="space-y-4">
                    <div>
                        <label for="addMenuName" class="block text-sm font-medium text-gray-700 mb-1">Menu Name <span class="text-red-500">*</span></label>
                        <input type="text" id="addMenuName" name="nama_menu" class="w-full border border-gray-300 rounded-lg pl-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Enter menu name" required>
                    </div>
                    
                    <div>
                        <label for="addMenuRoute" class="block text-sm font-medium text-gray-700 mb-1">Route</label>
                        <input type="text" id="addMenuRoute" name="route" class="w-full border border-gray-300 rounded-lg pl-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="e.g., users.index">
                    </div>

                    <div>
                        <label for="addMenuOrder" class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                        <input type="number" id="addMenuOrder" name="order" class="w-full border border-gray-300 rounded-lg pl-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Order Number">
                    </div>

                    <div>
                        <label for="addMenuIcon" class="block text-sm font-medium text-gray-700 mb-1">Icon</label>
                        <div class="relative">
                            <input type="text" id="addMenuIcon" name="icon" class="w-full border border-gray-300 rounded-lg pl-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="e.g., fas fa-users">
                            <div class="absolute right-3 top-2.5">
                                <i id="addIconPreview" class="text-gray-400"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Use FontAwesome classes (e.g., fas fa-users)</p>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeAddMenuModal()" class="px-6 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 transform hover:scale-105 font-medium shadow-md hover:shadow-lg">Add Menu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openEditMenuModal(menuId, menuName, route, icon, parentId = null, order = null) {
        document.getElementById('editMenuId').value = menuId;
        document.getElementById('editMenuName').value = menuName;
        document.getElementById('editMenuRoute').value = route || '';
        document.getElementById('editMenuIcon').value = icon || '';
        document.getElementById('editMenuOrder').value = order || '';
        
        const parentSelect = document.getElementById('editMenuParent');
        parentSelect.value = parentId || '';
        
        const iconPreview = document.getElementById('iconPreview');
        if (icon) {
            iconPreview.className = icon;
        } else {
            iconPreview.className = 'text-gray-400';
        }
        
        document.getElementById('editMenuModal').classList.remove('hidden');
    }

    function closeEditMenuModal() {
        document.getElementById('editMenuModal').classList.add('hidden');
        document.getElementById('editMenuForm').reset();
        document.getElementById('iconPreview').className = 'text-gray-400';
    }

    function openMenuModal() {
        document.getElementById('addMenuModal').classList.remove('hidden');
    }

    function closeAddMenuModal() {
        document.getElementById('addMenuModal').classList.add('hidden');
        document.getElementById('addMenuForm').reset();
        document.getElementById('addIconPreview').className = 'text-gray-400';
    }

    document.getElementById('editMenuForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const menuId = document.getElementById('editMenuId').value;
        const form = document.getElementById('editMenuForm');
        form.action = `/menus/${menuId}`;
        form.submit();
    });

    document.getElementById('editMenuIcon').addEventListener('input', function(e) {
        const iconPreview = document.getElementById('iconPreview');
        const iconValue = e.target.value.trim();
        iconPreview.className = iconValue || 'text-gray-400';
    });

    document.getElementById('addMenuIcon').addEventListener('input', function(e) {
        const iconPreview = document.getElementById('addIconPreview');
        const iconValue = e.target.value.trim();
        iconPreview.className = iconValue || 'text-gray-400';
    });

    document.getElementById('editMenuModal').addEventListener('click', function(e) {
        if (e.target === this) closeEditMenuModal();
    });

    document.getElementById('addMenuModal').addEventListener('click', function(e) {
        if (e.target === this) closeAddMenuModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEditMenuModal();
            closeAddMenuModal();
        }
    });
</script>

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