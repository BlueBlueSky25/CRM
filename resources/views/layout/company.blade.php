@extends('main')
@section('title','Company')

@section('content')
<div class="container-expanded mx-auto px-6 lg:px-8 py-8 pt-[80px]">
        <x-company.kpi />
        <x-company.action :types="$types"/>

        <!-- Filters and Search -->
        <div class="bg-white rounded-xl shadow-sm border mb-6">
            <div class="p-6">
                <!-- Customer Table -->
                <div class="bg-white rounded-xl shadow-sm border">
                    <x-company.table :companies="$companies" :types="$types"/>
                    <x-globalr.pagination :paginator="$companies" />
@endsection