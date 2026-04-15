<x-app-layout>
    @php
        $user = auth()->user();
        $myCourses = $user->courses;
        $allAssignments = $assignments ?? collect();
        
        // Vize notlarını kartlarda göstermek için çekiyoruz
        $grades = \App\Models\Grade::where('user_id', $user->id)->get()->keyBy('course_id');
    @endphp

    <div class="max-w-7xl mx-auto pb-16 pt-6 px-4 sm:px-6 lg:px-8">
        
        <div class="mb-10">
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Talha'nın Akademik Takipçisi</h1>
            <div class="flex items-center gap-3 mt-2 text-sm font-medium text-slate-500">
                <span class="bg-slate-200/50 px-2.5 py-1 rounded text-slate-600 font-mono text-xs font-bold tracking-wider">221120241013</span>
                <span>•</span>
                <span>Bilgisayar Programcılığı</span>
                <span>•</span>
                <span>2025-2026 Bahar Dönemi</span>
            </div>
        </div>

        <div x-data="{ filter: 'pending' }" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-12">
            
            <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center bg-slate-50/50 gap-4">
                <div class="flex items-center gap-6">
                    <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <h2 class="text-xl font-black text-slate-800">Yapılacaklar Listesi</h2>
                </div>
                
                <div class="flex p-1 bg-slate-200/50 rounded-lg">
                    <button @click="filter = 'pending'" :class="{'bg-white shadow-sm text-slate-800': filter === 'pending', 'text-slate-500 hover:text-slate-700': filter !== 'pending'}" class="px-4 py-1.5 text-xs font-bold rounded-md transition-all">Bekleyenler</button>
                    <button @click="filter = 'completed'" :class="{'bg-white shadow-sm text-slate-800': filter === 'completed', 'text-slate-500 hover:text-slate-700': filter !== 'completed'}" class="px-4 py-1.5 text-xs font-bold rounded-md transition-all">Tamamlananlar</button>
                    <button @click="filter = 'all'" :class="{'bg-white shadow-sm text-slate-800': filter === 'all', 'text-slate-500 hover:text-slate-700': filter !== 'all'}" class="px-4 py-1.5 text-xs font-bold rounded-md transition-all">Hepsini Göster</button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="text-slate-400 bg-white border-b border-slate-100 text-[10px] uppercase tracking-widest font-bold">
                        <tr>
                            <th class="px-6 py-4 w-16 text-center">Durum</th>
                            <th class="px-6 py-4">Görev Adı</th>
                            <th class="px-6 py-4">İlgili Ders</th>
                            <th class="px-6 py-4 w-48">Teslim Zamanı</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($allAssignments as $assignment)
                            @php
                                // Clone hatasından kurtulduk, null-safe ve hatasız kontrol:
                                $ogrenci = $assignment->students->where('id', auth()->id())->first();
                                $isCompleted = $ogrenci ? $ogrenci->pivot->is_completed : false;
                                
                                // Zaman kontrolü:
                                $dueDate = \Carbon\Carbon::parse($assignment->due_date);
                                $isOverdue = $dueDate->endOfDay()->isPast();
                            @endphp
                            
                            <tr x-show="filter === 'all' || (filter === 'pending' && !{{ $isCompleted ? 'true' : 'false' }}) || (filter === 'completed' && {{ $isCompleted ? 'true' : 'false' }})" 
                                class="hover:bg-slate-50/80 transition-colors {{ $isCompleted ? 'opacity-60' : ($isOverdue ? 'opacity-75 grayscale bg-slate-50/40' : '') }}">
                                
                                <td class="px-6 py-4 flex justify-center">
                                    <form action="{{ route('assignment.toggle', $assignment->id) }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" 
                                            class="w-6 h-6 rounded-md border-2 flex items-center justify-center transition-all duration-200 
                                            {{ $isCompleted ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-slate-300 text-transparent' }} 
                                            {{ $isOverdue && !$isCompleted ? 'cursor-not-allowed opacity-50 bg-slate-200' : 'hover:border-emerald-400 hover:text-emerald-200' }}" 
                                            title="{{ $isOverdue && !$isCompleted ? 'Süresi Doldu - İşlem Yapılamaz' : ($isCompleted ? 'Geri al' : 'Tamamlandı işaretle') }}"
                                            {{ $isOverdue && !$isCompleted ? 'disabled' : '' }}>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                    </form>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <p class="font-bold text-sm {{ $isCompleted || $isOverdue ? 'text-slate-500 line-through' : 'text-slate-800' }}">{{ $assignment->title }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5 italic line-clamp-1">{{ $assignment->description ?? 'Ek açıklama bulunmuyor.' }}</p>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <span class="text-xs font-bold text-slate-600 uppercase tracking-tight">{{ $assignment->course->course_name }}</span>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2.5 py-1 rounded-md text-[11px] font-bold {{ $isCompleted ? 'bg-emerald-100 text-emerald-700' : ($isOverdue ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">
                                            {{ $isCompleted ? 'Teslim Edildi' : ($isOverdue ? 'Süresi Doldu' : $dueDate->diffForHumans()) }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 font-medium">{{ $dueDate->format('d M H:i') }}</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400 font-medium">Aktif bir ödev kaydı bulunmuyor.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
<br>
        <h2 class="text-xl font-black text-slate-800 mb-6 flex items-center gap-3">
            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            Ders Kartları
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($myCourses as $index => $course)
                @php
                    // Temiz ve zarif vurgu renkleri
                    $accents = ['sky', 'emerald', 'rose', 'indigo', 'amber'];
                    $color = $accents[$index % count($accents)];
                    
                    // Görev ve not bilgileri (Tarihi geçenleri de bekleyen saymasın diye güncellendi)
                    $pendingCount = $allAssignments->filter(function($a) use ($course) {
                        $ogrenci = $a->students->where('id', auth()->id())->first();
                        $isCompleted = $ogrenci ? $ogrenci->pivot->is_completed : false;
                        $isOverdue = \Carbon\Carbon::parse($a->due_date)->endOfDay()->isPast();
                        
                        return $a->course_id === $course->id && !$isCompleted && !$isOverdue;
                    })->count();
                    
                    $gradeInfo = $grades->get($course->id);
                    $vizeNotu = $gradeInfo ? $gradeInfo->vize : '-';
                @endphp
                
                <div class="bg-white rounded-2xl shadow-sm border-y border-r border-slate-200 border-l-4 border-l-{{$color}}-500 p-6 flex flex-col hover:shadow-md transition-shadow group relative overflow-hidden">
                    
                    <div class="flex justify-between items-start mb-6 z-10">
                        <div class="pr-4">
                            <h3 class="text-lg font-black text-slate-800 leading-tight mb-1.5">{{ $course->course_name }}</h3>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-slate-100 px-2 py-0.5 rounded">{{ $course->course_code ?? 'BLG-###' }}</span>
                        </div>
                        <div class="w-10 h-10 flex-shrink-0 rounded-full bg-{{$color}}-50 flex items-center justify-center text-{{$color}}-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-6 z-10">
                        <div class="bg-slate-50/80 rounded-xl p-3 border border-slate-100">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Bekleyen Görev</p>
                            <p class="text-xl font-black {{ $pendingCount > 0 ? 'text-amber-500' : 'text-slate-700' }} mt-0.5">{{ $pendingCount }}</p>
                        </div>
                        <div class="bg-slate-50/80 rounded-xl p-3 border border-slate-100">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Son Vize Notu</p>
                            <p class="text-xl font-black text-{{$color}}-600 mt-0.5">{{ $vizeNotu }}</p>
                        </div>
                    </div>

                    <div class="mt-auto z-10">
                        <a href="{{ route('messages.index', ['to' => 1]) }}" class="w-full flex items-center justify-center gap-2 bg-white border border-slate-200 hover:border-{{$color}}-300 hover:bg-{{$color}}-50 text-slate-600 hover:text-{{$color}}-700 font-bold text-sm py-2.5 rounded-xl transition-colors mb-2">
                            <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            <span>Hocaya Mesaj At</span>
                        </a>
                        
                        <button class="w-full flex items-center justify-center gap-2 bg-slate-50 border border-slate-100 hover:bg-slate-100 text-slate-500 font-bold text-sm py-2.5 rounded-xl transition-colors">
                            <span>Ders Detayları</span>
                            <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>

                    <svg class="absolute -bottom-6 -right-6 w-32 h-32 text-{{$color}}-50 opacity-50 transform rotate-12 transition-transform group-hover:rotate-0 duration-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
                </div>
            @endforeach
        </div>

    </div>
</x-app-layout>