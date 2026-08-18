@extends('layouts.admin')
@section('title', 'School Overview')

@section('content')
    <div class="max-w-[1400px] mx-auto">

        {{-- Overview Page Header & Live System Clock --}}
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4" x-data="{
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
                    <h2 class="text-4xl font-extrabold text-[#111827] tracking-tight leading-none" style="font-size: 38px;">
                        School Overview</h2>

                    {{-- Live System Clock Pill --}}
                    <div class="bg-[#eef2f6] px-3.5 py-1.5 rounded-full flex items-center gap-2 border border-gray-200/80">
                        <span class="w-2.5 h-2.5 rounded-full shrink-0"
                            style="width: 10px; height: 10px; background-color: #22c55e; border-radius: 50%; display: inline-block;"></span>
                        <span class="text-[#334155] text-[13px] font-medium font-sans">
                            Live System Clock: <span
                                x-text="timeStr">{{ now()->timezone('Asia/Kolkata')->format('h:i:s A') }}</span>
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
                <a href="{{ route('schooladmin.vacancies.create') }}"
                    class="text-white font-bold text-xs px-6 py-3 rounded-full transition-all shadow-md flex items-center gap-2 active:scale-95 cursor-pointer"
                    style="background-color: #D7B56D;">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    <span>Post Vacancy</span>
                </a>
                <a href="{{ route('schooladmin.applications.index') }}"
                    class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-800 font-bold text-xs px-6 py-3 rounded-full transition-all shadow-2xs flex items-center gap-2 cursor-pointer">
                    <span class="material-symbols-outlined text-[18px] text-gray-500">description</span>
                    <span>Applications</span>
                </a>
            </div>
        </div>

        {{-- Stats Cards Grid (4 Columns) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

            {{-- Card 1: Open Vacancies --}}
            <div class="group p-4 border border-gray-100 flex flex-col gap-2 cursor-default
                                            transition-all duration-200 hover:shadow-lg hover:-translate-y-px"
                style="background: linear-gradient(135deg, #ffffff 0%, #e5eeff 100%); border-radius: 20px;">
                <div class="flex justify-between items-start">
                    <h3 class="text-xs text-gray-500 font-medium leading-tight">Open Vacancies</h3>
                    <div class="flex items-center justify-center text-blue-600 shrink-0"
                        style="width:32px;height:32px;border-radius:10px;background:rgba(219,234,254,0.7);">
                        <span class="material-symbols-outlined" style="font-size:17px;">work</span>
                    </div>
                </div>
                <span class="text-4xl font-bold text-gray-900 leading-none tracking-tight">{{ $stats['open_jobs'] }}</span>
                <div class="flex items-center gap-1.5 text-xs text-gray-500 mt-auto">
                    <span
                        class="bg-blue-50 text-blue-700 font-semibold px-2 py-0.5 rounded border border-blue-200 text-[11px]">
                        {{ $stats['closed_jobs'] }} Closed
                    </span>
                    <span class="text-gray-400 text-[11px]">Active recruitment</span>
                </div>
            </div>

            {{-- Card 2: Total Applications --}}
            <div class="group p-4 border border-gray-100 flex flex-col gap-2 cursor-default
                                            transition-all duration-200 hover:shadow-lg hover:-translate-y-px"
                style="background: linear-gradient(135deg, #ffffff 0%, #fdd88d 100%); border-radius: 20px;">
                <div class="flex justify-between items-start">
                    <h3 class="text-xs text-gray-500 font-medium leading-tight">Total Applications</h3>
                    <div class="flex items-center justify-center text-amber-600 shrink-0"
                        style="width:32px;height:32px;border-radius:10px;background:rgba(254,243,199,0.7);">
                        <span class="material-symbols-outlined" style="font-size:17px;">description</span>
                    </div>
                </div>
                <span
                    class="text-4xl font-bold text-gray-900 leading-none tracking-tight">{{ $stats['total_applications'] }}</span>
                <div class="flex items-center gap-1 bg-amber-50 text-amber-700 w-fit text-xs font-semibold border border-amber-200 mt-auto px-2 py-0.5"
                    style="border-radius:8px;">
                    <span class="material-symbols-outlined" style="font-size:12px;">trending_up</span>
                    +{{ $stats['today_applications'] }} today
                </div>
            </div>

            {{-- Card 3: Scheduled Interviews --}}
            <div class="group p-4 border border-gray-100 flex flex-col gap-2 cursor-default
                                            transition-all duration-200 hover:shadow-lg hover:-translate-y-px"
                style="background: linear-gradient(135deg, #ffffff 0%, #e5eeff 100%); border-radius: 20px;">
                <div class="flex justify-between items-start">
                    <h3 class="text-xs text-gray-500 font-medium leading-tight">Scheduled Interviews</h3>
                    <div class="flex items-center justify-center text-purple-600 shrink-0"
                        style="width:32px;height:32px;border-radius:10px;background:rgba(233,213,255,0.7);">
                        <span class="material-symbols-outlined" style="font-size:17px;">event</span>
                    </div>
                </div>
                <span
                    class="text-4xl font-bold text-gray-900 leading-none tracking-tight">{{ $stats['upcoming_interviews'] }}</span>
                <div class="text-xs text-gray-400 mt-auto">
                    Candidates assigned dates
                </div>
            </div>

            {{-- Card 4: Selected Candidates --}}
            <div class="group p-4 border border-gray-100 flex flex-col gap-2 cursor-default
                                            transition-all duration-200 hover:shadow-lg hover:-translate-y-px"
                style="background: linear-gradient(135deg, #ffffff 0%, #e5eeff 100%); border-radius: 20px;">
                <div class="flex justify-between items-start">
                    <h3 class="text-xs text-gray-500 font-medium leading-tight">Selected Candidates</h3>
                    <div class="flex items-center justify-center text-emerald-600 shrink-0"
                        style="width:32px;height:32px;border-radius:10px;background:rgba(209,250,229,0.7);">
                        <span class="material-symbols-outlined" style="font-size:17px;">how_to_reg</span>
                    </div>
                </div>
                <span
                    class="text-4xl font-bold text-gray-900 leading-none tracking-tight">{{ $stats['selected_candidates'] }}</span>
                <div class="text-xs text-gray-400 mt-auto">
                    {{ $stats['rejected_candidates'] }} rejected candidates
                </div>
            </div>

        </div>

        {{-- Main Content Grid (3 Columns) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Column 1 & 2 (Span 2) --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Active Vacancies List Card --}}
                <div class="bg-white shadow-sm border border-gray-100 p-6 sm:p-8" style="border-radius: 20px;">
                    <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div>
                                <h3 class="text-lg font-bold text-[#111827]">Active Job Vacancies</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Current open positions accepting candidate
                                    applications.</p>
                            </div>
                        </div>
                        <a href="{{ route('schooladmin.vacancies.create') }}"
                            class="text-xs font-bold border border-gray-200 text-gray-700 px-4 py-2 rounded-full hover:bg-gray-50 transition-colors flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">add</span>
                            <span>Post Vacancy</span>
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse($activeVacancies as $job)
                            <div
                                class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 hover:bg-gray-50/80 rounded-2xl transition-all border border-gray-100/80">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="min-w-0">
                                        <a href="{{ route('schooladmin.vacancies.edit', $job) }}"
                                            class="font-bold text-sm text-gray-900 hover:text-blue-600 truncate block transition-colors">{{ $job->title }}</a>
                                        <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-2">
                                            <span
                                                class="font-medium text-gray-600">{{ $job->department->name ?? 'General' }}</span>
                                            <span>&bull;</span>
                                            <span>{{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between sm:justify-end gap-4 shrink-0">
                                    <div class="text-right">
                                        <span
                                            class="block text-sm font-bold text-gray-900">{{ $job->applications_count }}</span>
                                        <span class="text-[10px] font-semibold text-gray-400">Applicants</span>
                                    </div>
                                    <a href="{{ route('schooladmin.applications.index', ['vacancy_id' => $job->id]) }}"
                                        class="bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold px-4 py-2 rounded-full transition-colors flex items-center gap-1">
                                        <span>View</span>
                                        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center text-xs text-gray-400">
                                No active vacancies. Post your first job vacancy to start receiving candidate applications.
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Pipeline Analytics Card --}}
                <div class="bg-white shadow-sm border border-gray-100 p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-6"
                    style="border-radius: 20px;">
                    <div>
                        <h3 class="text-base font-bold text-gray-900 mb-1">Vacancy Pipeline Status</h3>
                        <p class="text-xs text-gray-500 max-w-sm leading-relaxed">Ratio of candidate applications converted
                            to shortlisted and selected status.</p>
                    </div>
                    <div class="flex items-center gap-6">
                        <div
                            class="relative w-24 h-24 rounded-full border-4 border-emerald-500 flex flex-col items-center justify-center bg-emerald-50/50">
                            <span
                                class="text-2xl font-bold text-gray-900 leading-none">{{ $stats['total_applications'] > 0 ? round(($stats['selected_candidates'] / $stats['total_applications']) * 100) : 0 }}%</span>
                            <span class="text-[9px] text-emerald-800 font-bold uppercase mt-1">Selected</span>
                        </div>
                        <div class="space-y-2 text-xs font-medium">
                            <div class="flex items-center gap-2 text-gray-700">
                                <div class="w-3 h-3 rounded-full bg-emerald-500"></div> Selected Candidates
                            </div>
                            <div class="flex items-center gap-2 text-gray-700">
                                <div class="w-3 h-3 rounded-full bg-blue-500"></div> Interview Stage
                            </div>
                            <div class="flex items-center gap-2 text-gray-700">
                                <div class="w-3 h-3 rounded-full bg-amber-400"></div> Pending Review
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Column 3 (Span 1) --}}
            <div class="space-y-6">

                {{-- Upcoming Interviews Card --}}
                <div class="bg-white shadow-sm border border-gray-100 p-6" style="border-radius: 20px;">
                    <div class="flex justify-between items-center mb-6 pb-3 border-b border-gray-100">
                        <h3 class="text-base font-bold text-gray-900">Upcoming Interviews</h3>
                        <a href="{{ route('schooladmin.interviews.calendar') }}"
                            class="text-xs border border-gray-200 text-gray-600 px-3 py-1 rounded-full hover:bg-gray-50 transition-colors flex items-center gap-1 font-bold">
                            <span>Calendar</span>
                            <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                        </a>
                    </div>
                    <div class="space-y-3">
                        @forelse($upcomingInterviews as $interview)
                            <div class="p-3.5 bg-gray-50/80 rounded-2xl border border-gray-100 space-y-2">
                                <div class="flex items-start justify-between">
                                    <h4 class="text-xs font-bold text-gray-900">{{ $interview->application->full_name }}</h4>
                                    <span
                                        class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200 uppercase">{{ $interview->location_type }}</span>
                                </div>
                                <p class="text-[11px] text-gray-500 truncate font-medium">
                                    {{ $interview->application->vacancy->title }}
                                </p>
                                <div class="text-[11px] font-semibold text-blue-700 flex items-center gap-1 pt-1">
                                    <span>{{ $interview->scheduled_date->format('M d') }} &bull;
                                        {{ date('h:i A', strtotime($interview->scheduled_time)) }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-xs text-gray-400">No upcoming interviews scheduled.</div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection