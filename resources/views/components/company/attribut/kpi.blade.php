<div class="pt-2 max-w-7xl mx-auto fade-in">
    <!-- KPI Cards Company -->
    <div class="mt-2 mb-0">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">

            <!-- Total Company -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-building text-blue-600 text-lg"></i>
                        </div>
                    </div>
                    <div class="ml-4 min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-600 truncate">Total Company</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalCompanies }}</p>
                    </div>
                </div>
            </div>

            <!-- Jenis Company -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tags text-green-600 text-lg"></i>
                        </div>
                    </div>
                    <div class="ml-4 min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-600 truncate">Jenis Company</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $jenisCompanies }}</p>
                    </div>
                </div>
            </div>

            <!-- Tier Company -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-layer-group text-yellow-600 text-lg"></i>
                        </div>
                    </div>
                    <div class="ml-4 min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-600 truncate">Tier Company</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $tierCompanies }}</p>
                    </div>
                </div>
            </div>

            <!-- Company Active (Green) -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-lg"></i>
                        </div>
                    </div>
                    <div class="ml-4 min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-600 truncate">Company Active</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $activeCompanies }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
