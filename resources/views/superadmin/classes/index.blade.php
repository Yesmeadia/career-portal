@extends('layouts.admin')

@section('title', 'Manage Global Classes')

@section('content')
    <div class="max-w-[1400px] mx-auto space-y-6">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="font-extrabold text-[#111827] tracking-tight leading-none" style="font-size: 38px;">Global Grade
                    &amp; Class Levels</h1>
            </div>
            <div class="flex items-center gap-2 bg-[#eef2f6] px-4 py-2 border border-gray-200/80"
                style="border-radius: 999px;">
                <span style="width:9px;height:9px;background:#21255E;border-radius:50%;display:inline-block;"></span>
                <span class="text-[#334155] text-[13px] font-medium">
                    <span class="font-bold text-[#111827]">{{ $classes->total() }}</span> Standard Levels Configured
                </span>
            </div>
        </div>

        {{-- Add New Class Form Card --}}
        <div class="bg-white shadow-sm border border-gray-100 p-6" style="border-radius: 20px;">
            <div class="flex items-center gap-3 pb-4 mb-5 border-b border-gray-100">
                <div>
                    <h2 class="font-bold text-[#111827]" style="font-size:17px;">Add Global Class Level</h2>
                    <p class="text-[11px] text-gray-400 mt-0.5">Define a new standard class or grade level for all partner
                        schools.</p>
                </div>
            </div>

            @if($errors->any())
                <div
                    class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 text-xs font-semibold rounded-xl flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">error</span>
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('superadmin.global-classes.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">

                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Class Name
                            *</label>
                        <input type="text" name="name" required value="{{ old('name') }}"
                            placeholder="e.g. Grade 1 or Nursery"
                            class="w-full bg-gray-50 border border-gray-200 text-[13px] text-gray-800 font-semibold px-4 py-2.5 focus:outline-none focus:ring-2 focus:bg-white transition-all placeholder-gray-400"
                            style="border-radius: 12px;">
                        @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Sort
                            Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" placeholder="0"
                            class="w-full bg-gray-50 border border-gray-200 text-[13px] text-gray-800 font-semibold px-4 py-2.5 focus:outline-none focus:ring-2 focus:bg-white transition-all placeholder-gray-400"
                            style="border-radius: 12px;">
                    </div>

                    <div>
                        <label
                            class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Description</label>
                        <input type="text" name="description" value="{{ old('description') }}"
                            placeholder="Brief description..."
                            class="w-full bg-gray-50 border border-gray-200 text-[13px] text-gray-800 font-semibold px-4 py-2.5 focus:outline-none focus:ring-2 focus:bg-white transition-all placeholder-gray-400"
                            style="border-radius: 12px;">
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 text-white text-[13px] font-bold py-2.5 px-6 transition-all hover:opacity-90 active:scale-95 shadow-sm"
                            style="background-color: #D7B56D; border-radius: 12px;">
                            <span class="material-symbols-outlined" style="font-size:18px;">add</span>
                            Add Class Level
                        </button>
                    </div>

                </div>
            </form>
        </div>

        {{-- Classes Table Card --}}
        <div class="bg-white shadow-sm border border-gray-100" style="border-radius: 20px; overflow: hidden;">

            {{-- Table Header --}}
            <div class="px-6 pt-5 pb-4 border-b border-gray-100"
                style="background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div>
                            <h2 class="font-bold text-[#111827]" style="font-size:17px;">Existing Global Classes</h2>
                            <p class="text-[11px] text-gray-400 mt-0.5">{{ $classes->total() }} grade level(s) configured in
                                system</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-[12px] text-gray-500 font-medium">
                        Showing <span class="font-bold text-[#111827] mx-1">{{ $classes->count() }}</span> of
                        <span class="font-bold text-[#111827] mx-1">{{ $classes->total() }}</span>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left" style="border-collapse: separate; border-spacing: 0;">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th
                                class="px-5 py-3 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                                Sort</th>
                            <th
                                class="px-5 py-3 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                                Class Name</th>
                            <th
                                class="px-5 py-3 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                                Status</th>
                            <th
                                class="px-5 py-3 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100 text-right">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classes as $index => $item)
                            @php $rowBg = $index % 2 === 0 ? '#ffffff' : '#fafbfc'; @endphp
                            <tr style="background: {{ $rowBg }}; transition: background 0.15s;"
                                onmouseover="this.style.background='#f0f4ff'" onmouseout="this.style.background='{{ $rowBg }}'">

                                {{-- Sort Order --}}
                                <td class="px-5 py-3.5 border-b border-gray-50">
                                    <span class="font-mono text-[12px] font-bold text-[#21255E]">
                                        {{ $item->sort_order }}
                                    </span>
                                </td>

                                {{-- Class Name --}}
                                <td class="px-5 py-3.5 border-b border-gray-50">
                                    <div class="flex items-center gap-3">
                                        <span class="font-bold text-[#111827]" style="font-size:13px;">{{ $item->name }}</span>
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td class="px-5 py-3.5 border-b border-gray-50">
                                    @if($item->is_active)
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200"
                                            style="border-radius: 999px;">
                                            <span
                                                style="width:7px;height:7px;border-radius:50%;background-color:#22c55e;display:inline-block;"></span>
                                            Active
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 text-[11px] font-bold bg-gray-100 text-gray-500 border border-gray-200"
                                            style="border-radius: 999px;">
                                            <span
                                                style="width:7px;height:7px;border-radius:50%;background-color:#94a3b8;display:inline-block;"></span>
                                            Disabled
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-5 py-3.5 border-b border-gray-50 text-right">
                                    <form action="{{ route('superadmin.global-classes.destroy', $item) }}" method="POST"
                                        class="inline"
                                        data-confirm="Are you sure you want to delete class level '{{ $item->name }}'? This action cannot be undone."
                                        data-confirm-title="Delete Global Class" data-confirm-btn="Yes, Delete">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center justify-center transition-all border text-gray-500 hover:text-white hover:bg-red-500 hover:border-transparent cursor-pointer"
                                            style="width:32px;height:32px;border-radius:10px;background:#f8fafc;border-color:#e5e7eb;"
                                            title="Delete Class">
                                            <span class="material-symbols-outlined" style="font-size:16px;">delete</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="flex items-center justify-center text-gray-300"
                                            style="width:56px;height:56px;border-radius:18px;background:#f1f5f9;">
                                            <span class="material-symbols-outlined" style="font-size:28px;">layers</span>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-700 text-sm">No global classes found</p>
                                            <p class="text-xs text-gray-400 mt-1">Add your first class level using the form
                                                above.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($classes->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between gap-4"
                    style="background:#fafbfc;">
                    <p class="text-[12px] text-gray-400">
                        Page <span class="font-bold text-gray-700">{{ $classes->currentPage() }}</span> of
                        <span class="font-bold text-gray-700">{{ $classes->lastPage() }}</span>
                    </p>
                    {{ $classes->links() }}
                </div>
            @endif

        </div>

    </div>
@endsection