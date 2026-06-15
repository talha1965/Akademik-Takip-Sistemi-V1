<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ATS | Akademik Takip Sistemi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800" x-data="{ sidebarOpen: true }">
    
    <header class="fixed top-0 w-full h-16 bg-[#0f5279] text-white flex items-center justify-between px-4 z-50 shadow-md">
        <div class="flex items-center gap-4 w-64">
            <div class="flex items-center gap-3 font-black tracking-widest text-xl ml-2">
                <div class="w-9 h-9 bg-white text-[#0f5279] rounded-lg flex items-center justify-center shadow-inner text-2xl">A</div>
                <span class="tracking-[0.2em] mt-1 text-white">T.S.</span>
            </div>
            <button @click="sidebarOpen = !sidebarOpen" class="text-white/80 hover:text-white transition ml-auto p-1 rounded-md hover:bg-white/10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </div>

        <div class="flex items-center gap-6 pr-4">
            <div class="flex items-center gap-5">
                
                @php
                    $unreadMsg = \App\Models\Message::where('receiver_id', auth()->id())->where('is_read', false)->count();
                @endphp
                <a href="{{ route('messages.index') }}" class="relative text-white/80 hover:text-white transition focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"></path></svg>
                    @if($unreadMsg > 0)
                        <span class="absolute -top-1.5 -right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-rose-500 text-[9px] font-bold shadow-sm">
                            {{ $unreadMsg }}
                        </span>
                    @endif
                </a>
                
                <div x-data="{ notifOpen: false }" class="relative">
                    <button @click="notifOpen = !notifOpen" @click.away="notifOpen = false" class="relative text-white/80 hover:text-white transition focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"></path></svg>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="absolute -top-1.5 -right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-amber-400 text-[9px] font-bold text-slate-900 shadow-sm animate-bounce">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </button>

                    <div x-show="notifOpen" x-cloak x-transition class="absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-2xl border border-slate-100 overflow-hidden z-50 text-slate-800">
                        <div class="bg-slate-50 border-b border-slate-100 px-4 py-3 flex justify-between items-center">
                            <h3 class="font-bold text-sm text-slate-800">Bildirimler</h3>
                            <span class="text-xs bg-sky-100 text-sky-600 font-bold px-2 py-0.5 rounded-full">{{ auth()->user()->unreadNotifications->count() }} Yeni</span>
                        </div>
                        <div class="max-h-80 overflow-y-auto divide-y divide-slate-50">
                            @forelse(auth()->user()->unreadNotifications as $notification)
                                <div class="p-4 hover:bg-slate-50 transition relative group">
                                    <div class="flex gap-3">
                                        <div class="flex-shrink-0 mt-1">
                                            @if(($notification->data['type'] ?? '') === 'grade')
                                                <div class="w-8 h-8 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center text-lg">📈</div>
                                            @else
                                                <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-lg">🔔</div>
                                            @endif
                                        </div>
                                        <div class="flex-1 pr-6">
                                            <p class="text-sm font-bold text-slate-800 mb-0.5">{{ $notification->data['message'] ?? 'Yeni bildirim.' }}</p>
                                            <p class="text-[10px] text-slate-400 mt-2 font-medium">{{ $notification->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <form action="{{ route('notification.read', $notification->id) }}" method="POST" class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition">
                                        @csrf
                                        <button type="submit" class="text-slate-300 hover:text-emerald-500 bg-white w-6 h-6 rounded-full flex items-center justify-center border border-slate-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <div class="p-6 text-center text-slate-400 text-sm italic">Okunmamış bildirim yok.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            
           <div x-data="{ profileOpen: false }" class="relative border-l border-white/20 pl-6 ml-2">
                <button @click="profileOpen = !profileOpen" class="flex items-center gap-3 hover:opacity-80 transition-opacity focus:outline-none">
                    
                    @php
                        $nameParts = explode(' ', Auth::user()->name);
                        $initials = isset($nameParts[1]) ? mb_substr($nameParts[0], 0, 1) . mb_substr($nameParts[1], 0, 1) : mb_substr($nameParts[0], 0, 2);
                    @endphp
                    <div class="w-10 h-10 rounded-full bg-[#E0F2FE] text-[#0284C7] font-black text-sm flex items-center justify-center border-2 border-white/50 shadow-sm">
                        {{ mb_strtoupper($initials) }}
                    </div>
                    
                    <div class="hidden md:block text-xs font-medium text-right leading-tight">
                        <span class="block text-white/80 font-mono text-[10px] tracking-wider">221120241013</span>
                        <span class="uppercase font-bold tracking-wide text-white">{{ Auth::user()->name }}</span>
                    </div>

                    <svg class="w-4 h-4 text-white/70 ml-1 transition-transform duration-200" :class="{'rotate-180': profileOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                <div x-show="profileOpen" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-[-10px]"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-[-10px]"
                     x-cloak 
                     @click.away="profileOpen = false"
                     class="absolute right-0 mt-4 w-56 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden z-50 text-slate-800">
                    
                    <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50">
                        <p class="text-sm font-black text-slate-800">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5 truncate">{{ Auth::user()->email }}</p>
                    </div>

                    <div class="p-2 space-y-1">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 text-xs font-bold text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Profil Bilgilerim
                        </a>
                        
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-xs font-bold text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                            Hesap Ayarları
                        </a>
                    </div>

                    <div class="border-t border-slate-100 p-2">
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-xs font-bold text-rose-600 hover:bg-rose-50 hover:text-rose-700 rounded-xl transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Sistemden Çıkış
                            </button>
                        </form>
                    </div>
                </div>
            </div>
    </header>

    <aside x-bind:class="sidebarOpen ? 'w-64' : 'w-0 overflow-hidden'" class="fixed top-16 left-0 h-[calc(100vh-4rem)] bg-[#1e293b] text-slate-300 transition-all duration-300 z-40 overflow-y-auto border-r border-slate-800">
        <nav class="p-4 text-sm font-medium">
            <ul class="space-y-1.5">
                
                @if(auth()->user()->role === 'student')
                    <div class="px-4 mb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Genel</div>
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition group {{ request()->routeIs('dashboard') ? 'bg-slate-800 text-white' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125V9.75M8.25 21h8.25"></path></svg>
                            <span>Dashboard (Özet)</span>
                        </a>
                    </li>

                    <div class="px-4 mb-2 mt-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Akademik Takip</div>
                    <li>
                        <a href="{{ route('student.analysis') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition group {{ request()->routeIs('student.analysis') ? 'bg-slate-800 text-white' : '' }}">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            <span>Akademik Analiz (Notlarım)</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('student.courses') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition group {{ request()->routeIs('student.courses') ? 'bg-slate-800 text-white' : '' }}">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            <span>Derslerim & Müfredat</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('student.assignments') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition group {{ request()->routeIs('student.assignments') ? 'bg-slate-800 text-white' : '' }}">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0118 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3l1.5 1.5 3-3.75"></path></svg>
                            <span>Ödev ve Görev Takvimi</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('messages.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition group {{ request()->routeIs('messages.index') ? 'bg-slate-800 text-white' : '' }}">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span>İletişim & Mesajlar</span>
                        </a>
                    </li>

                    <li class="pt-2">
                        <a href="{{ route('course.selection') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition group {{ request()->routeIs('course.selection') ? 'bg-slate-800 text-white' : '' }}">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg>
                            <span>Ders Kayıt İşlemleri</span>
                        </a>
                    </li>
                    <li class="pt-2">
                    <a href="{{ route('dgs.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition group {{ request()->routeIs('dgs.index') ? 'bg-slate-800 text-white' : '' }}">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            <span>DGS Puan Hesaplama</span>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->role === 'teacher')
                    <li>
                        <a href="{{ route('teacher.panel') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition group {{ request()->routeIs('teacher.panel') ? 'bg-slate-800 text-white' : '' }}">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                            <span>Öğretmen Paneli</span>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->role === 'admin')
                    <li class="pt-2 border-t border-slate-700 mt-2">
                        <a href="{{ route('admin.panel') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 border border-indigo-500/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                            <span class="font-bold">Sistem Yönetimi</span>
                        </a>
                    </li>
                @endif

                <li class="mt-2 pb-2"><div class="h-px w-full bg-slate-800"></div></li>
                
                <li>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white {{ request()->routeIs('profile.edit') ? 'bg-slate-800 text-white' : '' }}">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"></path></svg>
                        <span>Şifre Değiştirme</span>
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-red-500/10 text-slate-300 hover:text-red-400 text-left transition group">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-red-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"></path></svg>
                            <span>Çıkış</span>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </aside>

    <main x-bind:class="sidebarOpen ? 'ml-64' : 'ml-0'" class="pt-16 transition-all duration-300 min-h-screen flex flex-col bg-slate-50/50">
        <div class="flex-1 p-6 lg:p-8">
            {{ $slot }}
        </div>
        <footer class="bg-white border-t p-4 text-xs text-slate-500 flex justify-between items-center px-6">
            <div><strong>ATS</strong> 2024 - 2026 © Tüm Hakları Saklıdır.</div>
            <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>Sistem Aktif (v2.0.0)</div>
        </footer>
    </main>

</body>
</html>