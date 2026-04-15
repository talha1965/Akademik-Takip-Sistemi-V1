<x-app-layout>
    @php
        $toplamDers = count($grades);
        
        $bekleyenOdev = 0;
        foreach($assignments as $a) {
            $ogrenci = $a->students->where('id', Auth::id())->first();
            $tamamlandi = $ogrenci ? $ogrenci->pivot->is_completed : false;
            
            if(!$tamamlandi && !\Carbon\Carbon::parse($a->due_date)->endOfDay()->isPast()) {
                $bekleyenOdev++;
            }
        }

        $genelOrtalama = 0;
        if($toplamDers > 0) {
            $toplamNot = 0;
            foreach($grades as $g) {
                $toplamNot += $g->calculateAverage();
            }
            $yuzlukOrtalama = $toplamNot / $toplamDers;
            $genelOrtalama = ($yuzlukOrtalama / 100) * 4; 
        }
    @endphp

    <div class="max-w-7xl mx-auto pb-12">

        <div class="bg-gradient-to-r from-[#0f5279] to-sky-600 rounded-2xl shadow-lg mb-8 overflow-hidden relative flex items-center mt-6">
            <div class="p-8 relative z-10 w-full">
                <h1 class="text-3xl font-black text-white mb-2">Hoş Geldin, {{ Auth::user()->name }}! 🎓</h1>
                <p class="text-sky-100 text-lg">İşte 2025-2026 Akademik durumunun güncel özeti.</p>
            </div>
            <svg class="absolute right-0 top-0 h-full text-white/10 transform translate-x-1/4 scale-150" fill="currentColor" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="50"></circle>
            </svg>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">GENEL NOT ORTALAMASI</p>
                    <h2 class="text-4xl font-black text-slate-800">{{ number_format($genelOrtalama, 2) }} <span class="text-lg text-slate-400 font-medium">/ 4.00</span></h2>
                </div>
                <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-2xl shadow-inner">📈</div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">KAYITLI DERSLER</p>
                    <h2 class="text-4xl font-black text-slate-800">{{ auth()->user()->courses->count() }} <span class="text-lg text-slate-400 font-medium">Ders</span></h2>
                </div>
                <div class="w-14 h-14 bg-sky-100 text-sky-600 rounded-full flex items-center justify-center text-2xl shadow-inner">📚</div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">BEKLEYEN ÖDEVLER</p>
                    <h2 class="text-4xl font-black text-amber-500">{{ $bekleyenOdev }} <span class="text-lg text-slate-400 font-medium">Görev</span></h2>
                </div>
                <div class="w-14 h-14 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center text-2xl shadow-inner">✍️</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
                <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Son Açıklanan Notlar
                    </h3>
                </div>
                <div class="p-0 flex-1 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="text-slate-400 bg-white border-b border-slate-100 text-xs uppercase">
                            <tr>
                                <th class="px-5 py-3 font-semibold">Ders Adı</th>
                                <th class="px-5 py-3 font-semibold text-center">Vize</th>
                                <th class="px-5 py-3 font-semibold text-center">Final</th>
                                <th class="px-5 py-3 font-semibold text-center">Ort.</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse ($grades as $grade)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-5 py-4 font-medium text-slate-800">{{ $grade->course->course_name ?? 'Bilinmeyen Ders' }}</td>
                                <td class="px-5 py-4 text-center font-bold text-slate-600">{{ $grade->vize }}</td>
                                <td class="px-5 py-4 text-center font-bold text-slate-600">{{ $grade->final ?? '-' }}</td>
                                <td class="px-5 py-4 text-center">
                                    <span class="bg-sky-50 text-sky-700 border border-sky-100 py-1 px-2 rounded font-bold">
                                        {{ number_format($grade->calculateAverage(), 1) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-slate-400 font-medium">Sistemde henüz açıklanmış bir notunuz bulunmuyor.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
                <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Ödev ve Görevler
                    </h3>
                    <span class="bg-amber-100 text-amber-700 text-xs font-bold px-2 py-1 rounded-full border border-amber-200">{{ $bekleyenOdev }} Görev</span>
                </div>
                <div class="p-5 space-y-4 flex-1 bg-white">
                    @forelse ($assignments as $assignment)
                       @php
                            $ogrenci = $assignment->students->where('id', auth()->id())->first();
                            $isCompleted = $ogrenci ? $ogrenci->pivot->is_completed : false;
                            $isOverdue = \Carbon\Carbon::parse($assignment->due_date)->endOfDay()->isPast();
                        @endphp
                        
                        <div class="flex items-start gap-4 p-4 border border-slate-100 rounded-xl transition-all group {{ $isCompleted ? 'opacity-60 bg-slate-50' : ($isOverdue ? 'opacity-75 bg-slate-50 grayscale hover:shadow-none hover:border-slate-100' : 'hover:border-sky-300 hover:shadow-sm') }}">
                            
                            <div class="mt-1">
                                <form action="{{ route('assignment.toggle', $assignment->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                        class="w-5 h-5 rounded border flex items-center justify-center transition-colors 
                                        {{ $isCompleted ? 'bg-emerald-500 border-emerald-500' : 'bg-white border-slate-300' }} 
                                        {{ $isOverdue && !$isCompleted ? 'cursor-not-allowed opacity-50 bg-slate-200' : 'cursor-pointer' }}"
                                        {{ $isOverdue && !$isCompleted ? 'disabled' : '' }}
                                        title="{{ $isOverdue && !$isCompleted ? 'Süresi Doldu' : 'Tamamlandı olarak işaretle' }}">
                                        @if($isCompleted)
                                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        @endif
                                    </button>
                                </form>
                            </div>
                            
                            <div class="flex-1">
                                <h4 class="font-bold {{ $isCompleted || $isOverdue ? 'line-through text-slate-500' : 'text-slate-800 group-hover:text-sky-700' }} transition">{{ $assignment->title }}</h4>
                                <p class="text-sm {{ $isOverdue ? 'text-slate-400' : 'text-slate-500' }} mt-1">{{ $assignment->description }}</p>
                                
                                <div class="flex items-center gap-4 mt-3 text-xs font-medium">
                                    <span class="flex items-center gap-1 text-slate-400 bg-slate-100 px-2 py-1 rounded">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg> 
                                        {{ $assignment->course->course_name ?? 'Genel' }}
                                    </span>
                                    
                                    @if($isOverdue && !$isCompleted)
                                        <span class="flex items-center gap-1 text-rose-700 bg-rose-100 border border-rose-200 px-2 py-1 rounded">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Süresi Doldu
                                        </span>
                                    @else
                                        <span class="flex items-center gap-1 {{ $isCompleted ? 'text-slate-400 bg-slate-100' : 'text-amber-600 bg-amber-50' }} px-2 py-1 rounded">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> 
                                            Son: {{ \Carbon\Carbon::parse($assignment->due_date)->translatedFormat('d M') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <p class="text-slate-500 font-medium">Bekleyen hiçbir ödeviniz yok.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Kişisel Devamsızlık Takibi
                </h3>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse(auth()->user()->courses as $course)
                    @php
                        $count = $course->pivot->absences_count;
                        $limit = $course->pivot->student_limit;
                        $percent = $limit > 0 ? min(100, ($count / $limit) * 100) : 0;
                        
                        $colorClass = 'bg-emerald-500';
                        $textClass = 'text-emerald-600';
                        $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                        $statusText = 'Güvenli';
                        
                        if ($percent >= 100) {
                            $colorClass = 'bg-red-600';
                            $textClass = 'text-red-600';
                            $badgeClass = 'bg-red-50 text-red-700 border-red-200';
                            $statusText = 'Kaldın!';
                        } elseif ($percent >= 80) {
                            $colorClass = 'bg-rose-500';
                            $textClass = 'text-rose-600';
                            $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
                            $statusText = 'Çok Riskli';
                        } elseif ($percent >= 50) {
                            $colorClass = 'bg-amber-500';
                            $textClass = 'text-amber-600';
                            $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
                            $statusText = 'Dikkat';
                        }
                    @endphp

                    <div class="border border-slate-100 rounded-xl p-5 hover:shadow-md transition bg-white relative overflow-hidden group flex flex-col justify-between">
                        
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex-1 pr-2">
                                <h4 class="font-bold text-slate-800 text-base leading-tight mb-1">{{ $course->course_name }}</h4>
                                <span class="text-[10px] uppercase tracking-wider font-bold px-2 py-0.5 rounded border {{ $badgeClass }}">{{ $statusText }}</span>
                            </div>
                            
                            <form action="{{ route('course.absence', $course->id) }}" method="POST" class="flex flex-col items-end opacity-60 focus-within:opacity-100 hover:opacity-100 transition">
                                @csrf
                                <span class="text-[10px] text-slate-400 font-bold uppercase mb-0.5">Sınırım</span>
                                <input type="number" name="student_limit" value="{{ $limit }}" min="1" max="30" onchange="this.form.submit()" class="w-12 text-center text-sm py-1 px-1 border-slate-200 rounded-md text-slate-700 font-bold focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                            </form>
                        </div>

                        <div class="w-full bg-slate-100 rounded-full h-2 mb-4 relative overflow-hidden">
                            <div class="h-2 rounded-full transition-all duration-500 {{ $colorClass }}" style="width: {{ $percent }}%"></div>
                        </div>

                        <div class="flex justify-between items-center mt-auto">
                            <div class="font-black text-3xl tracking-tight {{ $textClass }}">
                                {{ $count }}<span class="text-sm text-slate-400 font-medium tracking-normal">/{{ $limit }}</span>
                            </div>
                            
                            <div class="flex gap-2">
                                <form action="{{ route('course.absence', $course->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="decrement">
                                    <button type="submit" class="w-9 h-9 rounded-lg bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-800 flex items-center justify-center font-black transition shadow-sm">-</button>
                                </form>
                                <form action="{{ route('course.absence', $course->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="increment">
                                    <button type="submit" class="w-11 h-11 rounded-lg {{ $colorClass }} text-white hover:opacity-90 flex items-center justify-center font-black text-xl transition shadow-md">+</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-6 text-slate-400 font-medium">
                        Önce "Ders Seçimi" sayfasından bu dönem aldığınız dersleri seçmelisiniz.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>