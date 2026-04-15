<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-white">
    <div class="min-h-screen flex flex-col sm:flex-row">
        
        <div class="w-full sm:w-1/2 flex flex-col justify-center items-center p-8 lg:p-20 bg-white">
            <div class="w-full max-w-md">
                {{ $slot }}
            </div>
        </div>

        <div class="hidden sm:flex w-1/2 bg-[#1b1b2f] flex-col justify-center items-center relative overflow-hidden">


            <div class="relative z-10 p-12 text-center">
                <img src="{{ asset('images/ogretmen.png') }}" alt="Akademik Yönetim" class="max-w-full h-auto drop-shadow-2xl">
                <h2 class="text-amber-500 text-3xl font-black mt-8 tracking-tighter uppercase">Eğitimi Dijitalle Yönetin</h2>
                <p class="text-gray-400 mt-2 italic text-sm">Akademik Takip Sistemi ile her şey kontrolünüzde.</p>
            </div>
            
            <div class="absolute -bottom-20 -right-20 w-80 h-80 border-[40px] border-amber-500/10 rounded-full"></div>
        </div>
    </div>
</body>
</html>
