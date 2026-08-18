@extends('layouts.admin')

@section('title', 'School Departments')

@section('content')
<div class="max-w-[1400px] mx-auto">

    {{-- Overview Page Header & Live System Clock --}}
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
                <h2 class="text-4xl font-extrabold text-[#111827] tracking-tight leading-none" style="font-size: 38px;">School Departments</h2>

                {{-- Live System Clock Pill --}}
                <div class="bg-[#eef2f6] px-3.5 py-1.5 rounded-full flex items-center gap-2 border border-gray-200/80">
                    <span class="w-2.5 h-2.5 rounded-full shrink-0" style="width: 10px; height: 10px; background-color: #22c55e; border-radius: 50%; display: inline-block;"></span>
                    <span class="text-[#334155] text-[13px] font-medium font-sans">
                        Live System Clock: <span x-text="timeStr">{{ now()->timezone('Asia/Kolkata')->format('h:i:s A') }}</span>
                    </span>
                </div>
            </div>

            <p class="text-gray-500 mt-2.5 flex items-center gap-2 text-sm font-medium">
                <span class="material-symbols-outlined text-[18px] text-gray-400">schedule</span>
                <span x-text="dateStr">{{ now()->timezone('Asia/Kolkata')->format('l, M d, Y | h:i A') }}</span>
            </p>
        </div>

        {{-- Top Right Actions --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('schooladmin.departments.create') }}"
               class="text-white font-bold text-xs px-6 py-3 rounded-full transition-all shadow-md flex items-center gap-2 active:scale-95 cursor-pointer"
               style="background-color: #D7B56D;">
                <span class="material-symbols-outlined text-[18px]">add_circle</span>
                <span>Add Department</span>
            </a>
        </div>
    </div>

    {{-- Department List Card (Full Width) --}}
    <div class="bg-white shadow-sm border border-gray-100 overflow-hidden mb-6" style="border-radius: 20px;">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold shrink-0">
                    <span class="material-symbols-outlined text-[22px]">domain</span>
                </div>
                <div>
                    <h3 class="text-base font-bold text-[#111827]">School Departments Directory</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Total {{ $departments->total() }} department(s) configured for vacancies and staff allocation.</p>
                </div>
            </div>
            <div class="flex items-center gap-2 text-[12px] text-gray-500 font-medium">
                Showing <span class="font-bold text-[#111827] mx-1">{{ $departments->count() }}</span> of
                <span class="font-bold text-[#111827] mx-1">{{ $departments->total() }}</span> entries
            </div>
        </div>

        <table class="w-full text-left" style="border-collapse: separate; border-spacing: 0;">
            <thead>
                <tr style="background: #f8fafc;">
                    <th class="px-6 py-4 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Department Name</th>
                    <th class="px-6 py-4 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Department Code</th>
                    <th class="px-6 py-4 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Associated Vacancies</th>
                    <th class="px-6 py-4 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Status</th>
                    <th class="px-6 py-4 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($departments as $index => $dept)
                    @php $rowBg = $index % 2 === 0 ? '#ffffff' : '#fafbfc'; @endphp
                    <tr style="background: {{ $rowBg }}; transition: background 0.15s;"
                        onmouseover="this.style.background='#f0f4ff'" onmouseout="this.style.background='{{ $rowBg }}'">
                        
                        <td class="px-6 py-4 border-b border-gray-50 font-bold text-gray-900 text-sm">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold text-xs shrink-0 border border-blue-100">
                                    <span class="material-symbols-outlined text-[18px]">domain</span>
                                </div>
                                <div>
                                    <p class="font-bold text-[#111827] text-sm leading-tight">{{ $dept->name }}</p>
                                    @if($dept->description)
                                        <p class="text-[11px] text-gray-400 font-normal mt-0.5 max-w-sm truncate">{{ $dept->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 border-b border-gray-50 font-mono text-xs font-bold text-gray-500">
                            {{ $dept->code ?? 'N/A' }}
                        </td>

                        <td class="px-6 py-4 border-b border-gray-50">
                            <span class="font-bold text-blue-600 text-sm bg-blue-50 border border-blue-100 px-3 py-1 rounded-full">
                                {{ $dept->vacancies_count ?? 0 }} Active Vacancies
                            </span>
                        </td>

                        <td class="px-6 py-4 border-b border-gray-50">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[11px] font-bold border rounded-full {{ $dept->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-gray-50 text-gray-600 border-gray-200' }}">
                                <span style="width:7px;height:7px;border-radius:50%;background-color:{{ $dept->is_active ? '#22c55e' : '#94a3b8' }};display:inline-block;flex-shrink:0;"></span>
                                {{ $dept->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>

                        <td class="px-6 py-4 border-b border-gray-50 text-right">
                            <form action="{{ route('schooladmin.departments.destroy', $dept) }}" method="POST" class="inline"
                                  data-confirm="Are you sure you want to delete department '{{ $dept->name }}'? This action cannot be undone."
                                  data-confirm-title="Delete Department" data-confirm-btn="Yes, Delete">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-9 h-9 rounded-xl bg-gray-50 hover:bg-red-50 text-gray-400 hover:text-red-600 border border-gray-200 inline-flex items-center justify-center transition-all cursor-pointer shadow-2xs"
                                        title="Delete Department">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="flex items-center justify-center text-gray-300"
                                     style="width:56px;height:56px;border-radius:18px;background:#f1f5f9;">
                                    <span class="material-symbols-outlined" style="font-size:28px;">domain_disabled</span>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-700 text-sm">No departments configured</p>
                                    <p class="text-xs text-gray-400 mt-1">Configure institutional departments to organize job vacancies.</p>
                                </div>
                                <a href="{{ route('schooladmin.departments.create') }}"
                                   class="text-[12px] font-bold text-[#21255E] hover:underline mt-1">Add First Department</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($departments->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $departments->links() }}
            </div>
        @endif
    </div>

</div>
@endsection