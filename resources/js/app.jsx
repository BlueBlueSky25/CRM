// === Import Core ===
import React from "react";
import ReactDOM from "react-dom/client";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import CalendarPage from "@/pages/CalendarPage";

// === Import eksternal ===
import "./bootstrap";
import "flowbite";
import Chart from "chart.js/auto";

// =======================
//  REACT COMPONENT
// =======================
function Home() {
    return (
        <div className="p-6">
            <h1 className="text-3xl font-bold">Selamat Datang di CRM 🚀</h1>
            <p className="mt-2 text-gray-600">
                Ini halaman awal React. Akses{" "}
                <a href="/calendar" className="text-blue-500 underline">
                    /calendar
                </a>{" "}
                untuk buka kalender.
            </p>
        </div>
    );
}

function App() {
    return (
        <BrowserRouter>
            <Routes>
                <Route path="/" element={<Home />} />
                <Route path="/calendar" element={<CalendarPage />} />
            </Routes>
        </BrowserRouter>
    );
}

// =======================
//  RENDER REACT
// =======================
const rootElement = document.getElementById("app");
if (rootElement) {
    ReactDOM.createRoot(rootElement).render(
        <React.StrictMode>
            <App />
        </React.StrictMode>
    );
}

// =======================
//  CHART.JS LOGIC
// =======================

// Chart 1: Geo
const geo = document.getElementById("geo");
if (geo) {
    new Chart(geo, {
        type: "bar",
        data: {
            labels: ["Jakarta", "Bandung", "Surabaya", "Medan", "Bali"],
            datasets: [
                {
                    label: "Jumlah Customer",
                    data: [120, 90, 150, 70, 60],
                    backgroundColor: [
                        "rgba(54, 162, 235, 0.6)",
                        "rgba(255, 99, 132, 0.6)",
                        "rgba(255, 206, 86, 0.6)",
                        "rgba(75, 192, 192, 0.6)",
                        "rgba(153, 102, 255, 0.6)",
                    ],
                    borderColor: [
                        "rgb(54, 162, 235)",
                        "rgb(255, 99, 132)",
                        "rgb(255, 206, 86)",
                        "rgb(75, 192, 192)",
                        "rgb(153, 102, 255)",
                    ],
                    borderWidth: 1,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: "bottom" } },
        },
    });
}

// Chart 2: Proposal
const proposal = document.getElementById("proposal");
if (proposal) {
    new Chart(proposal, {
        type: "bar",
        data: {
            labels: ["Sukses", "Menunggu", "Ditolak", "Dipantau"],
            datasets: [
                {
                    label: "Jumlah Customer",
                    data: [88, 11, 55, 77],
                    backgroundColor: [
                        "rgba(255, 99, 132, 0.6)",
                        "rgba(54, 162, 235, 0.6)",
                        "rgba(255, 206, 86, 0.6)",
                        "rgba(75, 192, 192, 0.6)",
                        "rgba(153, 102, 255, 0.6)",
                    ],
                    borderColor: [
                        "rgb(255, 99, 132)",
                        "rgb(54, 162, 235)",
                        "rgb(255, 206, 86)",
                        "rgb(75, 192, 192)",
                        "rgb(153, 102, 255)",
                    ],
                    borderWidth: 1,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: "bottom" } },
        },
    });
}

// Chart 3: Trend
const trend = document.getElementById("trend");
if (trend) {
    new Chart(trend, {
        type: "line",
        data: {
            labels: ["Apr", "Mei", "Jun", "Jul", "Agu", "Sep"],
            datasets: [
                {
                    label: "Proposal",
                    data: [50, 44, 52, 40, 60, 62],
                    borderColor: "rgba(34, 197, 94, 1)",
                    backgroundColor: "rgba(34, 197, 94, 0.2)",
                    tension: 0.3,
                    fill: true,
                },
                {
                    label: "Customer",
                    data: [300, 350, 420, 450, 500, 490],
                    borderColor: "rgba(59, 130, 246, 1)",
                    backgroundColor: "rgba(59, 130, 246, 0.2)",
                    tension: 0.3,
                    fill: true,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: "bottom" } },
            scales: { y: { beginAtZero: true } },
        },
    });
}
