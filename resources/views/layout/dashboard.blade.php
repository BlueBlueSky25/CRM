@extends('main')
@section('title','CRM Dashboard')

@section('content')
<div class="container-expanded mx-auto px-6 lg:px-8 py-8">
        <!-- Welcome Section -->
        <x-dashboard.welcome />
        </div>

        <!-- Ringkasan Metrik -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4 mb-8">
            <!-- Total Customer -->
            <x-dashboard.kpi />
        </div>

        <!-- Charts Section-->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8">
            <!-- Distribusi Geografis -->
            <x-dashboard.distribusigeografis />

            <!-- Kategori Industri -->
            <x-dashboard.kategoriindustri />
        </div>

        <!-- Status Proposal & Trend -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8">
            <!-- Status Proposal -->
            <x-dashboard.statusproposal />

            <!-- Trend Bulanan -->
            <x-dashboard.trendbulanan />
        </div>

        <!-- Communication & Follow-up Reminders -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-8">
            <!-- Follow-up Reminders -->
            <x-dashboard.folreminder />

            <!-- komunikasi -->
            <x-dashboard.komunikasi />

            <!-- performa Wilayah -->
            <x-dashboard.performawilayah />
        </div>

        <!-- Aktivitas Terbaru & Quick Actions - Updated Layout -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
            <!-- Aktivitas Terbaru -->
            <x-dashboard.aktivitasterbaru />

            <!-- Quick Actions -->
            <x-dashboard.action />
        </div>
@endsection