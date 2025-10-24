@extends('layout.main')
@section('title','PIC Management')

@section('content')
<div class="container-expanded mx-auto px-6 lg:px-8 py-8 pt-[60px]">

    <!-- PIC Table Component -->
    <div class="mb-8 mt-8">
    <x-PICharge.table.table :currentMenuId="10" />
    </div>

    <!-- Action Modal (Add PIC) -->
    <x-PICharge.action.action 
        :provinces="[]"
        :companies="[]"
        :currentMenuId="10"
    />

    <!-- Edit Modal -->
    <x-PICharge.action.edit 
        :provinces="[]"
        :companies="[]"
    />
</div>

<script>
    // Success/Error notification handling
    @if(session('success'))
        showNotification('{{ session("success") }}', 'success');
    @endif

    @if(session('error'))
        showNotification('{{ session("error") }}', 'error');
    @endif

    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-[60] p-4 rounded-lg shadow-lg text-white transform transition-all duration-300 translate-x-full`;
        
        const bgColor = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            info: 'bg-blue-500'
        };
        
        notification.classList.add(bgColor[type]);
        notification.innerHTML = `
            <div class="flex items-center gap-2">
                <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : 'info'}-circle"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
</script>
@endsection