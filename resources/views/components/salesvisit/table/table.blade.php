@props(['salesVisits', 'currentMenuId'])

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden fade-in">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Sales Visit Management</h3>
            <p class="text-sm text-gray-600 mt-1">Kelola data kunjungan sales dan informasinya</p>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table id="salesVisitTable" class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sales</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Name</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Province</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visit Date</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Follow Up</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($salesVisits as $index => $visit)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $salesVisits->firstItem() + $index }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $visit->sales->username ?? '-' }}</div>
                                <div class="text-sm text-gray-500">{{ $visit->sales->email ?? 'No email' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $visit->customer_name ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $visit->company_name ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $visit->province->name ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ $visit->visit_date ? $visit->visit_date->format('d M Y') : '-' }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ Str::limit($visit->visit_purpose ?? '-', 30) }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        @if($visit->is_follow_up)
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Ya</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Tidak</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-right">
                        <div class="flex items-center justify-end space-x-2">
                            @if(auth()->user()->canAccess($currentMenuId, 'edit'))
                            <button onclick='openEditVisitModal({
                                id: {{ $visit->id }},
                                salesId: {{ $visit->sales_id }},
                                customerName: "{{ addslashes($visit->customer_name) }}",
                                company: "{{ addslashes($visit->company_name ?? '') }}",
                                provinceId: {{ $visit->province_id }},
                                regencyId: {{ $visit->regency_id ?? 'null' }},
                                districtId: {{ $visit->district_id ?? 'null' }},
                                villageId: {{ $visit->village_id ?? 'null' }},
                                address: "{{ addslashes($visit->address ?? '') }}",
                                visitDate: "{{ $visit->visit_date->format('Y-m-d') }}",
                                purpose: "{{ addslashes($visit->visit_purpose) }}",
                                followUp: {{ $visit->is_follow_up ? 1 : 0 }}
                            })'
                                class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 flex items-center" 
                                title="Edit Visit">
                                <i class="fas fa-edit"></i>
                            </button>
                            @endif
                            
                            @if(auth()->user()->canAccess($currentMenuId, 'delete'))
                            <button type="button" 
                                onclick="deleteVisit({{ $visit->id }}, '{{ route('salesvisit.destroy', $visit->id) }}', '{{ csrf_token() }}')"
                                class="text-red-600 hover:text-red-900 p-2 flex items-center" 
                                title="Hapus Visit">
                                <i class="fas fa-trash"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                        <p>Belum ada data kunjungan sales</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($salesVisits->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $salesVisits->links() }}
    </div>
    @endif
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