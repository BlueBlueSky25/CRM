@extends('settings')
@section('title','User-Setting')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Filters and Search -->
        

        <!-- User Management Table -->
        <x-settingsm.user.utable :users="$users"  :roles="$roles" />
        <x-globalr.pagination :paginator="$users" /> 
</div>

<!-- User Form -->
<x-settingsm.user.uform :roles="$roles" />
@endsection
