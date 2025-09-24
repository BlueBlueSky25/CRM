@extends('main')
@section('title','Marketing')

@section('content')
<div class="container-expanded mx-auto px-6 lg:px-8 py-8">
        <x-marketing.welcome />
        <!-- <x-marketing.kpi /> -->
        <x-marketing.action />
        <!-- <x-marketing.tabs /> -->

        <!-- Filters and Search -->
        <div class="bg-white rounded-xl shadow-sm border mb-6">
            <div class="p-6">
                <!-- <x-marketing.filter />-->
               <!-- <x-marketing.bulkaction /> -->

                <!-- Customer Table -->
                <div class="bg-white rounded-xl shadow-sm border">
                    <x-marketing.table :salesUsers="$salesUsers" :currentMenuId="$currentMenuId" />
                    <x-marketing.pagination />
@endsection