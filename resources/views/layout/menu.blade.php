@extends('settings')
@section('title','Menu-Setting')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Filters and Search -->
        <x-settingsm.filtersearch />

        <!-- User Management Table -->
        <x-settingsm.menu.mtable />

        

        
    </div>
    <x-settingsm.menu.mform /> 
@endsection