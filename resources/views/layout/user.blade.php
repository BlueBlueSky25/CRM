@extends('settings')
@section('title','User-Setting')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Filters and Search -->
        <x-settingsm.filtersearch
        tableId="userTable"
        :searchFields="[2,3]"
        :showRoleFilter="true"
        :roles="$roles"
        ajaxUrl="{{ route('users.search') }}"
        />

        <!-- User Management Table -->
        <x-settingsm.user.utable :users="$users"  :roles="$roles" :provinces="$provinces" />
        <x-globalr.pagination :paginator="$users" /> 
</div>

<!-- User Form -->
<x-settingsm.user.uform :roles="$roles" :provinces="$provinces" />

<script>
    window.Laravel = {!! json_encode(['csrfToken' => csrf_token()]) !!};
</script>
@endsection
