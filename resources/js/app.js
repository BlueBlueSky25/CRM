import './bootstrap';
import 'flowbite';
import Chart from 'chart.js/auto';

const geo = document.getElementById('geo');
if (geo) {
    new Chart(geo, {
        type: 'bar',
        data: {
            labels: ['Jakarta', 'Bandung', 'Surabaya', 'Medan', 'Bali'],
            datasets: [{
                label: 'Jumlah Customer',
                data: [120, 90, 150, 70, 60],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)'
                ],
                borderColor: [
                    'rgb(54, 162, 235)',
                    'rgb(255, 99, 132)',
                    'rgb(255, 206, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(153, 102, 255)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
}

const proposal = document.getElementById('proposal');
if (proposal) {
    new Chart(proposal, {
        type: 'bar',
        data: {
            labels: ['Sukses', 'Menunggu', 'Ditolak', 'Dipantau'],
            datasets: [{
                label: 'Jumlah Customer',
                data: [88, 11, 55, 77],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)'
                ],
                borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 206, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(153, 102, 255)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
}

const trend = document.getElementById('trend');
if (trend) {
    new Chart(trend, {
        type: 'line',
        data: {
            labels: ['Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep'],
            datasets: [
                {
                    label: 'Proposal',
                    data: [50, 44, 52, 40, 60, 62],
                    borderColor: 'rgba(34, 197, 94, 1)', // hijau
                    backgroundColor: 'rgba(34, 197, 94, 0.2)',
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Customer',
                    data: [300, 350, 420, 450, 500, 490],
                    borderColor: 'rgba(59, 130, 246, 1)', // biru
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    tension: 0.3,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}


// Data Rumah Sakit (dari Excel yang lo kasih)
const hospitalData = {
    type: {
        labels: ['Tipe A', 'Tipe B', 'Tipe C', 'Tipe D'],
        data: [6, 14, 18, 4],
        colors: ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0'],
        details: {
            'Tipe A': ['RS 1', 'RS 2',],
            'Tipe B': ['RS 3', 'RS 4',],
            // Pastikan semua tipe memiliki detail
        }
    },
    city: {
        labels: ['Kota Bandung', 'Kota Cimahi',],
        data: [35, 4, 3],
        colors: ['#ff6384', '#36a2eb',],
        details: {
            'Kota Bandung': ['RS 1', 'RS 2',],
            // Pastikan semua kota memiliki detail
        }
    },
    // Pastikan juga status memiliki detail
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
                    display: false // Hide default legend
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
                    const value = data.data[elementIndex];
                    alert(`Detail: ${label}\nJumlah: ${value} Rumah Sakit\n\n(Ini nanti bisa redirect ke halaman detail)`);
                }
            },
            onHover: (event, elements) => {
                event.native.target.style.cursor = elements.length > 0 ? 'pointer' : 'default';
            }
        }
    });

    // Create custom legend
    createCustomLegend(data);
}

function createCustomLegend(data) {
    const legendContainer = document.getElementById('customLegend');
    legendContainer.innerHTML = '';
    
    data.labels.forEach((label, index) => {
        const legendItem = document.createElement('div');
        legendItem.className = 'legend-item';
        legendItem.onclick = () => toggleDataset(index);
        
        legendItem.innerHTML = `
            <div class="legend-color" style="background-color: ${data.colors[index]}"></div>
            <span>${label} (${data.data[index]})</span>
        `;
        
        legendContainer.appendChild(legendItem);
    });
}

function toggleDataset(index) {
    const meta = currentChart.getDatasetMeta(0);
    meta.data[index].hidden = !meta.data[index].hidden;
    currentChart.update();
}

function switchChart(view) {
    currentView = view;
    
    // Update button states
    document.querySelectorAll('.control-btn').forEach(btn => btn.classList.remove('active'));
    document.getElementById(`btn-${view}`).classList.add('active');
    if (isFullscreen) {
        document.getElementById(`fs-btn-${view}`).classList.add('active');
    }
    
    // Update chart
    const ctx = isFullscreen ? 
        document.getElementById('fullscreenChart').getContext('2d') : 
        document.getElementById('inds').getContext('2d');
    createChart(ctx, view);
    
    // Update stats
    updateStats(view);
}

function updateStats(view) {
    const data = hospitalData[view];
    const total = data.data.reduce((a, b) => a + b, 0);
    
    document.getElementById('totalHospitals').textContent = total;
    document.getElementById('typeCount').textContent = data.labels.length;
    
    // Update city count based on current view
    if (view === 'city') {
        document.getElementById('cityCount').textContent = data.labels.length;
    } else {
        document.getElementById('cityCount').textContent = hospitalData.city.labels.length;
    }
}

function refreshChart() {
    // Simulate data refresh (nanti bisa fetch dari API)
    const refreshBtn = document.querySelector('.fa-sync-alt').parentElement;
    refreshBtn.style.transform = 'rotate(360deg)';
    
    setTimeout(() => {
        refreshBtn.style.transform = 'rotate(0deg)';
        switchChart(currentView);
        
        // Show success notification (optional)
        const notification = document.createElement('div');
        notification.innerHTML = '<i class="fas fa-check"></i> Data updated!';
        notification.style.cssText = 'position:fixed;top:20px;right:20px;background:#10b981;color:white;padding:12px 20px;border-radius:6px;z-index:1001;animation:slideIn 0.3s ease;';
        document.body.appendChild(notification);
        
        setTimeout(() => notification.remove(), 2000);
    }, 500);
}

function openFullscreen() {
    isFullscreen = true;
    document.getElementById('fullscreenOverlay').style.display = 'block';
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => {
        const ctx = document.getElementById('fullscreenChart').getContext('2d');
        createChart(ctx, currentView);
    }, 100);
}

function closeFullscreen() {
    isFullscreen = false;
    document.getElementById('fullscreenOverlay').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Initialize on page load
window.onload = function() {
    initChart();
    updateStats(currentView);
};

// Handle ESC key for fullscreen
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && isFullscreen) {
        closeFullscreen();
    }
});