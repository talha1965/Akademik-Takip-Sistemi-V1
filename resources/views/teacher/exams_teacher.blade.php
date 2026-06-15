<x-app-layout>
    <div class="max-w-5xl mx-auto pb-24 pt-10 px-6 lg:px-8">

        <div class="mb-10 border-b border-slate-200 pb-8 flex justify-between items-end">
            <div>
                <h1 class="text-4xl font-black text-slate-800 tracking-tight italic">Sınav Programı</h1>
                <p class="text-slate-500 font-medium mt-2">Derslerinize ait sınav tarihlerini buradan yönetin.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-6 rounded-r-lg">
                <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                <p class="text-sm font-bold text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        {{-- SINAV EKLEME FORMU --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8 mb-10">
            <h2 class="font-black text-slate-800 text-lg mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Yeni Sınav Ekle
            </h2>

            <form action="{{ route('teacher.exam.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Ders</label>
                        <select name="course_id" required class="w-full rounded-xl border-slate-200 text-slate-700 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Ders seçin...</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->course_code }} — {{ $course->course_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Sınav Türü</label>
                        <select name="exam_type" required class="w-full rounded-xl border-slate-200 text-slate-700 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="vize">Vize</option>
                            <option value="final">Final</option>
                            <option value="butunleme">Bütünleme</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Sınav Tarihi</label>
                        <input type="date" name="exam_date" required
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               class="w-full rounded-xl border-slate-200 text-slate-700 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Derslik</label>
                        <input type="text" name="classroom" required placeholder="Örn: A-101, Amfi 2"
                               class="w-full rounded-xl border-slate-200 text-slate-700 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Başlangıç Saati</label>
                        <input type="time" name="start_time" required
                               class="w-full rounded-xl border-slate-200 text-slate-700 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Bitiş Saati</label>
                        <input type="time" name="end_time" required
                               class="w-full rounded-xl border-slate-200 text-slate-700 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Not (İsteğe Bağlı)</label>
                        <input type="text" name="notes" placeholder="Örn: Kalem ve silgi getiriniz."
                               class="w-full rounded-xl border-slate-200 text-slate-700 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-black px-8 py-3 rounded-xl transition shadow-sm">
                        Sınavı Kaydet
                    </button>
                </div>
            </form>
        </div>

        {{-- MEVCUT SINAVLAR --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-black text-slate-800">Kayıtlı Sınavlar</h3>
            </div>

            @forelse($exams as $exam)
                @php
                    $typeColor = match($exam->exam_type) {
                        'vize'      => 'indigo',
                        'final'     => 'rose',
                        'butunleme' => 'amber',
                    };
                @endphp
                <div class="flex items-center gap-5 px-6 py-4 border-b border-slate-50 hover:bg-slate-50 transition
                    {{ $exam->isPast() ? 'opacity-60' : '' }}">

                    <span class="text-xs font-black px-2.5 py-1 rounded-lg
                        bg-{{ $typeColor }}-50 text-{{ $typeColor }}-700 border border-{{ $typeColor }}-100 flex-shrink-0">
                        {{ $exam->exam_type_label }}
                    </span>

                    <div class="flex-1">
                        <p class="font-black text-slate-800 text-sm">{{ $exam->course->course_name }}</p>
                        <p class="text-xs text-slate-400">{{ $exam->classroom }}</p>
                    </div>

                    <div class="text-center flex-shrink-0">
                        <p class="font-black text-slate-700 text-sm">{{ \Carbon\Carbon::parse($exam->exam_date)->format('d.m.Y') }}</p>
                        <p class="text-xs text-slate-400">
                            {{ \Carbon\Carbon::parse($exam->start_time)->format('H:i') }} —
                            {{ \Carbon\Carbon::parse($exam->end_time)->format('H:i') }}
                        </p>
                    </div>

                    <form action="{{ route('teacher.exam.destroy', $exam->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                onclick="return confirm('Bu sınavı silmek istediğinize emin misiniz?')"
                                class="text-slate-300 hover:text-red-500 transition p-2 rounded-lg hover:bg-red-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
            @empty
                <div class="py-12 text-center text-slate-400 font-bold">
                    Henüz sınav eklenmemiş.
                </div>
            @endforelse
        </div>

    </div>
</x-app-layout>
