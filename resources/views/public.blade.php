    <!DOCTYPE html>
    <html lang="id">
    <head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/svg+xml" href="/vite.svg" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inotal Partners - Public</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.min.js"></script>
    </head>
    <body class="bg-gradient-primary min-h-screen">
    <!-- Header -->
    <header class="bg-gradient-to-r from-purple-600 to-indigo-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
            <div class="flex items-center space-x-4">
            <button class="text-white hover:text-purple-200 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <div class="text-white font-bold text-2xl tracking-wider">
                LI✗EN
            </div>
            </div>
            
            <div class="flex items-center space-x-4">
            <div class="relative">
                <button class="text-white hover:text-purple-200 transition-colors">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                </svg>
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
                </button>
            </div>
            
            <div class="flex items-center space-x-2 text-white">
                <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                <span class="text-sm font-medium">X</span>
                </div>
                <div>
                <div class="text-sm font-medium">Xie</div>
                <div class="text-xs text-purple-200">superadmin</div>
                </div>
            </div>
            </div>
        </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-purple-500 to-indigo-500 rounded-2xl p-8 mb-8 text-white">
        <h1 class="text-3xl font-bold mb-2">Selamat Datang di Dashboard Partnership</h1>
        <p class="text-lg opacity-90">Kelola partnership dan proposal perusahaan dengan mudah dan efisien</p>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mb-8">
        <!-- Total Partners -->
        <div class="bg-white rounded-xl p-6 shadow-card hover:shadow-card-hover transition-all duration-300 cursor-pointer border-l-4 border-blue-500">
            <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-lg">
                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Total Partners</p>
                <p class="text-2xl font-bold text-gray-900" id="total-partners">125</p>
            </div>
            </div>
        </div>

        <!-- Active Partners -->
        <div class="bg-white rounded-xl p-6 shadow-card hover:shadow-card-hover transition-all duration-300 cursor-pointer border-l-4 border-green-500">
            <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-lg">
                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Partners Aktif</p>
                <p class="text-2xl font-bold text-gray-900" id="active-partners">98</p>
            </div>
            </div>
        </div>

        <!-- Proposals Sent -->
        <div class="bg-white rounded-xl p-6 shadow-card hover:shadow-card-hover transition-all duration-300 cursor-pointer border-l-4 border-purple-500">
            <div class="flex items-center">
            <div class="p-3 bg-purple-100 rounded-lg">
                <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a1 1 0 001 1h6a1 1 0 001-1V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm2.707 4.707a1 1 0 000-1.414L8.414 6.586a1 1 0 10-1.414 1.414L8.586 9.586a1 1 0 001.414 0z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Proposal Terkirim</p>
                <p class="text-2xl font-bold text-gray-900" id="proposals-sent">342</p>
            </div>
            </div>
        </div>

        <!-- Acceptance Rate -->
        <div class="bg-white rounded-xl p-6 shadow-card hover:shadow-card-hover transition-all duration-300 cursor-pointer border-l-4 border-indigo-500">
            <div class="flex items-center">
            <div class="p-3 bg-indigo-100 rounded-lg">
                <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Tingkat Konversi</p>
                <p class="text-2xl font-bold text-gray-900" id="conversion-rate">72%</p>
            </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white rounded-xl p-6 shadow-card hover:shadow-card-hover transition-all duration-300 cursor-pointer border-l-4 border-yellow-500">
            <div class="flex items-center">
            <div class="p-3 bg-yellow-100 rounded-lg">
                <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Total Pendapatan</p>
                <p class="text-2xl font-bold text-gray-900" id="total-revenue">Rp 12.5M</p>
            </div>
            </div>
        </div>

        <!-- Active Teams -->
        <div class="bg-white rounded-xl p-6 shadow-card hover:shadow-card-hover transition-all duration-300 cursor-pointer border-l-4 border-red-500">
            <div class="flex items-center">
            <div class="p-3 bg-red-100 rounded-lg">
                <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Tim Aktif</p>
                <p class="text-2xl font-bold text-gray-900" id="active-teams">24</p>
            </div>
            </div>
        </div>
        </div>

        <!-- Regions Section -->
        <div class="bg-white rounded-2xl p-8 shadow-card mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Distribusi Partner Berdasarkan Wilayah</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4" id="regions-container">
            <!-- Regions will be populated by JavaScript -->
        </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8">
        <!-- Bar Chart -->
        <div class="bg-white rounded-2xl p-8 shadow-card">
            <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Distribusi Partnership</h2>
            <button class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                </svg>
            </button>
            </div>
            <div class="relative h-80">
            <canvas id="partnershipChart"></canvas>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="bg-white rounded-2xl p-8 shadow-card">
            <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Distribusi Industri</h2>
            <button class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                </svg>
            </button>
            </div>
            <div class="relative h-80">
            <canvas id="industryChart"></canvas>
            </div>
        </div>
        </div>

        <!-- Partnership List -->
        <div class="bg-white rounded-2xl p-8 shadow-card">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Partner Perusahaan</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="companies-list">
            <!-- Companies will be populated by JavaScript -->
        </div>
        </div>
    </main>

    <!-- Modal for Company Details -->
    <div id="companyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full max-h-90vh overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-900" id="modalCompanyName"></h3>
            <button id="closeModal" class="text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            </button>
        </div>
        <div id="modalContent">
            <!-- Modal content will be populated by JavaScript -->
        </div>
        </div>
    </div>

    <script type="module" src="/src/main.js"></script>
    </body>
    </html>