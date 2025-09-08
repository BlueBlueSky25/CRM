@extends('main')
@section('title','All Customers')

@section('content')
<div class="container-expanded mx-auto px-6 lg:px-8 py-8">
        <x-customers.welcome />
        <x-customers.kpi />
        <x-customers.action />
        <x-customers.tabs />

        <!-- Filters and Search -->
        <div class="bg-white rounded-xl shadow-sm border mb-6">
            <div class="p-6">
                <x-customers.filter />
                <x-customers.bulkaction />

                <!-- Customer Table -->
                <div class="bg-white rounded-xl shadow-sm border">
                    <x-customers.table />
                    <x-customers.pagination />
@endsection