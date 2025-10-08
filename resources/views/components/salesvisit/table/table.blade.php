<!-- INDUSTRIES TABLE COMPONENT -->
<div class="fade-in">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-gray-800">Daftar Industri</h2>
        <button onclick="openEditIndustryModal()" 
            class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-all">
            <i class="fas fa-plus mr-1"></i> Tambah Industri
        </button>
    </div>

    <div class="overflow-x-auto bg-white rounded-xl shadow-sm border">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-left font-medium text-gray-500">Nama Industri</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500">Jenis</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500">No. Telepon</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500">Email</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500">Alamat</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500">Kota</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <tr>
                    <td class="px-6 py-4 font-medium text-gray-800">PT Teknologi Nusantara</td>
                    <td class="px-6 py-4 text-gray-600">Teknologi</td>
                    <td class="px-6 py-4 text-gray-600">+62 812-3456-7890</td>
                    <td class="px-6 py-4 text-gray-600">info@nusantara.co.id</td>
                    <td class="px-6 py-4 text-gray-600">Jl. Merdeka No.45</td>
                    <td class="px-6 py-4 text-gray-600">Jakarta</td>
                    <td class="px-6 py-4 flex gap-2">
                        <button class="text-blue-600 hover:text-blue-800" onclick="openEditIndustryModal()">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="text-red-600 hover:text-red-800">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 font-medium text-gray-800">CV Mitra Manufaktur</td>
                    <td class="px-6 py-4 text-gray-600">Manufaktur</td>
                    <td class="px-6 py-4 text-gray-600">+62 813-5555-1212</td>
                    <td class="px-6 py-4 text-gray-600">mitra@factory.co.id</td>
                    <td class="px-6 py-4 text-gray-600">Jl. Industri Raya No.12</td>
                    <td class="px-6 py-4 text-gray-600">Bandung</td>
                    <td class="px-6 py-4 flex gap-2">
                        <button class="text-blue-600 hover:text-blue-800" onclick="openEditIndustryModal()">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="text-red-600 hover:text-red-800">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


