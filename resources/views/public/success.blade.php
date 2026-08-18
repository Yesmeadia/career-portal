@extends('layouts.app')
@section('title', 'Application Submitted — ' . $application->reference_no)

@section('content')
    <div class="w-full bg-background dark:bg-deep-onyx py-10 sm:py-16 px-margin-mobile md:px-gutter">
        <div class="max-w-7xl mx-auto space-y-8">

            {{-- Top Success Hero Banner --}}
            <div
                class="bg-stark-white dark:bg-[#1a1c1c] text-deep-onyx dark:text-stark-white rounded-3xl p-6 sm:p-8 border-2 border-deep-onyx dark:border-white/10 shadow-[8px_8px_0px_0px_#171717] dark:shadow-none flex flex-col sm:flex-row items-center justify-between gap-6 relative overflow-hidden">
                <div class="flex items-center gap-5 text-center sm:text-left">
                    <div>
                        <span
                            class="inline-flex items-center gap-1 bg-electric-green/20 text-deep-onyx dark:text-electric-green px-3.5 py-1 rounded-full text-xs font-label-bold uppercase tracking-wider mb-2 border border-electric-green/30">
                            Application Received
                        </span>
                        <h1
                            class="font-display text-3xl sm:text-4xl text-deep-onyx dark:text-stark-white uppercase tracking-tight leading-tight">
                            Application Submitted!
                        </h1>
                        <p class="font-body-md text-sm text-secondary dark:text-secondary-fixed-dim mt-1 leading-relaxed">
                            Your application for <strong
                                class="font-label-bold text-deep-onyx dark:text-electric-green">{{ $application->vacancy->title }}</strong>
                            at <strong
                                class="font-label-bold text-deep-onyx dark:text-electric-green">{{ $application->school->name }}</strong>
                            has been registered.
                        </p>
                    </div>
                </div>

                <div class="shrink-0 flex gap-2">
                    <a href="{{ route('applications.track', ['reference_no' => $application->reference_no]) }}"
                        class="bg-electric-green text-deep-onyx px-6 py-3 rounded-full font-label-bold text-xs uppercase tracking-wider hover:bg-primary-fixed transition-colors border-2 border-deep-onyx shadow-[4px_4px_0px_0px_#171717] flex items-center gap-2">
                        <span class="material-symbols-outlined text-base">my_location</span>
                        <span>Track Application</span>
                    </a>
                </div>
            </div>

            {{-- TWO-COLUMN LAYOUT --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                {{-- LEFT COLUMN: Application Status & Reference Card (7 cols) --}}
                <div class="lg:col-span-7 space-y-6">

                    {{-- Status & Reference Card --}}
                    <div
                        class="bg-stark-white dark:bg-[#1a1c1c] rounded-3xl border-2 border-deep-onyx dark:border-white/10 p-6 sm:p-8 shadow-[8px_8px_0px_0px_#171717] dark:shadow-none space-y-6">

                        {{-- Status Header --}}
                        <div class="flex items-center justify-between pb-4 border-b border-ghost-gray dark:border-white/10">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-electric-green text-2xl">verified</span>
                                <h2
                                    class="font-headline-md text-xl text-deep-onyx dark:text-stark-white uppercase tracking-wide">
                                    Application Status</h2>
                            </div>
                            <span
                                class="inline-flex items-center gap-1.5 bg-electric-green/20 text-deep-onyx dark:text-electric-green px-3.5 py-1.5 rounded-full text-xs font-label-bold uppercase tracking-wider border border-electric-green/30">
                                <span class="w-2 h-2 rounded-full bg-electric-green animate-pulse"></span>
                                {{ ucwords(str_replace('_', ' ', $application->status)) }}
                            </span>
                        </div>

                        {{-- Candidate Reference Code Box --}}
                        <div
                            class="rounded-2xl bg-ghost-gray dark:bg-deep-onyx border-2 border-deep-onyx/20 dark:border-white/10 p-6 text-center space-y-3"
                            x-data="{ copied: false }">
                            <span class="font-label-bold text-xs text-secondary uppercase tracking-wider block">Candidate
                                Reference Code</span>
                            <div
                                class="font-mono font-headline-md text-3xl sm:text-4xl text-deep-onyx dark:text-electric-green tracking-widest">
                                {{ $application->reference_no }}
                            </div>
                            <p class="text-xs text-secondary font-label-sm">Save this code to check real-time recruitment updates</p>

                            <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
                                <button
                                    @click="navigator.clipboard.writeText('{{ $application->reference_no }}'); copied = true; setTimeout(() => copied = false, 2500)"
                                    type="button"
                                    class="inline-flex items-center gap-2 bg-deep-onyx text-stark-white px-5 py-2.5 rounded-full font-label-bold text-xs uppercase tracking-wider hover:bg-electric-green hover:text-deep-onyx transition-colors border border-deep-onyx shadow-xs cursor-pointer">
                                    <span class="material-symbols-outlined text-base" x-text="copied ? 'check' : 'content_copy'"></span>
                                    <span x-text="copied ? 'Copied to Clipboard!' : 'Copy Code'"></span>
                                </button>
                            </div>
                        </div>

                        {{-- Candidate Details Grid --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 font-body-md text-sm text-deep-onyx dark:text-stark-white">
                            <div
                                class="p-4 rounded-2xl bg-ghost-gray dark:bg-deep-onyx border border-ghost-gray dark:border-white/10 space-y-1">
                                <p class="text-[11px] font-label-bold text-secondary dark:text-stark-white/60 uppercase">
                                    Applied Position</p>
                                <p class="font-bold truncate">{{ $application->vacancy->title }}</p>
                            </div>

                            <div
                                class="p-4 rounded-2xl bg-ghost-gray dark:bg-deep-onyx border border-ghost-gray dark:border-white/10 space-y-1">
                                <p class="text-[11px] font-label-bold text-secondary dark:text-stark-white/60 uppercase">
                                    Institution / School</p>
                                <p class="font-bold truncate">{{ $application->vacancy->school->name ?? ($application->school->name ?? 'School Campus') }}
                                </p>
                            </div>

                            <div
                                class="p-4 rounded-2xl bg-ghost-gray dark:bg-deep-onyx border border-ghost-gray dark:border-white/10 space-y-1">
                                <p class="text-[11px] font-label-bold text-secondary dark:text-stark-white/60 uppercase">
                                    Candidate Name</p>
                                <p class="font-bold truncate">{{ $application->full_name }}</p>
                            </div>

                            <div
                                class="p-4 rounded-2xl bg-ghost-gray dark:bg-deep-onyx border border-ghost-gray dark:border-white/10 space-y-1">
                                <p class="text-[11px] font-label-bold text-secondary dark:text-stark-white/60 uppercase">
                                    Contact Email</p>
                                <p class="font-bold truncate">{{ $application->email }}</p>
                            </div>

                            <div
                                class="p-4 rounded-2xl bg-ghost-gray dark:bg-deep-onyx border border-ghost-gray dark:border-white/10 space-y-1">
                                <p class="text-[11px] font-label-bold text-secondary dark:text-stark-white/60 uppercase">
                                    Contact Phone</p>
                                <p class="font-bold truncate">{{ $application->phone ?? 'Not provided' }}</p>
                            </div>

                            <div
                                class="p-4 rounded-2xl bg-ghost-gray dark:bg-deep-onyx border border-ghost-gray dark:border-white/10 space-y-1">
                                <p class="text-[11px] font-label-bold text-secondary dark:text-stark-white/60 uppercase">
                                    Submission Date</p>
                                <p class="font-bold truncate">
                                    {{ $application->created_at ? $application->created_at->format('M d, Y h:i A') : 'Just now' }}
                                </p>
                            </div>
                        </div>

                        {{-- Navigation Buttons --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2">
                            <a href="{{ route('applications.track', ['reference_no' => $application->reference_no]) }}"
                                class="bg-electric-green text-deep-onyx py-3.5 px-4 rounded-full font-label-bold text-xs uppercase tracking-wider text-center border-2 border-deep-onyx shadow-[4px_4px_0px_0px_#171717] lift-hover flex items-center justify-center gap-1.5">
                                <span class="material-symbols-outlined text-base">my_location</span>
                                <span>Track Status</span>
                            </a>
                            <a href="{{ route('vacancies.index') }}"
                                class="bg-stark-white dark:bg-deep-onyx text-deep-onyx dark:text-stark-white py-3.5 px-4 rounded-full font-label-bold text-xs uppercase tracking-wider text-center border-2 border-deep-onyx dark:border-white/20 lift-hover flex items-center justify-center gap-1.5">
                                <span class="material-symbols-outlined text-base">work</span>
                                <span>Browse Jobs</span>
                            </a>
                            <a href="{{ route('home') }}"
                                class="bg-stark-white dark:bg-deep-onyx text-deep-onyx dark:text-stark-white py-3.5 px-4 rounded-full font-label-bold text-xs uppercase tracking-wider text-center border-2 border-deep-onyx dark:border-white/20 lift-hover flex items-center justify-center gap-1.5">
                                <span class="material-symbols-outlined text-base">home</span>
                                <span>Home</span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: What Happens Next? (5 cols) --}}
                <div class="lg:col-span-5 space-y-6">

                    <div
                        class="bg-stark-white dark:bg-[#1a1c1c] rounded-3xl border-2 border-deep-onyx dark:border-white/10 p-6 sm:p-8 shadow-[8px_8px_0px_0px_#171717] dark:shadow-none space-y-6">

                        {{-- Header --}}
                        <div class="flex items-center gap-2 pb-4 border-b border-ghost-gray dark:border-white/10">
                            <span
                                class="material-symbols-outlined text-electric-green text-2xl">published_with_changes</span>
                            <h2
                                class="font-headline-md text-xl text-deep-onyx dark:text-stark-white uppercase tracking-wide">
                                What Happens Next?</h2>
                        </div>

                        {{-- Next Steps Roadmap --}}
                        <div class="space-y-4">
                            @php
                                $steps = [
                                    [
                                        'icon' => 'mark_email_read',
                                        'title' => 'Confirmation Email',
                                        'desc' => 'An automated confirmation mail with your reference code has been sent to your email.'
                                    ],
                                    [
                                        'icon' => 'find_in_page',
                                        'title' => 'Application Review',
                                        'desc' => 'The school recruitment committee evaluates your qualifications, experience, and uploaded resume.'
                                    ],
                                    [
                                        'icon' => 'support_agent',
                                        'title' => 'Shortlisting Notification',
                                        'desc' => 'If shortlisted, you will receive an email and call for initial screening or document verification.'
                                    ],
                                    [
                                        'step' => '4',
                                        'icon' => 'groups',
                                        'title' => 'Interview Scheduling',
                                        'desc' => 'Formal interview dates and venue instructions will be assigned to qualified candidates.'
                                    ],
                                ];
                            @endphp

                            @foreach($steps as $i => $step)
                                <div
                                    class="flex items-start gap-4 p-4 rounded-2xl bg-ghost-gray dark:bg-deep-onyx border border-ghost-gray dark:border-white/10">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-electric-green text-deep-onyx font-headline-md text-base flex items-center justify-center shrink-0 border border-deep-onyx shadow-xs">
                                        <span class="material-symbols-outlined text-xl">{{ $step['icon'] }}</span>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <p
                                                class="font-headline-md text-sm text-deep-onyx dark:text-stark-white uppercase tracking-wide">
                                                {{ $step['title'] }}
                                            </p>
                                        </div>
                                        <p
                                            class="text-xs text-secondary dark:text-stark-white/70 font-body-md mt-1.5 leading-relaxed">
                                            {{ $step['desc'] }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Help & Info Box --}}
                        <div
                            class="p-5 rounded-2xl bg-electric-green/10 dark:bg-electric-green/5 border border-electric-green/30 flex items-start gap-3">
                            <span class="material-symbols-outlined text-electric-green text-2xl shrink-0 mt-0.5">info</span>
                            <div>
                                <p
                                    class="font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider">
                                    Need Help with Application?</p>
                                <p
                                    class="text-xs text-secondary dark:text-stark-white/70 font-body-md mt-1 leading-relaxed">
                                    You can track status anytime or contact
                                    <strong>{{ $application->school->name }}</strong> admissions &amp; recruitment desk.
                                </p>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            {{-- Applied timestamp --}}
            <p class="text-center font-label-sm text-xs text-secondary pt-4">
                Submitted on {{ $application->created_at->format('M d, Y \a\t h:i A') }}
            </p>

        </div>
    </div>
@endsection