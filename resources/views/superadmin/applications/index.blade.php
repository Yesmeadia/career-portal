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

                        <div class="relative min-w-[160px]">
                            <span
                                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"
                                style="font-size:16px;">category</span>
                            <select name="application_type"
                                class="w-full bg-white border border-gray-200 text-[13px] text-gray-700 pl-9 pr-8 py-2.5 appearance-none focus:outline-none focus:ring-2 transition-all cursor-pointer"
                                style="border-radius: 12px;">
                                <option value="">All Types</option>
                                <option value="vacancy" {{ ($filters['application_type'] ?? '') === 'vacancy' ? 'selected' : '' }}>Vacancy Applications</option>
                                <option value="general" {{ ($filters['application_type'] ?? '') === 'general' ? 'selected' : '' }}>General Applications</option>
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

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100" style="background-color: #f8fafc;">
                            <th class="px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Candidate</th>
                            <th class="px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">School / Campus</th>
                            <th class="px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Target Position</th>
                            <th class="px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Qualification / Exp</th>
                            <th class="px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Applied</th>
                            <th class="px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
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
                                        {{ Str::limit($app->school?->name ?? 'All Campuses (General)', 28) }}</p>
                                    @if($app->isTransferred())
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 mt-1" title="Transferred from {{ $app->transferredFromSchool?->name }}">
                                            <span class="material-symbols-outlined text-[12px]">swap_horiz</span> Transferred
                                        </span>
                                    @else
                                        <p class="text-[11px] text-gray-400 mt-0.5">{{ $app->school?->city ?? 'Central Pool' }}</p>
                                    @endif
                                </td>

                                <td class="px-5 py-3.5 border-b border-gray-50">
                                    @if($app->isGeneral())
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="material-symbols-outlined text-[12px]">stars</span> General App
                                        </span>
                                        <p class="text-[11px] text-gray-500 mt-1 font-medium truncate max-w-[180px]" title="{{ $app->preferred_position ?: 'Open Role' }}">
                                            {{ $app->preferred_position ?: ($app->preferred_department ?: 'Open Placement') }}
                                        </p>
                                    @else
                                        <p class="font-semibold text-[#111827]" style="font-size:13px;">
                                            {{ Str::limit($app->vacancy?->title ?? 'Position', 26) }}</p>
                                        <p class="text-[11px] text-gray-400 mt-0.5">
                                            {{ $app->vacancy?->department?->name ?? 'Teaching/Staff' }}</p>
                                    @endif
                                </td>

                                <td class="px-5 py-3.5 border-b border-gray-50">
                                    <p class="font-medium text-[#111827]" style="font-size:13px;">
                                        {{ $app->highest_qualification ?? 'N/A' }}</p>
                                    <p class="text-[11px] text-gray-400 mt-0.5">
                                        {{ $app->experience_years ?: 'Fresher' }}
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
                                </td>

                                <td class="px-5 py-3.5 border-b border-gray-50 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('superadmin.applications.show', $app) }}"
                                            class="inline-flex items-center gap-1 text-white font-bold px-3 py-1.5 transition-all hover:opacity-90 active:scale-95 shadow-sm"
                                            style="background-color: #21255E; border-radius: 999px; font-size: 11px;">
                                            <span>View</span>
                                            <span class="material-symbols-outlined" style="font-size:13px;">arrow_forward</span>
                                        </a>
                                        <a href="{{ route('superadmin.applications.show', $app) }}?transfer=1"
                                            class="inline-flex items-center gap-1 bg-indigo-50 border border-indigo-200 text-indigo-700 font-bold px-2.5 py-1.5 rounded-full hover:bg-indigo-100 transition-all text-[11px]" title="Transfer / Recommend to another campus">
                                            <span class="material-symbols-outlined text-[13px]">swap_horiz</span> Transfer
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-gray-400 text-[13px]">
                                    <span class="material-symbols-outlined text-4xl text-gray-300 block mb-2">inbox</span>
                                    No applications matching your filters were found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($applications->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                    <p class="text-[12px] text-gray-500">
                        Showing {{ $applications->firstItem() }} to {{ $applications->lastItem() }} of {{ $applications->total() }} results
                    </p>
                    <div>
                        {{ $applications->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection