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

            <form action="{{ route('menus.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Menu Name *</label>
                    <input type="text" name="nama_menu" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none" placeholder="Enter menu name" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Route</label>
                    <input type="text" name="route" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none" placeholder="Enter Route (e.g. dashboard)">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Icon</label>
                    <input type="text" name="icon" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none" placeholder="Enter icon class (e.g. fas fa-home)">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Parent Menu</label>
                    <select name="parent_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none">                         
                        <option value="">-- No Parent (Main Menu) --</option>
                        @foreach(App\Models\Menu::whereNull('parent_id')->get() as $menu)
                            <option value="{{ $menu->menu_id }}">{{ $menu->nama_menu }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                    <input type="number" name="order" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none" placeholder="Enter order (e.g. 1)">
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeMenuModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">Cancel</button>                     
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 transition-colors">Save Menu</button>                 
                </div>
            </form>
        </div>
    </div>
</div>