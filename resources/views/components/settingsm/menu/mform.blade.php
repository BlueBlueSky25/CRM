<!-- Add Menu Modal -->
<div id="addMenuModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden animate-modal-in">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold text-white">Add New Menu</h3>
                <button onclick="closeAddMenuModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <div class="overflow-y-auto max-h-[calc(90vh-140px)] p-6">
            <form id="addMenuForm" action="{{ route('menus.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div class="space-y-4">
                    <div>
                        <label for="addMenuName" class="block text-sm font-medium text-gray-700 mb-1">Menu Name <span class="text-red-500">*</span></label>
                        <input type="text" id="addMenuName" name="nama_menu" class="w-full border border-gray-300 rounded-lg pl-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Enter menu name" required>
                    </div>
                    
                    <div>
                        <label for="addMenuRoute" class="block text-sm font-medium text-gray-700 mb-1">Route</label>
                        <input type="text" id="addMenuRoute" name="route" class="w-full border border-gray-300 rounded-lg pl-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="e.g., users.index">
                    </div>

                    <div>
                        <label for="addMenuOrder" class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                        <input type="number" id="addMenuOrder" name="order" class="w-full border border-gray-300 rounded-lg pl-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Order Number">
                    </div>

                    <div>
                        <label for="addMenuIcon" class="block text-sm font-medium text-gray-700 mb-1">Icon</label>
                        <div class="relative">
                            <input type="text" id="addMenuIcon" name="icon" class="w-full border border-gray-300 rounded-lg pl-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="e.g., fas fa-users">
                            <div class="absolute right-3 top-2.5">
                                <i id="addIconPreview" class="text-gray-400"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Use FontAwesome classes (e.g., fas fa-users)</p>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeAddMenuModal()" class="px-6 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 transform hover:scale-105 font-medium shadow-md hover:shadow-lg">Add Menu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openMenuModal() {
        document.getElementById('addMenuModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeAddMenuModal() {
        document.getElementById('addMenuModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('addMenuForm').reset();
        document.getElementById('addIconPreview').className = 'text-gray-400';
    }

    // Icon preview untuk add modal
    document.addEventListener('DOMContentLoaded', function() {
        const addIconInput = document.getElementById('addMenuIcon');
        if (addIconInput) {
            addIconInput.addEventListener('input', function(e) {
                const iconPreview = document.getElementById('addIconPreview');
                const iconValue = e.target.value.trim();
                iconPreview.className = iconValue || 'text-gray-400';
            });
        }
    });

    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('addMenuModal');
        if (e.target === modal) {
            closeAddMenuModal();
        }
    });

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('addMenuModal');
            if (modal && !modal.classList.contains('hidden')) {
                closeAddMenuModal();
            }
        }
    });
</script>