@extends('layout.main')
@section('title','Marketing - Sales Visit')

@section('content')
<div class="container-expanded mx-auto px-6 lg:px-8 py-8">

    <!-- Filters and Search Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 mt-12 fade-in">
        <div class="p-6">
            <div class="flex flex-wrap gap-4 items-center">
                <!-- Search Input -->
                <div class="flex-1 min-w-[250px]">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" 
                            id="searchInput"
                            placeholder="Cari nama customer, perusahaan, atau sales..." 
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    </div>
                </div>

                <!-- Filter by Sales -->
                <div class="w-full sm:w-auto min-w-[200px]">
                    <select id="filterSales" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        <option value="">Semua Sales</option>
                        @foreach($salesUsers as $sales)
                            <option value="{{ $sales->user_id }}">{{ $sales->username }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter by Province -->
                <div class="w-full sm:w-auto min-w-[200px]">
                    <select id="filterProvince" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        <option value="">Semua Provinsi</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province->id }}">{{ $province->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter by Follow Up Status -->
                <div class="w-full sm:w-auto min-w-[200px]">
                    <select id="filterFollowUp" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        <option value="">Semua Status</option>
                        <option value="1">Perlu Follow Up</option>
                        <option value="0">Tidak Perlu Follow Up</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
        <!-- Header Section with Add Button -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden fade-in mb-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Sales Visit Management</h3>
                    <p class="text-sm text-gray-600 mt-1">Kelola data kunjungan sales dan informasinya</p>
                </div>
                @if(auth()->user()->canAccess($currentMenuId ?? 1, 'create'))
                <button onclick="openVisitModal()" 
                    class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
                    <i class="fas fa-plus"></i>
                    Tambah Visit
                </button>
                @endif
            </div>
            <x-salesvisit.table.table :salesVisits="$salesVisits" :currentMenuId="$currentMenuId ?? 1"/>
        </div>


    <!-- Modals -->
    <x-salesvisit.action.action :provinces="$provinces" :salesUsers="$salesUsers"/> 
    <x-salesvisit.action.edit :provinces="$provinces" :salesUsers="$salesUsers"/> 
</div>

@push('scripts')
<script src="{{ asset('js/salesvisit-modal.js') }}"></script>
<script src="{{ asset('js/address-cascade.js') }}"></script>
<script src="{{ asset('js/salesvisit-filter.js') }}"></script>
@endpush

<script>
// Initialize cascade untuk CREATE form
document.addEventListener('DOMContentLoaded', function() {
    const createCascade = new AddressCascade({
        provinceId: 'create-province',
        regencyId: 'create-regency',
        districtId: 'create-district',
        villageId: 'create-village'
    });
});
</script>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.fade-in {
    animation: fadeIn 0.3s ease-in;
}

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
</style>
@endsection