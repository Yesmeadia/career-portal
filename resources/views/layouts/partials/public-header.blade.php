<header class="sticky top-4 z-50 w-full px-4 md:px-8">
    <nav class="nav-pill-header max-w-6xl mx-auto backdrop-blur-xl rounded-full px-4 md:px-6 py-2.5 flex items-center justify-between relative transition-all duration-200"
        x-data="{ mobileOpen: false }">

        {{-- Brand Logo & Site Name --}}
        <a class="flex items-center gap-3 flex-shrink-0 z-10" href="{{ route('home') }}">
            <img src="{{ asset('logo.png') }}" alt="{{ $siteSettings['site_name'] ?? 'YES India Foundation' }}"
                class="h-9 w-auto object-contain" style="max-height: 36px; height: 36px; width: auto;" height="36">
            <span
                class="font-figtree font-semibold text-deep-onyx dark:text-white text-[15px] tracking-tight leading-none hidden sm:block">
                {{ $siteSettings['site_name'] ?? 'YES India Foundation' }}
            </span>
        </a>

        {{-- Desktop Navigation Links — Absolutely centered --}}
        <div class="hidden md:flex items-center gap-8 absolute left-0 right-0 justify-center pointer-events-none">
            <div class="flex items-center gap-8 pointer-events-auto font-figtree font-normal text-[14.5px]">
                <a class="text-[#555] dark:text-white/80 hover:text-deep-onyx dark:hover:text-electric-green transition-colors duration-150 {{ request()->routeIs('home') ? 'text-deep-onyx dark:text-electric-green font-semibold' : '' }}"
                    href="{{ route('home') }}">Home</a>
                <a class="text-[#555] dark:text-white/80 hover:text-deep-onyx dark:hover:text-electric-green transition-colors duration-150 {{ request()->routeIs('vacancies.index') ? 'text-deep-onyx dark:text-electric-green font-semibold' : '' }}"
                    href="{{ route('vacancies.index') }}">Find Jobs</a>
                <a class="text-[#555] dark:text-white/80 hover:text-deep-onyx dark:hover:text-electric-green transition-colors duration-150 {{ request()->routeIs('faq') ? 'text-deep-onyx dark:text-electric-green font-semibold' : '' }}"
                    href="{{ route('faq') }}">FAQ</a>
                <a class="text-[#555] dark:text-white/80 hover:text-deep-onyx dark:hover:text-electric-green transition-colors duration-150 {{ request()->routeIs('contact') ? 'text-deep-onyx dark:text-electric-green font-semibold' : '' }}"
                    href="{{ route('contact') }}">Contact</a>
                <a class="text-[#555] dark:text-white/80 hover:text-deep-onyx dark:hover:text-electric-green transition-colors duration-150 {{ request()->routeIs('applications.track') ? 'text-deep-onyx dark:text-electric-green font-semibold' : '' }}"
                    href="{{ route('applications.track') }}">Track Status</a>
            </div>
        </div>

        {{-- Desktop Right Actions --}}
        <div class="hidden md:flex items-center gap-2.5 flex-shrink-0 z-10">
            {{-- Dark Mode Toggle --}}
            <button x-data @click="$store.theme.toggle()" type="button"
                class="w-8 h-8 rounded-full bg-gray-100 dark:bg-white/10 flex items-center justify-center text-deep-onyx dark:text-white hover:bg-deep-onyx hover:text-white dark:hover:bg-white/20 transition-all duration-150 cursor-pointer"
                title="Toggle dark mode" aria-label="Toggle dark mode">
                <span class="material-symbols-outlined text-[17px]" x-show="!$store.theme.dark"
                    style="font-size:17px;">dark_mode</span>
                <span class="material-symbols-outlined text-[17px]" x-show="$store.theme.dark"
                    style="font-size:17px; display:none;">light_mode</span>
            </button> {{-- Login / Dashboard Pill Button --}}
            @auth
                <a href="{{ route('dashboard') }}"
                    class="flex items-center bg-electric-green rounded-full pl-1 pr-4 py-1.5 hover:opacity-95 transition-all duration-150 shadow-sm cursor-pointer gap-2.5 border border-transparent">
                    <span
                        class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-white leading-none"
                            style="font-size:14px;">arrow_forward</span>
                    </span>
                    <span
                        class="font-figtree font-semibold text-[14px] text-white tracking-wide">Dashboard</span>
                </a>
            @else
                <a href="{{ route('login') }}"
                    class="flex items-center bg-electric-green rounded-full pl-1 pr-4 py-1.5 hover:opacity-95 transition-all duration-150 shadow-sm cursor-pointer gap-2.5 border border-transparent">
                    <span
                        class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-white leading-none"
                            style="font-size:14px;">arrow_forward</span>
                    </span>
                    <span
                        class="font-figtree font-semibold text-[14px] text-white tracking-wide">Login</span>
                </a>
            @endauth
        </div>

        {{-- Mobile Menu Trigger --}}
        <div class="flex md:hidden items-center gap-2 z-10">
            <button x-data @click="$store.theme.toggle()" type="button"
                class="w-9 h-9 rounded-full bg-gray-100 dark:bg-white/10 flex items-center justify-center text-deep-onyx dark:text-white hover:bg-deep-onyx hover:text-white dark:hover:bg-white/20 transition-all duration-150 cursor-pointer"
                title="Toggle dark mode" aria-label="Toggle dark mode">
                <span class="material-symbols-outlined text-[18px]" x-show="!$store.theme.dark"
                    style="font-size:18px;">dark_mode</span>
                <span class="material-symbols-outlined text-[18px]" x-show="$store.theme.dark"
                    style="font-size:18px; display:none;">light_mode</span>
            </button>
            <button @click="mobileOpen = !mobileOpen" type="button"
                class="w-9 h-9 rounded-full bg-gray-100 dark:bg-white/10 text-deep-onyx dark:text-white flex items-center justify-center transition-all cursor-pointer"
                aria-label="Toggle navigation menu">
                <span class="material-symbols-outlined text-[22px]" x-show="!mobileOpen"
                    style="font-size:22px;">menu</span>
                <span class="material-symbols-outlined text-[22px]" x-show="mobileOpen"
                    style="display:none; font-size:22px;">close</span>
            </button>
        </div>

        {{-- Mobile Dropdown Menu --}}
        <div x-show="mobileOpen" @click.away="mobileOpen = false" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
            class="nav-mobile-drawer md:hidden absolute top-full left-0 right-0 mt-3 rounded-3xl p-4 shadow-2xl z-50 font-figtree font-normal space-y-1"
            style="display: none;">

            <a href="{{ route('home') }}"
                class="flex items-center gap-3 text-[15px] font-medium px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('home') ? 'bg-electric-green/15 text-deep-onyx dark:text-electric-green font-semibold' : 'text-deep-onyx/80 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/5' }}">
                <span class="material-symbols-outlined text-electric-green text-[20px]">home</span>
                <span>Home</span>
            </a>

            <a href="{{ route('vacancies.index') }}"
                class="flex items-center gap-3 text-[15px] font-medium px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('vacancies.index') ? 'bg-electric-green/15 text-deep-onyx dark:text-electric-green font-semibold' : 'text-deep-onyx/80 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/5' }}">
                <span class="material-symbols-outlined text-electric-green text-[20px]">work</span>
                <span>Find Jobs</span>
            </a>

            <a href="{{ route('applications.track') }}"
                class="flex items-center gap-3 text-[15px] font-medium px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('applications.track') ? 'bg-electric-green/15 text-deep-onyx dark:text-electric-green font-semibold' : 'text-deep-onyx/80 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/5' }}">
                <span class="material-symbols-outlined text-electric-green text-[20px]">find_in_page</span>
                <span>Track Status</span>
            </a>

            <a href="{{ route('faq') }}"
                class="flex items-center gap-3 text-[15px] font-medium px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('faq') ? 'bg-electric-green/15 text-deep-onyx dark:text-electric-green font-semibold' : 'text-deep-onyx/80 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/5' }}">
                <span class="material-symbols-outlined text-electric-green text-[20px]">help</span>
                <span>FAQ</span>
            </a>

            <a href="{{ route('contact') }}"
                class="flex items-center gap-3 text-[15px] font-medium px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('contact') ? 'bg-electric-green/15 text-deep-onyx dark:text-electric-green font-semibold' : 'text-deep-onyx/80 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/5' }}">
                <span class="material-symbols-outlined text-electric-green text-[20px]">mail</span>
                <span>Contact</span>
            </a>

            <div class="pt-2 border-t border-gray-100 dark:border-white/10 mt-2">
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center justify-between bg-electric-green text-white rounded-full px-5 py-3 font-semibold text-[14.5px] transition-all shadow-md w-full border-2 border-transparent">
                        <span>Go to Dashboard</span>
                        <span
                            class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                            <span
                                class="material-symbols-outlined text-white text-[16px]">arrow_forward</span>
                        </span>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="flex items-center justify-between bg-electric-green text-white rounded-full px-5 py-3 font-semibold text-[14.5px] transition-all shadow-md w-full border-2 border-transparent">
                        <span>Staff / Admin Login</span>
                        <span
                            class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                            <span
                                class="material-symbols-outlined text-white text-[16px]">arrow_forward</span>
                        </span>
                    </a>
                @endauth
            </div>
        </div>
    </nav>
</header>