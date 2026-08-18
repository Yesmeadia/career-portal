<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title')@yield('title') —
    {{ $siteSettings['site_name'] ?? config('app.name', 'School Careers') }}@else{{ $siteSettings['site_name'] ?? config('app.name', 'School Careers') }}@endif
    </title>
    <meta name="description"
        content="@yield('meta_description', 'Official career portal for teaching, leadership and administrative recruitment across premier schools.')">

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    {{-- Dark Mode Initializer --}}
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    {{-- Fonts & Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Anton&family=Figtree:wght@400;500;600;700&family=Nunito+Sans:opsz,wght@6..12,600;6..12,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">


    <script>
        // Register Alpine global theme store before Alpine boots
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                dark: document.documentElement.classList.contains('dark'),
                toggle() {
                    this.dark = !this.dark;
                    this._apply();
                },
                set(isDark) {
                    this.dark = isDark;
                    this._apply();
                },
                _apply() {
                    if (this.dark) {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                    }
                }
            });
        });
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Lucide Icons CDN --}}
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .lift-hover {
            transition: all 0.2s ease-in-out;
        }

        .lift-hover:hover {
            transform: translate(-4px, -4px);
            box-shadow: 4px 4px 0px 0px #171717;
        }

        .card-lift:hover {
            transform: translate(-4px, -4px);
            box-shadow: 8px 8px 0px 0px #171717;
        }

        /* Checked Background Grid Pattern */
        .bg-checked-pattern {
            background-image: linear-gradient(to right, rgba(0, 0, 0, 0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(0, 0, 0, 0.04) 1px, transparent 1px);
            background-size: 64px 64px;
        }

        .dark .bg-checked-pattern {
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
            background-size: 64px 64px;
        }

        /* Hide scrollbar for horizontal scroll area */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    @stack('head')
</head>

<body
    class="bg-stark-white dark:bg-deep-onyx text-deep-onyx dark:text-stark-white font-sans min-h-screen flex flex-col antialiased transition-colors duration-200">

    {{-- Public Header Navbar (Hidden if @section('hideHeader', true) is set) --}}
    @unless(View::hasSection('hideHeader'))
        @include('layouts.partials.public-header')
    @endunless

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
            class="fixed top-20 right-5 z-50 flex items-center gap-3 bg-emerald-600 text-white px-5 py-3 rounded-xl shadow-lg border border-emerald-500/30 text-sm">
            <i data-lucide="check-circle-2" class="w-4 h-4 flex-shrink-0"></i>
            <span>{{ session('success') }}</span>
            <button @click="show = false" class="ml-2 opacity-70 hover:opacity-100">✕</button>
        </div>
    @endif
    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 6000)"
            class="fixed top-20 right-5 z-50 flex items-center gap-3 bg-red-600 text-white px-5 py-3 rounded-xl shadow-lg border border-red-500/30 text-sm">
            <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0"></i>
            <span>{{ session('error') }}</span>
            <button @click="show = false" class="ml-2 opacity-70 hover:opacity-100">✕</button>
        </div>
    @endif

    {{-- Page Content --}}
    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- Public Footer --}}
    @include('layouts.partials.public-footer')

    @include('layouts.partials.custom-dialog')
    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
        document.addEventListener('alpine:initialized', function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>

</html>