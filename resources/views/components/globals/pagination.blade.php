@props([
    'paginator' => null,
    'containerId' => 'pagination-container'
])

@if ($paginator && $paginator->hasPages())
    <div class="flex items-center justify-between px-6 py-4 bg-white border-t border-gray-200">
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
        <div class="flex items-center space-x-1">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                    <i class="fas fa-chevron-left"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" 
                   class="px-3 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-300 transition-all">
                    <i class="fas fa-chevron-left"></i>
                </a>
            @endif

            @php
                $start = max(1, $paginator->currentPage() - 2);
                $end = min($paginator->lastPage(), $paginator->currentPage() + 2);
            @endphp

            {{-- First page --}}
            @if ($start > 1)
                <a href="{{ $paginator->url(1) }}" 
                   class="px-3 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-300 transition-all">
                    1
                </a>
                @if ($start > 2)
                    <span class="px-2 text-gray-400">...</span>
                @endif
            @endif

            {{-- Page numbers --}}
            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $paginator->currentPage())
                    <span class="px-3 py-2 text-white bg-indigo-600 rounded-lg font-medium shadow-sm">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $paginator->url($page) }}" 
                       class="px-3 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-300 transition-all">
                        {{ $page }}
                    </a>
                @endif
            @endfor

            {{-- Last page --}}
            @if ($end < $paginator->lastPage())
                @if ($end < $paginator->lastPage() - 1)
                    <span class="px-2 text-gray-400">...</span>
                @endif
                <a href="{{ $paginator->url($paginator->lastPage()) }}" 
                   class="px-3 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-300 transition-all">
                    {{ $paginator->lastPage() }}
                </a>
            @endif

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" 
                   class="px-3 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-300 transition-all">
                    <i class="fas fa-chevron-right"></i>
                </a>
            @else
                <span class="px-3 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                    <i class="fas fa-chevron-right"></i>
                </span>
            @endif
        </div>
    </div>
@endif