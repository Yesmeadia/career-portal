@extends('layouts.admin')

@section('title', 'Interview Calendar')

@section('content')
    @php
        $eventsData = $interviews->map(function($inv) {
            return [
                'id' => $inv->id,
                'application_id' => $inv->application_id,
                'candidate_name' => $inv->application->full_name ?? 'Candidate',
                'candidate_photo' => $inv->application->photo_url ?? asset('images/default-avatar.png'),
                'reference_no' => $inv->application->reference_no ?? '—',
                'position_title' => $inv->application->vacancy->title ?? 'General Position',
                'department' => $inv->application->vacancy->department ?? '',
                'date' => \Carbon\Carbon::parse($inv->scheduled_date)->format('Y-m-d'),
                'time' => date('h:i A', strtotime($inv->scheduled_time)),
                'time_raw' => $inv->scheduled_time,
                'location_type' => $inv->location_type,
                'location_address_or_link' => $inv->location_address_or_link,
                'panel_members' => $inv->panel_members,
                'remarks' => $inv->remarks,
                'status' => $inv->status,
                'show_url' => route('schooladmin.applications.show', $inv->application_id),
            ];
        });

        $totalInterviews = $interviews->count();
        $todayInterviews = $interviews->filter(fn($i) => \Carbon\Carbon::parse($i->scheduled_date)->isToday())->count();
        $inPersonCount = $interviews->where('location_type', 'in_person')->count();
        $onlineCount = $interviews->where('location_type', 'online')->count();
    @endphp

    <div class="max-w-[1400px] mx-auto space-y-6" x-data="interviewCalendar({
        events: {{ Js::from($eventsData) }},
        todayDate: '{{ now()->format('Y-m-d') }}'
    })">

        {{-- ── 1. PAGE HEADER & VIEW SWITCHER ── --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-[#111827] tracking-tight leading-none">
                    Interview Schedules
                </h2>
                <p class="text-xs text-gray-500 font-medium mt-1">Interactive calendar view of candidate interview timelines and appointments.</p>
            </div>

            {{-- Top Right View Mode Switcher --}}
            <div class="flex items-center gap-3">
                <div class="bg-white shadow-sm border border-gray-100 p-1.5 inline-flex gap-2" style="border-radius: 20px;">
                    <a href="{{ route('schooladmin.interviews.index') }}"
                        class="px-5 py-2 rounded-full text-xs font-bold text-gray-600 hover:bg-gray-100/80 flex items-center gap-1.5 transition-all">
                        <span class="material-symbols-outlined text-[18px]">format_list_bulleted</span>
                        <span>List View</span>
                    </a>
                    <a href="{{ route('schooladmin.interviews.calendar') }}"
                        class="px-5 py-2 rounded-full text-xs font-bold bg-[#21255E] text-white shadow-2xs flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                        <span>Calendar View</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- ── 2. QUICK STATS SUMMARY TILES ── --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-xs flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center shrink-0 border border-blue-100">
                    <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Total Scheduled</span>
                    <span class="text-lg font-extrabold text-gray-900">{{ $totalInterviews }}</span>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-xs flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 border border-amber-100">
                    <span class="material-symbols-outlined text-[20px]">today</span>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Interviews Today</span>
                    <span class="text-lg font-extrabold text-amber-700">{{ $todayInterviews }}</span>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-xs flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 border border-emerald-100">
                    <span class="material-symbols-outlined text-[20px]">location_city</span>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">In-Person Campus</span>
                    <span class="text-lg font-extrabold text-gray-900">{{ $inPersonCount }}</span>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-xs flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center shrink-0 border border-indigo-100">
                    <span class="material-symbols-outlined text-[20px]">videocam</span>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Online Video Calls</span>
                    <span class="text-lg font-extrabold text-gray-900">{{ $onlineCount }}</span>
                </div>
            </div>
        </div>

        {{-- ── 3. MAIN CALENDAR GRID & DAY AGENDA SPLIT ── --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- ── LEFT: INTERACTIVE MONTH CALENDAR (8 Cols) ── --}}
            <div class="lg:col-span-8 bg-white rounded-3xl shadow-sm border border-gray-100 p-6 space-y-5">
                {{-- Calendar Header: Month, Nav Arrows, and Format Filters --}}
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-gray-100 pb-5">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1 bg-gray-50 border border-gray-200 p-1 rounded-xl">
                            <button type="button" @click="prevMonth()" class="p-1.5 hover:bg-white rounded-lg text-gray-600 hover:text-gray-900 transition-all cursor-pointer" title="Previous Month">
                                <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                            </button>
                            <button type="button" @click="goToToday()" class="px-3 py-1 text-xs font-bold text-gray-700 hover:bg-white rounded-lg transition-all cursor-pointer">
                                Today
                            </button>
                            <button type="button" @click="nextMonth()" class="p-1.5 hover:bg-white rounded-lg text-gray-600 hover:text-gray-900 transition-all cursor-pointer" title="Next Month">
                                <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                            </button>
                        </div>
                        <h3 class="text-lg font-extrabold text-gray-900 tracking-tight" x-text="monthYearTitle"></h3>
                    </div>

                    {{-- Format Filter Chips --}}
                    <div class="flex items-center gap-1.5 bg-gray-50 p-1 rounded-xl border border-gray-200 text-xs">
                        <button type="button" @click="filterType = 'all'"
                            :class="filterType === 'all' ? 'bg-[#21255E] text-white shadow-xs' : 'text-gray-600 hover:text-gray-900'"
                            class="px-3 py-1 rounded-lg font-bold transition-all cursor-pointer">
                            All
                        </button>
                        <button type="button" @click="filterType = 'in_person'"
                            :class="filterType === 'in_person' ? 'bg-[#21255E] text-white shadow-xs' : 'text-gray-600 hover:text-gray-900'"
                            class="px-3 py-1 rounded-lg font-bold transition-all cursor-pointer">
                            In-Person
                        </button>
                        <button type="button" @click="filterType = 'online'"
                            :class="filterType === 'online' ? 'bg-[#21255E] text-white shadow-xs' : 'text-gray-600 hover:text-gray-900'"
                            class="px-3 py-1 rounded-lg font-bold transition-all cursor-pointer">
                            Online
                        </button>
                    </div>
                </div>

                {{-- 7-Day Header --}}
                <div class="grid grid-cols-7 text-center text-[11px] font-bold text-gray-400 uppercase tracking-wider py-1 border-b border-gray-50">
                    <div>Sun</div>
                    <div>Mon</div>
                    <div>Tue</div>
                    <div>Wed</div>
                    <div>Thu</div>
                    <div>Fri</div>
                    <div>Sat</div>
                </div>

                {{-- Calendar Dates Grid (35 or 42 cells) --}}
                <div class="grid grid-cols-7 gap-1 sm:gap-1.5">
                    <template x-for="(day, index) in calendarDays" :key="index">
                        <div @click="selectDate(day.dateString)"
                            :class="{
                                'bg-gray-50/40 text-gray-300': !day.isCurrentMonth,
                                'bg-white hover:bg-slate-50/80 cursor-pointer': day.isCurrentMonth,
                                'ring-2 ring-[#21255E] border-transparent bg-blue-50/30': selectedDate === day.dateString,
                                'border-gray-200': selectedDate !== day.dateString
                            }"
                            class="min-h-[85px] sm:min-h-[95px] p-1.5 sm:p-2 rounded-2xl border transition-all flex flex-col justify-between group relative select-none">
                            
                            {{-- Day Header (Date Number & Indicator) --}}
                            <div class="flex items-center justify-between">
                                <span :class="{
                                    'w-6 h-6 rounded-full bg-[#21255E] text-white font-extrabold flex items-center justify-center shadow-xs': day.isToday,
                                    'text-xs font-bold text-gray-700': day.isCurrentMonth && !day.isToday,
                                    'text-xs font-medium text-gray-300': !day.isCurrentMonth
                                }" x-text="day.dayNumber"></span>

                                {{-- Event count badge --}}
                                <template x-if="getEventsForDay(day.dateString).length > 0">
                                    <span class="w-5 h-5 rounded-full bg-blue-500 text-white font-extrabold text-[10px] flex items-center justify-center shadow-2xs"
                                        x-text="getEventsForDay(day.dateString).length">
                                    </span>
                                </template>
                            </div>

                            {{-- Marked Interview Pills on this Date --}}
                            <div class="space-y-1 mt-1 overflow-hidden">
                                <template x-for="(ev, evIdx) in getEventsForDay(day.dateString).slice(0, 2)" :key="ev.id">
                                    <div :class="ev.location_type === 'online' ? 'bg-indigo-50 border-indigo-200 text-indigo-800' : 'bg-emerald-50 border-emerald-200 text-emerald-800'"
                                        class="px-1.5 py-0.5 rounded-md border text-[10px] font-bold truncate flex items-center gap-1 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full shrink-0" :class="ev.location_type === 'online' ? 'bg-indigo-500' : 'bg-emerald-500'"></span>
                                        <span class="truncate" x-text="ev.candidate_name"></span>
                                    </div>
                                </template>

                                {{-- More than 2 events indicator --}}
                                <template x-if="getEventsForDay(day.dateString).length > 2">
                                    <span class="text-[9px] font-bold text-gray-400 block px-1 truncate"
                                        x-text="'+' + (getEventsForDay(day.dateString).length - 2) + ' more'">
                                    </span>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Calendar Legend --}}
                <div class="flex items-center gap-4 text-[11px] font-medium text-gray-500 pt-3 border-t border-gray-100 flex-wrap">
                    <span class="font-bold text-gray-700">Legend:</span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#21255E]"></span>
                        <span>Today</span>
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <span>In-Person Campus</span>
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                        <span>Online Video Call</span>
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                        <span>Interview Count</span>
                    </span>
                </div>
            </div>

            {{-- ── RIGHT: SELECTED DAY AGENDA DRAWER (4 Cols) ── --}}
            <div class="lg:col-span-4 bg-white rounded-3xl shadow-sm border border-gray-100 p-6 space-y-4 flex flex-col">
                {{-- Drawer Header --}}
                <div class="border-b border-gray-100 pb-4">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Day Schedule</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-blue-50 text-blue-800 border border-blue-100"
                            x-text="selectedDayEvents.length + ' Candidate(s)'"></span>
                    </div>
                    <h3 class="text-base font-extrabold text-gray-900 mt-1" x-text="formattedSelectedDate"></h3>
                </div>

                {{-- List of Interviews for Selected Day --}}
                <div class="flex-1 overflow-y-auto space-y-3 pr-1 max-h-[580px]">
                    <template x-if="selectedDayEvents.length === 0">
                        <div class="py-16 text-center space-y-3">
                            <div class="w-12 h-12 rounded-2xl bg-gray-50 border border-gray-200 text-gray-400 flex items-center justify-center mx-auto">
                                <span class="material-symbols-outlined text-[24px]">event_available</span>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-700">No interviews scheduled</p>
                                <p class="text-[11px] text-gray-400 mt-0.5">There are no candidate interviews on this selected date.</p>
                            </div>
                            <button type="button" @click="goToToday()" class="px-3.5 py-1.5 text-xs font-bold text-[#21255E] bg-blue-50 hover:bg-blue-100 rounded-xl transition-all cursor-pointer">
                                Jump to Today
                            </button>
                        </div>
                    </template>

                    <template x-for="event in selectedDayEvents" :key="event.id">
                        <div class="p-4 rounded-2xl border border-gray-200 bg-white hover:border-[#21255E]/40 hover:shadow-sm transition-all space-y-3">
                            {{-- Header: Time Badge & Format Badge --}}
                            <div class="flex items-center justify-between gap-2">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-gray-100 text-gray-800 font-bold text-xs">
                                    <span class="material-symbols-outlined text-[14px]">schedule</span>
                                    <span x-text="event.time"></span>
                                </span>

                                <span :class="event.location_type === 'online' ? 'bg-indigo-50 text-indigo-800 border-indigo-200' : 'bg-emerald-50 text-emerald-800 border-emerald-200'"
                                    class="px-2 py-0.5 rounded-full text-[10px] font-bold border uppercase tracking-wider"
                                    x-text="event.location_type === 'online' ? 'Online' : 'In-Person'">
                                </span>
                            </div>

                            {{-- Candidate Info --}}
                            <div class="flex items-start gap-3">
                                <img :src="event.candidate_photo" class="w-10 h-10 rounded-xl object-cover border border-gray-200 shrink-0" alt="Avatar">
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-sm font-extrabold text-gray-900 truncate" x-text="event.candidate_name"></h4>
                                    <p class="text-xs text-gray-500 font-medium truncate" x-text="event.position_title"></p>
                                    <span class="font-mono text-[10px] text-gray-400" x-text="'Ref: ' + event.reference_no"></span>
                                </div>
                            </div>

                            {{-- Venue / Meeting Link --}}
                            <div class="p-2.5 rounded-xl bg-gray-50 border border-gray-100 text-xs space-y-1">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block">Venue / Link</span>
                                <template x-if="event.location_address_or_link && event.location_address_or_link.startsWith('http')">
                                    <a :href="event.location_address_or_link" target="_blank" class="text-blue-600 underline font-bold block truncate" x-text="event.location_address_or_link"></a>
                                </template>
                                <template x-if="!event.location_address_or_link || !event.location_address_or_link.startsWith('http')">
                                    <span class="font-semibold text-gray-700 block truncate" x-text="event.location_address_or_link || 'Main Campus'"></span>
                                </template>
                            </div>

                            {{-- Panel Members (if specified) --}}
                            <template x-if="event.panel_members">
                                <div class="text-[11px] text-gray-600">
                                    <strong class="text-gray-800">Panel:</strong> <span x-text="event.panel_members"></span>
                                </div>
                            </template>

                            {{-- Instructions (if specified) --}}
                            <template x-if="event.remarks">
                                <div class="text-[11px] text-gray-500 italic bg-amber-50/50 p-2 rounded-lg border border-amber-100">
                                    <span x-text="event.remarks"></span>
                                </div>
                            </template>

                            {{-- View Application Link --}}
                            <div class="pt-1">
                                <a :href="event.show_url" class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-bold text-[#21255E] bg-blue-50/80 hover:bg-blue-100 border border-blue-200 rounded-xl transition-all">
                                    <span class="material-symbols-outlined text-[15px]">badge</span>
                                    <span>View Candidate Dossier</span>
                                </a>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

        </div>

    </div>

    {{-- ── 4. ALPINE.JS CALENDAR COMPONENT LOGIC ── --}}
    <script>
        function interviewCalendar(config) {
            return {
                allEvents: config.events || [],
                todayDate: config.todayDate || new Date().toISOString().slice(0, 10),
                currentYear: new Date().getFullYear(),
                currentMonth: new Date().getMonth(), // 0-indexed (0 = Jan, 7 = Aug)
                selectedDate: config.todayDate || new Date().toISOString().slice(0, 10),
                filterType: 'all', // 'all', 'in_person', 'online'

                init() {
                    // If today has no events, pick the first event date in current month if available
                    const todayEvents = this.getEventsForDay(this.todayDate);
                    if (todayEvents.length === 0 && this.allEvents.length > 0) {
                        this.selectedDate = this.allEvents[0].date;
                        const firstEventDate = new Date(this.allEvents[0].date + 'T00:00:00');
                        if (!isNaN(firstEventDate)) {
                            this.currentYear = firstEventDate.getFullYear();
                            this.currentMonth = firstEventDate.getMonth();
                        }
                    }
                },

                get monthYearTitle() {
                    const date = new Date(this.currentYear, this.currentMonth, 1);
                    return date.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
                },

                get formattedSelectedDate() {
                    if (!this.selectedDate) return 'Select a date';
                    const parts = this.selectedDate.split('-');
                    const date = new Date(parts[0], parts[1] - 1, parts[2]);
                    return date.toLocaleDateString('en-US', { weekday: 'long', month: 'short', day: 'numeric', year: 'numeric' });
                },

                get calendarDays() {
                    const year = this.currentYear;
                    const month = this.currentMonth;
                    const firstDayOfMonth = new Date(year, month, 1).getDay(); // 0 = Sun
                    const daysInMonth = new Date(year, month + 1, 0).getDate();
                    const daysInPrevMonth = new Date(year, month, 0).getDate();

                    const days = [];

                    // Leading days from previous month
                    for (let i = firstDayOfMonth - 1; i >= 0; i--) {
                        const dayNum = daysInPrevMonth - i;
                        const prevMonthDate = new Date(year, month - 1, dayNum);
                        const dateString = this.formatDateString(prevMonthDate);
                        days.push({
                            dayNumber: dayNum,
                            dateString: dateString,
                            isCurrentMonth: false,
                            isToday: dateString === this.todayDate
                        });
                    }

                    // Days of current month
                    for (let i = 1; i <= daysInMonth; i++) {
                        const date = new Date(year, month, i);
                        const dateString = this.formatDateString(date);
                        days.push({
                            dayNumber: i,
                            dateString: dateString,
                            isCurrentMonth: true,
                            isToday: dateString === this.todayDate
                        });
                    }

                    // Trailing days from next month to complete 35 or 42 grid
                    const totalCells = days.length <= 35 ? 35 : 42;
                    const remainingDays = totalCells - days.length;
                    for (let i = 1; i <= remainingDays; i++) {
                        const nextMonthDate = new Date(year, month + 1, i);
                        const dateString = this.formatDateString(nextMonthDate);
                        days.push({
                            dayNumber: i,
                            dateString: dateString,
                            isCurrentMonth: false,
                            isToday: dateString === this.todayDate
                        });
                    }

                    return days;
                },

                get selectedDayEvents() {
                    return this.getEventsForDay(this.selectedDate);
                },

                getEventsForDay(dateString) {
                    return this.allEvents.filter(event => {
                        const matchesDate = event.date === dateString;
                        if (!matchesDate) return false;
                        if (this.filterType === 'all') return true;
                        return event.location_type === this.filterType;
                    });
                },

                selectDate(dateString) {
                    this.selectedDate = dateString;
                },

                prevMonth() {
                    if (this.currentMonth === 0) {
                        this.currentMonth = 11;
                        this.currentYear--;
                    } else {
                        this.currentMonth--;
                    }
                },

                nextMonth() {
                    if (this.currentMonth === 11) {
                        this.currentMonth = 0;
                        this.currentYear++;
                    } else {
                        this.currentMonth++;
                    }
                },

                goToToday() {
                    const today = new Date(this.todayDate + 'T00:00:00');
                    this.currentYear = today.getFullYear();
                    this.currentMonth = today.getMonth();
                    this.selectedDate = this.todayDate;
                },

                formatDateString(date) {
                    const y = date.getFullYear();
                    const m = String(date.getMonth() + 1).padStart(2, '0');
                    const d = String(date.getDate()).padStart(2, '0');
                    return `${y}-${m}-${d}`;
                }
            };
        }
    </script>
@endsection
