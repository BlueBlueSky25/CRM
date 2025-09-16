<!-- Role Management Table with Fixed Actions -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Role Management</h3>
            <p class="text-sm text-gray-600 mt-1">Manage roles and their assigned permissions</p>
        </div>
        <button onclick="openRoleModal()" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Add New Role</span>
        </button>
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assigned Users</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
           <tbody class="bg-white divide-y divide-gray-200">
    @foreach($roles as $index => $role)
    <tr class="hover:bg-gray-50">
        <td class="px-6 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
        <td class="px-6 py-4 font-medium text-gray-900">{{ $role->role_name }}</td>
        <td class="px-6 py-4 text-sm text-gray-600">{{ $role->description ?? 'No description' }}</td>
        <td class="px-6 py-4 text-sm text-gray-900">
            {{ $role->users->count() }} Users
        </td>
        <td class="px-6 py-4 text-sm font-medium">
            <div class="flex items-center space-x-2">
                @if($role->role_name !== 'superadmin')
                    <!-- Edit Button -->
                    <button onclick="openEditRoleModal('{{ $role->role_id }}', '{{ $role->role_name }}', '{{ $role->description }}')" 
                            class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 transition-colors" 
                            title="Edit Role">
                        <i class="fas fa-edit"></i>
                    </button>
                    
                    <!-- Assign Permissions Button -->
                    <button onclick="openAssignMenuModal({{ $role->role_id }}, '{{ $role->role_name }}')"
                            class="text-green-600 hover:text-green-900 p-2 rounded-lg hover:bg-green-50 transition-colors" 
                            title="Assign Permissions">
                        <i class="fas fa-shield-alt"></i>
                    </button>
                    
                    <!-- Delete Button -->
                    <form action="{{ route('roles.destroy', $role->role_id) }}" method="POST" class="inline-flex">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition-colors" 
                                title="Delete Role" onclick="return confirm('Are you sure you want to delete this role?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                @else
                    <!-- SuperAdmin - Protected -->
                    <span class="text-xs text-gray-500 italic px-3 py-2">🛡️ Protected Role</span>
                @endif
            </div>
        </td>
    </tr>
    @endforeach
</tbody>
        </table>
    </div>
</div>

<!-- Edit Role Modal -->
<div id="editRoleModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-50" style="backdrop-filter: blur(4px);">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-xl shadow-xl max-w-lg w-full" style="animation: modalSlideIn 0.3s ease-out;">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Edit Role</h3>
                    <button onclick="closeEditRoleModal()" class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <form id="editRoleForm" action="{{ route('roles.update', ['id' => 0]) }}" method="POST" class="px-6 py-4">
                @csrf
                @method('PUT')
                <input type="hidden" id="editRoleId" name="role_id">
                
                <div class="space-y-4">
                    <div>
                        <label for="editRoleName" class="block text-sm font-medium text-gray-700 mb-1">Role Name *</label>
                        <input type="text" id="editRoleName" name="role_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none" required>
                    </div>
                    
                    <div>
                        <label for="editRoleDescription" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="editRoleDescription" name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none" placeholder="Enter role description..."></textarea>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeEditRoleModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 transition-colors">
                        Update Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Role Modal -->
<div id="addRoleModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-50" style="backdrop-filter: blur(4px);">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-xl shadow-xl max-w-lg w-full" style="animation: modalSlideIn 0.3s ease-out;">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Add New Role</h3>
                    <button onclick="closeAddRoleModal()" class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <form id="addRoleForm" action="{{ route('roles.store') }}" method="POST" class="px-6 py-4">
                @csrf
                
                <div class="space-y-4">
                    <div>
                        <label for="addRoleName" class="block text-sm font-medium text-gray-700 mb-1">Role Name *</label>
                        <input type="text" id="addRoleName" name="role_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none" placeholder="Enter role name" required>
                    </div>
                    
                    <div>
                        <label for="addRoleDescription" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="addRoleDescription" name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none" placeholder="Enter role description..."></textarea>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeAddRoleModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 transition-colors">
                        Add Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript untuk Modal -->
<script>
    // Function to open edit role modal
    function openEditRoleModal(roleId, roleName, description) {
        document.getElementById('editRoleId').value = roleId;
        document.getElementById('editRoleName').value = roleName;
        document.getElementById('editRoleDescription').value = description || '';
        
        document.getElementById('editRoleModal').classList.remove('hidden');
    }

    // Function to close edit role modal
    function closeEditRoleModal() {
        document.getElementById('editRoleModal').classList.add('hidden');
        document.getElementById('editRoleForm').reset();
    }

    // Function to open add role modal
    function openRoleModal() {
        document.getElementById('addRoleModal').classList.remove('hidden');
    }

    // Function to close add role modal
    function closeAddRoleModal() {
        document.getElementById('addRoleModal').classList.add('hidden');
        document.getElementById('addRoleForm').reset();
    }

    // Handle edit role form submission
    document.getElementById('editRoleForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const roleId = document.getElementById('editRoleId').value;
        const form = document.getElementById('editRoleForm');
        form.action = `/roles/${roleId}`;  // Adjust this URL to match your Laravel route
        form.submit();
    });

    // Close modal when clicking outside
    document.getElementById('editRoleModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditRoleModal();
        }
    });

    document.getElementById('addRoleModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAddRoleModal();
        }
    });

    // Handle ESC key to close modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEditRoleModal();
            closeAddRoleModal();
        }
    });

    // Placeholder for assign menu modal function
    function openAssignMenuModal(roleId, roleName) {
        alert(`Assign permissions for role: ${roleName} (ID: ${roleId})`);
        // This function will be implemented when you create the assign permissions modal
    }
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