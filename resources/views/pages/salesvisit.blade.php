@extends('layout.main')
@section('title','Marketing')

@section('content')
<div class="container-expanded mx-auto px-6 lg:px-8 py-8">
    
    <x-salesvisit.action.action :provinces="$provinces" :salesUsers="$salesUsers"/> 

    <!-- Filters and Search -->
    <div class="bg-white rounded-xl shadow-sm border mb-6">
        <div class="p-6">
            <!-- Customer Table -->
            <div class="bg-white rounded-xl shadow-sm border">
                <x-salesvisit.table.table  :salesVisits="$salesVisits"/>
                <x-salesvisit.action.edit provinces="$provinces" :salesUsers="$salesUsers"/> 
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/salesvisit-modal.js') }}"></script>
<script src="{{ asset('js/address-cascade.js') }}"></script>
<script src="{{ asset('js/search.js') }}"></script>
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
@endsection