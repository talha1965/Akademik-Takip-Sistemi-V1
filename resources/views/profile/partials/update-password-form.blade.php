<section>
    <header>
        <h2 class="text-lg font-bold text-slate-800 uppercase tracking-tight">
            {{ __('Şifreyi Güncelle') }}
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            {{ __('Hesabınızın güvenliğini korumak için uzun ve rastgele bir şifre kullandığınızdan emin olun.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Mevcut Şifre')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full border-gray-300 focus:border-amber-500 focus:ring-amber-500 rounded-md shadow-sm" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('Yeni Şifre')" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full border-gray-300 focus:border-amber-500 focus:ring-amber-500 rounded-md shadow-sm" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Yeni Şifre Onayı')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full border-gray-300 focus:border-amber-500 focus:ring-amber-500 rounded-md shadow-sm" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="bg-slate-900 hover:bg-slate-800 text-amber-500 font-bold border border-amber-500/20">
                {{ __('Şifreyi Kaydet') }}
            </x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-medium"
                >{{ __('Başarıyla kaydedildi.') }}</p>
            @endif
        </div>
    </form>
</section>