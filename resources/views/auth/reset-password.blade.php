<x-guest-layout>
    <div class="mb-4 text-center border-b border-slate-800 pb-4">
        <h2 class="text-xl font-bold text-amber-500 tracking-tighter uppercase">Yeni Şifre Belirle</h2>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <x-input-label for="email" :value="__('E-posta Adresi')" class="text-gray-300" />
            <x-text-input id="email" class="block mt-1 w-full bg-slate-800 border-slate-700 text-white focus:border-amber-500 focus:ring-amber-500" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Yeni Şifre')" class="text-gray-300" />
            <x-text-input id="password" class="block mt-1 w-full bg-slate-800 border-slate-700 text-white focus:border-amber-500 focus:ring-amber-500" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Yeni Şifre Onayı')" class="text-gray-300" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full bg-slate-800 border-slate-700 text-white focus:border-amber-500 focus:ring-amber-500"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button class="w-full justify-center bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold">
                Şifreyi Güncelle
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>