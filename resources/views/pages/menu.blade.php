@extends('layout.settings')
@section('title','Menu-Setting')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <!-- Menu Table (dengan pagination sudah di dalam) -->
        <x-settingsm.menu.mtable :menus="$menus" />
</div>

<x-settingsm.menu.mform />
<x-settingsm.menu.medit />
@endsection