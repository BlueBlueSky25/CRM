@extends('layout.main')
@section('title','Sales Visit')

@section('content')
<div class="container-expanded mx-auto px-6 lg:px-8 py-8 pt-[60px]">
    
    <!-- Tombol Add Component -->
    <x-salesvisit.action.action :currentMenuId="$currentMenuId" :salesUsers="$salesUsers" :provinces="$provinces" />

    <!-- HAPUS BARIS INI: -->
    <!-- <x-salesvisit.action.edit :currentMenuId="$currentMenuId" :salesUsers="$salesUsers" :provinces="$provinces" /> -->

    <!-- Sales Visit Table -->
    <div class="bg-white rounded-xl shadow-sm border mt-4">
        <div class="p-6">

            <x-globals.filtersearch
                tableId="salesVisitTable"
                :columns="[
                    'number',
                    'sales',
                    'customer_name',
                    'company',
                    'province',
                    'visit_date',
                    'purpose',
                    'follow_up',
                    'actions'
                ]"
                :filters="['Follow Up' => ['Ya', 'Tidak']]"
                ajaxUrl="{{ route('salesvisit.search') }}"
                placeholder="Cari nama customer, company, atau sales..."
            />
            
            <!-- Table Component -->
            <x-salesvisit.table.table :salesVisits="$salesVisits" :currentMenuId="$currentMenuId" />

            <!-- Pagination -->
            <x-globals.pagination :paginator="$salesVisits" />
        </div>
    </div>
</div>

<!-- Edit Modal -->
<x-salesvisit.action.edit :currentMenuId="$currentMenuId" />

@push('scripts')
<script src="{{ asset('js/search.js') }}"></script>
<script src="{{ asset('js/address-cascade.js') }}"></script>
<script src="{{ asset('js/salesvisit-modal.js') }}"></script>
@endpush

<script>
    window.Laravel = {!! json_encode(['csrfToken' => csrf_token()]) !!};

    function deleteVisit(visitId, deleteRoute, csrfToken) {
        console.log('deleteVisit called:', { visitId, deleteRoute, csrfToken });

        deleteRecord(visitId, deleteRoute, csrfToken, (data) => {
            console.log('Delete success:', data);
            if (window.salesVisitTableHandler) {
                window.salesVisitTableHandler.refresh();
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

        window.salesVisitTableHandler = new TableHandler({
            tableId: 'salesVisitTable',
            ajaxUrl: '{{ route("salesvisit.search") }}',
            filters: ['Follow Up'],
            columns: ['number', 'sales', 'customer_name', 'company', 'province', 'visit_date', 'purpose', 'follow_up', 'actions']
        });
    });
</script>
@endsection