<x-app-layout>
    <div class="max-w-7xl mx-auto pb-12">
        
        <div class="bg-gradient-to-r from-[#0f5279] to-sky-600 rounded-2xl shadow-lg mb-8 overflow-hidden relative flex items-center mt-6">
            <div class="p-8 relative z-10 w-full">
                <h1 class="text-3xl font-black text-white mb-2 flex items-center gap-3">
                    <svg class="w-8 h-8 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    Ders Seçimi ve Kayıt
                </h1>
                <p class="text-sky-100 text-lg">2025-2026 Akademik yılı için almak istediğiniz dersleri aşağıdan seçip kaydedebilirsiniz.</p>
            </div>
            <svg class="absolute right-0 top-0 h-full text-white/10 transform translate-x-1/4 scale-150" fill="currentColor" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="50"></circle>
            </svg>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-8 rounded-r-lg shadow-sm">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-emerald-400 mr-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-8 rounded-r-lg shadow-sm">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm font-bold text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <form action="{{ route('course.enroll') }}" method="POST">
            @csrf
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col mb-8">
                <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        Açılan Dersler Havuzu
                    </h3>
                    <div class="flex gap-3">
                        <span class="bg-amber-100 text-amber-700 text-xs font-bold px-3 py-1.5 rounded-full border border-amber-200">
                            Max Limit: 30 AKTS
                        </span>
                        <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1.5 rounded-full border border-indigo-200">
                            {{ count($allCourses) }} Ders Bulundu
                        </span>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="text-slate-400 bg-white border-b border-slate-100 text-xs uppercase">
                            <tr>
                                <th class="px-6 py-4 font-bold text-center w-16">Seç</th>
                                <th class="px-6 py-4 font-bold">Ders Kodu</th>
                                <th class="px-6 py-4 font-bold">Dersin Adı</th>
                                <th class="px-6 py-4 font-bold text-center">AKTS</th>
                                <th class="px-6 py-4 font-bold text-center">Kontenjan</th>
                                <th class="px-6 py-4 font-bold text-center">Durum</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse ($allCourses as $course)
                            @php
                                $isSelected = in_array($course->id, $myCourses);
                                $isFull = $course->available_quota <= 0;
                            @endphp
                            <tr class="{{ ($isFull && !$isSelected) ? 'opacity-60 bg-slate-50' : 'hover:bg-slate-50 transition cursor-pointer' }}" 
                                {{ (!$isFull || $isSelected) ? "onclick=document.getElementById('course_{$course->id}').click()" : '' }}>
                                
                                <td class="px-6 py-4 text-center">
                                    <input type="checkbox" id="course_{{ $course->id }}" name="courses[]" value="{{ $course->id }}" 
                                        {{ $isSelected ? 'checked' : '' }}
                                        {{ ($isFull && !$isSelected) ? 'disabled' : '' }}
                                        onclick="event.stopPropagation()"
                                        class="w-5 h-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer shadow-sm disabled:cursor-not-allowed">
                                </td>
                                
                                <td class="px-6 py-4 font-mono text-xs text-slate-500 font-bold">
                                    {{ $course->course_code }}
                                </td>
                                
                                <td class="px-6 py-4 font-bold text-slate-800 text-base">
                                    {{ $course->course_name }}
                                </td>
                                
                                <td class="px-6 py-4 text-center font-bold text-slate-600">
                                    <span class="bg-slate-100 text-slate-600 py-1 px-2 rounded">
                                        {{ $course->akts }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center font-bold">
                                    <span class="{{ $isFull ? 'text-red-500' : 'text-emerald-600' }}">
                                        {{ $course->available_quota }} / {{ $course->quota ?? 50 }}
                                    </span>
                                </td>
                                
                                <td class="px-6 py-4 text-center">
                                    @if($isSelected)
                                        <span class="inline-flex items-center gap-1 py-1 px-2.5 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Kayıtlı
                                        </span>
                                    @elseif($isFull)
                                        <span class="inline-flex items-center gap-1 py-1 px-2.5 rounded-md text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                            Dolu
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 py-1 px-2.5 rounded-md text-xs font-bold bg-slate-50 text-slate-500 border border-slate-200">
                                            Seçilmedi
                                        </span>
                                    @endif
                                </td>
                                
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-400 font-medium text-base">
                                    Sistemde henüz açılmış bir ders bulunmuyor. Lütfen daha sonra tekrar deneyin.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if(count($allCourses) > 0)
                <div class="p-5 border-t border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <p class="text-xs text-slate-500 font-medium max-w-md">
                        * Ders seçimlerinizi yaptıktan sonra kaydet butonuna basmayı unutmayın. Danışman onayından geçmeyen dersler kesinleşmez. Toplam AKTS limitiniz 30'dur.
                    </p>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-black py-3 px-8 rounded-xl transition shadow-lg shadow-indigo-500/30 active:scale-95 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        Seçimlerimi Kaydet
                    </button>
                </div>
                @endif
                
            </div>
        </form>

    </div>
</x-app-layout>