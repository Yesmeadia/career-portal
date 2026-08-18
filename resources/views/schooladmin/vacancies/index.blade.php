@extends('layouts.admin')

@section('title', 'Manage Job Vacancies')

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
                        Manage Job Vacancies</h2>
                </div>
            </div>

            {{-- Top Right Actions --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('schooladmin.vacancies.create') }}"
                    class="text-white font-bold text-xs px-6 py-3 rounded-full transition-all shadow-md flex items-center gap-2 active:scale-95 cursor-pointer"
                    style="background-color: #D7B56D;">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    <span>Post New Vacancy</span>
                </a>
            </div>
        </div>

        <!-- Filter & Search Bar Card -->
        <div class="bg-white shadow-sm border border-gray-100 p-4 mb-6" style="border-radius: 20px;">
            <form action="{{ route('schooladmin.vacancies.index') }}" method="GET"
                class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex flex-1 items-center gap-3 w-full">
                    <div class="relative flex-1">
                        <span
                            class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">search</span>
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                            placeholder="Search vacancies by title or department..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-full border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-xs font-medium text-gray-900 bg-gray-50/50">
                    </div>
                    <select name="status"
                        class="px-4 py-2.5 rounded-full border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-xs font-semibold text-gray-700 bg-gray-50/50">
                        <option value="">All Statuses</option>
                        @foreach(['draft' => 'Draft', 'published' => 'Published', 'closed' => 'Closed', 'expired' => 'Expired', 'archived' => 'Archived'] as $val => $label)
                            <option value="{{ $val }}" {{ isset($filters['status']) && $filters['status'] === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit"
                        class="px-5 py-2.5 bg-[#21255E] hover:bg-[#171a44] text-white text-xs font-bold rounded-full transition-all shadow-2xs">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Directory Table Card -->
        <div class="bg-white shadow-sm border border-gray-100 overflow-hidden mb-6" style="border-radius: 20px;">
            <table class="w-full text-left text-sm border-collapse">
                <thead>
                    <tr
                        class="bg-gray-50/80 border-b border-gray-100 text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Title &amp; Type</th>
                        <th class="px-6 py-4">Department &amp; Class</th>
                        <th class="px-6 py-4">Applications</th>
                        <th class="px-6 py-4">Deadline</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($vacancies as $job)
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-bold text-gray-900 text-sm leading-snug">{{ $job->title }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span
                                        class="text-xs text-gray-400 font-medium">{{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}</span>
                                    @if($job->is_featured)
                                        <span
                                            class="text-[10px] font-bold bg-amber-50 text-amber-700 px-2 py-0.5 rounded-full border border-amber-200">
                                            Featured</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-gray-900 font-semibold text-xs">{{ $job->department->name ?? 'N/A' }}</p>
                                <p class="text-[11px] text-gray-400 font-medium">{{ $job->globalClass?->name ?? 'Campus Wide' }}
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('schooladmin.applications.index', ['vacancy_id' => $job->id]) }}"
                                    class="inline-flex items-center gap-1 font-bold text-blue-600 hover:underline text-xs">
                                    <span>{{ $job->applications_count }} Candidates</span>
                                </a>
                            </td>
                            <td class="px-6 py-4 text-xs font-medium text-gray-500">
                                {{ $job->deadline ? $job->deadline->format('M d, Y') : 'No deadline' }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusStyles = [
                                        'published' => 'bg-green-50 text-green-700 border-green-200',
                                        'draft' => 'bg-gray-50 text-gray-600 border-gray-200',
                                        'closed' => 'bg-red-50 text-red-700 border-red-200',
                                        'expired' => 'bg-orange-50 text-orange-700 border-orange-200',
                                        'archived' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    ];
                                @endphp
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-bold border {{ $statusStyles[$job->status] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">
                                    {{ ucfirst($job->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Toggle Status (Job Categories Pattern) --}}
                                    <form action="{{ route('schooladmin.vacancies.toggle-status', $job) }}" method="POST"
                                        class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="inline-flex items-center justify-center transition-all border cursor-pointer"
                                            style="width:32px;height:32px;border-radius:10px;background:#f8fafc;border-color:#e5e7eb;color: {{ strtolower($job->status) === 'published' ? '#22c55e' : '#94a3b8' }};"
                                            onmouseover="this.style.background='{{ strtolower($job->status) === 'published' ? '#21255e' : '#22c55e' }}';this.style.color='white';this.style.borderColor='transparent';"
                                            onmouseout="this.style.background='#f8fafc';this.style.color='{{ strtolower($job->status) === 'published' ? '#22c55e' : '#94a3b8' }}';this.style.borderColor='#e5e7eb';"
                                            title="{{ strtolower($job->status) === 'published' ? 'Disable Vacancy (Move to Draft)' : 'Enable Vacancy (Publish Live)' }}">
                                            <span class="material-symbols-outlined"
                                                style="font-size:20px;">{{ strtolower($job->status) === 'published' ? 'toggle_on' : 'toggle_off' }}</span>
                                        </button>
                                    </form>

                                    <a href="{{ route('schooladmin.vacancies.edit', $job) }}"
                                        class="w-8 h-8 rounded-full bg-gray-50 hover:bg-blue-50 text-gray-500 hover:text-blue-600 border border-gray-200 flex items-center justify-center transition-colors"
                                        title="Edit Vacancy">
                                        <span class="material-symbols-outlined text-[16px]">edit</span>
                                    </a>
                                    <form action="{{ route('schooladmin.vacancies.destroy', $job) }}" method="POST"
                                        class="inline" data-confirm="Are you sure you want to delete vacancy '{{ $job->title }}'? This action cannot be undone."
                                        data-confirm-title="Delete Vacancy" data-confirm-btn="Yes, Delete">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="w-8 h-8 rounded-full bg-gray-50 hover:bg-red-50 text-gray-500 hover:text-red-600 border border-gray-200 flex items-center justify-center transition-colors cursor-pointer"
                                            title="Delete Vacancy">
                                            <span class="material-symbols-outlined text-[16px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                No vacancies created yet. Post a job vacancy above.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if($vacancies->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $vacancies->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection