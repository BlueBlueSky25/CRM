        <div class="fade-in">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left">
                                <input
                                    type="checkbox"
                                    id="selectAll"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                />
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Phone
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Birth Date
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Address
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Roles
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody id="salesTableBody" class="divide-y divide-gray-200">
                        @foreach($salesUsers as $index => $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $user->username }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->email ?? 'No email' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                        
                                    <div class="text-sm text-gray-900">{{ $user->phone ?? '-' }}</div>
                                </td>

                                <td class="px-6 py-4">
                                    
                                    <div class="text-sm text-gray-900">{{ $user->birth_date ? date('d M Y', strtotime($user->birth_date)) : '-' }}</div>
                                </td>

                                <td class="px-6 py-4">
                                    
                                     <div class="text-sm text-gray-900">{{ $user->address ?? '-' }}</div> 
                                </td>

                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $user->role->role_name ?? 'No Role' }}
                                    </span>
                                </td>

                                
                                <td class="px-6 py-4 text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        @if(auth()->user()->canAccess($currentMenuId, 'edit'))
                                        <button onclick="openEditModal('{{ $user->user_id }}', '{{ $user->username }}', '{{ $user->email }}', '{{ $user->role_id }}', {{ $user->is_active ? 'true' : 'false' }})" 
                                                class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 flex items-center" 
                                                title="Edit User">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @endif
                                        
                                        @if(auth()->user()->canAccess($currentMenuId, 'delete'))
                                        <form action="{{ route('users.destroy', $user->user_id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 p-2 flex items-center" title="Delete User" onclick="return confirm('Are you sure you want to delete this user?')">
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