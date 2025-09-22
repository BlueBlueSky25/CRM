{{-- Single Hospital Distribution Card Component --}}
<div class="bg-white rounded-xl shadow-lg p-6 card-hover fade-in">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-800">Distribusi Rumah Sakit</h3>
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
        <button class="control-btn active" onclick="switchChart('type')" id="btn-type">Tipe RS</button>
        <button class="control-btn" onclick="switchChart('city')" id="btn-city">Kota</button>
        <button class="control-btn" onclick="switchChart('status')" id="btn-status">Status</button>
    </div>
    
    <!-- Chart Container -->
    <div class="relative h-80">
        <canvas id="inds"></canvas>
    </div>
    
    <!-- Custom Legend - Now Clickable -->
    <div class="custom-legend" id="customLegend"></div>
    
    <!-- Stats Row - Now Clickable -->
    <div class="stats-row">
        <div class="stat-item clickable-stat" onclick="showStatDetail('total')">
            <div class="stat-number" id="totalHospitals">42</div>
            <div class="stat-label">Total RS</div>
        </div>
        <div class="stat-item clickable-stat" onclick="showStatDetail('category')">
            <div class="stat-number" id="typeCount">4</div>
            <div class="stat-label">Kategori</div>
        </div>
        <div class="stat-item clickable-stat" onclick="showStatDetail('city')">
            <div class="stat-number" id="cityCount">3</div>
            <div class="stat-label">Kota</div>
        </div>
    </div>
</div>

<!-- Fullscreen Overlay -->
<div class="fullscreen-overlay" id="fullscreenOverlay">
    <div class="fullscreen-content">
        <i class="fas fa-times close-fullscreen" onclick="closeFullscreen()"></i>
        <h2 class="text-2xl font-bold mb-6">Distribusi Rumah Sakit - Detail View</h2>
        <div class="chart-controls mb-4">
            <button class="control-btn active" onclick="switchChart('type')" id="fs-btn-type">Tipe RS</button>
            <button class="control-btn" onclick="switchChart('city')" id="fs-btn-city">Kota</button>
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
// Data Rumah Sakit dengan detail lengkap
const hospitalData = {
    type: {
        labels: ['Tipe A', 'Tipe B', 'Tipe C', 'Tipe D'],
        data: [6, 14, 18, 4],
        colors: ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0'],
        details: {
            'Tipe A': [
                'RS Premier Jatinegara', 'RS Pondok Indah', 'RS Siloam Kebun Jeruk',
                'RS Metropolitan Medical Centre', 'RS Mayapada Jakarta Selatan', 'RS Hermina Kemayoran'
            ],
            'Tipe B': [
                'RS Santo Borromeus', 'RS Al-Islam', 'RS Advent Bandung', 'RS Hasan Sadikin',
                'RS Rajawali Citra', 'RS Immanuel', 'RS Santosa', 'RS Hermina Arcamanik',
                'RS Salamun', 'RS Cahaya Kawaluyaan', 'RS Melinda', 'RS AMC Cileunyi',
                'RS Pindad', 'RS Kartika Husada'
            ],
            'Tipe C': [
                'RS Cibabat', 'RS Sartika Asih', 'RS Hermina Pandanaran', 'RS Muhammadiyah',
                'RS Advent Bandung', 'RS Dustira', 'RS TK II Dustira', 'RS Bhayangkara',
                'RS Lavalette', 'RS Cahaya Kawaluyaan', 'RS Melinda 2', 'RS AMC',
                'RS Pindad 2', 'RS Kartika 2', 'RS Santo 2', 'RS Al-Islam 2', 'RS Advent 2', 'RS Hasan 2'
            ],
            'Tipe D': [
                'RS Kecil Cimahi', 'RS Pratama Bandung', 'RS Klinik Utama', 'RS Bersalin Melati'
            ]
        }
    },
    city: {
        labels: ['Kota Bandung', 'Kota Cimahi', 'Kab. Bandung'],
        data: [35, 4, 3],
        colors: ['#ff6384', '#36a2eb', '#ffce56'],
        details: {
            'Kota Bandung': Array(35).fill('RS').map((rs, i) => `${rs} Bandung ${i+1}`),
            'Kota Cimahi': Array(4).fill('RS').map((rs, i) => `${rs} Cimahi ${i+1}`),
            'Kab. Bandung': Array(3).fill('RS').map((rs, i) => `${rs} Kabupaten ${i+1}`)
        }
    },
    status: {
        labels: ['Aktif', 'Riset Kontak', 'Status Update'],
        data: [28, 8, 6],
        colors: ['#4ade80', '#fbbf24', '#f87171'],
        details: {
            'Aktif': Array(28).fill('RS').map((rs, i) => `${rs} Aktif ${i+1}`),
            'Riset Kontak': Array(8).fill('RS').map((rs, i) => `${rs} Research ${i+1}`),
            'Status Update': Array(6).fill('RS').map((rs, i) => `${rs} Update ${i+1}`)
        }
    }
};

let currentChart = null;
let currentView = 'type';
let isFullscreen = false;

// Inisialisasi Chart
function initChart() {
    const ctx = document.getElementById('inds').getContext('2d');
    createChart(ctx, currentView);
}

function createChart(ctx, view) {
    if (currentChart) {
        currentChart.destroy();
    }

    const data = hospitalData[view];
    
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
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return `${context.label}: ${context.parsed} RS (${percentage}%)`;
                        }
                    }
                }
            },
            onClick: (event, elements) => {
                if (elements.length > 0) {
                    const elementIndex = elements[0].index;
                    const label = data.labels[elementIndex]; // Pastikan label diambil dengan benar
                    showChartDetail(label, view); // Pastikan view sesuai
                }
            },
            onHover: (event, elements) => {
                event.native.target.style.cursor = elements.length > 0 ? 'pointer' : 'default';
            }
        }
    });

    createCustomLegend(data);
}

function createCustomLegend(data) {
    const legendContainer = document.getElementById('customLegend');
    legendContainer.innerHTML = '';
    
    data.labels.forEach((label, index) => {
        const legendItem = document.createElement('div');
        legendItem.className = 'legend-item';
        legendItem.onclick = () => showChartDetail(label, currentView);
        
        legendItem.innerHTML = `
            <div class="legend-color" style="background-color: ${data.colors[index]}"></div>
            <span>${label} (${data.data[index]})</span>
        `;
        
        legendContainer.appendChild(legendItem);
    });
}

// Show detailed data popup for chart segments
function showChartDetail(label, view) {
    const data = hospitalData[view];
    const details = data.details[label] || []; // Pastikan ini mengambil detail yang benar
    
    // Cek di sini jika details tidak kosong
    console.log('Details:', details);
    
    // Update isi popup
    const bodyContent = `
        <div class="stat-highlight">
            <div class="number">${details.length}</div>
            <div class="label">Total ${label}</div>
        </div>
        <h4>Daftar Rumah Sakit:</h4>
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Rumah Sakit</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                ${details.map((hospital, index) => `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${hospital}</td>
                        <td><span>Status</span></td>
                        <td><button>Detail</button></td>
                    </tr>
                `).join('')}
            </tbody>
        </table>
    `;
    
    document.getElementById('popupBody').innerHTML = bodyContent;
    document.getElementById('dataPopup').classList.add('show');
}

// Show detailed stats popup
function showStatDetail(type) {
    let title = '';
    let content = '';
    
    switch(type) {
        case 'total':
            title = 'Total Rumah Sakit';
            const totalAll = Object.values(hospitalData.type.data).reduce((a, b) => a + b, 0);
            content = `
                <div class="stat-highlight">
                    <div class="number">${totalAll}</div>
                    <div class="label">Total Rumah Sakit</div>
                </div>
                
                <h4 style="margin: 16px 0 12px 0; color: #374151; font-weight: 600;">Breakdown by Type:</h4>
                
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tipe</th>
                            <th>Jumlah</th>
                            <th>Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${hospitalData.type.labels.map((label, index) => {
                            const percentage = ((hospitalData.type.data[index] / totalAll) * 100).toFixed(1);
                            return `
                                <tr>
                                    <td><span style="color: ${hospitalData.type.colors[index]}; font-weight: bold;">■</span> ${label}</td>
                                    <td>${hospitalData.type.data[index]} RS</td>
                                    <td>${percentage}%</td>
                                </tr>
                            `;
                        }).join('')}
                    </tbody>
                </table>
            `;
            break;
            
        case 'category':
            title = 'Kategori Rumah Sakit';
            content = `
                <div class="stat-highlight">
                    <div class="number">${hospitalData.type.labels.length}</div>
                    <div class="label">Jenis Kategori</div>
                </div>
                
                <h4 style="margin: 16px 0 12px 0; color: #374151; font-weight: 600;">Detail Kategori:</h4>
                
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Deskripsi</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span style="color: #ff6384; font-weight: bold;">■</span> Tipe A</td>
                            <td>RS Rujukan Tertinggi</td>
                            <td>6 RS</td>
                        </tr>
                        <tr>
                            <td><span style="color: #36a2eb; font-weight: bold;">■</span> Tipe B</td>
                            <td>RS Rujukan Regional</td>
                            <td>14 RS</td>
                        </tr>
                        <tr>
                            <td><span style="color: #ffce56; font-weight: bold;">■</span> Tipe C</td>
                            <td>RS Rujukan Kabupaten</td>
                            <td>18 RS</td>
                        </tr>
                        <tr>
                            <td><span style="color: #4bc0c0; font-weight: bold;">■</span> Tipe D</td>
                            <td>RS Pratama</td>
                            <td>4 RS</td>
                        </tr>
                    </tbody>
                </table>
            `;
            break;
            
        case 'city':
            title = 'Distribusi Kota';
            content = `
                <div class="stat-highlight">
                    <div class="number">${hospitalData.city.labels.length}</div>
                    <div class="label">Wilayah Cakupan</div>
                </div>
                
                <h4 style="margin: 16px 0 12px 0; color: #374151; font-weight: 600;">Detail Wilayah:</h4>
                
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Wilayah</th>
                            <th>Jumlah RS</th>
                            <th>Persentase</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${hospitalData.city.labels.map((label, index) => {
                            const total = hospitalData.city.data.reduce((a, b) => a + b, 0);
                            const percentage = ((hospitalData.city.data[index] / total) * 100).toFixed(1);
                            return `
                                <tr>
                                    <td><span style="color: ${hospitalData.city.colors[index]}; font-weight: bold;">■</span> ${label}</td>
                                    <td>${hospitalData.city.data[index]} RS</td>
                                    <td>${percentage}%</td>
                                    <td><span style="background: #10b981; color: white; padding: 2px 8px; border-radius: 12px; font-size: 11px;">Active</span></td>
                                </tr>
                            `;
                        }).join('')}
                    </tbody>
                </table>
            `;
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
    // Simulate data export
    alert('Data exported successfully! 📊\n\nFile: hospital_distribution.xlsx\nLocation: Downloads folder');
    closeDataPopup();
}

function switchChart(view) {
    currentView = view;
    
    // Update button states for both normal and fullscreen
    document.querySelectorAll('.control-btn').forEach(btn => btn.classList.remove('active'));
    document.getElementById(`btn-${view}`).classList.add('active');
    
    if (isFullscreen) {
        document.querySelectorAll('#fullscreenOverlay .control-btn').forEach(btn => btn.classList.remove('active'));
        document.getElementById(`fs-btn-${view}`).classList.add('active');
        
        const canvas = document.getElementById('fullscreenChart');
        if (canvas) {
            const ctx = canvas.getContext('2d');
            createFullscreenChart(ctx, view);
        }
    } else {
        const ctx = document.getElementById('inds').getContext('2d');
        createChart(ctx, view);
    }
    
    updateStats(view);
}

function updateStats(view) {
    const data = hospitalData[view];
    const total = data.data.reduce((a, b) => a + b, 0);
    
    document.getElementById('totalHospitals').textContent = total;
    document.getElementById('typeCount').textContent = data.labels.length;
    
    if (view === 'city') {
        document.getElementById('cityCount').textContent = data.labels.length;
    } else {
        document.getElementById('cityCount').textContent = hospitalData.city.labels.length;
    }
}

function refreshChart() {
    const refreshBtn = document.querySelector('.fa-sync-alt').parentElement;
    refreshBtn.style.transform = 'rotate(360deg)';
    
    setTimeout(() => {
        refreshBtn.style.transform = 'rotate(0deg)';
        switchChart(currentView);
        
        const notification = document.createElement('div');
        notification.innerHTML = '<i class="fas fa-check"></i> Data updated!';
        notification.style.cssText = 'position:fixed;top:20px;right:20px;background:#10b981;color:white;padding:12px 20px;border-radius:6px;z-index:1001;animation:slideIn 0.3s ease;';
        document.body.appendChild(notification);
        
        setTimeout(() => notification.remove(), 2000);
    }, 500);
}

function openFullscreen() {
    isFullscreen = true;
    const overlay = document.getElementById('fullscreenOverlay');
    overlay.style.display = 'block';
    document.body.style.overflow = 'hidden';
    
    // Sync button states
    document.querySelectorAll('#fullscreenOverlay .control-btn').forEach(btn => btn.classList.remove('active'));
    document.getElementById(`fs-btn-${currentView}`).classList.add('active');
    
    setTimeout(() => {
        const canvas = document.getElementById('fullscreenChart');
        if (canvas) {
            const ctx = canvas.getContext('2d');
            createFullscreenChart(ctx, currentView);
        }
    }, 200);
}

function createFullscreenChart(ctx, view) {
    // Destroy any existing fullscreen chart
    if (window.fullscreenChart) {
        window.fullscreenChart.destroy();
    }

    const data = hospitalData[view];
    
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
                        font: {
                            size: 16
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return `${context.label}: ${context.parsed} RS (${percentage}%)`;
                        }
                    }
                }
            },
            onClick: (event, elements) => {
                if (elements.length > 0) {
                    const elementIndex = elements[0].index;
                    const label = data.labels[elementIndex];
                    showChartDetail(label, view);
                }
            },
            onHover: (event, elements) => {
                event.native.target.style.cursor = elements.length > 0 ? 'pointer' : 'default';
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
        
        // Destroy fullscreen chart
        if (window.fullscreenChart) {
            window.fullscreenChart.destroy();
            window.fullscreenChart = null;
        }
    }
}

// Initialize
window.onload = function() {
    if (typeof Chart !== 'undefined') {
        initChart();
        updateStats(currentView);
    }
};

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
</script>