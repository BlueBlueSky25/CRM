@props(['salesUsers', 'provinces', 'currentMenuId'])

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden fade-in">

    <!-- Table -->
    <div class="overflow-x-auto">
        <table id="salesTable" class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-3 py-2 text-left text-[10px] font-medium text-gray-500 uppercase tracking-tight">No</th>
                    <th class="px-3 py-2 text-left text-[10px] font-medium text-gray-500 uppercase tracking-tight">Name</th>
                    <th class="px-3 py-2 text-left text-[10px] font-medium text-gray-500 uppercase tracking-tight">Phone</th>
                    <th class="px-3 py-2 text-left text-[10px] font-medium text-gray-500 uppercase tracking-tight">Birth Date</th>
                    <th class="px-3 py-2 text-left text-[10px] font-medium text-gray-500 uppercase tracking-tight">Address</th>
                    <th class="px-3 py-2 text-left text-[10px] font-medium text-gray-500 uppercase tracking-tight">Roles</th>
                    <th class="px-3 py-2 text-left text-[10px] font-medium text-gray-500 uppercase tracking-tight">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($salesUsers as $index => $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 text-xs text-gray-900">{{ $loop->iteration }}</td>
                    <td class="px-3 py-2">
                        <div class="flex items-center">
                            <div>
                                <div class="text-xs font-medium text-gray-900">{{ $user->username }}</div>
                                <div class="text-[10px] text-gray-500">{{ $user->email ?? 'No email' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-3 py-2 text-xs text-gray-900">{{ $user->phone ?? '-' }}</td>
                    <td class="px-3 py-2 text-xs text-gray-900">
                        {{ $user->birth_date ? date('d M Y', strtotime($user->birth_date)) : '-' }}
                    </td>
                    <td class="px-3 py-2 max-w-xs">
                        <div class="text-xs text-gray-900">
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
                                    <div class="font-medium truncate" title="{{ $alamatWilayah }}">{{ $alamatWilayah }}</div>
                                    @if($user->address)
                                        <div class="text-[10px] text-gray-600 truncate" title="{{ $user->address }}">{{ $user->address }}</div>
                                    @endif
                                @else
                                    {{ $user->address ?? '-' }}
                                @endif
                            @else
                                -
                            @endif
                        </div>
                    </td>
                    <td class="px-3 py-2 text-xs">
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-blue-100 text-blue-800">
                            {{ $user->role->role_name ?? 'No Role' }}
                        </span>
                    </td>
                    <td class="px-3 py-2 text-xs font-medium">
                        <div class="flex items-center space-x-1">
                            @if(auth()->user()->canAccess($currentMenuId, 'edit'))
                            <button onclick="openEditSalesModal('{{ $user->user_id }}', '{{ $user->username }}', '{{ $user->email }}', '{{ $user->phone }}', '{{ $user->birth_date }}', '{{ $user->address }}', '{{ $user->province_id }}', '{{ $user->regency_id }}', '{{ $user->district_id }}', '{{ $user->village_id }}')" 
                                class="text-blue-600 hover:text-blue-900 p-1.5 rounded-lg hover:bg-blue-50 flex items-center" 
                                title="Edit User">
                                <i class="fas fa-edit text-xs"></i>
                            </button>
                            @endif
                            
                            @if(auth()->user()->canAccess($currentMenuId, 'delete'))
                            <form action="{{ route('marketing.sales.destroy', $user->user_id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 p-1.5 flex items-center" title="Hapus User" onclick="return confirm('Yakin ingin menghapus user ini?')">
                                    <i class="fas fa-trash text-xs"></i>
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
    <x-globals.pagination :paginator="$salesUsers" />
</div>

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