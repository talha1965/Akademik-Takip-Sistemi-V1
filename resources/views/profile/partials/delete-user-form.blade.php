<section class="space-y-6">
    <header>
        <h2 class="text-lg font-bold text-red-600 uppercase tracking-tight">
            {{ __('Hesabı Sil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            {{ __('Hesabınız silindiğinde, tüm kaynakları ve verileri kalıcı olarak silinecektir. Hesabınızı silmeden önce lütfen saklamak istediğiniz tüm verileri indirin.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="bg-red-600 hover:bg-red-700 text-white font-bold px-6 py-2 rounded-lg transition"
    >{{ __('Hesabımı Tamamen Sil') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8 bg-white border border-red-100 rounded-xl">
            @csrf
            @method('delete')

            <h2 class="text-xl font-black text-slate-900 tracking-tighter">
                {{ __('Hesabınızı silmek istediğinize emin misiniz?') }}
            </h2>

            <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                {{ __('Hesabınız silindiğinde tüm verileriniz kalıcı olarak yok edilecektir. İşlemi onaylamak için lütfen mevcut şifrenizi giriniz.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Şifre') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4 border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md"
                    placeholder="{{ __('Devam etmek için şifrenizi girin') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')" class="px-6 border-slate-300 text-slate-700">
                    {{ __('İptal Et') }}
                </x-secondary-button>

                <x-danger-button class="ms-3 bg-red-600 hover:bg-red-700 px-6">
                    {{ __('Evet, Hesabımı Sil') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>