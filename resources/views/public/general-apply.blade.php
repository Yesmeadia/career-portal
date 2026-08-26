@extends('layouts.app')

@section('title', 'General Application · ' . ($siteSettings['site_name'] ?? 'RAZA UL ULOOM ISLAMIA HSS'))
@section('meta_description', 'Submit a general application to join our institutional talent pool for upcoming academic, administrative, and leadership opportunities.')

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
                <span class="text-deep-onyx dark:text-stark-white">General Application</span>
            </nav>

            {{-- Title block --}}
            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-electric-green/15 text-deep-onyx dark:text-electric-green text-xs font-label-bold uppercase tracking-wider mb-3">
                        <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">stars</span>
                        <span>Open Talent Pool</span>
                    </div>
                    <h1 class="font-display text-3xl sm:text-4xl text-deep-onyx dark:text-stark-white uppercase tracking-tight leading-tight">
                        General Application
                    </h1>
                    <p class="text-secondary dark:text-stark-white/80 text-sm mt-2 max-w-2xl font-body-md">
                        Can't find a specific opening that matches your profile? Submit your credentials to our candidate database. Our recruitment team reviews general submissions for upcoming academic and administrative roles across all partner campuses.
                    </p>
                </div>

                {{-- Back to jobs button --}}
                <a href="{{ route('vacancies.index') }}"
                    class="section-primary-btn inline-flex items-center gap-2 px-7 py-3.5 rounded-full font-label-bold text-sm uppercase tracking-wider lift-hover shrink-0">
                    <span class="material-symbols-outlined text-lg">search</span>
                    <span>Browse Openings</span>
                </a>
            </div>
        </div>
    </section>

    {{-- ===== FORM BODY WITH INTERACTIVE CV PREVIEW ===== --}}
    <div class="w-full bg-stark-white dark:bg-[#111111] py-10 px-margin-mobile md:px-gutter" x-data="{
        step: 'form',
        photoUrl: null,
        photoError: null,
        resumeName: null,
        isSubmitting: false,
        pincodeStatus: 'idle',
        pincodeMsg: 'Enter 6-digit PIN to auto-fill City & State',
        _pincodeTimer: null,
        formData: {
            school_id: '{{ old('school_id', $selectedSchoolId ?? '') }}',
            preferred_position: '{{ old('preferred_position') }}',
            preferred_department: '{{ old('preferred_department') }}',
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
            portfolio_url: '{{ old('portfolio_url') }}',
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
        handleResumeChange(e) {
            const file = e.target.files[0];
            if (file) {
                this.resumeName = file.name;
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
            const form = this.$refs.generalAppForm;
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
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form x-ref="generalAppForm" action="{{ route('applications.store-general') }}" method="POST" enctype="multipart/form-data"
                @submit="isSubmitting = true" class="space-y-6">
                @csrf

                {{-- STEP 1: APPLICATION FORM INPUTS --}}
                <div x-show="step === 'form'" class="space-y-6">

                    {{-- SECTION 1: Preferences & Campus --}}
                    <div class="bg-stark-white dark:bg-[#1a1c1c] rounded-3xl border-2 border-deep-onyx/10 dark:border-white/10 p-6 sm:p-8 shadow-xs">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-ghost-gray dark:border-white/10">
                            <span class="w-8 h-8 rounded-xl about-badge font-headline-md text-base flex items-center justify-center shrink-0 border border-deep-onyx/20 dark:border-white/10">1</span>
                            <div>
                                <h2 class="font-headline-md text-2xl text-deep-onyx dark:text-stark-white uppercase tracking-wide">
                                    Placement &amp; Role Preferences
                                </h2>
                                <p class="text-xs text-secondary dark:text-stark-white/70 mt-0.5">Specify your preferred campus, discipline, or desired position.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Preferred Campus --}}
                            <div>
                                <label class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">
                                    Preferred Campus / Institution
                                </label>
                                <select name="school_id" x-model="formData.school_id"
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                                    <option value="">Any Campus / Central Institutional Pool</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}">
                                            {{ $school->name }}{{ $school->city ? ' (' . $school->city . ')' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-[11px] text-secondary dark:text-stark-white/60 mt-1">Leave as "Any Campus" for consideration across all institutional units.</p>
                            </div>

                            {{-- Preferred Department / Stream --}}
                            <div>
                                <label class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">
                                    Preferred Department / Domain
                                </label>
                                <select name="preferred_department" x-model="formData.preferred_department"
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                                    <option value="">General / Any Department</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->name }}">{{ $dept->name }}</option>
                                    @endforeach
                                    <option value="Administration & Operations">Administration &amp; Operations</option>
                                    <option value="Information Technology & Systems">Information Technology &amp; Systems</option>
                                    <option value="Finance & Accounts">Finance &amp; Accounts</option>
                                    <option value="Student Counseling & Support">Student Counseling &amp; Support</option>
                                </select>
                            </div>

                            {{-- Desired Position / Role Title --}}
                            <div class="sm:col-span-2">
                                <label class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">
                                    Target Position / Role of Interest
                                </label>
                                <input type="text" name="preferred_position" x-model="formData.preferred_position"
                                    placeholder="e.g. Mathematics PGT Teacher, Office Administrator, IT Instructor, Vice Principal"
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                                
                                {{-- Quick Suggestion Chips --}}
                                <div class="flex items-center gap-1.5 mt-2.5 flex-wrap">
                                    <span class="text-[11px] text-secondary dark:text-stark-white/70 font-semibold mr-1">Suggestions:</span>
                                    @foreach(['PGT / TGT Teacher', 'Primary Educator', 'Lab Instructor', 'Administrative Officer', 'Accountant', 'Librarian'] as $suggestion)
                                        <button type="button" @click="formData.preferred_position = '{{ $suggestion }}'"
                                            class="text-[11px] px-2.5 py-1 rounded-full bg-ghost-gray dark:bg-white/10 text-deep-onyx dark:text-stark-white hover:bg-electric-green hover:text-white transition-all font-label-sm">
                                            + {{ $suggestion }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 2: Personal Information --}}
                    <div class="bg-stark-white dark:bg-[#1a1c1c] rounded-3xl border-2 border-deep-onyx/10 dark:border-white/10 p-6 sm:p-8 shadow-xs">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-ghost-gray dark:border-white/10">
                            <span class="w-8 h-8 rounded-xl about-badge font-headline-md text-base flex items-center justify-center shrink-0 border border-deep-onyx/20 dark:border-white/10">2</span>
                            <h2 class="font-headline-md text-2xl text-deep-onyx dark:text-stark-white uppercase tracking-wide">
                                Personal Information
                            </h2>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">First Name *</label>
                                <input type="text" name="first_name" x-model="formData.first_name" required placeholder="John"
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                            </div>
                            <div>
                                <label class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Last Name *</label>
                                <input type="text" name="last_name" x-model="formData.last_name" required placeholder="Doe"
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                            </div>
                            <div>
                                <label class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Email Address *</label>
                                <input type="email" name="email" x-model="formData.email" required placeholder="john@example.com"
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                            </div>
                            <div>
                                <label class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Phone Number *</label>
                                <input type="text" name="phone" x-model="formData.phone" required placeholder="+91 98765 43210"
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider">WhatsApp Number</label>
                                    <button type="button" @click="formData.whatsapp_number = formData.phone"
                                        class="text-[11px] font-label-bold about-foundation-text hover:underline flex items-center gap-1 cursor-pointer select-none">
                                        <span class="material-symbols-outlined text-[13px]">content_copy</span> Same as Phone
                                    </button>
                                </div>
                                <input type="text" name="whatsapp_number" x-model="formData.whatsapp_number" placeholder="+91 98765 43210"
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                            </div>
                            <div>
                                <label class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Date of Birth</label>
                                <input type="date" name="date_of_birth" x-model="formData.date_of_birth" max="{{ date('Y-m-d', strtotime('-18 years')) }}"
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                            </div>
                            <div>
                                <label class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Gender</label>
                                <select name="gender" x-model="formData.gender"
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other / Prefer not to say</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">PIN / Postal Code</label>
                                <input type="text" name="pin_code" x-model="formData.pin_code" @input="lookupPincode($event.target.value)"
                                    placeholder="6-digit PIN (e.g. 192101)" maxlength="6"
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                                <p class="text-[11px] mt-1 flex items-center gap-1 font-body-md"
                                    :class="{
                                        'text-secondary dark:text-white/60': pincodeStatus === 'idle',
                                        'text-[#21255E] dark:text-[#d7b56d]': pincodeStatus === 'loading',
                                        'text-green-700 dark:text-green-400 font-semibold': pincodeStatus === 'ok',
                                        'text-amber-700 dark:text-amber-400': pincodeStatus === 'error'
                                    }">
                                    <span class="material-symbols-outlined text-[13px]" x-show="pincodeStatus === 'loading'">sync</span>
                                    <span class="material-symbols-outlined text-[13px]" x-show="pincodeStatus === 'ok'">check_circle</span>
                                    <span x-text="pincodeMsg"></span>
                                </p>
                            </div>
                            <div>
                                <label class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">City / District</label>
                                <input type="text" name="city" x-model="formData.city" placeholder="City name"
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                            </div>
                            <div>
                                <label class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">State / UT</label>
                                <input type="text" name="state" x-model="formData.state" placeholder="State or UT"
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Residential Address</label>
                                <input type="text" name="address" x-model="formData.address" placeholder="House/Flat No., Street, Landmark"
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 3: Academic & Professional Credentials --}}
                    <div class="bg-stark-white dark:bg-[#1a1c1c] rounded-3xl border-2 border-deep-onyx/10 dark:border-white/10 p-6 sm:p-8 shadow-xs">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-ghost-gray dark:border-white/10">
                            <span class="w-8 h-8 rounded-xl about-badge font-headline-md text-base flex items-center justify-center shrink-0 border border-deep-onyx/20 dark:border-white/10">3</span>
                            <h2 class="font-headline-md text-2xl text-deep-onyx dark:text-stark-white uppercase tracking-wide">
                                Academic &amp; Professional Background
                            </h2>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Highest Qualification *</label>
                                <input type="text" name="highest_qualification" x-model="formData.highest_qualification" required
                                    placeholder="e.g. M.Sc. Physics, M.A. English, B.Ed., MBA, MCA"
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                            </div>
                            <div>
                                <label class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Total Experience *</label>
                                <select name="experience_years" x-model="formData.experience_years" required
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                                    <option value="">Select Experience Bracket</option>
                                    <option value="0-1 years (Fresher)">0–1 years (Fresher)</option>
                                    <option value="1-3 years">1–3 years</option>
                                    <option value="3-5 years">3–5 years</option>
                                    <option value="5-8 years">5–8 years</option>
                                    <option value="8-12 years">8–12 years</option>
                                    <option value="12+ years (Senior / Leadership)">12+ years (Senior / Leadership)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Current / Most Recent Employer</label>
                                <input type="text" name="current_employer" x-model="formData.current_employer" placeholder="School, College, Company, or Freelance"
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                            </div>
                            <div>
                                <label class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Notice Period</label>
                                <select name="notice_period" x-model="formData.notice_period"
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                                    <option value="Immediate Joiner">Immediate Joiner</option>
                                    <option value="15 Days">15 Days</option>
                                    <option value="1 Month">1 Month</option>
                                    <option value="2 Months">2 Months</option>
                                    <option value="3 Months">3 Months</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Current Monthly Salary (₹)</label>
                                <input type="text" name="current_salary" x-model="formData.current_salary" placeholder="e.g. 35,000 / month"
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                            </div>
                            <div>
                                <label class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Expected Monthly Salary (₹)</label>
                                <input type="text" name="expected_salary" x-model="formData.expected_salary" placeholder="e.g. 45,000 / month"
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Key Skills &amp; Subject Specializations</label>
                                <input type="text" name="skills" x-model="formData.skills" placeholder="e.g. CBSE Curriculum, Physics, Classroom Management, Smart Board, Lesson Planning, Python"
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Languages Known</label>
                                <input type="text" name="languages" x-model="formData.languages" placeholder="e.g. English, Urdu, Hindi, Kashmiri, Arabic"
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 4: Uploads & Candidate Statement --}}
                    <div class="bg-stark-white dark:bg-[#1a1c1c] rounded-3xl border-2 border-deep-onyx/10 dark:border-white/10 p-6 sm:p-8 shadow-xs">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-ghost-gray dark:border-white/10">
                            <span class="w-8 h-8 rounded-xl about-badge font-headline-md text-base flex items-center justify-center shrink-0 border border-deep-onyx/20 dark:border-white/10">4</span>
                            <h2 class="font-headline-md text-2xl text-deep-onyx dark:text-stark-white uppercase tracking-wide">
                                Documents &amp; Statement
                            </h2>
                        </div>

                        <div class="space-y-5">
                            {{-- Photo & Resume Grid --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                {{-- Passport Photo --}}
                                <div>
                                    <label class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">
                                        Passport Size Photo * <span class="text-secondary font-normal">(Max 2MB, JPG/PNG)</span>
                                    </label>
                                    <div class="flex items-center gap-4">
                                        <div class="w-20 h-20 rounded-2xl bg-ghost-gray dark:bg-white/10 border-2 border-dashed border-deep-onyx/20 dark:border-white/20 flex items-center justify-center overflow-hidden shrink-0">
                                            <template x-if="photoUrl">
                                                <img :src="photoUrl" alt="Photo Preview" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!photoUrl">
                                                <span class="material-symbols-outlined text-secondary text-2xl">account_circle</span>
                                            </template>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <input type="file" name="photo" @change="handlePhotoChange($event)" required accept="image/*"
                                                class="file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-label-bold file:bg-[#21255E] dark:file:bg-[#d7b56d] file:text-white dark:file:text-deep-onyx hover:file:opacity-90 cursor-pointer text-xs text-secondary dark:text-stark-white/70 w-full">
                                            <p x-show="photoError" x-text="photoError" class="text-xs text-red-600 dark:text-red-400 font-semibold mt-1"></p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Resume / CV --}}
                                <div>
                                    <label class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">
                                        Resume / Curriculum Vitae <span class="text-secondary font-normal">(PDF/DOC, Max 5MB)</span>
                                    </label>
                                    <div class="mt-2">
                                        <input type="file" name="resume" @change="handleResumeChange($event)" accept=".pdf,.doc,.docx"
                                            class="file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-label-bold file:bg-[#21255E] dark:file:bg-[#d7b56d] file:text-white dark:file:text-deep-onyx hover:file:opacity-90 cursor-pointer text-xs text-secondary dark:text-stark-white/70 w-full">
                                        <p x-show="resumeName" x-text="'Selected: ' + resumeName" class="text-xs text-green-600 dark:text-green-400 font-semibold mt-1"></p>
                                    </div>
                                </div>
                            </div>

                            {{-- Social Links --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                                <div>
                                    <label class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">LinkedIn Profile URL</label>
                                    <input type="url" name="linkedin_url" x-model="formData.linkedin_url" placeholder="https://linkedin.com/in/username"
                                        class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                                </div>
                                <div>
                                    <label class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">Portfolio / Website URL</label>
                                    <input type="url" name="portfolio_url" x-model="formData.portfolio_url" placeholder="https://yourportfolio.com"
                                        class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors">
                                </div>
                            </div>

                            {{-- Candidate Statement / Motivation --}}
                            <div>
                                <label class="block font-label-bold text-xs text-deep-onyx dark:text-stark-white uppercase tracking-wider mb-1.5">
                                    Candidate Statement / Introduction <span class="text-secondary font-normal">(Optional)</span>
                                </label>
                                <textarea name="cover_letter" x-model="formData.cover_letter" rows="4"
                                    placeholder="Briefly introduce yourself, your teaching/work philosophy, or specific campus interests..."
                                    class="form-input-box w-full px-4 py-3 rounded-xl font-body-md text-sm outline-none focus:border-[#21255E] dark:focus:border-[#d7b56d] transition-colors"></textarea>
                            </div>

                            {{-- Declaration Checkbox --}}
                            <div class="pt-2 border-t border-ghost-gray dark:border-white/10">
                                <label class="flex items-start gap-3 cursor-pointer select-none">
                                    <input type="checkbox" name="declaration_accepted" value="1" required checked
                                        class="mt-1 w-4 h-4 rounded text-electric-green focus:ring-electric-green border-gray-300">
                                    <span class="text-xs text-slate-text dark:text-stark-white/90 font-body-md leading-relaxed">
                                        I hereby declare that all the information provided in this general application is true, complete, and correct to the best of my knowledge. I understand that any false statements or omissions may disqualify me from recruitment across all institutional campuses.
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Bottom Continue to Preview Action --}}
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
                        <a href="{{ route('vacancies.index') }}" class="text-xs font-label-bold text-secondary dark:text-white/70 hover:underline flex items-center gap-1">
                            <span class="material-symbols-outlined text-base">arrow_back</span>
                            Back to Job Directory
                        </a>

                        <button type="button" @click="generateCvPreview()"
                            class="section-primary-btn w-full sm:w-auto px-10 py-4 rounded-full font-label-bold text-sm uppercase tracking-wider lift-hover flex items-center justify-center gap-2 cursor-pointer shadow-lg">
                            <span>Proceed to Verification &amp; Preview</span>
                            <span class="material-symbols-outlined text-lg">arrow_forward</span>
                        </button>
                    </div>
                </div>

                {{-- STEP 2: INTERACTIVE CV VERIFICATION & PREVIEW --}}
                <div x-show="step === 'preview'" class="space-y-6" style="display: none;">
                    <div class="bg-stark-white dark:bg-[#1a1c1c] rounded-3xl border-2 border-deep-onyx/10 dark:border-white/10 p-6 sm:p-10 shadow-lg space-y-8">
                        
                        {{-- Preview Header --}}
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-6 border-b border-ghost-gray dark:border-white/10">
                            <div>
                                <span class="badge-pill inline-block font-label-sm text-xs px-3 py-1 rounded-full uppercase tracking-wider mb-2">
                                    Candidate Profile Preview
                                </span>
                                <h2 class="font-headline-md text-2xl sm:text-3xl text-deep-onyx dark:text-stark-white uppercase">
                                    Application Summary
                                </h2>
                                <p class="text-xs text-secondary dark:text-stark-white/70 mt-1">Please review all submitted information carefully before final submission.</p>
                            </div>

                            <button type="button" @click="step = 'form'"
                                class="px-5 py-2.5 rounded-full border border-deep-onyx/20 dark:border-white/20 text-xs font-label-bold uppercase tracking-wider hover:bg-ghost-gray dark:hover:bg-white/10 transition-colors flex items-center gap-1 cursor-pointer">
                                <span class="material-symbols-outlined text-sm">edit</span>
                                <span>Edit Information</span>
                            </button>
                        </div>

                        {{-- Candidate Snapshot Card --}}
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 bg-ghost-gray dark:bg-white/5 p-6 rounded-2xl border border-deep-onyx/10 dark:border-white/10">
                            <div class="w-24 h-24 rounded-2xl bg-stark-white dark:bg-white/10 border-2 border-deep-onyx/10 dark:border-white/10 overflow-hidden shrink-0 shadow-sm flex items-center justify-center">
                                <template x-if="photoUrl">
                                    <img :src="photoUrl" alt="Photo" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!photoUrl">
                                    <span class="material-symbols-outlined text-4xl text-secondary">person</span>
                                </template>
                            </div>
                            <div class="min-w-0 flex-1 text-center sm:text-left">
                                <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                    <h3 class="font-headline-md text-2xl text-deep-onyx dark:text-stark-white" x-text="(formData.first_name || 'Candidate') + ' ' + (formData.last_name || '')"></h3>
                                    <span class="about-badge inline-block text-[11px] font-label-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider w-max mx-auto sm:mx-0">
                                        General Application
                                    </span>
                                </div>
                                <p class="text-xs font-label-bold about-foundation-text mt-1" x-text="formData.preferred_position ? 'Target Role: ' + formData.preferred_position : 'Open Institutional Placement'"></p>
                                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3 mt-3 text-xs text-secondary dark:text-stark-white/80 font-body-md">
                                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">mail</span><span x-text="formData.email"></span></span>
                                    <span>•</span>
                                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">phone</span><span x-text="formData.phone"></span></span>
                                    <span x-show="formData.city">•</span>
                                    <span x-show="formData.city" class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">location_on</span><span x-text="formData.city + (formData.state ? ', ' + formData.state : '')"></span></span>
                                </div>
                            </div>
                        </div>

                        {{-- Details Grid --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                            <div class="space-y-4">
                                <h4 class="font-headline-md text-lg text-deep-onyx dark:text-stark-white uppercase border-b border-ghost-gray dark:border-white/10 pb-2 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-base about-foundation-text">school</span>
                                    Qualifications &amp; Experience
                                </h4>
                                <div class="space-y-2 text-xs">
                                    <div class="flex justify-between py-1 border-b border-ghost-gray/50 dark:border-white/5">
                                        <span class="text-secondary dark:text-stark-white/60">Highest Qualification:</span>
                                        <span class="font-bold text-deep-onyx dark:text-stark-white" x-text="formData.highest_qualification || '—'"></span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b border-ghost-gray/50 dark:border-white/5">
                                        <span class="text-secondary dark:text-stark-white/60">Total Experience:</span>
                                        <span class="font-bold text-deep-onyx dark:text-stark-white" x-text="formData.experience_years || '—'"></span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b border-ghost-gray/50 dark:border-white/5">
                                        <span class="text-secondary dark:text-stark-white/60">Current Employer:</span>
                                        <span class="font-bold text-deep-onyx dark:text-stark-white" x-text="formData.current_employer || 'None / Fresher'"></span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b border-ghost-gray/50 dark:border-white/5">
                                        <span class="text-secondary dark:text-stark-white/60">Notice Period:</span>
                                        <span class="font-bold text-deep-onyx dark:text-stark-white" x-text="formData.notice_period || '—'"></span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b border-ghost-gray/50 dark:border-white/5">
                                        <span class="text-secondary dark:text-stark-white/60">Expected Salary:</span>
                                        <span class="font-bold text-deep-onyx dark:text-stark-white" x-text="formData.expected_salary || 'Negotiable'"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h4 class="font-headline-md text-lg text-deep-onyx dark:text-stark-white uppercase border-b border-ghost-gray dark:border-white/10 pb-2 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-base about-foundation-text">psychology</span>
                                    Skills &amp; Preferences
                                </h4>
                                <div class="space-y-2 text-xs">
                                    <div class="flex justify-between py-1 border-b border-ghost-gray/50 dark:border-white/5">
                                        <span class="text-secondary dark:text-stark-white/60">Key Skills:</span>
                                        <span class="font-bold text-deep-onyx dark:text-stark-white text-right max-w-[200px] truncate" x-text="formData.skills || '—'"></span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b border-ghost-gray/50 dark:border-white/5">
                                        <span class="text-secondary dark:text-stark-white/60">Languages:</span>
                                        <span class="font-bold text-deep-onyx dark:text-stark-white" x-text="formData.languages || '—'"></span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b border-ghost-gray/50 dark:border-white/5">
                                        <span class="text-secondary dark:text-stark-white/60">Preferred Domain:</span>
                                        <span class="font-bold text-deep-onyx dark:text-stark-white" x-text="formData.preferred_department || 'General'"></span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b border-ghost-gray/50 dark:border-white/5">
                                        <span class="text-secondary dark:text-stark-white/60">Resume Attached:</span>
                                        <span class="font-bold text-green-600 dark:text-green-400" x-text="resumeName ? '✓ ' + resumeName : 'Not uploaded'"></span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b border-ghost-gray/50 dark:border-white/5">
                                        <span class="text-secondary dark:text-stark-white/60">Declaration:</span>
                                        <span class="font-bold text-green-600 dark:text-green-400">✓ Accepted</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Statement Preview --}}
                        <div x-show="formData.cover_letter" class="bg-ghost-gray dark:bg-white/5 p-5 rounded-2xl border border-deep-onyx/10 dark:border-white/10">
                            <h4 class="font-label-bold text-xs uppercase tracking-wider text-secondary dark:text-stark-white/70 mb-2">Statement of Interest</h4>
                            <p class="text-xs text-slate-text dark:text-stark-white/90 font-body-md whitespace-pre-line leading-relaxed" x-text="formData.cover_letter"></p>
                        </div>

                        {{-- Bottom Final Submit Buttons --}}
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-ghost-gray dark:border-white/10">
                            <button type="button" @click="step = 'form'"
                                class="w-full sm:w-auto px-6 py-3.5 rounded-full border-2 border-deep-onyx/20 dark:border-white/20 text-xs font-label-bold uppercase tracking-wider hover:bg-ghost-gray dark:hover:bg-white/10 transition-colors flex items-center justify-center gap-1 cursor-pointer">
                                <span class="material-symbols-outlined text-base">arrow_back</span>
                                <span>Back to Edit Form</span>
                            </button>

                            <button type="submit" :disabled="isSubmitting"
                                class="section-primary-btn w-full sm:w-auto px-12 py-4 rounded-full font-label-bold text-sm uppercase tracking-wider lift-hover flex items-center justify-center gap-2 cursor-pointer shadow-xl">
                                <span x-show="!isSubmitting">Submit General Application</span>
                                <span x-show="isSubmitting" class="flex items-center gap-2" style="display:none;">
                                    <span class="material-symbols-outlined animate-spin text-base">sync</span>
                                    <span>Submitting Application...</span>
                                </span>
                                <span class="material-symbols-outlined text-lg" x-show="!isSubmitting">check_circle</span>
                            </button>
                        </div>

                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
