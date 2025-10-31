@props([
    'tableId',
    'ajaxUrl',
    'filters' => [],
    'columns' => [],
    'placeholder' => 'Cari sesuatu...',
])

<div class="flex flex-col md:flex-row md:items-center gap-3">
    {{-- Search Input --}}
    <div class="relative flex-1 md:flex-initial md:w-80">
        <input
            type="text"
            id="{{ $tableId }}SearchInput"
            placeholder="{{ $placeholder }}"
            class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all"
        />
        <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
    </div>

    {{-- Dynamic Filters --}}
    @foreach($filters as $filterName => $filterOptions)
        @php
            $filterId = Str::slug($filterName, '_');
        @endphp
        <select id="{{ $tableId }}_{{ $filterId }}Filter"
            class="px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all bg-white">
            <option value="">Semua {{ $filterName }}</option>
            @foreach($filterOptions as $option)
                @php
                    $value = is_object($option)
                        ? ($option->id ?? strtolower($option->name ?? $option->role_name ?? $option->type_name ?? ''))
                        : strtolower($option);
                    $label = is_object($option)
                        ? ($option->name ?? $option->role_name ?? $option->type_name ?? 'Unknown')
                        : $option;
                @endphp
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
    @endforeach

    {{-- Slot buat tombol Add, Export, dsb (jika ada) --}}
    @if(trim($slot ?? ''))
    <div class="flex items-center gap-2 ml-auto">
        {{ $slot }}
    </div>
    @endif
</div>