@extends('layouts.app')

@section('title', 'Browse Job Vacancies')
@section('meta_description', 'Search and filter job vacancies across all schools by keyword, department, class, location, and more.')

@section('content')

    {{-- ===== HERO HEADER ===== --}}
    <section
        class="w-full bg-stark-white dark:bg-deep-onyx bg-checked-pattern border-b border-ghost-gray dark:border-white/10 pt-20 pb-12 sm:py-16 px-4 md:px-gutter">
        <div
            class="max-w-container-max mx-auto flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div>
                <h1 class="font-display text-display text-deep-onyx dark:text-stark-white uppercase tracking-tight leading-tight">Open <span class="about-foundation-text">Positions</span></h1>
                <p class="font-body-lg text-slate-text dark:text-stark-white/90 mt-3 max-w-xl">Discover <strong class="about-foundation-text font-bold">{{ $vacancies->total() }}</strong> active {{ Str::plural('opportunity', $vacancies->total()) }} across premier partner institutions.</p>
            </div>

            {{-- Quick Search Form --}}
            <form action="{{ route('vacancies.index') }}" method="GET"
                class="search-box w-full md:w-auto flex items-center gap-2 p-1.5">
                <div class="flex items-center px-3 bg-ghost-gray dark:bg-white/10 rounded-full py-1.5 flex-1">
                    <span class="material-symbols-outlined text-secondary dark:text-white/60 mr-2 text-lg">search</span>
                    <input type="text" name="keyword" value="{{ $filters['keyword'] ?? '' }}"
                        placeholder="Search title or skill..."
                        class="w-full bg-transparent border-none text-sm outline-none text-deep-onyx dark:text-white placeholder-secondary dark:placeholder-white/60">
                </div>
                <button type="submit"
                    class="section-primary-btn px-5 py-2 rounded-full font-label-bold text-xs uppercase tracking-wider shrink-0">
                    Find
                </button>
            </form>
        </div>
    </section>

    {{-- ===== VACANCIES DIRECTORY BODY ===== --}}
    <section class="py-10 sm:py-16 bg-stark-white dark:bg-deep-onyx bg-checked-pattern px-4 md:px-gutter">
        <div class="max-w-container-max mx-auto">
            <div class="vacancies-layout">

                {{-- ===== SIDEBAR FILTERS ===== --}}
                <aside class="vacancies-sidebar space-y-6">

                    {{-- Mobile Filter Toggle Accordion --}}
                    <div class="w-full lg:hidden" x-data="{ open: false }">
                        <button @click="open = !open" type="button"
                            class="w-full flex items-center justify-between px-6 py-4 rounded-2xl bg-ghost-gray dark:bg-white/10 border border-deep-onyx/15 dark:border-white/15 text-deep-onyx dark:text-stark-white font-label-bold text-sm uppercase tracking-wider">
                            <span class="flex items-center gap-2">
                                <span class="material-symbols-outlined about-foundation-text">filter_list</span>
                                <span>Filter Positions</span>
                                @if(array_filter($filters))
                                    <span class="w-2 h-2 rounded-full bg-[#21255E] dark:bg-[#d7b56d]"></span>
                                @endif
                            </span>
                            <span class="material-symbols-outlined transition-transform duration-200"
                                :class="open ? 'rotate-180' : ''">expand_more</span>
                        </button>

                        <div x-show="open" x-collapse class="mt-3">
                            <div class="card-box p-6 bg-stark-white dark:bg-[#1a1c1c] rounded-2xl">
                                <form method="GET" action="{{ route('vacancies.index') }}" class="space-y-5">
                                    {{-- Keyword Filter --}}
                                    <div>
                                        <label class="text-xs font-label-bold text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5 block">Search Keyword</label>
                                        <input type="text" name="keyword" value="{{ $filters['keyword'] ?? '' }}" placeholder="Job title or keywords..."
                                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-ghost-gray dark:border-white/10 bg-ghost-gray dark:bg-deep-onyx text-deep-onyx dark:text-stark-white focus:border-[#21255E] dark:focus:border-[#d7b56d] outline-none font-body-md">
                                    </div>
                                    {{-- School Filter --}}
                                    <div>
                                        <label class="text-xs font-label-bold text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5 block">School / Campus</label>
                                        <select name="school_id" class="w-full px-4 py-2.5 text-sm rounded-xl border border-ghost-gray dark:border-white/10 bg-ghost-gray dark:bg-deep-onyx text-deep-onyx dark:text-stark-white focus:border-[#21255E] dark:focus:border-[#d7b56d] outline-none font-body-md">
                                            <option value="">All Institutions</option>
                                            @foreach($schools as $school)
                                                <option value="{{ $school->id }}" {{ ($filters['school_id'] ?? '') == $school->id ? 'selected' : '' }}>
                                                    {{ $school->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- Category Filter --}}
                                    <div>
                                        <label class="text-xs font-label-bold text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5 block">Discipline / Category</label>
                                        <select name="category_id" class="w-full px-4 py-2.5 text-sm rounded-xl border border-ghost-gray dark:border-white/10 bg-ghost-gray dark:bg-deep-onyx text-deep-onyx dark:text-stark-white focus:border-[#21255E] dark:focus:border-[#d7b56d] outline-none font-body-md">
                                            <option value="">All Disciplines</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ ($filters['category_id'] ?? '') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- Employment Type Filter --}}
                                    <div>
                                        <label class="text-xs font-label-bold text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5 block">Employment Type</label>
                                        <select name="employment_type" class="w-full px-4 py-2.5 text-sm rounded-xl border border-ghost-gray dark:border-white/10 bg-ghost-gray dark:bg-deep-onyx text-deep-onyx dark:text-stark-white focus:border-[#21255E] dark:focus:border-[#d7b56d] outline-none font-body-md">
                                            <option value="">All Types</option>
                                            <option value="full_time" {{ ($filters['employment_type'] ?? '') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                                            <option value="part_time" {{ ($filters['employment_type'] ?? '') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                                            <option value="contract" {{ ($filters['employment_type'] ?? '') == 'contract' ? 'selected' : '' }}>Contract</option>
                                        </select>
                                    </div>
                                    {{-- Location Filter --}}
                                    <div>
                                        <label class="text-xs font-label-bold text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5 block">Location</label>
                                        <input type="text" name="location" value="{{ $filters['location'] ?? '' }}" placeholder="City, region, or Remote"
                                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-ghost-gray dark:border-white/10 bg-ghost-gray dark:bg-deep-onyx text-deep-onyx dark:text-stark-white focus:border-[#21255E] dark:focus:border-[#d7b56d] outline-none font-body-md">
                                    </div>

                                    <button type="submit"
                                        class="section-primary-btn w-full py-3 rounded-full font-label-bold text-sm uppercase tracking-wider lift-hover">
                                        Apply Filters
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Desktop Sticky Sidebar --}}
                    <div class="hidden lg:block card-box p-6 bg-stark-white dark:bg-[#1a1c1c] sticky top-24">
                        <div class="flex items-center justify-between mb-5 pb-3 border-b border-ghost-gray dark:border-white/10">
                            <h2 class="font-headline-md text-xl text-deep-onyx dark:text-stark-white flex items-center gap-2">
                                <span class="material-symbols-outlined about-foundation-text">filter_list</span>
                                Filter Roles
                            </h2>
                            @if(array_filter($filters))
                                <a href="{{ route('vacancies.index') }}"
                                    class="text-xs about-foundation-text hover:underline font-label-bold">Clear All</a>
                            @endif
                        </div>

                        <form method="GET" action="{{ route('vacancies.index') }}" class="space-y-5">
                            {{-- Keyword Filter --}}
                            <div>
                                <label
                                    class="text-xs font-label-bold text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5 block">Search Keyword</label>
                                <input type="text" name="keyword" value="{{ $filters['keyword'] ?? '' }}"
                                    placeholder="Job title or keywords..."
                                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-ghost-gray dark:border-white/10 bg-ghost-gray dark:bg-deep-onyx text-deep-onyx dark:text-stark-white focus:border-[#21255E] dark:focus:border-[#d7b56d] outline-none font-body-md">
                            </div>

                            {{-- School Filter --}}
                            <div>
                                <label
                                    class="text-xs font-label-bold text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5 block">School / Campus</label>
                                <select name="school_id"
                                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-ghost-gray dark:border-white/10 bg-ghost-gray dark:bg-deep-onyx text-deep-onyx dark:text-stark-white focus:border-[#21255E] dark:focus:border-[#d7b56d] outline-none font-body-md">
                                    <option value="">All Institutions</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}" {{ ($filters['school_id'] ?? '') == $school->id ? 'selected' : '' }}>
                                            {{ $school->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Category Filter --}}
                            <div>
                                <label
                                    class="text-xs font-label-bold text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5 block">Discipline / Category</label>
                                <select name="category_id"
                                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-ghost-gray dark:border-white/10 bg-ghost-gray dark:bg-deep-onyx text-deep-onyx dark:text-stark-white focus:border-[#21255E] dark:focus:border-[#d7b56d] outline-none font-body-md">
                                    <option value="">All Disciplines</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ ($filters['category_id'] ?? '') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Employment Type Filter --}}
                            <div>
                                <label
                                    class="text-xs font-label-bold text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5 block">Employment Type</label>
                                <select name="employment_type"
                                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-ghost-gray dark:border-white/10 bg-ghost-gray dark:bg-deep-onyx text-deep-onyx dark:text-stark-white focus:border-[#21255E] dark:focus:border-[#d7b56d] outline-none font-body-md">
                                    <option value="">All Types</option>
                                    <option value="full_time" {{ ($filters['employment_type'] ?? '') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                                    <option value="part_time" {{ ($filters['employment_type'] ?? '') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                                    <option value="contract" {{ ($filters['employment_type'] ?? '') == 'contract' ? 'selected' : '' }}>Contract</option>
                                </select>
                            </div>

                            {{-- Location Filter --}}
                            <div>
                                <label
                                    class="text-xs font-label-bold text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5 block">Location</label>
                                <input type="text" name="location" value="{{ $filters['location'] ?? '' }}"
                                    placeholder="City, region, or Remote"
                                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-ghost-gray dark:border-white/10 bg-ghost-gray dark:bg-deep-onyx text-deep-onyx dark:text-stark-white focus:border-[#21255E] dark:focus:border-[#d7b56d] outline-none font-body-md">
                            </div>

                            <button type="submit"
                                class="section-primary-btn w-full py-3 rounded-full font-label-bold text-sm uppercase tracking-wider lift-hover">
                                Apply Filters
                            </button>
                        </form>
                    </div>
                </aside>

                {{-- ===== JOB LISTINGS ===== --}}
                <div class="vacancies-content space-y-4">
                    @forelse($vacancies as $vacancy)
                        <div class="vacancy-row group flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                            <div class="flex items-start gap-4 min-w-0 flex-1">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h3
                                            class="font-body-lg text-deep-onyx dark:text-stark-white text-xl font-bold vacancy-title-text transition-colors truncate">
                                            <a href="{{ route('vacancies.show', $vacancy->slug) }}">{{ $vacancy->title }}</a>
                                        </h3>

                                        @if($vacancy->is_featured)
                                            <span
                                                class="vacancy-featured-badge inline-flex items-center gap-1 font-label-sm text-[11px] px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                                                <span class="material-symbols-outlined text-[12px]">bolt</span> Featured
                                            </span>
                                        @endif
                                    </div>

                                    <p
                                        class="text-xs font-label-bold text-secondary dark:text-stark-white/80 mt-1 flex items-center gap-2 flex-wrap">
                                        <span
                                            class="text-deep-onyx/80 dark:text-stark-white/80 font-normal text-xs">{{ $vacancy->school->name }}</span>
                                        @if($vacancy->department)
                                            <span>•</span>
                                            <span>{{ $vacancy->department->name }}</span>
                                        @endif
                                    </p>

                                    {{-- Meta Badges --}}
                                    <div class="flex flex-wrap gap-2 mt-4">
                                        <span
                                            class="badge-pill inline-block font-label-sm text-xs px-3 py-1 rounded-full uppercase tracking-wider">
                                            {{ ucfirst(str_replace('_', ' ', $vacancy->employment_type)) }}
                                        </span>
                                        @if($vacancy->location)
                                            <span
                                                class="badge-pill inline-flex items-center gap-1 font-label-sm text-xs px-3 py-1 rounded-full uppercase tracking-wider">
                                                <span class="material-symbols-outlined text-[14px]">location_on</span>
                                                {{ $vacancy->location }}
                                            </span>
                                        @endif
                                        @if($vacancy->globalClass)
                                            <span
                                                class="about-badge inline-flex items-center gap-1 font-label-sm text-xs px-3 py-1 rounded-full uppercase tracking-wider font-semibold">
                                                <span class="material-symbols-outlined text-[14px]">school</span>
                                                {{ $vacancy->globalClass->name }}
                                            </span>
                                        @endif
                                        @if($vacancy->salary_from)
                                            <span
                                                class="badge-pill inline-block font-label-bold text-xs px-3 py-1 rounded-full uppercase tracking-wider">
                                                ₹{{ number_format($vacancy->salary_from) }}{{ $vacancy->salary_to ? '–' . number_format($vacancy->salary_to) : '+' }}/mo
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Right Action Button --}}
                            <div
                                class="flex sm:flex-col items-center sm:items-end justify-between sm:justify-center gap-3 shrink-0 w-full sm:w-auto pt-4 sm:pt-0 border-t sm:border-0 border-ghost-gray dark:border-white/10">
                                <a href="{{ route('vacancies.show', $vacancy->slug) }}"
                                    class="section-primary-btn px-6 py-2.5 rounded-full font-label-bold text-xs uppercase tracking-wider lift-hover flex items-center gap-1 whitespace-nowrap">
                                    <span>View Role</span>
                                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                </a>
                                @if($vacancy->deadline)
                                    <p class="text-[11px] text-secondary dark:text-stark-white/70">
                                        Deadline: <span
                                            class="{{ $vacancy->deadline->isPast() ? 'text-error font-bold' : 'text-deep-onyx dark:text-stark-white font-semibold' }}">{{ $vacancy->deadline->format('M d, Y') }}</span>
                                    </p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div
                            class="text-center py-20 bg-stark-white dark:bg-[#1a1c1c] rounded-3xl border-2 border-deep-onyx/10 dark:border-white/10 text-deep-onyx dark:text-stark-white space-y-4 shadow-xs">
                            <span class="material-symbols-outlined about-foundation-text text-5xl">search_off</span>
                            <h3 class="font-headline-md text-2xl">No vacancies match your search</h3>
                            <p class="text-secondary dark:text-stark-white/80 text-sm max-w-md mx-auto">Try adjusting your
                                keyword, school, or location filters to find open opportunities.</p>
                            <a href="{{ route('vacancies.index') }}"
                                class="section-primary-btn inline-block px-8 py-3 rounded-full font-label-bold uppercase text-xs tracking-wider lift-hover">
                                Clear All Filters
                            </a>
                    @endforelse

                    {{-- Pagination --}}
                    @if($vacancies->hasPages())
                        <div class="mt-8 flex justify-center">{{ $vacancies->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection