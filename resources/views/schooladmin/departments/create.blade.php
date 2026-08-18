@extends('layouts.admin')

@section('title', 'Add Department')

@section('content')
<div class="max-w-[1000px] mx-auto">

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
                <h2 class="text-4xl font-extrabold text-[#111827] tracking-tight leading-none" style="font-size: 38px;">Add New Department</h2>

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
            <a href="{{ route('schooladmin.departments.index') }}"
               class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold text-xs px-6 py-3 rounded-full transition-all shadow-2xs flex items-center gap-2 cursor-pointer">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                <span>Back to Departments</span>
            </a>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="bg-white shadow-sm border border-gray-100 p-8 space-y-6" style="border-radius: 20px;">
        <div class="border-b border-gray-100 pb-4">
            <h3 class="text-lg font-bold text-[#111827] flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-600 text-[22px]">domain_add</span>
                Department Specification &amp; Configuration
            </h3>
            <p class="text-xs text-gray-500 mt-0.5">Configure institutional department branches (e.g., Teaching, Administration, Accounts, Library, IT, Security).</p>
        </div>

        <form action="{{ route('schooladmin.departments.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Department Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" required placeholder="e.g. Teaching or Administration"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-900 bg-gray-50/50">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Department Code</label>
                    <input type="text" name="code" placeholder="e.g. TCH or ADM"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-mono uppercase font-bold text-gray-900 bg-gray-50/50">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Description</label>
                <textarea name="description" rows="4" placeholder="Describe the responsibilities and scope of this department..."
                          class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm text-gray-900 bg-gray-50/50 resize-none"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-6">
                <a href="{{ route('schooladmin.departments.index') }}"
                   class="px-6 py-3 rounded-full text-xs font-bold text-gray-600 hover:bg-gray-100 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="text-white font-bold text-xs px-8 py-3 rounded-full transition-all shadow-md flex items-center gap-2 active:scale-95 cursor-pointer"
                        style="background-color: #D7B56D;">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    <span>Save Department</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
