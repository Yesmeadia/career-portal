{{-- ===== SUPER ADMIN SIDEBAR NAV ===== --}}

{{-- Brand Logo Header --}}
<div class="flex items-center h-16 px-4 border-b border-gray-200 gap-3 shrink-0">
    <img src="{{ asset('logo.png') }}" alt="Logo" class="w-9 h-9 object-contain rounded-xl shrink-0">
    <div class="brand-text min-w-0">
        <h1 class="text-[14px] font-bold text-[#111827] leading-tight truncate">Career Portal</h1>
        <p class="text-[11px] text-gray-400">Super Admin</p>
    </div>
</div>

@php
    $navItems = [
        ['route' => 'superadmin.dashboard',         'label' => 'Dashboard',        'icon' => 'dashboard'],
        ['route' => 'superadmin.schools.index',      'label' => 'Schools',          'icon' => 'school'],
        ['route' => 'superadmin.departments.index',  'label' => 'Departments',      'icon' => 'domain'],
        ['route' => 'superadmin.vacancies.index',    'label' => 'Manage Vacancies', 'icon' => 'work'],
        ['route' => 'superadmin.applications.index', 'label' => 'Applications',     'icon' => 'assignment'],
        ['route' => 'superadmin.reports.index',      'label' => 'Reports & Analytics', 'icon' => 'bar_chart'],
        ['route' => 'superadmin.contact-messages.index', 'label' => 'Contact Inbox',   'icon' => 'inbox',
            'badge' => \App\Models\ContactMessage::unread()->count()],
        ['route' => 'superadmin.global-classes.index', 'label' => 'Global Classes',   'icon' => 'layers'],
        ['route' => 'superadmin.job-categories.index', 'label' => 'Job Categories',   'icon' => 'category'],
    ];
    $systemItems = [
        ['route' => 'superadmin.cms.index',        'label' => 'Site Settings', 'icon' => 'settings'],
        ['route' => 'superadmin.audit-logs.index', 'label' => 'Audit Log',    'icon' => 'history'],
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
            @if(isset($item['badge']) && $item['badge'] > 0)
                <span class="nav-label bg-red-500 text-white text-[10px] px-1.5 py-0.5 rounded-full font-bold leading-none">{{ $item['badge'] }}</span>
            @endif
            {{-- Collapsed tooltip --}}
            <span class="nav-tooltip">{{ $item['label'] }}</span>
        </a>
    @endforeach

    {{-- System Section --}}
    <div class="mt-4 pt-4 border-t border-gray-100">
        <p class="section-label px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">System</p>
        @foreach($systemItems as $item)
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
    <a href="{{ route('vacancies.index') }}" target="_blank"
       class="group relative flex items-center justify-center gap-2 w-full h-11 text-white rounded-xl font-semibold text-[14px] transition-colors shadow-sm hover:opacity-90"
       style="background-color: #D7B56D;">
        <span class="material-symbols-outlined text-[20px] shrink-0">add</span>
        <span class="btn-label nav-label">Post Job</span>
        <span class="nav-tooltip">Post Job</span>
    </a>
</div>

{{-- Logout --}}
<div class="px-3 pb-4 border-t border-gray-100 pt-3 shrink-0">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
                class="group relative flex items-center gap-3 px-3 h-11 w-full rounded-xl text-gray-500 hover:bg-red-50 hover:text-red-600 transition-all font-medium text-[14px]">
            <span class="material-symbols-outlined text-[22px] shrink-0">logout</span>
            <span class="nav-label">Logout</span>
            <span class="nav-tooltip">Logout</span>
        </button>
    </form>
</div>