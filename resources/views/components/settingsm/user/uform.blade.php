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
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input type="password" id="userPassword" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none" placeholder="Enter User Password" required>
                        </div>
                    </div>
                    
                    <!-- <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <select id="userRole" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none" required>
                            <option value="">Select role</option>
                            <option value="manager">Manager</option>
                            <option value="sales">Sales</option>
                            <option value="support">Support</option>
                            <option value="editor">Editor</option>
                            <option value="viewer">Viewer</option>
                        </select>
                    </div> -->
                    
                    <!-- <div>
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
                    </div> -->
                    
                    <!-- <div>
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
                    </div> -->
                    
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeUserModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 transition-colors">Save User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>