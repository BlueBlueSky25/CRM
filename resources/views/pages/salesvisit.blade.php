@extends('layout.main')
@section('title','Marketing')

@section('content')
<div class="container-expanded mx-auto px-6 lg:px-8 py-8">
    
    <x-salesvisit.action.action /> 

    <!-- Filters and Search -->
    <div class="bg-white rounded-xl shadow-sm border mb-6">
        <div class="p-6">
            <!-- Customer Table -->
            <div class="bg-white rounded-xl shadow-sm border">
                <x-salesvisit.table.table  />
                <x-salesvisit.action.edit /> 
            </div>
        </div>
    </div>
</div>
@endsection