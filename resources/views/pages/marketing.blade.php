@extends('layout.main')
@section('title','Marketing')

@section('content')
<div class="container-expanded mx-auto px-6 lg:px-8 py-8 pt-[60px]">
    <!-- KPI Section (kalau ada) -->


    <!-- Marketing Table dengan tombol Add -->
    <div class="bg-white rounded-xl shadow-sm border mt-4">
        <div class="p-6">

        <x-globals.filtersearch
                tableId="salesTable"
                :columns="[
                    'number',
                    'user',
                    'phone',
                    'date_birth',
                    'alamat',
                    'status',
                    'actions'
                ]"
                :filters="['Status' => ['Active', 'Inactive']]"
                ajaxUrl="{{ route('marketing.search') }}"
                placeholder="Cari nama sales, email, atau nomor telepon..."
            />
            <!-- Tombol Add Component -->
            <div class="flex justify-between mb-0">
                <x-marketing.action.action :provinces="$provinces" :currentMenuId="$currentMenuId"/>
            </div>

            <!-- Table Component -->
            <x-marketing.table.table :salesUsers="$salesUsers" :currentMenuId="$currentMenuId" />

            <!-- Pagination -->
            <x-globals.pagination />
        </div>
    </div>
</div>

<!-- Edit Modal -->
<x-marketing.action.edit :provinces="$provinces"  />


@push('scripts')
<!-- Load script dengan URUTAN YANG BENAR -->
<script src="{{ asset('js/address-cascade.js') }}"></script> <!-- FILE YANG SUDAH ADA -->
<script src="{{ asset('js/search.js') }}"></script>


@endpush


<script>
    window.Laravel = {!! json_encode(['csrfToken' => csrf_token()]) !!};

    function deleteSales(userId, deleteRoute, csrfToken) {
        console.log('deleteSales called:', { userId, deleteRoute, csrfToken });

        deleteRecord(userId, deleteRoute, csrfToken, (data) => {
            console.log('Delete success:', data);
            if (window.salesTableHandler) {
                window.salesTableHandler.refresh();
            } else {
                location.reload();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (typeof TableHandler === 'undefined') {
            console.error('TableHandler not loaded.');
            return;
        }

        window.salesTableHandler = new TableHandler({
            tableId: 'salesTable',
            ajaxUrl: '{{ route("marketing.search") }}',
            filters: ['Status'],
            columns: ['number', 'user', 'phone', 'date_birth', 'alamat', 'status', 'actions']
        });
    });

    // Initialize cascade SETELAH semua script loaded
document.addEventListener('DOMContentLoaded', function() {
    const createCascade = new AddressCascade({
        provinceId: 'create-province',
        regencyId: 'create-regency',
        districtId: 'create-district',
        villageId: 'create-village'
    });
});
</script>
@endsection