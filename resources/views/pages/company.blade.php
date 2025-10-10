@extends('layout.main')
@section('title','Company')

@section('content')
<div class="container-expanded mx-auto px-6 lg:px-8 py-8 pt-[60px]">
    <!-- KPI Section -->
    <x-company.attribut.kpi
        :totalCompanies="$totalCompanies"
        :jenisCompanies="$jenisCompanies"
        :tierCompanies="$tierCompanies"
        :activeCompanies="$activeCompanies"
    />
    
    <!-- Company Table -->
    <div class="bg-white rounded-xl shadow-sm border mt-3">
        <x-globals.filtersearch
            tableId="companyTable"
            :columns="[
                'number',
                'company_name', 
                'company_type',
                'tier',
                'description', 
                'status',
                'actions'
            ]"
            :filters="[
                'Type' => $types->pluck('type_name', 'company_type_id')->toArray(),
                'Tier' => ['A', 'B', 'C', 'D'],
                'Status' => ['Active', 'Inactive']
            ]"
            ajaxUrl="{{ route('company.search') }}"
            placeholder="Cari nama perusahaan, deskripsi, atau tipe..."
        />
        <x-company.action.action :types="$types"/>
        <x-company.action.edit :types="$types"/>
        <div class="p-6">
            <x-company.table.table :companies="$companies" />
            <x-globals.pagination :paginator="$companies" />
        </div>
    </div>
</div>

@push('scripts')
<!-- Load script dengan URUTAN YANG BENAR -->
 <script src="{{ asset('js/search.js') }}"></script>




@endpush


<script>

    // Function untuk delete company
function deleteCompany(companyId, deleteRoute, csrfToken) {
    console.log('deleteCompany called:', {companyId, deleteRoute, csrfToken});
    
    deleteRecord(companyId, deleteRoute, csrfToken, (data) => {
        console.log('Delete success:', data);
        // Refresh table setelah delete sukses
        if (window.companyTableHandler) {
            console.log('Refreshing table...');
            window.companyTableHandler.refresh();
        } else {
            console.warn('companyTableHandler not found, reloading page');
            location.reload();
        }
    });
}

// Initialize setelah DOM siap
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOMContentLoaded fired');
    
    // Cek apakah TableHandler ada
    if (typeof TableHandler === 'undefined') {
        console.error('TableHandler class not found. search.js may not be loaded.');
        return;
    }

    console.log('Creating TableHandler instance...');
    
    try {
        window.companyTableHandler = new TableHandler({
            tableId: 'companyTable',
            ajaxUrl: '{{ route("company.search") }}',
            filters: ['Type', 'Tier', 'Status'],
            columns: ['number', 'company_name', 'company_type', 'tier', 'description', 'status', 'actions']
        });
        
        console.log('TableHandler initialized successfully:', window.companyTableHandler);
    } catch (error) {
        console.error('Error initializing TableHandler:', error);
    }
});
</script>
@endsection
