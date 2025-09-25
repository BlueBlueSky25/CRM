@if ($paginator->hasPages())
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

                {{-- Page Links --}}
                @php
                    $currentPage = $paginator->currentPage();
                    $lastPage = $paginator->lastPage();
                    $adjacents = 2; // jumlah halaman sebelum/sesudah halaman aktif

                    $start = max(1, $currentPage - $adjacents);
                    $end = min($lastPage, $currentPage + $adjacents);
                @endphp

                {{-- Always show first page if not in range --}}
                @if ($start > 1)
                    <a href="{{ $paginator->url(1) }}" class="px-3 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">1</a>
                    @if ($start > 2)
                        <span class="px-2">...</span>
                    @endif
                @endif

                {{-- Numbered page links --}}
                @for ($page = $start; $page <= $end; $page++)
                    @if ($page == $currentPage)
                        <span class="px-3 py-2 text-white bg-blue-600 border border-blue-600 rounded-lg">{{ $page }}</span>
                    @else
                        <a href="{{ $paginator->url($page) }}" class="px-3 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            {{ $page }}
                        </a>
                    @endif
                @endfor

                {{-- Always show last page if not in range --}}
                @if ($end < $lastPage)
                    @if ($end < $lastPage - 1)
                        <span class="px-2">...</span>
                    @endif
                    <a href="{{ $paginator->url($lastPage) }}" class="px-3 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">{{ $lastPage }}</a>
                @endif

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