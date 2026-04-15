<x-app-layout>
    <div class="max-w-7xl mx-auto pb-12">
        
        <div class="bg-gradient-to-r from-slate-800 to-slate-900 rounded-2xl shadow-lg mb-8 overflow-hidden relative flex items-center mt-6">
            <div class="p-8 relative z-10 w-full flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-black text-white mb-2 flex items-center gap-3">
                        <svg class="w-8 h-8 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        Sistem Yönetim Paneli
                    </h1>
                    <p class="text-slate-300 text-lg">Sistemdeki tüm kullanıcıları ve içerikleri buradan yönetebilirsiniz.</p>
                </div>
                <div class="hidden lg:block text-right">
                    <div class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-1">Sistem Durumu</div>
                    <div class="flex items-center gap-2 text-emerald-400 font-black text-lg">
                        <span class="relative flex h-3 w-3">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                        </span>
                        TÜM SİSTEMLER AKTİF
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition">
                <div class="w-14 h-14 bg-sky-100 text-sky-600 rounded-xl flex items-center justify-center text-2xl shadow-inner">🎓</div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Öğrenci</p>
                    <h2 class="text-3xl font-black text-slate-800">{{ $stats['total_students'] }}</h2>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition">
                <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center text-2xl shadow-inner">👨‍🏫</div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Öğretmen</p>
                    <h2 class="text-3xl font-black text-slate-800">{{ $stats['total_teachers'] }}</h2>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition">
                <div class="w-14 h-14 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center text-2xl shadow-inner">📚</div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Açık Ders</p>
                    <h2 class="text-3xl font-black text-slate-800">{{ $stats['total_courses'] }}</h2>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition">
                <div class="w-14 h-14 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center text-2xl shadow-inner">👥</div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Toplam Hesap</p>
                    <h2 class="text-3xl font-black text-slate-800">{{ $stats['total_users'] }}</h2>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-8 rounded-r-lg shadow-sm">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-emerald-400 mr-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Sistem Kullanıcıları Yönetimi
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="text-slate-400 bg-white border-b border-slate-100 text-xs uppercase">
                        <tr>
                            <th class="px-6 py-4 font-bold">Kullanıcı</th>
                            <th class="px-6 py-4 font-bold">Kayıt Tarihi</th>
                            <th class="px-6 py-4 font-bold">Mevcut Rol</th>
                            <th class="px-6 py-4 font-bold text-right">Rol Değiştir (İşlem)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($users as $user)
                        <tr class="hover:bg-slate-50 transition group">
                            
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold border border-slate-200">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800">{{ $user->name }}</div>
                                        <div class="text-xs text-slate-400 font-medium">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-slate-500 font-medium">
                                {{ $user->created_at->format('d M Y') }}
                            </td>

                            <td class="px-6 py-4">
                                @if($user->role === 'admin')
                                    <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-md text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd"/></svg>
                                        Yönetici
                                    </span>
                                @elseif($user->role === 'teacher')
                                    <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Akademisyen
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-md text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                        Öğrenci
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right">
                                <form action="#" method="POST" class="flex justify-end items-center gap-2">
                                    @csrf
                                    @if($user->id !== auth()->id())
                                        <select name="new_role" class="text-sm py-1.5 pl-3 pr-8 border-slate-200 rounded-md text-slate-600 focus:ring-slate-500 focus:border-slate-500 shadow-sm bg-slate-50 cursor-pointer">
                                            <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>Öğrenci Yap</option>
                                            <option value="teacher" {{ $user->role === 'teacher' ? 'selected' : '' }}>Öğretmen Yap</option>
                                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin Yap</option>
                                        </select>
                                        <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold py-2 px-3 rounded-md transition shadow-sm">
                                            Güncelle
                                        </button>
                                    @else
                                        <span class="text-xs font-bold text-slate-400 bg-slate-100 py-1 px-3 rounded border border-slate-200">Bu Senin Hesabın</span>
                                    @endif
                                </form>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>