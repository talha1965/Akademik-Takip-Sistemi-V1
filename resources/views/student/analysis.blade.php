<x-app-layout>
    <div class="max-w-7xl mx-auto pb-24 pt-10 px-6 lg:px-8">
        
        <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-slate-200 pb-8">
            <div>
                <h1 class="text-4xl font-black text-slate-800 tracking-tight italic">Akademik Analiz</h1>
                <p class="text-slate-500 font-medium mt-2">Ders durumlarınızı ve harf notu tahminlerinizi inceleyin.</p>
            </div>
            <div class="bg-indigo-50 border border-indigo-100 px-6 py-4 rounded-2xl flex items-center gap-4 shadow-sm">
                <div class="w-12 h-12 bg-indigo-500 rounded-full flex items-center justify-center text-white shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-indigo-400">Güncel GNO</p>
                    <p class="text-3xl font-black text-indigo-700 leading-none">{{ number_format($gno, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @forelse($grades as $grade)
                @php
                    $course = $grade->course;
                    $analiz = $analysisData[$course->id];
                    $renk = $analiz['renk']; // emerald, amber veya red
                @endphp
                
                <div x-data="{ 
                        simOpen: false, 
                        vizeNotu: {{ $grade->vize ?? 0 }},
                        projeNotu: {{ $grade->proje ?? 0 }},
                        hedefOrtalama: 60,
                        vizeYuzde: {{ $course->vize_weight }},
                        projeYuzde: {{ $course->proje_weight }},
                        finalYuzde: {{ $course->final_weight }},
                        
                        get gerekenFinal() {
                            if (this.finalYuzde === 0) return 0;
                            
                            let mevcutPuan = (this.vizeNotu * (this.vizeYuzde / 100)) + (this.projeNotu * (this.projeYuzde / 100));
                            let puan = (this.hedefOrtalama - mevcutPuan) / (this.finalYuzde / 100);
                            return Math.ceil(puan);
                        }
                    }" 
                    class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden transition-all duration-300 hover:shadow-xl">
                    
                    <div class="p-8">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <span class="bg-slate-100 text-slate-500 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">{{ $course->course_code }}</span>
                                <h3 class="text-2xl font-black text-slate-800 mt-3 leading-tight">{{ $course->course_name }}</h3>
                                
                                <div class="flex gap-2 mt-3">
                                    <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg">Vize %{{ $course->vize_weight }}</span>
                                    @if($course->proje_weight > 0)
                                        <span class="text-[10px] font-bold text-sky-600 bg-sky-50 px-2.5 py-1 rounded-lg">Proje %{{ $course->proje_weight }}</span>
                                    @endif
                                    <span class="text-[10px] font-bold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-lg">Final %{{ $course->final_weight }}</span>
                                </div>
                            </div>
                            
                            <div class="w-16 h-16 rounded-2xl bg-{{$renk}}-50 flex items-center justify-center border border-{{$renk}}-100 shadow-sm flex-shrink-0">
                                <span class="text-2xl font-black text-{{$renk}}-600 leading-none">{{ $analiz['harf_notu'] }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-{{ $course->proje_weight > 0 ? '4' : '3' }} gap-3 mb-6">
                            <div class="bg-slate-50 rounded-2xl p-4 text-center border border-slate-100">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Vize</p>
                                <p class="text-lg font-black text-slate-700 mt-1">{{ $grade->vize ?? '-' }}</p>
                            </div>
                            
                            @if($course->proje_weight > 0)
                                <div class="bg-slate-50 rounded-2xl p-4 text-center border border-slate-100">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Proje</p>
                                    <p class="text-lg font-black text-slate-700 mt-1">{{ $grade->proje ?? '-' }}</p>
                                </div>
                            @endif

                            <div class="bg-slate-50 rounded-2xl p-4 text-center border border-slate-100">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Final</p>
                                <p class="text-lg font-black text-slate-700 mt-1">{{ $grade->final ?? '-' }}</p>
                            </div>
                            
                            <div class="bg-{{$renk}}-50 rounded-2xl p-4 text-center border border-{{$renk}}-100">
                                <p class="text-[9px] font-black text-{{$renk}}-500 uppercase tracking-widest">Ortalama</p>
                                <p class="text-lg font-black text-{{$renk}}-700 mt-1">{{ $analiz['ortalama'] }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 bg-{{$renk}}-50/50 p-4 rounded-xl border border-{{$renk}}-100/50 mb-6">
                            <svg class="w-5 h-5 text-{{$renk}}-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <p class="text-sm font-bold text-{{$renk}}-700">{{ $analiz['durum'] }}</p>
                                <p class="text-xs text-{{$renk}}-600/80 mt-0.5 font-medium">{{ $analiz['mesaj'] }}</p>
                            </div>
                        </div>

                        @if(!$grade->final)
                            <button @click="simOpen = !simOpen" class="w-full py-3.5 rounded-xl border-2 border-dashed border-slate-200 text-slate-500 font-bold text-sm hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition-all flex justify-center items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                <span x-text="simOpen ? 'Simülatörü Kapat' : 'Hedef Simülatörünü Aç'"></span>
                            </button>
                        @endif
                    </div>

                    <div x-show="simOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2">
                        <div class="bg-slate-800 p-8 border-t border-slate-700 text-white">
                            <h4 class="font-black text-lg text-indigo-300 mb-6 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                Başarı Simülatörü
                            </h4>
                            
                            <div class="mb-8">
                                <div class="flex justify-between mb-3 text-sm font-bold">
                                    <span class="text-slate-300">Hedeflenen Yıl Sonu Ortalaması:</span>
                                    <span class="text-emerald-400 text-lg" x-text="hedefOrtalama"></span>
                                </div>
                                <input type="range" x-model="hedefOrtalama" min="45" max="100" class="w-full accent-indigo-500 h-2.5 bg-slate-600 rounded-lg appearance-none cursor-pointer">
                                <div class="flex justify-between text-[10px] text-slate-400 font-bold mt-2 px-1">
                                    <span>45 (DC)</span><span>60 (CC)</span><span>75 (CB)</span><span>90 (AA)</span>
                                </div>
                            </div>

                            <div class="bg-slate-700/50 rounded-2xl p-6 border border-slate-600 flex justify-between items-center shadow-inner">
                                <div>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-1">Gereken Final Notu</p>
                                    <p class="text-xs text-slate-500">Bu ortalama için finalden alınmalı</p>
                                </div>
                                <div class="text-right">
                                    <template x-if="gerekenFinal > 100">
                                        <span class="text-2xl font-black text-rose-400">İmkansız</span>
                                    </template>
                                    <template x-if="gerekenFinal <= 100 && gerekenFinal > 0">
                                        <span class="text-4xl font-black text-emerald-400" x-text="gerekenFinal"></span>
                                    </template>
                                    <template x-if="gerekenFinal <= 0">
                                        <span class="text-xl font-black text-sky-400">Zaten Geçtin</span>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center bg-white rounded-[2rem] border border-slate-100">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 text-slate-300 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <p class="text-slate-400 font-bold">Henüz sisteme girilmiş bir notunuz bulunmuyor.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>