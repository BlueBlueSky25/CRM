<!-- INDUSTRIES TABLE COMPONENT -->
<div class="fade-in">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-gray-800">Visit Sales</h2>
        <button onclick="openVisitModal()" 
            class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-all">
            <i class="fas fa-plus mr-1"></i> Tambah Visit
        </button>
    </div>

    <div class="overflow-x-auto bg-white rounded-xl shadow-sm border">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-left font-medium text-gray-500">No</th>
                <th class="px-6 py-3 text-left font-medium text-gray-500">Sales</th>
                <th class="px-6 py-3 text-left font-medium text-gray-500">Customer</th>
                <th class="px-6 py-3 text-left font-medium text-gray-500">Perusahaan</th>
                <th class="px-6 py-3 text-left font-medium text-gray-500">Provinsi</th>
                <th class="px-6 py-3 text-left font-medium text-gray-500">Tanggal Kunjungan</th>
                <th class="px-6 py-3 text-left font-medium text-gray-500">Tujuan</th>
                <th class="px-6 py-3 text-left font-medium text-gray-500">Follow Up</th>
                <th class="px-6 py-3 text-left font-medium text-gray-500">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y" id="salesVisitTableBody">
            @forelse($salesVisits as $index => $visit)
            <tr>
                <td class="px-6 py-4 text-gray-600">{{ $salesVisits->firstItem() + $index }}</td>
                <td class="px-6 py-4 font-medium text-gray-800">{{ $visit->sales->username ?? '-' }}</td>
                <td class="px-6 py-4 text-gray-600">{{ $visit->customer_name ?? '-' }}</td>
                <td class="px-6 py-4 text-gray-600">{{ $visit->company ?? '-' }}</td>
                <td class="px-6 py-4 text-gray-600">{{ $visit->province->name ?? '-' }}</td>
                <td class="px-6 py-4 text-gray-600">
                    {{ $visit->visit_date ? $visit->visit_date->format('d-m-Y') : '-' }}
                </td>
                <td class="px-6 py-4 text-gray-600">
                    <div class="max-w-xs truncate" title="{{ $visit->purpose ?? '-' }}">
                        {{ $visit->purpose ?? '-' }}
                    </div>
                </td>
                <td class="px-6 py-4">
                    @if($visit->is_follow_up)
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                            Ya
                        </span>
                    @else
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                            Tidak
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 flex gap-2">
                    @if(auth()->user()->canAccess($currentMenuId ?? 1, 'edit'))
                    <button 
                        class="text-blue-600 hover:text-blue-800" 
                        onclick="openEditVisitModal(
                            '{{ $visit->id }}',
                            '{{ $visit->sales_id }}',
                            '{{ addslashes($visit->customer_name) }}',
                            '{{ addslashes($visit->company ?? '') }}',
                            '{{ $visit->province_id }}',
                            '{{ $visit->visit_date->format('Y-m-d') }}',
                            '{{ addslashes($visit->purpose) }}',
                            '{{ $visit->is_follow_up }}'
                        )"
                        title="Edit Visit">
                        <i class="fas fa-edit"></i>
                    </button>

                    @endif

                    @if(auth()->user()->canAccess($currentMenuId ?? 1, 'delete'))
                    <button 
                        class="text-red-600 hover:text-red-800"
                        onclick="deleteVisit('{{ $visit->id }}', '{{ route('salesvisit.destroy', $visit->id) }}', '{{ csrf_token() }}')"
                        title="Delete Visit">
                        <i class="fas fa-trash"></i>
                    </button>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-2"></i>
                    <p>Belum ada data kunjungan sales</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
    </div>
</div>


