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

const inds = document.getElementById('inds');
if (inds) {
    new Chart(inds, {
        type: 'pie',
        data: {
            labels: ['Rumah Sakit', 'UMKM', 'Cafe', 'Pendidikan', 'Lainnya'],
            datasets: [{
                label: 'Jumlah Customer',
                data: [2, 10, 6, 5, 9],
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
