<x-app-layout>
    <div class="max-w-4xl mx-auto pb-24 pt-12 px-6 lg:px-8">
        
        <div class="mb-10">
            <a href="{{ route('teacher.panel') }}" class="text-sm font-bold text-indigo-500 hover:text-indigo-600 flex items-center gap-1 mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Ders Paneline Dön
            </a>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">{{ $course->course_name }}</h1>
            <p class="text-slate-500 font-medium mt-1">Ders değerlendirme oranlarını ve geçme barajlarını buradan yönetin.</p>
        </div>

        <div x-data="{
            vize: {{ $course->vize_weight ?? 40 }},
            proje: {{ $course->proje_weight ?? 0 }},
            final: {{ $course->final_weight ?? 60 }},
            get total() {
                return parseInt(this.vize) + parseInt(this.proje) + parseInt(this.final);
            },
            get isValid() {
                return this.total === 100;
            }
        }" class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
            
            <div class="p-8 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-slate-800 rounded-xl flex items-center justify-center text-white shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-slate-800">Değerlendirme Kriterleri</h2>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-0.5">Ağırlık Dağılımı</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 px-5 py-3 rounded-2xl transition-all duration-300" :class="isValid ? 'bg-emerald-50 border border-emerald-100' : 'bg-rose-50 border border-rose-100'">
                    <span class="text-xs font-black uppercase tracking-widest" :class="isValid ? 'text-emerald-600' : 'text-rose-600'">Toplam:</span>
                    <span class="text-2xl font-black" :class="isValid ? 'text-emerald-600' : 'text-rose-600'" x-text="'%' + total"></span>
                </div>
            </div>

            <form action="{{ route('teacher.update.rules', $course->id) }}" method="POST" class="p-8 space-y-12">
                @csrf
                @method('PUT')

                <div class="space-y-10">
                    <div class="relative">
                        <div class="flex justify-between items-end mb-4">
                            <label class="text-sm font-black text-slate-700 uppercase tracking-widest">Vize Sınavı</label>
                            <span class="text-3xl font-black text-indigo-500" x-text="'%' + vize"></span>
                        </div>
                        <input type="range" name="vize_weight" x-model="vize" min="0" max="100" class="w-full h-3 bg-slate-100 rounded-lg appearance-none cursor-pointer accent-indigo-500">
                    </div>

                    <div class="relative">
                        <div class="flex justify-between items-end mb-4">
                            <label class="text-sm font-black text-slate-700 uppercase tracking-widest">Proje / Ödev</label>
                            <span class="text-3xl font-black text-sky-500" x-text="'%' + proje"></span>
                        </div>
                        <input type="range" name="proje_weight" x-model="proje" min="0" max="100" class="w-full h-3 bg-slate-100 rounded-lg appearance-none cursor-pointer accent-sky-500">
                    </div>

                    <div class="relative">
                        <div class="flex justify-between items-end mb-4">
                            <label class="text-sm font-black text-slate-700 uppercase tracking-widest">Final Sınavı</label>
                            <span class="text-3xl font-black text-rose-500" x-text="'%' + final"></span>
                        </div>
                        <input type="range" name="final_weight" x-model="final" min="0" max="100" class="w-full h-3 bg-slate-100 rounded-lg appearance-none cursor-pointer accent-rose-500">
                    </div>
                </div>

                <hr class="border-slate-100 border-2 border-dashed">

                <div class="flex flex-col md:flex-row justify-between items-center gap-8">
                    <div class="w-full md:w-auto bg-amber-50 border border-amber-100 p-5 rounded-2xl flex items-center gap-5">
                        <div class="w-10 h-10 rounded-full bg-amber-200 text-amber-700 flex items-center justify-center font-black">!</div>
                        <div>
                            <label class="block text-xs font-black text-amber-700 uppercase tracking-widest mb-1">Final Geçme Barajı</label>
                            <div class="flex items-center gap-2">
                                <input type="number" name="passing_grade" value="{{ $course->passing_grade ?? 50 }}" min="0" max="100" class="w-20 text-center font-black text-xl text-slate-800 border-none bg-white rounded-xl shadow-sm focus:ring-2 focus:ring-amber-400">
                                <span class="text-xs text-amber-600 font-medium leading-tight max-w-[120px]">Ortalama yetse bile bu notun altı kalır.</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" 
                        :disabled="!isValid" 
                        :class="!isValid ? 'opacity-50 cursor-not-allowed bg-slate-400' : 'bg-slate-800 hover:bg-slate-900 shadow-xl shadow-slate-200'"
                        class="w-full md:w-auto text-white px-10 py-5 rounded-2xl font-black text-sm uppercase tracking-widest transition-all">
                        <span x-text="isValid ? 'Ayarları Kaydet' : 'Toplam %100 Olmalı'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>