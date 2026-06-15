<x-app-layout>
    <div class="max-w-7xl mx-auto pb-12">
        
        <div class="bg-gradient-to-r from-[#0f5279] to-sky-600 rounded-2xl shadow-lg mb-8 overflow-hidden relative flex items-center mt-6">
            <div class="p-8 relative z-10 w-full">
                <h1 class="text-3xl font-black text-white mb-2 flex items-center gap-3">
                    <svg class="w-8 h-8 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    Derslerim ve Müfredat
                </h1>
                <p class="text-sky-100 text-lg">Kayıtlı olduğunuz tüm dersler ve akademik detayları aşağıda listelenmektedir.</p>
            </div>
            <svg class="absolute right-0 top-0 h-full text-white/10 transform translate-x-1/4 scale-150" fill="currentColor" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="50"></circle>
            </svg>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col mb-8">
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    Kayıtlı Olduğum Dersler
                </h3>
                <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1.5 rounded-full border border-indigo-200">
                    {{ count($courses) }} Ders Bulundu
                </span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="text-slate-400 bg-white border-b border-slate-100 text-xs uppercase">
                        <tr>
                            <th class="px-6 py-4 font-bold">Ders Kodu</th>
                            <th class="px-6 py-4 font-bold">Dersin Adı</th>
                            <th class="px-6 py-4 font-bold">Akademisyen</th>
                            <th class="px-6 py-4 font-bold text-center">AKTS / Kredi</th>
                            <th class="px-6 py-4 font-bold text-center">Durum</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($courses as $course)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-mono text-xs text-slate-500 font-bold">
                                {{ $course->course_code }}
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-800 text-base">
                                {{ $course->course_name }}
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $course->teacher ? $course->teacher->full_title : 'Belirtilmedi' }}
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-slate-600">
                                <span class="bg-slate-100 text-slate-600 py-1 px-2 rounded">
                                    {{ $course->akts }} AKTS
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1 py-1 px-2.5 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Aktif Kayıtlı
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 font-medium text-base">
                                <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                Şu anda kayıtlı olduğunuz bir ders bulunmuyor. Ders seçim ekranından ders ekleyebilirsiniz.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>