@extends('layouts.app')
@section('title', 'Privacy Policy')
@section('meta_description', 'Privacy policy regarding how candidate data, resumes, and personal details are handled.')

@section('content')
    <section
        class="w-full bg-stark-white dark:bg-deep-onyx bg-checked-pattern border-b border-ghost-gray dark:border-white/10 py-14 sm:py-20 px-margin-mobile md:px-gutter">
        <div class="max-w-4xl mx-auto text-center flex flex-col items-center">
            <h1
                class="font-display text-display text-deep-onyx dark:text-stark-white uppercase tracking-tight leading-none">
                Privacy <span class="text-electric-green">Policy</span>
            </h1>
            <p class="font-body-lg text-slate-text dark:text-stark-white/90 mt-4 max-w-xl mx-auto">
                How we protect, process, and safeguard applicant data across our recruitment network.
            </p>
        </div>
    </section>

    <section class="py-14 sm:py-20 bg-stark-white dark:bg-deep-onyx bg-checked-pattern px-margin-mobile md:px-gutter">
        <div class="max-w-4xl mx-auto">
            <div
                class="bg-stark-white dark:bg-[#1a1c1c] rounded-3xl border-2 border-deep-onyx/10 dark:border-white/10 p-8 sm:p-12 shadow-xs space-y-8">
                <div>
                    <h2 class="font-headline-md text-2xl text-deep-onyx dark:text-stark-white uppercase tracking-wide mb-3">
                        1. Information We Collect</h2>
                    <p class="font-body-md text-slate-text dark:text-secondary-fixed-dim text-sm leading-relaxed">
                        {{ $siteSettings['site_name'] ?? 'Our portal' }} respects your privacy and is committed to
                        protecting your personal data. We collect information provided directly by candidates including
                        name, contact details, academic background, work experience, salary expectations, and uploaded
                        resumes or profile photos.
                    </p>
                </div>

                <div class="border-t border-ghost-gray dark:border-white/10 pt-6">
                    <h2 class="font-headline-md text-2xl text-deep-onyx dark:text-stark-white uppercase tracking-wide mb-3">
                        2. Use of Data</h2>
                    <p class="font-body-md text-slate-text dark:text-secondary-fixed-dim text-sm leading-relaxed">
                        Applicant data is strictly utilized by authorized school administrators and recruiters to evaluate
                        candidate suitability, contact applicants regarding open positions, and conduct interview rounds.
                    </p>
                </div>

                <div class="border-t border-ghost-gray dark:border-white/10 pt-6">
                    <h2 class="font-headline-md text-2xl text-deep-onyx dark:text-stark-white uppercase tracking-wide mb-3">
                        3. Data Security & Storage</h2>
                    <p class="font-body-md text-slate-text dark:text-secondary-fixed-dim text-sm leading-relaxed">
                        All document uploads (CVs, profile photos) are stored securely with strict access permissions. We do
                        not sell or lease candidate information to third-party marketing companies.
                    </p>
                </div>

                <div class="border-t border-ghost-gray dark:border-white/10 pt-6">
                    <h2 class="font-headline-md text-2xl text-deep-onyx dark:text-stark-white uppercase tracking-wide mb-3">
                        4. Contact Us</h2>
                    <p class="font-body-md text-slate-text dark:text-secondary-fixed-dim text-sm leading-relaxed">
                        If you have questions regarding this privacy policy or wish to request data removal, please contact
                        our support team at <a href="mailto:support@schoolcareerportal.com"
                            class="text-electric-green font-label-bold underline">support@schoolcareerportal.com</a>.
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection