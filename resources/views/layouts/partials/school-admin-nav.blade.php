{{-- ===== SCHOOL ADMIN SIDEBAR NAV ===== --}}

{{-- Brand Logo Header --}}
<div class="flex items-center h-16 px-4 border-b border-gray-200 gap-3 shrink-0">
    <img src="{{ asset('logo.png') }}" alt="Logo" class="w-9 h-9 object-contain rounded-xl shrink-0">
    <div class="brand-text min-w-0">
        <h1 class="text-[14px] font-bold text-[#111827] leading-tight truncate">{{ auth()->user()->school->name ?? $siteSettings['site_name'] ?? 'Career Portal' }}</h1>
        <p class="text-[11px] text-gray-400">School Admin</p>
    </div>
</div>

@php
    $navItems = [
        ['route' => 'schooladmin.dashboard',          'label' => 'Dashboard',      'icon' => 'dashboard'],
        ['route' => 'schooladmin.vacancies.index',    'label' => 'Vacancies',      'icon' => 'work'],
        ['route' => 'schooladmin.applications.index', 'label' => 'Applications',   'icon' => 'assignment'],
        ['route' => 'schooladmin.interviews.index',   'label' => 'Interviews',     'icon' => 'event'],
    ];
    $managementItems = [
        ['route' => 'schooladmin.reports.index',      'label' => 'Reports',        'icon' => 'bar_chart'],
        ['route' => 'profile.edit',                   'label' => 'Profile',        'icon' => 'person'],
    ];
@endphp

{{-- Nav Items --}}
<nav class="flex-1 overflow-y-auto overflow-x-hidden px-3 py-4 flex flex-col gap-0.5">

    @foreach($navItems as $item)
        @php $isActive = request()->routeIs($item['route'] . '*'); @endphp
        <a href="{{ route($item['route']) }}"
           class="group relative flex items-center gap-3 px-3 h-11 rounded-xl transition-all duration-150 font-medium text-[14px]
                  {{ $isActive
                     ? 'bg-[#21255E] text-white shadow-sm'
                     : 'text-gray-600 hover:bg-gray-100 hover:text-[#21255E]' }}"
           title="{{ $item['label'] }}">
            <span class="material-symbols-outlined text-[22px] shrink-0
                         {{ $isActive ? 'text-white' : 'text-gray-500 group-hover:text-[#21255E]' }}">
                {{ $item['icon'] }}
            </span>
            <span class="nav-label flex-1">{{ $item['label'] }}</span>
            @if(isset($item['badge']) && $item['badge'])
                <span class="nav-label bg-[#21255E] text-white text-[10px] px-1.5 py-0.5 rounded-full font-bold leading-none">{{ $item['badge'] }}</span>
            @endif
            {{-- Collapsed tooltip --}}
            <span class="nav-tooltip">{{ $item['label'] }}</span>
        </a>
    @endforeach

    {{-- Management Section --}}
    <div class="mt-4 pt-4 border-t border-gray-100">
        <p class="section-label px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Management</p>
        @foreach($managementItems as $item)
            @php $isActive = request()->routeIs($item['route'] . '*'); @endphp
            <a href="{{ route($item['route']) }}"
               class="group relative flex items-center gap-3 px-3 h-11 rounded-xl transition-all duration-150 font-medium text-[14px]
                      {{ $isActive
                         ? 'bg-[#21255E] text-white shadow-sm'
                         : 'text-gray-600 hover:bg-gray-100 hover:text-[#21255E]' }}"
               title="{{ $item['label'] }}">
                <span class="material-symbols-outlined text-[22px] shrink-0
                             {{ $isActive ? 'text-white' : 'text-gray-500 group-hover:text-[#21255E]' }}">
                    {{ $item['icon'] }}
                </span>
                <span class="nav-label flex-1">{{ $item['label'] }}</span>
                {{-- Collapsed tooltip --}}
                <span class="nav-tooltip">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>
</nav>

{{-- Post Job Button --}}
<div class="px-3 pb-3 shrink-0">
    <a href="{{ route('schooladmin.vacancies.create') }}"
       class="group relative flex items-center justify-center gap-2 w-full h-11 text-white rounded-xl font-semibold text-[14px] transition-colors shadow-sm hover:opacity-90"
       style="background-color: #D7B56D;">
        <span class="material-symbols-outlined text-[20px] shrink-0">add</span>
        <span class="btn-label nav-label">Post Vacancy</span>
        <span class="nav-tooltip">Post Vacancy</span>
    </a>
</div>

{{-- Logout --}}
<div class="px-3 pb-4 border-t border-gray-100 pt-3 shrink-0">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
                class="group relative flex items-center gap-3 px-3 h-11 w-full rounded-xl text-gray-500 hover:bg-red-50 hover:text-red-600 transition-all font-medium text-[14px] cursor-pointer">
            <span class="material-symbols-outlined text-[22px] shrink-0">logout</span>
            <span class="nav-label">Logout</span>
            <span class="nav-tooltip">Logout</span>
        </button>
    </form>
</div>
