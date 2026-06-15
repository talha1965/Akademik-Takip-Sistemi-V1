<x-app-layout>
    <div class="max-w-7xl mx-auto pb-12">
        
        <div class="bg-gradient-to-r from-slate-800 to-slate-900 rounded-3xl shadow-xl mb-8 overflow-hidden relative flex flex-col md:flex-row items-center mt-6">
            <div class="p-8 relative z-10 flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <span class="p-2 bg-white/10 rounded-xl backdrop-blur-sm text-2xl">👨‍🏫</span>
                    <h1 class="text-3xl font-black text-white">Akademisyen Paneli</h1>
                </div>
                <p class="text-slate-300 text-sm md:text-base max-w-xl">Sisteme yeni dersler ekleyin, not girişlerini tamamlayın ve öğrencilerinizin akademik ilerlemesini tek ekrandan yönetin.</p>
            </div>
            
            <div class="w-full md:w-auto bg-white/5 backdrop-blur-md p-6 border-t md:border-t-0 md:border-l border-white/10 flex gap-8 justify-center h-full">
                <div class="text-center">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Açılan Ders</p>
                    <p class="text-3xl font-black text-sky-400">{{ count($courses) }}</p>
                </div>
                <div class="w-px bg-white/10"></div>
                <div class="text-center">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Öğrenci</p>
                    <p class="text-3xl font-black text-emerald-400">{{ count($students) }}</p>
                </div>
            </div>
            
            <svg class="absolute right-0 bottom-0 h-64 text-white/5 transform translate-x-1/3 translate-y-1/4 pointer-events-none" fill="currentColor" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="50"></circle>
            </svg>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 p-4 mb-8 rounded-2xl shadow-sm flex items-center gap-4 animate-fade-in-down">
                <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-100 p-4 mb-8 rounded-2xl shadow-sm flex items-start gap-4">
                <div class="w-10 h-10 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <ul class="list-disc pl-5 text-sm font-bold text-rose-800 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mb-8 transition-shadow hover:shadow-md">
            <div class="p-6 border-b border-slate-100 bg-gradient-to-r from-sky-50 to-white flex justify-between items-center">
                <h3 class="font-black text-sky-800 flex items-center gap-3 text-lg">
                    <div class="p-2 bg-sky-100 text-sky-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </div>
                    Sisteme Yeni Ders Ekle
                </h3>
            </div>
            
            <form action="{{ route('course.store') }}" method="POST" class="p-8">
                @csrf
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2 group">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 group-focus-within:text-sky-600 transition-colors">Dersin Adı</label>
                            <input type="text" name="course_name" placeholder="Örn: Nesne Yönelimli Programlama" class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-sky-500 focus:border-sky-500 shadow-inner px-4 py-3 transition-all" required>
                        </div>
                        <div class="group">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 group-focus-within:text-sky-600 transition-colors">Ders Kodu</label>
                            <input type="text" name="course_code" placeholder="Örn: BIL201" class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-sky-500 focus:border-sky-500 shadow-inner px-4 py-3 transition-all uppercase" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-6 p-6 bg-slate-50/50 border border-slate-100 rounded-2xl">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">AKTS</label>
                            <input type="number" name="akts" min="1" max="30" placeholder="5" class="w-full border-slate-200 rounded-xl focus:ring-sky-500 text-center font-bold text-lg" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kredi</label>
                            <input type="number" name="credits" min="1" max="10" placeholder="3" class="w-full border-slate-200 rounded-xl focus:ring-sky-500 text-center font-bold text-lg" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kontenjan</label>
                            <input type="number" name="quota" min="1" placeholder="50" class="w-full border-slate-200 rounded-xl focus:ring-sky-500 text-center font-bold text-lg" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Dönem</label>
                            <select name="semester" class="w-full border-slate-200 rounded-xl focus:ring-sky-500 font-bold" required>
                                <option value="1">Güz Dönemi</option>
                                <option value="2">Bahar Dönemi</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">İlgili Bölüm</label>
                            <select name="department_id" class="w-full border-slate-200 rounded-xl focus:ring-sky-500 font-bold" required>
                                <option value="" disabled selected>Bölüm Seçin...</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Teorik Saat (Haftalık)</label>
                            <input type="number" name="theory_hours" min="0" placeholder="2" class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-sky-500 text-center" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pratik Saat (Haftalık)</label>
                            <input type="number" name="practice_hours" min="0" placeholder="2" class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-sky-500 text-center" required>
                        </div>
                        <div class="w-full">
                            <button type="submit" class="w-full bg-gradient-to-r from-sky-500 to-sky-600 hover:from-sky-600 hover:to-sky-700 text-white font-black py-3.5 px-6 rounded-xl transition-all shadow-lg shadow-sky-500/30 active:scale-[0.98] flex justify-center items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Dersi Havuza Ekle
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mb-8">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-black text-slate-800 flex items-center gap-3 text-lg">
                    <div class="p-2 bg-slate-100 text-slate-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    </div>
                    Mevcut Dersleri Yönet
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50/80 border-b border-slate-100 text-slate-500 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4 font-bold">Ders Kodu</th>
                            <th class="px-6 py-4 font-bold">Ders Adı & Güncelleme</th>
                            <th class="px-6 py-4 font-bold text-center">Kontenjan</th>
                            <th class="px-6 py-4 font-bold text-right">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($courses as $course)
                        <tr class="hover:bg-slate-50 transition group">
                            <td class="px-6 py-5">
                                <span class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-100">{{ $course->course_code }}</span>
                            </td>
                            <td class="px-6 py-5">
                                <form action="{{ route('course.update', $course->id) }}" method="POST" class="flex items-center gap-3">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="course_name" value="{{ $course->course_name }}" class="bg-transparent border-transparent hover:border-slate-200 focus:bg-white rounded-lg text-sm font-bold text-slate-800 focus:ring-sky-500 focus:border-sky-500 w-full max-w-sm px-3 py-2 transition-all">
                                    <button type="submit" class="text-sky-600 hover:text-white hover:bg-sky-500 text-xs font-bold bg-sky-50 px-3 py-2 rounded-lg transition-all opacity-0 group-hover:opacity-100 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                        Kaydet
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-slate-600 font-bold text-xs">{{ $course->quota ?? 50 }}</span>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <form action="{{ route('course.delete', $course->id) }}" method="POST" onsubmit="return confirm('Bu dersi silmek istediğinize emin misiniz? Derse ait tüm notlar ve ödevler de SİLİNECEKTİR!');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-500 hover:text-white hover:bg-rose-500 text-xs font-bold bg-rose-50 px-3 py-2 rounded-lg transition-all border border-rose-100 hover:border-rose-500 flex items-center gap-1 ml-auto">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Sil
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400 font-medium">Henüz sistemde kayıtlı ders bulunmuyor. Yukarıdaki formu kullanarak ilk dersinizi oluşturun.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden flex flex-col hover:shadow-md transition-shadow">
                <div class="p-6 border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-white flex justify-between items-center">
                    <h3 class="font-black text-indigo-800 flex items-center gap-3 text-lg">
                        <div class="p-2 bg-indigo-100 text-indigo-600 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        Sınav Notu Girişi
                    </h3>
                    @if(count($courses) > 0)
                        <a href="{{ route('teacher.course.settings') }}" class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs uppercase tracking-widest py-2 px-4 rounded-xl transition-all shadow-md shadow-indigo-500/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                            Yüzdelik Ayarları
                        </a>
                    @endif
                </div>
                
                <form action="{{ route('grade.store') }}" method="POST" class="p-8 flex-1 flex flex-col">
                    @csrf
                    <div class="space-y-5 flex-1">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Öğrenci Seçimi</label>
                            <select name="user_id" class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 font-medium py-3" required>
                                <option value="" disabled selected>Öğrenci Seçiniz...</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->student_number ?? 'No Yok' }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Ders Seçimi</label>
                            <select name="course_id" class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 font-medium py-3" required>
                                <option value="" disabled selected>Ders Seçiniz...</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4 pt-2">
                            <div class="relative">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Vize Notu</label>
                                <div class="relative">
                                    <input type="number" name="vize" min="0" max="100" class="w-full bg-white border-slate-200 rounded-xl pl-10 pr-4 py-3 text-lg font-black focus:ring-indigo-500 focus:border-indigo-500 transition-all shadow-sm" placeholder="0-100" required>
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-bold">V</span>
                                </div>
                            </div>
                            <div class="relative">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Final Notu</label>
                                <div class="relative">
                                    <input type="number" name="final" min="0" max="100" class="w-full bg-white border-slate-200 rounded-xl pl-10 pr-4 py-3 text-lg font-black focus:ring-indigo-500 focus:border-indigo-500 transition-all shadow-sm" placeholder="0-100">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-bold">F</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full mt-8 bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 px-4 rounded-xl transition-all shadow-lg shadow-indigo-500/30 active:scale-[0.98]">
                        Notu Kaydet ve Bildir
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden flex flex-col hover:shadow-md transition-shadow">
                <div class="p-6 border-b border-slate-100 bg-gradient-to-r from-amber-50 to-white flex justify-between items-center">
                    <h3 class="font-black text-amber-800 flex items-center gap-3 text-lg">
                        <div class="p-2 bg-amber-100 text-amber-600 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        Yeni Ödev Atama
                    </h3>
                </div>
                
                <form action="{{ route('assignment.store') }}" method="POST" class="p-8 flex-1 flex flex-col">
                    @csrf
                    <div class="space-y-5 flex-1">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Ders Seçimi</label>
                            <select name="course_id" class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-amber-500 focus:border-amber-500 font-medium py-3" required>
                                <option value="" disabled selected>Ders Seçiniz...</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Ödev Başlığı</label>
                            <input type="text" name="title" placeholder="Örn: Vize Projesi Araştırma Raporu" class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-amber-500 focus:border-amber-500 py-3" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Görev Açıklaması</label>
                            <textarea name="description" rows="3" placeholder="Öğrencilerden beklenenleri detaylıca yazın..." class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-amber-500 focus:border-amber-500 py-3 resize-none" required></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Son Teslim Tarihi</label>
                            <input type="date" name="due_date" class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-amber-500 focus:border-amber-500 py-3 font-medium" required>
                        </div>
                    </div>

                    <button type="submit" class="w-full mt-6 bg-amber-500 hover:bg-amber-600 text-white font-black py-4 px-4 rounded-xl transition-all shadow-lg shadow-amber-500/30 active:scale-[0.98]">
                        Ödevi Sisteme Yükle
                    </button>
                </form>
            </div>

        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mt-8">
            <div class="p-6 border-b border-slate-100 bg-gradient-to-r from-emerald-50 to-white flex justify-between items-center">
                <h3 class="font-black text-emerald-800 flex items-center gap-3 text-lg">
                    <div class="p-2 bg-emerald-100 text-emerald-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    Kayıtlı Öğrenciler ve Genel Durum
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50/80 border-b border-slate-100 text-slate-500 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4 font-bold">Öğrenci Bilgileri</th>
                            <th class="px-6 py-4 font-bold text-center">Aldığı Ders Sayısı</th>
                            <th class="px-6 py-4 font-bold text-right">Detaylı İncele</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($students as $student)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-5 flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-emerald-400 to-sky-400 flex items-center justify-center text-white font-bold text-sm shadow-inner shrink-0">
                                    {{ substr($student->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 text-base">{{ $student->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $student->email }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="inline-flex items-center gap-1.5 bg-sky-50 text-sky-700 px-3 py-1.5 rounded-lg font-bold border border-sky-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    {{ $student->courses->count() }} Ders
                                </span>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <a href="{{ route('teacher.student.details', $student->id) }}" class="inline-flex items-center justify-center gap-2 bg-white hover:bg-emerald-50 text-emerald-600 border border-emerald-200 hover:border-emerald-300 text-xs font-black py-2.5 px-4 rounded-xl transition-all shadow-sm">
                                    Profili Aç
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-slate-400 font-medium">Sistemde henüz kayıtlı bir öğrenci bulunmuyor.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>