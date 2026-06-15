<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-3xl font-black text-sky-600 tracking-tighter">ATS KAYIT</h2>
        <p class="text-slate-500 text-xs uppercase tracking-widest mt-1">Yeni Akademik Profil Oluştur</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Ad Soyad')" class="text-slate-700 font-bold mb-1" />
            <x-text-input id="name" class="block mt-1 w-full bg-white border-slate-300 text-slate-900 focus:border-sky-500 focus:ring-sky-500 rounded-lg shadow-sm transition-colors" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('E-posta Adresi')" class="text-slate-700 font-bold mb-1" />
            <x-text-input id="email" class="block mt-1 w-full bg-white border-slate-300 text-slate-900 focus:border-sky-500 focus:ring-sky-500 rounded-lg shadow-sm transition-colors" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="department_id" :value="__('Bölümünüz')" class="text-slate-700 font-bold mb-1" />
            
            <select id="department_id" name="department_id" class="block mt-1 w-full bg-white border-slate-300 text-slate-900 focus:border-sky-500 focus:ring-sky-500 rounded-lg shadow-sm transition-colors cursor-pointer" required>
                <option value="" disabled selected class="text-slate-400">Lütfen Bölümünüzü Seçin</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            </select>

            <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Şifre')" class="text-slate-700 font-bold mb-1" />
            <x-text-input id="password" class="block mt-1 w-full bg-white border-slate-300 text-slate-900 focus:border-sky-500 focus:ring-sky-500 rounded-lg shadow-sm transition-colors"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Şifre Onayı')" class="text-slate-700 font-bold mb-1" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full bg-white border-slate-300 text-slate-900 focus:border-sky-500 focus:ring-sky-500 rounded-lg shadow-sm transition-colors"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex flex-col gap-4 mt-8">
            <button type="submit" class="w-full flex justify-center items-center bg-sky-600 hover:bg-sky-700 text-white font-bold py-3.5 rounded-lg transition-all shadow-md shadow-sky-500/20 active:scale-[0.98]">
                Kayıt Ol ve Başla
            </button>

            <a class="text-center underline text-sm text-slate-500 hover:text-sky-600 transition-colors mt-2" href="{{ route('login') }}">
                Zaten kayıtlı mısınız? Giriş yapın
            </a>
        </div>
    </form>
</x-guest-layout>