<x-app-layout>
    <div class="max-w-5xl mx-auto">
        
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black text-slate-800">Dönem Ders Kaydı</h1>
                <p class="text-slate-500 text-sm mt-1">2025-2026 Bahar Yarıyılı için almak istediğiniz dersleri aşağıdan seçiniz.</p>
            </div>
            <div class="w-12 h-12 bg-sky-100 text-sky-600 rounded-xl flex items-center justify-center text-xl shadow-inner">🎓</div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-8 rounded-r-lg">
                <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
            </div>
        @endif

        <form action="{{ route('course.enroll') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                @forelse($allCourses as $course)
                    <label class="relative flex items-start p-5 cursor-pointer rounded-2xl border-2 transition-all duration-200 shadow-sm
                        {{ in_array($course->id, $myCourses) ? 'border-sky-500 bg-sky-50/30' : 'border-slate-100 bg-white hover:border-slate-300' }}">
                        
                        <div class="flex items-center h-6 mt-1">
                            <input type="checkbox" name="courses[]" value="{{ $course->id }}" 
                                {{ in_array($course->id, $myCourses) ? 'checked' : '' }}
                                class="w-5 h-5 text-sky-600 bg-slate-100 border-slate-300 rounded focus:ring-sky-500 focus:ring-2">
                        </div>
                        
                        <div class="ml-4 flex-1">
                            <span class="block text-sm font-bold text-slate-800">{{ $course->course_name }}</span>
                            <span class="block text-xs font-medium text-slate-500 mt-0.5">Ders Kodu: {{ $course->course_code ?? 'BLG-'.$course->id }}</span>
                        </div>

                        <div class="ml-auto text-right">
                            <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 text-xs font-bold text-slate-600 ring-1 ring-inset ring-slate-500/10">
                                {{ $course->akts ?? 5 }} AKTS
                            </span>
                        </div>
                    </label>
                @empty
                    <div class="col-span-2 text-center py-10 text-slate-500">
                        Sistemde henüz açılmış bir ders bulunmamaktadır.
                    </div>
                @endforelse
            </div>

            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between sticky bottom-6 z-10">
                <div class="text-sm text-slate-600 font-medium">
                    <span class="text-sky-600 font-bold">Not:</span> Seçtiğiniz dersler anında Dashboard'unuza yansıyacaktır.
                </div>
                <button type="submit" class="bg-sky-600 hover:bg-sky-700 text-white font-bold py-2.5 px-6 rounded-lg transition shadow-md shadow-sky-500/30">
                    Seçili Dersleri Kaydet
                </button>
            </div>
        </form>

    </div>
</x-app-layout>