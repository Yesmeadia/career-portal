@extends('layouts.admin')

@section('title', 'Manage Schools')

@section('content')
    <div class="max-w-[1400px] mx-auto">

        {{-- Page Header & Live System Clock --}}
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4"
             x-data="{
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
                    <h2 class="text-4xl font-extrabold text-[#111827] tracking-tight leading-none" style="font-size: 38px;">Campus Details</h2>
                </div>
            </div>

            {{-- Top Right Actions --}}
            <div class="flex items-center gap-3">
                <a href="#filters-section"
                    class="bg-white border border-gray-200 text-gray-600 px-4 py-2 rounded-full font-label-md text-[13px] flex items-center gap-2 hover:bg-gray-50 transition-all shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">filter_list</span> Search & Filters
                </a>
                <a href="{{ route('superadmin.schools.create') }}"
                    class="text-white px-5 py-2 rounded-full font-label-md text-[13px] flex items-center gap-2 hover:opacity-90 transition-all shadow-md active:scale-95 font-bold"
                    style="background-color: #D7B56D;">
                    <span class="material-symbols-outlined text-[18px]">add_location_alt</span> Add New School
                </a>
            </div>
        </div>

        {{-- Filter & Directory Panel --}}
        <div id="filters-section" class="bg-white shadow-sm border border-gray-100 p-5 mb-6" style="border-radius: 20px;">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div>
                        <h3 class="font-headline-sm text-[18px] font-bold text-[#111827]">Schools Directory</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Filter by institution name, status, or location details.</p>
                    </div>
                </div>

                <!-- Filter Form -->
                <form action="{{ route('superadmin.schools.index') }}" method="GET"
                    class="flex flex-wrap items-center gap-3">
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">search</span>
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                            placeholder="Search schools..."
                            class="pl-10 pr-4 py-2 rounded-full border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-xs text-gray-700 w-56 sm:w-64 bg-gray-50/50">
                    </div>

                    <select name="status"
                        class="px-4 py-2 rounded-full border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-xs text-gray-700 bg-gray-50/50">
                        <option value="">All Statuses</option>
                        <option value="active" {{ isset($filters['status']) && $filters['status'] === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ isset($filters['status']) && $filters['status'] === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>

                    <button type="submit"
                        class="bg-[#21255E] hover:bg-[#1a1d4b] text-white px-4 py-2 rounded-full text-xs font-semibold transition-all shadow-xs flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">filter_alt</span> Filter
                    </button>

                    @if(array_filter($filters))
                        <a href="{{ route('superadmin.schools.index') }}"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-full text-xs font-semibold transition-colors flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">close</span> Clear
                        </a>
                    @endif
                </form>
            </div>
        </div>

        {{-- Schools Table Card --}}
        <div class="bg-white shadow-sm border border-gray-100 overflow-hidden mb-6" style="border-radius: 20px;">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50/70 border-b border-gray-100 text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Campus Profile</th>
                            <th class="px-6 py-4">Contact & Location</th>
                            <th class="px-6 py-4 text-center">Vacancies</th>
                            <th class="px-6 py-4 text-center">Applications</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @forelse($schools as $school)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-[#111827] text-[14px] leading-snug">{{ $school->name }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-800 flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[15px] text-gray-400">mail</span>
                                        {{ $school->email }}
                                    </p>
                                    <p class="text-[11px] text-gray-500 mt-1 flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[15px] text-gray-400">location_on</span>
                                        {{ $school->city ?? 'Poonch' }}, {{ $school->state ?? 'J&K' }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 font-bold text-xs border border-emerald-200/60">
                                        {{ $school->vacancies_count ?? 0 }} Jobs
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-blue-50 text-blue-700 font-bold text-xs border border-blue-200/60">
                                        {{ $school->applications_count ?? 0 }} Apps
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($school->status === 'active')
                                        <span class="rounded-full px-3 py-1 text-[11px] font-bold bg-green-50 text-green-700 border border-green-200 inline-flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span> Active
                                        </span>
                                    @else
                                        <span class="rounded-full px-3 py-1 text-[11px] font-bold bg-red-50 text-red-700 border border-red-200 inline-flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full bg-red-500"></span> Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('superadmin.schools.edit', $school) }}"
                                            class="w-8 h-8 rounded-full bg-gray-50 border border-gray-200 hover:bg-blue-50 hover:text-blue-700 hover:border-blue-200 flex items-center justify-center transition-colors text-gray-600 shadow-2xs"
                                            title="Edit School">
                                            <span class="material-symbols-outlined text-[17px]">edit</span>
                                        </a>

                                        <form action="{{ route('superadmin.schools.toggle-status', $school) }}" method="POST" class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="w-8 h-8 rounded-full bg-gray-50 border border-gray-200 hover:bg-amber-50 hover:text-amber-700 hover:border-amber-200 flex items-center justify-center transition-colors text-gray-600 shadow-2xs"
                                                title="{{ $school->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                                <span class="material-symbols-outlined text-[17px]">
                                                    {{ $school->status === 'active' ? 'toggle_off' : 'toggle_on' }}
                                                </span>
                                            </button>
                                        </form>

                                        <form action="{{ route('superadmin.schools.destroy', $school) }}" method="POST"
                                            class="inline"
                                            data-confirm="Are you sure you want to delete school '{{ $school->name }}'? This action cannot be undone."
                                            data-confirm-title="Delete School Campus" data-confirm-btn="Yes, Delete">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="w-8 h-8 rounded-full bg-gray-50 border border-gray-200 hover:bg-red-50 hover:text-red-700 hover:border-red-200 flex items-center justify-center transition-colors text-gray-600 shadow-2xs cursor-pointer"
                                                title="Delete School">
                                                <span class="material-symbols-outlined text-[17px]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                    <div class="w-14 h-14 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-3 text-2xl">
                                        <span class="material-symbols-outlined text-[32px]">school</span>
                                    </div>
                                    <p class="font-bold text-gray-800 text-base">No schools found</p>
                                    <p class="text-xs text-gray-400 mt-1 mb-4">Start by registering your first educational institution.</p>
                                    <a href="{{ route('superadmin.schools.create') }}"
                                        class="inline-flex items-center gap-2 text-white text-xs px-5 py-2.5 rounded-full font-bold transition-all shadow-md"
                                        style="background-color: #D7B56D;">
                                        <span class="material-symbols-outlined text-[18px]">add</span> Add School
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($schools->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $schools->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection