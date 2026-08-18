@extends('layouts.app')

@section('title', 'Apply — ' . $vacancy->title . ' · ' . ($siteSettings['site_name'] ?? 'RAZA UL ULOOM ISLAMIA HSS'))
@section('meta_description', 'Submit your application for ' . $vacancy->title . '.')

@section('content')

    {{-- ===== HERO STRIP ===== --}}
    <section
        class="w-full bg-ghost-gray dark:bg-deep-onyx text-deep-onyx dark:text-stark-white py-10 px-margin-mobile md:px-gutter border-b border-ghost-gray dark:border-white/10">
        <div class="max-w-container-max mx-auto">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-xs font-label-bold text-secondary dark:text-secondary-fixed-dim mb-5">
                <a href="{{ route('home') }}" class="about-foundation-text hover:underline transition-colors">Home</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <a href="{{ route('vacancies.index') }}" class="about-foundation-text hover:underline transition-colors">Job
                    Directory</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <a href="{{ route('vacancies.show', $vacancy->slug) }}"
                    class="about-foundation-text hover:underline transition-colors truncate max-w-[200px] sm:max-w-xs">{{ $vacancy->title }}</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-deep-onyx dark:text-stark-white">Apply</span>
            </nav>

            {{-- Title block --}}
            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6">
                <div>
                    <h1
                        class="font-display text-3xl sm:text-4xl text-deep-onyx dark:text-stark-white uppercase tracking-tight leading-tight">
                        {{ $vacancy->title }}
                    </h1>
                    <p class="text-secondary dark:text-stark-white/80 text-xs mt-1 font-label-sm">
                        {{ $vacancy->department->name ?? '' }}
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

                {{-- Back to details button --}}
                <a href="{{ route('vacancies.show', $vacancy->slug) }}"
                    class="section-primary-btn inline-flex items-center gap-2 px-7 py-3.5 rounded-full font-label-bold text-sm uppercase tracking-wider lift-hover shrink-0">
                    <span class="material-symbols-outlined text-lg">arrow_back</span>
                    <span>Back to Job Details</span>
                </a>
            </div>
        </div>
    </section>

    {{-- ===== FORM BODY WITH CV PREVIEW STEP ===== --}}
    <div class="w-full bg-stark-white dark:bg-[#111111] py-10 px-margin-mobile md:px-gutter" x-data="{
                                                         step: 'form',
                                                         photoUrl: null,
                                                         photoError: null,
                                                         isSubmitting: false,
                                                         pincodeStatus: 'idle',
                                                         pincodeMsg: 'Enter 6-digit PIN to auto-fill City & State',
                                                         _pincodeTimer: null,
                                                         formData: {
                                                             first_name: '{{ old('first_name') }}',
                                                             last_name: '{{ old('last_name') }}',
                                                             email: '{{ old('email') }}',
                                                             phone: '{{ old('phone') }}',
                                                             whatsapp_number: '{{ old('whatsapp_number') }}',
                                                             date_of_birth: '{{ old('date_of_birth') }}',
                                                             gender: '{{ old('gender', 'male') }}',
                                                             address: '{{ old('address') }}',
                                                             city: '{{ old('city') }}',
                                                             state: '{{ old('state') }}',
                                                             country: '{{ old('country', 'India') }}',
                                                             pin_code: '{{ old('pin_code') }}',
                                                             highest_qualification: '{{ old('highest_qualification') }}',
                                                             experience_years: '{{ old('experience_years') }}',
                                                             current_employer: '{{ old('current_employer') }}',
                                                             current_salary: '{{ old('current_salary') }}',
                                                             expected_salary: '{{ old('expected_salary') }}',
                                                             notice_period: '{{ old('notice_period') }}',
                                                             skills: '{{ old('skills') }}',
                                                             languages: '{{ old('languages') }}',
                                                             cover_letter: '{{ old('cover_letter') }}',
                                                             linkedin_url: '{{ old('linkedin_url') }}'
                                                         },
                                                         handlePhotoChange(e) {
                                                             const file = e.target.files[0];
                                                             if (file) {
                                                                 if (file.size > 2 * 1024 * 1024) {
                                                                     const sizeInMb = (file.size / (1024 * 1024)).toFixed(2);
                                                                     this.photoError = `The selected photo is ${sizeInMb} MB. Maximum allowed size is 2 MB. Please choose a smaller photo.`;
                                                                     e.target.value = '';
                                                                     this.photoUrl = null;
                                                                     return;
                                                                 }
                                                                 this.photoError = null;
                                                                 this.photoUrl = URL.createObjectURL(file);
                                                             }
                                                         },
                                                         lookupPincode(pin) {
                                                             if (!/^\d{6}$/.test(pin)) return;
                                                             clearTimeout(this._pincodeTimer);
                                                             this._pincodeTimer = setTimeout(async () => {
                                                                 this.pincodeStatus = 'loading';
                                                                 this.pincodeMsg = 'Looking up pincode…';
                                                                 try {
                                                                     const res = await fetch(`https://api.postalpincode.in/pincode/${pin}`);
                                                                     const data = await res.json();
                                                                     if (data[0].Status === 'Success' && data[0].PostOffice.length) {
                                                                         const po = data[0].PostOffice[0];
                                                                         this.formData.city  = po.District || po.Block || po.Name;
                                                                         this.formData.state = po.State;
                                                                         this.formData.country = 'India';
                                                                         this.pincodeStatus = 'ok';
                                                                         this.pincodeMsg = `✓ ${po.District}, ${po.State}`;
                                                                     } else {
                                                                         this.pincodeStatus = 'error';
                                                                         this.pincodeMsg = 'PIN not found. Please fill manually.';
                                                                     }
                                                                 } catch(e) {
                                                                     this.pincodeStatus = 'error';
                                                                     this.pincodeMsg = 'Network error. Please fill manually.';
                                                                 }
                                                             }, 500);
                                                         },
                                                         generateCvPreview() {
                                                             const form = this.$refs.appForm;
                                                             if (!form.checkValidity()) {
                                                                 form.reportValidity();
                                                                 return;
                                                             }
                                                             this.step = 'preview';
                                                             window.scrollTo({ top: 100, behavior: 'smooth' });
                                                         }
                                                     }">
        <div class="max-w-4xl mx-auto space-y-6">

            {{-- Step Navigation Indicator --}}
            <div
                class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between bg-stark-white dark:bg-[#1a1c1c] border-2 border-deep-onyx/10 dark:border-white/10 rounded-2xl p-3.5 sm:p-4 shadow-xs gap-3">
                <div class="flex items-center gap-2 sm:gap-3 w-full sm:w-auto">
                    <button @click="step = 'form'" type="button"
                        :class="step === 'form' ? 'step-btn-active' : 'step-btn-inactive'"
                        class="flex-1 sm:flex-none px-3.5 sm:px-4 py-2.5 rounded-xl text-[11px] sm:text-xs uppercase font-label-bold transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                        <span>1. Application Form</span>
                    </button>

                    <span class="material-symbols-outlined text-secondary text-base shrink-0">arrow_forward</span>

                    <button @click="generateCvPreview()" type="button"
                        :class="step === 'preview' ? 'step-btn-active' : 'step-btn-inactive'"
                        class="flex-1 sm:flex-none px-3.5 sm:px-4 py-2.5 rounded-xl text-[11px] sm:text-xs uppercase font-label-bold transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                        <span>2. Preview &amp; Verification</span>
                    </button>
                </div>
                <span class="text-xs text-secondary dark:text-white/70 font-label-bold hidden sm:block"
                    x-text="step === 'form' ? 'Step 1 of 2' : 'Step 2 of 2: Final Verification'"></span>
            </div>

            {{-- Validation Errors Alert --}}
            @if($errors->any())
                <div class="bg-red-50 dark:bg-red-950/40 border-2 border-red-500/30 dark:border-red-800 p-6 rounded-3xl text-red-900 dark:text-red-200 shadow-sm">
                    <p class="font-label-bold text-sm uppercase tracking-wider flex items-center gap-2 mb-2 text-red-700 dark:text-red-400">
                        <span class="material-symbols-outlined text-xl">error</span>
                        Please check and correct the following details before submitting:
                    </p>
                    <ul class="list-disc pl-6 text-xs space-y-1 font-body-md text-red-800 dark:text-red-300">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form x-ref="appForm" action="{{ route('applications.store') }}" method="POST" enctype="multipart/form-data"
                @submit="isSubmitting = true" class="space-y-6">
                @csrf
                <input type="hidden" name="vacancy_id" value="{{ $vacancy->id }}">
                <input type="hidden" name="school_id" value="{{ $vacancy->school_id }}">

                {{-- STEP 1: APPLICATION FORM INPUTS --}}
                <div x-show="step === 'form'" class="space-y-6">

                    {{-- SECTION 1: Personal --}}
                    <div
                        class="bg-stark-white dark:bg-[#1a1c1c] rounded-3xl border-2 border-deep-onyx/10 dark:border-white/10 p-6 sm:p-8 shadow-xs">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-ghost-gray dark:border-white/10">
                            <span
                                class="w-8 h-8 rounded-xl about-badge font-headline-md text-base flex items-center justify-center shrink-0 border border-deep-onyx/20 dark:border-white/10">1</span>
                            <h2
                                class="font-headline-md text-2xl text-deep-onyx dark:text-stark-white uppercase tracking-wide">
                                Personal Information</h2>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">First
                                    Name *</label>
                                <input type="text" name="first_name" x-model="formData.first_name" required
                                    placeholder="John"
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                            </div>
                            <div>
                                <label
                                    class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Last
                                    Name *</label>
                                <input type="text" name="last_name" x-model="formData.last_name" required placeholder="Doe"
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                            </div>
                            <div>
                                <label
                                    class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Email
                                    Address *</label>
                                <input type="email" name="email" x-model="formData.email" required
                                    placeholder="john@example.com"
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                            </div>
                            <div>
                                <label
                                    class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Phone
                                    Number *</label>
                                <input type="text" name="phone" x-model="formData.phone" required
                                    placeholder="+91 98765 43210"
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label
                                        class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider">WhatsApp
                                        Number</label>
                                    <button type="button" @click="formData.whatsapp_number = formData.phone"
                                        class="text-[11px] font-label-bold about-foundation-text hover:underline flex items-center gap-1 cursor-pointer select-none">
                                        <span class="material-symbols-outlined text-[13px]">content_copy</span>
                                        Same as Phone
                                    </button>
                                </div>
                                <input type="text" name="whatsapp_number" x-model="formData.whatsapp_number"
                                    placeholder="Same as phone"
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                            </div>
                            <div>
                                <label
                                    class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Date
                                    of Birth</label>
                                <input type="date" name="date_of_birth" x-model="formData.date_of_birth"
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                            </div>

                            {{-- Gender --}}
                            <div class="sm:col-span-2 mt-2">
                                <label
                                    class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-2">Gender</label>
                                <div class="flex items-center gap-6">
                                    @foreach(['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $val => $label)
                                        <label
                                            class="flex items-center gap-2 cursor-pointer font-label-bold text-sm text-deep-onyx dark:text-stark-white">
                                            <input type="radio" name="gender" value="{{ $val }}" x-model="formData.gender"
                                                class="w-4 h-4 accent-[#21255E] dark:accent-[#d7b56d]">
                                            {{ $label }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 2: Address --}}
                    <div
                        class="bg-stark-white dark:bg-[#1a1c1c] rounded-3xl border-2 border-deep-onyx/10 dark:border-white/10 p-6 sm:p-8 shadow-xs">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-ghost-gray dark:border-white/10">
                            <span
                                class="w-8 h-8 rounded-xl about-badge font-headline-md text-base flex items-center justify-center shrink-0 border border-deep-onyx/20 dark:border-white/10">2</span>
                            <h2
                                class="font-headline-md text-2xl text-deep-onyx dark:text-stark-white uppercase tracking-wide">
                                Address Details</h2>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Full Address --}}
                            <div class="sm:col-span-2">
                                <label
                                    class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Full
                                    Address</label>
                                <textarea name="address" x-model="formData.address" rows="2"
                                    placeholder="Street address, locality, house no..."
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors resize-none"></textarea>
                            </div>

                            {{-- PIN Code --}}
                            <div class="sm:col-span-2">
                                <label
                                    class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">PIN
                                    Code</label>
                                <div class="relative">
                                    <input type="text" name="pin_code" x-model="formData.pin_code"
                                        @input="lookupPincode($event.target.value)" maxlength="6" placeholder="e.g. 185101"
                                        class="form-input-box w-full px-4 py-3 pr-12 rounded-xl font-body-md text-sm outline-none transition-colors">
                                    {{-- Spinner --}}
                                    <span x-show="pincodeStatus === 'loading'"
                                        class="absolute right-4 top-1/2 -translate-y-1/2">
                                        <svg class="animate-spin h-4 w-4 text-[#21255E] dark:text-[#d7b56d]" fill="none"
                                            viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4" />
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                        </svg>
                                    </span>
                                    {{-- Success tick --}}
                                    <span x-show="pincodeStatus === 'ok'"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 about-foundation-text font-bold text-base">&#10003;</span>
                                    {{-- Error cross --}}
                                    <span x-show="pincodeStatus === 'error'"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-red-500 font-bold text-base">&#10007;</span>
                                </div>
                                <p class="mt-1.5 text-[11px] font-label-sm" :class="{
                                                                            'about-foundation-text': pincodeStatus === 'ok',
                                                                            'text-red-400': pincodeStatus === 'error',
                                                                            'text-secondary dark:text-stark-white/50': pincodeStatus === 'idle' || pincodeStatus === 'loading'
                                                                        }" x-text="pincodeMsg"></p>
                            </div>

                            {{-- City --}}
                            <div>
                                <label
                                    class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">
                                    City
                                    <span
                                        class="ml-1 text-[10px] font-label-sm about-foundation-text normal-case tracking-normal">(auto-filled)</span>
                                </label>
                                <input type="text" name="city" x-model="formData.city" placeholder="Auto-filled from PIN"
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                            </div>

                            {{-- State --}}
                            <div>
                                <label
                                    class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">
                                    State
                                    <span
                                        class="ml-1 text-[10px] font-label-sm about-foundation-text normal-case tracking-normal">(auto-filled)</span>
                                </label>
                                <input type="text" name="state" x-model="formData.state" placeholder="Auto-filled from PIN"
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                            </div>

                            {{-- Country --}}
                            <div>
                                <label
                                    class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Country</label>
                                <input type="text" name="country" x-model="formData.country" placeholder="India"
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 3: Professional --}}
                    <div
                        class="bg-stark-white dark:bg-[#1a1c1c] rounded-3xl border-2 border-deep-onyx/10 dark:border-white/10 p-6 sm:p-8 shadow-xs">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-ghost-gray dark:border-white/10">
                            <span
                                class="w-8 h-8 rounded-xl about-badge font-headline-md text-base flex items-center justify-center shrink-0 border border-deep-onyx/20 dark:border-white/10">3</span>
                            <h2
                                class="font-headline-md text-2xl text-deep-onyx dark:text-stark-white uppercase tracking-wide">
                                Professional &amp; Academic Credentials</h2>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Highest
                                    Qualification *</label>
                                <select name="highest_qualification" x-model="formData.highest_qualification" required
                                    class="w-full px-4 py-3 rounded-xl border border-ghost-gray dark:border-white/10 bg-ghost-gray dark:bg-deep-onyx text-deep-onyx dark:text-stark-white font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d]">
                                    <option value="">Select Qualification...</option>
                                    @foreach(['High School', 'Diploma', "Bachelor's Degree", "Master's Degree", 'Ph.D', 'Other'] as $q)
                                        <option value="{{ $q }}">{{ $q }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label
                                    class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Years
                                    of Experience *</label>
                                <select name="experience_years" x-model="formData.experience_years" required
                                    class="w-full px-4 py-3 rounded-xl border border-ghost-gray dark:border-white/10 bg-ghost-gray dark:bg-deep-onyx text-deep-onyx dark:text-stark-white font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d]">
                                    <option value="">Select Experience...</option>
                                    @foreach(['Fresher', '0-1 years', '1-2 years', '2-5 years', '5-10 years', '10+ years'] as $e)
                                        <option value="{{ $e }}">{{ $e }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label
                                    class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Current
                                    Employer</label>
                                <input type="text" name="current_employer" x-model="formData.current_employer"
                                    placeholder="School/Institution name or N/A"
                                    class="w-full px-4 py-3 rounded-xl border border-ghost-gray dark:border-white/10 bg-ghost-gray dark:bg-deep-onyx text-deep-onyx dark:text-stark-white font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                            </div>
                            <div>
                                <label
                                    class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Current
                                    Salary</label>
                                <input type="text" name="current_salary" x-model="formData.current_salary"
                                    placeholder="₹ Amount / month"
                                    class="w-full px-4 py-3 rounded-xl border border-ghost-gray dark:border-white/10 bg-ghost-gray dark:bg-deep-onyx text-deep-onyx dark:text-stark-white font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                            </div>
                            <div>
                                <label
                                    class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Expected
                                    Salary</label>
                                <input type="text" name="expected_salary" x-model="formData.expected_salary"
                                    placeholder="₹ Amount / month"
                                    class="w-full px-4 py-3 rounded-xl border border-ghost-gray dark:border-white/10 bg-ghost-gray dark:bg-deep-onyx text-deep-onyx dark:text-stark-white font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                            </div>
                            <div>
                                <label
                                    class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Notice
                                    Period</label>
                                <select name="notice_period" x-model="formData.notice_period"
                                    class="w-full px-4 py-3 rounded-xl border border-ghost-gray dark:border-white/10 bg-ghost-gray dark:bg-deep-onyx text-deep-onyx dark:text-stark-white font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d]">
                                    <option value="">Select Notice Period...</option>
                                    @foreach(['Immediate', '15 days', '1 month', '2 months', '3 months'] as $n)
                                        <option value="{{ $n }}">{{ $n }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Key
                                    Skills</label>
                                <input type="text" name="skills" x-model="formData.skills"
                                    placeholder="Subject Pedagogy, Classroom Management..."
                                    class="w-full px-4 py-3 rounded-xl border border-ghost-gray dark:border-white/10 bg-ghost-gray dark:bg-deep-onyx text-deep-onyx dark:text-stark-white font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                            </div>
                            <div>
                                <label
                                    class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Languages
                                    Known</label>
                                <input type="text" name="languages" x-model="formData.languages"
                                    placeholder="English, Urdu, Hindi..."
                                    class="w-full px-4 py-3 rounded-xl border border-ghost-gray dark:border-white/10 bg-ghost-gray dark:bg-deep-onyx text-deep-onyx dark:text-stark-white font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 4: Passport Photo & Cover Letter --}}
                    <div
                        class="bg-stark-white dark:bg-[#1a1c1c] rounded-3xl border-2 border-deep-onyx/10 dark:border-white/10 p-6 sm:p-8 shadow-xs">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-ghost-gray dark:border-white/10">
                            <span
                                class="w-8 h-8 rounded-xl about-badge font-headline-md text-base flex items-center justify-center shrink-0 border border-deep-onyx/20 dark:border-white/10">4</span>
                            <h2
                                class="font-headline-md text-2xl text-deep-onyx dark:text-stark-white uppercase tracking-wide">
                                Passport Photograph &amp; Letter</h2>
                        </div>

                        <div class="grid grid-cols-1 gap-6 mb-6">
                            {{-- Passport Size Photo --}}
                            <div>
                                <label
                                    class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-2">
                                    Passport Size Photograph <span class="text-error">*</span> <span
                                        class="text-secondary font-normal">(Required &bull; Max 2 MB)</span>
                                </label>
                                <div class="flex flex-col sm:flex-row items-center gap-6">
                                    <label for="photo_file"
                                        class="flex-1 w-full flex flex-col items-center justify-center gap-2 p-6 rounded-2xl border-2 border-dashed border-ghost-gray dark:border-white/20 hover:border-[#21255E] dark:hover:border-[#d7b56d] bg-ghost-gray dark:bg-deep-onyx cursor-pointer transition-colors text-center">
                                        <span
                                            class="material-symbols-outlined about-foundation-text text-3xl">add_a_photo</span>
                                        <p class="font-label-bold text-xs text-deep-onyx dark:text-stark-white">Click to
                                            Upload Passport Photo</p>
                                        <p class="text-[11px] text-secondary font-label-sm">JPG, PNG, WEBP &bull; Max 2 MB
                                        </p>
                                    </label>
                                    <input type="file" id="photo_file" name="photo" required
                                        accept="image/jpeg,image/png,image/webp" class="hidden"
                                        @change="handlePhotoChange($event)">

                                    {{-- Photo Live Thumbnail Preview --}}
                                    <template x-if="photoUrl">
                                        <div
                                            class="w-28 h-36 rounded-2xl overflow-hidden border-2 border-[#21255E] dark:border-[#d7b56d] shadow-md shrink-0 flex flex-col items-center justify-center bg-black relative">
                                            <img :src="photoUrl" class="w-full h-full object-cover"
                                                alt="Passport Photo Preview">
                                        </div>
                                    </template>
                                </div>

                                {{-- Dynamic Client-side Size Error Banner --}}
                                <template x-if="photoError">
                                    <div class="mt-3 p-3.5 rounded-xl bg-red-50 dark:bg-red-950/50 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 text-xs font-semibold flex items-center gap-2">
                                        <span class="material-symbols-outlined text-base shrink-0">error</span>
                                        <span x-text="photoError"></span>
                                    </div>
                                </template>

                                @error('photo')
                                <p class="mt-2 text-xs text-error font-label-sm">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Cover Letter --}}
                        <div class="mb-6">
                            <label
                                class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Cover
                                Letter / Candidate Statement</label>
                            <textarea name="cover_letter" x-model="formData.cover_letter" rows="4"
                                placeholder="Tell us about your educational background and motivation for applying..."
                                class="w-full px-4 py-3 rounded-xl border border-ghost-gray dark:border-white/10 bg-ghost-gray dark:bg-deep-onyx text-deep-onyx dark:text-stark-white font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors resize-y"></textarea>
                        </div>

                        {{-- LinkedIn Profile --}}
                        <div>
                            <label
                                class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">LinkedIn
                                Profile URL</label>
                            <input type="url" name="linkedin_url" x-model="formData.linkedin_url"
                                placeholder="https://linkedin.com/in/yourprofile"
                                class="w-full px-4 py-3 rounded-xl border border-ghost-gray dark:border-white/10 bg-ghost-gray dark:bg-deep-onyx text-deep-onyx dark:text-stark-white font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                        </div>
                    </div>

                    {{-- DECLARATION & PREVIEW BUTTON --}}
                    <div
                        class="bg-stark-white dark:bg-[#1a1c1c] rounded-3xl border-2 border-deep-onyx/10 dark:border-white/10 p-6 sm:p-8 shadow-xs">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="declaration_accepted" value="1" required
                                class="w-5 h-5 mt-0.5 accent-[#21255E] dark:accent-[#d7b56d] shrink-0">
                            <span class="text-xs text-secondary dark:text-stark-white/80 font-body-md leading-relaxed">
                                I hereby declare that all details provided in this application are accurate and true to the
                                best of my knowledge. I understand that any false information may lead to disqualification.
                            </span>
                        </label>

                        <div
                            class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-ghost-gray dark:border-white/10 pt-6">
                            <button type="button" @click="generateCvPreview()"
                                class="section-primary-btn w-full sm:w-auto px-10 py-4 rounded-full font-label-bold text-sm uppercase tracking-wider lift-hover flex items-center justify-center gap-2">
                                <span>Preview Application</span>
                            </button>
                            <p class="text-xs text-secondary dark:text-stark-white/70 font-label-sm text-center">You will
                                review your complete
                                data before final submission.</p>
                        </div>
                    </div>
                </div>

                {{-- STEP 2: CANDIDATE CURRICULUM VITAE (CV) PREVIEW PAGE --}}
                <div x-show="step === 'preview'" class="space-y-6" style="display:none;">

                    {{-- Formal CV Document Container --}}
                    <div
                        class="bg-stark-white dark:bg-[#1a1c1c] rounded-3xl border-2 border-deep-onyx/20 dark:border-white/20 p-6 sm:p-10 shadow-xl space-y-8 relative overflow-hidden">

                        {{-- Watermark Banner --}}
                        <div
                            class="about-badge px-6 py-2 rounded-xl flex items-center justify-between border border-deep-onyx/20 dark:border-white/10 font-label-bold text-xs uppercase">
                            <span class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[18px]">verified</span>
                                Official Candidate Application Preview
                            </span>
                            <span class="text-[11px] opacity-80">Ref Code will be assigned on submission</span>
                        </div>

                        {{-- CV Header --}}
                        <div
                            class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 pb-6 border-b-2 border-ghost-gray dark:border-white/10">
                            <div class="flex items-center gap-6">
                                {{-- Candidate Photo --}}
                                <div
                                    class="w-28 h-36 rounded-2xl border-2 border-deep-onyx bg-ghost-gray dark:bg-deep-onyx flex flex-col items-center justify-center shrink-0 overflow-hidden shadow-md">
                                    <template x-if="photoUrl">
                                        <img :src="photoUrl" class="w-full h-full object-cover"
                                            alt="Candidate Passport Photo">
                                    </template>
                                    <template x-if="!photoUrl">
                                        <div class="text-center p-2">
                                            <span class="material-symbols-outlined text-3xl text-secondary">person</span>
                                            <p class="text-[10px] text-secondary">No Photo Selected</p>
                                        </div>
                                    </template>
                                </div>
                                <div>
                                    <h1 class="font-display text-3xl sm:text-4xl text-deep-onyx dark:text-stark-white uppercase tracking-tight"
                                        x-text="(formData.first_name || 'Candidate') + ' ' + (formData.last_name || 'Name')">
                                    </h1>
                                    <p class="about-foundation-text font-label-bold text-base mt-1 uppercase tracking-wide">
                                        Applying for: {{ $vacancy->title }}
                                    </p>
                                    <p class="text-secondary dark:text-stark-white/70 text-xs mt-0.5">
                                        Institution: {{ $siteSettings['site_name'] ?? 'RAZA UL ULOOM ISLAMIA HSS' }} —
                                        POONCH
                                    </p>
                                </div>
                            </div>

                            <div
                                class="bg-ghost-gray dark:bg-deep-onyx p-4 rounded-2xl border border-ghost-gray dark:border-white/10 shrink-0 text-xs space-y-1 font-label-sm">
                                <p class="text-secondary uppercase tracking-wider text-[10px]">Application Target</p>
                                <p class="font-bold text-deep-onyx dark:text-stark-white">
                                    {{ ucfirst(str_replace('_', ' ', $vacancy->employment_type)) }} Role
                                </p>
                                <p class="text-slate-text dark:text-stark-white/80">
                                    {{ $vacancy->location ?? 'Campus Wide' }}
                                </p>
                            </div>
                        </div>

                        {{-- Grid 1: Personal & Contact Information --}}
                        <div class="space-y-3">
                            <h3
                                class="font-headline-md text-lg text-deep-onyx dark:text-stark-white uppercase tracking-wider flex items-center gap-2">
                                <span class="material-symbols-outlined about-foundation-text">contact_page</span>
                                Contact &amp; Personal Information
                            </h3>
                            <div
                                class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-ghost-gray dark:bg-deep-onyx p-5 rounded-2xl border border-ghost-gray dark:border-white/10 text-xs">
                                <div>
                                    <span
                                        class="text-secondary font-label-sm block uppercase tracking-wider text-[10px]">Email
                                        Address</span>
                                    <strong class="text-deep-onyx dark:text-stark-white break-all"
                                        x-text="formData.email || 'N/A'"></strong>
                                </div>
                                <div>
                                    <span
                                        class="text-secondary font-label-sm block uppercase tracking-wider text-[10px]">Phone
                                        Number</span>
                                    <strong class="text-deep-onyx dark:text-stark-white"
                                        x-text="formData.phone || 'N/A'"></strong>
                                </div>
                                <div>
                                    <span
                                        class="text-secondary font-label-sm block uppercase tracking-wider text-[10px]">WhatsApp</span>
                                    <strong class="text-deep-onyx dark:text-stark-white"
                                        x-text="formData.whatsapp_number || 'Same as phone'"></strong>
                                </div>
                                <div>
                                    <span
                                        class="text-secondary font-label-sm block uppercase tracking-wider text-[10px]">Date
                                        of Birth / Gender</span>
                                    <strong class="text-deep-onyx dark:text-stark-white"
                                        x-text="(formData.date_of_birth || 'N/A') + ' (' + (formData.gender || 'male') + ')'"></strong>
                                </div>
                                <div class="col-span-2 sm:col-span-4 border-t border-slate-200 dark:border-white/10 pt-2.5">
                                    <span
                                        class="text-secondary font-label-sm block uppercase tracking-wider text-[10px]">Residential
                                        Address</span>
                                    <strong class="text-deep-onyx dark:text-stark-white"
                                        x-text="(formData.address || 'N/A') + ', ' + (formData.city || 'Poonch') + ', ' + (formData.state || 'J&K') + ' - ' + (formData.pin_code || '') + ' (' + (formData.country || 'India') + ')'"></strong>
                                </div>
                            </div>
                        </div>

                        {{-- Grid 2: Qualifications & Professional Details --}}
                        <div class="space-y-3">
                            <h3
                                class="font-headline-md text-lg text-deep-onyx dark:text-stark-white uppercase tracking-wider flex items-center gap-2">
                                <span class="material-symbols-outlined about-foundation-text">school</span>
                                Academic &amp; Professional Qualifications
                            </h3>
                            <div
                                class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-ghost-gray dark:bg-deep-onyx p-5 rounded-2xl border border-ghost-gray dark:border-white/10 text-xs">
                                <div>
                                    <span
                                        class="text-secondary font-label-sm block uppercase tracking-wider text-[10px]">Highest
                                        Qualification</span>
                                    <strong class="about-foundation-text text-sm"
                                        x-text="formData.highest_qualification || 'N/A'"></strong>
                                </div>
                                <div>
                                    <span
                                        class="text-secondary font-label-sm block uppercase tracking-wider text-[10px]">Experience
                                        Years</span>
                                    <strong class="text-deep-onyx dark:text-stark-white"
                                        x-text="formData.experience_years || 'N/A'"></strong>
                                </div>
                                <div>
                                    <span
                                        class="text-secondary font-label-sm block uppercase tracking-wider text-[10px]">Current
                                        Employer</span>
                                    <strong class="text-deep-onyx dark:text-stark-white"
                                        x-text="formData.current_employer || 'N/A'"></strong>
                                </div>
                                <div>
                                    <span
                                        class="text-secondary font-label-sm block uppercase tracking-wider text-[10px]">Notice
                                        Period</span>
                                    <strong class="text-deep-onyx dark:text-stark-white"
                                        x-text="formData.notice_period || 'N/A'"></strong>
                                </div>
                                <div class="col-span-2 sm:col-span-2 border-t border-slate-200 dark:border-white/10 pt-2.5">
                                    <span
                                        class="text-secondary font-label-sm block uppercase tracking-wider text-[10px]">Current
                                        Salary</span>
                                    <strong class="text-deep-onyx dark:text-stark-white"
                                        x-text="formData.current_salary || 'N/A'"></strong>
                                </div>
                                <div class="col-span-2 sm:col-span-2 border-t border-slate-200 dark:border-white/10 pt-2.5">
                                    <span
                                        class="text-secondary font-label-sm block uppercase tracking-wider text-[10px]">Expected
                                        Salary</span>
                                    <strong class="text-deep-onyx dark:text-stark-white"
                                        x-text="formData.expected_salary || 'N/A'"></strong>
                                </div>
                            </div>
                        </div>

                        {{-- Skills & Languages --}}
                        <div class="space-y-3">
                            <h3
                                class="font-headline-md text-lg text-deep-onyx dark:text-stark-white uppercase tracking-wider flex items-center gap-2">
                                <span class="material-symbols-outlined about-foundation-text">psychology</span>
                                Competencies &amp; Languages
                            </h3>
                            <div
                                class="grid grid-cols-1 sm:grid-cols-2 gap-3 bg-ghost-gray dark:bg-deep-onyx p-5 rounded-2xl border border-ghost-gray dark:border-white/10 text-xs">
                                <div>
                                    <span
                                        class="text-secondary font-label-sm block uppercase tracking-wider text-[10px] mb-1">Key
                                        Subject Skills</span>
                                    <p class="text-deep-onyx dark:text-stark-white font-semibold"
                                        x-text="formData.skills || 'Not specified'"></p>
                                </div>
                                <div>
                                    <span
                                        class="text-secondary font-label-sm block uppercase tracking-wider text-[10px] mb-1">Languages
                                        Spoken</span>
                                    <p class="text-deep-onyx dark:text-stark-white font-semibold"
                                        x-text="formData.languages || 'Not specified'"></p>
                                </div>
                            </div>
                        </div>

                        {{-- Cover Letter Statement --}}
                        <template x-if="formData.cover_letter">
                            <div class="space-y-2">
                                <h3
                                    class="font-headline-md text-lg text-deep-onyx dark:text-stark-white uppercase tracking-wider flex items-center gap-2">
                                    <span class="material-symbols-outlined about-foundation-text">description</span>
                                    Candidate Statement
                                </h3>
                                <div class="bg-ghost-gray dark:bg-deep-onyx p-5 rounded-2xl border border-ghost-gray dark:border-white/10 text-xs text-slate-text dark:text-stark-white/90 leading-relaxed italic"
                                    x-text="formData.cover_letter"></div>
                            </div>
                        </template>

                        {{-- Action Bar: Edit vs Submit --}}
                        <div
                            class="pt-6 border-t-2 border-ghost-gray dark:border-white/10 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <button type="button" @click="step = 'form'; window.scrollTo({ top: 100, behavior: 'smooth' });"
                                :disabled="isSubmitting"
                                class="w-full sm:w-auto bg-stark-white dark:bg-deep-onyx text-deep-onyx dark:text-stark-white border-2 border-deep-onyx dark:border-white/20 px-8 py-3.5 rounded-full font-label-bold text-xs uppercase tracking-wider flex items-center justify-center gap-2 hover:bg-ghost-gray transition-colors disabled:opacity-50 cursor-pointer">
                                <span class="material-symbols-outlined text-base">edit</span>
                                <span>Edit Information</span>
                            </button>

                            <button type="submit"
                                :disabled="isSubmitting"
                                class="section-primary-btn w-full sm:w-auto px-12 py-4 rounded-full font-label-bold text-sm uppercase tracking-wider lift-hover flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                                <span class="material-symbols-outlined text-base">send</span>
                                <span x-text="isSubmitting ? 'Submitting Application...' : 'Confirm & Final Submit Application'">Confirm &amp; Final Submit Application</span>
                            </button>
                        </div>

                    </div>
                </div>

            </form>
        </div>

        {{-- CUSTOM APPLICATION SUBMIT LOADING OVERLAY --}}
        <div x-show="isSubmitting"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            class="fixed inset-0 z-50 bg-black/75 backdrop-blur-md flex items-center justify-center p-4"
            style="display: none;">
            
            <div class="bg-stark-white dark:bg-[#1a1c1c] text-deep-onyx dark:text-stark-white rounded-3xl border-2 border-deep-onyx dark:border-white/20 p-8 sm:p-10 max-w-md w-full text-center shadow-[10px_10px_0px_0px_#171717] dark:shadow-none space-y-6 relative overflow-hidden"
                @click.stop>
                
                {{-- Top Gradient Line --}}
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-emerald-500 via-[#d7b56d] to-[#21255E] animate-pulse"></div>

                {{-- Animated Dual Spinner & Icon --}}
                <div class="relative w-20 h-20 mx-auto flex items-center justify-center">
                    <div class="absolute inset-0 rounded-full border-4 border-dashed border-[#21255E] dark:border-[#d7b56d] animate-spin"></div>
                    <div class="w-12 h-12 rounded-full bg-electric-green/20 dark:bg-electric-green/10 flex items-center justify-center animate-pulse">
                        <span class="material-symbols-outlined text-2xl text-deep-onyx dark:text-electric-green">cloud_upload</span>
                    </div>
                </div>

                {{-- Text Content --}}
                <div class="space-y-2">
                    <h3 class="font-headline-md text-2xl text-deep-onyx dark:text-stark-white uppercase tracking-tight">
                        Submitting Application
                    </h3>
                    <p class="text-xs sm:text-sm text-secondary dark:text-stark-white/70 font-body-md leading-relaxed">
                        Please hold on while we securely upload your photograph and register your application details...
                    </p>
                </div>

                {{-- Shimmer Progress Bar --}}
                <div class="w-full bg-ghost-gray dark:bg-white/10 h-2.5 rounded-full overflow-hidden relative">
                    <div class="h-full bg-gradient-to-r from-[#21255E] via-emerald-500 to-[#21255E] rounded-full w-full animate-pulse"></div>
                </div>

                {{-- Safety Alert --}}
                <p class="text-[11px] font-label-bold uppercase tracking-wider text-secondary dark:text-stark-white/50 flex items-center justify-center gap-1.5">
                    <span class="material-symbols-outlined text-[14px]">lock</span>
                    <span>Please do not refresh or close this window</span>
                </p>
            </div>
        </div>
    </div>
@endsection