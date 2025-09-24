@extends('settings')
@section('title','Role-Setting')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        

        <x-settingsm.role.rtable :roles="$roles" />
        <!-- <x-globalr.pagination :paginator="$roles" /> -->

</div>

<x-settingsm.role.rform />
<x-settingsm.role.assign-menu :menus="$menus" />
@endsection
