@extends('layout.settings')
@section('title','User-Setting')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">

    <!-- Filters and Search -->
    <x-globals.filtersearch
        tableId="userTable"
        :columns="[
            'number',
            'user',      
            'phone', 
            'date_birth',
            'alamat',
            'role',
            'status',
            'actions'
        ]"
        :filters="['Role' => $roles]"
        ajaxUrl="{{ route('users.search') }}"
        placeholder="Cari username, email, atau phone..."
    />

    <!-- User Management Table + Pagination di dalam 1 card -->
    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
        <x-settingsm.user.utable :users="$users" />
        <x-globals.pagination :paginator="$users" />
    </div>
</div>

<!-- User Form -->
<x-settingsm.user.uform :roles="$roles" :provinces="$provinces" />
<x-settingsm.user.uedit :roles="$roles" :provinces="$provinces" />


@push('scripts')
<script src="{{ asset('js/address-cascade.js') }}"></script>
<script src="{{ asset('js/global-toast.js') }}"></script>
<script src="{{ asset('js/user-modal.js') }}"></script>
<script src="{{ asset('js/search.js') }}"></script>
@endpush

<script>
    window.Laravel = {!! json_encode(['csrfToken' => csrf_token()]) !!};

    // Function untuk delete user
    function deleteUser(userId, deleteRoute, csrfToken) {
        console.log('deleteUser called:', {userId, deleteRoute, csrfToken});
        
        deleteRecord(userId, deleteRoute, csrfToken, (data) => {
            console.log('Delete success:', data);
            if (window.userTableHandler) {
                console.log('Refreshing table...');
                window.userTableHandler.refresh();
            } else {
                console.warn('userTableHandler not found, reloading page');
                location.reload();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        console.log('DOMContentLoaded fired');
        
        if (typeof TableHandler === 'undefined') {
            console.error('TableHandler class not found. search.js may not be loaded.');
            return;
        }

        console.log('Creating TableHandler instance...');
        
        try {
            window.userTableHandler = new TableHandler({
                tableId: 'userTable',
                ajaxUrl: '{{ route("users.search") }}',
                filters: ['Role'],
                columns: ['number', 'user', 'phone', 'date_birth', 'alamat', 'role', 'status', 'actions']
            });
            
            console.log('TableHandler initialized successfully:', window.userTableHandler);
        } catch (error) {
            console.error('Error initializing TableHandler:', error);
        }
    });

    // Initialize cascade untuk CREATE form
        document.addEventListener('DOMContentLoaded', function() {
            const createCascade = new AddressCascade({
                provinceId: 'create-province',
                regencyId: 'create-regency',
                districtId: 'create-district',
                villageId: 'create-village'
            });
        });
</script>
@endsection
