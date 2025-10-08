@extends('layout.main')
@section('title','Company')

@section('content')
<div class="container-expanded mx-auto px-6 lg:px-8 py-8 pt-[80px]">
    <!-- KPI Section -->
    <x-company.attribut.kpi />

    <!-- Company Table dengan jarak dari KPI -->
    <div class="bg-white rounded-xl shadow-sm border mt-8">
        <div class="p-6">
            <x-company.table.table :companies="$companies" :types="$types"/>
            <x-globals.pagination :paginator="$companies" />
        </div>
    </div>
</div>
@endsection