@props(['currentMenuId' => 10])

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden fade-in">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">PIC Management</h3>
            <p class="text-sm text-gray-600 mt-1">Kelola data Person In Charge dan informasinya</p>
        </div>
        <button onclick="openPICModal()" 
            class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
            <i class="fas fa-plus"></i>
            Tambah PIC
        </button>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table id="picTable" class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Nama PIC</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Jabatan</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Telepon</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Perusahaan</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <!-- Sample Data 1 -->
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-sm text-gray-900">1</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="h-10 w-10 flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <i class="fas fa-user-tie text-indigo-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">Andi Saputra</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            Manager
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="mailto:andi@example.com" class="text-indigo-600 hover:text-indigo-900 flex items-center gap-1 text-sm">
                            <i class="fas fa-envelope text-xs"></i>
                            andi@example.com
                        </a>
                    </td>
                    <td class="px-6 py-4">
                        <a href="tel:0812-3456-7890" class="text-indigo-600 hover:text-indigo-900 flex items-center gap-1 text-sm">
                            <i class="fas fa-phone text-xs"></i>
                            0812-3456-7890
                        </a>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-building text-gray-400 text-xs"></i>
                            <span class="text-gray-900">PT Maju Jaya</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <button onclick="openEditPICModal('Andi Saputra', 'Manager', 'andi@example.com', '0812-3456-7890', '1')" 
                                class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 flex items-center transition-colors" 
                                title="Edit PIC">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deletePIC(1)" 
                                class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 flex items-center transition-colors" 
                                title="Hapus PIC">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                
                <!-- Sample Data 2 -->
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-sm text-gray-900">2</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="h-10 w-10 flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <i class="fas fa-user-tie text-indigo-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">Budi Santoso</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            Supervisor
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="mailto:budi@example.com" class="text-indigo-600 hover:text-indigo-900 flex items-center gap-1 text-sm">
                            <i class="fas fa-envelope text-xs"></i>
                            budi@example.com
                        </a>
                    </td>
                    <td class="px-6 py-4">
                        <a href="tel:0813-2222-3333" class="text-indigo-600 hover:text-indigo-900 flex items-center gap-1 text-sm">
                            <i class="fas fa-phone text-xs"></i>
                            0813-2222-3333
                        </a>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-building text-gray-400 text-xs"></i>
                            <span class="text-gray-900">CV Sejahtera</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <button onclick="openEditPICModal('Budi Santoso', 'Supervisor', 'budi@example.com', '0813-2222-3333', '2')" 
                                class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 flex items-center transition-colors" 
                                title="Edit PIC">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deletePIC(2)" 
                                class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 flex items-center transition-colors" 
                                title="Hapus PIC">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { 
        opacity: 0; 
        transform: translateY(10px);
    }
    to { 
        opacity: 1; 
        transform: translateY(0);
    }
}

.fade-in {
    animation: fadeIn 0.3s ease-in;
}

#picTable tbody tr {
    transition: all 0.2s ease;
}

#picTable tbody tr:hover {
    background-color: #f9fafb;
    transform: scale(1.001);
}

#picTable button {
    transition: all 0.2s ease;
}

#picTable button:hover {
    transform: translateY(-1px);
}

.h-10.w-10.rounded-full {
    transition: all 0.3s ease;
}

#picTable tbody tr:hover .h-10.w-10.rounded-full {
    transform: scale(1.1);
    box-shadow: 0 4px 6px rgba(79, 70, 229, 0.2);
}
</style>

<script>
function deletePIC(id) {
    if (confirm('Yakin ingin menghapus PIC ini?')) {
        alert('PIC ID ' + id + ' dihapus! (Backend belum ada)');
        // location.reload(); // Uncomment ketika backend sudah siap
    }
}
</script>