@extends('layout.main')
@section('title','Marketing')

@section('content')
<div class="container-expanded mx-auto px-6 lg:px-8 py-8">
    
    <x-marketing.action.action :provinces="$provinces"/>

    <!-- Filters and Search -->
    <div class="bg-white rounded-xl shadow-sm border mb-6">
        <div class="p-6">
            <!-- Customer Table -->
            <div class="bg-white rounded-xl shadow-sm border">
                <x-marketing.table.table :salesUsers="$salesUsers" :currentMenuId="$currentMenuId" />
                <x-globals.pagination :paginator="$salesUsers" />
            </div>
        </div>
    </div>
</div>
@endsection