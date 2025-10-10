<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Privilege Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#64748B'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <x-settingsm.header />
    
    <div>
        <!-- Stats Cards -->
        <x-settingsm.kpi />
    </div>
        
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <x-settingsm.footer />
        <script>
        window.rolePermissions = @json($rolePermissions ?? []);
        </script>
    <script src="{{ asset('js/address-cascade.js') }}"></script>
    <script src="{{ asset('js/user-modal.js') }}"></script>
    <script src="{{ asset('js/search.js') }}"></script>
    <script>
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
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Akses Ditolak',
                text: "{{ session('error') }}",
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'OK',
                timer: 3000, // auto close dalam 3 detik
                timerProgressBar: true
            });
        </script>
        @endif
</body>
</html>