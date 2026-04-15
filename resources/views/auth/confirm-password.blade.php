<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-xl font-bold text-amber-500 tracking-tighter uppercase">Güvenli Alan</h2>
        <p class="text-sm text-gray-400 mt-2">
            Bu projenin güvenli bir alanıdır. Devam etmeden önce lütfen şifrenizi onaylayın.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div>
            <x-input-label for="password" :value="__('Şifre')" class="text-gray-300" />
            <x-text-input id="password" class="block mt-1 w-full bg-slate-800 border-slate-700 text-white focus:border-amber-500 focus:ring-amber-500"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-6">
            <x-primary-button class="w-full justify-center bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold">
                Şifreyi Onayla
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>