@props(['salesUsers', 'provinces', 'currentMenuId'])

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden fade-in">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Sales Management</h3>
            <p class="text-sm text-gray-600 mt-1">Kelola data sales dan informasinya</p>
        </div>
        @if(auth()->user()->canAccess($currentMenuId, 'create'))
        <button onclick="openAddSalesModal()" 
            class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
            <i class="fas fa-plus"></i>
            Tambah Sales
        </button>
        @endif
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Birth Date</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Address</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Roles</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($salesUsers as $index => $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $user->username }}</div>
                                <div class="text-sm text-gray-500">{{ $user->email ?? 'No email' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $user->phone ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ $user->birth_date ? date('d M Y', strtotime($user->birth_date)) : '-' }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">
                            @if($user->province || $user->regency || $user->district || $user->village || $user->address)
                                @php
                                    $alamatWilayah = collect([
                                        $user->village->name ?? null,
                                        $user->district->name ?? null, 
                                        $user->regency->name ?? null,
                                        $user->province->name ?? null
                                    ])->filter()->implode(', ');
                                @endphp
                                
                                @if($alamatWilayah)
                                    <div class="font-medium">{{ $alamatWilayah }}</div>
                                    @if($user->address)
                                        <div class="text-xs text-gray-600 mt-1">{{ $user->address }}</div>
                                    @endif
                                @else
                                    {{ $user->address ?? '-' }}
                                @endif
                            @else
                                -
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $user->role->role_name ?? 'No Role' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            @if(auth()->user()->canAccess($currentMenuId, 'edit'))
                            <button onclick="openEditSalesModal('{{ $user->user_id }}', '{{ $user->username }}', '{{ $user->email }}', '{{ $user->phone }}', '{{ $user->birth_date }}', '{{ $user->address }}', '{{ $user->province_id }}', '{{ $user->regency_id }}', '{{ $user->district_id }}', '{{ $user->village_id }}')" 
                                class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 flex items-center" 
                                title="Edit User">
                                <i class="fas fa-edit"></i>
                            </button>
                            @endif
                            
                            @if(auth()->user()->canAccess($currentMenuId, 'delete'))
                            <form action="{{ route('marketing.sales.destroy', $user->user_id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 p-2 flex items-center" title="Hapus User" onclick="return confirm('Yakin ingin menghapus user ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Add Sales -->
<div id="addSalesModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden animate-modal-in">
        <!-- Header -->
        <div style="background: linear-gradient(to right, #4f46e5, #7c3aed); padding: 1.25rem 1.5rem;">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-semibold text-white">Tambah Marketing Baru</h3>
                    <p class="text-sm text-indigo-100 mt-1">Lengkapi formulir berikut untuk menambahkan marketing</p>
                </div>
                <button onclick="closeAddSalesModal()" 
                    class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Body -->
        <div class="overflow-y-auto max-h-[calc(90vh-140px)]" style="background-color: #f3f4f6; padding: 1.5rem;">
            <form id="addSalesForm" action="{{ route('marketing.sales.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 1rem;">
                @csrf
                
                <!-- Nama -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Nama Lengkap <span style="color: #ef4444;">*</span>
                    </label>
                    <div style="position: relative;">
                        <i class="fas fa-user" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
                        <input type="text" 
                            name="username" 
                            style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem 0.75rem 2.5rem; font-size: 0.875rem;" 
                            required>
                    </div>
                </div>
                
                <!-- Email -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Email <span style="color: #ef4444;">*</span>
                    </label>
                    <div style="position: relative;">
                        <i class="fas fa-envelope" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
                        <input type="email" 
                            name="email" 
                            style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem 0.75rem 2.5rem; font-size: 0.875rem;" 
                            required>
                    </div>
                </div>

                <!-- Phone -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        No. Telepon
                    </label>
                    <div style="position: relative;">
                        <i class="fas fa-phone" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
                        <input type="text" 
                            name="phone" 
                            style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem 0.75rem 2.5rem; font-size: 0.875rem;">
                    </div>
                </div>

                <!-- Birth Date -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Tanggal Lahir
                    </label>
                    <div style="position: relative;">
                        <i class="fas fa-calendar" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
                        <input type="date" 
                            name="birth_date" 
                            style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem 0.75rem 2.5rem; font-size: 0.875rem;"
                            max="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <!-- Provinsi -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Provinsi <span style="color: #ef4444;">*</span>
                    </label>
                    <select id="add_province" 
                            name="province" 
                            style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.875rem;" 
                            required>
                        <option value="">-- Pilih Provinsi --</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province->id }}">{{ $province->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Kabupaten -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Kabupaten/Kota <span style="color: #ef4444;">*</span>
                    </label>
                    <select id="add_regency" 
                            name="regency" 
                            style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.875rem;" 
                            required disabled>
                        <option value="">-- Pilih Kabupaten/Kota --</option>
                    </select>
                </div>

                <!-- Kecamatan -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Kecamatan <span style="color: #ef4444;">*</span>
                    </label>
                    <select id="add_district" 
                            name="district" 
                            style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.875rem;" 
                            required disabled>
                        <option value="">-- Pilih Kecamatan --</option>
                    </select>
                </div>

                <!-- Kelurahan -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Kelurahan/Desa <span style="color: #ef4444;">*</span>
                    </label>
                    <select id="add_village" 
                            name="village" 
                            style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.875rem;" 
                            required disabled>
                        <option value="">-- Pilih Kelurahan/Desa --</option>
                    </select>
                </div>

                <!-- Alamat -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Alamat Lengkap
                    </label>
                    <div style="position: relative;">
                        <i class="fas fa-map-marker-alt" style="position: absolute; left: 0.75rem; top: 1rem; color: #9ca3af;"></i>
                        <textarea name="address" 
                                  rows="3" 
                                  style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem 0.75rem 2.5rem; font-size: 0.875rem; resize: none;" 
                                  placeholder="Masukkan alamat detail..."></textarea>
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Password <span style="color: #ef4444;">*</span>
                    </label>
                    <div style="position: relative;">
                        <i class="fas fa-lock" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
                        <input type="password" 
                            name="password" 
                            style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem 0.75rem 2.5rem; font-size: 0.875rem;" 
                            required>
                    </div>
                </div>
                
                <!-- Tombol -->
                <div style="display: flex; justify-content: flex-end; gap: 0.75rem; padding-top: 1rem; border-top: 1px solid #e5e7eb; margin-top: 1.5rem;">
                    <button type="button" 
                            onclick="closeAddSalesModal()" 
                            style="background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.625rem 1.5rem; font-weight: 500; font-size: 0.875rem; cursor: pointer; transition: all 0.2s;">
                        Batal
                    </button>
                    <button type="submit" 
                            style="background-color: #4f46e5; color: white; border: none; border-radius: 0.5rem; padding: 0.625rem 1.5rem; font-weight: 500; font-size: 0.875rem; cursor: pointer; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); transition: all 0.2s;">
                        <i class="fas fa-save" style="margin-right: 0.5rem;"></i>
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Sales -->
<div id="editSalesModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden animate-modal-in">
        <!-- Header -->
        <div style="background: linear-gradient(to right, #4f46e5, #7c3aed); padding: 1.25rem 1.5rem;">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-semibold text-white">Edit Marketing</h3>
                    <p class="text-sm text-indigo-100 mt-1">Perbarui data marketing di formulir berikut</p>
                </div>
                <button onclick="closeEditSalesModal()" 
                    class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Body -->
        <div class="overflow-y-auto max-h-[calc(90vh-140px)]" style="background-color: #f3f4f6; padding: 1.5rem;">
            <form id="editSalesForm" action="#" method="POST" style="display: flex; flex-direction: column; gap: 1rem;">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_user_id" name="user_id">
                
                <!-- Nama -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Nama Lengkap <span style="color: #ef4444;">*</span>
                    </label>
                    <div style="position: relative;">
                        <i class="fas fa-user" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
                        <input type="text" 
                            id="edit_username" 
                            name="username" 
                            style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem 0.75rem 2.5rem; font-size: 0.875rem;" 
                            required>
                    </div>
                </div>
                
                <!-- Email -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Email <span style="color: #ef4444;">*</span>
                    </label>
                    <div style="position: relative;">
                        <i class="fas fa-envelope" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
                        <input type="email" 
                            id="edit_email" 
                            name="email" 
                            style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem 0.75rem 2.5rem; font-size: 0.875rem;" 
                            required>
                    </div>
                </div>

                <!-- Phone -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        No. Telepon
                    </label>
                    <div style="position: relative;">
                        <i class="fas fa-phone" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
                        <input type="text" 
                            id="edit_phone" 
                            name="phone" 
                            style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem 0.75rem 2.5rem; font-size: 0.875rem;">
                    </div>
                </div>

                <!-- Birth Date -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Tanggal Lahir
                    </label>
                    <div style="position: relative;">
                        <i class="fas fa-calendar" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
                        <input type="date" 
                            id="edit_birth_date" 
                            name="birth_date" 
                            style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem 0.75rem 2.5rem; font-size: 0.875rem;"
                            max="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <!-- Provinsi -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Provinsi <span style="color: #ef4444;">*</span>
                    </label>
                    <select id="edit_province" 
                            name="province" 
                            style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.875rem;" 
                            required>
                        <option value="">-- Pilih Provinsi --</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province->id }}">{{ $province->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Kabupaten -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Kabupaten/Kota <span style="color: #ef4444;">*</span>
                    </label>
                    <select id="edit_regency" 
                            name="regency" 
                            style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.875rem;" 
                            required>
                        <option value="">-- Pilih Kabupaten/Kota --</option>
                    </select>
                </div>

                <!-- Kecamatan -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Kecamatan <span style="color: #ef4444;">*</span>
                    </label>
                    <select id="edit_district" 
                            name="district" 
                            style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.875rem;" 
                            required>
                        <option value="">-- Pilih Kecamatan --</option>
                    </select>
                </div>

                <!-- Kelurahan -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Kelurahan/Desa <span style="color: #ef4444;">*</span>
                    </label>
                    <select id="edit_village" 
                            name="village" 
                            style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.875rem;" 
                            required>
                        <option value="">-- Pilih Kelurahan/Desa --</option>
                    </select>
                </div>

                <!-- Alamat -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Alamat Lengkap
                    </label>
                    <div style="position: relative;">
                        <i class="fas fa-map-marker-alt" style="position: absolute; left: 0.75rem; top: 1rem; color: #9ca3af;"></i>
                        <textarea id="edit_address" 
                                  name="address" 
                                  rows="3" 
                                  style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem 0.75rem 2.5rem; font-size: 0.875rem; resize: none;" 
                                  placeholder="Masukkan alamat detail..."></textarea>
                    </div>
                </div>
                
                <!-- Tombol -->
                <div style="display: flex; justify-content: flex-end; gap: 0.75rem; padding-top: 1rem; border-top: 1px solid #e5e7eb; margin-top: 1.5rem;">
                    <button type="button" 
                            onclick="closeEditSalesModal()" 
                            style="background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.625rem 1.5rem; font-weight: 500; font-size: 0.875rem; cursor: pointer; transition: all 0.2s;">
                        Batal
                    </button>
                    <button type="submit" 
                            style="background-color: #4f46e5; color: white; border: none; border-radius: 0.5rem; padding: 0.625rem 1.5rem; font-weight: 500; font-size: 0.875rem; cursor: pointer; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); transition: all 0.2s;">
                        <i class="fas fa-save" style="margin-right: 0.5rem;"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Modal Add Sales
function openAddSalesModal() {
    document.getElementById('addSalesModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAddSalesModal() {
    document.getElementById('addSalesModal').classList.add('hidden');
    document.getElementById('addSalesForm').reset();
    document.body.style.overflow = 'auto';
}

// Modal Edit Sales
function openEditSalesModal(user_id, username, email, phone, birth_date, address, province_id, regency_id, district_id, village_id) {
    document.getElementById('edit_user_id').value = user_id;
    document.getElementById('edit_username').value = username;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_phone').value = phone ?? '';
    document.getElementById('edit_birth_date').value = birth_date ?? '';
    document.getElementById('edit_address').value = address ?? '';
    
    // Set province and trigger cascade
    if(province_id) {
        document.getElementById('edit_province').value = province_id;
        loadEditRegencies(province_id, regency_id, district_id, village_id);
    }
    
    // Set form action
    document.getElementById('editSalesForm').action = `/marketing/sales/${user_id}`;
    
    document.getElementById('editSalesModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditSalesModal() {
    document.getElementById('editSalesModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('editSalesForm').reset();
}

// Cascade for Add Modal
document.getElementById('add_province')?.addEventListener('change', function() {
    const provinceId = this.value;
    const regencySelect = document.getElementById('add_regency');
    
    regencySelect.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
    regencySelect.disabled = true;
    document.getElementById('add_district').innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
    document.getElementById('add_district').disabled = true;
    document.getElementById('add_village').innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';
    document.getElementById('add_village').disabled = true;
    
    if(provinceId) {
        fetch(`/api/regencies/${provinceId}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(regency => {
                    const option = document.createElement('option');
                    option.value = regency.id;
                    option.textContent = regency.name;
                    regencySelect.appendChild(option);
                });
                regencySelect.disabled = false;
            });
    }
});

document.getElementById('add_regency')?.addEventListener('change', function() {
    const regencyId = this.value;
    const districtSelect = document.getElementById('add_district');
    
    districtSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
    districtSelect.disabled = true;
    document.getElementById('add_village').innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';
    document.getElementById('add_village').disabled = true;
    
    if(regencyId) {
        fetch(`/api/districts/${regencyId}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(district => {
                    const option = document.createElement('option');
                    option.value = district.id;
                    option.textContent = district.name;
                    districtSelect.appendChild(option);
                });
                districtSelect.disabled = false;
            });
    }
});

document.getElementById('add_district')?.addEventListener('change', function() {
    const districtId = this.value;
    const villageSelect = document.getElementById('add_village');
    
    villageSelect.innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';
    villageSelect.disabled = true;
    
    if(districtId) {
        fetch(`/api/villages/${districtId}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(village => {
                    const option = document.createElement('option');
                    option.value = village.id;
                    option.textContent = village.name;
                    villageSelect.appendChild(option);
                });
                villageSelect.disabled = false;
            });
    }
});

// Cascade for Edit Modal
document.getElementById('edit_province')?.addEventListener('change', function() {
    const provinceId = this.value;
    const regencySelect = document.getElementById('edit_regency');
    
    regencySelect.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
    document.getElementById('edit_district').innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
    document.getElementById('edit_village').innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';
    
    if(provinceId) {
        fetch(`/api/regencies/${provinceId}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(regency => {
                    const option = document.createElement('option');
                    option.value = regency.id;
                    option.textContent = regency.name;
                    regencySelect.appendChild(option);
                });
            });
    }
});

document.getElementById('edit_regency')?.addEventListener('change', function() {
    const regencyId = this.value;
    const districtSelect = document.getElementById('edit_district');
    
    districtSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
    document.getElementById('edit_village').innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';
    
    if(regencyId) {
        fetch(`/api/districts/${regencyId}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(district => {
                    const option = document.createElement('option');
                    option.value = district.id;
                    option.textContent = district.name;
                    districtSelect.appendChild(option);
                });
            });
    }
});

document.getElementById('edit_district')?.addEventListener('change', function() {
    const districtId = this.value;
    const villageSelect = document.getElementById('edit_village');
    
    villageSelect.innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';
    
    if(districtId) {
        fetch(`/api/villages/${districtId}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(village => {
                    const option = document.createElement('option');
                    option.value = village.id;
                    option.textContent = village.name;
                    villageSelect.appendChild(option);
                });
            });
    }
});

// Load regencies for edit modal with pre-selected values
function loadEditRegencies(provinceId, regencyId, districtId, villageId) {
    fetch(`/api/regencies/${provinceId}`)
        .then(response => response.json())
        .then(data => {
            const regencySelect = document.getElementById('edit_regency');
            regencySelect.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
            data.forEach(regency => {
                const option = document.createElement('option');
                option.value = regency.id;
                option.textContent = regency.name;
                if(regency.id == regencyId) option.selected = true;
                regencySelect.appendChild(option);
            });
            
            if(regencyId) {
                loadEditDistricts(regencyId, districtId, villageId);
            }
        });
}

function loadEditDistricts(regencyId, districtId, villageId) {
    fetch(`/api/districts/${regencyId}`)
        .then(response => response.json())
        .then(data => {
            const districtSelect = document.getElementById('edit_district');
            districtSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
            data.forEach(district => {
                const option = document.createElement('option');
                option.value = district.id;
                option.textContent = district.name;
                if(district.id == districtId) option.selected = true;
                districtSelect.appendChild(option);
            });
            
            if(districtId) {
                loadEditVillages(districtId, villageId);
            }
        });
}

function loadEditVillages(districtId, villageId) {
    fetch(`/api/villages/${districtId}`)
        .then(response => response.json())
        .then(data => {
            const villageSelect = document.getElementById('edit_village');
            villageSelect.innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';
            data.forEach(village => {
                const option = document.createElement('option');
                option.value = village.id;
                option.textContent = village.name;
                if(village.id == villageId) option.selected = true;
                villageSelect.appendChild(option);
            });
        });
}
</script>

<style>
@keyframes modalSlideIn {
    from { 
        opacity: 0; 
        transform: translateY(-20px) scale(0.95); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0) scale(1); 
    }
}

.animate-modal-in { 
    animation: modalSlideIn 0.25s ease-out; 
}

.fade-in { 
    animation: fadeIn 0.3s ease-in; 
}

@keyframes fadeIn { 
    from { opacity: 0; } 
    to { opacity: 1; } 
}
</style>