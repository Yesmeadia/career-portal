@extends('layouts.app')

@section('title', 'Track Application Status')
@section('meta_description', 'Check live application status, recruitment stage updates, and candidate details using your tracking reference code.')

@section('content')

    {{-- ===== TRACKING HERO ===== --}}
    <section
        class="w-full bg-stark-white dark:bg-black bg-checked-pattern border-b border-ghost-gray dark:border-white/10 py-14 sm:py-20 px-margin-mobile md:px-gutter">
        <div class="max-w-container-max mx-auto text-center flex flex-col items-center">
            <h1
                class="font-display text-display text-deep-onyx dark:text-stark-white uppercase tracking-tight leading-none max-w-3xl">
                Track Application <span class="about-foundation-text">Status</span>
            </h1>
            <p class="font-body-lg text-slate-text dark:text-stark-white/90 mt-4 max-w-xl">
                Enter your unique candidate reference number (e.g. <code
                    class="about-foundation-text font-label-bold bg-ghost-gray dark:bg-black px-2 py-0.5 rounded border border-deep-onyx/20 dark:border-white/20">APP-849201</code>)
                to view live recruitment updates.
            </p>

            {{-- High impact search box --}}
            <form action="{{ route('applications.track') }}" method="GET"
                class="mt-8 w-full max-w-2xl bg-stark-white dark:bg-[#1a1c1c] border-2 border-deep-onyx dark:border-white/20 rounded-3xl sm:rounded-full p-2.5 flex flex-col sm:flex-row items-center gap-2.5 shadow-[8px_8px_0px_0px_#171717] dark:shadow-none transition-all hover:shadow-[4px_4px_0px_0px_#171717]">
                <div class="flex-1 flex items-center px-4 bg-ghost-gray dark:bg-white/10 rounded-full py-2 w-full border border-transparent dark:border-white/10">
                    <span class="material-symbols-outlined text-secondary dark:text-white/60 mr-2">tag</span>
                    <input type="text" name="reference_no" value="{{ request('reference_no') }}" required
                        placeholder="Enter Reference Code (e.g. APP-849201)"
                        class="w-full bg-transparent border-none text-body-md font-label-bold text-deep-onyx dark:text-white placeholder-secondary dark:placeholder-white/60 outline-none py-1">
                </div>
                <button type="submit"
                    class="section-primary-btn w-full sm:w-auto px-8 py-3.5 rounded-full font-label-bold text-xs uppercase tracking-wider flex items-center justify-center gap-1 shrink-0">
                    <span>Check Status</span>
                    <span class="material-symbols-outlined text-lg">arrow_forward</span>
                </button>
            </form>
        </div>
    </section>

    {{-- ===== RESULTS SECTION ===== --}}
    <section
        class="py-14 sm:py-20 bg-background dark:bg-black bg-checked-pattern px-margin-mobile md:px-gutter transition-colors">
        <div class="max-w-4xl mx-auto">

            @if(request('reference_no'))
                @if(isset($application) && $application)

                    @php
                        $statusMap = [
                            'submitted' => ['label' => 'Application Submitted', 'color' => 'bg-ghost-gray dark:bg-black text-deep-onyx dark:text-electric-green border-electric-green/40', 'step' => 1],
                            'new' => ['label' => 'New Application Received', 'color' => 'bg-ghost-gray dark:bg-black text-deep-onyx dark:text-electric-green border-electric-green/40', 'step' => 1],
                            'under_review' => ['label' => 'Under Review', 'color' => 'bg-amber-500/10 dark:bg-black text-amber-700 dark:text-amber-400 border-amber-500/40', 'step' => 2],
                            'shortlisted' => ['label' => 'Shortlisted', 'color' => 'bg-teal-500/10 dark:bg-black text-teal-700 dark:text-teal-400 border-teal-500/40', 'step' => 3],
                            'interview_scheduled' => ['label' => 'Interview Scheduled', 'color' => 'bg-purple-500/10 dark:bg-black text-purple-700 dark:text-purple-400 border-purple-500/40', 'step' => 3],
                            'interview_completed' => ['label' => 'Interview Completed', 'color' => 'bg-purple-500/10 dark:bg-black text-purple-700 dark:text-purple-400 border-purple-500/40', 'step' => 3],
                            'selected' => ['label' => 'Candidate Selected', 'color' => 'bg-electric-green text-deep-onyx border-deep-onyx font-bold', 'step' => 4],
                            'hired' => ['label' => 'Appointed / Hired', 'color' => 'bg-electric-green text-deep-onyx border-deep-onyx font-bold', 'step' => 4],
                            'rejected' => ['label' => 'Not Selected', 'color' => 'bg-red-500/10 dark:bg-black text-red-700 dark:text-red-400 border-red-500/40', 'step' => 4],
                            'on_hold' => ['label' => 'On Hold', 'color' => 'bg-amber-500/10 dark:bg-black text-amber-700 dark:text-amber-400 border-amber-500/40', 'step' => 2],
                        ];

                        $currStatus = $statusMap[$application->status] ?? ['label' => strtoupper($application->status), 'color' => 'bg-ghost-gray dark:bg-black text-deep-onyx dark:text-stark-white border-ghost-gray', 'step' => 1];
                        $currStep = $currStatus['step'];
                        $isRejected = ($application->status === 'rejected');
                    @endphp

                    <div class="space-y-6">

                        {{-- Main Status Card --}}
                        <div style="background-image: none;"
                            class="bg-stark-white dark:bg-black text-deep-onyx dark:text-stark-white rounded-3xl border-2 border-deep-onyx dark:border-white/10 p-6 sm:p-10 shadow-[8px_8px_0px_0px_#171717] dark:shadow-none space-y-8">

                            {{-- Header --}}
                            <div
                                class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-black/10 dark:border-white/10">
                                <div>
                                    <h2
                                        class="font-headline-md text-2xl sm:text-3xl text-deep-onyx dark:text-stark-white uppercase tracking-tight">
                                        {{ $application->vacancy->title }}
                                    </h2>
                                </div>
                                <div class="shrink-0">
                                    <span
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full border text-xs font-label-bold uppercase tracking-wider {{ $currStatus['color'] }} shadow-xs">
                                        <span class="w-2 h-2 rounded-full bg-electric-green animate-pulse"></span>
                                        {{ $currStatus['label'] }}
                                    </span>
                                </div>
                            </div>

                            {{-- Stepper Progress --}}
                            <div class="py-2 space-y-4">
                                <div class="flex items-center justify-between">
                                    <h3
                                        class="font-label-bold text-xs text-secondary dark:text-stark-white/70 uppercase tracking-wider">
                                        Recruitment Stage Progress</h3>
                                    <span class="text-xs font-label-bold text-deep-onyx dark:text-electric-green">Stage
                                        {{ $currStep }} of 4</span>
                                </div>

                                <div class="relative grid grid-cols-4 gap-1 sm:gap-2">
                                    <div
                                        class="absolute top-4 sm:top-5 left-0 right-0 h-1.5 bg-ghost-gray dark:bg-white/10 z-0 rounded-full">
                                    </div>
                                    <div class="absolute top-4 sm:top-5 left-0 h-1.5 bg-electric-green transition-all duration-500 z-0 rounded-full"
                                        style="width: {{ $isRejected ? '100%' : (($currStep - 1) / 3 * 100) }}%"></div>

                                    {{-- Step 1 --}}
                                    <div class="relative z-10 text-center space-y-1.5">
                                        <div
                                            class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl mx-auto flex items-center justify-center font-headline-md text-xs sm:text-sm transition-all border-2 {{ $currStep >= 1 ? 'bg-electric-green dark:bg-black text-deep-onyx dark:text-electric-green border-deep-onyx dark:border-electric-green shadow-xs' : 'bg-stark-white dark:bg-black text-secondary dark:text-stark-white/40 border-ghost-gray dark:border-white/10' }}">
                                            ✓
                                        </div>
                                        <p
                                            class="text-[10px] sm:text-xs font-label-bold uppercase tracking-wider {{ $currStep >= 1 ? 'text-deep-onyx dark:text-stark-white' : 'text-secondary dark:text-stark-white/50' }}">
                                            Submitted</p>
                                    </div>

                                    {{-- Step 2 --}}
                                    <div class="relative z-10 text-center space-y-1.5">
                                        <div
                                            class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl mx-auto flex items-center justify-center font-headline-md text-xs sm:text-sm transition-all border-2 {{ $currStep >= 2 ? 'bg-electric-green dark:bg-black text-deep-onyx dark:text-electric-green border-deep-onyx dark:border-electric-green shadow-xs' : 'bg-stark-white dark:bg-black text-secondary dark:text-stark-white/40 border-ghost-gray dark:border-white/10' }}">
                                            {{ $currStep >= 2 ? '✓' : '2' }}
                                        </div>
                                        <p
                                            class="text-[10px] sm:text-xs font-label-bold uppercase tracking-wider {{ $currStep >= 2 ? 'text-deep-onyx dark:text-stark-white' : 'text-secondary dark:text-stark-white/50' }}">
                                            Review</p>
                                    </div>

                                    {{-- Step 3 --}}
                                    <div class="relative z-10 text-center space-y-1.5">
                                        <div
                                            class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl mx-auto flex items-center justify-center font-headline-md text-xs sm:text-sm transition-all border-2 {{ $currStep >= 3 ? 'bg-electric-green dark:bg-black text-deep-onyx dark:text-electric-green border-deep-onyx dark:border-electric-green shadow-xs' : 'bg-stark-white dark:bg-black text-secondary dark:text-stark-white/40 border-ghost-gray dark:border-white/10' }}">
                                            {{ $currStep >= 3 ? '✓' : '3' }}
                                        </div>
                                        <p
                                            class="text-[10px] sm:text-xs font-label-bold uppercase tracking-wider {{ $currStep >= 3 ? 'text-deep-onyx dark:text-stark-white' : 'text-secondary dark:text-stark-white/50' }}">
                                            Interview</p>
                                    </div>

                                    {{-- Step 4 --}}
                                    <div class="relative z-10 text-center space-y-1.5">
                                        <div
                                            class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl mx-auto flex items-center justify-center font-headline-md text-xs sm:text-sm transition-all border-2 {{ $isRejected ? 'bg-red-500 text-stark-white border-red-600' : ($currStep >= 4 ? 'bg-electric-green dark:bg-black text-deep-onyx dark:text-electric-green border-deep-onyx dark:border-electric-green shadow-xs' : 'bg-stark-white dark:bg-black text-secondary dark:text-stark-white/40 border-ghost-gray dark:border-white/10') }}">
                                            {{ $isRejected ? '✕' : ($currStep >= 4 ? '✓' : '4') }}
                                        </div>
                                        <p
                                            class="text-[10px] sm:text-xs font-label-bold uppercase tracking-wider {{ $isRejected ? 'text-red-500 dark:text-red-400' : ($currStep >= 4 ? 'text-deep-onyx dark:text-electric-green' : 'text-secondary dark:text-stark-white/50') }}">
                                            {{ $isRejected ? 'Outcome' : 'Decision' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Candidate Meta Details Grid --}}
                            <div class="space-y-3 pt-4 border-t border-black/10 dark:border-white/10">
                                <h3
                                    class="font-label-bold text-xs text-secondary dark:text-stark-white/70 uppercase tracking-wider">
                                    Candidate Details</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                                    <div
                                        class="p-4 rounded-2xl bg-ghost-gray dark:bg-black border border-black/10 dark:border-white/10">
                                        <span
                                            class="text-[11px] font-label-bold text-secondary dark:text-stark-white/60 uppercase tracking-wider block">Candidate
                                            Name</span>
                                        <span
                                            class="text-sm font-label-bold text-deep-onyx dark:text-stark-white mt-1 block truncate">{{ $application->full_name }}</span>
                                    </div>
                                    <div
                                        class="p-4 rounded-2xl bg-ghost-gray dark:bg-black border border-black/10 dark:border-white/10">
                                        <span
                                            class="text-[11px] font-label-bold text-secondary dark:text-stark-white/60 uppercase tracking-wider block">Reference
                                            Code</span>
                                        <span
                                            class="text-sm font-label-bold text-deep-onyx dark:text-electric-green mt-1 block truncate font-mono">{{ $application->reference_no }}</span>
                                    </div>
                                    <div
                                        class="p-4 rounded-2xl bg-ghost-gray dark:bg-black border border-black/10 dark:border-white/10">
                                        <span
                                            class="text-[11px] font-label-bold text-secondary dark:text-stark-white/60 uppercase tracking-wider block">Submitted
                                            On</span>
                                        <span
                                            class="text-sm font-label-bold text-deep-onyx dark:text-stark-white mt-1 block truncate">{{ $application->created_at->format('M d, Y') }}</span>
                                    </div>
                                    <div
                                        class="p-4 rounded-2xl bg-ghost-gray dark:bg-black border border-black/10 dark:border-white/10">
                                        <span
                                            class="text-[11px] font-label-bold text-secondary dark:text-stark-white/60 uppercase tracking-wider block">Email
                                            Address</span>
                                        <span
                                            class="text-sm font-label-bold text-deep-onyx dark:text-stark-white mt-1 block truncate"
                                            title="{{ $application->email }}">{{ $application->email }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Interview Schedule Info if Scheduled --}}
                            @php
                                $trackInterview = $application->interviews ? $application->interviews->sortByDesc('id')->first() : null;
                            @endphp
                            @if($trackInterview && in_array($application->status, ['interview_scheduled', 'interview_completed', 'selected', 'hired']))
                                <div class="pt-4 border-t border-black/10 dark:border-white/10">
                                    <div class="p-5 rounded-2xl bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800/40 space-y-3">
                                        <div class="flex items-center gap-2 text-emerald-800 dark:text-emerald-300">
                                            <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                                            <h4 class="font-label-bold text-xs uppercase tracking-wider">Interview Appointment Details</h4>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                                            <div class="p-3 bg-white dark:bg-black rounded-xl border border-emerald-100 dark:border-white/10">
                                                <span class="text-[10px] uppercase font-bold text-gray-400 dark:text-gray-500 block">Date &amp; Time</span>
                                                <span class="font-bold text-gray-900 dark:text-white mt-0.5 block">
                                                    {{ \Carbon\Carbon::parse($trackInterview->scheduled_date)->format('M d, Y') }} at {{ date('h:i A', strtotime($trackInterview->scheduled_time)) }}
                                                </span>
                                            </div>
                                            <div class="p-3 bg-white dark:bg-black rounded-xl border border-emerald-100 dark:border-white/10">
                                                <span class="text-[10px] uppercase font-bold text-gray-400 dark:text-gray-500 block">Format / Mode</span>
                                                <span class="font-bold text-gray-900 dark:text-white mt-0.5 block">
                                                    {{ ucfirst(str_replace('_', ' ', $trackInterview->location_type)) }}
                                                </span>
                                            </div>
                                            <div class="p-3 bg-white dark:bg-black rounded-xl border border-emerald-100 dark:border-white/10">
                                                <span class="text-[10px] uppercase font-bold text-gray-400 dark:text-gray-500 block">Venue / Meeting URL</span>
                                                @if(str_starts_with($trackInterview->location_address_or_link, 'http'))
                                                    <a href="{{ $trackInterview->location_address_or_link }}" target="_blank" class="text-blue-600 dark:text-blue-400 underline font-bold mt-0.5 block truncate">
                                                        {{ $trackInterview->location_address_or_link }}
                                                    </a>
                                                @else
                                                    <span class="font-bold text-gray-900 dark:text-white mt-0.5 block truncate">
                                                        {{ $trackInterview->location_address_or_link }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        @if($trackInterview->remarks)
                                            <div class="text-xs text-gray-600 dark:text-gray-300 italic pt-1">
                                                <strong>Instructions:</strong> {{ $trackInterview->remarks }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>

                @else
                    {{-- Not Found --}}
                    <div style="background-image: none;"
                        class="bg-stark-white dark:bg-black text-deep-onyx dark:text-stark-white border-2 border-deep-onyx dark:border-white/10 rounded-3xl p-10 text-center space-y-4 max-w-xl mx-auto shadow-[8px_8px_0px_0px_#171717] dark:shadow-none">
                        <span class="material-symbols-outlined text-red-500 text-5xl">warning</span>
                        <h2 class="font-headline-md text-2xl uppercase">No Matching Application Found</h2>
                        <p class="text-secondary dark:text-secondary-fixed-dim text-sm font-body-md">
                            We could not find an application record matching tracking code <span
                                class="font-mono text-red-500 font-bold">{{ request('reference_no') }}</span>.
                        </p>
                        <p class="text-xs text-secondary dark:text-stark-white/60 font-label-sm">
                            Please double check your reference code or reach out to candidate support.
                        </p>
                    </div>
                @endif
            @else
                {{-- Initial Search Prompt --}}
                <div style="background-image: none;"
                    class="bg-stark-white dark:bg-black text-deep-onyx dark:text-stark-white border-2 border-deep-onyx dark:border-white/10 rounded-3xl p-10 text-center space-y-4 max-w-xl mx-auto shadow-[8px_8px_0px_0px_#171717] dark:shadow-none">
                    <div
                        class="w-14 h-14 bg-ghost-gray dark:bg-black text-electric-green rounded-2xl flex items-center justify-center mx-auto border border-electric-green/40 shadow-xs">
                        <span class="material-symbols-outlined text-3xl text-electric-green">pageview</span>
                    </div>
                    <h3 class="font-headline-md text-2xl uppercase">Enter Your Reference Code</h3>
                    <p class="text-secondary dark:text-secondary-fixed-dim text-sm font-body-md leading-relaxed">
                        Search using the tracking reference number provided when you submitted your application (e.g. <span
                            class="font-mono font-bold text-deep-onyx dark:text-electric-green">APP-849201</span>).
                    </p>
                </div>
            @endif

        </div>
    </section>

@endsection