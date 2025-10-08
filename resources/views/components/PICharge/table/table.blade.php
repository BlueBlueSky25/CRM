<!-- COMPONENT: PIC TABLE -->
<div class="fade-in">
    <div class="overflow-x-auto bg-white rounded-xl shadow-sm border">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-left font-medium text-gray-500">Nama PIC</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500">Jabatan</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500">Email</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500">Telepon</th>
                    <th class="px-6 py-3 text-center font-medium text-gray-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <tr>
                    <td class="px-6 py-4">Andi Saputra</td>
                    <td class="px-6 py-4">Manager</td>
                    <td class="px-6 py-4">andi@example.com</td>
                    <td class="px-6 py-4">0812-3456-7890</td>
                    <td class="px-6 py-4 flex justify-center gap-3">
                        <button class="text-blue-600 hover:text-blue-800" 
                            onclick="openEditPICModal('Andi Saputra', 'Manager', 'andi@example.com', '0812-3456-7890')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="text-red-600 hover:text-red-800" onclick="alert('Hapus masih dummy 😎')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4">Budi Santoso</td>
                    <td class="px-6 py-4">Supervisor</td>
                    <td class="px-6 py-4">budi@example.com</td>
                    <td class="px-6 py-4">0813-2222-3333</td>
                    <td class="px-6 py-4 flex justify-center gap-3">
                        <button class="text-blue-600 hover:text-blue-800"
                            onclick="openEditPICModal('Budi Santoso', 'Supervisor', 'budi@example.com', '0813-2222-3333')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="text-red-600 hover:text-red-800" onclick="alert('Hapus masih dummy 😎')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

