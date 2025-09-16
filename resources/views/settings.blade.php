<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Privilege Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#64748B'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <x-settingsm.header />
    
    <div>
        <!-- Stats Cards -->
        <x-settingsm.kpi />
        <x-settingsm.filtersearch />
    </div>

    <main>
        @yield('content')
    </main>





    <!-- Footer -->
    <x-settingsm.footer />
    
        

        <script>
        // Set data permissions dari PHP ke JavaScript
        window.rolePermissions = @json($rolePermissions ?? []);
        </script> 

    <script>
        // Modal functions for User
        function openUserModal() {
            const modal = document.getElementById('userModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeUserModal() {
            const modal = document.getElementById('userModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            // Clear form
            document.getElementById('userName').value = '';
            document.getElementById('userEmail').value = '';
            document.getElementById('userRole').value = '';
            // Uncheck all checkboxes
            const menuCheckboxes = document.querySelectorAll('input[name="menus"]');
            menuCheckboxes.forEach(checkbox => checkbox.checked = false);
            const permissionCheckboxes = document.querySelectorAll('input[name="permissions"]');
            permissionCheckboxes.forEach(checkbox => checkbox.checked = false);
        }

        // Modal functions for Role
        function openRoleModal() {
            const modal = document.getElementById('roleModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeRoleModal() {
            const modal = document.getElementById('roleModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            // Clear form
            document.getElementById('roleName').value = '';
            document.getElementById('roleDescription').value = '';
            document.getElementById('roleStatus').value = 'active';
            // Uncheck all permissions
            const checkboxes = document.querySelectorAll('input[name="rolePermissions"]');
            checkboxes.forEach(checkbox => checkbox.checked = false);
        }

        // Modal functions for Menu
        function openMenuModal() {
            const modal = document.getElementById('menuModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeMenuModal() {
            const modal = document.getElementById('menuModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            // Clear form
            document.getElementById('menuName').value = '';
            document.getElementById('parentMenu').value = '';
            document.getElementById('menuRoute').value = '';
            document.getElementById('menuIcon').value = '';
            document.getElementById('menuOrder').value = '';
            document.getElementById('menuStatus').value = 'active';
        }

        // Form submission handlers
        function handleUserSubmit(event) {
            event.preventDefault();
            
            const userName = document.getElementById('userName').value;
            const userEmail = document.getElementById('userEmail').value;
            const userRole = document.getElementById('userRole').value;
            const menus = Array.from(document.querySelectorAll('input[name="menus"]:checked')).map(cb => cb.value);
            const permissions = Array.from(document.querySelectorAll('input[name="permissions"]:checked')).map(cb => cb.value);
            
            // Here you would typically send data to server
            console.log('User Data:', {
                name: userName,
                email: userEmail,
                role: userRole,
                menus: menus,
                permissions: permissions
            });
            
            alert(`User "${userName}" has been created successfully!`);
            closeUserModal();
        }

        function handleRoleSubmit(event) {
            event.preventDefault();
            
            const roleName = document.getElementById('roleName').value;
            const roleDescription = document.getElementById('roleDescription').value;
            const roleStatus = document.getElementById('roleStatus').value;
            const permissions = Array.from(document.querySelectorAll('input[name="rolePermissions"]:checked')).map(cb => cb.value);
            
            // Here you would typically send data to server
            console.log('Role Data:', {
                name: roleName,
                description: roleDescription,
                status: roleStatus,
                permissions: permissions
            });
            
            alert(`Role "${roleName}" has been created successfully!`);
            closeRoleModal();
        }

        function handleMenuSubmit(event) {
            event.preventDefault();
            
            const menuName = document.getElementById('menuName').value;
            const parentMenu = document.getElementById('parentMenu').value;
            const menuRoute = document.getElementById('menuRoute').value;
            const menuIcon = document.getElementById('menuIcon').value;
            const menuOrder = document.getElementById('menuOrder').value;
            const menuStatus = document.getElementById('menuStatus').value;
            
            // Here you would typically send data to server
            console.log('Menu Data:', {
                name: menuName,
                parent: parentMenu,
                route: menuRoute,
                icon: menuIcon,
                order: menuOrder,
                status: menuStatus
            });
            
            alert(`Menu "${menuName}" has been created successfully!`);
            closeMenuModal();
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const userModal = document.getElementById('userModal');
            const roleModal = document.getElementById('roleModal');
            const menuModal = document.getElementById('menuModal');
            
            if (event.target === userModal) {
                closeUserModal();
            }
            if (event.target === roleModal) {
                closeRoleModal();
            }
            if (event.target === menuModal) {
                closeMenuModal();
            }
        }

        // Escape key to close modals
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeUserModal();
                closeRoleModal();
                closeMenuModal();
            }
        });

        // Additional utility functions
        function toggleUserStatus(userId) {
            // Function to toggle user active/inactive status
            console.log('Toggle status for user:', userId);
            // Implementation would go here
        }

        function editUser(userId) {
            // Function to edit user details
            console.log('Edit user:', userId);
            // Implementation would go here
        }

        function deleteUser(userId) {
            // Function to delete user
            if (confirm('Are you sure you want to delete this user?')) {
                console.log('Delete user:', userId);
                // Implementation would go here
            }
        }

        function manageUserPermissions(userId) {
            // Function to manage specific user permissions
            console.log('Manage permissions for user:', userId);
            // Implementation would go here
        }

        // Search functionality
        function searchUsers() {
            const searchInput = document.querySelector('input[placeholder="Search users..."]');
            const searchTerm = searchInput.value.toLowerCase();
            const tableRows = document.querySelectorAll('#userTable tbody tr');
            
            tableRows.forEach(row => {
                const userName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const userEmail = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const userRole = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                
                if (userName.includes(searchTerm) || userEmail.includes(searchTerm) || userRole.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Filter functionality
        function filterByRole() {
            const roleFilter = document.querySelector('select');
            const selectedRole = roleFilter.value.toLowerCase();
            const tableRows = document.querySelectorAll('#userTable tbody tr');
            
            tableRows.forEach(row => {
                if (selectedRole === 'all roles' || selectedRole === '') {
                    row.style.display = '';
                } else {
                    const userRole = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    if (userRole.includes(selectedRole)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        }

        // Export functionality
        function exportData() {
            console.log('Exporting data...');
            // Implementation for exporting data would go here
            alert('Export functionality would be implemented here');
        }

        // Initialize event listeners when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Add search functionality
            const searchInput = document.querySelector('input[placeholder="Search users..."]');
            if (searchInput) {
                searchInput.addEventListener('input', searchUsers);
            }

            // Add filter functionality
            const roleFilter = document.querySelector('select');
            if (roleFilter) {
                roleFilter.addEventListener('change', filterByRole);
            }

            // Add export functionality
            const exportBtn = document.querySelector('button:contains("Export")');
            if (exportBtn) {
                exportBtn.addEventListener('click', exportData);
            }
        });

        // Modal functions for User
        function openUserModal() {
            const modal = document.getElementById('userModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeUserModal() {
            const modal = document.getElementById('userModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            // Clear form
            document.getElementById('userName').value = '';
            document.getElementById('userEmail').value = '';
            document.getElementById('userRole').value = '';
            // Uncheck all checkboxes
            const menuCheckboxes = document.querySelectorAll('input[name="menus"]');
            menuCheckboxes.forEach(checkbox => checkbox.checked = false);
            const permissionCheckboxes = document.querySelectorAll('input[name="permissions"]');
            permissionCheckboxes.forEach(checkbox => checkbox.checked = false);
        }

        // Modal functions for Role
        function openRoleModal() {
            const modal = document.getElementById('roleModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeRoleModal() {
            const modal = document.getElementById('roleModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            // Clear form
            document.getElementById('roleName').value = '';
            document.getElementById('roleDescription').value = '';
            document.getElementById('roleStatus').value = 'active';
            // Uncheck all permissions
            const checkboxes = document.querySelectorAll('input[name="rolePermissions"]');
            checkboxes.forEach(checkbox => checkbox.checked = false);
        }

        // Modal functions for Menu
        function openMenuModal() {
            const modal = document.getElementById('menuModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeMenuModal() {
            const modal = document.getElementById('menuModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            // Clear form
            document.getElementById('menuName').value = '';
            document.getElementById('parentMenu').value = '';
            document.getElementById('menuRoute').value = '';
            document.getElementById('menuIcon').value = '';
            document.getElementById('menuOrder').value = '';
            document.getElementById('menuStatus').value = 'active';
        }

        // Data permissions dari controller (ini perlu diset dari PHP)
        const rolePermissions = window.rolePermissions || {};

        // Modal functions for Assign Menu
        function openAssignMenuModal(roleId, roleName) {
            const modal = document.getElementById('assignMenuModal');
            const form = document.getElementById('assignMenuForm');
            
            // Set role information
            document.getElementById('roleId').value = roleId;
            document.getElementById('roleName').textContent = roleName;
            form.action = `/roles/${roleId}/assign-menu`;
            
            // Reset semua checkbox terlebih dahulu
            resetAllCheckboxes();
            
            // Load permissions yang sudah ada untuk role ini
            loadExistingPermissions(roleId);
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeAssignMenuModal() {
            const modal = document.getElementById('assignMenuModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            
            // Reset form dan checkboxes
            document.getElementById('assignMenuForm').reset();
            resetAllCheckboxes();
        }

        function resetAllCheckboxes() {
            // Reset semua checkbox di modal assign menu
            const checkboxes = document.querySelectorAll('#assignMenuModal input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
        }

        function loadExistingPermissions(roleId) {
            // Ambil permissions untuk role ini dari data yang sudah ada
            const permissions = rolePermissions[roleId] || [];
            
            // Set checkbox berdasarkan permissions yang sudah ada
            permissions.forEach(permission => {
                const checkbox = document.querySelector(
                    `input[name="menus[${permission.menu_id}][]"][value="${permission.permission_type}"]`
                );
                if (checkbox) {
                    checkbox.checked = true;
                }
            });
        }

        // Form submission handlers
        function handleUserSubmit(event) {
            event.preventDefault();
            
            const userName = document.getElementById('userName').value;
            const userEmail = document.getElementById('userEmail').value;
            const userRole = document.getElementById('userRole').value;
            const menus = Array.from(document.querySelectorAll('input[name="menus"]:checked')).map(cb => cb.value);
            const permissions = Array.from(document.querySelectorAll('input[name="permissions"]:checked')).map(cb => cb.value);
            
            // Here you would typically send data to server
            console.log('User Data:', {
                name: userName,
                email: userEmail,
                role: userRole,
                menus: menus,
                permissions: permissions
            });
            
            alert(`User "${userName}" has been created successfully!`);
            closeUserModal();
        }

        function handleRoleSubmit(event) {
            event.preventDefault();
            
            const roleName = document.getElementById('roleName').value;
            const roleDescription = document.getElementById('roleDescription').value;
            const roleStatus = document.getElementById('roleStatus').value;
            const permissions = Array.from(document.querySelectorAll('input[name="rolePermissions"]:checked')).map(cb => cb.value);
            
            // Here you would typically send data to server
            console.log('Role Data:', {
                name: roleName,
                description: roleDescription,
                status: roleStatus,
                permissions: permissions
            });
            
            alert(`Role "${roleName}" has been created successfully!`);
            closeRoleModal();
        }

        function handleMenuSubmit(event) {
            event.preventDefault();
            
            const menuName = document.getElementById('menuName').value;
            const parentMenu = document.getElementById('parentMenu').value;
            const menuRoute = document.getElementById('menuRoute').value;
            const menuIcon = document.getElementById('menuIcon').value;
            const menuOrder = document.getElementById('menuOrder').value;
            const menuStatus = document.getElementById('menuStatus').value;
            
            // Here you would typically send data to server
            console.log('Menu Data:', {
                name: menuName,
                parent: parentMenu,
                route: menuRoute,
                icon: menuIcon,
                order: menuOrder,
                status: menuStatus
            });
            
            alert(`Menu "${menuName}" has been created successfully!`);
            closeMenuModal();
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const userModal = document.getElementById('userModal');
            const roleModal = document.getElementById('roleModal');
            const menuModal = document.getElementById('menuModal');
            const assignMenuModal = document.getElementById('assignMenuModal');
            
            if (event.target === userModal) {
                closeUserModal();
            }
            if (event.target === roleModal) {
                closeRoleModal();
            }
            if (event.target === menuModal) {
                closeMenuModal();
            }
            if (event.target === assignMenuModal) {
                closeAssignMenuModal();
            }
        }

        // Escape key to close modals
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeUserModal();
                closeRoleModal();
                closeMenuModal();
                closeAssignMenuModal();
            }
        });

        // Additional utility functions
        function toggleUserStatus(userId) {
            // Function to toggle user active/inactive status
            console.log('Toggle status for user:', userId);
            // Implementation would go here
        }

        function editUser(userId) {
            // Function to edit user details
            console.log('Edit user:', userId);
            // Implementation would go here
        }

        function deleteUser(userId) {
            // Function to delete this user
            if (confirm('Are you sure you want to delete this user?')) {
                console.log('Delete user:', userId);
                // Implementation would go here
            }
        }

        function manageUserPermissions(userId) {
            // Function to manage specific user permissions
            console.log('Manage permissions for user:', userId);
            // Implementation would go here
        }

        // Search functionality
        function searchUsers() {
            const searchInput = document.querySelector('input[placeholder="Search users..."]');
            const searchTerm = searchInput.value.toLowerCase();
            const tableRows = document.querySelectorAll('#userTable tbody tr');
            
            tableRows.forEach(row => {
                const userName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const userEmail = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const userRole = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                
                if (userName.includes(searchTerm) || userEmail.includes(searchTerm) || userRole.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Filter functionality
        function filterByRole() {
            const roleFilter = document.querySelector('select');
            const selectedRole = roleFilter.value.toLowerCase();
            const tableRows = document.querySelectorAll('#userTable tbody tr');
            
            tableRows.forEach(row => {
                if (selectedRole === 'all roles' || selectedRole === '') {
                    row.style.display = '';
                } else {
                    const userRole = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    if (userRole.includes(selectedRole)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        }

        // Export functionality
        function exportData() {
            console.log('Exporting data...');
            // Implementation for exporting data would go here
            alert('Export functionality would be implemented here');
        }

        // Initialize event listeners when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Add search functionality
            const searchInput = document.querySelector('input[placeholder="Search users..."]');
            if (searchInput) {
                searchInput.addEventListener('input', searchUsers);
            }

            // Add filter functionality
            const roleFilter = document.querySelector('select');
            if (roleFilter) {
                roleFilter.addEventListener('change', filterByRole);
            }

            // Add export functionality
            const exportBtn = document.querySelector('button:contains("Export")');
            if (exportBtn) {
                exportBtn.addEventListener('click', exportData);
            }
        });
    </script>
</body>
</html>

