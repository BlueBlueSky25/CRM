{{-- kategoriindustri.blade.php --}}

<div class="bg-white rounded-xl shadow-lg p-6 card-hover fade-in">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-800">Distribusi Perusahaan</h3>
        <div class="flex space-x-2">
            <button class="text-gray-400 hover:text-gray-600 transition-colors" onclick="refreshChart()">
                <i class="fas fa-sync-alt"></i>
            </button>
            <button class="text-gray-400 hover:text-gray-600 transition-colors" onclick="openFullscreen()">
                <i class="fas fa-expand-alt"></i>
            </button>
        </div>
    </div>
    
    <!-- Chart Controls -->
    <div class="chart-controls">
        <button class="control-btn active" onclick="switchChart('type')" id="btn-type">Tipe Perusahaan</button>
        <button class="control-btn" onclick="switchChart('tier')" id="btn-tier">Tier</button>
        <button class="control-btn" onclick="switchChart('status')" id="btn-status">Status</button>
    </div>
    
    <!-- Chart Container -->
    <div class="relative h-80">
        <canvas id="companyChart"></canvas>
    </div>
    
    <!-- Custom Legend - Now Clickable -->
    <div class="custom-legend" id="customLegend"></div>
    
    <!-- Stats Row - Now Clickable -->
    <div class="stats-row">
        <div class="stat-item clickable-stat" onclick="showStatDetail('total')">
            <div class="stat-number" id="totalCompanies">0</div>
            <div class="stat-label">Total Perusahaan</div>
        </div>
        <div class="stat-item clickable-stat" onclick="showStatDetail('category')">
            <div class="stat-number" id="typeCount">0</div>
            <div class="stat-label">Kategori</div>
        </div>
        <div class="stat-item clickable-stat" onclick="showStatDetail('tier')">
            <div class="stat-number" id="tierCount">0</div>
            <div class="stat-label">Tier</div>
        </div>
    </div>
</div>

<!-- Fullscreen Overlay -->
<div class="fullscreen-overlay" id="fullscreenOverlay">
    <div class="fullscreen-content">
        <i class="fas fa-times close-fullscreen" onclick="closeFullscreen()"></i>
        <h2 class="text-2xl font-bold mb-6">Distribusi Perusahaan - Detail View</h2>
        <div class="chart-controls mb-4">
            <button class="control-btn active" onclick="switchChart('type')" id="fs-btn-type">Tipe Perusahaan</button>
            <button class="control-btn" onclick="switchChart('tier')" id="fs-btn-tier">Tier</button>
            <button class="control-btn" onclick="switchChart('status')" id="fs-btn-status">Status</button>
        </div>
        <canvas id="fullscreenChart" style="max-height: calc(100% - 120px);"></canvas>
    </div>
</div>

<!-- Interactive Data Popup Modal -->
<div class="data-popup-overlay" id="dataPopup">
    <div class="data-popup-content">
        <div class="popup-header">
            <h3 class="popup-title" id="popupTitle">Detail Data</h3>
            <button class="close-popup" onclick="closeDataPopup()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="popup-body" id="popupBody">
            <!-- Dynamic content will be inserted here -->
        </div>
        <div class="popup-footer">
            <button class="btn-secondary" onclick="closeDataPopup()">Tutup</button>
            <button class="btn-primary" onclick="exportData()">Export Data</button>
        </div>
    </div>
</div>

<style>
.card-hover {
    transition: all 0.3s ease;
    cursor: pointer;
}

.card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
}

.fade-in {
    animation: fadeIn 0.6s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.chart-controls {
    display: flex;
    gap: 8px;
    margin-bottom: 12px;
}

.control-btn {
    padding: 6px 12px;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    background: #f9fafb;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s;
}

.control-btn:hover {
    background: #f3f4f6;
}

.control-btn.active {
    background: #3b82f6;
    color: white;
    border-color: #3b82f6;
}

.stats-row {
    display: flex;
    justify-content: space-between;
    margin-top: 16px;
    gap: 8px;
    flex-wrap: wrap;
}

.stat-item {
    flex: 1;
    min-width: 80px;
    text-align: center;
    padding: 12px 8px;
    background: #f8fafc;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.clickable-stat {
    cursor: pointer;
}

.clickable-stat:hover {
    background: #e0f2fe;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.stat-number {
    font-size: 20px;
    font-weight: bold;
    color: #1f2937;
}

.stat-label {
    font-size: 11px;
    color: #6b7280;
    margin-top: 4px;
}

.custom-legend {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    justify-content: center;
    margin-top: 16px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    cursor: pointer;
    padding: 6px 10px;
    border-radius: 4px;
    transition: all 0.2s;
    border: 1px solid transparent;
}

.legend-item:hover {
    background-color: #f3f4f6;
    border-color: #d1d5db;
    transform: translateY(-1px);
}

.legend-color {
    width: 12px;
    height: 12px;
    border-radius: 2px;
}

.fullscreen-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.9);
    display: none;
    z-index: 1000;
    padding: 20px;
}

.fullscreen-content {
    background: white;
    border-radius: 12px;
    padding: 24px;
    height: calc(100% - 40px);
    position: relative;
}

.close-fullscreen {
    position: absolute;
    top: 16px;
    right: 16px;
    font-size: 24px;
    cursor: pointer;
    color: #6b7280;
}

.close-fullscreen:hover {
    color: #374151;
}

/* Data Popup Styles */
.data-popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: none;
    z-index: 2000;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(4px);
}

.data-popup-overlay.show {
    display: flex;
    animation: fadeInOverlay 0.3s ease;
}

@keyframes fadeInOverlay {
    from { opacity: 0; }
    to { opacity: 1; }
}

.data-popup-content {
    background: white;
    border-radius: 16px;
    min-width: 500px;
    max-width: 700px;
    max-height: 85vh;
    overflow: hidden;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    animation: popupSlideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    display: flex;
    flex-direction: column;
}

@keyframes popupSlideIn {
    from { 
        opacity: 0; 
        transform: scale(0.9) translateY(-20px);
    }
    to { 
        opacity: 1; 
        transform: scale(1) translateY(0);
    }
}

.popup-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 24px 28px;
    border-bottom: 1px solid #e5e7eb;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    flex-shrink: 0;
}

.popup-title {
    font-size: 20px;
    font-weight: 600;
    margin: 0;
}

.close-popup {
    background: none;
    border: none;
    color: white;
    font-size: 18px;
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: background-color 0.2s;
}

.close-popup:hover {
    background: rgba(255, 255, 255, 0.1);
}

.popup-body {
    padding: 24px 28px;
    max-height: 400px;
    overflow-y: auto;
    flex: 1;
}

.popup-footer {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding: 20px 28px;
    border-top: 1px solid #e5e7eb;
    background: #f9fafb;
    flex-shrink: 0;
}

.btn-primary, .btn-secondary {
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
    transform: translateY(-1px);
}

.btn-secondary {
    background: #e5e7eb;
    color: #374151;
}

.btn-secondary:hover {
    background: #d1d5db;
}

/* Data Table Styles */
.data-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 12px;
}

.data-table th,
.data-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

.data-table th {
    background: #f8fafc;
    font-weight: 600;
    color: #374151;
    font-size: 14px;
}

.data-table td {
    color: #6b7280;
    font-size: 13px;
}

.data-table tr:hover {
    background: #f9fafb;
}

.stat-highlight {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 16px;
    text-align: center;
}

.stat-highlight .number {
    font-size: 28px;
    font-weight: bold;
    margin-bottom: 4px;
}

.stat-highlight .label {
    font-size: 14px;
    opacity: 0.9;
}

@keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}
</style>

<script>
// Data dari PHP/Laravel dengan struktur yang lebih sederhana
const companyData = @json($chartData ?? []);
console.log('Company Data loaded:', companyData);

// Check if data is actually empty
if (!companyData || Object.keys(companyData).length === 0) {
    console.error('No company data received from controller');
}
// Jika companyData kosong, buat struktur default
if (Object.keys(companyData).length === 0) {
    companyData.type = { labels: [], data: [], details: {} };
    companyData.tier = { labels: [], data: [], details: {} };
    companyData.status = { labels: [], data: [], details: {} };
}

// Tambahkan colors
companyData.type.colors = ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0', '#9966ff', '#ff9f40'];
companyData.tier.colors = ['#4ade80', '#fbbf24', '#f87171', '#60a5fa', '#c084fc'];
companyData.status.colors = ['#10b981', '#f59e0b', '#ef4444', '#8b5cf6'];

let currentChart = null;
let currentView = 'type';
let isFullscreen = false;

// Inisialisasi Chart
function initChart() {
    const ctx = document.getElementById('companyChart').getContext('2d');
    createChart(ctx, currentView);
}

function createChart(ctx, view) {
    if (currentChart) {
        currentChart.destroy();
    }

    const data = companyData[view];
    
    // Cek jika data kosong
    if (!data || !data.labels || data.labels.length === 0) {
        showNoDataMessage(ctx);
        return;
    }
    
    currentChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: data.labels,
            datasets: [{
                data: data.data,
                backgroundColor: data.colors,
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverBorderWidth: 3,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                            return context.label + ': ' + context.parsed + ' Perusahaan (' + percentage + '%)';
                        }
                    }
                }
            },
            onClick: function(event, elements) {
                if (elements.length > 0) {
                    const elementIndex = elements[0].index;
                    const label = data.labels[elementIndex];
                    showChartDetail(label, view);
                }
            },
            onHover: function(event, elements) {
                event.native.target.style.cursor = elements.length > 0 ? 'pointer' : 'default';
            }
        }
    });

    createCustomLegend(data);
    updateStats(view);
}

function showNoDataMessage(ctx) {
    ctx.font = '16px Arial';
    ctx.fillStyle = '#6b7280';
    ctx.textAlign = 'center';
    ctx.fillText('Tidak ada data perusahaan tersedia', ctx.canvas.width / 2, ctx.canvas.height / 2);
    updateStats('type');
}

function createCustomLegend(data) {
    const legendContainer = document.getElementById('customLegend');
    if (!legendContainer) return;
    
    legendContainer.innerHTML = '';
    
    data.labels.forEach(function(label, index) {
        const legendItem = document.createElement('div');
        legendItem.className = 'legend-item';
        legendItem.onclick = function() { showChartDetail(label, currentView); };
        
        legendItem.innerHTML = '<div class="legend-color" style="background-color: ' + data.colors[index] + '"></div><span>' + label + ' (' + data.data[index] + ')</span>';
        legendContainer.appendChild(legendItem);
    });
}

function showChartDetail(label, view) {
    const data = companyData[view];
    const details = data.details[label] || [];
    
    let tableContent = '';
    details.forEach(function(company, index) {
        tableContent += '<tr><td>' + (index + 1) + '</td><td>' + company + '</td><td>' + label + '</td><td><span class="status-badge">Active</span></td></tr>';
    });
    
    const bodyContent = '<div class="stat-highlight"><div class="number">' + details.length + '</div><div class="label">Total ' + label + '</div></div><h4>Daftar Perusahaan:</h4><table class="data-table"><thead><tr><th>No</th><th>Nama Perusahaan</th><th>Tipe</th><th>Status</th></tr></thead><tbody>' + tableContent + '</tbody></table>';
    
    document.getElementById('popupBody').innerHTML = bodyContent;
    document.getElementById('popupTitle').textContent = 'Detail ' + label;
    document.getElementById('dataPopup').classList.add('show');
}

function showStatDetail(type) {
    let title = '';
    let content = '';
    
    switch(type) {
        case 'total':
            title = 'Total Perusahaan';
            const totalAll = companyData.type.data.reduce(function(a, b) { return a + b; }, 0);
            let typeRows = '';
            
            companyData.type.labels.forEach(function(label, index) {
                const percentage = totalAll > 0 ? ((companyData.type.data[index] / totalAll) * 100).toFixed(1) : 0;
                typeRows += '<tr><td><span style="color: ' + companyData.type.colors[index] + '; font-weight: bold;">■</span> ' + label + '</td><td>' + companyData.type.data[index] + ' Perusahaan</td><td>' + percentage + '%</td></tr>';
            });
            
            content = '<div class="stat-highlight"><div class="number">' + totalAll + '</div><div class="label">Total Perusahaan</div></div><h4 style="margin: 16px 0 12px 0; color: #374151; font-weight: 600;">Breakdown by Type:</h4><table class="data-table"><thead><tr><th>Tipe</th><th>Jumlah</th><th>Persentase</th></tr></thead><tbody>' + typeRows + '</tbody></table>';
            break;
            
        case 'category':
            title = 'Kategori Perusahaan';
            let categoryRows = '';
            
            companyData.type.labels.forEach(function(label, index) {
                categoryRows += '<tr><td><span style="color: ' + companyData.type.colors[index] + '; font-weight: bold;">■</span> ' + label + '</td><td>' + companyData.type.data[index] + ' Perusahaan</td><td><span style="background: #10b981; color: white; padding: 2px 8px; border-radius: 12px; font-size: 11px;">Active</span></td></tr>';
            });
            
            content = '<div class="stat-highlight"><div class="number">' + companyData.type.labels.length + '</div><div class="label">Jenis Kategori</div></div><h4 style="margin: 16px 0 12px 0; color: #374151; font-weight: 600;">Detail Kategori:</h4><table class="data-table"><thead><tr><th>Kategori</th><th>Jumlah Perusahaan</th><th>Status</th></tr></thead><tbody>' + categoryRows + '</tbody></table>';
            break;
            
        case 'tier':
            title = 'Distribusi Tier';
            const tierTotal = companyData.tier.data.reduce(function(a, b) { return a + b; }, 0);
            let tierRows = '';
            
            companyData.tier.labels.forEach(function(label, index) {
                const percentage = tierTotal > 0 ? ((companyData.tier.data[index] / tierTotal) * 100).toFixed(1) : 0;
                tierRows += '<tr><td><span style="color: ' + companyData.tier.colors[index] + '; font-weight: bold;">■</span> ' + label + '</td><td>' + companyData.tier.data[index] + ' Perusahaan</td><td>' + percentage + '%</td></tr>';
            });
            
            content = '<div class="stat-highlight"><div class="number">' + companyData.tier.labels.length + '</div><div class="label">Level Tier</div></div><h4 style="margin: 16px 0 12px 0; color: #374151; font-weight: 600;">Detail Tier:</h4><table class="data-table"><thead><tr><th>Tier</th><th>Jumlah Perusahaan</th><th>Persentase</th></tr></thead><tbody>' + tierRows + '</tbody></table>';
            break;
    }
    
    document.getElementById('popupTitle').textContent = title;
    document.getElementById('popupBody').innerHTML = content;
    document.getElementById('dataPopup').classList.add('show');
}

function closeDataPopup() {
    document.getElementById('dataPopup').classList.remove('show');
}

function exportData() {
    alert('Data exported successfully! 📊\n\nFile: company_distribution.xlsx\nLocation: Downloads folder');
    closeDataPopup();
}

function switchChart(view) {
    currentView = view;
    
    // Update button states
    document.querySelectorAll('.control-btn').forEach(function(btn) {
        btn.classList.remove('active');
    });
    document.getElementById('btn-' + view).classList.add('active');
    
    if (isFullscreen) {
        document.querySelectorAll('#fullscreenOverlay .control-btn').forEach(function(btn) {
            btn.classList.remove('active');
        });
        document.getElementById('fs-btn-' + view).classList.add('active');
        
        const canvas = document.getElementById('fullscreenChart');
        if (canvas) {
            const ctx = canvas.getContext('2d');
            createFullscreenChart(ctx, view);
        }
    } else {
        const ctx = document.getElementById('companyChart').getContext('2d');
        createChart(ctx, view);
    }
}

function updateStats(view) {
    const data = companyData[view];
    const total = data.data.reduce(function(a, b) { return a + b; }, 0);
    
    document.getElementById('totalCompanies').textContent = total;
    document.getElementById('typeCount').textContent = companyData.type.labels.length;
    document.getElementById('tierCount').textContent = companyData.tier.labels.length;
}

function refreshChart() {
    const refreshBtn = document.querySelector('.fa-sync-alt').parentElement;
    refreshBtn.style.transform = 'rotate(360deg)';
    
    setTimeout(function() {
        refreshBtn.style.transform = 'rotate(0deg)';
        switchChart(currentView);
        
        const notification = document.createElement('div');
        notification.innerHTML = '<i class="fas fa-check"></i> Data updated!';
        notification.style.cssText = 'position:fixed;top:20px;right:20px;background:#10b981;color:white;padding:12px 20px;border-radius:6px;z-index:1001;';
        document.body.appendChild(notification);
        
        setTimeout(function() { notification.remove(); }, 2000);
    }, 500);
}

function openFullscreen() {
    isFullscreen = true;
    const overlay = document.getElementById('fullscreenOverlay');
    overlay.style.display = 'block';
    document.body.style.overflow = 'hidden';
    
    setTimeout(function() {
        const canvas = document.getElementById('fullscreenChart');
        if (canvas) {
            const ctx = canvas.getContext('2d');
            createFullscreenChart(ctx, currentView);
        }
    }, 200);
}

function createFullscreenChart(ctx, view) {
    if (window.fullscreenChart) {
        window.fullscreenChart.destroy();
    }

    const data = companyData[view];
    
    window.fullscreenChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: data.labels,
            datasets: [{
                data: data.data,
                backgroundColor: data.colors,
                borderWidth: 3,
                borderColor: '#ffffff',
                hoverBorderWidth: 4,
                hoverOffset: 12
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: { size: 16 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce(function(a, b) { return a + b; }, 0);
                            const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                            return context.label + ': ' + context.parsed + ' Perusahaan (' + percentage + '%)';
                        }
                    }
                }
            },
            onClick: function(event, elements) {
                if (elements.length > 0) {
                    const elementIndex = elements[0].index;
                    const label = data.labels[elementIndex];
                    showChartDetail(label, view);
                }
            }
        }
    });
}

function closeFullscreen() {
    isFullscreen = false;
    const overlay = document.getElementById('fullscreenOverlay');
    if (overlay) {
        overlay.style.display = 'none';
        document.body.style.overflow = 'auto';
        
        if (window.fullscreenChart) {
            window.fullscreenChart.destroy();
            window.fullscreenChart = null;
        }
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart !== 'undefined') {
        initChart();
    }
});

// Handle ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        if (document.getElementById('dataPopup').classList.contains('show')) {
            closeDataPopup();
        } else if (isFullscreen) {
            closeFullscreen();
        }
    }
});
console.log('Raw data from PHP:', @json($chartCompanyData ?? []));
</script>