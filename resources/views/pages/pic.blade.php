@extends('layout.main')

@section('content')
<div class="container-fluid" style="padding: 1.5rem;">
    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-bottom: 1.5rem;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-bottom: 1.5rem;">
        <i class="fas fa-exclamation-circle"></i>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- Main Card -->
    <div style="background-color: white; border-radius: 0.75rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); border: 1px solid #e5e7eb; overflow: hidden;">
        
        <!-- Header -->
        <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #e5e7eb; background-color: #f9fafb; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin: 0;">PIC Management</h3>
                <p style="font-size: 0.875rem; color: #6b7280; margin: 0.25rem 0 0 0;">Kelola data Person In Charge dan informasinya</p>
            </div>
            @if(auth()->user()->canAccess($currentMenuId ?? 10, 'create'))
            <button onclick="openPICModal()" 
                style="display: flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1rem; background-color: #6366f1; color: white; border: none; border-radius: 0.5rem; font-weight: 500; cursor: pointer; transition: all 0.2s; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);"
                onmouseover="this.style.backgroundColor='#4f46e5'; this.style.transform='scale(1.02)'"
                onmouseout="this.style.backgroundColor='#6366f1'; this.style.transform='scale(1)'">
                <i class="fas fa-plus"></i>
                <span>Tambah PIC</span>
            </button>
            @endif
        </div>

        <!-- Filters -->
        <div style="padding: 1rem 1.5rem; background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
            <div style="display: grid; grid-template-columns: 1fr 1fr 200px; gap: 1rem; align-items: center;">
                <div>
                    <input type="text" id="searchInput" 
                        style="width: 100%; padding: 0.625rem 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem;"
                        placeholder="Cari nama, email, telepon, jabatan...">
                </div>
                <div>
                    <select id="companyFilter" 
                        style="width: 100%; padding: 0.625rem 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem;">
                        <option value="">Semua Perusahaan</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->company_id }}">{{ $company->company_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="button" onclick="searchPics()" 
                        style="width: 100%; padding: 0.625rem 1rem; background-color: #6366f1; color: white; border: none; border-radius: 0.5rem; font-weight: 500; cursor: pointer; transition: background-color 0.2s;"
                        onmouseover="this.style.backgroundColor='#4f46e5'"
                        onmouseout="this.style.backgroundColor='#6366f1'">
                        <i class="fas fa-search" style="margin-right: 0.5rem;"></i>Cari
                    </button>
                </div>
            </div>
        </div>

        <!-- Table Component -->
        <x-PICharge.table.table :pics="$pics" :companies="$companies" :currentMenuId="$currentMenuId ?? 10" />

        <!-- Pagination -->
        @if($pics->total() > 0)
        <div id="paginationContainer" style="padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
            <div style="font-size: 0.875rem; color: #6b7280;">
                Showing {{ $pics->firstItem() }} to {{ $pics->lastItem() }} of {{ $pics->total() }} entries
            </div>
            <div>
                {{ $pics->links() }}
            </div>
        </div>
        @endif

    </div>

    <!-- Action Modals -->
    <x-PICharge.action.action :companies="$companies" :currentMenuId="$currentMenuId ?? 10" />
    <x-PICharge.action.edit :companies="$companies" />
</div>

<style>
/* Responsive Grid */
@media (max-width: 1024px) {
    div[style*="grid-template-columns: 1fr 1fr 200px"] {
        grid-template-columns: 1fr 1fr !important;
    }
    
    div[style*="grid-template-columns: 1fr 1fr 200px"] > div:last-child {
        grid-column: 1 / -1;
    }
}

@media (max-width: 640px) {
    div[style*="grid-template-columns: 1fr 1fr 200px"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection

@push('scripts')

@endpush