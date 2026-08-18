@extends('layouts.admin')

@section('title', 'Interview Schedules')

@section('content')
    <div class="max-w-[1400px] mx-auto">

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
                        Interview Schedules</h2>
                </div>
            </div>

            {{-- Top Right Actions --}}
            <div class="flex items-center gap-3">
                <div class="bg-white shadow-sm border border-gray-100 p-1.5 inline-flex gap-2" style="border-radius: 20px;">
                    <a href="{{ route('schooladmin.interviews.index') }}"
                        class="px-5 py-2 rounded-full text-xs font-bold bg-[#21255E] text-white shadow-2xs flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px]">format_list_bulleted</span>
                        <span>List View</span>
                    </a>
                    <a href="{{ route('schooladmin.interviews.calendar') }}"
                        class="px-5 py-2 rounded-full text-xs font-bold text-gray-600 hover:bg-gray-100/80 flex items-center gap-1.5 transition-all">
                        <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                        <span>Calendar View</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Container Card -->
        <div class="bg-white shadow-sm border border-gray-100 overflow-hidden mb-6" style="border-radius: 20px;">

            {{-- Header Bar --}}
            <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center text-purple-600 shrink-0"
                        style="width:36px;height:36px;border-radius:12px;background:#f3e8ff;">
                        <span class="material-symbols-outlined" style="font-size:20px;">event</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[#111827]">Scheduled Candidate Interviews</h3>
                        <p class="text-xs text-gray-500">Track candidates assigned for screening and interview venues.</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-[12px] text-gray-500 font-medium">
                    Showing <span class="font-bold text-[#111827] mx-1">{{ $interviews->count() }}</span> scheduled
                    interviews
                </div>
            </div>

            {{-- Data Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left" style="border-collapse: separate; border-spacing: 0;">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th
                                class="px-5 py-3 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                                Candidate &amp; Position</th>
                            <th
                                class="px-5 py-3 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                                Date &amp; Time</th>
                            <th
                                class="px-5 py-3 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                                Format &amp; Venue / Link</th>
                            <th
                                class="px-5 py-3 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                                Status</th>
                            <th
                                class="px-5 py-3 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100 text-right">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($interviews as $index => $inv)
                            @php
                                $rowBg = $index % 2 === 0 ? '#ffffff' : '#fafbfc';
                                $statusBadge = match ($inv->status) {
                                    'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'scheduled' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'rescheduled' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                                    default => 'bg-gray-100 text-gray-600 border-gray-200',
                                };
                                $dotColor = match ($inv->status) {
                                    'completed' => '#22c55e',
                                    'scheduled' => '#3b82f6',
                                    'rescheduled' => '#f59e0b',
                                    'cancelled' => '#ef4444',
                                    default => '#94a3b8',
                                };
                            @endphp
                            <tr style="background: {{ $rowBg }}; transition: background 0.15s;"
                                onmouseover="this.style.background='#f0f4ff'" onmouseout="this.style.background='{{ $rowBg }}'">

                                {{-- Candidate & Position --}}
                                <td class="px-5 py-4 border-b border-gray-50">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $inv->application->photo_url ?? asset('images/default-avatar.png') }}"
                                            class="object-cover border border-gray-200 shrink-0"
                                            style="width:38px;height:38px;border-radius:10px;"
                                            alt="{{ $inv->application->full_name ?? 'Candidate' }}"
                                            onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                                        <div class="min-w-0">
                                            <a href="{{ route('schooladmin.applications.show', $inv->application) }}"
                                                class="font-bold text-[#111827] hover:text-[#21255E] transition-colors block truncate"
                                                style="font-size:13px;max-width:180px;">
                                                {{ $inv->application->full_name ?? 'Candidate Name' }}
                                            </a>
                                            <p class="text-[11px] text-gray-500 font-medium truncate max-w-[180px]">
                                                {{ $inv->application->vacancy->title ?? 'General Position' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Date & Time --}}
                                <td class="px-5 py-4 border-b border-gray-50">
                                    <div class="flex items-center gap-1.5 text-blue-700 font-bold text-xs">
                                        <span>{{ \Carbon\Carbon::parse($inv->scheduled_date)->format('M d, Y') }}</span>
                                    </div>
                                    <p class="text-[11px] text-gray-400 font-mono mt-0.5">
                                        {{ date('h:i A', strtotime($inv->scheduled_time)) }}
                                    </p>
                                </td>

                                {{-- Format & Venue / Link --}}
                                <td class="px-5 py-4 border-b border-gray-50 max-w-[220px]">
                                    <span
                                        class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $inv->location_type === 'online' ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200' }}">
                                        {{ str_replace('_', ' ', $inv->location_type) }}
                                    </span>
                                    <p class="text-[11px] text-gray-500 mt-1 truncate font-mono"
                                        title="{{ $inv->location_address_or_link }}">
                                        {{ $inv->location_address_or_link }}
                                    </p>
                                </td>

                                {{-- Status --}}
                                <td class="px-5 py-4 border-b border-gray-50">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 text-[11px] font-bold border {{ $statusBadge }}"
                                        style="border-radius:999px;">
                                        <span
                                            style="width:7px;height:7px;border-radius:50%;background-color:{{ $dotColor }};display:inline-block;flex-shrink:0;"></span>
                                        {{ ucfirst($inv->status) }}
                                    </span>
                                </td>

                                {{-- Action --}}
                                <td class="px-5 py-4 border-b border-gray-50 text-right">
                                    <a href="{{ route('schooladmin.applications.show', $inv->application) }}"
                                        class="inline-flex items-center gap-1.5 text-[12px] font-bold text-[#21255E] hover:text-white transition-all px-3 py-1.5 border border-[#21255E]/20 hover:bg-[#21255E] hover:border-[#21255E]"
                                        style="border-radius:10px;" title="View Application Profile">
                                        <span class="material-symbols-outlined" style="font-size:15px;">open_in_new</span>
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="flex items-center justify-center text-gray-300"
                                            style="width:56px;height:56px;border-radius:18px;background:#f1f5f9;">
                                            <span class="material-symbols-outlined" style="font-size:28px;">event_busy</span>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-700 text-sm">No scheduled interviews found</p>
                                            <p class="text-xs text-gray-400 mt-1">Select candidates from Applications to
                                                schedule panel interviews.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($interviews->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $interviews->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection