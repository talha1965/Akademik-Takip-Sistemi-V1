<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-xl font-bold text-amber-500 tracking-tighter uppercase">E-posta Doğrulama</h2>
        <p class="text-sm text-gray-400 mt-2">
            Kayıt olduğunuz için teşekkürler! Başlamadan önce, size yeni gönderdiğimiz bağlantıya tıklayarak e-posta adresinizi doğrulayabilir misiniz? Eğer e-posta gelmediyse, size memnuniyetle bir tane daha gönderebiliriz.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-500 text-center bg-green-500/10 p-2 rounded border border-green-500/20">
            Kayıt sırasında belirttiğiniz adrese yeni bir doğrulama bağlantısı gönderildi.
        </div>
    @endif

    <div class="mt-6 flex flex-col gap-4 items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}" class="w-full">
            @csrf
            <x-primary-button class="w-full justify-center bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold">
                Doğrulama E-postasını Tekrar Gönder
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="underline text-sm text-gray-500 hover:text-amber-500 transition">
                Güvenli Çıkış Yap
            </button>
        </form>
    </div>
</x-guest-layout>