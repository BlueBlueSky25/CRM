@extends('layout.main')
@section('title', 'All Customers')

@section('content')
<div class="container-expanded mx-auto px-6 lg:px-8 py-8 pt-[80px] space-y-6">

    <!-- KPI -->
    <x-customers.attribut.kpi />

    <!-- Action Button -->
    <x-customers.action.action :provinces="$provinces"/>
    <x-customers.action.edit :provinces="$provinces"/>

    <!-- Filter + Table Section -->
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-6 space-y-4">

            <!-- Filter + Bulk Action -->
            <x-customers.attribut.filter />
            <x-customers.action.bulkaction :provinces="$provinces"/>

            <!-- Table -->
            <div class="bg-white rounded-xl shadow-sm border">
                <x-customers.table.table />
            </div>

            <!-- Pagination -->
            <x-globals.pagination />
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="{{ asset('js/customers.js') }}"></script>
<script src="{{ asset('js/address-cascade.js') }}"></script>
<script src="{{ asset('js/search.js') }}"></script>
@endpush

<script>
// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Customer Management System Loaded');
});

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
