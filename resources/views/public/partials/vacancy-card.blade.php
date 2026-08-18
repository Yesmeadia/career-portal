<div class="vacancy-card group">

    <div>
        {{-- Top Badge / Quote Header --}}
        <div class="flex items-center justify-between mb-2.5 sm:mb-4">
            <svg class="vacancy-quote-icon w-6 h-6 sm:w-8 sm:h-8 opacity-80" viewBox="0 0 32 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M0 24V14.4C0 10.08 1.12 6.56 3.36 3.84 5.6 1.12 8.96 0 13.44 0L14.4 3.36C11.84 3.84 9.92 4.96 8.64 6.72 7.36 8.48 6.72 10.56 6.72 12.96H12.48V24H0ZM17.52 24V14.4C17.52 10.08 18.64 6.56 20.88 3.84 23.12 1.12 26.48 0 30.96 0L31.92 3.36C29.36 3.84 27.44 4.96 26.16 6.72 24.88 8.48 24.24 10.56 24.24 12.96H30V24H17.52Z"/>
            </svg>
            @if($vacancy->is_featured)
                <span
                    class="vacancy-featured-badge inline-flex items-center gap-1 font-label-sm text-[11px] sm:text-xs px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full uppercase tracking-wider">
                    <span class="material-symbols-outlined text-[13px] sm:text-[14px]">bolt</span> Featured
                </span>
            @endif
        </div>

        {{-- Job Title --}}
        <h3
            class="font-body-lg text-deep-onyx dark:text-stark-white text-lg sm:text-xl font-bold leading-snug mb-2 sm:mb-3 vacancy-title-text transition-colors line-clamp-2">
            <a href="{{ route('vacancies.show', $vacancy->slug) }}">
                {{ $vacancy->title }}
            </a>
        </h3>

        {{-- Location & Employment Badge --}}
        <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 mb-3 sm:mb-6">
            <span
                class="badge-pill inline-block font-label-sm text-[11px] sm:text-xs px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full uppercase tracking-wider">
                {{ ucfirst(str_replace('_', ' ', $vacancy->employment_type)) }}
            </span>
            @if($vacancy->location)
                <span
                    class="badge-pill inline-block font-label-sm text-[11px] sm:text-xs px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full uppercase tracking-wider">
                    <span class="material-symbols-outlined text-[12px] align-middle mr-0.5">location_on</span>{{ $vacancy->location }}
                </span>
            @endif
        </div>
    </div>

    {{-- Bottom Bar: School & Compensation --}}
    <div class="pt-3 sm:pt-6 border-t border-ghost-gray dark:border-white/10 flex items-center justify-between">
        <div class="min-w-0">
            <h4
                class="text-deep-onyx/80 dark:text-stark-white/90 font-label-bold text-xs truncate max-w-[150px] sm:max-w-[220px]">
                {{ $vacancy->school->name }}</h4>
            <p class="text-secondary dark:text-stark-white/70 text-[11px] truncate">
                {{ $vacancy->department->name ?? 'General' }}</p>
        </div>

        <a href="{{ route('vacancies.show', $vacancy->slug) }}"
            class="card-arrow-btn w-8 h-8 sm:w-9 sm:h-9 rounded-full flex items-center justify-center shrink-0">
            <span class="material-symbols-outlined text-base sm:text-lg">arrow_forward</span>
        </a>
    </div>
</div>