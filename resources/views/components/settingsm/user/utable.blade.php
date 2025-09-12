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