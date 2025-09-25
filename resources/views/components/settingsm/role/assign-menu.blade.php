<!-- Modal for Assign Menu to Role -->
<div id="assignMenuModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden animate-modal-in">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-semibold text-white">Assign Permissions to Role: <span id="roleName" class="font-medium"></span></h3>
                </div>
                <button onclick="closeAssignMenuModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body - Scrollable -->
        <div class="overflow-y-auto max-h-[calc(90vh-140px)] p-6">
            <form id="assignMenuForm" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" id="roleId" name="role_id">
                
                <div class="overflow-y-auto max-h-96">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Menu</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">View</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Create</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Assign</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Edit</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Delete</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($menus as $menu)
                            <tr>
                                <td class="px-6 py-4">
                                    <label class="flex items-center">
                                        <span class="text-sm font-medium text-gray-900">{{ $menu->nama_menu }}</span>
                                    </label>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="checkbox" name="menus[{{ $menu->menu_id }}][]" value="view" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="checkbox" name="menus[{{ $menu->menu_id }}][]" value="create" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="checkbox" name="menus[{{ $menu->menu_id }}][]" value="assign" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="checkbox" name="menus[{{ $menu->menu_id }}][]" value="edit" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="checkbox" name="menus[{{ $menu->menu_id }}][]" value="delete" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeAssignMenuModal()" class="px-6 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 transform hover:scale-105 font-medium shadow-md hover:shadow-lg">Save Permissions</button>
                </div>
            </form>
        </div>
    </div>
</div>