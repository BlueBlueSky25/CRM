@extends('layout.main')
@section('title', 'All Customers')

@section('content')
<div class="container-expanded mx-auto px-6 lg:px-8 py-8 pt-[80px] space-y-6">

    <!-- KPI -->
    <x-customers.attribut.kpi />

    <!-- Action Button -->
    <x-customers.action.action />

    <!-- Filter + Table Section -->
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-6 space-y-4">

            <!-- Filter + Bulk Action -->
            <x-customers.attribut.filter />
            <x-customers.action.bulkaction />

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

<script>
// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Customer Management System Loaded');
});
</script>
@endpush