@extends('layouts.app')

@section('title', 'FAQ — Applicant Help Center')
@section('meta_description', 'Frequently asked questions regarding recruitment, application submission, document requirements, and status tracking.')

@section('content')

    {{-- ===== FAQ HERO ===== --}}
    <section
        class="w-full bg-stark-white dark:bg-deep-onyx bg-checked-pattern border-b border-ghost-gray dark:border-white/10 py-16 sm:py-24 px-margin-mobile md:px-gutter">
        <div class="max-w-5xl mx-auto text-center flex flex-col items-center">
            <h1
                class="font-display text-4xl sm:text-6xl text-deep-onyx dark:text-stark-white uppercase tracking-tight leading-none">
                Frequently Asked <span class="about-foundation-text">Questions</span>
            </h1>
            <p class="font-body-lg text-slate-text dark:text-stark-white/90 mt-4 max-w-2xl mx-auto">
                Find answers to common queries regarding candidate application procedures, required credentials, status
                tracking, and recruitment guidelines.
            </p>
        </div>
    </section>

    {{-- ===== FAQ CONTENT ===== --}}
    <section class="py-16 sm:py-24 bg-stark-white dark:bg-deep-onyx bg-checked-pattern px-margin-mobile md:px-gutter">
        <div class="max-w-5xl mx-auto space-y-12" x-data="{ activeIndex: 0 }">

            @php
                $faqs = [
                    [
                        'q' => 'Do I need an account to apply for open positions?',
                        'a' => 'No account registration is required! You can browse active vacancies and submit your application form directly. After submitting, a unique Reference Code (e.g. APP-849201) will be generated for tracking your application status in real-time.'
                    ],
                    [
                        'q' => 'What documents are required during application?',
                        'a' => 'You will need to upload an updated CV or Resume in PDF, DOC, or DOCX format (maximum file size 10MB). Optional cover letters, profile photos, and portfolio links can also be attached.'
                    ],
                    [
                        'q' => 'How can I check the live status of my application?',
                        'a' => 'Navigate to the "Track Status" page from the top navigation bar, enter your unique Reference Code (e.g. APP-849201), and click "Check Status" to view real-time recruitment updates.'
                    ],
                    [
                        'q' => 'Can I apply for multiple roles across different partner schools?',
                        'a' => 'Yes! You are welcome to submit separate applications for any open position matching your academic qualifications and experience across all partner institutions.'
                    ],
                    [
                        'q' => 'How will I be contacted if I am shortlisted for an interview?',
                        'a' => 'If shortlisted, you will receive an official email update as well as a direct call or interview invitation from the respective school\'s recruitment committee.'
                    ],
                    [
                        'q' => 'What should I do if I encounter technical issues while applying?',
                        'a' => 'If you face any issues during document upload or form submission, please use our Contact page or send an email directly to our candidate support desk.'
                    ],
                ];
            @endphp

            {{-- FAQ Accordion Cards Grid --}}
            <div class="grid grid-cols-1 gap-5">
                @foreach($faqs as $i => $faq)
                    <div
                        class="bg-stark-white dark:bg-[#1a1c1c] border-2 border-deep-onyx dark:border-white/20 rounded-3xl overflow-hidden shadow-[4px_4px_0px_0px_#171717] dark:shadow-none transition-all">
                        <button @click="activeIndex = (activeIndex === {{ $i }} ? null : {{ $i }})" type="button"
                            class="w-full p-6 sm:p-7 text-left font-label-bold flex items-center justify-between gap-4 hover:bg-ghost-gray dark:hover:bg-deep-onyx/80 transition-colors focus:outline-none">
                            <span
                                class="text-base sm:text-xl font-headline-md text-deep-onyx dark:text-stark-white uppercase tracking-wide flex items-center gap-4">
                                <span
                                    class="w-10 h-10 rounded-2xl about-badge font-display text-base flex items-center justify-center shrink-0 border border-deep-onyx/20 dark:border-white/10">
                                    {{ sprintf('%02d', $i + 1) }}
                                </span>
                                {{ $faq['q'] }}
                            </span>
                            <span
                                class="w-9 h-9 rounded-xl bg-ghost-gray dark:bg-white/10 text-deep-onyx dark:text-stark-white flex items-center justify-center shrink-0 border border-deep-onyx/10 dark:border-white/10 transition-transform duration-200"
                                :class="{ 'rotate-180 bg-[#21255E] text-white dark:bg-[#d7b56d] dark:text-[#171717]': activeIndex === {{ $i }} }">
                                <span class="material-symbols-outlined text-xl">expand_more</span>
                            </span>
                        </button>

                        <div x-show="activeIndex === {{ $i }}" x-transition
                            class="px-7 pb-7 pt-2 text-deep-onyx dark:text-stark-white text-base font-body-md leading-relaxed border-t border-ghost-gray dark:border-white/10 bg-stark-white dark:bg-[#161818]"
                            style="display: none;">
                            <p class="text-deep-onyx dark:text-stark-white opacity-90">{{ $faq['a'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Contact Support Box --}}
            <div
                class="card-box-green p-8 sm:p-14 text-center space-y-4">
                <span class="material-symbols-outlined text-6xl">support_agent</span>
                <h3 class="font-display text-4xl sm:text-5xl uppercase tracking-tight">Still have questions?</h3>
                <p class="font-body-md text-lg max-w-lg mx-auto opacity-90">
                    Our institutional candidate support desk is available to assist you through every step of the selection
                    process.
                </p>
                <div class="pt-4">
                    <a href="{{ route('contact') }}"
                        class="cta-btn-primary inline-flex items-center gap-2 px-9 py-4 rounded-full font-label-bold text-sm uppercase tracking-wider lift-hover transition-colors">
                        <span>Contact Support Desk</span>
                        <span class="material-symbols-outlined text-xl">arrow_forward</span>
                    </a>
                </div>
            </div>

        </div>
    </section>

@endsection