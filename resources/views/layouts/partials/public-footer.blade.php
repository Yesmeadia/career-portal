{{-- CareerFlow Footer --}}
<footer
    class="bg-ghost-gray dark:!bg-[#141517] w-full py-lg border-t border-black/10 dark:!border-white/10 mt-auto text-deep-onyx dark:!text-stark-white transition-colors">
    <div
        class="flex flex-col md:flex-row justify-between items-center px-margin-mobile md:px-gutter gap-md max-w-container-max mx-auto">
        <div class="flex flex-col items-center md:items-start gap-4">
            <a href="{{ route('home') }}" class="flex items-center gap-3 text-deep-onyx dark:text-stark-white">
                <img src="{{ asset('logo.png') }}" alt="{{ $siteSettings['site_name'] ?? 'YES India Foundation' }}"
                    class="h-8 w-auto object-contain flex-shrink-0" style="max-height: 32px; height: 32px; width: auto;" height="32">
                <span class="text-base font-semibold tracking-tight text-deep-onyx dark:text-stark-white">{{ $siteSettings['site_name'] ?? 'YES India Foundation' }}</span>
            </a>
            <!-- Theme Switcher -->
            <div class="flex p-1 bg-white/60 dark:bg-white/10 rounded-full border border-black/10 dark:border-white/10 shadow-xs"
                x-data>
                <button @click="$store.theme.set(false)"
                    :class="!$store.theme.dark ? 'bg-electric-green text-deep-onyx font-bold' : 'text-secondary dark:text-secondary-fixed-dim hover:text-deep-onyx dark:hover:text-stark-white'"
                    class="px-4 py-1.5 rounded-full font-label-bold text-xs flex items-center gap-1.5 shadow-sm transition-all">
                    <span class="material-symbols-outlined text-[16px]">light_mode</span> Light
                </button>
                <button @click="$store.theme.set(true)"
                    :class="$store.theme.dark ? 'bg-electric-green text-deep-onyx font-bold' : 'text-secondary dark:text-secondary-fixed-dim hover:text-deep-onyx dark:hover:text-stark-white'"
                    class="px-4 py-1.5 rounded-full font-label-bold text-xs flex items-center gap-1.5 transition-all">
                    <span class="material-symbols-outlined text-[16px]">dark_mode</span> Night
                </button>
            </div>
        </div>
        <div class="flex flex-col items-center md:items-end gap-4">
            <div
                class="flex flex-wrap justify-center md:justify-end gap-4 text-secondary dark:text-secondary-fixed-dim font-label-sm text-label-sm">
                <a class="hover:text-emerald-600 dark:hover:text-electric-green transition-colors opacity-90 hover:opacity-100"
                    href="{{ route('privacy') }}">Privacy Policy</a>
                <a class="hover:text-emerald-600 dark:hover:text-electric-green transition-colors opacity-90 hover:opacity-100"
                    href="{{ route('terms') }}">Terms of Service</a>
                <a class="hover:text-emerald-600 dark:hover:text-electric-green transition-colors opacity-90 hover:opacity-100"
                    href="{{ route('faq') }}">FAQ</a>
                <a class="hover:text-emerald-600 dark:hover:text-emerald-600 dark:hover:text-electric-green transition-colors opacity-90 hover:opacity-100"
                    href="{{ route('contact') }}">Contact Us</a>
                <a class="hover:text-emerald-600 dark:hover:text-electric-green transition-colors opacity-90 hover:opacity-100"
                    href="{{ route('applications.track') }}">Track Application</a>
            </div>
            <p
                class="text-secondary dark:text-secondary-fixed-dim font-label-sm text-label-sm text-center md:text-right">
                &copy;
                {{ date('Y') }} {{ $siteSettings['site_name'] ?? 'YES India Foundation' }}. All rights reserved.
            </p>

            <p
                class="text-secondary dark:text-secondary-fixed-dim font-label-sm text-label-sm text-center md:text-right">
                Designed and Developed by <a href="https://cyberduce.com"
                    class=" hover:text-emerald-600 dark:hover:text-electric-green transition-colors">Cyberduce
                    Technologies
                </a>
            </p>
        </div>
    </div>
</footer>