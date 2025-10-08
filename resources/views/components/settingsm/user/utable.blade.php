<!-- User Management Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">User Management</h3>
            <p class="text-sm text-gray-600 mt-1">Manage users and their access privileges</p>
        </div>
        @if(auth()->user()->canAccess($currentMenuId, 'create'))
        <button onclick="openUserModal()" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Add New User</span>
        </button>
        @endif
    </div>

    <!-- Notifikasi -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mx-6 mt-4">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table id="userTable" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No.</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date Birth</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alamat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($users as $index => $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $users->firstItem() + $loop->index }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $user->username }}</div>
                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $user->phone ?? '-' }}</div> 
                    </td>

                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $user->birth_date ? date('d M Y', strtotime($user->birth_date)) : '-' }}</div> 
                    </td>

                    <!-- KOLOM ALAMAT -->
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

                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $user->role->role_name ?? 'No Role' }}
                        </span>
                    </td>
                    
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    
                    <td class="px-6 py-4 text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            @php
                                $isSuperAdmin = auth()->user()->role && 
                                            (strtolower(auth()->user()->role->role_name) === 'superadmin');
                                
                                $isTargetSuperAdmin = $user->role && 
                                                    (strtolower($user->role->role_name) === 'superadmin');
                                
                                $canEdit = auth()->user()->canAccess($currentMenuId, 'edit') && 
                                        (!$isTargetSuperAdmin || $isSuperAdmin);
                                
                                $canDelete = auth()->user()->canAccess($currentMenuId, 'delete') && 
                                            (!$isTargetSuperAdmin || $isSuperAdmin);
                            @endphp
                            
                            @if($canEdit)
                            <button onclick="openEditModal(
                                '{{ $user->user_id }}',
                                '{{ $user->username }}',
                                '{{ $user->email }}',
                                '{{ $user->role_id }}',
                                {{ $user->is_active ? 'true' : 'false' }},
                                '{{ $user->phone }}',
                                '{{ $user->birth_date }}',
                                `{{ $user->address }}`,
                                '{{ $user->province_id }}',
                                '{{ $user->regency_id }}', 
                                '{{ $user->district_id }}',
                                '{{ $user->village_id }}')"
                                class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 flex items-center" 
                                title="Edit User">
                                <i class="fas fa-edit"></i>
                            </button>
                            @endif
                            
                            @if($canDelete)
                            <form action="{{ route('users.destroy', $user->user_id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 p-2 flex items-center" 
                                        title="Delete User" 
                                        onclick="return confirm('Are you sure you want to delete this user?')">
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
    
    <!-- Pagination langsung di dalam wrapper -->
    <x-globals.pagination :paginator="$users" />
    
</div>

<!-- Edit User Modal -->
<div id="editUserModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden animate-modal-in">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-semibold text-white">Edit User</h3>
                    <p class="text-indigo-100 text-sm mt-1">Update user account details</p>
                </div>
                <button onclick="closeEditModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body - Scrollable -->
        <div class="overflow-y-auto max-h-[calc(90vh-140px)]">
            <form id="editUserForm" action="{{ route('users.update', ['id' => 0]) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <input type="hidden" id="editUserId" name="user_id">
                
                <!-- Personal Information Section -->
                <div class="mb-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center border-b pb-2">
                        <i class="fas fa-user-circle text-indigo-500 mr-2"></i>
                        Personal Information
                    </h4>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        <!-- Username -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Username <span class="text-red-500">*</span></label>
                            <input type="text" id="editUsername" name="username" class="w-full border border-gray-300 rounded-lg pl-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
                        </div>
                        
                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" id="editEmail" name="email" class="w-full border border-gray-300 rounded-lg pl-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="text" id="editPhone" name="phone" class="w-full border border-gray-300 rounded-lg pl-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        </div>

                        <!-- Birth Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Birth Date</label>
                            <input type="date" name="birth_date" id="editBirthDate" class="w-full border border-gray-300 rounded-lg pl-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" max="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                </div>

                <!-- Address Section -->
                <div class="mb-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center border-b pb-2">
                        <i class="fas fa-map-marker-alt text-indigo-500 mr-2"></i>
                        Address Information
                    </h4>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        <!-- Provinsi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi <span class="text-red-500">*</span></label>
                            <select id="edit-province" name="province_id" class="cascade-province w-full border border-gray-300 rounded-lg px-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
                                <option value="">-- Pilih Provinsi --</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Kabupaten/Kota -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten/Kota <span class="text-red-500">*</span></label>
                            <select id="edit-regency" name="regency_id" class="cascade-regency w-full border border-gray-300 rounded-lg px-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
                                <option value="">-- Pilih Kabupaten/Kota --</option>
                            </select>
                        </div>

                        <!-- Kecamatan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan <span class="text-red-500">*</span></label>
                            <select id="edit-district" name="district_id" class="cascade-district w-full border border-gray-300 rounded-lg px-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
                                <option value="">-- Pilih Kecamatan --</option>
                            </select>
                        </div>

                        <!-- Kelurahan/Desa -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kelurahan/Desa <span class="text-red-500">*</span></label>
                            <select id="edit-village" name="village_id" class="cascade-village w-full border border-gray-300 rounded-lg px-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
                                <option value="">-- Pilih Kelurahan/Desa --</option>
                            </select>
                        </div>

                        <!-- Detail Alamat (full width) -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Detail Alamat</label>
                            <div class="relative">
                                <i class="fas fa-home absolute left-3 top-3 text-gray-400"></i>
                                <textarea id="editAlamat" name="address" rows="3" placeholder="Contoh: Jl. Merdeka No. 123, RT 01/RW 02" 
                                        class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-none"></textarea>
                            </div>
                            <small class="text-gray-500">Isi dengan detail alamat seperti nama jalan, nomor rumah, RT/RW</small>
                        </div>
                    </div>
                </div>

                <!-- Account Settings Section -->
                <div class="mb-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center border-b pb-2">
                        <i class="fas fa-cog text-indigo-500 mr-2"></i>
                        Account Settings
                    </h4>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        <!-- Role -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Role <span class="text-red-500">*</span></label>
                            <select id="editRole" name="role_id" class="w-full border border-gray-300 rounded-lg px-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    @php
                                        $isSuperAdmin = auth()->user()->role->role_name === 'Superadmin' || auth()->user()->role->role_name === 'superadmin';
                                        $isProtectedRole = in_array($role->role_name, ['Admin', 'Superadmin', 'admin', 'superadmin']);
                                    @endphp
                                    
                                    @if(!$isProtectedRole || $isSuperAdmin)
                                        <option value="{{ $role->role_id }}">{{ $role->role_name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- Active Status -->
                        <div class="flex items-center pt-8">
                            <input type="checkbox" id="editIsActive" name="is_active" value="1" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="editIsActive" class="ml-2 text-sm font-medium text-gray-700">Active User</label>
                        </div>

                        <!-- New Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">New Password <span class="text-xs text-gray-500 font-normal">(Leave blank to keep current)</span></label>
                            <input type="password" id="editPassword" name="password" placeholder="••••••••" class="w-full border border-gray-300 rounded-lg pl-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        </div>

                        <!-- Confirm New Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                            <input type="password" id="editPasswordConfirm" name="password_confirmation" placeholder="••••••••" class="w-full border border-gray-300 rounded-lg pl-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeEditModal()" class="px-6 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 transform hover:scale-105 font-medium shadow-md hover:shadow-lg">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>