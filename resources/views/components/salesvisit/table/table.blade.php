@props(['salesVisits', 'currentMenuId'])

<div class="overflow-x-auto">
    <table id="salesVisitTable" class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sales</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perusahaan</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Kunjungan</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tujuan</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Follow Up</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200" id="salesVisitTableBody">
            @forelse($salesVisits as $index => $visit)
            <tr class="hover:bg-gray-50 transition-colors" 
                data-sales-id="{{ $visit->sales_id }}" 
                data-province-id="{{ $visit->province_id }}" 
                data-follow-up="{{ $visit->is_follow_up ? '1' : '0' }}">
                <td class="px-6 py-4 text-sm text-gray-900">{{ $salesVisits->firstItem() + $index }}</td>
                
                <!-- Sales Info -->
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $visit->sales->username ?? '-' }}</div>
                            <div class="text-sm text-gray-500">{{ $visit->sales->email ?? '' }}</div>
                        </div>
                    </div>
                </td>

                <!-- Customer Name -->
                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $visit->customer_name ?? '-' }}</td>
                
                <!-- Company -->
                <td class="px-6 py-4 text-sm text-gray-900">{{ $visit->company ?? '-' }}</td>
                
                <!-- Address -->
                <td class="px-6 py-4">
                    <div class="text-sm text-gray-900">
                        @if($visit->province || $visit->regency || $visit->district || $visit->village || $visit->address)
                            @php
                                $alamatWilayah = collect([
                                    $visit->village->name ?? null,
                                    $visit->district->name ?? null, 
                                    $visit->regency->name ?? null,
                                    $visit->province->name ?? null
                                ])->filter()->implode(', ');
                            @endphp
                            
                            @if($alamatWilayah)
                                <div class="font-medium max-w-xs truncate" title="{{ $alamatWilayah }}">
                                    {{ $alamatWilayah }}
                                </div>
                                @if($visit->address)
                                    <div class="text-xs text-gray-600 mt-1 max-w-xs truncate" title="{{ $visit->address }}">
                                        {{ $visit->address }}
                                    </div>
                                @endif
                            @else
                                {{ $visit->address ?? '-' }}
                            @endif
                        @else
                            -
                        @endif
                    </div>
                </td>

                <!-- Visit Date -->
                <td class="px-6 py-4 text-sm text-gray-900">
                    {{ $visit->visit_date ? $visit->visit_date->format('d M Y') : '-' }}
                </td>
                
                <!-- Purpose -->
                <td class="px-6 py-4">
                    <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $visit->purpose ?? '-' }}">
                        {{ $visit->purpose ?? '-' }}
                    </div>
                </td>
                
                <!-- Follow Up Status -->
                <td class="px-6 py-4 text-sm">
                    @if($visit->is_follow_up)
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Ya
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Tidak
                        </span>
                    @endif
                </td>
                
                <!-- Actions -->
                <td class="px-6 py-4 text-sm font-medium">
                    <div class="flex items-center space-x-2">
                        @if(auth()->user()->canAccess($currentMenuId, 'edit'))
                        <button 
                            type="button"
                            class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 flex items-center edit-visit-btn" 
                            title="Edit Visit"
                            data-visit="{{ json_encode([
                            'id' => $visit->id,
                            'salesId' => $visit->sales_id,
                            'customerName' => $visit->customer_name,
                            'company' => $visit->company ?? '',
                            'provinceId' => $visit->province_id,
                            'regencyId' => $visit->regency_id,
                            'districtId' => $visit->district_id,
                            'villageId' => $visit->village_id,
                            'address' => $visit->address ?? '',
                            'visitDate' => optional($visit->visit_date)->format('Y-m-d'),
                            'purpose' => $visit->purpose,
                            'followUp' => $visit->is_follow_up,
                        ]) }}">

                            <i class="fas fa-edit"></i>
                        </button>
                        @endif
                        
                        @if(auth()->user()->canAccess($currentMenuId, 'delete'))
                        <button 
                            onclick="deleteVisit('{{ $visit->id }}', '{{ route('salesvisit.destroy', $visit->id) }}', '{{ csrf_token() }}')"
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
                <td colspan="9" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center text-gray-500">
                        <i class="fas fa-inbox text-5xl mb-3 text-gray-300"></i>
                        <p class="text-lg font-medium">Belum ada data kunjungan sales</p>
                        <p class="text-sm mt-1">Klik tombol "Tambah Visit" untuk menambahkan data baru</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<x-globals.pagination :paginator="$salesVisits" />