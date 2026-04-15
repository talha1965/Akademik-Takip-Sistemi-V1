<x-app-layout>
    <div class="max-w-7xl mx-auto pb-12">
        
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 mb-8 flex justify-between items-center mt-6">
            <div>
                <h1 class="text-2xl font-black text-slate-800">Akademisyen Kontrol Paneli</h1>
                <p class="text-slate-500 text-sm mt-1">Sisteme ders ekleyebilir, öğrencilerinize not girebilir veya yeni görevler atayabilirsiniz.</p>
            </div>
            <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center text-xl shadow-inner">👨‍🏫</div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-8 rounded-r-lg shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8 flex flex-col">
            <div class="p-5 border-b border-slate-100 bg-sky-50/50 flex justify-between items-center">
                <h3 class="font-bold text-sky-800 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Sisteme Yeni Ders Ekle
                </h3>
            </div>
            <div class="p-6">
                <form action="{{ route('course.store') }}" method="POST" class="flex flex-col md:flex-row gap-4 items-end">
                    @csrf
                    <div class="flex-1 w-full">
                        <label class="block text-sm font-bold text-slate-700 mb-1">Dersin Adı</label>
                        <input type="text" name="course_name" placeholder="Örn: Nesne Yönelimli Programlama (OOP)" class="w-full border-slate-300 rounded-lg focus:ring-sky-500 focus:border-sky-500 shadow-sm" required>
                    </div>
                    <button type="submit" class="w-full md:w-auto bg-sky-600 hover:bg-sky-700 text-white font-bold py-2.5 px-6 rounded-lg transition shadow-md shadow-sky-500/30 whitespace-nowrap">
                        Dersi Oluştur
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8 flex flex-col">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    Mevcut Dersleri Yönet
                </h3>
            </div>
            <div class="p-0 overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 border-b border-slate-100 text-slate-500">
                        <tr>
                            <th class="px-6 py-3 font-semibold">Ders Kodu</th>
                            <th class="px-6 py-3 font-semibold">Ders Adı</th>
                            <th class="px-6 py-3 font-semibold text-right">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($courses as $course)
                        <tr class="hover:bg-slate-50 transition group">
                            <td class="px-6 py-4 font-mono text-xs text-slate-500">{{ $course->course_code }}</td>
                            <td class="px-6 py-4">
                                <form action="{{ route('course.update', $course->id) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="course_name" value="{{ $course->course_name }}" class="border-slate-200 rounded-md text-sm focus:ring-sky-500 focus:border-sky-500 w-full max-w-xs px-2 py-1.5 transition shadow-sm">
                                    <button type="submit" class="text-sky-600 hover:text-sky-800 text-xs font-bold bg-sky-50 hover:bg-sky-100 px-3 py-1.5 rounded transition opacity-0 group-hover:opacity-100 border border-sky-200">Güncelle</button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('course.delete', $course->id) }}" method="POST" onsubmit="return confirm('Bu dersi silmek istediğinize emin misiniz? Derse ait tüm notlar ve ödevler de SİLİNECEKTİR!');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-white hover:bg-red-500 text-xs font-bold bg-red-50 px-3 py-1.5 rounded transition border border-red-200 hover:border-red-500">Dersi Sil</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-slate-400">Henüz sistemde kayıtlı ders bulunmuyor.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
                <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-800">Sınav Notu Girişi</h3>
                </div>
                <a href="{{ route('teacher.course.settings', $course->id) }}" class="inline-flex items-center justify-center gap-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 font-bold text-xs uppercase tracking-widest py-2 px-4 rounded-xl transition-all border border-indigo-100">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
    Değerlendirme Ayarları
</a>
                <div class="p-6">
                    <form action="{{ route('grade.store') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Öğrenci Seçimi</label>
                            <select name="user_id" class="w-full border-slate-300 rounded-lg focus:ring-sky-500 focus:border-sky-500 shadow-sm" required>
                                <option value="" disabled selected>Öğrenci Seçiniz...</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Ders Seçimi</label>
                            <select name="course_id" class="w-full border-slate-300 rounded-lg focus:ring-sky-500 focus:border-sky-500 shadow-sm" required>
                                <option value="" disabled selected>Ders Seçiniz...</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Vize Notu</label>
                                <input type="number" name="vize" min="0" max="100" class="w-full border-slate-300 rounded-lg focus:ring-sky-500 focus:border-sky-500 shadow-sm" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Final Notu</label>
                                <input type="number" name="final" min="0" max="100" class="w-full border-slate-300 rounded-lg focus:ring-sky-500 focus:border-sky-500 shadow-sm">
                            </div>
                        </div>

                        <button type="submit" class="w-full mt-4 bg-sky-600 hover:bg-sky-700 text-white font-bold py-3 px-4 rounded-lg transition shadow-md shadow-sky-500/30">
                            Notu Kaydet
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
                <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-800">Yeni Ödev Atama</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('assignment.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Ders Seçimi</label>
                            <select name="course_id" class="w-full border-slate-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 shadow-sm" required>
                                <option value="" disabled selected>Ders Seçiniz...</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Ödev Başlığı</label>
                            <input type="text" name="title" placeholder="Örn: Vize Projesi Raporu" class="w-full border-slate-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 shadow-sm" required>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Ödev Açıklaması</label>
                            <textarea name="description" rows="3" placeholder="Öğrencilerden beklenenleri kısaca yazın..." class="w-full border-slate-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 shadow-sm" required></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Son Teslim Tarihi</label>
                            <input type="date" name="due_date" class="w-full border-slate-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 shadow-sm" required>
                        </div>

                        <button type="submit" class="w-full mt-4 bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-4 rounded-lg transition shadow-md shadow-amber-500/30">
                            Ödevi Sisteme Yükle
                        </button>
                    </form>
                </div>
            </div>

        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8 mt-8 flex flex-col">
    <div class="p-5 border-b border-slate-100 bg-emerald-50/50 flex justify-between items-center">
        <h3 class="font-bold text-emerald-800 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            Kayıtlı Öğrenciler ve Akademik Durumları
        </h3>
    </div>
    <div class="p-0 overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 text-xs uppercase">
                <tr>
                    <th class="px-6 py-4 font-bold">Öğrenci Adı</th>
                    <th class="px-6 py-4 font-bold">E-Posta</th>
                    <th class="px-6 py-4 font-bold text-center">Aldığı Ders Sayısı</th>
                    <th class="px-6 py-4 font-bold text-right">İşlemler</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($students as $student)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4 font-bold text-slate-800">{{ $student->name }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $student->email }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="bg-sky-50 text-sky-700 px-3 py-1 rounded-full font-bold border border-sky-100">
                            {{ $student->courses->count() }} Ders
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('teacher.student.details', $student->id) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black py-2 px-4 rounded-lg transition shadow-md">
                            Öğrenciyi Yönet →
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
</div>
    </div>
</x-app-layout>