@extends('layouts.app')

@section('title', 'Find Your Next Career Move')
@section('meta_description', 'High-performance careers start here. Discover teaching, leadership and administrative opportunities across top educational institutions.')

@section('content')
    <main class="flex-grow flex flex-col">

        {{-- ===== HERO SECTION ===== --}}
        <section
            class="w-full bg-stark-white dark:bg-deep-onyx bg-checked-pattern border-b border-ghost-gray dark:border-white/10 py-xl">
            <div class="px-margin-mobile md:px-gutter max-w-container-max mx-auto flex flex-col items-center text-center">
                {{-- Hero Pill Badge --}}
                <div
                    class="hero-chip-badge inline-flex items-center gap-2 px-4 py-2 rounded-full mb-md shadow-xs">
                    <span class="material-symbols-outlined text-[16px]"
                        style="font-variation-settings: 'FILL' 1;">bolt</span>
                    <span
                        class="font-label-bold text-label-bold">{{ $cms['hero_badge'] ?? 'Over 10,000 new opportunities added' }}</span>
                </div>

                {{-- Headline --}}
                <h1
                    class="font-display text-display text-deep-onyx dark:text-stark-white mb-md max-w-4xl tracking-tight uppercase leading-tight">
                    {{ $cms['hero_title'] ?? 'FIND YOUR NEXT' }}
                    <span class="hero-headline-accent relative inline-block">
                        CAREER
                        <svg class="hero-headline-underline absolute w-full h-4 -bottom-1 left-0"
                            preserveAspectRatio="none" viewBox="0 0 100 20">
                            <path d="M0,10 Q50,20 100,10" fill="none" stroke="currentColor" stroke-width="4"></path>
                        </svg>
                    </span>
                    MOVE
                </h1>

                {{-- Subtitle --}}
                <p class="font-body-lg text-body-lg text-slate-text dark:text-stark-white/90 max-w-2xl mb-lg">
                    {{ $cms['hero_subtitle'] ?? 'High-performance careers start here. Discover teaching, leadership, and administrative opportunities across top educational institutions.' }}
                </p>

                {{-- Search Bar --}}
                <form action="{{ route('vacancies.index') }}" method="GET"
                    class="search-box w-full max-w-2xl p-1.5 flex flex-col md:flex-row gap-1.5 relative z-10">
                    <div class="flex-1 flex items-center px-3.5 bg-ghost-gray dark:bg-white/10 rounded-full border border-transparent dark:border-white/10">
                        <span class="material-symbols-outlined text-secondary dark:text-white/60 mr-1.5 text-xl">search</span>
                        <input
                            class="w-full bg-transparent border-none focus:ring-0 text-sm font-body-md placeholder-secondary dark:placeholder-white/60 text-deep-onyx dark:text-white py-2 outline-none"
                            name="keyword" value="{{ request('keyword') }}" placeholder="Job title, keyword, or role"
                            type="text" />
                    </div>
                    <div class="flex-1 flex items-center px-3.5 bg-ghost-gray dark:bg-white/10 rounded-full border border-transparent dark:border-white/10">
                        <span class="material-symbols-outlined text-secondary dark:text-white/60 mr-1.5 text-xl">location_on</span>
                        <input
                            class="w-full bg-transparent border-none focus:ring-0 text-sm font-body-md placeholder-secondary dark:placeholder-white/60 text-deep-onyx dark:text-white py-2 outline-none"
                            name="location" value="{{ request('location') }}" placeholder="City, state, or Remote"
                            type="text" />
                    </div>
                    <button type="submit"
                        class="bg-electric-green text-white px-6 py-2 rounded-full font-label-bold text-xs uppercase tracking-wide hover:opacity-90 transition-colors flex-shrink-0 border-2 border-deep-onyx dark:border-white/20 flex items-center justify-center gap-1">
                        <span>Search</span>
                        <span class="material-symbols-outlined text-base text-white">arrow_forward</span>
                    </button>
                </form>

                {{-- Popular Search Chips --}}
                <div class="flex items-center justify-center gap-2 mt-6 flex-wrap">
                    <span
                        class="text-xs font-label-bold text-secondary dark:text-stark-white/90 uppercase tracking-wider">Popular
                        searches:</span>
                    @foreach(['Teacher', 'Principal', 'Administrator', 'Mathematics', 'Science'] as $tag)
                        <a href="{{ route('vacancies.index', ['keyword' => $tag]) }}"
                            class="tag-chip text-xs px-3.5 py-1.5 rounded-full font-label-bold transition-all">
                            {{ $tag }}
                        </a>
                    @endforeach
                </div>

                {{-- Hero Stats / Social Proof --}}
                <div class="mt-10 sm:mt-14 max-w-4xl mx-auto">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3.5 sm:gap-6">
                        {{-- Stat 1 --}}
                        <div
                            class="bg-stark-white dark:bg-white/5 border border-deep-onyx/10 dark:border-white/10 rounded-2xl p-4 sm:p-5 text-center shadow-xs backdrop-blur-sm transition-all hover:border-electric-green/40">
                            <span class="font-headline-md text-2xl sm:text-3xl text-deep-onyx dark:text-stark-white block mb-0.5">
                                {{ $cms['stats_schools'] ?? '25+' }}
                            </span>
                            <span class="font-label-bold text-[10px] sm:text-xs text-secondary dark:text-electric-green uppercase tracking-wider block">
                                Partner Institutions
                            </span>
                        </div>

                        {{-- Stat 2 --}}
                        <div
                            class="bg-stark-white dark:bg-white/5 border border-deep-onyx/10 dark:border-white/10 rounded-2xl p-4 sm:p-5 text-center shadow-xs backdrop-blur-sm transition-all hover:border-electric-green/40">
                            <span class="font-headline-md text-2xl sm:text-3xl text-deep-onyx dark:text-stark-white block mb-0.5">
                                {{ $cms['stats_teachers'] ?? '80+' }}
                            </span>
                            <span class="font-label-bold text-[10px] sm:text-xs text-secondary dark:text-electric-green uppercase tracking-wider block">
                                Active Teachers
                            </span>
                        </div>

                        {{-- Stat 3 --}}
                        <div
                            class="bg-stark-white dark:bg-white/5 border border-deep-onyx/10 dark:border-white/10 rounded-2xl p-4 sm:p-5 text-center shadow-xs backdrop-blur-sm transition-all hover:border-electric-green/40">
                            <span class="font-headline-md text-2xl sm:text-3xl text-deep-onyx dark:text-stark-white block mb-0.5">
                                {{ $cms['stats_hired'] ?? '1,200+' }}
                            </span>
                            <span class="font-label-bold text-[10px] sm:text-xs text-secondary dark:text-electric-green uppercase tracking-wider block">
                                Educators Placed
                            </span>
                        </div>

                        {{-- Stat 4 --}}
                        <div
                            class="bg-stark-white dark:bg-white/5 border border-deep-onyx/10 dark:border-white/10 rounded-2xl p-4 sm:p-5 text-center shadow-xs backdrop-blur-sm transition-all hover:border-electric-green/40">
                            <span class="font-headline-md text-2xl sm:text-3xl text-deep-onyx dark:text-stark-white block mb-0.5">
                                {{ $cms['stats_sat'] ?? '99%' }}
                            </span>
                            <span class="font-label-bold text-[10px] sm:text-xs text-secondary dark:text-electric-green uppercase tracking-wider block">
                                Satisfaction Rate
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== ALL OPEN POSITIONS SECTION ===== --}}
        <section
            class="w-full bg-stark-white dark:bg-[#111111] bg-checked-pattern py-xl border-b border-ghost-gray dark:border-white/10">
            <div class="px-margin-mobile md:px-gutter max-w-container-max mx-auto mb-lg">
                <div
                    class="section-chip-badge inline-flex items-center gap-2 px-4 py-2 rounded-full mb-md">
                    <span class="material-symbols-outlined text-[16px]"
                        style="font-variation-settings: 'FILL' 1;">star</span>
                    <span class="font-label-sm text-sm">Openings</span>
                </div>
                <div class="flex justify-between items-end">
                    <h2
                        class="font-display text-headline-lg md:text-[56px] text-deep-onyx dark:text-stark-white leading-tight">
                        Latest <span class="section-title-accent">Openings</span>
                    </h2>
                    <span class="material-symbols-outlined section-ac-unit text-4xl hidden md:block">ac_unit</span>
                </div>
                <p class="text-slate-text dark:text-stark-white/90 font-body-md mt-4">Discover opportunities at top
                    educational institutions.</p>
            </div>

            <div class="px-margin-mobile md:px-gutter max-w-container-max mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($latestVacancies as $vacancy)
                        @include('public.partials.vacancy-card', ['vacancy' => $vacancy])
                    @empty
                        <div
                            class="col-span-full text-center py-16 bg-ghost-gray dark:bg-[#1a1c1c] rounded-3xl border-2 border-deep-onyx/10 dark:border-white/10 text-deep-onyx dark:text-stark-white">
                            <span class="material-symbols-outlined section-title-accent text-5xl mb-3">work_outline</span>
                            <h3 class="font-headline-md text-xl">No active vacancies right now</h3>
                            <p class="text-secondary dark:text-stark-white/80 text-sm mt-1">Please check back soon for new
                                opportunities.</p>
                        </div>
                    @endforelse
                </div>
                @if($latestVacancies->count())
                    <div class="mt-10 text-center">
                        <a href="{{ route('vacancies.index') }}"
                            class="section-primary-btn inline-flex items-center gap-2 px-8 py-3.5 rounded-full font-label-bold text-label-bold uppercase tracking-wider lift-hover">
                            <span>View All Positions</span>
                            <span class="material-symbols-outlined text-lg">arrow_forward</span>
                        </a>
                    </div>
                @endif
            </div>
        </section>

        {{-- ===== EXPLORE BY DISCIPLINE / CATEGORY SECTION ===== --}}
        @if($categories->count())
            <section
                class="w-full bg-stark-white dark:bg-[#111111] bg-checked-pattern py-xl border-b border-ghost-gray dark:border-white/10">
                <div class="px-margin-mobile md:px-gutter max-w-container-max mx-auto mb-lg">
                    <div
                        class="section-chip-badge inline-flex items-center gap-2 px-4 py-2 rounded-full mb-md">
                        <span class="material-symbols-outlined text-[16px]"
                            style="font-variation-settings: 'FILL' 1;">category</span>
                        <span class="font-label-sm text-sm">Disciplines</span>
                    </div>
                    <div class="flex justify-between items-end">
                        <h2
                            class="font-display text-headline-lg md:text-[56px] text-deep-onyx dark:text-stark-white leading-tight">
                            Explore by <span class="section-title-accent">Category</span>
                        </h2>
                        <span class="material-symbols-outlined section-ac-unit text-4xl hidden md:block">ac_unit</span>
                    </div>
                    <p class="text-slate-text dark:text-stark-white/90 font-body-md mt-4">Browse opportunities across academic
                        departments and administrative functions.</p>
                </div>

                <div class="px-margin-mobile md:px-gutter max-w-container-max mx-auto">
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                        @php
                            $catIcons = ['menu_book', 'science', 'calculate', 'public', 'palette', 'music_note', 'computer', 'fitness_center', 'military_tech', 'explore', 'layers', 'shield'];
                        @endphp
                        @foreach($categories as $i => $cat)
                            <a href="{{ route('vacancies.index', ['category_id' => $cat->id]) }}"
                                class="category-card card-box-sm bg-stark-white dark:bg-[#1a1c1c] group p-5 hover:border-deep-onyx dark:hover:border-[#d7b56d] transition-all flex items-center gap-4">
                                <div
                                    class="category-card-icon w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 transition-colors">
                                    <span class="material-symbols-outlined text-2xl">{{ $catIcons[$i % count($catIcons)] }}</span>
                                </div>
                                <div class="min-w-0">
                                    <p
                                        class="category-card-title font-label-bold text-sm text-deep-onyx dark:text-stark-white transition-colors truncate">
                                        {{ $cat->name }}
                                    </p>
                                    <p class="text-xs text-secondary dark:text-stark-white/70 mt-0.5 font-label-sm">
                                        {{ $cat->vacancies_count ?? 0 }} open {{ Str::plural('role', $cat->vacancies_count ?? 0) }}
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- ===== ABOUT YES INDIA FOUNDATION SECTION ===== --}}
        <section
            class="w-full bg-stark-white dark:bg-[#111111] bg-checked-pattern py-xl border-b border-ghost-gray dark:border-white/10">
            <div class="px-margin-mobile md:px-gutter max-w-container-max mx-auto">

                {{-- Section Header --}}
                <div class="mb-lg">
                    <div
                        class="about-badge inline-flex items-center gap-2 px-4 py-2 rounded-full mb-md">
                        <span class="material-symbols-outlined text-[16px]"
                            style="font-variation-settings: 'FILL' 1;">volunteer_activism</span>
                        <span class="font-label-sm text-sm">About Us</span>
                    </div>
                    <div class="flex justify-between items-end">
                        <h2
                            class="font-display text-headline-lg md:text-[56px] text-deep-onyx dark:text-stark-white leading-tight">
                            YES India <span class="about-foundation-text">Foundation</span>
                        </h2>
                        <span class="material-symbols-outlined about-ac-unit text-4xl hidden md:block">ac_unit</span>
                    </div>
                    <p class="text-slate-text dark:text-stark-white/90 font-body-md mt-4 max-w-3xl">
                        An educational and humanitarian initiative dedicated to the uplift of backward communities across
                        India.
                    </p>
                </div>

                {{-- Main Content Card --}}
                <div class="card-box bg-stark-white dark:bg-[#1a1c1c] relative p-8 md:p-14 mb-8 overflow-hidden">


                    <div class="flex flex-col lg:flex-row gap-10 lg:gap-16 relative z-10">

                        {{-- Left: Main Description --}}
                        <div class="lg:w-3/5">
                            <p
                                class="text-slate-text dark:text-stark-white/85 font-body-md text-base md:text-lg leading-relaxed mb-6">
                                <span class="font-label-bold text-deep-onyx dark:text-stark-white">YES India
                                    Foundation</span> is an educational and humanitarian initiative, aiming the educational
                                and social uplift of the backward communities in India. Shaping the better culture, YES
                                India Foundation could become successful, within a short span of time, in providing the
                                value-based education through its academic institutions.
                            </p>
                            <p
                                class="text-slate-text dark:text-stark-white/85 font-body-md text-base md:text-lg leading-relaxed mb-6">
                                Currently, it has a huge network of educational institutes across India — spread over <span
                                    class="about-foundation-text font-label-bold">8 states</span>; namely
                                Kerala, Karnataka, Andhra Pradesh, Maharashtra, Rajasthan, Bihar, West Bengal and Jammu
                                Kashmir. Around <span class="about-foundation-text font-label-bold">17,000
                                    students</span> are beneficiaries of <span
                                    class="about-foundation-text font-label-bold">60 institutes</span>
                                including 8 residential institutes under YES India Foundation.
                            </p>
                            <p
                                class="text-slate-text dark:text-stark-white/85 font-body-md text-base md:text-lg leading-relaxed">
                                YES India Foundation focuses on holistic &amp; all-round educational plans — ensuring
                                quality education to marginalized people, fostering social leaders through residential
                                institutes up to Post Graduate level, offering scholarships and facilities, and shaping the
                                leaders of the next generation.
                            </p>

                            <a href="https://web.yesindiafoundation.com/about" target="_blank" rel="noopener noreferrer"
                                class="about-learn-more-btn inline-flex items-center gap-2 mt-8 px-7 py-3 rounded-full font-label-bold text-label-bold uppercase tracking-wide hover:opacity-90 transition-colors lift-hover">
                                <span>Learn More</span>
                                <span class="material-symbols-outlined text-lg">open_in_new</span>
                            </a>
                        </div>

                        {{-- Right: Key Stats --}}
                        <div class="lg:w-2/5 flex flex-col gap-5">
                            {{-- Stat 1: Students --}}
                            <div
                                class="stat-card-hover group bg-[#21255E]/10 dark:bg-[#d7b56d]/10 border-2 border-deep-onyx/10 dark:border-[#d7b56d]/30 rounded-2xl p-6 flex items-center gap-5 transition-all duration-300 hover:border-[#21255E] dark:hover:border-[#d7b56d] hover:shadow-[4px_4px_0px_0px_#21255E] dark:hover:shadow-none hover:-translate-y-1 cursor-default">
                                <div
                                    class="stat-icon-bg w-14 h-14 rounded-2xl flex items-center justify-center border-2 border-deep-onyx flex-shrink-0 transition-transform duration-300 group-hover:scale-110">
                                    <span class="material-symbols-outlined text-2xl"
                                        style="font-variation-settings: 'FILL' 1;">people</span>
                                </div>
                                <div>
                                    <p
                                        class="stat-number-text font-display text-4xl text-deep-onyx dark:text-stark-white leading-none transition-colors duration-300">
                                        17,000+</p>
                                    <p
                                        class="font-label-sm text-sm text-secondary dark:text-stark-white/70 uppercase tracking-wide mt-1">
                                        Students Benefited</p>
                                </div>
                            </div>
                            {{-- Stat 2: Institutes --}}
                            <div
                                class="stat-card-hover group bg-stark-white dark:bg-white/5 border-2 border-deep-onyx/10 dark:border-white/10 rounded-2xl p-6 flex items-center gap-5 transition-all duration-300 hover:border-[#21255E] dark:hover:border-[#d7b56d] hover:shadow-[4px_4px_0px_0px_#171717] dark:hover:shadow-none hover:-translate-y-1 cursor-default">
                                <div
                                    class="stat-icon-soft w-14 h-14 rounded-2xl flex items-center justify-center border-2 border-deep-onyx dark:border-[#d7b56d]/30 flex-shrink-0 transition-transform duration-300 group-hover:scale-110">
                                    <span
                                        class="material-symbols-outlined text-2xl"
                                        style="font-variation-settings: 'FILL' 1;">business</span>
                                </div>
                                <div>
                                    <p
                                        class="stat-number-text font-display text-4xl text-deep-onyx dark:text-stark-white leading-none transition-colors duration-300">
                                        60
                                    </p>
                                    <p
                                        class="font-label-sm text-sm text-secondary dark:text-stark-white/70 uppercase tracking-wide mt-1">
                                        Institutes Across India</p>
                                </div>
                            </div>
                            {{-- Stat 3: States --}}
                            <div
                                class="stat-card-hover group bg-stark-white dark:bg-white/5 border-2 border-deep-onyx/10 dark:border-white/10 rounded-2xl p-6 flex items-center gap-5 transition-all duration-300 hover:border-[#21255E] dark:hover:border-[#d7b56d] hover:shadow-[4px_4px_0px_0px_#171717] dark:hover:shadow-none hover:-translate-y-1 cursor-default">
                                <div
                                    class="stat-icon-soft w-14 h-14 rounded-2xl flex items-center justify-center border-2 border-deep-onyx dark:border-[#d7b56d]/30 flex-shrink-0 transition-transform duration-300 group-hover:scale-110">
                                    <span
                                        class="material-symbols-outlined text-2xl"
                                        style="font-variation-settings: 'FILL' 1;">map</span>
                                </div>
                                <div>
                                    <p
                                        class="stat-number-text font-display text-4xl text-deep-onyx dark:text-stark-white leading-none transition-colors duration-300">
                                        8</p>
                                    <p
                                        class="font-label-sm text-sm text-secondary dark:text-stark-white/70 uppercase tracking-wide mt-1">
                                        States of Operations</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3 Spotlight Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <a href="https://web.yesindiafoundation.com/history" target="_blank" rel="noopener noreferrer"
                        class="spotlight-card card-box-sm bg-stark-white dark:bg-[#1a1c1c] group flex flex-col gap-4 p-6 hover:border-[#21255E] dark:hover:border-[#d7b56d] lift-hover transition-all">
                        <div
                            class="spotlight-icon-box w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 transition-colors">
                            <span
                                class="material-symbols-outlined text-2xl"
                                style="font-variation-settings: 'FILL' 1;">history_edu</span>
                        </div>
                        <div>
                            <h3
                                class="spotlight-title font-label-bold text-base text-deep-onyx dark:text-stark-white transition-colors">
                                The Inspiring Journey</h3>
                            <p class="text-xs text-secondary dark:text-stark-white/70 mt-1.5 font-label-sm leading-relaxed">
                                From humble beginnings in Poonch in 2007, charting a remarkable path of expansion and
                                progress across India.</p>
                        </div>
                        <div
                            class="spotlight-link flex items-center gap-1.5 text-xs font-label-bold text-secondary transition-colors mt-auto">
                            <span>Read more</span>
                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </div>
                    </a>
                    <a href="https://web.yesindiafoundation.com/milestone" target="_blank" rel="noopener noreferrer"
                        class="spotlight-card card-box-sm bg-stark-white dark:bg-[#1a1c1c] group flex flex-col gap-4 p-6 hover:border-[#21255E] dark:hover:border-[#d7b56d] lift-hover transition-all">
                        <div
                            class="spotlight-icon-box w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 transition-colors">
                            <span
                                class="material-symbols-outlined text-2xl"
                                style="font-variation-settings: 'FILL' 1;">emoji_events</span>
                        </div>
                        <div>
                            <h3
                                class="spotlight-title font-label-bold text-base text-deep-onyx dark:text-stark-white transition-colors">
                                Milestones of Growth</h3>
                            <p class="text-xs text-secondary dark:text-stark-white/70 mt-1.5 font-label-sm leading-relaxed">
                                Each year has brought new achievements — new institutes, enhanced infrastructure, and
                                innovative educational programs.</p>
                        </div>
                        <div
                            class="spotlight-link flex items-center gap-1.5 text-xs font-label-bold text-secondary transition-colors mt-auto">
                            <span>Yearly progress since 2007</span>
                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </div>
                    </a>
                    <a href="https://web.yesindiafoundation.com/states" target="_blank" rel="noopener noreferrer"
                        class="spotlight-card card-box-sm bg-stark-white dark:bg-[#1a1c1c] group flex flex-col gap-4 p-6 hover:border-[#21255E] dark:hover:border-[#d7b56d] lift-hover transition-all">
                        <div
                            class="spotlight-icon-box w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 transition-colors">
                            <span
                                class="material-symbols-outlined text-2xl"
                                style="font-variation-settings: 'FILL' 1;">public</span>
                        </div>
                        <div>
                            <h3
                                class="spotlight-title font-label-bold text-base text-deep-onyx dark:text-stark-white transition-colors">
                                States of Operations</h3>
                            <p class="text-xs text-secondary dark:text-stark-white/70 mt-1.5 font-label-sm leading-relaxed">
                                Expanding horizons across Kerala, Karnataka, Andhra Pradesh, Maharashtra, Rajasthan, Bihar,
                                West Bengal &amp; J&amp;K.</p>
                        </div>
                        <div
                            class="spotlight-link flex items-center gap-1.5 text-xs font-label-bold text-secondary transition-colors mt-auto">
                            <span>Expanding horizons</span>
                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </div>
                    </a>
                </div>

            </div>
        </section>

        {{-- ===== HIRING PROCESS SECTION ("The Process") ===== --}}
        <section
            class="w-full bg-stark-white dark:bg-[#111111] bg-checked-pattern py-12 md:py-16 border-b border-ghost-gray dark:border-white/10">
            <div class="px-margin-mobile md:px-gutter max-w-7xl mx-auto">
                <div
                    class="card-box bg-stark-white dark:bg-[#1a1c1c] p-6 md:p-10 lg:p-12 flex flex-col lg:flex-row gap-8 lg:gap-14 relative overflow-hidden">
                    <!-- Background decorative icon -->
                    <span
                        class="material-symbols-outlined process-icon-bg text-6xl absolute top-12 right-24 opacity-20 hidden lg:block">ac_unit</span>

                    <!-- Left Column (Sticky Header) -->
                    <div class="lg:w-2/5 flex flex-col justify-between items-start relative z-10">
                        <div
                            class="process-badge w-fit self-start inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full mb-6">
                            <span class="material-symbols-outlined text-[16px]"
                                style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="font-label-sm text-sm">The Process</span>
                        </div>
                        <h2
                            class="font-display text-4xl md:text-5xl lg:text-6xl text-deep-onyx dark:text-stark-white leading-[1.1] mb-4 tracking-tight">
                            The story<br />behind <span
                                class="text-process-accent">{{ $siteSettings['site_name'] ?? 'CareerFlow' }}</span>
                        </h2>
                        <p class="text-slate-text dark:text-stark-white/90 font-body-md text-base md:text-lg max-w-sm">
                            How our streamlined recruitment portal connects exceptional talent with leading educational
                            institutions.
                        </p>
                        <div class="mt-8 flex items-center gap-4">
                            <div
                                class="got-hired-box w-11 h-11 rounded-2xl flex items-center justify-center shadow-xs">
                                <span class="material-symbols-outlined text-xl">star</span>
                            </div>
                            <h3 class="text-2xl sm:text-3xl font-display text-deep-onyx dark:text-stark-white tracking-wide">They <span
                                    class="got-hired-text">got hired.</span></h3>
                        </div>
                    </div>

                    <!-- Right Column (Timeline) -->
                    <div class="lg:w-3/5 relative z-10 pl-2 md:pl-6">
                        <!-- Vertical Line -->
                        <div
                            class="absolute left-[23px] md:left-[31px] top-3 bottom-6 w-1 bg-deep-onyx/20 dark:bg-white/20">
                        </div>

                        <!-- Step 1 -->
                        <div class="relative pl-10 mb-8">
                            <div
                                class="process-step-dot absolute left-[-1px] top-1.5 w-4 h-4 rounded-full border-2 border-deep-onyx shadow-[0_0_0_6px_#ffffff] dark:shadow-[0_0_0_6px_#1a1c1c]">
                            </div>
                            <h4
                                class="process-step-title font-label-bold text-base tracking-widest uppercase mb-1">
                                1. Application Review</h4>
                            <p class="text-slate-text dark:text-stark-white/90 text-body-md text-sm md:text-base leading-relaxed">
                                We review your profile to ensure your academic experience and credentials align with
                                institutional needs.
                            </p>
                        </div>

                        <!-- Step 2 -->
                        <div class="relative pl-10 mb-8">
                            <div
                                class="process-step-dot absolute left-[-1px] top-1.5 w-4 h-4 rounded-full border-2 border-deep-onyx shadow-[0_0_0_6px_#ffffff] dark:shadow-[0_0_0_6px_#1a1c1c]">
                            </div>
                            <h4
                                class="process-step-title font-label-bold text-base tracking-widest uppercase mb-1">
                                2. Initial Interview</h4>
                            <p class="text-slate-text dark:text-stark-white/90 text-body-md text-sm md:text-base leading-relaxed">
                                An introductory conversation to discuss your educational philosophy, career vision, and
                                institutional culture fit.
                            </p>
                        </div>

                        <!-- Step 3 -->
                        <div class="relative pl-10 mb-8">
                            <div
                                class="process-step-dot absolute left-[-1px] top-1.5 w-4 h-4 rounded-full border-2 border-deep-onyx shadow-[0_0_0_6px_#ffffff] dark:shadow-[0_0_0_6px_#1a1c1c]">
                            </div>
                            <h4
                                class="process-step-title font-label-bold text-base tracking-widest uppercase mb-1">
                                3. Evaluation &amp; Demo</h4>
                            <p class="text-slate-text dark:text-stark-white/90 text-body-md text-sm md:text-base leading-relaxed">
                                Demonstrate your teaching or leadership skills through a practical exercise or panel
                                presentation with school heads.
                            </p>
                        </div>

                        <!-- Step 4 -->
                        <div class="relative pl-10">
                            <div
                                class="process-step-dot absolute left-[-1px] top-1.5 w-4 h-4 rounded-full border-2 border-deep-onyx shadow-[0_0_0_6px_#ffffff] dark:shadow-[0_0_0_6px_#1a1c1c]">
                            </div>
                            <h4
                                class="process-step-title font-label-bold text-base tracking-widest uppercase mb-1">
                                4. Formal Placement</h4>
                            <p class="text-slate-text dark:text-stark-white/90 text-body-md text-sm md:text-base leading-relaxed">
                                Welcome aboard! Receive your formal placement offer and seamless onboarding guide into your
                                new career role.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== CTA BANNER ===== --}}
        <section
            class="w-full py-xl bg-stark-white dark:bg-deep-onyx bg-checked-pattern border-b border-ghost-gray dark:border-white/10">
            <div class="px-margin-mobile md:px-gutter max-w-container-max mx-auto">
                <div class="cta-banner-box p-8 md:p-14 flex flex-col md:flex-row justify-between items-center gap-8 transition-colors duration-300">
                    <div>
                        <h2
                            class="font-display text-4xl md:text-5xl uppercase tracking-tight">
                            Already submitted an application?</h2>
                        <p class="font-body-md text-lg mt-2 max-w-xl opacity-90">
                            Track your candidate selection status live in real-time using your unique application reference
                            code.
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3.5 sm:gap-4 w-full md:w-auto flex-shrink-0">
                        <a href="{{ route('applications.track') }}"
                            class="cta-btn-primary px-8 py-3.5 rounded-full font-label-bold text-label-bold uppercase tracking-wider lift-hover text-center transition-colors">
                            Track Application
                        </a>
                        <a href="{{ route('contact') }}"
                            class="cta-btn-secondary px-8 py-3.5 rounded-full font-label-bold text-label-bold uppercase tracking-wider lift-hover text-center transition-colors">
                            Contact Us
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </main>
@endsection