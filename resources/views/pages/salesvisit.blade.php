@extends('layout.main')
@section('title','Sales Visit')

@section('content')
<div class="container-expanded mx-auto px-6 lg:px-8 py-8 pt-[60px] mt-8">
    
    <!-- Sales Visit Card dengan Everything Inside -->
    <div style="background-color: #ffffff; border-radius: 0.75rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); border: 1px solid #e5e7eb; overflow: hidden;">
        
        <!-- Card Header dengan Title dan Action Buttons -->
        <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin: 0;">Sales Visit Management</h3>
                    <p style="font-size: 0.875rem; color: #6b7280; margin: 0.25rem 0 0 0;">Kelola data kunjungan sales dan informasinya</p>
                </div>
                
                <!-- Action Buttons -->
                <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                    @if(auth()->user()->canAccess($currentMenuId, 'create'))
                    <button onclick="openVisitModal()"
                        style="display: flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1rem; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white; border: none; border-radius: 0.5rem; font-weight: 500; font-size: 0.875rem; cursor: pointer; box-shadow: 0 2px 4px rgba(99, 102, 241, 0.2); transition: all 0.2s;">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Kunjungan</span>
                    </button>
                    @endif

                    <a href="{{ route('salesvisit.export') }}" 
                        style="display: flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1rem; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; text-decoration: none; border-radius: 0.5rem; font-weight: 500; font-size: 0.875rem; box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2); transition: all 0.2s;">
                        <i class="fas fa-file-export"></i>
                        <span>Export</span>
                    </a>

                    <button onclick="openImportModal()"
                        style="display: flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1rem; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border: none; border-radius: 0.5rem; font-weight: 500; font-size: 0.875rem; cursor: pointer; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2); transition: all 0.2s;">
                        <i class="fas fa-file-import"></i>
                        <span>Import</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div style="padding: 1.5rem; background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
            <div style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center;">
                <!-- Search Box -->
                <div style="flex: 1; min-width: 250px;">
                    <div style="position: relative;">
                        <i class="fas fa-search" style="position: absolute; left: 0.875rem; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 0.875rem;"></i>
                        <input type="text" 
                            id="searchInput"
                            style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.625rem 1rem 0.625rem 2.5rem; font-size: 0.875rem; background-color: #ffffff; transition: all 0.2s;"
                            placeholder="Cari nama customer, company, atau sales...">
                    </div>
                </div>

                <!-- Filter -->
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <label style="font-size: 0.875rem; font-weight: 500; color: #374151; white-space: nowrap;">Filter:</label>
                    <div style="position: relative;">
                        <i class="fas fa-filter" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 0.75rem;"></i>
                        <select id="filterFollowUp" 
                            style="border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 2rem 0.5rem 2.25rem; font-size: 0.875rem; background-color: #ffffff; cursor: pointer; transition: all 0.2s; min-width: 180px;">
                            <option value="">Semua Follow Up</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section - NO PADDING! -->
        <x-salesvisit.table.table :salesVisits="$salesVisits" :currentMenuId="$currentMenuId" />

        <!-- Pagination -->
        @if($salesVisits->hasPages())
        <div style="padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb; background-color: #f9fafb;">
            <x-globals.pagination :paginator="$salesVisits" />
        </div>
        @endif
    </div>
</div>

<!-- Modals -->
<x-salesvisit.action.action :currentMenuId="$currentMenuId" :salesUsers="$salesUsers" :provinces="$provinces" />
<x-salesvisit.action.edit :currentMenuId="$currentMenuId" :salesUsers="$salesUsers" :provinces="$provinces" />

@push('scripts')
<script src="{{ asset('js/search.js') }}"></script>
<script src="{{ asset('js/address-cascade.js') }}"></script>
<script src="{{ asset('js/salesvisit-modal.js') }}"></script>
@endpush

<style>
    /* Hover effects for buttons */
    button:hover, a[href]:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    /* Focus styles for inputs */
    #searchInput:focus,
    #filterFollowUp:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        button span, a span {
            display: none;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    console.log('SalesVisit page loaded');
    

    console.log('deleteVisit function available:', typeof deleteVisit !== 'undefined');
    console.log('showNotification function available:', typeof showNotification !== 'undefined');
    
    
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            console.log('Searching for:', searchValue);
            
        });
    }

    
    const filterFollowUp = document.getElementById('filterFollowUp');
    if (filterFollowUp) {
        filterFollowUp.addEventListener('change', function() {
            const filterValue = this.value;
            console.log('Filtering by:', filterValue);
        
        });
    }
});
</script>
@endsection
