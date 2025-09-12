@extends('settings')
@section('title','Role-Setting')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        

        <!-- Filters and Search -->
        <x-settingsm.filtersearch />

        <!-- Role Management Table -->
        <x-settingsm.role.rtable />

        

        
    </div>
    <x-settingsm.role.rform />
@endsection