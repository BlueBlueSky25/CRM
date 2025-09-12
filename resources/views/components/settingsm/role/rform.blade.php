
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