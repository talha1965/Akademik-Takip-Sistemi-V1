<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Ana Panel') }}
                    </x-nav-link>

                    {{-- ÖĞRENCİ LİNKLERİ --}}
                    @if(auth()->user()->role === 'student')
                        <x-nav-link :href="route('student.courses')" :active="request()->routeIs('student.courses')">
                            {{ __('Derslerim') }}
                        </x-nav-link>
                        <x-nav-link :href="route('student.assignments')" :active="request()->routeIs('student.assignments')">
                            {{ __('Ödevler') }}
                        </x-nav-link>
                        <x-nav-link :href="route('student.analysis')" :active="request()->routeIs('student.analysis')">
                            {{ __('Akademik Analiz') }}
                        </x-nav-link>
                        <x-nav-link :href="route('student.exams')" :active="request()->routeIs('student.exams')">
                            {{ __('Sınav Programı') }}
                        </x-nav-link>
                        <x-nav-link :href="route('course.selection')" :active="request()->routeIs('course.selection')">
                            {{ __('Ders Seçimi') }}
                        </x-nav-link>
                    @endif

                    {{-- ÖĞRETMEN LİNKLERİ --}}
                    @if(auth()->user()->role === 'teacher')
                        <x-nav-link :href="route('teacher.exams')" :active="request()->routeIs('teacher.exams')">
                            {{ __('Sınav Programı') }}
                        </x-nav-link>
                        <x-nav-link :href="route('messages.index')" :active="request()->routeIs('messages.index')">
                            {{ __('Mesajlar') }}
                        </x-nav-link>
                    @endif

                    {{-- ADMİN LİNKLERİ --}}
                    @if(auth()->user()->role === 'admin')
                        <x-nav-link :href="route('admin.panel')" :active="request()->routeIs('admin.panel')">
                            {{ __('Yönetim Paneli') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profil Bilgilerim') }}
                        </x-dropdown-link>

                        {{-- Öğrenci dropdown ekleri --}}
                        @if(auth()->user()->role === 'student')
                            <x-dropdown-link :href="route('messages.index')">
                                {{ __('Mesajlarım') }}
                            </x-dropdown-link>
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Güvenli Çıkış') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- MOBİL MENÜ BUTONU --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- MOBİL MENÜ --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Ana Panel') }}
            </x-responsive-nav-link>

            @if(auth()->user()->role === 'student')
                <x-responsive-nav-link :href="route('student.courses')" :active="request()->routeIs('student.courses')">
                    {{ __('Derslerim') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('student.assignments')" :active="request()->routeIs('student.assignments')">
                    {{ __('Ödevler') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('student.analysis')" :active="request()->routeIs('student.analysis')">
                    {{ __('Akademik Analiz') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('student.exams')" :active="request()->routeIs('student.exams')">
                    {{ __('Sınav Programı') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('course.selection')" :active="request()->routeIs('course.selection')">
                    {{ __('Ders Seçimi') }}
                </x-responsive-nav-link>
            @endif

            @if(auth()->user()->role === 'teacher')
                <x-responsive-nav-link :href="route('teacher.exams')" :active="request()->routeIs('teacher.exams')">
                    {{ __('Sınav Programı') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('messages.index')" :active="request()->routeIs('messages.index')">
                    {{ __('Mesajlar') }}
                </x-responsive-nav-link>
            @endif

            @if(auth()->user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.panel')" :active="request()->routeIs('admin.panel')">
                    {{ __('Yönetim Paneli') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profil Bilgilerim') }}
                </x-responsive-nav-link>

                @if(auth()->user()->role === 'student')
                    <x-responsive-nav-link :href="route('messages.index')">
                        {{ __('Mesajlarım') }}
                    </x-responsive-nav-link>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Güvenli Çıkış') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>