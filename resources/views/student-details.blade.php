<x-app-layout>
    <div class="max-w-7xl mx-auto pb-12">
        
        <div class="flex items-center gap-4 mt-6 mb-6">
            <a href="{{ route('teacher.panel') }}" class="bg-white p-2 rounded-lg shadow-sm border border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="text-2xl font-black text-slate-800">Öğrenci Akademik Profili</h1>
        </div>

        <div class="bg-gradient-to-r from-slate-800 to-slate-900 rounded-2xl shadow-lg mb-8 overflow-hidden relative">
            <div class="p-8 relative z-10 flex flex-col md:flex-row gap-6 items-center">
                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-inner border-4 border-slate-700">
                    <span class="text-4xl font-black text-slate-400">{{ strtoupper(substr($student->name, 0, 1)) }}</span>
                </div>
                
                <div class="flex-1 text-center md:text-left">
                    <h2 class="text-3xl font-black text-white mb-1">{{ $student->name }}</h2>
                    <p class="text-slate-400 font-medium mb-3">{{ $student->email }} • Kayıt: {{ $student->created_at->format('Y') }}</p>
                    
                    <div class="flex flex-wrap gap-2 justify-center md:justify-start">
                        <span class="bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                            Aktif Öğrenci
                        </span>
                        <span class="bg-sky-500/20 border border-sky-500/30 text-sky-400 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                            {{ $student->courses->count() }} Kayıtlı Ders
                        </span>
                    </div>
                </div>
            </div>
            <svg class="absolute right-0 top-0 h-full text-white/5 transform translate-x-1/4 scale-150" fill="currentColor" viewBox="0 0 100 100"><circle cx="50" cy="50" r="50"></circle></svg>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-8 rounded-r-lg shadow-sm">
                <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Dönem Dersleri ve Performans Karnesi
                </h3>
            </div>
            
            <div class="p-0 overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="text-slate-400 bg-white border-b border-slate-100 text-xs uppercase">
                        <tr>
                            <th class="px-6 py-4 font-bold">Ders Adı</th>
                            <th class="px-6 py-4 font-bold text-center w-32">Vize Notu</th>
                            <th class="px-6 py-4 font-bold text-center w-32">Final Notu</th>
                            <th class="px-6 py-4 font-bold text-center w-24">Ortalama</th>
                            <th class="px-6 py-4 font-bold text-center w-28">Devamsızlık</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($student->courses as $course)
                            @php
                                // İlgili dersin notunu bul, yoksa boş bir nesne oluştur
                                $grade = $grades->get($course->id) ?? new \App\Models\Grade(['vize' => null, 'final' => null]);
                                
                                // Ortalamayı hesapla ve renklendir
                                $avg = $grade->vize !== null ? ($grade->vize * 0.4) + (($grade->final ?? 0) * 0.6) : null;
                                $avgClass = 'bg-slate-100 text-slate-500';
                                
                                if($avg !== null) {
                                    $avgClass = $avg >= 50 ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-red-100 text-red-700 border border-red-200';
                                }

                                // Devamsızlık durumu
                                $absCount = $course->pivot->absences_count;
                                $absLimit = $course->pivot->student_limit;
                                $isRisk = ($absLimit > 0 && $absCount >= $absLimit) ? true : false;
                            @endphp

                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 font-bold text-slate-800 text-base">
                                    {{ $course->course_name }}
                                </td>
                                
                                <td class="px-6 py-4">
                                    <form action="{{ route('teacher.student.grade.update') }}" method="POST" class="flex items-center">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $student->id }}">
                                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                                        <input type="hidden" name="type" value="vize">
                                        <input type="number" name="value" value="{{ $grade->vize }}" min="0" max="100" class="w-16 text-center text-sm py-1 px-2 border-slate-200 rounded focus:ring-sky-500 focus:border-sky-500 shadow-sm font-bold" onchange="this.form.submit()">
                                    </form>
                                </td>

                                <td class="px-6 py-4">
                                    <form action="{{ route('teacher.student.grade.update') }}" method="POST" class="flex items-center">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $student->id }}">
                                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                                        <input type="hidden" name="type" value="final">
                                        <input type="number" name="value" value="{{ $grade->final }}" min="0" max="100" class="w-16 text-center text-sm py-1 px-2 border-slate-200 rounded focus:ring-sky-500 focus:border-sky-500 shadow-sm font-bold" onchange="this.form.submit()">
                                    </form>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center py-1 px-3 rounded text-sm font-black {{ $avgClass }}">
                                        {{ $avg !== null ? number_format($avg, 1) : '-' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="text-lg font-black {{ $isRisk ? 'text-red-500' : 'text-slate-600' }}">
                                        {{ $absCount }} <span class="text-xs text-slate-400 font-bold">/ {{ $absLimit }}</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400 font-medium">
                                    Bu öğrenci henüz hiçbir derse kayıt olmamış.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 bg-slate-50 border-t border-slate-100 text-xs text-slate-500 font-medium text-center">
                Notları değiştirmek için kutucuğa sayıyı yazıp boşluğa tıklamanız (veya Enter'a basmanız) yeterlidir.
            </div>
        </div>

    </div>
</x-app-layout>