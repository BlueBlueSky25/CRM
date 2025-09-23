@extends('main')
@section('title','Marketing')

@section('content')
<div class="container-expanded mx-auto px-6 lg:px-8 py-8">
        <x-industri.welcome />
        <!-- <x-industri.kpi /> -->
        <x-industri.action />
        <!-- <x-industri.tabs /> -->

        <!-- Filters and Search -->
        <div class="bg-white rounded-xl shadow-sm border mb-6">
            <div class="p-6">
                <!-- <x-industri.filter />-->
               <!-- <x-industri.bulkaction /> -->

                <!-- Customer Table -->
                <div class="bg-white rounded-xl shadow-sm border">
                    <x-industri.table" />
                    <x-industri.pagination />
@endsection