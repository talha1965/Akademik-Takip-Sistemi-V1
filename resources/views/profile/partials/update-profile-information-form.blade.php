<section>
    <header>
        <h2 class="text-lg font-bold text-slate-800 uppercase tracking-tight">
            {{ __('Profil Bilgileri') }}
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            {{ __("Hesabınızın profil bilgilerini ve e-posta adresini buradan güncelleyebilirsiniz.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Ad Soyad')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full border-gray-300 focus:border-amber-500 focus:ring-amber-500 rounded-md shadow-sm" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('E-posta Adresi')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full border-gray-300 focus:border-amber-500 focus:ring-amber-500 rounded-md shadow-sm" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-3 bg-amber-50 rounded-lg border border-amber-200">
                    <p class="text-sm text-amber-800">
                        {{ __('E-posta adresiniz henüz doğrulanmamış.') }}

                        <button form="send-verification" class="block mt-2 underline text-sm text-amber-900 hover:text-amber-700 font-bold focus:outline-none transition">
                            {{ __('Doğrulama e-postasını tekrar göndermek için buraya tıklayın.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 italic">
                            {{ __('E-posta adresinize yeni bir doğrulama bağlantısı gönderildi.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="bg-slate-900 hover:bg-slate-800 text-amber-500 font-bold border border-amber-500/20 px-6">
                {{ __('Bilgileri Kaydet') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
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