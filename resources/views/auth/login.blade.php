@extends('layouts.app')

@section('hideHeader', true)
@section('title', 'Admin Sign In')

@push('head')
    {{-- Cloudflare Turnstile API --}}
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
@endpush

@section('content')
    <section
        class="w-full min-h-[85vh] bg-stark-white dark:bg-deep-onyx bg-checked-pattern py-10 sm:py-xl flex flex-col justify-center items-center px-4 md:px-gutter relative">


        {{-- Heading OUTSIDE the Box --}}
        <div class="flex flex-col items-center text-center max-w-lg mb-6 sm:mb-8">

            {{-- Main Heading --}}
            <h1
                class="font-display text-3xl sm:text-4xl md:text-5xl text-deep-onyx dark:text-stark-white tracking-tight uppercase leading-tight mb-2 sm:mb-3">
                ADMIN PORTAL <span class="text-electric-green">LOGIN</span>
            </h1>

            {{-- Subtitle --}}
            <p class="font-body-md text-secondary dark:text-stark-white/80 text-xs sm:text-sm md:text-base leading-relaxed">
                Enter your credentials to access the administrative dashboard.
            </p>
        </div>

        {{-- Form Card Box --}}
        <div class="glass-box w-full max-w-md p-6 sm:p-8 md:p-10 relative z-10">

            {{-- Status Alert --}}
            @if(session('status'))
                <div
                    class="mb-5 flex items-center gap-3 p-4 rounded-2xl bg-electric-green/10 border border-electric-green/30 text-deep-onyx dark:text-electric-green text-sm font-label-bold">
                    <span class="material-symbols-outlined text-[18px] text-electric-green"
                        style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            {{-- CAPTCHA Error Alert --}}
            @if($errors->has('cf-turnstile-response'))
                <div
                    class="mb-5 flex items-start gap-3 p-4 rounded-2xl bg-red-500/10 border border-red-500/30 text-red-600 dark:text-red-400 text-sm">
                    <span class="material-symbols-outlined text-[18px] shrink-0 mt-px">security</span>
                    <div>
                        <p class="font-bold mb-0.5">CAPTCHA Verification Failed</p>
                        <p class="text-xs opacity-90">{{ $errors->first('cf-turnstile-response') }}</p>
                    </div>
                </div>
            @endif

            {{-- Rate Limit / Lockout Alerts --}}
            @if($errors->has('email') && str_contains($errors->first('email'), 'attempts'))
                <div
                    class="mb-5 flex items-start gap-3 p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-700 dark:text-amber-400 text-sm">
                    <span class="material-symbols-outlined text-[20px] shrink-0 mt-px">timer</span>
                    <div>
                        <p class="font-bold mb-0.5">Too Many Attempts</p>
                        <p class="text-xs opacity-90">{{ $errors->first('email') }}</p>
                    </div>
                </div>
            @elseif($errors->has('email') && str_contains($errors->first('email'), 'unavailable'))
                <div
                    class="mb-5 flex items-start gap-3 p-4 rounded-2xl bg-red-500/10 border border-red-500/30 text-red-600 dark:text-red-400 text-sm">
                    <span class="material-symbols-outlined text-[20px] shrink-0 mt-px">warning</span>
                    <div>
                        <p class="font-bold mb-0.5">Service Temporarily Unavailable</p>
                        <p class="text-xs opacity-90">{{ $errors->first('email') }}</p>
                    </div>
                </div>
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-5" x-data="{ showPass: false }">
                @csrf

                {{-- Email Address --}}
                <div>
                    <label for="email"
                        class="block text-xs font-label-bold uppercase tracking-wider text-deep-onyx/70 dark:text-stark-white/70 mb-2">
                        Email Address
                    </label>
                    <div class="relative">
                        <div
                            class="absolute left-4 inset-y-0 flex items-center pointer-events-none text-secondary dark:text-stark-white/50">
                            <span class="material-symbols-outlined text-[18px] leading-none">mail</span>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            autocomplete="email"
                            class="w-full pl-11 pr-4 py-3.5 rounded-full bg-ghost-gray dark:bg-white/5 border-2 {{ $errors->has('email') && !str_contains($errors->first('email'), 'attempts') && !str_contains($errors->first('email'), 'unavailable') ? 'border-red-500' : 'border-deep-onyx/20 dark:border-white/20' }} text-deep-onyx dark:text-stark-white placeholder-secondary focus:outline-none focus:border-electric-green text-sm transition-all"
                            placeholder="admin@school.edu">
                    </div>
                    @error('email')
                        @if(!str_contains($message, 'attempts') && !str_contains($message, 'unavailable'))
                            <p class="mt-2 text-xs text-red-500 flex items-center gap-1 font-label-bold">
                                <span class="material-symbols-outlined text-[14px]">error</span> {{ $message }}
                            </p>
                        @endif
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password"
                        class="block text-xs font-label-bold uppercase tracking-wider text-deep-onyx/70 dark:text-stark-white/70 mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <div
                            class="absolute left-4 inset-y-0 flex items-center pointer-events-none text-secondary dark:text-stark-white/50">
                            <span class="material-symbols-outlined text-[18px] leading-none">lock</span>
                        </div>
                        <input id="password" :type="showPass ? 'text' : 'password'" name="password" required
                            autocomplete="current-password"
                            class="w-full pl-11 pr-12 py-3.5 rounded-full bg-ghost-gray dark:bg-white/5 border-2 {{ $errors->has('password') ? 'border-red-500' : 'border-deep-onyx/20 dark:border-white/20' }} text-deep-onyx dark:text-stark-white placeholder-secondary focus:outline-none focus:border-electric-green text-sm transition-all"
                            placeholder="••••••••">
                        <div class="absolute right-4 inset-y-0 flex items-center">
                            <button type="button" @click="showPass = !showPass"
                                class="text-secondary dark:text-stark-white/50 hover:text-electric-green transition-colors flex items-center justify-center">
                                <span class="material-symbols-outlined text-[18px] leading-none"
                                    x-text="showPass ? 'visibility_off' : 'visibility'">visibility</span>
                            </button>
                        </div>
                    </div>
                    @error('password')
                        <p class="mt-2 text-xs text-red-500 flex items-center gap-1 font-label-bold">
                            <span class="material-symbols-outlined text-[14px]">error</span> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Remember Me & Forgot Password --}}
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember"
                            class="w-4 h-4 rounded text-electric-green focus:ring-electric-green border-deep-onyx/20 dark:border-white/20 bg-ghost-gray dark:bg-white/5">
                        <span class="text-xs text-deep-onyx/70 dark:text-stark-white/70 font-label-bold">Remember me</span>
                    </label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-xs text-deep-onyx dark:text-electric-green hover:underline font-label-bold">Forgot
                            password?</a>
                    @endif
                </div>

                {{-- Cloudflare Turnstile CAPTCHA Widget (Only rendered if site key configured) --}}
                @if(config('services.turnstile.site_key'))
                    <div class="pt-1">
                        <div class="cf-turnstile flex justify-center" data-sitekey="{{ config('services.turnstile.site_key') }}"
                            data-theme="auto" data-size="normal">
                        </div>
                    </div>
                @endif

                {{-- Submit Button (Homepage Brutalist Style) --}}
                <button type="submit"
                    class="w-full bg-electric-green dark:!bg-electric-green text-deep-onyx dark:!text-deep-onyx px-xl py-3.5 rounded-full font-label-bold text-label-bold uppercase tracking-wide hover:bg-primary-fixed transition-all flex-shrink-0 border-2 border-deep-onyx dark:!border-electric-green flex items-center justify-center gap-2 shadow-[4px_4px_0px_0px_#171717] dark:shadow-none hover:translate-x-0.5 hover:translate-y-0.5">
                    <span>Sign In to Dashboard</span>
                    <span class="material-symbols-outlined text-lg">arrow_forward</span>
                </button>

                {{-- Footer Link --}}
                <div class="pt-4 border-t border-ghost-gray dark:border-white/10 text-center">
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center gap-1 text-xs font-label-bold text-secondary dark:text-stark-white/80 hover:text-electric-green transition-colors">
                        <span class="material-symbols-outlined text-[15px]">arrow_back</span>
                        <span>Back to Public Site</span>
                    </a>
                </div>
            </form>
        </div>

    </section>
@endsection