@extends('layouts.app')
@section('title', 'Terms of Service')
@section('meta_description', 'Terms of service and applicant guidelines for using the career portal.')

@section('content')
    <section
        class="w-full bg-stark-white dark:bg-deep-onyx bg-checked-pattern border-b border-ghost-gray dark:border-white/10 py-14 sm:py-20 px-margin-mobile md:px-gutter">
        <div class="max-w-4xl mx-auto text-center flex flex-col items-center">
            <h1
                class="font-display text-display text-deep-onyx dark:text-stark-white uppercase tracking-tight leading-none">
                Terms of <span class="text-electric-green">Service</span>
            </h1>
            <p class="font-body-lg text-slate-text dark:text-stark-white/90 mt-4 max-w-xl mx-auto">
                Agreement guidelines and policies for candidate applications across all participating institutions.
            </p>
        </div>
    </section>

    <section class="py-14 sm:py-20 bg-stark-white dark:bg-deep-onyx bg-checked-pattern px-margin-mobile md:px-gutter">
        <div class="max-w-4xl mx-auto">
            <div
                class="bg-stark-white dark:bg-[#1a1c1c] rounded-3xl border-2 border-deep-onyx/10 dark:border-white/10 p-8 sm:p-12 shadow-xs space-y-8">
                <div>
                    <h2 class="font-headline-md text-2xl text-deep-onyx dark:text-stark-white uppercase tracking-wide mb-3">
                        1. Application Accuracy</h2>
                    <p class="font-body-md text-slate-text dark:text-secondary-fixed-dim text-sm leading-relaxed">
                        Welcome to {{ $siteSettings['site_name'] ?? 'our career portal' }}. By submitting an application
                        through our portal, you certify that all information, work history, and academic documents provided
                        are truthful and accurate. Providing misleading information may result in immediate candidate
                        disqualification.
                    </p>
                </div>

                <div class="border-t border-ghost-gray dark:border-white/10 pt-6">
                    <h2 class="font-headline-md text-2xl text-deep-onyx dark:text-stark-white uppercase tracking-wide mb-3">
                        2. Partner School Discretion</h2>
                    <p class="font-body-md text-slate-text dark:text-secondary-fixed-dim text-sm leading-relaxed">
                        Shortlisting, interviewing, and final hiring decisions rest solely with the respective partner
                        institution offering the vacancy. {{ $siteSettings['site_name'] ?? 'This portal' }} functions as the
                        recruitment facilitator.
                    </p>
                </div>

                <div class="border-t border-ghost-gray dark:border-white/10 pt-6">
                    <h2 class="font-headline-md text-2xl text-deep-onyx dark:text-stark-white uppercase tracking-wide mb-3">
                        3. Acceptable Use</h2>
                    <p class="font-body-md text-slate-text dark:text-secondary-fixed-dim text-sm leading-relaxed">
                        Applicants must not attempt to upload malicious files, exploit system forms, or submit duplicate
                        fraudulent applications.
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection