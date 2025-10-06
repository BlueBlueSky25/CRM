@extends('main')
@section('title','CRM Dashboard')

@section('content')
<div class="pt-20">  <!-- CUMA pt-20, TANPA px -->
    <!-- Ringkasan Metrik -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4 mb-8 px-4 sm:px-6 lg:px-8">
        <x-dashboard.kpi />
    </div>

    <!-- Charts Section-->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8 px-4 sm:px-6 lg:px-8">
        <x-dashboard.distribusigeografis />
        <x-dashboard.kategoriindustri :chart-data="$chartCompanyData" />
    </div>

    <!-- Status Proposal & Trend -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8 px-4 sm:px-6 lg:px-8">
        <x-dashboard.statusproposal />
        <x-dashboard.trendbulanan />
    </div>

    <!-- Communication & Follow-up Reminders -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-8 px-4 sm:px-6 lg:px-8">
        <x-dashboard.folreminder />
        <x-dashboard.komunikasi />
        <x-dashboard.performawilayah />
    </div>

    <!-- Aktivitas Terbaru & Quick Actions -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 px-4 sm:px-6 lg:px-8">
        <x-dashboard.aktivitasterbaru />
        <x-dashboard.action />
    </div>
</div>

@push('head')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
@endpush

@endsection