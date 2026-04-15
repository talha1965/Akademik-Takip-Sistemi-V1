<x-app-layout>
    <div x-data="{ tab: 'inbox', composeOpen: false }" class="max-w-7xl mx-auto pb-24 pt-10 px-6 lg:px-8">
        
        
        <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">İletişim Merkezi</h1>
                <p class="text-slate-500 font-medium mt-1">Öğretmenleriniz ve diğer öğrencilerle mesajlaşın.</p>
            </div>
            <button @click="composeOpen = true" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-2xl font-black text-sm uppercase tracking-widest shadow-lg shadow-indigo-200 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Yeni Mesaj
            </button>
        </div>

        <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden flex flex-col md:flex-row min-h-[600px]">
            
            <div class="w-full md:w-64 bg-slate-50 border-r border-slate-100 p-6 flex flex-col gap-2">
                <button @click="tab = 'inbox'" :class="tab === 'inbox' ? 'bg-white shadow-sm text-indigo-600 font-black' : 'text-slate-500 hover:bg-slate-100 font-bold'" class="flex items-center justify-between w-full p-3 rounded-xl transition-all">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        Gelen Kutusu
                    </div>
                    @php $unread = $receivedMessages->where('is_read', false)->count(); @endphp
                    @if($unread > 0)
                        <span class="bg-rose-500 text-white text-[10px] px-2 py-0.5 rounded-full">{{ $unread }}</span>
                    @endif
                </button>
                
                <button @click="tab = 'sent'" :class="tab === 'sent' ? 'bg-white shadow-sm text-indigo-600 font-black' : 'text-slate-500 hover:bg-slate-100 font-bold'" class="flex items-center gap-3 w-full p-3 rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    Gönderilenler
                </button>
            </div>

            <div class="flex-1 bg-white">
                
                <div x-show="tab === 'inbox'" class="divide-y divide-slate-100">
                    @forelse($receivedMessages as $msg)
                        <div x-data="{ open: false }" class="group transition-colors {{ $msg->is_read ? 'bg-white' : 'bg-indigo-50/30' }}">
                            <div @click="open = !open; if(!{{ $msg->is_read ? 'true' : 'false' }}) { fetch('/messages/{{$msg->id}}/read', {method:'PATCH', headers:{'X-CSRF-TOKEN':'{{csrf_token()}}'}}).then(()=>window.location.reload()) }" class="cursor-pointer p-6 flex items-center justify-between hover:bg-slate-50">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-black text-white {{ $msg->is_read ? 'bg-slate-300' : 'bg-indigo-500' }}">
                                        {{ substr($msg->sender->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-black text-slate-800 {{ $msg->is_read ? '' : 'text-indigo-700' }}">{{ $msg->sender->name }}</p>
                                        <p class="text-sm font-bold {{ $msg->is_read ? 'text-slate-500' : 'text-slate-800' }}">{{ $msg->subject }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-xs text-slate-400 font-bold">{{ $msg->created_at->diffForHumans() }}</span>
                                    @if(!$msg->is_read)
                                        <div class="w-3 h-3 bg-indigo-500 rounded-full"></div>
                                    @endif
                                </div>
                            </div>
                            <div x-show="open" x-collapse class="px-20 py-6 bg-slate-50/50 text-slate-600 text-sm leading-relaxed border-t border-slate-100">
                                {{ $msg->body }}
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center text-slate-400 font-bold">Gelen kutunuz boş.</div>
                    @endforelse
                </div>

                <div x-show="tab === 'sent'" x-cloak class="divide-y divide-slate-100">
                    @forelse($sentMessages as $msg)
                        <div x-data="{ open: false }" class="group transition-colors bg-white">
                            <div @click="open = !open" class="cursor-pointer p-6 flex items-center justify-between hover:bg-slate-50">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-black text-slate-500 bg-slate-100">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Alıcı: <span class="text-slate-700">{{ $msg->receiver->name }}</span></p>
                                        <p class="text-sm font-bold text-slate-800">{{ $msg->subject }}</p>
                                    </div>
                                </div>
                                <span class="text-xs text-slate-400 font-bold">{{ $msg->created_at->translatedFormat('d M H:i') }}</span>
                            </div>
                            <div x-show="open" x-collapse class="px-20 py-6 bg-slate-50/50 text-slate-600 text-sm leading-relaxed border-t border-slate-100">
                                {{ $msg->body }}
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center text-slate-400 font-bold">Henüz hiç mesaj göndermediniz.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div x-show="composeOpen" x-cloak class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div @click.away="composeOpen = false" class="bg-white w-full max-w-2xl rounded-[2rem] shadow-2xl overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <h3 class="text-xl font-black text-slate-800">Yeni Mesaj Gönder</h3>
                    <button @click="composeOpen = false" class="text-slate-400 hover:text-rose-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form action="{{ route('messages.store') }}" method="POST" class="p-8">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Kime</label>
                            <select name="receiver_id" class="w-full bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-indigo-500 text-sm font-bold text-slate-700 py-3 px-4" required>
                                <option value="">Kişi Seçin...</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Konu</label>
                            <input type="text" name="subject" class="w-full bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-indigo-500 text-sm font-bold text-slate-700 py-3 px-4" placeholder="Mesajınızın konusu..." required>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Mesaj</label>
                            <textarea name="body" rows="6" class="w-full bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-indigo-500 text-sm font-medium text-slate-700 py-3 px-4 resize-none" placeholder="Mesajınızı buraya yazın..." required></textarea>
                        </div>
                        <div class="flex justify-end pt-4">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-black text-sm uppercase tracking-widest transition-all shadow-lg shadow-indigo-200">
                                Gönder
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>