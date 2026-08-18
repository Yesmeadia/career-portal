@extends('layouts.app')

@section('title', 'Contact Us — Institutional Support Desk')
@section('meta_description', 'Get in touch with our recruitment team for queries, technical support, or applicant assistance.')

@section('content')

    {{-- ===== CONTACT HERO ===== --}}
    <section
        class="w-full bg-stark-white dark:bg-deep-onyx bg-checked-pattern border-b border-ghost-gray dark:border-white/10 py-14 sm:py-20 px-margin-mobile md:px-gutter">
        <div class="max-w-4xl mx-auto text-center flex flex-col items-center">
            <h1
                class="font-display text-display text-deep-onyx dark:text-stark-white uppercase tracking-tight leading-none">
                Get In <span class="about-foundation-text">Touch</span>
            </h1>
            <p class="font-body-lg text-slate-text dark:text-stark-white/90 mt-4 max-w-xl mx-auto">
                Have questions regarding application guidelines, vacancies, or technical support? Our institutional desk is
                here to help.
            </p>
        </div>
    </section>

    {{-- ===== CONTACT FORM & DETAILS ===== --}}
    <section class="py-14 sm:py-20 bg-stark-white dark:bg-deep-onyx bg-checked-pattern px-margin-mobile md:px-gutter">
        <div class="max-w-container-max mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

                {{-- Left Side: Information Cards (5 cols) --}}
                <div class="lg:col-span-5 space-y-6">
                    <div
                        class="bg-stark-white dark:bg-[#1a1c1c] border-2 border-deep-onyx/10 dark:border-white/10 rounded-3xl p-8 space-y-8 shadow-xs">
                        <div>
                            <h2
                                class="font-headline-md text-2xl text-deep-onyx dark:text-stark-white uppercase tracking-wide">
                                Support Channels</h2>
                            <p class="text-xs text-secondary font-label-sm mt-1">Direct communication channels for candidate
                                assistance.</p>
                        </div>

                        <div class="space-y-6">
                            {{-- Email --}}
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-12 h-12 rounded-2xl category-card-icon flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-2xl">mail</span>
                                </div>
                                <div>
                                    <h3 class="font-label-bold text-xs text-secondary uppercase tracking-wider">Email
                                        Address</h3>
                                    <a href="mailto:support@ruihss.in" class="text-base font-label-bold text-deep-onyx dark:text-stark-white hover:underline transition-colors mt-0.5 block">
                                        support@ruihss.in</a>
                                    <p class="text-xs text-secondary font-body-md">Response within 24 business hours</p>
                                </div>
                            </div>

                            {{-- Phone --}}
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-12 h-12 rounded-2xl category-card-icon flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-2xl">call</span>
                                </div>
                                <div>
                                    <h3 class="font-label-bold text-xs text-secondary uppercase tracking-wider">Helpline
                                    </h3>
                                    <a href="tel:+9118001234567" class="text-base font-label-bold text-deep-onyx dark:text-stark-white hover:underline transition-colors mt-0.5 block">+91
                                        1800-123-4567</a>
                                    <p class="text-xs text-secondary font-body-md">Monday – Friday, 9am – 3pm IST</p>
                                </div>
                            </div>

                            {{-- Location --}}
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-12 h-12 rounded-2xl category-card-icon flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-2xl">location_city</span>
                                </div>
                                <div>
                                    <h3 class="font-label-bold text-xs text-secondary uppercase tracking-wider">Office
                                    </h3>
                                    <p class="text-base font-label-bold text-deep-onyx dark:text-stark-white mt-0.5">
                                        Parade Ground, Old City, Poonch</p>
                                    <p class="text-xs text-secondary font-body-md">Jammu and Kashmir 185101</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Side: Contact Form (7 cols) --}}
                <div class="lg:col-span-7">
                    @if(session('success'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
                            class="mb-6 p-5 rounded-2xl bg-emerald-500/10 border-2 border-emerald-500/30 text-emerald-700 dark:text-emerald-300 text-sm font-label-bold flex items-start justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <span class="material-symbols-outlined text-xl shrink-0">check_circle</span>
                                <div>
                                    <p class="font-bold text-base">Message Sent Successfully!</p>
                                    <p class="font-normal text-xs mt-0.5">{{ session('success') }}</p>
                                </div>
                            </div>
                            <button type="button" @click="show = false" class="text-emerald-700 dark:text-emerald-300 opacity-70 hover:opacity-100 transition-opacity">
                                <span class="material-symbols-outlined text-lg">close</span>
                            </button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 p-5 rounded-2xl bg-red-500/10 border-2 border-red-500/30 text-red-700 dark:text-red-300 text-sm font-label-bold space-y-1">
                            <p class="font-bold flex items-center gap-2">
                                <span class="material-symbols-outlined text-xl">error</span>
                                Please fix the following errors:
                            </p>
                            <ul class="list-disc pl-6 text-xs font-normal">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST"
                        class="bg-stark-white dark:bg-[#1a1c1c] border-2 border-deep-onyx/10 dark:border-white/10 rounded-3xl p-8 sm:p-10 space-y-6 shadow-xs">
                        @csrf

                        <div>
                            <h2
                                class="font-headline-md text-3xl text-deep-onyx dark:text-stark-white uppercase tracking-tight">
                                Send a Message</h2>
                            <p class="text-xs text-secondary font-label-sm mt-1">Fill out the details below and our
                                recruitment team will get back to you.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Name --}}
                            <div>
                                <label
                                    class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Your
                                    Name *</label>
                                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Full Name"
                                    class="w-full px-4 py-3 rounded-xl border border-ghost-gray dark:border-white/10 bg-ghost-gray dark:bg-deep-onyx text-deep-onyx dark:text-stark-white font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                            </div>

                            {{-- Email --}}
                            <div>
                                <label
                                    class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Your
                                    Email *</label>
                                <input type="email" name="email" value="{{ old('email') }}" required placeholder="name@domain.com"
                                    class="w-full px-4 py-3 rounded-xl border border-ghost-gray dark:border-white/10 bg-ghost-gray dark:bg-deep-onyx text-deep-onyx dark:text-stark-white font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Phone --}}
                            <div>
                                <label
                                    class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Phone
                                    Number</label>
                                <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+91 98765 43210"
                                    class="w-full px-4 py-3 rounded-xl border border-ghost-gray dark:border-white/10 bg-ghost-gray dark:bg-deep-onyx text-deep-onyx dark:text-stark-white font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                            </div>

                            {{-- Subject --}}
                            <div>
                                <label
                                    class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Subject
                                    *</label>
                                <select name="subject" required
                                    class="w-full px-4 py-3 rounded-xl border border-ghost-gray dark:border-white/10 bg-ghost-gray dark:bg-deep-onyx text-deep-onyx dark:text-stark-white font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d]">
                                    <option value="">Select subject...</option>
                                    <option value="Application Status Query" {{ old('subject') == 'Application Status Query' ? 'selected' : '' }}>Application Status Query</option>
                                    <option value="Vacancy Information" {{ old('subject') == 'Vacancy Information' ? 'selected' : '' }}>Vacancy Information</option>
                                    <option value="Technical Support" {{ old('subject') == 'Technical Support' ? 'selected' : '' }}>Technical Support</option>
                                    <option value="General Inquiry" {{ old('subject') == 'General Inquiry' ? 'selected' : '' }}>General Inquiry</option>
                                </select>
                            </div>
                        </div>

                        {{-- Reference Code --}}
                        <div>
                            <label
                                class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Reference
                                Code <span class="text-secondary font-normal lowercase">(optional)</span></label>
                            <input type="text" name="reference_no" value="{{ old('reference_no') }}" placeholder="e.g. APP-849201"
                                class="w-full px-4 py-3 rounded-xl border border-ghost-gray dark:border-white/10 bg-ghost-gray dark:bg-deep-onyx text-deep-onyx dark:text-stark-white font-mono text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                        </div>

                        {{-- Message --}}
                        <div>
                            <label
                                class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Message
                                *</label>
                            <textarea name="message" rows="4" required placeholder="Describe your query in detail..."
                                class="w-full px-4 py-3 rounded-xl border border-ghost-gray dark:border-white/10 bg-ghost-gray dark:bg-deep-onyx text-deep-onyx dark:text-stark-white font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors resize-none">{{ old('message') }}</textarea>
                        </div>

                        {{-- Submit --}}
                        <button type="submit"
                            class="section-primary-btn w-full py-4 rounded-full font-label-bold text-sm uppercase tracking-wider lift-hover flex items-center justify-center gap-2">
                            <span>Send Message</span>
                            <span class="material-symbols-outlined text-lg">send</span>
                        </button>

                    </form>
                </div>

            </div>
        </div>
    </section>
@endsection