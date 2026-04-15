<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-2xl font-black text-amber-500 tracking-tighter">ATS KAYIT</h2>
        <p class="text-gray-400 text-xs uppercase tracking-widest">Yeni Akademik Profil Oluştur</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Ad Soyad')" class="text-gray-300" />
            <x-text-input id="name" class="block mt-1 w-full bg-slate-800 border-slate-700 text-white focus:border-amber-500 focus:ring-amber-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('E-posta Adresi')" class="text-gray-300" />
            <x-text-input id="email" class="block mt-1 w-full bg-slate-800 border-slate-700 text-white focus:border-amber-500 focus:ring-amber-500" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Şifre')" class="text-gray-300" />
            <x-text-input id="password" class="block mt-1 w-full bg-slate-800 border-slate-700 text-white focus:border-amber-500 focus:ring-amber-500"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Şifre Onayı')" class="text-gray-300" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full bg-slate-800 border-slate-700 text-white focus:border-amber-500 focus:ring-amber-500"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex flex-col gap-4 mt-6">
            <x-primary-button class="w-full justify-center bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold py-3">
                Kayıt Ol ve Başla
            </x-primary-button>

            <a class="text-center underline text-sm text-gray-500 hover:text-amber-500" href="{{ route('login') }}">
                Zaten kayıtlı mısınız? Giriş yapın
            </a>
        </div>
    </form>
</x-guest-layout>