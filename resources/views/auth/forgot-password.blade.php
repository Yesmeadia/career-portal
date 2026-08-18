@extends('layouts.app')

@section('hideHeader', true)
@section('title', 'Reset Password')

@section('content')
    <section
        class="w-full min-h-[85vh] bg-stark-white dark:bg-deep-onyx bg-checked-pattern py-xl flex flex-col justify-center items-center px-margin-mobile md:px-gutter relative">

        {{-- Heading OUTSIDE the Box --}}
        <div class="flex flex-col items-center text-center max-w-lg mb-8">
            {{-- Main Heading --}}
            <h1
                class="font-display text-4xl md:text-5xl text-deep-onyx dark:text-stark-white tracking-tight uppercase leading-none mb-3">
                RESET YOUR <span class="text-electric-green">PASSWORD</span>
            </h1>

            {{-- Subtitle --}}
            <p class="font-body-md text-secondary dark:text-stark-white/80 text-sm md:text-base leading-relaxed">
                Enter your administrator email address and we'll send a password reset link.
            </p>
        </div>

        {{-- Form Card Box (NO "Reset your password" heading text inside the box) --}}
        <div
            class="w-full max-w-md bg-stark-white dark:bg-[#141618] border-2 border-deep-onyx dark:border-white/20 rounded-3xl p-8 md:p-10 shadow-[8px_8px_0px_0px_#171717] dark:shadow-[8px_8px_0px_0px_rgba(0,204,104,0.25)] relative z-10">

            {{-- Status Alert --}}
            @if(session('status'))
                <div
                    class="mb-5 flex items-center gap-3 p-4 rounded-2xl bg-electric-green/10 border border-electric-green/30 text-deep-onyx dark:text-electric-green text-sm font-label-bold">
                    <span class="material-symbols-outlined text-[18px] text-electric-green"
                        style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            {{-- Password Email Form --}}
            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                {{-- Email Address --}}
                <div>
                    <label for="email"
                        class="block text-xs font-label-bold uppercase tracking-wider text-deep-onyx/70 dark:text-stark-white/70 mb-2">
                        Email Address
                    </label>
                    <div class="relative">
                        <div class="absolute left-4 inset-y-0 flex items-center pointer-events-none text-secondary dark:text-stark-white/50">
                            <span class="material-symbols-outlined text-[18px] leading-none">mail</span>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full pl-11 pr-4 py-3.5 rounded-full bg-ghost-gray dark:bg-white/5 border-2 {{ $errors->has('email') ? 'border-red-500' : 'border-deep-onyx/20 dark:border-white/20' }} text-deep-onyx dark:text-stark-white placeholder-secondary focus:outline-none focus:border-electric-green text-sm transition-all"
                            placeholder="admin@school.edu">
                    </div>
                    @error('email')
                        <p class="mt-2 text-xs text-red-500 flex items-center gap-1 font-label-bold">
                            <span class="material-symbols-outlined text-[14px]">error</span> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <button type="submit"
                    class="w-full bg-electric-green text-deep-onyx px-xl py-3.5 rounded-full font-label-bold text-label-bold uppercase tracking-wide hover:bg-primary-fixed transition-all flex-shrink-0 border-2 border-deep-onyx flex items-center justify-center gap-2 shadow-[4px_4px_0px_0px_#171717] hover:shadow-[2px_2px_0px_0px_#171717] hover:translate-x-0.5 hover:translate-y-0.5">
                    <span>Send Reset Link</span>
                    <span class="material-symbols-outlined text-lg">mail</span>
                </button>

                {{-- Footer Link --}}
                <div class="pt-4 border-t border-ghost-gray dark:border-white/10 text-center">
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center gap-1 text-xs font-label-bold text-secondary dark:text-stark-white/80 hover:text-electric-green transition-colors">
                        <span class="material-symbols-outlined text-[15px]">arrow_back</span>
                        <span>Back to Login</span>
                    </a>
                </div>
            </form>
        </div>

    </section>
@endsection