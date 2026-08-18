<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ $siteSettings['site_name'] ?? 'School Career Portal' }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <script>(function(){ document.documentElement.classList.remove('dark'); })();</script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')

    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Hanken Grotesk', sans-serif; margin: 0; }

        /* Sidebar width transition */
        #admin-sidebar {
            transition: width 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            will-change: width;
            overflow: hidden;
            flex-shrink: 0;
        }
        #admin-sidebar.expanded { width: 260px; }
        #admin-sidebar.collapsed { width: 72px; }

        /* Nav label fade */
        .nav-label {
            transition: opacity 0.15s ease;
            white-space: nowrap;
            overflow: hidden;
        }
        #admin-sidebar.collapsed .nav-label { opacity: 0; width: 0; }
        #admin-sidebar.expanded .nav-label { opacity: 1; }

        /* Brand text fade */
        .brand-text {
            transition: opacity 0.15s ease;
            overflow: hidden;
            white-space: nowrap;
        }
        #admin-sidebar.collapsed .brand-text { opacity: 0; width: 0; }
        #admin-sidebar.expanded .brand-text { opacity: 1; }

        /* System section label */
        #admin-sidebar.collapsed .section-label { display: none; }

        /* Post Job button text */
        #admin-sidebar.collapsed .btn-label { display: none; }

        /* Tooltip for collapsed nav items */
        .nav-tooltip {
            position: absolute;
            left: calc(100% + 12px);
            top: 50%;
            transform: translateY(-50%);
            background: #111827;
            color: white;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 6px;
            white-space: nowrap;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.15s;
            z-index: 999;
        }
        .nav-tooltip::before {
            content: '';
            position: absolute;
            right: 100%;
            top: 50%;
            transform: translateY(-50%);
            border: 5px solid transparent;
            border-right-color: #111827;
        }
        /* Show tooltip only when sidebar is collapsed */
        #admin-sidebar.collapsed a:hover .nav-tooltip,
        #admin-sidebar.collapsed button:hover .nav-tooltip {
            opacity: 1;
        }

        /* Material icon fix */
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            flex-shrink: 0;
        }

        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }

        /* Hide scrollbar / slider in sidebar nav */
        #admin-sidebar *, #admin-sidebar nav, #admin-sidebar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        #admin-sidebar *::-webkit-scrollbar,
        #admin-sidebar nav::-webkit-scrollbar,
        #admin-sidebar::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }

        /* Stat card hover lift effect */
        main div.grid > div.group {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        main div.grid > div.group:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -6px rgba(0,0,0,0.10), 0 4px 8px -4px rgba(0,0,0,0.06);
        }

        /* Recruitment Chart Bar Motion & Tooltip */
        .chart-bar-container {
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.25s ease, filter 0.25s ease;
        }
        .chart-bar-container:hover, .chart-bar-container:active {
            transform: translateY(-5px) scaleX(1.05);
            box-shadow: 0 10px 20px -4px rgba(33, 37, 94, 0.35);
            filter: brightness(1.1);
        }
        .chart-bar-container:hover .chart-tooltip,
        .chart-bar-container:active .chart-tooltip {
            opacity: 1 !important;
            transform: translateX(-50%) translateY(-6px) !important;
            pointer-events: auto;
        }
    </style>
</head>

<body class="flex h-screen overflow-hidden bg-[#f3f4f6]"
      x-data="{ 
          sidebarOpen: true,
          mobileSidebarOpen: false,
          init() {
              this.$nextTick(() => {
                  const el = document.getElementById('admin-sidebar');
                  if (el) el.classList.toggle('expanded', this.sidebarOpen);
                  if (el) el.classList.toggle('collapsed', !this.sidebarOpen);
              });
              this.$watch('sidebarOpen', (val) => {
                  const el = document.getElementById('admin-sidebar');
                  if (!el) return;
                  if (val) { el.classList.remove('collapsed'); el.classList.add('expanded'); }
                  else { el.classList.remove('expanded'); el.classList.add('collapsed'); }
              });
          }
      }"
      @keydown.escape.window="mobileSidebarOpen = false">

    {{-- Mobile Backdrop --}}
    <div x-show="mobileSidebarOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileSidebarOpen = false"
         class="fixed inset-0 bg-black/40 z-40 lg:hidden">
    </div>

    {{-- ===== SIDEBAR ===== --}}
    <div id="admin-sidebar" class="expanded bg-white h-full flex flex-col border-r border-gray-200 z-50 relative"
         :class="{ 'fixed inset-y-0 left-0 shadow-2xl': mobileSidebarOpen, 'hidden lg:flex': !mobileSidebarOpen && false }">

        @auth
            @if(auth()->user()->hasRole('Super Admin'))
                @include('layouts.partials.super-admin-nav')
            @elseif(auth()->user()->hasRole('School Admin'))
                @include('layouts.partials.school-admin-nav')
            @endif
        @endauth
    </div>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-[#f3f4f6]">

        {{-- Top Header Bar --}}
        <header class="flex items-center justify-between bg-[#f3f4f6] px-6 py-4 shrink-0 gap-4">
            <div class="flex items-center gap-3 flex-1 max-w-md">
                {{-- Sidebar Toggle Button (Desktop & Mobile) --}}
                <button
                    @click="sidebarOpen = !sidebarOpen; if (window.innerWidth < 1024) mobileSidebarOpen = !mobileSidebarOpen"
                    class="w-10 h-10 bg-white border border-gray-200/80 flex items-center justify-center text-gray-600 hover:text-[#21255e] hover:bg-gray-50 transition-all shrink-0 shadow-2xs cursor-pointer"
                    style="border-radius: 14px;"
                    title="Toggle Sidebar">
                    <span class="material-symbols-outlined text-[20px]">menu</span>
                </button>

                {{-- Search Bar --}}
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-[18px] pointer-events-none">search</span>
                    <input type="text"
                           placeholder="Try searching..."
                           class="w-full bg-white border border-gray-200/80 pl-10 pr-4 py-2.5 text-[13px] text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#21255e]/10 focus:border-gray-300 shadow-2xs transition-all"
                           style="border-radius: 14px;">
                </div>
            </div>

            {{-- Right Actions --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" target="_blank" title="Public Site"
                   class="w-10 h-10 bg-white border border-gray-200/80 flex items-center justify-center text-gray-500 hover:text-[#21255e] transition-all shadow-2xs"
                   style="border-radius: 14px;">
                    <span class="material-symbols-outlined text-[19px]">language</span>
                </a>

                @auth
                @if(auth()->user()->hasRole('Super Admin'))
                <a href="{{ route('superadmin.contact-messages.index') }}" title="Notifications"
                   class="relative w-10 h-10 bg-white border border-gray-200/80 flex items-center justify-center text-gray-500 hover:text-[#21255e] transition-all shadow-2xs"
                   style="border-radius: 14px;">
                    <span class="material-symbols-outlined text-[19px]">notifications</span>
                    @if(\App\Models\ContactMessage::unread()->count() > 0)
                        <span class="absolute top-2.5 right-2.5 w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                    @endif
                </a>
                @endif
                @endauth

                {{-- User Profile Card --}}
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-3 bg-white border border-gray-200/80 px-4 py-2 shadow-2xs hover:bg-gray-50/80 transition-all cursor-pointer"
                   style="border-radius: 14px;">
                    <div class="hidden sm:block text-right">
                        <p class="text-[13px] font-bold text-[#111827] leading-tight">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-gray-400 font-medium leading-tight mt-0.5">{{ auth()->user()->roles->first()?->name ?? 'Super Admin' }}</p>
                    </div>
                    <img alt="{{ auth()->user()->name }}"
                         src="{{ auth()->user()->avatar_url }}"
                         class="w-8 h-8 object-cover border border-gray-100 shrink-0"
                         style="border-radius: 10px;">
                </a>
            </div>
        </header>

        {{-- Toast Notifications --}}
        @if(session('success'))
        <div class="px-6 pt-3">
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
                 class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-[13px] px-4 py-2.5 rounded-xl flex items-center justify-between">
                <div class="flex items-center gap-2 font-medium">
                    <span class="material-symbols-outlined text-emerald-600 text-[18px]">check_circle</span>
                    {{ session('success') }}
                </div>
                <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 ml-3">
                    <span class="material-symbols-outlined text-[16px]">close</span>
                </button>
            </div>
        </div>
        @endif
        @if(session('error'))
        <div class="px-6 pt-3">
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)" x-transition
                 class="bg-red-50 border border-red-200 text-red-700 text-[13px] px-4 py-2.5 rounded-xl flex items-center justify-between">
                <div class="flex items-center gap-2 font-medium">
                    <span class="material-symbols-outlined text-red-600 text-[18px]">error</span>
                    {{ session('error') }}
                </div>
                <button @click="show = false" class="text-red-400 hover:text-red-600 ml-3">
                    <span class="material-symbols-outlined text-[16px]">close</span>
                </button>
            </div>
        </div>
        @endif

        {{-- Main Scrollable Canvas --}}
        <main class="flex-1 overflow-y-auto px-6 py-6 flex flex-col">
            <div class="flex-1">
                @yield('content')
            </div>
            @include('layouts.partials.admin-footer')
        </main>
    </div>

    @include('layouts.partials.custom-dialog')
    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => { if (window.lucide) lucide.createIcons(); });
    </script>
</body>
</html>
