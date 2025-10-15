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
                                class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 transition-colors flex items-center" 
                                title="Edit User">
                                <i class="fas fa-edit"></i>
                            </button>
                            @endif
                            
                            @if($canDelete)
                            <button type="button" 
                                onclick="deleteUser('{{ $user->user_id }}', '{{ route('users.destroy', $user->user_id) }}', '{{ csrf_token() }}')"
                                class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition-colors flex items-center" 
                                title="Delete User">
                                <i class="fas fa-trash"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
    
</div>