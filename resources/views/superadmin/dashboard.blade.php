@extends('layouts.admin')

@section('title', 'Super Admin Overview')

@section('content')
    <div class="max-w-[1400px] mx-auto">

        {{-- Overview Page Header & Live System Clock --}}
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4"
             x-data="{
                 timeStr: '{{ now()->timezone('Asia/Kolkata')->format('h:i:s A') }}',
                 dateStr: '{{ now()->timezone('Asia/Kolkata')->format('l, M d, Y | h:i A') }}',
                 init() {
                     const update = () => {
                         const now = new Date();
                         const optionsTime = { timeZone: 'Asia/Kolkata', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
                         const optionsShortTime = { timeZone: 'Asia/Kolkata', hour: '2-digit', minute: '2-digit', hour12: true };
                         const optionsDate = { timeZone: 'Asia/Kolkata', weekday: 'long', month: 'short', day: 'numeric', year: 'numeric' };
                         
                         this.timeStr = new Intl.DateTimeFormat('en-US', optionsTime).format(now);
                         const d = new Intl.DateTimeFormat('en-US', optionsDate).format(now);
                         const tShort = new Intl.DateTimeFormat('en-US', optionsShortTime).format(now);
                         this.dateStr = `${d} | ${tShort}`;
                     };
                     update();
                     setInterval(update, 1000);
                 }
             }">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-4xl font-extrabold text-[#111827] tracking-tight leading-none" style="font-size: 38px;">Overview</h2>

                    {{-- Live System Clock Pill --}}
                    <div class="bg-[#eef2f6] px-3.5 py-1.5 rounded-full flex items-center gap-2 border border-gray-200/80">
                        <span class="w-2.5 h-2.5 rounded-full shrink-0" style="width: 10px; height: 10px; background-color: #22c55e; border-radius: 50%; display: inline-block;"></span>
                        <span class="text-[#334155] text-[13px] font-medium font-sans">
                            Live System Clock: <span x-text="timeStr">{{ now()->timezone('Asia/Kolkata')->format('h:i:s A') }}</span>
                        </span>
                    </div>
                </div>

                <p class="text-gray-500 mt-2.5 flex items-center gap-2 text-sm font-medium">
                    <span class="material-symbols-outlined text-[18px] text-gray-400">schedule</span>
                    <span x-text="dateStr">{{ now()->timezone('Asia/Kolkata')->format('l, M d, Y | h:i A') }}</span>
                </p>
            </div>

            {{-- Top Right Actions --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('superadmin.applications.index') }}"
                    class="bg-white border border-gray-200 text-gray-600 px-4 py-2 rounded-full font-label-md text-[13px] flex items-center gap-2 hover:bg-gray-50 transition-all shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">tune</span> Applications Filter
                </a>
                <a href="{{ route('superadmin.reports.index') }}"
                    class="text-white px-5 py-2 rounded-full font-label-md text-[13px] flex items-center gap-2 hover:opacity-90 transition-all shadow-md active:scale-95 font-bold"
                    style="background-color: #D7B56D;">
                    <span class="material-symbols-outlined text-[18px]">bar_chart</span> Reports &amp; Analytics
                </a>
            </div>
        </div>

        {{-- Stats Grid (4 Cards with Gradients) --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

            {{-- Card 1: Total Schools --}}
            <div class="group p-4 border border-gray-100 flex flex-col gap-2 cursor-default
                            transition-all duration-200 hover:shadow-lg hover:-translate-y-px"
                style="background: linear-gradient(135deg, #ffffff 0%, #e5eeff 100%); border-radius: 20px;">
                <div class="flex justify-between items-start">
                    <h3 class="text-xs text-gray-500 font-medium leading-tight">Total Schools</h3>
                    <div class="flex items-center justify-center text-blue-600 shrink-0"
                        style="width:32px;height:32px;border-radius:10px;background:rgba(219,234,254,0.7);">
                        <span class="material-symbols-outlined" style="font-size:17px;">school</span>
                    </div>
                </div>
                <span
                    class="text-3xl font-bold text-gray-900 leading-none tracking-tight">{{ $stats['total_schools'] ?? 0 }}</span>
                <div class="flex items-center gap-1 bg-green-50 text-green-700 w-fit text-xs font-semibold border border-green-200 mt-auto px-2 py-0.5"
                    style="border-radius:8px;">
                    <span class="material-symbols-outlined" style="font-size:12px;">arrow_upward</span>
                    {{ $recentSchools->count() }} registered
                </div>
            </div>

            {{-- Card 2: Published Vacancies --}}
            <div class="group p-4 border border-gray-100 flex flex-col gap-2 cursor-default
                            transition-all duration-200 hover:shadow-lg hover:-translate-y-px"
                style="background: linear-gradient(135deg, #ffffff 0%, #fdd88d 100%); border-radius: 20px;">
                <div class="flex justify-between items-start">
                    <h3 class="text-xs text-gray-500 font-medium leading-tight">Published Vacancies</h3>
                    <div class="flex items-center justify-center text-purple-600 shrink-0"
                        style="width:32px;height:32px;border-radius:10px;background:rgba(233,213,255,0.7);">
                        <span class="material-symbols-outlined" style="font-size:17px;">work</span>
                    </div>
                </div>
                <span
                    class="text-3xl font-bold text-gray-900 leading-none tracking-tight">{{ $stats['published_vacancies'] ?? 0 }}</span>
                <div class="flex items-center gap-1 bg-gray-100 text-gray-600 w-fit text-xs font-semibold border border-gray-200 mt-auto px-2 py-0.5"
                    style="border-radius:8px;">
                    Active Listings
                </div>
            </div>

            {{-- Card 3: Total Applications --}}
            <div class="group p-4 border border-gray-100 flex flex-col gap-2 cursor-default
                            transition-all duration-200 hover:shadow-lg hover:-translate-y-px"
                style="background: linear-gradient(135deg, #ffffff 0%, #e5eeff 100%); border-radius: 20px;">
                <div class="flex justify-between items-start">
                    <h3 class="text-xs text-gray-500 font-medium leading-tight">Total Applications</h3>
                    <div class="flex items-center justify-center text-amber-600 shrink-0"
                        style="width:32px;height:32px;border-radius:10px;background:rgba(254,243,199,0.7);">
                        <span class="material-symbols-outlined" style="font-size:17px;">assignment</span>
                    </div>
                </div>
                <span
                    class="text-3xl font-bold text-gray-900 leading-none tracking-tight">{{ number_format($stats['total_applications'] ?? 0) }}</span>
                <div class="flex items-center gap-2">
                    <div style="display:flex;margin-left:0;">
                        @foreach($recentApplications->take(3) as $app)
                            <img alt="{{ $app->full_name }}" src="{{ $app->photo_url }}" onerror="this.style.display='none'"
                                style="width:22px;height:22px;border-radius:6px;border:2px solid white;object-fit:cover;margin-left:-4px;box-shadow:0 1px 2px rgba(0,0,0,0.1);">
                        @endforeach
                    </div>
                    <span class="text-xs text-gray-400 font-medium">+{{ max(0, ($stats['total_applications'] ?? 0) - 3) }}
                        more</span>
                </div>
                <div class="flex items-center gap-1 bg-green-50 text-green-700 w-fit text-xs font-semibold border border-green-200 mt-auto px-2 py-0.5"
                    style="border-radius:8px;">
                    <span class="material-symbols-outlined" style="font-size:12px;">arrow_upward</span>
                    Active Portal
                </div>
            </div>

            {{-- Card 4: School Admins --}}
            <div class="group p-4 border border-gray-100 flex flex-col gap-2 cursor-default
                            transition-all duration-200 hover:shadow-lg hover:-translate-y-px"
                style="background: linear-gradient(135deg, #ffffff 0%, #fdd88d 100%); border-radius: 20px;">
                <div class="flex justify-between items-start">
                    <h3 class="text-xs text-gray-500 font-medium leading-tight">School Admins</h3>
                    <div class="flex items-center justify-center text-rose-600 shrink-0"
                        style="width:32px;height:32px;border-radius:10px;background:rgba(255,228,230,0.7);">
                        <span class="material-symbols-outlined" style="font-size:17px;">group</span>
                    </div>
                </div>
                <span
                    class="text-3xl font-bold text-gray-900 leading-none tracking-tight">{{ $schoolAdminsCount ?? 0 }}</span>
                <div class="flex items-center gap-1 bg-rose-50 text-rose-700 w-fit text-xs font-semibold border border-rose-200 mt-auto px-2 py-0.5"
                    style="border-radius:8px;">
                    Tenant Admins
                </div>
            </div>

        </div>

        {{-- Main Activity & Candidates Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5">
            {{-- Activity Chart --}}
            <div class="lg:col-span-2 bg-white shadow-sm border border-gray-100 p-5 flex flex-col"
                style="border-radius: 20px;">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-headline-sm text-[18px] font-bold text-[#111827]">Recruitment Activity</h3>
                    <div class="flex gap-2">
                        <span
                            class="px-3 py-1 bg-emerald-50 text-[#21255E] border border-[#21255E]/10 rounded-lg text-[12px] font-bold">Last
                            7 Days</span>
                    </div>
                </div>

                <div class="flex-1 flex items-end justify-between gap-2 h-[220px] mt-4 relative pt-4">
                    <div
                        class="absolute inset-0 flex flex-col justify-between text-[10px] text-gray-400 pb-6 pointer-events-none">
                        <span class="border-b border-gray-100 w-full pb-1">High</span>
                        <span class="border-b border-gray-100 w-full pb-1">Medium</span>
                        <span class="border-b border-gray-100 w-full pb-1">Low</span>
                        <span>0</span>
                    </div>

                    <div class="w-full h-full flex items-end justify-between px-6 gap-3 z-10 pb-6">
                        @foreach($weeklyActivity as $item)
                            @php
                                $count = $item['count'] ?? 0;
                                $height = max(12, $item['height_pct']);
                                $isMax = $count > 0 && $item['height_pct'] >= 70;
                                $bgStyle = $isMax
                                    ? 'background: linear-gradient(180deg, #313783 0%, #21255e 100%);'
                                    : 'background: linear-gradient(180deg, #dbeafe 0%, #93c5fd 100%);';
                            @endphp
                            <div class="chart-bar-container w-full relative group cursor-pointer flex flex-col justify-end shadow-xs"
                                 style="height: {{ $height }}%; {{ $bgStyle }} border-radius: 12px 12px 4px 4px;">
                                
                                {{-- Count Tooltip Badge on Touch / Hover --}}
                                <div class="chart-tooltip absolute -top-9 left-1/2 opacity-0 transition-all duration-200 pointer-events-none z-30"
                                     style="transform: translateX(-50%) translateY(0px);">
                                    <div class="bg-[#111827] text-white text-[11px] font-bold py-1 px-2.5 rounded-lg shadow-xl whitespace-nowrap flex items-center gap-1">
                                        <span>{{ $count }}</span>
                                        <span class="text-gray-400 font-normal">apps</span>
                                    </div>
                                    <div class="w-2 h-2 bg-[#111827] rotate-45 mx-auto -mt-1"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="absolute bottom-0 w-full flex justify-between px-6 text-[11px] text-gray-400 font-medium">
                        @foreach($weeklyActivity as $item)
                            <span
                                class="text-center flex-1 {{ $item['is_today'] ? 'font-bold text-[#21255e]' : '' }}">{{ $item['day'] }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Candidate Submissions --}}
            <div class="bg-white shadow-sm border border-gray-100 p-5 flex flex-col" style="border-radius: 20px;">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-headline-sm text-[18px] font-bold text-[#111827]">Recent Submissions</h3>
                    <a href="{{ route('superadmin.applications.index') }}" class="text-gray-400 hover:text-[#111827]"
                        title="View Applications">
                        <span class="material-symbols-outlined text-[20px]">more_horiz</span>
                    </a>
                </div>

                <div class="flex flex-col gap-4">
                    @forelse($recentApplications as $app)
                        <a href="{{ route('superadmin.applications.show', $app) }}"
                            class="flex items-center gap-3 p-2.5 hover:bg-gray-50 rounded-xl transition-colors cursor-pointer border border-transparent hover:border-gray-100">
                            <img src="{{ $app->photo_url }}" alt="{{ $app->full_name }}"
                                class="w-10 h-10 rounded-full object-cover border border-gray-200 shrink-0 shadow-xs">
                            <div class="flex-1 min-w-0">
                                <p class="text-[13px] font-bold text-[#111827] truncate">{{ $app->full_name }}</p>
                                <p class="text-[11px] text-gray-500 truncate mt-0.5">{{ $app->vacancy->title }} &bull;
                                    {{ $app->school->name }}
                                </p>
                            </div>
                            <span
                                class="text-[11px] font-mono text-gray-400 shrink-0">{{ $app->created_at->diffForHumans(null, true) }}</span>
                        </a>
                    @empty
                        <div class="py-8 text-center text-xs text-gray-400">No applications submitted yet.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Bottom Row: Registered Schools & Activity Log (same layout as row above) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
            {{-- Registered Schools (narrower col) --}}
            <div class="bg-white shadow-sm border border-gray-100 p-5" style="border-radius: 20px;">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-headline-sm text-[18px] font-bold text-[#111827]">Registered Schools</h3>
                    <a href="{{ route('superadmin.schools.index') }}"
                        class="text-[13px] font-semibold text-blue-600 hover:text-blue-700">View All</a>
                </div>

                <div class="flex flex-col gap-3">
                    @forelse($recentSchools as $school)
                        <div
                            class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100/70 transition-colors">
                            <div class="flex items-center gap-4 min-w-0">
                                <div class="min-w-0">
                                    <h4 class="text-[14px] font-bold text-[#111827] truncate">{{ $school->name }}</h4>
                                    <p class="text-[12px] text-gray-500 truncate">{{ $school->city ?? 'Poonch' }},
                                        {{ $school->state ?? 'J&K' }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 shrink-0">
                                <span
                                    class="px-2.5 py-1 rounded-md text-[11px] font-bold {{ $school->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ ucfirst($school->status) }}
                                </span>
                                <a href="{{ route('superadmin.schools.edit', $school) }}"
                                    class="text-gray-400 hover:text-[#111827]">
                                    <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="py-6 text-center text-xs text-gray-400">No registered schools yet.</div>
                    @endforelse
                </div>
            </div>

            {{-- Activity Log (wider col) --}}
            <div class="lg:col-span-2 bg-white shadow-sm border border-gray-100 p-6" style="border-radius: 20px;">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-[18px] font-bold text-[#111827]">Activity Log</h3>
                    <a href="{{ route('superadmin.audit-logs.index') }}"
                       class="text-[13px] font-semibold text-slate-400 hover:text-slate-700 transition-colors">View Full Log</a>
                </div>

                <div class="relative border-l border-gray-200/80 ml-2 space-y-6 pb-2">
                    @forelse($recentLogs as $log)
                        @php
                            $logType = strtolower($log->log_name ?? 'default');
                            $hexColor = match(true) {
                                str_contains($logType, 'create') || str_contains($logType, 'school') || str_contains($logType, 'approve') => '#3b82f6',
                                str_contains($logType, 'export') || str_contains($logType, 'complete') || str_contains($logType, 'success') => '#22c55e',
                                str_contains($logType, 'delete') || str_contains($logType, 'fail') || str_contains($logType, 'error') => '#ef4444',
                                default => '#cbd5e1',
                            };
                            $timeFormatted = match(true) {
                                $log->created_at->isToday() => $log->created_at->format('g:i A'),
                                $log->created_at->isYesterday() => 'Yesterday, ' . $log->created_at->format('g:i A'),
                                default => $log->created_at->format('M d, g:i A'),
                            };
                        @endphp
                        <div style="position: relative; padding-left: 24px;">
                            <div style="position: absolute; left: -6px; top: 4px; width: 12px; height: 12px; background-color: {{ $hexColor }}; border-radius: 50%; display: inline-block;"></div>
                            <h4 class="text-[14px] font-semibold text-[#1e293b] leading-tight">
                                {{ Str::headline($log->log_name ?? 'System Event') }}
                            </h4>
                            <p class="text-[13px] text-gray-500 mt-0.5 leading-normal">
                                {{ $log->description ?? 'System activity recorded.' }}
                            </p>
                            <p class="text-[12px] text-gray-400 font-medium mt-1">
                                {{ $timeFormatted }}
                            </p>
                        </div>
                    @empty
                        <div style="position: relative; padding-left: 24px;">
                            <div style="position: absolute; left: -6px; top: 4px; width: 12px; height: 12px; background-color: #3b82f6; border-radius: 50%; display: inline-block;"></div>
                            <h4 class="text-[14px] font-semibold text-[#1e293b]">System Portal Initialized</h4>
                            <p class="text-[13px] text-gray-500 mt-0.5">Audit logging system active.</p>
                            <p class="text-[12px] text-gray-400 font-medium mt-1">Just now</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

@endsection