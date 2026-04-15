<x-app-layout>
    <div class="max-w-7xl mx-auto pb-12">
        
        <div class="mt-6 mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-slate-800">Derslerim ve Müfredat</h1>
                <p class="text-sm text-slate-500 font-medium italic">2025-2026 Bahar Dönemi Kayıtlı Ders Listesi</p>
            </div>
            <div class="bg-[#0f5279] text-white px-4 py-2 rounded-xl shadow-lg flex items-center gap-3">
                <span class="text-xs font-bold uppercase opacity-80">Toplam AKTS:</span>
                <span class="text-xl font-black">{{ $courses->sum('akts') ?? 30 }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($courses as $course)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition group">
                    <div class="bg-slate-50 px-5 py-3 border-b border-slate-100 flex justify-between items-center">
                        <span class="text-[10px] font-black text-slate-400 tracking-tighter uppercase italic">{{ $course->course_code }}</span>
                        <span class="bg-sky-100 text-sky-700 text-[10px] font-black px-2 py-0.5 rounded">{{ $course->akts }} AKTS</span>
                    </div>

                    <div class="p-5">
                        <h3 class="text-lg font-bold text-slate-800 mb-4 leading-tight group-hover:text-[#0f5279] transition">
                            {{ $course->course_name }}
                        </h3>

                        <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl mb-4 border border-slate-100">
                            <div class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 font-bold shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <div class="flex-1 overflow-hidden">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Öğretim Elemanı</p>
                                <p class="text-xs font-bold text-slate-700 truncate">Öğr. Gör. {{ $course->teacher_name ?? 'Hakan Hoca' }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <a href="mailto:hakan@ogu.edu.tr" class="flex items-center justify-center gap-2 py-2 px-3 bg-white border border-slate-200 rounded-lg text-[11px] font-bold text-slate-600 hover:bg-slate-50 transition">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                E-Posta
                            </a>
                            <a href="{{ route('messages.index', ['to' => $course->teacher_id ?? 1]) }}" class="flex items-center justify-center gap-2 w-full py-3 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-xl font-black text-xs uppercase tracking-widest transition-all">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
    Mesaj
</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-slate-100 rounded-full text-slate-300 mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-700">Henüz Ders Seçilmemiş</h3>
                    <p class="text-slate-400 max-w-xs mx-auto mt-2 text-sm">Eğitim hayatınıza başlamak için lütfen 'Ders Kayıt İşlemleri' menüsünden derslerinizi seçiniz.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12 bg-[#0f5279]/5 rounded-2xl p-8 border border-[#0f5279]/10">
            <h2 class="text-xl font-black text-[#0f5279] mb-4">Müfredat Hakkında Önemli Notlar</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-[#0f5279]/80 font-medium">
                <div class="flex gap-3">
                    <span class="w-6 h-6 rounded-full bg-[#0f5279] text-white flex-shrink-0 flex items-center justify-center text-[10px]">1</span>
                    <p>Mezuniyet için toplamda 120 AKTS'lik ders yükünü başarıyla tamamlamanız gerekmektedir.</p>
                </div>
                <div class="flex gap-3">
                    <span class="w-6 h-6 rounded-full bg-[#0f5279] text-white flex-shrink-0 flex items-center justify-center text-[10px]">2</span>
                    <p>Ders seçimleri akademik takvimde belirtilen tarihler arasında danışman onayı ile yapılır.</p>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>