@extends('layout.main')
@section('title','Marketing')

@section('content')
<div class="container-expanded mx-auto px-6 lg:px-8 py-8 pt-[60px]">
    <!-- Marketing Table -->
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-6">
            <x-marketing.table.table :salesUsers="$salesUsers" :provinces="$provinces" :currentMenuId="$currentMenuId" />
            <x-globals.pagination :paginator="$salesUsers" />
        </div>
    </div>
</div>
@endsection