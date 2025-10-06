import React from "react"
import ReactDOM from "react-dom/client"
import ElectricBorder from "@/components/ElectricBorder"

function ImageCard() {
    return (
        <ElectricBorder color="#7df9ff" speed={1.5} chaos={1} thickness={2} style={{ borderRadius: "1rem" }}>
        <div id="imageSlider" className="relative w-96 h-[520px] rounded-2xl shadow-2xl overflow-hidden bg-white/50 cursor-pointer">
            {/* Slideshow Images */}
            <div className="image-slider active" data-slide="0">
            <img className="w-full h-full object-cover" src="/img/login1.jpeg" alt="Image 1" />
            </div>
            <div className="image-slider" data-slide="1">
            <img className="w-full h-full object-cover" src="/img/login2.jpg" alt="Image 2" />
            </div>
            <div className="image-slider" data-slide="2">
            <img className="w-full h-full object-cover" src="/img/login4.jpg" alt="Image 3" />
            </div>

            {/* Overlay */}
            <div className="absolute inset-0 bg-gradient-to-b from-black/20 via-transparent to-black/90 z-[1]"></div>

            {/* Text */}
            <div className="absolute bottom-0 left-0 p-8 text-white z-10">
            <h3 className="text-4xl font-bold tracking-tight">Welcome !</h3>
            <p className="text-white mt-3 font-normal text-base">Visualize your growth with our dashboard.</p>
            </div>

            {/* Dots */}
            <div className="absolute bottom-6 right-6 flex gap-2 z-10">
            <div className="slide-dot w-2 h-2 rounded-full bg-white active" data-index="0"></div>
            <div className="slide-dot w-2 h-2 rounded-full bg-white/40" data-index="1"></div>
            <div className="slide-dot w-2 h-2 rounded-full bg-white/40" data-index="2"></div>
            </div>
        </div>
        </ElectricBorder>
    )
    }

    function LoginCard() {
    return (
        <ElectricBorder color="#5227FF" speed={1.2} chaos={0.8} thickness={2} style={{ borderRadius: "1rem" }}>
        <form method="POST" action="/login" id="loginForm" className="w-full px-10 py-12 bg-white rounded-2xl shadow-lg flex flex-col items-center">
            <h2 className="text-4xl text-gray-900 font-semibold">Sign in</h2>
            {/* ... isi form login kamu yang tadi ... */}
        </form>
        </ElectricBorder>
    )
    }

    const imageRoot = document.getElementById("react-image-card")
    if (imageRoot) {
    ReactDOM.createRoot(imageRoot).render(<ImageCard />)
    }

    const loginRoot = document.getElementById("react-login-card")
    if (loginRoot) {
    ReactDOM.createRoot(loginRoot).render(<LoginCard />)
}
