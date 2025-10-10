@props([
    'tableId',
    'ajaxUrl',
    'filters' => [],
    'columns' => [],
    'placeholder' => 'Cari sesuatu...',
])

<div class="bg-white rounded-xl shadow-sm p-6 mb-4 border border-gray-200 fade-in">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div class="flex flex-wrap items-center gap-4">
            {{-- Search Input --}}
            <div class="relative">
                <input
                    type="text"
                    id="{{ $tableId }}SearchInput"
                    placeholder="{{ $placeholder }}"
                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none"
                />
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>

            {{-- Dynamic Filters --}}
            @foreach($filters as $filterName => $filterOptions)
                @php
                    $filterId = Str::slug($filterName, '_');
                @endphp
                <select id="{{ $tableId }}_{{ $filterId }}Filter"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none">
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
        </div>

        {{-- Slot buat tombol Add, Export, dsb --}}
        <div class="flex items-center space-x-2">
            {{ $slot }}
        </div>
    </div>
</div>
