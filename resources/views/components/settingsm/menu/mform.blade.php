<!-- Modal for Add New Menu -->
<div id="menuModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden animate-modal-in">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-semibold text-white">Add New Menu</h3>
                    <p class="text-indigo-100 text-sm mt-1">Create a new menu item with details</p>
                </div>
                <button onclick="closeMenuModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body - Scrollable -->
        <div class="overflow-y-auto max-h-[calc(90vh-140px)] p-6">
            <form action="{{ route('menus.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Menu Name <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_menu" class="w-full border border-gray-300 rounded-lg pl-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Enter menu name" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Route</label>
                    <input type="text" name="route" class="w-full border border-gray-300 rounded-lg pl-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Enter Route (e.g. dashboard)">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Icon</label>
                    <input type="text" name="icon" class="w-full border border-gray-300 rounded-lg pl-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Enter icon class (e.g. fas fa-home)">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Parent Menu</label>
                    <select name="parent_id" class="w-full border border-gray-300 rounded-lg pl-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">                         
                        <option value="">-- No Parent (Main Menu) --</option>
                        @foreach(App\Models\Menu::whereNull('parent_id')->get() as $menu)
                            <option value="{{ $menu->menu_id }}">{{ $menu->nama_menu }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                    <input type="number" name="order" class="w-full border border-gray-300 rounded-lg pl-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Enter order (e.g. 1)">
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeMenuModal()" class="px-6 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">Cancel</button>                     
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 transform hover:scale-105 font-medium shadow-md hover:shadow-lg">Save Menu</button>                 
                </div>
            </form>
        </div>
    </div>
</div>