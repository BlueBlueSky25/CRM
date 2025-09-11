<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Privilege Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-gray-900">User Privilege Management</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                            <img src="https://ui-avatars.com/api/?name=Admin&background=3B82F6&color=ffffff" alt="Admin" class="w-8 h-8 rounded-full">
                            <span class="text-sm font-medium">Admin</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900">3</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-tag text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Roles</p>
                        <p class="text-2xl font-bold text-gray-900">3</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shield-alt text-yellow-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Active Permissions</p>
                        <p class="text-2xl font-bold text-gray-900">6</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending Reviews</p>
                        <p class="text-2xl font-bold text-gray-900">2</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" placeholder="Search users..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                        <option>All Roles</option>
                        <option>Super Admin</option>
                        <option>Marketing</option>
                        <option>Sales</option>
                    </select>
                </div>
                <div class="flex items-center space-x-2">
                    <button class="px-4 py-2 text-gray-600 hover:text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-download mr-2"></i>Export
                    </button>
                    <button class="px-4 py-2 text-gray-600 hover:text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- User Management Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">User Management</h3>
                    <p class="text-sm text-gray-600 mt-1">Manage users and their access privileges</p>
                </div>
                <button onclick="openUserModal()" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors flex items-center space-x-2">
                    <i class="fas fa-plus"></i>
                    <span>Add New User</span>
                </button>
            </div>
    
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No.</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Menu Access</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Permission</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">1</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">XieeRizki</div>
                                        <div class="text-sm text-gray-500">xiee.admin@company.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Super Admin</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">All Menus</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Full Access</span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <span class="text-gray-400 text-sm">-</span>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">2</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">Sanca</div>
                                        <div class="text-sm text-gray-500">sanca.sales@company.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Marketing</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">Dashboard, Reports, Users</td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-1">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">View</span>
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Edit</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button class="text-blue-600 hover:text-blue-900" title="Edit User"><i class="fas fa-edit"></i></button>
                                    <button class="text-green-600 hover:text-green-900" title="Manage Permissions"><i class="fas fa-shield-alt"></i></button>
                                    <button class="text-gray-600 hover:text-gray-900" title="Change Status"><i class="fas fa-toggle-on"></i></button>
                                    <button class="text-red-600 hover:text-red-900" title="Delete User"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">3</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">Mbah Upi</div>
                                        <div class="text-sm text-gray-500">upi.marketing@company.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Sales</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">Dashboard, Sales, Customers</td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-1">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">View</span>
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Create</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button class="text-blue-600 hover:text-blue-900" title="Edit User"><i class="fas fa-edit"></i></button>
                                    <button class="text-green-600 hover:text-green-900" title="Manage Permissions"><i class="fas fa-shield-alt"></i></button>
                                    <button class="text-gray-600 hover:text-gray-900" title="Change Status"><i class="fas fa-toggle-on"></i></button>
                                    <button class="text-red-600 hover:text-red-900" title="Delete User"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Role Management Table -->
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
    
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No.</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assigned Users</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Permissions</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">1</td>
                            <td class="px-6 py-4 font-medium text-gray-900">Super Admin</td>
                            <td class="px-6 py-4 text-sm text-gray-600">Full system access</td>
                            <td class="px-6 py-4 text-sm text-gray-900">1 Users</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Manage All</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></button>
                                    <button class="text-green-600 hover:text-green-900"><i class="fas fa-shield-alt"></i></button>
                                    <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">2</td>
                            <td class="px-6 py-4 font-medium text-gray-900">Marketing</td>
                            <td class="px-6 py-4 text-sm text-gray-600">Management level access</td>
                            <td class="px-6 py-4 text-sm text-gray-900">3 Users</td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-1">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">View</span>
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Edit</span>
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Manage</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></button>
                                    <button class="text-green-600 hover:text-green-900"><i class="fas fa-shield-alt"></i></button>
                                    <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">3</td>
                            <td class="px-6 py-4 font-medium text-gray-900">Sales</td>
                            <td class="px-6 py-4 text-sm text-gray-600">Access to sales modules</td>
                            <td class="px-6 py-4 text-sm text-gray-900">7 Users</td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-1">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">View</span>
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Create</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></button>
                                    <button class="text-green-600 hover:text-green-900"><i class="fas fa-shield-alt"></i></button>
                                    <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Menu Management Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Menu Management</h3>
                    <p class="text-sm text-gray-600 mt-1">Define menus and assign them to roles</p>
                </div>
                <button onclick="openMenuModal()" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors flex items-center space-x-2">
                    <i class="fas fa-plus"></i>
                    <span>Add New Menu</span>
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No.</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Menu Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Parent</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Route</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">1</td>
                            <td class="px-6 py-4 font-medium text-gray-900">Dashboard</td>
                            <td class="px-6 py-4 text-sm text-gray-600">-</td>
                            <td class="px-6 py-4"><code class="bg-gray-100 px-2 py-1 rounded text-sm">/dashboard</code></td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></button>
                                    <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">2</td>
                            <td class="px-6 py-4 font-medium text-gray-900">Setting</td>
                            <td class="px-6 py-4 text-sm text-gray-600">-</td>
                            <td class="px-6 py-4"><code class="bg-gray-100 px-2 py-1 rounded text-sm">/users</code></td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></button>
                                    <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">3</td>
                            <td class="px-6 py-4 font-medium text-gray-900">Menu</td>
                            <td class="px-6 py-4 text-sm text-gray-600">2</td>
                            <td class="px-6 py-4"><code class="bg-gray-100 px-2 py-1 rounded text-sm">/sales</code></td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></button>
                                    <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">4</td>
                            <td class="px-6 py-4 font-medium text-gray-900">Users</td>
                            <td class="px-6 py-4 text-sm text-gray-600">2</td>
                            <td class="px-6 py-4"><code class="bg-gray-100 px-2 py-1 rounded text-sm">/reports</code></td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></button>
                                    <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">5</td>
                            <td class="px-6 py-4 font-medium text-gray-900">Ganti Password</td>
                            <td class="px-6 py-4 text-sm text-gray-600">2</td>
                            <td class="px-6 py-4"><code class="bg-gray-100 px-2 py-1 rounded text-sm">/content</code></td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></button>
                                    <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">6</td>
                            <td class="px-6 py-4 font-medium text-gray-900">Refrensi</td>
                            <td class="px-6 py-4 text-sm text-gray-600">-</td>
                            <td class="px-6 py-4"><code class="bg-gray-100 px-2 py-1 rounded text-sm">/settings</code></td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Inactive</span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></button>
                                    <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal for Add New User -->
    <div id="userModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Add New User</h3>
                    <button onclick="closeUserModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <form class="space-y-4" onsubmit="handleUserSubmit(event)">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" id="userName" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none" placeholder="Enter full name" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" id="userEmail" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none" placeholder="Enter email address" required>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <select id="userRole" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none" required>
                            <option value="">Select role</option>
                            <option value="manager">Manager</option>
                            <option value="sales">Sales</option>
                            <option value="support">Support</option>
                            <option value="editor">Editor</option>
                            <option value="viewer">Viewer</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Menu Access</label>
                        <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-3">
                            <label class="flex items-center">
                                <input type="checkbox" name="menus" value="dashboard" class="mr-2"> Dashboard
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="menus" value="users" class="mr-2"> Users
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="menus" value="reports" class="mr-2"> Reports
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="menus" value="sales" class="mr-2"> Sales
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="menus" value="customers" class="mr-2"> Customers
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="menus" value="content" class="mr-2"> Content
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="menus" value="settings" class="mr-2"> Settings
                            </label>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Permissions</label>
                        <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-3">
                            <label class="flex items-center">
                                <input type="checkbox" name="permissions" value="view" class="mr-2"> View
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="permissions" value="create" class="mr-2"> Create
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="permissions" value="edit" class="mr-2"> Edit
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="permissions" value="delete" class="mr-2"> Delete
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="permissions" value="publish" class="mr-2"> Publish
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="permissions" value="manage" class="mr-2"> Manage
                            </label>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeUserModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 transition-colors">Save User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for Add New Role -->
    <div id="roleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Add New Role</h3>
                    <button onclick="closeRoleModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <form class="space-y-4" onsubmit="handleRoleSubmit(event)">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Role Name</label>
                        <input type="text" id="roleName" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none" placeholder="Enter role name" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="roleDescription" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none" rows="3" placeholder="Enter role description"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Permissions</label>
                        <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-3">
                            <label class="flex items-center">
                                <input type="checkbox" name="rolePermissions" value="view_dashboard" class="mr-2"> View Dashboard
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="rolePermissions" value="manage_users" class="mr-2"> Manage Users
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="rolePermissions" value="edit_content" class="mr-2"> Edit Content
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="rolePermissions" value="delete_records" class="mr-2"> Delete Records
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="rolePermissions" value="system_settings" class="mr-2"> System Settings
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="rolePermissions" value="reports_access" class="mr-2"> Reports Access
                            </label>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="roleStatus" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeRoleModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 transition-colors">Save Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for Add New Menu -->
    <div id="menuModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Add New Menu</h3>
                    <button onclick="closeMenuModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <form class="space-y-4" onsubmit="handleMenuSubmit(event)">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Menu Name</label>
                        <input type="text" id="menuName" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none" placeholder="Enter menu name" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Parent Menu</label>
                        <select id="parentMenu" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                            <option value="">Select parent menu (optional)</option>
                            <option value="dashboard">Dashboard</option>
                            <option value="users">Users</option>
                            <option value="sales">Sales</option>
                            <option value="reports">Reports</option>
                            <option value="content">Content</option>
                            <option value="settings">Settings</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Route/URL</label>
                        <input type="text" id="menuRoute" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none" placeholder="e.g., /menu-name" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Icon</label>
                        <input type="text" id="menuIcon" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none" placeholder="e.g., fas fa-home">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                        <input type="number" id="menuOrder" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none" placeholder="Menu order (1, 2, 3...)">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="menuStatus" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeMenuModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 transition-colors">Save Menu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <p class="text-center text-gray-600 text-sm">© 2025 User Privilege Management. All Rights Reserved.</p>
        </div>
    </footer>

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
    </script>
</body>
</html>