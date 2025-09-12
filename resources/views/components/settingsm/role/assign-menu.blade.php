<!-- Modal for Assign Menu to Role -->
<div id="assignMenuModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Assign Menu to Role: <span id="roleName"></span></h3>
                <button onclick="closeAssignMenuModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="assignMenuForm" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" id="roleId" name="role_id">
                
                <div class="overflow-y-auto max-h-96">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Menu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">View</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Create</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Edit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Delete</th>
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
                    <button type="button" onclick="closeAssignMenuModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 transition-colors">Save Permissions</button>
                </div>
            </form>
        </div>
    </div>
</div>