@extends('layouts.admin')

@section('title', 'Official Candidate Application — ' . $application->full_name)

@section('content')
    <div class="max-w-[1100px] mx-auto space-y-4">

        {{-- ── ADMIN ACTION TOOLBAR (Non-printable) ── --}}
        <div
            class="flex flex-wrap items-center justify-between gap-3 bg-white border border-gray-200 px-5 py-3 rounded-2xl shadow-xs print:hidden">
            <a href="{{ route('schooladmin.applications.index') }}"
                class="inline-flex items-center gap-1.5 text-xs font-bold text-[#21255E] hover:underline">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                Application Details
            </a>

            <div class="flex items-center gap-2">
                {{-- Premium Pipeline Stage Selector Dropdown Button --}}
                @php
                    $statusConfig = [
                        'submitted' => ['label' => 'Submitted', 'bg' => 'bg-slate-50', 'text' => 'text-slate-800', 'border' => 'border-slate-300', 'dot' => 'bg-slate-500', 'icon' => 'drafts'],
                        'new' => ['label' => 'New', 'bg' => 'bg-blue-50', 'text' => 'text-blue-800', 'border' => 'border-blue-300', 'dot' => 'bg-blue-500', 'icon' => 'fiber_new'],
                        'under_review' => ['label' => 'Under Review', 'bg' => 'bg-sky-50', 'text' => 'text-sky-800', 'border' => 'border-sky-300', 'dot' => 'bg-sky-500', 'icon' => 'search'],
                        'shortlisted' => ['label' => 'Shortlisted', 'bg' => 'bg-indigo-50', 'text' => 'text-indigo-800', 'border' => 'border-indigo-300', 'dot' => 'bg-indigo-500', 'icon' => 'verified'],
                        'interview_scheduled' => ['label' => 'Interview Scheduled', 'bg' => 'bg-amber-50', 'text' => 'text-amber-900', 'border' => 'border-amber-300', 'dot' => 'bg-amber-500', 'icon' => 'calendar_month'],
                        'interview_completed' => ['label' => 'Interview Completed', 'bg' => 'bg-teal-50', 'text' => 'text-teal-900', 'border' => 'border-teal-300', 'dot' => 'bg-teal-500', 'icon' => 'task_alt'],
                        'selected' => ['label' => 'Selected', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-900', 'border' => 'border-emerald-300', 'dot' => 'bg-emerald-600', 'icon' => 'check_circle'],
                        'hired' => ['label' => 'Hired', 'bg' => 'bg-green-100', 'text' => 'text-green-900', 'border' => 'border-green-400', 'dot' => 'bg-green-600', 'icon' => 'military_tech'],
                        'rejected' => ['label' => 'Rejected', 'bg' => 'bg-rose-50', 'text' => 'text-rose-800', 'border' => 'border-rose-300', 'dot' => 'bg-rose-500', 'icon' => 'cancel'],
                        'on_hold' => ['label' => 'On Hold', 'bg' => 'bg-orange-50', 'text' => 'text-orange-800', 'border' => 'border-orange-300', 'dot' => 'bg-orange-500', 'icon' => 'pause_circle'],
                    ];
                    $currentCfg = $statusConfig[$application->status] ?? ['label' => ucwords(str_replace('_', ' ', $application->status)), 'bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'border' => 'border-gray-300', 'dot' => 'bg-gray-500', 'icon' => 'swap_horiz'];
                @endphp

                <div class="relative" x-data="{ open: false }">
                    <form id="stageForm" action="{{ route('schooladmin.applications.update-status', $application) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" id="stageStatusInput" value="{{ $application->status }}">
                    </form>

                    {{-- Main Status Trigger Button --}}
                    <button type="button" @click="open = !open"
                        class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold border transition-all shadow-2xs hover:shadow-xs cursor-pointer select-none {{ $currentCfg['bg'] }} {{ $currentCfg['text'] }} {{ $currentCfg['border'] }}">
                        <span class="w-2 h-2 rounded-full {{ $currentCfg['dot'] }} shrink-0 {{ $application->status === 'interview_scheduled' ? 'animate-pulse' : '' }}"></span>
                        <span class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Stage:</span>
                        <span>{{ $currentCfg['label'] }}</span>
                        <span class="material-symbols-outlined text-[16px] text-gray-500 transition-transform duration-200" :class="{ 'rotate-180': open }">expand_more</span>
                    </button>

                    {{-- Dropdown Menu --}}
                    <div x-show="open" @click.outside="open = false"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                        style="display: none;"
                        class="absolute left-0 mt-2 w-72 bg-white rounded-2xl shadow-2xl border border-gray-200 py-2 z-50 overflow-hidden">
                        <div class="max-h-80 overflow-y-auto py-1 divide-y divide-gray-100">
                            {{-- Group: Initial Screening --}}
                            <div class="py-1">
                                @foreach(['submitted', 'new', 'under_review'] as $st)
                                    @php $cfg = $statusConfig[$st]; @endphp
                                    <button type="button" @click="open = false; selectStage('{{ $st }}')"
                                        class="w-full px-4 py-2 flex items-center justify-between text-left hover:bg-gray-50 transition-colors cursor-pointer {{ $application->status === $st ? 'bg-blue-50/50 font-bold' : '' }}">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <span class="w-2 h-2 rounded-full {{ $cfg['dot'] }} shrink-0"></span>
                                            <span class="text-xs text-gray-800">{{ $cfg['label'] }}</span>
                                        </div>
                                        @if($application->status === $st)
                                            <span class="material-symbols-outlined text-[16px] text-blue-600">check</span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>

                            {{-- Group: Assessment & Interview --}}
                            <div class="py-1">
                                @foreach(['shortlisted', 'interview_scheduled', 'interview_completed'] as $st)
                                    @php $cfg = $statusConfig[$st]; @endphp
                                    <button type="button" @click="open = false; selectStage('{{ $st }}')"
                                        class="w-full px-4 py-2 flex items-center justify-between text-left hover:bg-gray-50 transition-colors cursor-pointer {{ $application->status === $st ? 'bg-blue-50/50 font-bold' : '' }}">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <span class="w-2 h-2 rounded-full {{ $cfg['dot'] }} shrink-0"></span>
                                            <div>
                                                <span class="text-xs text-gray-800 block">{{ $cfg['label'] }}</span>
                                                @if($st === 'interview_scheduled')
                                                    <span class="text-[10px] text-amber-600 font-medium block">Opens schedule modal</span>
                                                @endif
                                            </div>
                                        </div>
                                        @if($application->status === $st)
                                            <span class="material-symbols-outlined text-[16px] text-blue-600">check</span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>

                            {{-- Group: Final Outcomes --}}
                            <div class="py-1">
                                @foreach(['selected', 'hired', 'on_hold', 'rejected'] as $st)
                                    @php $cfg = $statusConfig[$st]; @endphp
                                    <button type="button" @click="open = false; selectStage('{{ $st }}')"
                                        class="w-full px-4 py-2 flex items-center justify-between text-left hover:bg-gray-50 transition-colors cursor-pointer {{ $application->status === $st ? 'bg-blue-50/50 font-bold' : '' }}">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <span class="w-2 h-2 rounded-full {{ $cfg['dot'] }} shrink-0"></span>
                                            <span class="text-xs text-gray-800">{{ $cfg['label'] }}</span>
                                        </div>
                                        @if($application->status === $st)
                                            <span class="material-symbols-outlined text-[16px] text-blue-600">check</span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('applications.download-pdf', ['referenceNo' => $application->reference_no, 'auto_print' => 1]) }}"
                    target="_blank"
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-gray-50 border border-gray-200 text-gray-700 font-bold text-xs rounded-full hover:bg-gray-100 transition-all">
                    <span class="material-symbols-outlined text-[15px]">picture_as_pdf</span>
                    Application PDF
                </a>
                @if($application->resume_path)
                    <a href="{{ route('schooladmin.applications.download-cv', $application) }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-gray-50 border border-gray-200 text-gray-700 font-bold text-xs rounded-full hover:bg-gray-100 transition-all">
                        <span class="material-symbols-outlined text-[15px]">download</span>
                        Download CV
                    </a>
                @endif
                <button onclick="document.getElementById('emailComposer').classList.toggle('hidden')"
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-white font-bold text-xs rounded-full transition-all cursor-pointer shadow-xs"
                    style="background:#21255E;">
                    <span class="material-symbols-outlined text-[15px]">forward_to_inbox</span>
                    Dispatch Email
                </button>
            </div>
        </div>

        {{-- ── IN-APP EMAIL DISPATCHER (Collapsible) ── --}}
        <div id="emailComposer" class="hidden bg-white border border-gray-200 shadow-md rounded-2xl print:hidden">
            <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between bg-gray-50/50 rounded-t-2xl">
                <span class="text-xs font-bold text-gray-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px] text-blue-600">forward_to_inbox</span>
                    Official Email Dispatcher &mdash; {{ $application->full_name }}
                </span>
                <button onclick="document.getElementById('emailComposer').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600 cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>
            <div class="p-5">
                <form action="{{ route('schooladmin.applications.send-email', $application) }}" method="POST" x-data="{
                          subject: 'Regarding your application for {{ addslashes($application->vacancy->title ?? 'Position') }}',
                          message: 'Dear {{ addslashes($application->first_name) }},\n\nWe are writing regarding your official candidate application (Ref: {{ $application->reference_no }}) for the position of {{ addslashes($application->vacancy->title ?? 'Position') }} at {{ addslashes($application->school->name ?? 'our institution') }}.\n\n\nBest regards,\nSelection Board &amp; Recruitment Team'
                      }" class="space-y-3">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">To
                                Candidate</label>
                            <input type="text" readonly value="{{ $application->full_name }} <{{ $application->email }}>"
                                class="w-full px-3 py-2 rounded-xl border border-gray-200 bg-gray-50 text-gray-600 text-xs font-semibold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Subject
                                *</label>
                            <input type="text" name="subject" x-model="subject" required
                                class="w-full px-3 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255E]/20 outline-none text-xs font-semibold text-gray-900 bg-white">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Message Body
                            *</label>
                        <textarea name="message" x-model="message" rows="4" required
                            class="w-full px-3 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255E]/20 outline-none text-xs text-gray-800 bg-white leading-relaxed resize-y"></textarea>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] text-gray-400 flex items-center gap-1">
                            <span class="material-symbols-outlined text-[13px]">verified</span>
                            Uses official institutional email dispatch format.
                        </p>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="document.getElementById('emailComposer').classList.add('hidden')"
                                class="px-4 py-1.5 border border-gray-200 rounded-full text-xs font-semibold text-gray-600 hover:bg-gray-50 cursor-pointer">Cancel</button>
                            <button type="submit"
                                class="px-5 py-1.5 text-white font-bold rounded-full text-xs flex items-center gap-1.5 cursor-pointer shadow-xs"
                                style="background:#21255E;">
                                <span class="material-symbols-outlined text-[14px]">send</span>Send Email
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
        OFFICIAL INSTITUTIONAL APPLICATION (FORMAL DOCUMENT)
        ══════════════════════════════════════════════════════════════ --}}
        <div
            class="bg-white border-2 border-gray-200 shadow-md print:shadow-none print:border-0 rounded-2xl overflow-hidden font-sans">

            {{-- ── 2. CANDIDATE PROFILE OVERVIEW (PRIMARY SUMMARY BOX) ── --}}
            <div class="p-6 border-b border-gray-200 bg-white">
                <div class="flex flex-col md:flex-row items-center md:items-start gap-6">

                    {{-- Passport Photo Box --}}
                    <div class="shrink-0 text-center">
                        <div
                            class="w-28 h-32 border-2 border-gray-300 bg-gray-100 p-1 rounded-xl shadow-xs overflow-hidden mx-auto">
                            <img src="{{ $application->photo_url }}" class="w-full h-full object-cover rounded-lg"
                                alt="{{ $application->full_name }}"
                                onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                        </div>
                    </div>

                    {{-- Main Candidate Info Table --}}
                    <div class="flex-1 min-w-0 w-full space-y-3">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Candidate Full Name</p>
                            <h2 class="text-2xl font-extrabold text-[#21255E] tracking-tight leading-tight">
                                {{ $application->full_name }}
                            </h2>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 pt-2">
                            <div class="bg-gray-50 border border-gray-200 p-3 rounded-xl">
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Applied Position</p>
                                <p class="text-xs font-extrabold text-[#21255E] mt-0.5 truncate">
                                    {{ $application->vacancy->title ?? 'General Applicant' }}
                                </p>
                            </div>
                            <div class="bg-gray-50 border border-gray-200 p-3 rounded-xl">
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Calculated Age</p>
                                <p class="text-xs font-extrabold text-gray-900 mt-0.5">
                                    @if($application->age)
                                        <span class="text-blue-700 font-extrabold">{{ $application->age }} Years</span>
                                        <span
                                            class="text-gray-400 font-normal">({{ $application->date_of_birth?->format('d/m/Y') }})</span>
                                    @else
                                        <span>{{ $application->date_of_birth?->format('d/m/Y') ?: 'N/A' }}</span>
                                    @endif
                                </p>
                            </div>
                            <div class="bg-gray-50 border border-gray-200 p-3 rounded-xl">
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Pipeline Stage</p>
                                <p class="text-xs font-extrabold mt-0.5 flex items-center gap-1.5">
                                    @php
                                        $stageColor = match ($application->status) {
                                            'hired', 'selected' => 'text-emerald-700 bg-emerald-50 border-emerald-200',
                                            'shortlisted', 'interview_scheduled', 'interview_completed' => 'text-amber-700 bg-amber-50 border-amber-200',
                                            'rejected' => 'text-red-700 bg-red-50 border-red-200',
                                            default => 'text-gray-700 bg-gray-100 border-gray-200',
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold border {{ $stageColor }}">
                                        {{ ucwords(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── 3. FORMAL PERSONAL INFORMATION TABLE ── --}}
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center gap-2 mb-3 pb-2 border-b border-gray-100">
                    <h3 class="text-xs font-extrabold uppercase tracking-widest text-[#21255E]">1. Personal Identity &amp;
                        Contact Details</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border border-gray-200 rounded-xl overflow-hidden">
                        <tbody class="divide-y divide-gray-200">
                            <tr class="divide-x divide-gray-200">
                                <th class="w-1/6 px-4 py-2.5 bg-gray-50 font-bold text-gray-500 uppercase text-[10px]">First
                                    Name</th>
                                <td class="w-2/6 px-4 py-2.5 font-bold text-gray-800">{{ $application->first_name }}</td>
                                <th class="w-1/6 px-4 py-2.5 bg-gray-50 font-bold text-gray-500 uppercase text-[10px]">Last
                                    Name</th>
                                <td class="w-2/6 px-4 py-2.5 font-bold text-gray-800">{{ $application->last_name }}</td>
                            </tr>
                            <tr class="divide-x divide-gray-200">
                                <th class="px-4 py-2.5 bg-gray-50 font-bold text-gray-500 uppercase text-[10px]">Date of
                                    Birth</th>
                                <td class="px-4 py-2.5 font-semibold text-gray-800">
                                    {{ $application->date_of_birth ? $application->date_of_birth->format('d/m/Y') : 'N/A' }}
                                </td>
                                <th class="px-4 py-2.5 bg-gray-50 font-bold text-gray-500 uppercase text-[10px]">Candidate
                                    Age</th>
                                <td class="px-4 py-2.5 font-extrabold text-blue-700">
                                    {{ $application->age ? $application->age . ' Years' : 'N/A' }}
                                </td>
                            </tr>
                            <tr class="divide-x divide-gray-200">
                                <th class="px-4 py-2.5 bg-gray-50 font-bold text-gray-500 uppercase text-[10px]">Gender</th>
                                <td class="px-4 py-2.5 font-semibold text-gray-800">
                                    {{ ucfirst($application->gender ?? 'N/A') }}</td>
                                <th class="px-4 py-2.5 bg-gray-50 font-bold text-gray-500 uppercase text-[10px]">Nationality
                                </th>
                                <td class="px-4 py-2.5 font-semibold text-gray-800">
                                    {{ $application->nationality ?? $application->country ?? 'N/A' }}</td>
                            </tr>
                            <tr class="divide-x divide-gray-200">
                                <th class="px-4 py-2.5 bg-gray-50 font-bold text-gray-500 uppercase text-[10px]">Email
                                    Address</th>
                                <td class="px-4 py-2.5 font-semibold text-gray-800">
                                    <a href="mailto:{{ $application->email }}"
                                        class="text-blue-700 hover:underline">{{ $application->email }}</a>
                                </td>
                                <th class="px-4 py-2.5 bg-gray-50 font-bold text-gray-500 uppercase text-[10px]">Phone
                                    Number</th>
                                <td class="px-4 py-2.5 font-semibold text-gray-800">{{ $application->phone }}</td>
                            </tr>
                            @if($application->whatsapp_number || $application->religion)
                                <tr class="divide-x divide-gray-200">
                                    <th class="px-4 py-2.5 bg-gray-50 font-bold text-gray-500 uppercase text-[10px]">WhatsApp
                                    </th>
                                    <td class="px-4 py-2.5 font-semibold text-emerald-700">
                                        {{ $application->whatsapp_number ?? 'N/A' }}</td>
                                    <th class="px-4 py-2.5 bg-gray-50 font-bold text-gray-500 uppercase text-[10px]">Religion
                                    </th>
                                    <td class="px-4 py-2.5 font-semibold text-gray-800">{{ $application->religion ?? 'N/A' }}
                                    </td>
                                </tr>
                            @endif
                            <tr class="divide-x divide-gray-200">
                                <th class="px-4 py-2.5 bg-gray-50 font-bold text-gray-500 uppercase text-[10px]">Full
                                    Address</th>
                                <td colspan="3" class="px-4 py-2.5 font-semibold text-gray-800">
                                    {{ $application->address }}
                                    @if($application->city), {{ $application->city }}@endif
                                    @if($application->state), {{ $application->state }}@endif
                                    @if($application->pin_code) — {{ $application->pin_code }}@endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ── 4. ACADEMIC QUALIFICATIONS & PROFESSIONAL BACKGROUND ── --}}
            <div class="p-6 border-b border-gray-200 bg-slate-50/30">
                <div class="flex items-center gap-2 mb-3 pb-2 border-b border-gray-100">
                    <h3 class="text-xs font-extrabold uppercase tracking-widest text-[#21255E]">2. Academic Qualifications
                        &amp; Work Experience</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border border-gray-200 rounded-xl overflow-hidden bg-white">
                        <tbody class="divide-y divide-gray-200">
                            <tr class="divide-x divide-gray-200">
                                <th class="w-1/4 px-4 py-2.5 bg-gray-50 font-bold text-gray-500 uppercase text-[10px]">
                                    Highest Qualification</th>
                                <td class="w-1/4 px-4 py-2.5 font-extrabold text-gray-900">
                                    {{ $application->highest_qualification ?: '—' }}</td>
                                <th class="w-1/4 px-4 py-2.5 bg-gray-50 font-bold text-gray-500 uppercase text-[10px]">Total
                                    Experience</th>
                                <td class="w-1/4 px-4 py-2.5 font-extrabold text-gray-900">
                                    {{ $application->experience_years ?: '—' }}</td>
                            </tr>
                            <tr class="divide-x divide-gray-200">
                                <th class="px-4 py-2.5 bg-gray-50 font-bold text-gray-500 uppercase text-[10px]">Current
                                    Employer</th>
                                <td class="px-4 py-2.5 font-bold text-gray-800">{{ $application->current_employer ?: '—' }}
                                </td>
                                <th class="px-4 py-2.5 bg-gray-50 font-bold text-gray-500 uppercase text-[10px]">Notice
                                    Period</th>
                                <td class="px-4 py-2.5 font-bold text-gray-800">{{ $application->notice_period ?: '—' }}
                                </td>
                            </tr>
                            <tr class="divide-x divide-gray-200">
                                <th class="px-4 py-2.5 bg-gray-50 font-bold text-gray-500 uppercase text-[10px]">Current CTC
                                </th>
                                <td class="px-4 py-2.5 font-bold text-gray-800">{{ $application->current_salary ?: '—' }}
                                </td>
                                <th class="px-4 py-2.5 bg-gray-50 font-bold text-gray-500 uppercase text-[10px]">Expected
                                    CTC</th>
                                <td class="px-4 py-2.5 font-bold text-gray-800">{{ $application->expected_salary ?: '—' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ── 5. COMPETENCIES & LANGUAGES ── --}}
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center gap-2 mb-3 pb-2 border-b border-gray-100">
                    <h3 class="text-xs font-extrabold uppercase tracking-widest text-[#21255E]">3. Skills &amp; Competencies
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="border border-gray-200 rounded-xl p-4 bg-white">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Key Skills &amp; Domain
                            Expertise</p>
                        @if($application->skills)
                            <div class="flex flex-wrap gap-1.5">
                                @foreach(array_filter(array_map('trim', explode(',', $application->skills))) as $sk)
                                    <span
                                        class="px-2.5 py-1 text-[11px] font-bold rounded border bg-blue-50 border-blue-200 text-blue-900">
                                        {{ $sk }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-gray-400 italic">No skills listed</p>
                        @endif
                    </div>

                    <div class="border border-gray-200 rounded-xl p-4 bg-white">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Language Proficiencies
                        </p>
                        @if($application->languages)
                            <div class="flex flex-wrap gap-1.5">
                                @foreach(array_filter(array_map('trim', explode(',', $application->languages))) as $lang)
                                    <span
                                        class="px-2.5 py-1 text-[11px] font-bold rounded border bg-gray-100 border-gray-200 text-gray-800">
                                        {{ $lang }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-gray-400 italic">No languages listed</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ── 6. PERSONAL STATEMENT / COVER LETTER ── --}}
            @if($application->cover_letter)
                <div class="p-6 border-b border-gray-200 bg-slate-50/30">
                    <div class="flex items-center gap-2 mb-3 pb-2 border-b border-gray-100">
                        <h3 class="text-xs font-extrabold uppercase tracking-widest text-[#21255E]">4. Candidate Cover Statement
                        </h3>
                    </div>
                    <div class="border border-gray-200 rounded-xl p-5 bg-white text-xs text-gray-700 leading-6 font-sans">
                        {!! nl2br(e($application->cover_letter)) !!}
                    </div>
                </div>
            @endif

            {{-- ── 5. INTERVIEW & ASSESSMENT NOTES ── --}}
            <div class="p-6 border-b border-gray-200 bg-amber-50/20">
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-100">
                    <h3 class="text-xs font-extrabold uppercase tracking-widest text-[#21255E] flex items-center gap-2">
                        5. Interview Assessment &amp; Evaluation Notes
                    </h3>
                    <span class="text-[11px] text-gray-500 font-semibold">
                        Reference: {{ $application->reference_no }}
                    </span>
                </div>

                {{-- Interactive Notes Editor Form (Non-printable edit form, printable formatted text) --}}
                <div class="print:hidden mb-5">
                    <form action="{{ route('schooladmin.applications.update-notes', $application) }}" method="POST"
                        class="space-y-3">
                        @csrf
                        @method('PATCH')
                        <div>
                            <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                                Interviewer Remarks &amp; Evaluation Summary
                            </label>
                            <textarea name="admin_notes" rows="4"
                                placeholder="Enter interview feedback, panel evaluation remarks, candidate strengths, subject mastery observations, or hiring recommendations..."
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255E]/20 outline-none text-xs text-gray-800 bg-white leading-relaxed resize-y font-sans shadow-2xs">{{ old('admin_notes', $application->admin_notes) }}</textarea>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="text-[11px] text-gray-500 italic">
                                Notes saved here will be recorded and displayed on the official application PDF.
                            </p>
                            <button type="submit"
                                class="inline-flex items-center gap-1.5 px-5 py-2 text-white font-bold text-xs rounded-full transition-all cursor-pointer shadow-xs hover:opacity-90"
                                style="background:#21255E;">
                                <span class="material-symbols-outlined text-[15px]">save</span>
                                Save Interview Notes
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Printable & Static Notes Display --}}
                @if($application->admin_notes)
                    <div
                        class="hidden print:block border border-gray-200 rounded-xl p-4 bg-white text-xs text-gray-800 leading-relaxed font-sans mb-4">
                        <strong class="text-gray-500 uppercase text-[10px] block mb-1">Recorded Evaluation Notes:</strong>
                        {!! nl2br(e($application->admin_notes)) !!}
                    </div>
                @endif

                {{-- Scheduled / Completed Interview Records --}}
                @if($application->interviews && $application->interviews->count() > 0)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2.5">
                            Scheduled &amp; Conducted Interview Rounds
                        </p>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs border border-gray-200 rounded-xl overflow-hidden bg-white">
                                <thead
                                    class="bg-gray-50 text-gray-500 font-bold uppercase text-[10px] border-b border-gray-200">
                                    <tr>
                                        <th class="px-3.5 py-2">Round / Date</th>
                                        <th class="px-3.5 py-2">Mode &amp; Venue</th>
                                        <th class="px-3.5 py-2">Panel Members</th>
                                        <th class="px-3.5 py-2">Status &amp; Score</th>
                                        <th class="px-3.5 py-2">Feedback</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($application->interviews as $inv)
                                        <tr>
                                            <td class="px-3.5 py-2.5 font-bold text-gray-900 whitespace-nowrap">
                                                {{ \Carbon\Carbon::parse($inv->scheduled_date)->format('d M Y') }}
                                                <span
                                                    class="text-gray-500 font-normal block text-[11px]">{{ \Carbon\Carbon::parse($inv->scheduled_time)->format('h:i A') }}</span>
                                            </td>
                                            <td class="px-3.5 py-2.5 text-gray-700">
                                                <span
                                                    class="font-semibold">{{ ucfirst(str_replace('_', ' ', $inv->location_type)) }}</span>
                                                @if($inv->location_address_or_link)
                                                    <span
                                                        class="text-gray-500 block text-[11px] truncate max-w-xs">{{ $inv->location_address_or_link }}</span>
                                                @endif
                                            </td>
                                            <td class="px-3.5 py-2.5 text-gray-700">
                                                {{ $inv->panel_members ?: '—' }}
                                            </td>
                                            <td class="px-3.5 py-2.5 whitespace-nowrap">
                                                <span
                                                    class="inline-block px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $inv->status === 'completed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($inv->status === 'cancelled' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                                    {{ $inv->status }}
                                                </span>
                                                @if($inv->score !== null)
                                                    <span
                                                        class="font-extrabold text-gray-900 block text-[11px] mt-0.5">{{ $inv->score }}
                                                        / 100</span>
                                                @endif
                                            </td>
                                            <td class="px-3.5 py-2.5 text-gray-700 text-[11px]">
                                                {{ $inv->feedback ?: ($inv->remarks ?: '—') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
            {{-- ── 8. FORMAL DOCUMENT FOOTER & DISCLAIMS ── --}}
            <div
                class="p-6 bg-gray-50 flex flex-col sm:flex-row items-center justify-between gap-4 text-[10px] text-gray-500 font-medium">
                <div>
                    <p class="font-bold text-gray-700">{{ $application->school->name ?? 'YES India Career Portal' }} &bull;
                        Official Candidate Record</p>
                    <p>Generated on {{ now()->format('d M Y, h:i A') }} &bull; Reference: {{ $application->reference_no }}
                    </p>
                </div>
            </div>

        </div>
        {{-- ══ END FORMAL DOCUMENT ══ --}}

    </div>

    @php
        $latestInterview = $application->interviews ? $application->interviews->sortByDesc('id')->first() : null;
    @endphp

    {{-- ── INTERVIEW SCHEDULING MODAL (School Admin) ── --}}
    <div id="interviewScheduleModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 print:hidden">
        <div class="relative w-full max-w-xl bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            {{-- Modal Header --}}
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-[#21255E]">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center text-white">
                        <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-white tracking-wide">Schedule Interview</h3>
                        <p class="text-xs text-gray-200 font-normal truncate max-w-sm">
                            {{ $application->full_name }} &bull; Ref: {{ $application->reference_no }}
                        </p>
                    </div>
                </div>
                <button type="button" onclick="closeInterviewModal()" class="text-gray-300 hover:text-white transition-colors p-1.5 rounded-lg hover:bg-white/10 cursor-pointer">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            {{-- Modal Form --}}
            <form action="{{ route('schooladmin.applications.update-status', $application) }}" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="interview_scheduled">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Date --}}
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Interview Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="scheduled_date" required min="{{ date('Y-m-d') }}"
                            value="{{ $latestInterview?->scheduled_date ? \Carbon\Carbon::parse($latestInterview->scheduled_date)->format('Y-m-d') : date('Y-m-d', strtotime('+1 day')) }}"
                            class="w-full text-xs font-semibold px-3 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#21255E] focus:border-[#21255E] outline-none bg-white text-gray-900 shadow-xs">
                    </div>

                    {{-- Time --}}
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Interview Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="scheduled_time" required
                            value="{{ $latestInterview?->scheduled_time ? date('H:i', strtotime($latestInterview->scheduled_time)) : '10:30' }}"
                            class="w-full text-xs font-semibold px-3 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#21255E] focus:border-[#21255E] outline-none bg-white text-gray-900 shadow-xs">
                    </div>

                    {{-- Interview Mode / Format --}}
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Interview Format <span class="text-red-500">*</span>
                        </label>
                        <select name="location_type" id="locationTypeInput" onchange="toggleLocationPlaceholder(this.value)" required
                            class="w-full text-xs font-semibold px-3 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#21255E] focus:border-[#21255E] outline-none bg-white text-gray-900 shadow-xs">
                            <option value="in_person" {{ ($latestInterview?->location_type ?? 'in_person') === 'in_person' ? 'selected' : '' }}>
                                In-Person (Campus / Office)
                            </option>
                            <option value="online" {{ ($latestInterview?->location_type ?? '') === 'online' ? 'selected' : '' }}>
                                Online (Google Meet / Zoom)
                            </option>
                        </select>
                    </div>

                    {{-- Venue Address or Online Link --}}
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Venue / Meeting Link <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="location_address_or_link" id="locationAddressInput" required
                            placeholder="e.g. Pared Campus"
                            value="{{ $latestInterview?->location_address_or_link ?? 'Pared Campus' }}"
                            class="w-full text-xs font-semibold px-3 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#21255E] focus:border-[#21255E] outline-none bg-white text-gray-900 shadow-xs">
                    </div>
                </div>

                {{-- Panel Members --}}
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                        Panel Members <span class="text-gray-400 font-normal text-[10px]">(Optional)</span>
                    </label>
                    <input type="text" name="panel_members"
                        placeholder="e.g. Principal, Department Head, Subject Expert"
                        value="{{ $latestInterview?->panel_members ?? '' }}"
                        class="w-full text-xs font-medium px-3.5 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#21255E] focus:border-[#21255E] outline-none bg-white text-gray-900 shadow-xs">
                </div>

                {{-- Candidate Instructions / Special Remarks --}}
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                        Candidate Instructions <span class="text-gray-400 font-normal text-[10px]">(Optional)</span>
                    </label>
                    <textarea name="remarks" rows="2"
                        placeholder="e.g. Please bring original certificates and arrive 15 minutes before the scheduled time."
                        class="w-full text-xs font-medium p-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#21255E] focus:border-[#21255E] outline-none bg-white text-gray-900 shadow-xs">{{ $latestInterview?->remarks ?? '' }}</textarea>
                </div>

                {{-- Internal Notes --}}
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                        Internal Notes <span class="text-gray-400 font-normal text-[10px]">(Optional)</span>
                    </label>
                    <input type="text" name="admin_notes"
                        placeholder="Confidential notes for admin records..."
                        value="{{ $application->admin_notes }}"
                        class="w-full text-xs font-medium px-3.5 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#21255E] focus:border-[#21255E] outline-none bg-white text-gray-900 shadow-xs">
                </div>

                {{-- Modal Actions --}}
                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-gray-100">
                    <button type="button" onclick="closeInterviewModal()"
                        class="px-4 py-2 text-xs font-bold text-gray-600 hover:text-gray-800 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 px-5 py-2.5 text-xs font-bold text-white rounded-xl transition-all shadow-xs cursor-pointer hover:opacity-95"
                        style="background: #21255E;">
                        <span class="material-symbols-outlined text-[16px]">check</span>
                        Schedule &amp; Send Details
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openInterviewModal() {
            const modal = document.getElementById('interviewScheduleModal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeInterviewModal() {
            const modal = document.getElementById('interviewScheduleModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
            const select = document.getElementById('stageSelect');
            if (select) {
                select.value = '{{ $application->status }}';
            }
        }

        function selectStage(st) {
            if (st === 'interview_scheduled') {
                openInterviewModal();
            } else {
                const input = document.getElementById('stageStatusInput');
                const form = document.getElementById('stageForm');
                if (input && form) {
                    input.value = st;
                    form.submit();
                }
            }
        }

        function handleStageChange(select) {
            if (select.value === 'interview_scheduled') {
                openInterviewModal();
            } else {
                select.form.submit();
            }
        }

        function toggleLocationPlaceholder(type) {
            const input = document.getElementById('locationAddressInput');
            if (!input) return;
            if (type === 'online') {
                input.placeholder = 'https://meet.google.com/xxx-yyyy-zzz or Zoom link';
                if (!input.value || input.value.includes('Main Campus')) {
                    input.value = 'https://meet.google.com/sch-recruitment-desk';
                }
            } else {
                input.placeholder = 'e.g. Main Campus - Administrative Boardroom (Block B)';
                if (!input.value || input.value.includes('meet.google.com')) {
                    input.value = 'Main Campus - Administrative Boardroom (Block B)';
                }
            }
        }

        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeInterviewModal();
            }
        });
    </script>
@endsection