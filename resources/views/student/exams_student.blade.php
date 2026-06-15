<x-app-layout>
    <div class="max-w-4xl mx-auto pb-24 pt-10 px-6 lg:px-8">

        <div class="mb-10 border-b border-slate-200 pb-8">
            <h1 class="text-4xl font-black text-slate-800 tracking-tight italic">Sınav Programı</h1>
            <p class="text-slate-500 font-medium mt-2">Bölümünüzün tüm sınav tarihleri ve derslik bilgileri.</p>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-6 rounded-r-lg">
                <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
            </div>
        @endif

        @forelse($exams as $date => $dayExams)
            @php
                $carbon = \Carbon\Carbon::parse($date);
                $isPast = $carbon->isPast();
                $isToday = $carbon->isToday();
            @endphp

            <div class="mb-8">
                {{-- Tarih Başlığı --}}
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-14 h-14 rounded-2xl flex flex-col items-center justify-center shadow-sm flex-shrink-0
                        {{ $isToday ? 'bg-indigo-600 text-white' : ($isPast ? 'bg-slate-100 text-slate-400' : 'bg-white border border-slate-200 text-slate-700') }}">
                        <span class="text-xs font-black uppercase tracking-widest">{{ $carbon->locale('tr')->isoFormat('MMM') }}</span>
                        <span class="text-2xl font-black leading-none">{{ $carbon->format('d') }}</span>
                    </div>
                    <div>
                        <p class="font-black text-slate-800 text-lg">
                            {{ $carbon->locale('tr')->isoFormat('dddd') }}
                            @if($isToday) <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full ml-2">Bugün</span> @endif
                        </p>
                        <p class="text-slate-400 text-sm font-medium">{{ $carbon->format('d.m.Y') }}</p>
                    </div>
                    <div class="flex-1 h-px bg-slate-100 ml-2"></div>
                </div>

                {{-- O güne ait sınavlar --}}
                <div class="space-y-3 ml-18">
                    @foreach($dayExams as $exam)
                        @php
                            $typeColor = match($exam->exam_type) {
                                'vize'      => 'indigo',
                                'final'     => 'rose',
                                'butunleme' => 'amber',
                            };
                        @endphp
                        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex items-center gap-5 
                            {{ $isPast ? 'opacity-60' : 'hover:shadow-md transition' }}">
                            
                            {{-- Tip Rozeti --}}
                            <div class="w-16 text-center flex-shrink-0">
                                <span class="inline-block text-xs font-black px-2 py-1 rounded-lg
                                    bg-{{ $typeColor }}-50 text-{{ $typeColor }}-700 border border-{{ $typeColor }}-100">
                                    {{ $exam->exam_type_label }}
                                </span>
                            </div>

                            {{-- Ders Adı --}}
                            <div class="flex-1">
                                <p class="font-black text-slate-800">{{ $exam->course->course_name }}</p>
                                <p class="text-xs text-slate-400 font-medium mt-0.5">{{ $exam->course->course_code }}</p>
                                @if($exam->notes)
                                    <p class="text-xs text-slate-500 mt-1 italic">{{ $exam->notes }}</p>
                                @endif
                            </div>

                            {{-- Saat --}}
                            <div class="text-center flex-shrink-0">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Saat</p>
                                <p class="font-black text-slate-700">
                                    {{ \Carbon\Carbon::parse($exam->start_time)->format('H:i') }}
                                </p>
                                <p class="text-xs text-slate-400">
                                    {{ \Carbon\Carbon::parse($exam->end_time)->format('H:i') }}
                                </p>
                            </div>

                            {{-- Derslik --}}
                            <div class="text-center flex-shrink-0">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Derslik</p>
                                <p class="font-black text-slate-700">{{ $exam->classroom }}</p>
                            </div>

                            {{-- Kaç gün kaldı --}}
                            @if(!$isPast)
                                <div class="text-center flex-shrink-0 w-16">
                                    <p class="text-2xl font-black text-indigo-600">{{ $exam->daysUntil() }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">gün</p>
                                </div>
                            @else
                                <div class="text-center flex-shrink-0 w-16">
                                    <p class="text-xs font-bold text-slate-300">Geçti</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="py-20 text-center bg-white rounded-[2rem] border border-slate-100">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 text-slate-300 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-slate-400 font-bold">Henüz sınav programı girilmemiş.</p>
            </div>
        @endforelse

    </div>
</x-app-layout>
