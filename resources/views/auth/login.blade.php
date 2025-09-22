<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Login</title>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">

<div class="flex w-full max-w-6xl mx-auto min-h-[700px] bg-white rounded-3xl shadow-2xl overflow-hidden">
    <!-- Image Card -->
    <div class="hidden md:flex w-1/2 items-center justify-center bg-gradient-to-br from-indigo-50 to-blue-100">
        <div class="relative w-96 h-[520px] rounded-2xl shadow-xl overflow-hidden bg-white/50">
            <img class="absolute inset-0 w-full h-full object-cover" src="/img/chart.jpeg" alt="IMAGE CHART LOGIN">
            <div class="absolute inset-0 bg-gradient-to-b from-black/30 to-black/70"></div>
            <div class="absolute bottom-0 left-0 p-8 text-white z-10">
                <h3 class="text-3xl font-bold drop-shadow-lg">Welcome Back!</h3>
                <p class="text-gray-100/80 mt-2 font-light drop-shadow">Visualize your growth with our dashboard.</p>
            </div>
        </div>
    </div>
    <!-- Form Card -->
    <div class="w-full md:w-1/2 flex items-center justify-center">
        <form method="POST" action="{{ route('login.process') }}"
            class="w-full max-w-md px-10 py-12 bg-white rounded-2xl shadow-lg flex flex-col items-center">
            <h2 class="text-4xl text-gray-900 font-semibold">Sign in</h2>
            @csrf

            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="w-full mb-4 p-3 rounded-lg bg-red-100 text-red-700 text-sm text-center">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="flex items-center gap-4 w-full my-5">
                <div class="w-full h-px bg-gray-300/90"></div>
                <p class="text-nowrap text-sm text-gray-400/90">Silahkan Login Terlebih Dahulu</p>
                <div class="w-full h-px bg-gray-300/90"></div>
            </div>

            <div class="flex items-center w-full bg-transparent border border-gray-300/60 h-12 rounded-full overflow-hidden pl-6 gap-2 mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" 
                    fill="none" 
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="#6B7280"
                    class="w-5 h-5">
                    <path stroke-linecap="round" 
                        stroke-linejoin="round" 
                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 1115 0v.75H4.5v-.75z" />
                </svg>

                <input type="text" name="username" placeholder="Username" 
                    class="bg-transparent text-gray-700 placeholder-gray-400 outline-none text-sm w-full h-full" required>
            </div>

            <div class="flex items-center w-full bg-transparent border border-gray-300/60 h-12 rounded-full overflow-hidden pl-6 gap-2 mt-4 mb-2">
                <svg width="13" height="17" viewBox="0 0 13 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13 8.5c0-.938-.729-1.7-1.625-1.7h-.812V4.25C10.563 1.907 8.74 0 6.5 0S2.438 1.907 2.438 4.25V6.8h-.813C.729 6.8 0 7.562 0 8.5v6.8c0 .938.729 1.7 1.625 1.7h9.75c.896 0 1.625-.762 1.625-1.7zM4.063 4.25c0-1.406 1.093-2.55 2.437-2.55s2.438 1.144 2.438 2.55V6.8H4.061z" fill="#6B7280"/>
                </svg>
                <input type="password" name="password" placeholder="Password" class="bg-transparent text-gray-700 placeholder-gray-400 outline-none text-sm w-full h-full" required>
            </div>

            <div class="w-full flex items-center justify-between mt-6 text-gray-500/80">
                <div class="flex items-center gap-2">
                    <input class="h-5 w-5 rounded border-gray-300 focus:ring-indigo-400" type="checkbox" id="remember" name="remember">
                    <label class="text-sm" for="remember">Remember me</label>
                </div>
                <a class="text-sm underline hover:text-indigo-600" href="#">Forgot password?</a>
            </div>

            <button type="submit" class="mt-8 w-full h-11 rounded-full text-white bg-indigo-500 hover:bg-indigo-600 transition font-semibold shadow-md">
                Login
            </button>
        </form>
    </div>
</div>

</body>
</html>