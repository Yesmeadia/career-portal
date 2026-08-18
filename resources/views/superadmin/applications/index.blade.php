@extends('layouts.admin')

@section('title', 'Candidate Applications')

@section('content')
    <div class="max-w-[1400px] mx-auto space-y-6">

        {{-- Page Header & Actions --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-4xl font-extrabold text-[#111827] tracking-tight leading-none" style="font-size: 38px;">
                    Candidate Applications</h1>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('superadmin.applications.index') }}"
                    class="bg-white border border-gray-200 text-gray-600 px-4 py-2 rounded-full text-[13px] flex items-center gap-2 hover:bg-gray-50 transition-all shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">refresh</span> Refresh List
                </a>
                <a href="{{ route('superadmin.applications.index', array_merge(request()->all(), ['export' => 'csv'])) }}"
                    class="text-white px-5 py-2 rounded-full text-[13px] flex items-center gap-2 hover:opacity-90 transition-all shadow-md active:scale-95 font-bold"
                    style="background-color: #D7B56D;">
                    <span class="material-symbols-outlined text-[18px]">download</span> Export Report
                </a>
            </div>
        </div>

        {{-- Filters & Applications Register Card --}}
        <div class="bg-white shadow-sm border border-gray-100" style="border-radius: 20px; overflow: hidden;">

            {{-- Card Header: Title + Filter Toolbar --}}
            <div class="px-6 pt-5 pb-4 border-b border-gray-100"
                style="background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);">

                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div>
                            <h2 class="font-bold text-[#111827]" style="font-size:17px;">Applications Register</h2>
                            <p class="text-gray-400 text-[11px] mt-0.5">All candidate submissions across institutions</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-[12px] text-gray-500 font-medium">
                        Showing <span class="font-bold text-[#111827] mx-1">{{ $applications->count() }}</span> of
                        <span class="font-bold text-[#111827] mx-1">{{ $applications->total() }}</span> entries
                    </div>
                </div>

                {{-- Filter Toolbar --}}
                <form method="GET" action="{{ route('superadmin.applications.index') }}">
                    <div class="flex flex-wrap gap-3 items-center">

                        <div class="relative flex-1 min-w-[220px] max-w-xs">
                            <span
                                class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"
                                style="font-size:18px;">search</span>
                            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                                placeholder="Search name, email, ref no..."
                                class="w-full bg-white border border-gray-200 text-[13px] text-gray-700 placeholder-gray-400 pl-10 pr-4 py-2.5 focus:outline-none focus:ring-2 transition-all"
                                style="border-radius: 12px;">
                        </div>

                        <div class="relative min-w-[160px]">
                            <span
                                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"
                                style="font-size:16px;">school</span>
                            <select name="school_id"
                                class="w-full bg-white border border-gray-200 text-[13px] text-gray-700 pl-9 pr-8 py-2.5 appearance-none focus:outline-none focus:ring-2 transition-all cursor-pointer"
                                style="border-radius: 12px;">
                                <option value="">All Schools</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}" {{ ($filters['school_id'] ?? '') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                                @endforeach
                            </select>
                            <span
                                class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"
                                style="font-size:16px;">expand_more</span>
                        </div>

                        <div class="relative min-w-[150px]">
                            <span
                                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"
                                style="font-size:16px;">filter_list</span>
                            <select name="status"
                                class="w-full bg-white border border-gray-200 text-[13px] text-gray-700 pl-9 pr-8 py-2.5 appearance-none focus:outline-none focus:ring-2 transition-all cursor-pointer"
                                style="border-radius: 12px;">
                                <option value="">All Statuses</option>
                                @foreach($statusOptions as $val => $label)
                                    <option value="{{ $val }}" {{ ($filters['status'] ?? '') === $val ? 'selected' : '' }}>
                                        {{ $label }}</option>
                                @endforeach
                            </select>
                            <span
                                class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"
                                style="font-size:16px;">expand_more</span>
                        </div>

                        <div class="h-8 w-px bg-gray-200 hidden sm:block"></div>

                        <button type="submit"
                            class="flex items-center gap-2 text-white text-[13px] font-bold px-5 py-2.5 transition-all hover:opacity-90 active:scale-95 shadow-sm"
                            style="background-color: #21255E; border-radius: 12px;">
                            <span class="material-symbols-outlined" style="font-size:16px;">tune</span>
                            Apply Filter
                        </button>

                        @if(array_filter($filters))
                            <a href="{{ route('superadmin.applications.index') }}"
                                class="flex items-center gap-2 text-gray-500 text-[13px] font-semibold px-4 py-2.5 bg-gray-100 hover:bg-gray-200 transition-all"
                                style="border-radius: 12px;">
                                <span class="material-symbols-outlined" style="font-size:16px;">close</span>
                                Clear
                            </a>
                        @endif

                    </div>
                </form>
            </div>

            {{-- Applications Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left" style="border-collapse: separate; border-spacing: 0;">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th
                                class="px-5 py-3 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                                Candidate</th>
                            <th
                                class="px-5 py-3 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                                School</th>
                            <th
                                class="px-5 py-3 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                                Position Applied</th>
                            <th
                                class="px-5 py-3 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                                Qualification</th>
                            <th
                                class="px-5 py-3 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                                Status</th>
                            <th
                                class="px-5 py-3 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                                Date Applied</th>
                            <th
                                class="px-5 py-3 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100 text-right">
                                View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $index => $app)
                            @php
                                $badgeClass = match ($app->status) {
                                    'hired', 'selected' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'shortlisted', 'interview_scheduled', 'interview_completed' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    'under_review', 'submitted', 'new' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'rejected' => 'bg-red-50 text-red-700 border-red-200',
                                    default => 'bg-gray-100 text-gray-600 border-gray-200',
                                };
                                $dotColor = match ($app->status) {
                                    'hired', 'selected' => '#22c55e',
                                    'shortlisted', 'interview_scheduled', 'interview_completed' => '#a855f7',
                                    'under_review', 'submitted', 'new' => '#f59e0b',
                                    'rejected' => '#ef4444',
                                    default => '#94a3b8',
                                };
                                $rowBg = $index % 2 === 0 ? '#ffffff' : '#fafbfc';
                            @endphp
                            <tr style="background: {{ $rowBg }}; transition: background 0.15s;"
                                onmouseover="this.style.background='#f0f4ff'" onmouseout="this.style.background='{{ $rowBg }}'">

                                <td class="px-5 py-3.5 border-b border-gray-50">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $app->photo_url }}" class="object-cover border border-gray-200 shrink-0"
                                            style="width:38px;height:38px;border-radius:10px;" alt="{{ $app->full_name }}"
                                            onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                                        <div class="min-w-0">
                                            <a href="{{ route('superadmin.applications.show', $app) }}"
                                                class="font-bold text-[#111827] hover:text-[#21255E] transition-colors block truncate"
                                                style="font-size:13px;max-width:160px;">{{ $app->full_name }}</a>
                                            <span class="text-[11px] text-gray-400 font-mono">{{ $app->reference_no }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-3.5 border-b border-gray-50">
                                    <p class="font-semibold text-[#111827]" style="font-size:13px;">
                                        {{ Str::limit($app->school?->name ?? 'N/A', 28) }}</p>
                                    <p class="text-[11px] text-gray-400 mt-0.5">{{ $app->school?->city ?? '—' }}</p>
                                </td>

                                <td class="px-5 py-3.5 border-b border-gray-50">
                                    <p class="font-semibold text-[#111827]" style="font-size:13px;">
                                        {{ Str::limit($app->vacancy?->title ?? 'General Application', 26) }}</p>
                                    <p class="text-[11px] text-gray-400 mt-0.5">
                                        {{ $app->vacancy?->subject ?? 'Teaching/Staff' }}</p>
                                </td>

                                <td class="px-5 py-3.5 border-b border-gray-50">
                                    <p class="font-medium text-[#111827]" style="font-size:13px;">
                                        {{ $app->highest_qualification ?? 'N/A' }}</p>
                                    <p class="text-[11px] text-gray-400 mt-0.5">
                                        @if($app->total_experience_years)
                                            <span class="inline-flex items-center gap-1">
                                                <span class="material-symbols-outlined"
                                                    style="font-size:11px;color:#94a3b8;">work_history</span>
                                                {{ $app->total_experience_years }} yrs exp
                                            </span>
                                        @else
                                            <span class="text-blue-400 font-medium">Fresh Graduate</span>
                                        @endif
                                    </p>
                                </td>

                                <td class="px-5 py-3.5 border-b border-gray-50">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 text-[11px] font-bold border {{ $badgeClass }}"
                                        style="border-radius:999px;">
                                        <span
                                            style="width:7px;height:7px;border-radius:50%;background-color:{{ $dotColor }};display:inline-block;flex-shrink:0;"></span>
                                        {{ ucfirst(str_replace('_', ' ', $app->status)) }}
                                    </span>
                                </td>

                                <td class="px-5 py-3.5 border-b border-gray-50">
                                    <p class="text-[13px] font-medium text-gray-700">{{ $app->created_at->format('d M Y') }}</p>
                                    <p class="text-[11px] text-gray-400">{{ $app->created_at->diffForHumans() }}</p>
                                </td>

                                <td class="px-5 py-3.5 border-b border-gray-50 text-right">
                                    <a href="{{ route('superadmin.applications.show', $app) }}"
                                        class="inline-flex items-center gap-1.5 text-[12px] font-bold text-[#21255E] hover:text-white transition-all px-3 py-1.5 border border-[#21255E]/20 hover:bg-[#21255E] hover:border-[#21255E]"
                                        style="border-radius:10px;" title="View Application">
                                        <span class="material-symbols-outlined" style="font-size:15px;">open_in_new</span>
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="flex items-center justify-center text-gray-300"
                                            style="width:56px;height:56px;border-radius:18px;background:#f1f5f9;">
                                            <span class="material-symbols-outlined" style="font-size:28px;">folder_open</span>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-700 text-sm">No applications found</p>
                                            <p class="text-xs text-gray-400 mt-1">No candidate submissions match your active
                                                filters.</p>
                                        </div>
                                        <a href="{{ route('superadmin.applications.index') }}"
                                            class="text-[12px] font-bold text-[#21255E] hover:underline mt-1">Clear all filters
                                            →</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            @if($applications->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between gap-4"
                    style="background:#fafbfc;">
                    <p class="text-[12px] text-gray-400">
                        Page <span class="font-bold text-gray-700">{{ $applications->currentPage() }}</span> of
                        <span class="font-bold text-gray-700">{{ $applications->lastPage() }}</span>
                    </p>
                    <div>{{ $applications->links() }}</div>
                </div>
            @endif

        </div>

    </div>
@endsection