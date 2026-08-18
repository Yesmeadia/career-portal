@extends('layouts.admin')

@section('title', 'Institutional Reports & Analytics')

@push('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
    <div class="max-w-[1400px] mx-auto space-y-6">

        {{-- Overview Page Header & Live System Clock --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4"
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
                    <h1 class="text-4xl font-extrabold text-[#111827] tracking-tight leading-none" style="font-size: 38px;">
                        Reports &amp; Analytics</h1>
                </div>

                <p class="text-gray-500 mt-2 flex items-center gap-2 text-sm font-medium">
                    <span class="material-symbols-outlined text-[18px] text-gray-400">insights</span>
                    Comprehensive recruitment metrics, candidate pipeline breakdown, and campus comparisons.
                </p>
            </div>

            {{-- Top Right Actions --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('superadmin.reports.export-applications', request()->query()) }}"
                    class="text-white font-bold text-xs px-6 py-3 rounded-full transition-all shadow-md flex items-center gap-2 active:scale-95 cursor-pointer hover:opacity-90"
                    style="background-color: #D7B56D;">
                    <span class="material-symbols-outlined text-[18px]">download</span>
                    <span>Export Filtered CSV</span>
                </a>
            </div>
        </div>

        {{-- Filter & Control Bar --}}
        <div class="bg-white shadow-sm border border-gray-100 p-5" style="border-radius: 20px;">
            <form action="{{ route('superadmin.reports.index') }}" method="GET" class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-3 flex-1">
                    {{-- School Filter --}}
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Campus</label>
                        <select name="school_id"
                            class="px-4 py-2 rounded-full border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-xs text-gray-700 bg-gray-50/50">
                            <option value="">All Campuses</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ ($filters['school_id'] ?? '') == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Category Filter --}}
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Discipline</label>
                        <select name="category_id"
                            class="px-4 py-2 rounded-full border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-xs text-gray-700 bg-gray-50/50">
                            <option value="">All Disciplines</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ ($filters['category_id'] ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status Filter --}}
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Stage / Status</label>
                        <select name="status"
                            class="px-4 py-2 rounded-full border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-xs text-gray-700 bg-gray-50/50">
                            <option value="">All Stages</option>
                            <option value="submitted" {{ ($filters['status'] ?? '') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="under_review" {{ ($filters['status'] ?? '') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                            <option value="shortlisted" {{ ($filters['status'] ?? '') === 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                            <option value="interview_scheduled" {{ ($filters['status'] ?? '') === 'interview_scheduled' ? 'selected' : '' }}>Interview Scheduled</option>
                            <option value="selected" {{ ($filters['status'] ?? '') === 'selected' ? 'selected' : '' }}>Selected</option>
                            <option value="hired" {{ ($filters['status'] ?? '') === 'hired' ? 'selected' : '' }}>Hired</option>
                            <option value="rejected" {{ ($filters['status'] ?? '') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    {{-- Date From --}}
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Date From</label>
                        <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"
                            class="px-3 py-1.5 rounded-full border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-xs text-gray-700 bg-gray-50/50">
                    </div>

                    {{-- Date To --}}
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Date To</label>
                        <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"
                            class="px-3 py-1.5 rounded-full border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-xs text-gray-700 bg-gray-50/50">
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="bg-[#21255E] hover:bg-[#1a1d4b] text-white px-5 py-2.5 rounded-full text-xs font-semibold transition-all shadow-xs flex items-center gap-1.5 cursor-pointer">
                            <span class="material-symbols-outlined text-[16px]">filter_alt</span> Apply Filters
                        </button>
                    </div>

                    @if(array_filter($filters))
                        <div class="pt-4">
                            <a href="{{ route('superadmin.reports.index') }}"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2.5 rounded-full text-xs font-semibold transition-colors flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">close</span> Reset
                            </a>
                        </div>
                    @endif
                </div>
            </form>
        </div>

        {{-- Stats Summary Cards (4 Columns) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Card 1: Total Applications --}}
            <div class="group p-5 border border-gray-100 flex flex-col gap-2 cursor-default transition-all duration-200 hover:shadow-lg hover:-translate-y-px"
                style="background: linear-gradient(135deg, #ffffff 0%, #e5eeff 100%); border-radius: 20px;">
                <div class="flex justify-between items-start">
                    <h3 class="text-xs text-gray-500 font-medium">Total Applications</h3>
                    <div class="flex items-center justify-center text-blue-600 shrink-0"
                        style="width:32px;height:32px;border-radius:10px;background:rgba(219,234,254,0.7);">
                        <span class="material-symbols-outlined" style="font-size:17px;">description</span>
                    </div>
                </div>
                <span class="text-3xl font-bold text-gray-900 leading-none tracking-tight">{{ number_format($stats['total_applications']) }}</span>
                <div class="flex items-center gap-1 bg-blue-50 text-blue-700 w-fit text-xs font-semibold border border-blue-200 mt-auto px-2 py-0.5"
                    style="border-radius:8px;">
                    <span class="material-symbols-outlined" style="font-size:12px;">trending_up</span>
                    {{ $stats['today_applications'] }} received today
                </div>
            </div>

            {{-- Card 2: Open Positions --}}
            <div class="group p-5 border border-gray-100 flex flex-col gap-2 cursor-default transition-all duration-200 hover:shadow-lg hover:-translate-y-px"
                style="background: linear-gradient(135deg, #ffffff 0%, #fdd88d 100%); border-radius: 20px;">
                <div class="flex justify-between items-start">
                    <h3 class="text-xs text-gray-500 font-medium">Active Vacancies</h3>
                    <div class="flex items-center justify-center text-amber-600 shrink-0"
                        style="width:32px;height:32px;border-radius:10px;background:rgba(254,243,199,0.7);">
                        <span class="material-symbols-outlined" style="font-size:17px;">work</span>
                    </div>
                </div>
                <span class="text-3xl font-bold text-gray-900 leading-none tracking-tight">{{ number_format($stats['open_vacancies']) }}</span>
                <div class="flex items-center gap-1 bg-amber-50 text-amber-700 w-fit text-xs font-semibold border border-amber-200 mt-auto px-2 py-0.5"
                    style="border-radius:8px;">
                    <span class="material-symbols-outlined" style="font-size:12px;">checklist</span>
                    Across all {{ $stats['total_schools'] }} campuses
                </div>
            </div>

            {{-- Card 3: Selected & Hired --}}
            <div class="group p-5 border border-gray-100 flex flex-col gap-2 cursor-default transition-all duration-200 hover:shadow-lg hover:-translate-y-px"
                style="background: linear-gradient(135deg, #ffffff 0%, #dcfce7 100%); border-radius: 20px;">
                <div class="flex justify-between items-start">
                    <h3 class="text-xs text-gray-500 font-medium">Hired Candidates</h3>
                    <div class="flex items-center justify-center text-emerald-600 shrink-0"
                        style="width:32px;height:32px;border-radius:10px;background:rgba(209,250,229,0.7);">
                        <span class="material-symbols-outlined" style="font-size:17px;">verified</span>
                    </div>
                </div>
                <span class="text-3xl font-bold text-gray-900 leading-none tracking-tight">{{ number_format($stats['hired_candidates']) }}</span>
                <div class="flex items-center gap-1 bg-emerald-50 text-emerald-700 w-fit text-xs font-semibold border border-emerald-200 mt-auto px-2 py-0.5"
                    style="border-radius:8px;">
                    <span class="material-symbols-outlined" style="font-size:12px;">person_add</span>
                    Appointments finalized
                </div>
            </div>

            {{-- Card 4: Shortlisted / In Process --}}
            <div class="group p-5 border border-gray-100 flex flex-col gap-2 cursor-default transition-all duration-200 hover:shadow-lg hover:-translate-y-px"
                style="background: linear-gradient(135deg, #ffffff 0%, #f3e8ff 100%); border-radius: 20px;">
                <div class="flex justify-between items-start">
                    <h3 class="text-xs text-gray-500 font-medium">Active Pipeline</h3>
                    <div class="flex items-center justify-center text-purple-600 shrink-0"
                        style="width:32px;height:32px;border-radius:10px;background:rgba(233,213,255,0.7);">
                        <span class="material-symbols-outlined" style="font-size:17px;">group</span>
                    </div>
                </div>
                <span class="text-3xl font-bold text-gray-900 leading-none tracking-tight">{{ number_format($stats['shortlisted'] + $stats['interviews_scheduled']) }}</span>
                <div class="flex items-center gap-1 bg-purple-50 text-purple-700 w-fit text-xs font-semibold border border-purple-200 mt-auto px-2 py-0.5"
                    style="border-radius:8px;">
                    <span class="material-symbols-outlined" style="font-size:12px;">event</span>
                    {{ $stats['interviews_scheduled'] }} interviews scheduled
                </div>
            </div>

        </div>

        {{-- Visual Charts Section (2 Columns) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- Left: Monthly Trend Area Chart (7 Cols) --}}
            <div class="lg:col-span-7 bg-white shadow-sm border border-gray-100 p-6 flex flex-col justify-between"
                style="border-radius: 20px;">
                <div>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-4 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#21255E] flex items-center justify-center font-bold shrink-0 border border-blue-100 shadow-2xs">
                                <span class="material-symbols-outlined text-[22px]">trending_up</span>
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-[#111827]">Monthly Application Trends</h3>
                                <p class="text-xs text-gray-400 mt-0.5">Recruitment intake & candidate movement over the last 6 months.</p>
                            </div>
                        </div>

                        {{-- Chart View Switcher --}}
                        <div class="flex items-center gap-1.5 bg-gray-100 p-1 rounded-full text-xs font-bold border border-gray-200 self-start sm:self-auto">
                            <button type="button" id="chartTypeLineBtn"
                                onclick="switchTrendChartType('line')"
                                class="px-3.5 py-1 rounded-full bg-[#21255E] text-white shadow-2xs transition-all cursor-pointer flex items-center gap-1">
                                <span class="material-symbols-outlined text-[15px]">show_chart</span>
                                <span>Area Curve</span>
                            </button>
                            <button type="button" id="chartTypeBarBtn"
                                onclick="switchTrendChartType('bar')"
                                class="px-3.5 py-1 rounded-full text-gray-600 hover:text-gray-900 transition-all cursor-pointer flex items-center gap-1">
                                <span class="material-symbols-outlined text-[15px]">bar_chart</span>
                                <span>Bar Chart</span>
                            </button>
                        </div>
                    </div>

                    {{-- Metrics Legend Badges --}}
                    <div class="flex flex-wrap items-center gap-2.5 mb-4 px-1">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50/80 border border-blue-200 text-[#21255E] text-[11px] font-bold">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#21255E]"></span>
                            <span>Total Applications ({{ $stats['total_applications'] }})</span>
                        </div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50/80 border border-amber-200 text-amber-800 text-[11px] font-bold">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#D7B56D]"></span>
                            <span>Shortlisted & Pipeline</span>
                        </div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50/80 border border-emerald-200 text-emerald-800 text-[11px] font-bold">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#10B981]"></span>
                            <span>Hired / Selected</span>
                        </div>
                    </div>

                    <div class="relative h-72 w-full">
                        <canvas id="monthlyTrendChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Right: Status Pipeline Donut Chart (5 Cols) --}}
            <div class="lg:col-span-5 bg-white shadow-sm border border-gray-100 p-6 flex flex-col justify-between"
                style="border-radius: 20px;">
                <div>
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                        <div>
                            <h3 class="text-base font-bold text-[#111827]">Application Pipeline</h3>
                            <p class="text-xs text-gray-400 mt-0.5">Current candidate distribution by stage.</p>
                        </div>
                        <span class="material-symbols-outlined text-gray-400">pie_chart</span>
                    </div>

                    <div class="relative h-64 w-full flex items-center justify-center">
                        <canvas id="pipelineStatusChart"></canvas>
                    </div>
                </div>
            </div>

        </div>

        {{-- Campus Comparison Breakdown Table --}}
        <div class="bg-white shadow-sm border border-gray-100 overflow-hidden" style="border-radius: 20px;">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold shrink-0">
                        <span class="material-symbols-outlined text-[22px]">location_city</span>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-[#111827]">Campus Recruitment Summary</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Key hiring performance statistics across institutions.</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50/70 border-b border-gray-100 text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Campus / School</th>
                            <th class="px-6 py-4 text-center">Open Jobs</th>
                            <th class="px-6 py-4 text-center">Total Applicants</th>
                            <th class="px-6 py-4 text-center">Shortlisted</th>
                            <th class="px-6 py-4 text-center">Hired</th>
                            <th class="px-6 py-4 text-center">Interviews</th>
                            <th class="px-6 py-4 text-right">View Applications</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @forelse($campusReports as $campus)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold text-xs shrink-0 border border-blue-100">
                                            <span class="material-symbols-outlined text-[18px]">school</span>
                                        </div>
                                        <div>
                                            <p class="font-bold text-[#111827] text-[14px]">{{ $campus->name }}</p>
                                            <p class="text-[11px] text-gray-400">{{ $campus->city ?? 'Poonch' }}, {{ $campus->state ?? 'J&K' }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center font-bold text-gray-800">
                                    <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                        {{ $campus->open_vacancies_count }} Active
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center font-bold text-gray-900 text-sm">
                                    {{ $campus->applications_count }}
                                </td>

                                <td class="px-6 py-4 text-center font-semibold text-purple-700">
                                    {{ $campus->shortlisted_count }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-bold border border-emerald-200">
                                        {{ $campus->hired_count }} Hired
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center font-semibold text-gray-700">
                                    {{ $campus->interviews_count }}
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('superadmin.applications.index', ['school_id' => $campus->id]) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-gray-50 border border-gray-200 hover:bg-[#21255E] hover:text-white transition-all text-xs font-semibold text-gray-600">
                                        <span>View Applicants</span>
                                        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-400">
                                    No campuses found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Discipline / Category Breakdown Table --}}
        <div class="bg-white shadow-sm border border-gray-100 overflow-hidden" style="border-radius: 20px;">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold shrink-0">
                        <span class="material-symbols-outlined text-[22px]">category</span>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-[#111827]">Discipline / Category Performance</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Vacancies and candidate applications categorized by department disciplines.</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50/70 border-b border-gray-100 text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Discipline / Category</th>
                            <th class="px-6 py-4 text-center">Total Vacancies</th>
                            <th class="px-6 py-4 text-center">Total Applications</th>
                            <th class="px-6 py-4 text-right">View Vacancies</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @forelse($categoryReports as $cat)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-bold text-[#111827] text-[14px]">{{ $cat->name }}</p>
                                        @if($cat->description)
                                            <p class="text-[11px] text-gray-400 mt-0.5">{{ $cat->description }}</p>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center font-bold text-gray-900 text-sm">
                                    {{ $cat->vacancies_count }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-blue-50 text-blue-700 font-bold text-xs border border-blue-200">
                                        {{ $cat->applications_count }} Candidates
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('superadmin.vacancies.index', ['category_id' => $cat->id]) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-gray-50 border border-gray-200 hover:bg-[#21255E] hover:text-white transition-all text-xs font-semibold text-gray-600">
                                        <span>View Jobs</span>
                                        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-400">
                                    No category data available.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- Charts Initialization Script --}}
    @push('scripts')
    <script>
        let monthlyTrendChartInstance = null;

        function switchTrendChartType(type) {
            const lineBtn = document.getElementById('chartTypeLineBtn');
            const barBtn = document.getElementById('chartTypeBarBtn');

            if (type === 'line') {
                lineBtn.className = 'px-3.5 py-1 rounded-full bg-[#21255E] text-white shadow-2xs transition-all cursor-pointer flex items-center gap-1';
                barBtn.className = 'px-3.5 py-1 rounded-full text-gray-600 hover:text-gray-900 transition-all cursor-pointer flex items-center gap-1';
            } else {
                barBtn.className = 'px-3.5 py-1 rounded-full bg-[#21255E] text-white shadow-2xs transition-all cursor-pointer flex items-center gap-1';
                lineBtn.className = 'px-3.5 py-1 rounded-full text-gray-600 hover:text-gray-900 transition-all cursor-pointer flex items-center gap-1';
            }

            if (monthlyTrendChartInstance) {
                monthlyTrendChartInstance.config.type = type;
                monthlyTrendChartInstance.data.datasets.forEach(ds => {
                    if (type === 'bar') {
                        ds.borderRadius = 8;
                        ds.borderSkipped = false;
                        ds.fill = false;
                    } else {
                        ds.fill = (ds.label === 'Total Applications');
                        ds.tension = 0.38;
                    }
                });
                monthlyTrendChartInstance.update();
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // 1. Monthly Trend Area Chart
            const trendCanvas = document.getElementById('monthlyTrendChart');
            const trendCtx = trendCanvas.getContext('2d');

            const trendLabels = {!! json_encode($monthlyTrend->pluck('month_name')) !!};
            const trendTotal = {!! json_encode($monthlyTrend->pluck('total_count')) !!};
            const trendShortlisted = {!! json_encode($monthlyTrend->pluck('shortlisted_count')) !!};
            const trendHired = {!! json_encode($monthlyTrend->pluck('hired_count')) !!};

            // Create Smooth Gradient for Total Applications Fill
            const gradientTotal = trendCtx.createLinearGradient(0, 0, 0, 260);
            gradientTotal.addColorStop(0, 'rgba(33, 37, 94, 0.22)');
            gradientTotal.addColorStop(0.7, 'rgba(33, 37, 94, 0.04)');
            gradientTotal.addColorStop(1, 'rgba(33, 37, 94, 0.00)');

            monthlyTrendChartInstance = new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: trendLabels,
                    datasets: [
                        {
                            label: 'Total Applications',
                            data: trendTotal,
                            borderColor: '#21255E',
                            backgroundColor: gradientTotal,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.38,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#21255E',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                        },
                        {
                            label: 'Shortlisted & Pipeline',
                            data: trendShortlisted,
                            borderColor: '#D7B56D',
                            backgroundColor: 'rgba(215, 181, 109, 0.2)',
                            borderWidth: 2.5,
                            borderDash: [4, 4],
                            fill: false,
                            tension: 0.38,
                            pointRadius: 3.5,
                            pointHoverRadius: 5,
                            pointBackgroundColor: '#D7B56D',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                        },
                        {
                            label: 'Hired / Selected',
                            data: trendHired,
                            borderColor: '#10B981',
                            backgroundColor: 'rgba(16, 185, 129, 0.2)',
                            borderWidth: 2,
                            fill: false,
                            tension: 0.38,
                            pointRadius: 3,
                            pointHoverRadius: 5,
                            pointBackgroundColor: '#10B981',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#111827',
                            padding: 12,
                            cornerRadius: 12,
                            titleFont: { size: 13, weight: 'bold' },
                            bodyFont: { size: 12 },
                            usePointStyle: true,
                            boxPadding: 6,
                            callbacks: {
                                label: function(context) {
                                    return ' ' + context.dataset.label + ': ' + context.parsed.y;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.04)',
                                drawBorder: false
                            },
                            ticks: {
                                precision: 0,
                                font: { size: 11, weight: '500' },
                                color: '#6B7280'
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { size: 11, weight: '500' },
                                color: '#6B7280'
                            }
                        }
                    }
                }
            });

            // 2. Status Pipeline Donut Chart
            const pipelineCtx = document.getElementById('pipelineStatusChart').getContext('2d');
            const statusLabels = {!! json_encode(array_keys($statusCounts)) !!};
            const statusData = {!! json_encode(array_values($statusCounts)) !!};

            new Chart(pipelineCtx, {
                type: 'doughnut',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusData,
                        backgroundColor: [
                            '#94A3B8', // Submitted (Slate)
                            '#3B82F6', // Under Review (Blue)
                            '#A855F7', // Shortlisted (Purple)
                            '#F59E0B', // Interview (Amber)
                            '#10B981', // Selected / Hired (Emerald)
                            '#EF4444', // Rejected (Red)
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 12,
                                font: { size: 11, weight: '500' }
                            }
                        },
                        tooltip: {
                            backgroundColor: '#111827',
                            padding: 10,
                            cornerRadius: 10,
                            bodyFont: { size: 12 }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
@endsection
