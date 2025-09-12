@extends('settings')
@section('title','User-Setting')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Filters and Search -->
        <x-settingsm.filtersearch />

        <!-- User Management Table -->
        <x-settingsm.user.utable :users="$users" />

</div>

<!-- User Form -->
<x-settingsm.user.uform :roles="$roles" />
@endsection
