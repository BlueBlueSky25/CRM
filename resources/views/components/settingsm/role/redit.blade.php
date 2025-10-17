<!-- Edit Role Modal -->
<div id="editRoleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden animate-modal-in">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-semibold text-white">Edit Role</h3>
                    <p class="text-indigo-100 text-sm mt-1">Update role details and permissions</p>
                </div>
                <button onclick="closeEditRoleModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body - Scrollable -->
        <div class="overflow-y-auto max-h-[calc(90vh-140px)]">
            <form id="editRoleForm" action="{{ route('roles.update', ['id' => 0]) }}" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" id="editRoleId" name="role_id">
                
                <div>
                    <label for="editRoleName" class="block text-sm font-medium text-gray-700 mb-2">Role Name <span class="text-red-500">*</span></label>
                    <input type="text" id="editRoleName" name="role_name" class="w-full border border-gray-300 rounded-lg pl-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
                </div>
                
                <div>
                    <label for="editRoleDescription" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="editRoleDescription" name="description" rows="3" class="w-full border border-gray-300 rounded-lg pl-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Enter role description..."></textarea>
                </div>
                
                <!-- Form Actions -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeEditRoleModal()" class="px-6 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 transform hover:scale-105 font-medium shadow-md hover:shadow-lg">Update Role</button>
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
    // ========== EDIT ROLE FUNCTIONS ==========
    function openEditRoleModal(roleId, roleName, description) {
        document.getElementById('editRoleId').value = roleId;
        document.getElementById('editRoleName').value = roleName;
        document.getElementById('editRoleDescription').value = description || '';
        document.getElementById('editRoleModal').classList.remove('hidden');
    }

    function closeEditRoleModal() {
        document.getElementById('editRoleModal').classList.add('hidden');
        document.getElementById('editRoleForm').reset();
    }

    // ========== ADD ROLE FUNCTIONS ==========
    function openRoleModal() {
        document.getElementById('addRoleModal').classList.remove('hidden');
    }

    function closeAddRoleModal() {
        document.getElementById('addRoleModal').classList.add('hidden');
        document.getElementById('addRoleForm').reset();
    }

    // ========== ASSIGN MENU FUNCTIONS ==========
    function openAssignMenuModal(roleId, roleName) {
        const modal = document.getElementById('assignMenuModal');
        if (!modal) {
            console.error('assignMenuModal not found!');
            alert('Modal tidak ditemukan. Pastikan assign-menu.blade.php sudah di-include.');
            return;
        }

        // Set role info
        document.getElementById('roleId').value = roleId;
        document.getElementById('roleName').textContent = roleName;
        
        // Load existing permissions
        loadRolePermissions(roleId);
        
        // Update form action
        const form = document.getElementById('assignMenuForm');
        if (form) {
            form.action = `/roles/${roleId}/assign-menu`;
        }
        
        // Show modal
        modal.classList.remove('hidden');
    }

    function closeAssignMenuModal() {
        const modal = document.getElementById('assignMenuModal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    function loadRolePermissions(roleId) {
        // Reset all checkboxes
        document.querySelectorAll('#assignMenuForm input[type="checkbox"]').forEach(cb => {
            cb.checked = false;
        });
        
        // Load from global data
        if (window.rolePermissions && window.rolePermissions[roleId]) {
            const permissions = window.rolePermissions[roleId];
            
            permissions.forEach(perm => {
                const checkbox = document.querySelector(
                    `input[name="menus[${perm.menu_id}][]"][value="${perm.permission_type}"]`
                );
                if (checkbox) {
                    checkbox.checked = true;
                }
            });
        }
    }

    // ========== EVENT LISTENERS ==========
    document.addEventListener('DOMContentLoaded', function() {
        // Edit form submit
        const editForm = document.getElementById('editRoleForm');
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const roleId = document.getElementById('editRoleId').value;
                this.action = `/roles/${roleId}`;
                this.submit();
            });
        }

        // Edit modal outside click
        const editModal = document.getElementById('editRoleModal');
        if (editModal) {
            editModal.addEventListener('click', function(e) {
                if (e.target === this) closeEditRoleModal();
            });
        }

        // Add modal outside click
        const addModal = document.getElementById('addRoleModal');
        if (addModal) {
            addModal.addEventListener('click', function(e) {
                if (e.target === this) closeAddRoleModal();
            });
        }

        // Assign modal outside click
        const assignModal = document.getElementById('assignMenuModal');
        if (assignModal) {
            assignModal.addEventListener('click', function(e) {
                if (e.target === this) closeAssignMenuModal();
            });
        }
    });

    // ESC key to close all modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEditRoleModal();
            closeAddRoleModal();
            closeAssignMenuModal();
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