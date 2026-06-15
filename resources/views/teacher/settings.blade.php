<x-app-layout>
    <div class="max-w-7xl mx-auto pb-12">
        
        <div class="bg-gradient-to-r from-indigo-800 to-indigo-900 rounded-3xl shadow-xl mb-8 overflow-hidden relative flex flex-col md:flex-row items-center mt-6">
            <div class="p-8 relative z-10 flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('teacher.panel') }}" class="p-2 bg-white/10 hover:bg-white/20 rounded-xl backdrop-blur-sm text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <h1 class="text-3xl font-black text-white">Değerlendirme Kriterleri</h1>
                </div>
                <p class="text-indigo-200 text-sm md:text-base max-w-xl pl-12">Verdiğiniz derslerin başarı notu hesaplama ağırlıklarını (Vize, Final, Proje) ve geçme barajlarını buradan yönetebilirsiniz.</p>
            </div>
            <svg class="absolute right-0 bottom-0 h-64 text-white/5 transform translate-x-1/3 translate-y-1/4 pointer-events-none" fill="currentColor" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="50"></circle>
            </svg>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 p-4 mb-8 rounded-2xl shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 border border-rose-100 p-4 mb-8 rounded-2xl shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-sm font-bold text-rose-800">{{ session('error') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6">
            @forelse($courses as $course)
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                        <div>
                            <span class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-100 mb-2 inline-block">{{ $course->course_code }}</span>
                            <h3 class="font-black text-slate-800 text-xl">{{ $course->course_name }}</h3>
                        </div>
                    </div>

                    <form action="{{ route('teacher.update.rules', $course->id) }}" method="POST" class="p-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                            <div class="bg-sky-50 rounded-2xl p-5 border border-sky-100">
                                <label class="block text-xs font-bold text-sky-700 uppercase tracking-wider mb-2">Vize Ağırlığı (%)</label>
                                <input type="number" name="vize_weight" id="vize_{{ $course->id }}" value="{{ $course->vize_weight }}" min="0" max="100" class="w-full bg-white border-sky-200 rounded-xl focus:ring-sky-500 focus:border-sky-500 font-black text-xl text-sky-900 text-center py-3" oninput="calculateTotal({{ $course->id }})" required>
                            </div>

                            <div class="bg-amber-50 rounded-2xl p-5 border border-amber-100">
                                <label class="block text-xs font-bold text-amber-700 uppercase tracking-wider mb-2">Proje/Ödev (%)</label>
                                <input type="number" name="proje_weight" id="proje_{{ $course->id }}" value="{{ $course->proje_weight }}" min="0" max="100" class="w-full bg-white border-amber-200 rounded-xl focus:ring-amber-500 focus:border-amber-500 font-black text-xl text-amber-900 text-center py-3" oninput="calculateTotal({{ $course->id }})" required>
                            </div>

                            <div class="bg-emerald-50 rounded-2xl p-5 border border-emerald-100">
                                <label class="block text-xs font-bold text-emerald-700 uppercase tracking-wider mb-2">Final Ağırlığı (%)</label>
                                <input type="number" name="final_weight" id="final_{{ $course->id }}" value="{{ $course->final_weight }}" min="0" max="100" class="w-full bg-white border-emerald-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 font-black text-xl text-emerald-900 text-center py-3" oninput="calculateTotal({{ $course->id }})" required>
                            </div>

                            <div class="bg-slate-100 rounded-2xl p-5 border border-slate-200">
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Geçme Barajı</label>
                                <input type="number" name="passing_grade" value="{{ $course->passing_grade }}" min="0" max="100" class="w-full bg-white border-slate-300 rounded-xl focus:ring-slate-500 focus:border-slate-500 font-black text-xl text-slate-800 text-center py-3" required>
                                <p class="text-[10px] text-slate-500 mt-2 text-center leading-tight">Öğrencinin dersi geçmesi için alması gereken minimum ortalama.</p>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row justify-between items-center bg-slate-50 p-4 rounded-xl border border-slate-200 gap-4">
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-bold text-slate-500 uppercase tracking-wider">Ağırlık Toplamı:</span>
                                <span id="total_{{ $course->id }}" class="text-2xl font-black px-4 py-1 rounded-lg {{ ($course->vize_weight + $course->proje_weight + $course->final_weight) == 100 ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }}">
                                    %{{ $course->vize_weight + $course->proje_weight + $course->final_weight }}
                                </span>
                                <span id="warning_{{ $course->id }}" class="text-xs font-bold text-rose-500 {{ ($course->vize_weight + $course->proje_weight + $course->final_weight) == 100 ? 'hidden' : '' }}">Toplam 100 olmalıdır!</span>
                            </div>
                            
                            <button type="submit" class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-black py-3 px-8 rounded-xl transition-all shadow-md shadow-indigo-500/30 active:scale-[0.98]">
                                Değişiklikleri Kaydet
                            </button>
                        </div>
                    </form>
                </div>
            @empty
                <div class="bg-white rounded-3xl p-12 text-center border border-slate-100 shadow-sm">
                    <div class="w-20 h-20 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-700 mb-2">Henüz Dersiniz Bulunmuyor</h3>
                    <p class="text-slate-500">Değerlendirme kriterlerini ayarlayabilmek için öncelikle "Öğretmen Paneli"nden ders oluşturmalısınız.</p>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        function calculateTotal(courseId) {
            let vize = parseInt(document.getElementById('vize_' + courseId).value) || 0;
            let proje = parseInt(document.getElementById('proje_' + courseId).value) || 0;
            let final = parseInt(document.getElementById('final_' + courseId).value) || 0;
            
            let total = vize + proje + final;
            let totalEl = document.getElementById('total_' + courseId);
            let warningEl = document.getElementById('warning_' + courseId);
            
            totalEl.innerText = '%' + total;
            
            if(total === 100) {
                totalEl.className = 'text-2xl font-black px-4 py-1 rounded-lg bg-emerald-100 text-emerald-600';
                warningEl.classList.add('hidden');
            } else {
                totalEl.className = 'text-2xl font-black px-4 py-1 rounded-lg bg-rose-100 text-rose-600';
                warningEl.classList.remove('hidden');
            }
        }
    </script>
</x-app-layout>