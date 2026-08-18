@extends('layouts.app')

@section('title', $vacancy->meta_title ?? $vacancy->title . ' – ' . $vacancy->school->name)
@section('meta_description', $vacancy->meta_description ?? Str::limit(strip_tags($vacancy->description), 160))

@push('head')
    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $vacancy->title }} – {{ $vacancy->school->name }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($vacancy->description), 200) }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    {{-- JSON-LD for Google Jobs --}}
    <script type="application/ld+json">
    {!! json_encode($jsonLd, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@section('content')

    {{-- ===== HERO STRIP ===== --}}
    <section
        class="w-full bg-ghost-gray dark:bg-deep-onyx text-deep-onyx dark:text-stark-white py-10 px-margin-mobile md:px-gutter border-b border-ghost-gray dark:border-white/10">
        <div class="max-w-container-max mx-auto">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-xs font-label-bold text-secondary dark:text-secondary-fixed-dim mb-5">
                <a href="{{ route('home') }}" class="about-foundation-text hover:underline transition-colors">Home</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <a href="{{ route('vacancies.index') }}" class="about-foundation-text hover:underline transition-colors">Job Directory</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span
                    class="text-deep-onyx dark:text-stark-white truncate max-w-[200px] sm:max-w-xs">{{ $vacancy->title }}</span>
            </nav>

            {{-- Title block --}}
            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6">
                <div>
                    @if($vacancy->is_featured)
                        <span
                            class="vacancy-featured-badge inline-flex items-center gap-1 font-label-sm text-xs px-3 py-1 rounded-full uppercase tracking-wider mb-3">
                            <span class="material-symbols-outlined text-[14px]">bolt</span> Featured Opportunity
                        </span>
                    @endif
                    <h1
                        class="font-display text-3xl sm:text-4xl text-deep-onyx dark:text-stark-white uppercase tracking-tight leading-tight">
                        {{ $vacancy->title }}
                    </h1>
                    <p class="text-secondary dark:text-stark-white/80 text-xs mt-1 font-label-sm">
                        {{ $vacancy->school->name }}
                        @if($vacancy->department)&mdash; {{ $vacancy->department->name }}@endif
                        @if($vacancy->category)&mdash; {{ $vacancy->category->name }}@endif
                    </p>

                    {{-- Pill badges --}}
                    <div class="flex flex-wrap gap-2 mt-4">
                        <span
                            class="badge-pill inline-block font-label-sm text-xs px-3 py-1 rounded-full uppercase tracking-wider">
                            {{ ucfirst(str_replace('_', ' ', $vacancy->employment_type)) }}
                        </span>
                        @if($vacancy->location)
                            <span
                                class="badge-pill inline-block font-label-sm text-xs px-3 py-1 rounded-full uppercase tracking-wider">
                                <span class="material-symbols-outlined text-[12px] align-middle mr-0.5">location_on</span>
                                {{ $vacancy->location }}
                            </span>
                        @endif
                        @if($vacancy->deadline)
                            <span
                                class="about-badge inline-flex items-center gap-1 font-label-sm text-xs px-3 py-1 rounded-full uppercase tracking-wider font-semibold">
                                <span class="material-symbols-outlined text-[12px] align-middle">event</span> Deadline:
                                {{ $vacancy->deadline->format('M d, Y') }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- CTA button in hero --}}
                <a href="{{ route('applications.create', $vacancy) }}"
                    class="section-primary-btn inline-flex items-center gap-2 px-7 py-3.5 rounded-full font-label-bold text-sm uppercase tracking-wider lift-hover shrink-0">
                    <span>Apply Now</span>
                    <span class="material-symbols-outlined text-lg">arrow_forward</span>
                </a>
            </div>
        </div>
    </section>

    {{-- ===== VACANCY DETAILS BODY ===== --}}
    <section class="py-12 bg-stark-white dark:bg-deep-onyx bg-checked-pattern px-4 md:px-gutter">
        <div class="max-w-container-max mx-auto">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                {{-- ===== MAIN CONTENT ===== --}}
                <div class="lg:col-span-2 space-y-8 min-w-0">

                    {{-- Single Unified Card Box --}}
                    <div class="card-box p-6 md:p-10 space-y-8">

                        {{-- Top Meta Cards Grid (2x3) --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @php
                                $metas = [
                                    ['icon' => 'work', 'label' => 'Employment Type', 'value' => ucfirst(str_replace('_', ' ', $vacancy->employment_type))],
                                    ['icon' => 'location_on', 'label' => 'Location', 'value' => $vacancy->location ?? 'Main Campus'],
                                    ['icon' => 'event', 'label' => 'Deadline', 'value' => $vacancy->deadline ? $vacancy->deadline->format('M d, Y') : 'Open'],
                                    ['icon' => 'group', 'label' => 'Openings', 'value' => ($vacancy->number_of_vacancies ?? 1) . ' Open'],
                                    ['icon' => 'school', 'label' => 'Academic Level', 'value' => $vacancy->globalClass?->name ?? 'Grade Level'],
                                    ['icon' => 'payments', 'label' => 'Salary Range', 'value' => $vacancy->salary_from ? '₹' . number_format($vacancy->salary_from) . ($vacancy->salary_to ? '–' . number_format($vacancy->salary_to) : '+') : 'Negotiable'],
                                ];
                            @endphp
                            @foreach($metas as $meta)
                                <div class="flex items-center gap-3.5 bg-ghost-gray dark:bg-white/5 p-4 rounded-2xl border border-deep-onyx/10 dark:border-white/10">
                                    <div class="w-10 h-10 rounded-xl bg-stark-white dark:bg-white/10 flex items-center justify-center flex-shrink-0 border border-deep-onyx/10 dark:border-white/10">
                                        <span class="material-symbols-outlined text-xl text-deep-onyx dark:text-stark-white">{{ $meta['icon'] }}</span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[11px] font-label-bold text-secondary dark:text-stark-white/70 uppercase tracking-wider truncate">{{ $meta['label'] }}</p>
                                        <p class="font-label-bold text-sm text-deep-onyx dark:text-stark-white truncate mt-0.5">{{ $meta['value'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Job Description --}}
                        @if($vacancy->description)
                            <div class="space-y-2">
                                <h2 class="font-display text-2xl text-deep-onyx dark:text-stark-white uppercase tracking-tight">Job Description</h2>
                                <div class="text-slate-text dark:text-stark-white/90 text-sm leading-relaxed font-body-md">
                                    {!! nl2br(e($vacancy->description)) !!}
                                </div>
                            </div>
                        @endif

                        {{-- Responsibilities --}}
                        @if($vacancy->responsibilities)
                            <div class="space-y-2">
                                <h2 class="font-display text-2xl text-deep-onyx dark:text-stark-white uppercase tracking-tight">Responsibilities</h2>
                                <div class="text-slate-text dark:text-stark-white/90 text-sm leading-relaxed font-body-md">
                                    {!! nl2br(e($vacancy->responsibilities)) !!}
                                </div>
                            </div>
                        @endif

                        {{-- Requirements & Skills --}}
                        @if($vacancy->requirements)
                            <div class="space-y-2">
                                <h2 class="font-display text-2xl text-deep-onyx dark:text-stark-white uppercase tracking-tight">Requirements &amp; Skills</h2>
                                <div class="text-slate-text dark:text-stark-white/90 text-sm leading-relaxed font-body-md">
                                    {!! nl2br(e($vacancy->requirements)) !!}
                                </div>
                            </div>
                        @endif

                        {{-- Benefits & Perks --}}
                        @if($vacancy->benefits)
                            <div class="space-y-2">
                                <h2 class="font-display text-2xl text-deep-onyx dark:text-stark-white uppercase tracking-tight">Benefits &amp; Perks</h2>
                                <div class="text-slate-text dark:text-stark-white/90 text-sm leading-relaxed font-body-md">
                                    {!! nl2br(e($vacancy->benefits)) !!}
                                </div>
                            </div>
                        @endif

                        {{-- Bottom Apply Section Divider --}}
                        <div class="border-t border-ghost-gray dark:border-white/10 pt-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <h3 class="font-display text-2xl text-deep-onyx dark:text-stark-white uppercase tracking-tight">Ready to apply?</h3>
                                <p class="text-xs text-secondary dark:text-stark-white/70 mt-0.5 font-label-sm">Takes less than 5 minutes &middot; Direct school application</p>
                            </div>
                            <a href="{{ route('applications.create', $vacancy) }}"
                                class="section-primary-btn inline-flex items-center gap-2 px-8 py-3.5 rounded-full font-label-bold text-xs uppercase tracking-wider lift-hover shrink-0">
                                <span>Apply For This Role</span>
                                <span class="material-symbols-outlined text-lg">arrow_forward</span>
                            </a>
                        </div>

                    </div>

                    {{-- Related Jobs --}}
                    @php
                        $filteredRelated = $relatedVacancies->where('id', '!=', $vacancy->id);
                    @endphp
                    @if($filteredRelated->count())
                        <div class="pt-4">
                            <h2 class="font-display text-3xl text-deep-onyx dark:text-stark-white uppercase tracking-tight mb-6">
                                Related <span class="about-foundation-text">Opportunities</span>
                            </h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($filteredRelated->take(4) as $related)
                                    @include('public.partials.vacancy-card', ['vacancy' => $related])
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- ===== SIDEBAR ===== --}}
                <div class="space-y-6">

                    {{-- Quick Apply Card --}}
                    <div
                        class="card-box p-6 bg-stark-white dark:bg-[#1a1c1c] sticky top-24 space-y-4">
                        <div
                            class="about-badge inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-label-bold uppercase tracking-wider">
                            <span class="material-symbols-outlined text-[14px]">bolt</span> Quick Apply
                        </div>
                        <h3 class="font-headline-md text-2xl text-deep-onyx dark:text-stark-white uppercase">Interested in this role?</h3>
                        <p class="text-secondary dark:text-stark-white/80 text-xs font-body-md">Submit your credentials directly to
                            the institutional recruitment team.</p>
                        <a href="{{ route('applications.create', $vacancy) }}"
                            class="section-primary-btn block w-full text-center py-3 font-label-bold text-xs uppercase tracking-wider rounded-full lift-hover">
                            Apply Now
                        </a>
                        <p
                            class="text-xs text-secondary dark:text-stark-white/70 text-center mt-4 flex items-center justify-center gap-1 font-label-sm">
                            <span class="material-symbols-outlined about-foundation-text text-[16px]">verified_user</span>
                            Direct &amp; Confidential
                        </p>
                    </div>

                    {{-- School Info Card --}}
                    <div
                        class="card-box p-6 bg-stark-white dark:bg-[#1a1c1c]">
                        <h3
                            class="font-headline-md text-xl text-deep-onyx dark:text-stark-white uppercase mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined about-foundation-text text-xl"
                                style="font-variation-settings:'FILL' 1;">school</span>
                            About the School
                        </h3>
                        <div class="mb-3">
                            <p class="font-label-bold text-deep-onyx dark:text-stark-white text-sm leading-snug">
                                {{ $siteSettings['site_name'] ?? 'RAZA UL ULOOM ISLAMIA HSS' }} — POONCH
                            </p>
                            <p class="text-xs text-deep-onyx dark:text-stark-white font-label-bold mt-1">
                                {{ $vacancy->school->name }}
                            </p>
                            @if($vacancy->school->city)
                                <p class="text-xs text-secondary dark:text-stark-white/70 mt-0.5">
                                    {{ $vacancy->school->city }}{{ $vacancy->school->country ? ', ' . $vacancy->school->country : '' }}
                                </p>
                            @endif
                        </div>
                        @if($vacancy->school->website)
                            <a href="{{ $vacancy->school->website }}" target="_blank" rel="noopener noreferrer"
                                class="text-xs font-label-bold about-foundation-text hover:underline flex items-center gap-1 mt-3">
                                <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                                Visit Official School Website
                            </a>
                        @endif
                    </div>

                    {{-- Share Card --}}
                    <div
                        class="card-box p-6 bg-stark-white dark:bg-[#1a1c1c]">
                        <h3 class="font-headline-md text-base text-deep-onyx dark:text-stark-white uppercase mb-4">Share This Job</h3>
                        <div class="flex gap-3">
                            <a href="https://wa.me/?text={{ urlencode($vacancy->title . ' — ' . url()->current()) }}"
                                target="_blank"
                                class="tag-chip flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl text-xs font-label-bold transition-all">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                </svg>
                                WhatsApp
                            </a>
                            <button
                                onclick="navigator.clipboard.writeText('{{ url()->current() }}').then(()=>this.textContent='Copied!')"
                                class="tag-chip flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl text-xs font-label-bold transition-all">
                                <span class="material-symbols-outlined text-[16px]">link</span>
                                Copy Link
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Mobile Fixed Bottom Apply Bar --}}
    <div class="lg:hidden fixed bottom-0 left-0 right-0 z-40 p-3.5 bg-white/95 dark:bg-[#141517]/95 backdrop-blur-xl border-t border-gray-200 dark:border-white/15 shadow-2xl flex items-center justify-between gap-3">
        <div class="min-w-0 flex-1">
            <h4 class="text-xs font-bold text-deep-onyx dark:text-white truncate">{{ $vacancy->title }}</h4>
            <p class="text-[11px] about-foundation-text font-semibold truncate">{{ $vacancy->school->name }}</p>
        </div>
        <a href="{{ route('applications.create', $vacancy) }}"
            class="section-primary-btn px-5 py-2.5 rounded-full font-label-bold text-xs uppercase tracking-wider flex items-center gap-1 shrink-0">
            <span>Apply Now</span>
            <span class="material-symbols-outlined text-sm">arrow_forward</span>
        </a>
    </div>
@endsection