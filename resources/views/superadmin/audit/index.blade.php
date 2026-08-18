@extends('layouts.admin')

@section('title', 'System Audit Logs')

@section('content')
    <div class="max-w-[1400px] mx-auto space-y-6">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="font-extrabold text-[#111827] tracking-tight leading-none" style="font-size: 38px;">
                    System Audit Logs
                </h1>
                <p class="text-gray-500 text-sm mt-1.5 font-medium">
                    Immutable security log and chronological activity trail across all portal modules.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-2 rounded-full font-label-md text-[13px] flex items-center gap-2 font-bold shadow-2xs">
                    <span class="material-symbols-outlined text-[18px] text-emerald-600">shield_lock</span>
                    <span>{{ number_format($logs->total()) }} Log Records</span>
                </div>
            </div>
        </div>

        {{-- Filters & Search Toolbar --}}
        <div class="bg-white shadow-sm border border-gray-100 p-5" style="border-radius: 20px;">
            <form action="{{ route('superadmin.audit-logs.index') }}" method="GET" class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-3 flex-1">
                    
                    {{-- Search Input --}}
                    <div class="relative w-full sm:w-64">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">search</span>
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                            placeholder="Search description, IP, actor..."
                            class="pl-10 pr-4 py-2.5 rounded-full border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-xs text-gray-700 w-full bg-gray-50/50">
                    </div>

                    {{-- Campus Filter --}}
                    <select name="school_id"
                        class="px-4 py-2.5 rounded-full border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-xs text-gray-700 bg-gray-50/50">
                        <option value="">All Campuses</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ ($filters['school_id'] ?? '') == $school->id ? 'selected' : '' }}>
                                {{ $school->name }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Module Filter --}}
                    @if($logModules->isNotEmpty())
                    <select name="log_name"
                        class="px-4 py-2.5 rounded-full border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-xs text-gray-700 bg-gray-50/50 capitalize">
                        <option value="">All Modules</option>
                        @foreach($logModules as $mod)
                            <option value="{{ $mod }}" {{ ($filters['log_name'] ?? '') == $mod ? 'selected' : '' }}>
                                {{ ucfirst(str_replace(['-', '_'], ' ', $mod)) }}
                            </option>
                        @endforeach
                    </select>
                    @endif

                    {{-- Date Filter --}}
                    <input type="date" name="date" value="{{ $filters['date'] ?? '' }}"
                        class="px-4 py-2 rounded-full border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-xs text-gray-700 bg-gray-50/50">

                    {{-- Filter Action Buttons --}}
                    <button type="submit"
                        class="px-5 py-2.5 rounded-full text-white text-xs font-bold transition-all shadow-md active:scale-95 cursor-pointer"
                        style="background-color: #21255E;">
                        Filter
                    </button>

                    @if(array_filter($filters))
                        <a href="{{ route('superadmin.audit-logs.index') }}"
                            class="px-4 py-2.5 rounded-full border border-gray-200 text-gray-600 text-xs font-bold hover:bg-gray-100 transition-colors">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Audit Logs Table Structure --}}
        <div class="bg-white shadow-sm border border-gray-100 overflow-hidden" style="border-radius: 20px;">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th class="px-6 py-4 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                                Date &amp; Time
                            </th>
                            <th class="px-6 py-4 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                                Actor / User
                            </th>
                            <th class="px-6 py-4 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                                Event Category
                            </th>
                            <th class="px-6 py-4 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                                Action Description
                            </th>
                            <th class="px-6 py-4 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                                Campus / School
                            </th>
                            <th class="px-6 py-4 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100 text-right">
                                IP &amp; Client
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-gray-700">
                        @forelse($logs as $index => $log)
                            @php
                                $rowBg = $index % 2 === 0 ? '#ffffff' : '#fafbfc';
                                $logType = strtolower($log->log_name ?? 'default');
                                
                                // Module badge styling
                                [$badgeBg, $badgeText, $badgeBorder] = match (true) {
                                    str_contains($logType, 'email') || str_contains($logType, 'mail') => ['bg-sky-50', 'text-sky-700', 'border-sky-200'],
                                    str_contains($logType, 'auth') || str_contains($logType, 'security') || str_contains($logType, 'login') => ['bg-emerald-50', 'text-emerald-700', 'border-emerald-200'],
                                    str_contains($logType, 'contact') => ['bg-indigo-50', 'text-indigo-700', 'border-indigo-200'],
                                    str_contains($logType, 'application') || str_contains($logType, 'candidate') => ['bg-blue-50', 'text-blue-700', 'border-blue-200'],
                                    str_contains($logType, 'school') || str_contains($logType, 'department') => ['bg-purple-50', 'text-purple-700', 'border-purple-200'],
                                    str_contains($logType, 'vacancy') || str_contains($logType, 'job') => ['bg-amber-50', 'text-amber-800', 'border-amber-200'],
                                    str_contains($logType, 'delete') || str_contains($logType, 'error') => ['bg-red-50', 'text-red-700', 'border-red-200'],
                                    default => ['bg-slate-100', 'text-slate-700', 'border-slate-200'],
                                };
                            @endphp

                            <tr style="background: {{ $rowBg }}; transition: background 0.15s;"
                                onmouseover="this.style.background='#f0f4ff'" onmouseout="this.style.background='{{ $rowBg }}'">
                                
                                {{-- Date & Time --}}
                                <td class="px-6 py-4 whitespace-nowrap border-b border-gray-50">
                                    <div class="flex items-center gap-2">
                                        <div>
                                            <p class="font-bold text-[#111827] text-xs leading-tight">
                                                {{ $log->created_at->format('d M Y, h:i A') }}
                                            </p>
                                            <p class="text-[11px] text-gray-400 mt-0.5">
                                                {{ $log->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Actor / User --}}
                                <td class="px-6 py-4 whitespace-nowrap border-b border-gray-50">
                                    @if($log->user)
                                        <div class="flex items-center gap-2.5">
                                            <div>
                                                <p class="font-bold text-[#111827] text-xs leading-tight">{{ $log->user->name }}</p>
                                                <p class="text-[11px] text-gray-400 mt-0.5">{{ $log->user->email }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-2">
                                            <div>
                                                <p class="font-bold text-gray-800 text-xs leading-tight">Public / System</p>
                                                <p class="text-[11px] text-gray-400 mt-0.5">Automated Event</p>
                                            </div>
                                        </div>
                                    @endif
                                </td>

                                {{-- Event Category / Module --}}
                                <td class="px-6 py-4 whitespace-nowrap border-b border-gray-50">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-bold border rounded-full {{ $badgeBg }} {{ $badgeText }} {{ $badgeBorder }} capitalize">
                                        {{ str_replace(['-', '_'], ' ', $log->log_name ?? 'System') }}
                                    </span>
                                </td>

                                {{-- Action Description --}}
                                <td class="px-6 py-4 border-b border-gray-50 max-w-md">
                                    <p class="font-medium text-gray-900 text-xs leading-relaxed">
                                        {{ $log->description }}
                                    </p>
                                    @if(!empty($log->properties))
                                        <p class="font-mono text-[10px] text-gray-400 mt-1 truncate">
                                            {{ json_encode($log->properties) }}
                                        </p>
                                    @endif
                                </td>

                                {{-- Campus / School --}}
                                <td class="px-6 py-4 whitespace-nowrap border-b border-gray-50">
                                    @if($log->school)
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-bold text-gray-800 text-xs">{{ $log->school->name }}</span>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-gray-400 bg-gray-50 px-2.5 py-0.5 rounded-full border border-gray-200">
                                            Global / Portal
                                        </span>
                                    @endif
                                </td>

                                {{-- IP & Client --}}
                                <td class="px-6 py-4 whitespace-nowrap border-b border-gray-50 text-right">
                                    <span class="font-mono text-[11px] bg-slate-100 text-slate-700 px-2.5 py-1 rounded-md border border-slate-200 inline-block font-semibold">
                                        {{ $log->ip_address ?? '127.0.0.1' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                                    <div class="w-14 h-14 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-3 text-2xl">
                                        <span class="material-symbols-outlined text-[32px]">shield_lock</span>
                                    </div>
                                    <p class="font-bold text-gray-800 text-base">No audit events found</p>
                                    <p class="text-xs text-gray-400 mt-1">There are no log records matching your active filters.</p>
                                    @if(array_filter($filters))
                                        <a href="{{ route('superadmin.audit.index') }}"
                                            class="inline-block mt-3 text-xs font-bold text-[#21255E] hover:underline">
                                            Clear all filters →
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            @if($logs->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4 bg-gray-50/50">
                    <p class="text-[12px] text-gray-500 font-medium">
                        Showing page <span class="font-bold text-gray-800">{{ $logs->currentPage() }}</span> of
                        <span class="font-bold text-gray-800">{{ $logs->lastPage() }}</span> ({{ $logs->total() }} total entries)
                    </p>
                    <div>
                        {{ $logs->links() }}
                    </div>
                </div>
            @endif
        </div>

    </div>
@endsection