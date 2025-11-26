<div class="bg-white rounded-xl shadow-lg p-6 card-hover fade-in">
    <!-- Header with Filter Buttons + Date Range -->
    <div class="flex flex-col gap-4 mb-6">
        <!-- Title -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Trend Kunjungan Sales</h3>
            
        </div>
        
        <!-- Filter Buttons Row - Compact -->
        <div class="flex flex-wrap items-center gap-2">
            <button 
                data-trend-period="daily" 
                class="px-3 py-1.5 text-xs rounded-lg transition-all duration-200 bg-white text-gray-700 border border-gray-300 hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-300">
                Per Hari
            </button>
            <button 
                data-trend-period="monthly" 
                class="px-3 py-1.5 text-xs rounded-lg transition-all duration-200 bg-blue-500 text-white border border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-300">
                Per Bulan
            </button>
            <button 
                data-trend-period="yearly" 
                class="px-3 py-1.5 text-xs rounded-lg transition-all duration-200 bg-white text-gray-700 border border-gray-300 hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-300">
                Per Tahun
            </button>
            
            <!-- Divider -->
            <div class="h-6 w-px bg-gray-300 mx-1"></div>
            
            <!-- Custom Range Button -->
            <button 
                id="customRangeBtn"
                class="px-3 py-1.5 text-xs rounded-lg transition-all duration-200 bg-white text-gray-700 border border-gray-300 hover:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-300 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Custom Range
            </button>
        </div>
        
        <!-- Date Range Picker (Hidden by default) - COMPACT -->
        <div id="dateRangePicker" class="hidden bg-gray-50 border border-gray-200 rounded-lg p-3">
            <div class="flex flex-wrap items-end gap-2">
                <div class="flex-1 min-w-[140px]">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Start Date</label>
                    <input 
                        type="date" 
                        id="startDate"
                        class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-300 focus:border-purple-400">
                </div>
                
                <div class="flex-1 min-w-[140px]">
                    <label class="block text-xs font-medium text-gray-700 mb-1">End Date</label>
                    <input 
                        type="date" 
                        id="endDate"
                        class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-300 focus:border-purple-400">
                </div>
                
                <button 
                    id="applyDateRange"
                    class="px-3 py-1.5 text-xs bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-colors focus:outline-none focus:ring-2 focus:ring-purple-300">
                    Apply
                </button>
                
                <button 
                    id="cancelDateRange"
                    class="px-3 py-1.5 text-xs bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors focus:outline-none">
                    Cancel
                </button>
            </div>
        </div>
    </div>
    
    <!-- Chart Container -->
    <div class="relative h-80">
        <!-- Loading Indicator -->
        <div id="visitTrendLoading" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75" style="display: none;">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto mb-2"></div>
                <p class="text-sm text-gray-600">Loading data...</p>
            </div>
        </div>
        
        <!-- Canvas -->
        <canvas id="visitTrend"></canvas>
    </div>
    
    <!-- Statistics Section -->
    <div class="mt-6 grid grid-cols-2 gap-4 text-center">
        <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
            <div class="flex items-center justify-center gap-2 mb-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <div class="text-xs text-gray-600 font-medium">Total Kunjungan</div>
            </div>
            <div class="text-3xl font-bold text-blue-600" id="totalVisits">0</div>
        </div>
        
        <div class="bg-green-50 rounded-lg p-4 border border-green-100">
            <div class="flex items-center justify-center gap-2 mb-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                <div class="text-xs text-gray-600 font-medium">Rata-rata</div>
            </div>
            <div class="text-xl font-bold text-green-600" id="averageVisits">0</div>
        </div>
    </div>
    
    <!-- Info Text - SIMPLIFIED -->
    <div class="mt-4 text-center">
        <p class="text-xs text-gray-500">
            Grafik menunjukkan jumlah kunjungan sales per periode
        </p>
    </div>
</div>

<style>
    .card-hover {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Button hover effects */
    button[data-trend-period]:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    button[data-trend-period]:active {
        transform: translateY(0);
    }
</style>