<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ATS | Akademik Takip Sistemi</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        .hero-circles {
            position: absolute;
            right: -10%;
            top: 50%;
            transform: translateY(-50%);
            z-index: 0;
        }
    </style>
</head>
<body class="bg-[#FDFDFC] antialiased min-h-screen flex flex-col items-center justify-center p-6 lg:p-20 overflow-hidden">

    <header class="fixed top-0 w-full p-6 flex justify-between items-center max-w-7xl">
        <div class="flex items-center gap-2">
            <div class="w-10 h-10 bg-amber-500 rounded-lg flex items-center justify-center shadow-lg">
                <span class="text-white font-black text-xl">A</span>
            </div>
            <span class="text-2xl font-black tracking-tighter text-slate-900">ATS<span class="text-amber-500">.</span></span>
        </div>
        
        @if (Route::has('login'))
            <nav class="space-x-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-slate-700 hover:text-amber-500 transition">Panele Dön</a>
                @else
                    <a href="{{ route('register') }}" class="text-sm font-bold text-slate-700 hover:text-amber-500 transition">Kayıt Ol</a>
                @endauth
            </nav>
        @endif
    </header>

    <main class="relative w-full max-w-7xl flex flex-col lg:flex-row items-center justify-between z-10">
        
        <div class="lg:w-1/2 space-y-8 text-center lg:text-left">
            <div class="space-y-2">
                <h1 class="text-6xl lg:text-8xl font-black text-slate-900 leading-none tracking-tighter">
                    Akademik <br>
                    <span class="text-amber-500">Takip</span> <br>
                    Sistemi
                </h1>
                <p class="text-lg text-slate-500 max-w-md mx-auto lg:mx-0 leading-relaxed">
                    Öğrencilerinizin ödevlerini, gelişimlerini ve ilerlemelerini tek panelden kolayca yönetin. Eğitim artık daha dijital, daha kontrollü!
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                <a href="{{ route('login') }}" class="group relative flex items-center justify-between w-64 bg-amber-500 hover:bg-amber-600 p-4 rounded-full text-white font-extrabold shadow-2xl shadow-amber-500/30 transition-all transform hover:scale-105">
                    <span class="ml-4 uppercase tracking-widest text-sm">Öğrenci Girişi</span>
                    <div class="bg-white w-10 h-10 rounded-full flex items-center justify-center group-hover:translate-x-1 transition-transform">
                        <span class="text-amber-500 text-xl">→</span>
                    </div>
                </a>

                <a href="{{ route('login') }}" class="group relative flex items-center justify-between w-64 border-2 border-amber-500 p-4 rounded-full text-amber-600 font-extrabold hover:bg-amber-50 transition-all transform hover:scale-105">
                    <span class="ml-4 uppercase tracking-widest text-sm">Öğretmen Girişi</span>
                    <div class="bg-amber-500 w-10 h-10 rounded-full flex items-center justify-center group-hover:translate-x-1 transition-transform">
                        <span class="text-white text-xl">→</span>
                    </div>
                </a>
            </div>

           
        </div>

        <div class="lg:w-1/2 relative mt-20 lg:mt-0 flex justify-center">
            <div class="absolute w-[300px] h-[300px] lg:w-[500px] lg:h-[500px] border-[16px] border-amber-500/10 rounded-full"></div>
            <div class="absolute w-[350px] h-[350px] lg:w-[600px] lg:h-[600px] border-[2px] border-amber-500/20 rounded-full animate-spin-slow"></div>
            
            <div class="relative z-10 w-full flex justify-center">
                 <div class="bg-slate-200 rounded-3xl w-72 h-96 lg:w-96 lg:h-[500px] overflow-hidden shadow-2xl border-8 border-white">
                    {{-- Şeffaf öğrenci görselini buraya img etiketiyle ekleyebilirsin --}}
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-amber-100 to-amber-50">
                        <span class="text-amber-500 font-black text-9xl opacity-20">ATS</span>
                    </div>
                 </div>
            </div>
            
        </div>
    </main>

    <div class="fixed bottom-10 left-10 opacity-20 hidden lg:block">
        <div class="grid grid-cols-5 gap-2">
            @for ($i = 0; $i < 25; $i++)
                <div class="w-2 h-2 bg-amber-500 rounded-full"></div>
            @endfor
        </div>
    </div>

</body>
</html>