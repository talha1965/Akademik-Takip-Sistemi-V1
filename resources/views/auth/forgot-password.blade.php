<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-xl font-bold text-amber-500 tracking-tighter uppercase">Şifre Sıfırlama</h2>
        <p class="text-sm text-gray-400 mt-2">
            Şifrenizi mi unuttunuz? Sorun değil. E-posta adresinizi bize bildirin, size yeni bir şifre seçmenizi sağlayacak sıfırlama bağlantısını gönderelim.
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('E-posta Adresi')" class="text-gray-300" />
            <x-text-input id="email" class="block mt-1 w-full bg-slate-800 border-slate-700 text-white focus:border-amber-500 focus:ring-amber-500" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button class="w-full justify-center bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold">
                Sıfırlama Bağlantısını Gönder
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>