{{-- DEBUG: Hapus setelah selesai --}}
@php
    // Debug: Cek tipe paginator
    $paginatorType = get_class($paginator ?? 'null');
    $isPaginator = $paginator instanceof \Illuminate\Pagination\LengthAwarePaginator;
    $isCollection = $paginator instanceof \Illuminate\Database\Eloquent\Collection;
    
    // Log atau dump (gunakan dd() untuk stop execution, atau Log untuk production)
    if (app()->environment('local')) {  // Hanya di local
        \Log::info("Pagination Debug - Type: {$paginatorType}, IsPaginator: " . ($isPaginator ? 'Yes' : 'No') . ", IsCollection: " . ($isCollection ? 'Yes' : 'No'));
        // Atau gunakan ini untuk dump: dd($paginatorType, $paginator);
    }
@endphp

{{-- Sisanya tetap sama --}}
@if ($paginator instanceof \Illuminate\Pagination\LengthAwarePaginator && $paginator->hasPages())
    {{-- ... kode pagination ... --}}
@else
    {{-- DEBUG: Tampilkan pesan jika bukan paginator --}}
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
        <p>Debug: Paginator bukan LengthAwarePaginator. Type: {{ $paginatorType ?? 'null' }}. Pagination tidak ditampilkan.</p>
    </div>
@endif


@if ($paginator instanceof \Illuminate\Pagination\LengthAwarePaginator && $paginator->hasPages())
    @php
        // Ensure $paginator is a LengthAwarePaginator before calling elements()
        // This check is redundant if the outer @if works, but adds robustness.
        $elements = [];
        if ($paginator instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $elements = $paginator->elements();
        }
    @endphp

    <div class="bg-white px-6 py-4 border-t border-gray-200">
        <div class="flex items-center justify-between">
            {{-- Info --}}
            <div class="text-sm text-gray-700">
                Menampilkan 
                <span class="font-medium">{{ $paginator->firstItem() ?? 0 }}</span>
                sampai 
                <span class="font-medium">{{ $paginator->lastItem() ?? 0 }}</span>
                dari 
                <span class="font-medium">{{ $paginator->total() }}</span>
                hasil
            </div>

            {{-- Pagination Links --}}
            <div class="flex space-x-1">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="px-3 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" 
                       class="px-3 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="px-3 py-2 text-gray-400">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="px-3 py-2 text-white bg-primary border border-primary rounded-lg">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" 
                                   class="px-3 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" 
                       class="px-3 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                @else
                    <span class="px-3 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                @endif
            </div>
        </div>
    </div>
@endif