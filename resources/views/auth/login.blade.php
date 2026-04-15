<x-guest-layout>
    <div class="mb-10 text-left">
        
        <h1 class="text-4xl font-black text-slate-900 tracking-tighter">Sisteme Giriş</h1>
        <p class="text-slate-500 mt-2 font-medium">Kullanıcı Adı ve Parolanızı Giriniz</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div>
            <label class="text-sm font-bold text-slate-700 ml-1">Kullanıcı Adı (E-posta)</label>
            <x-text-input id="email" class="block mt-1 w-full border-gray-200 focus:border-amber-500 focus:ring-amber-500 rounded-none border-b-2 border-l-0 border-r-0 border-t-0 shadow-none px-0 text-lg" 
                type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <div class="flex justify-between items-center">
                <label class="text-sm font-bold text-slate-700 ml-1">Parola</label>
            </div>
            <x-text-input id="password" class="block mt-1 w-full border-gray-200 focus:border-amber-500 focus:ring-amber-500 rounded-none border-b-2 border-l-0 border-r-0 border-t-0 shadow-none px-0 text-lg"
                type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <a class="text-sm font-bold text-amber-500 hover:text-amber-600 transition" href="{{ route('password.request') }}">
                Parolamı Unuttum!
            </a>
        </div>

        <div class="pt-4">
            <x-primary-button class="w-full justify-center bg-amber-500 hover:bg-amber-600 text-slate-900 font-black py-4 rounded-none shadow-xl shadow-amber-500/20 transition-all active:scale-95 text-base uppercase">
                Giriş Yap
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>