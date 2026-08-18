@extends('layouts.admin')

@section('title', 'Manage Site Settings & CMS')

@section('content')
    <div class="max-w-[1400px] mx-auto" x-data="{ tab: 'branding' }">

        {{-- Overview Page Header & Live System Clock --}}
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4" x-data="{
                             timeStr: '{{ now()->timezone('Asia/Kolkata')->format('h:i:s A') }}',
                             dateStr: '{{ now()->timezone('Asia/Kolkata')->format('l, M d, Y | h:i A') }}',
                             init() {
                                 const update = () => {
                                     const now = new Date();
                                     const optionsTime = { timeZone: 'Asia/Kolkata', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
                                     const optionsShortTime = { timeZone: 'Asia/Kolkata', hour: '2-digit', minute: '2-digit', hour12: true };
                                     const optionsDate = { timeZone: 'Asia/Kolkata', weekday: 'long', month: 'short', day: 'numeric', year: 'numeric' };

                                     this.timeStr = new Intl.DateTimeFormat('en-US', optionsTime).format(now);
                                     const d = new Intl.DateTimeFormat('en-US', optionsDate).format(now);
                                     const tShort = new Intl.DateTimeFormat('en-US', optionsShortTime).format(now);
                                     this.dateStr = `${d} | ${tShort}`;
                                 };
                                 update();
                                 setInterval(update, 1000);
                             }
                         }">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-4xl font-extrabold text-[#111827] tracking-tight leading-none" style="font-size: 38px;">
                        Site Settings &amp; Configuration</h2>
                </div>
            </div>

            {{-- Top Right Actions --}}
            <div class="flex items-center gap-3">
                <div
                    class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-2 rounded-full font-label-md text-[13px] flex items-center gap-2 font-bold shadow-2xs">
                    <span class="material-symbols-outlined text-[18px] text-emerald-600">verified</span>
                    Live Portal Configurations
                </div>
            </div>
        </div>

        <!-- Navigation Tabs Bar -->
        <div class="bg-white shadow-sm border border-gray-100 p-2 mb-6 inline-flex flex-wrap gap-2"
            style="border-radius: 20px;">
            <button @click="tab = 'branding'"
                :class="tab === 'branding' ? 'bg-[#21255E] text-white font-bold shadow-xs' : 'text-gray-600 hover:bg-gray-100/80'"
                class="px-5 py-2.5 rounded-full text-xs font-bold transition-all flex items-center gap-2 cursor-pointer">
                <span class="material-symbols-outlined text-[18px]">palette</span>
                <span>Site Identity &amp; Title</span>
            </button>
            <button @click="tab = 'homepage'"
                :class="tab === 'homepage' ? 'bg-[#21255E] text-white font-bold shadow-xs' : 'text-gray-600 hover:bg-gray-100/80'"
                class="px-5 py-2.5 rounded-full text-xs font-bold transition-all flex items-center gap-2 cursor-pointer">
                <span class="material-symbols-outlined text-[18px]">home</span>
                <span>Homepage Content &amp; Stats</span>
            </button>
            <button @click="tab = 'mail'"
                :class="tab === 'mail' ? 'bg-[#21255E] text-white font-bold shadow-xs' : 'text-gray-600 hover:bg-gray-100/80'"
                class="px-5 py-2.5 rounded-full text-xs font-bold transition-all flex items-center gap-2 cursor-pointer">
                <span class="material-symbols-outlined text-[18px]">mark_email_read</span>
                <span>Mail Configuration (.env)</span>
            </button>
        </div>

        <!-- TAB 1: Site Identity & Title Settings -->
        <div x-show="tab === 'branding'" class="bg-white shadow-sm border border-gray-100 p-6 sm:p-8 space-y-6"
            style="border-radius: 20px;">
            <div class="border-b border-gray-100 pb-4 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-[#111827] flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-600 text-[22px]">badge</span>
                        Site Identity &amp; Portal Title Settings
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Customize your institution's portal title displayed on the
                        navigation bar, login screen, and page footers.</p>
                </div>
                <span
                    class="text-[11px] font-bold tracking-wider uppercase bg-emerald-50 text-emerald-700 border border-emerald-200 px-3 py-1 rounded-full">
                    Auto-Fetched Live
                </span>
            </div>

            <form action="{{ route('superadmin.cms.update-homepage') }}" method="POST" class="space-y-6">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            Global School / Portal Name (Displayed on Header, Footer &amp; Browser Tabs) *
                        </label>
                        <input type="text" name="site_name"
                            value="{{ $cms['site_name'] ?? $siteSettings['site_name'] ?? config('app.name', 'School Careers') }}"
                            required placeholder="e.g. RAZA UL ULOOM ISLAMIA HSS"
                            class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-base font-bold text-gray-900 bg-gray-50/50 transition-all">
                        <p class="text-xs text-gray-400 mt-1.5">This name will be automatically populated across the public
                            site, portal headers, login screens, and system emails.</p>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-gray-100">
                    <button type="submit"
                        class="text-white font-bold text-xs px-8 py-3 rounded-full transition-all shadow-md flex items-center gap-2 active:scale-95 cursor-pointer"
                        style="background-color: #D7B56D;">
                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                        <span>Save Site Title</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- TAB 2: Homepage Hero & Content Settings -->
        <div x-show="tab === 'homepage'" class="bg-white shadow-sm border border-gray-100 p-6 sm:p-8 space-y-6"
            style="border-radius: 20px; display:none;">
            <div class="border-b border-gray-100 pb-4">
                <h3 class="text-lg font-bold text-[#111827] flex items-center gap-2">
                    <span class="material-symbols-outlined text-purple-600 text-[22px]">home</span>
                    Homepage Hero &amp; Content Customization
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">Customize headline banners, subtext, and portal statistic counters.
                </p>
            </div>

            <form action="{{ route('superadmin.cms.update-homepage') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Hero Badge
                            Text</label>
                        <input type="text" name="hero_badge" value="{{ $cms['hero_badge'] ?? '' }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-900 bg-gray-50/50">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Hero
                            Title</label>
                        <textarea name="hero_title" rows="2"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-900 bg-gray-50/50 resize-none">{{ $cms['hero_title'] ?? '' }}</textarea>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Hero
                            Subtitle</label>
                        <textarea name="hero_subtitle" rows="2"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-900 bg-gray-50/50 resize-none">{{ $cms['hero_subtitle'] ?? '' }}</textarea>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-6">
                    <h4 class="font-bold text-[#111827] text-base mb-4">Homepage Key Statistics</h4>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Schools Count</label>
                            <input type="text" name="stats_schools" value="{{ $cms['stats_schools'] ?? '25+' }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-900 bg-gray-50/50">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Teachers Count</label>
                            <input type="text" name="stats_teachers" value="{{ $cms['stats_teachers'] ?? '500+' }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-900 bg-gray-50/50">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Placed Candidates</label>
                            <input type="text" name="stats_hired" value="{{ $cms['stats_hired'] ?? '1,200+' }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-900 bg-gray-50/50">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Satisfaction Rate</label>
                            <input type="text" name="stats_satisfaction" value="{{ $cms['stats_satisfaction'] ?? '98%' }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-900 bg-gray-50/50">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-gray-100">
                    <button type="submit"
                        class="text-white font-bold text-xs px-8 py-3 rounded-full transition-all shadow-md flex items-center gap-2 active:scale-95 cursor-pointer"
                        style="background-color: #D7B56D;">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        <span>Save Homepage Content</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- TAB 3: Mail Configuration (.env) -->
        <div x-show="tab === 'mail'" class="bg-white shadow-sm border border-gray-100 p-6 sm:p-8 space-y-6"
            style="border-radius: 20px; display:none;">
            <div class="border-b border-gray-100 pb-4 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-[#111827] flex items-center gap-2">
                        <span class="material-symbols-outlined text-amber-600 text-[22px]">mark_email_read</span>
                        Mail Server &amp; SMTP Configuration
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Configure system SMTP settings, mail server credentials, and
                        default sender profiles. Saves directly to the system.</p>
                </div>
            </div>

            <form action="{{ route('superadmin.cms.update-mail-config') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

                    <!-- Mail Driver -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Mail Driver
                            (MAIL_MAILER) *</label>
                        <select name="mail_mailer" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-900 bg-gray-50/50">
                            <option value="smtp" {{ ($mailConfig['mail_mailer'] ?? '') === 'smtp' ? 'selected' : '' }}>SMTP
                            </option>
                            <option value="sendmail" {{ ($mailConfig['mail_mailer'] ?? '') === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                            <option value="log" {{ ($mailConfig['mail_mailer'] ?? '') === 'log' ? 'selected' : '' }}>Log
                                (Testing)</option>
                            <option value="array" {{ ($mailConfig['mail_mailer'] ?? '') === 'array' ? 'selected' : '' }}>Array
                            </option>
                        </select>
                    </div>

                    <!-- SMTP Host -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">SMTP Host
                            (MAIL_HOST) * <span class="text-xs text-gray-400 font-normal lowercase">(e.g. smtp.hostinger.com, not an email)</span></label>
                        <input type="text" name="mail_host" value="{{ old('mail_host', $mailConfig['mail_host'] ?? '') }}"
                            required placeholder="smtp.hostinger.com"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-mono text-gray-900 bg-gray-50/50">
                        @error('mail_host')<p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <!-- SMTP Port -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">SMTP Port
                            (MAIL_PORT) *</label>
                        <input type="number" name="mail_port" value="{{ old('mail_port', $mailConfig['mail_port'] ?? '') }}"
                            required placeholder="587"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-mono text-gray-900 bg-gray-50/50">
                    </div>

                    <!-- Encryption -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Encryption
                            (MAIL_ENCRYPTION)</label>
                        <select name="mail_encryption"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-900 bg-gray-50/50">
                            <option value="tls" {{ strtolower($mailConfig['mail_encryption'] ?? '') === 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ strtolower($mailConfig['mail_encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="" {{ empty($mailConfig['mail_encryption']) ? 'selected' : '' }}>None</option>
                        </select>
                    </div>

                    <!-- SMTP Username -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">SMTP Username
                            (MAIL_USERNAME)</label>
                        <input type="text" name="mail_username"
                            value="{{ old('mail_username', $mailConfig['mail_username'] ?? '') }}"
                            placeholder="your-email@gmail.com"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-mono text-gray-900 bg-gray-50/50">
                    </div>

                    <!-- SMTP Password -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">SMTP Password
                            (MAIL_PASSWORD)</label>
                        <input type="password" name="mail_password"
                            value="{{ old('mail_password', $mailConfig['mail_password'] ?? '') }}"
                            placeholder="••••••••••••"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm text-gray-900 bg-gray-50/50">
                    </div>

                    <!-- From Address -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">From Email
                            (MAIL_FROM_ADDRESS) *</label>
                        <input type="email" name="mail_from_address"
                            value="{{ old('mail_from_address', $mailConfig['mail_from_address'] ?? '') }}" required
                            placeholder="noreply@school.edu"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-900 bg-gray-50/50">
                    </div>

                    <!-- From Sender Name -->
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">From Sender Name
                            (MAIL_FROM_NAME) *</label>
                        <input type="text" name="mail_from_name"
                            value="{{ old('mail_from_name', $mailConfig['mail_from_name'] ?? '') }}" required
                            placeholder="RAZA UL ULOOM ISLAMIA HSS"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-bold text-gray-900 bg-gray-50/50">
                    </div>

                </div>

                <div class="flex justify-end pt-4 border-t border-gray-100">
                    <button type="submit"
                        class="text-white font-bold text-xs px-8 py-3 rounded-full transition-all shadow-md flex items-center gap-2 active:scale-95 cursor-pointer"
                        style="background-color: #D7B56D;">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        <span>Save Mail Configuration (.env)</span>
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection