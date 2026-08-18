@extends('layouts.admin')

@section('title', 'Job Categories')

@section('content')
    <div class="max-w-[1400px] mx-auto space-y-6">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="font-extrabold text-[#111827] tracking-tight leading-none" style="font-size: 38px;">Job
                    Categories</h1>
            </div>
            <div class="flex items-center gap-2 bg-[#eef2f6] px-4 py-2 border border-gray-200/80"
                style="border-radius: 999px;">
                <span style="width:9px;height:9px;background:#21255E;border-radius:50%;display:inline-block;"></span>
                <span class="text-[#334155] text-[13px] font-medium">
                    <span class="font-bold text-[#111827]">{{ $categories->total() }}</span> Categories Configured
                </span>
            </div>
        </div>

        {{-- Add New Category Form Card --}}
        <div class="bg-white shadow-sm border border-gray-100 p-6" style="border-radius: 20px;">
            <div class="flex items-center gap-3 pb-4 mb-5 border-b border-gray-100">
                <div class="flex items-center justify-center text-[#21255E]"
                    style="width:38px;height:38px;border-radius:12px;background:linear-gradient(135deg,#e5eeff,#dbeafe);">
                    <span class="material-symbols-outlined" style="font-size:20px;">add_circle</span>
                </div>
                <div>
                    <h2 class="font-bold text-[#111827]" style="font-size:17px;">Add New Job Category</h2>
                    <p class="text-[11px] text-gray-400 mt-0.5">Create a classification for organizing career vacancies
                        across schools.</p>
                </div>
            </div>

            @if($errors->any())
                <div
                    class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 text-xs font-semibold rounded-xl flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">error</span>
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('superadmin.job-categories.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">

                    {{-- Category Name --}}
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Category Name
                            *</label>
                        <input type="text" name="name" required value="{{ old('name') }}"
                            placeholder="e.g. Primary Teaching"
                            class="w-full bg-gray-50 border border-gray-200 text-[13px] text-gray-800 font-semibold px-4 py-2.5 focus:outline-none focus:ring-2 focus:bg-white transition-all placeholder-gray-400"
                            style="border-radius: 12px;">
                        @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    {{-- Icon Name --}}
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Icon
                            Name</label>
                        <input type="text" name="icon" value="{{ old('icon') }}"
                            placeholder="e.g. school, briefcase, sports"
                            class="w-full bg-gray-50 border border-gray-200 text-[13px] text-gray-800 font-semibold px-4 py-2.5 focus:outline-none focus:ring-2 focus:bg-white transition-all placeholder-gray-400"
                            style="border-radius: 12px;">
                    </div>

                    {{-- Description --}}
                    <div>
                        <label
                            class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Description</label>
                        <input type="text" name="description" value="{{ old('description') }}"
                            placeholder="Brief description..."
                            class="w-full bg-gray-50 border border-gray-200 text-[13px] text-gray-800 font-semibold px-4 py-2.5 focus:outline-none focus:ring-2 focus:bg-white transition-all placeholder-gray-400"
                            style="border-radius: 12px;">
                    </div>

                    {{-- Submit --}}
                    <div>
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 text-white text-[13px] font-bold py-2.5 px-6 transition-all hover:opacity-90 active:scale-95 shadow-sm"
                            style="background-color: #D7B56D; border-radius: 12px;">
                            <span class="material-symbols-outlined" style="font-size:18px;">add</span>
                            Add Category
                        </button>
                    </div>

                </div>
            </form>
        </div>

        {{-- Categories Table Card --}}
        <div class="bg-white shadow-sm border border-gray-100" style="border-radius: 20px; overflow: hidden;">

            {{-- Table Header --}}
            <div class="px-6 pt-5 pb-4 border-b border-gray-100"
                style="background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div>
                            <h2 class="font-bold text-[#111827]" style="font-size:17px;">All Job Categories</h2>
                            <p class="text-[11px] text-gray-400 mt-0.5">{{ $categories->total() }} categories currently
                                configured</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-[12px] text-gray-500 font-medium">
                        <span class="material-symbols-outlined text-[16px] text-gray-400">table_rows</span>
                        Showing <span class="font-bold text-[#111827] mx-1">{{ $categories->count() }}</span> of
                        <span class="font-bold text-[#111827] mx-1">{{ $categories->total() }}</span>
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
                                Category</th>
                            <th
                                class="px-5 py-3 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                                Description</th>
                            <th
                                class="px-5 py-3 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100 text-center">
                                Jobs</th>
                            <th
                                class="px-5 py-3 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                                Status</th>
                            <th
                                class="px-5 py-3 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100 text-right">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $index => $cat)
                            @php $rowBg = $index % 2 === 0 ? '#ffffff' : '#fafbfc'; @endphp
                            <tr style="background: {{ $rowBg }}; transition: background 0.15s;"
                                onmouseover="this.style.background='#f0f4ff'" onmouseout="this.style.background='{{ $rowBg }}'">

                                {{-- Category Name --}}
                                <td class="px-5 py-3.5 border-b border-gray-50">
                                    <div class="flex items-center gap-3">
                                        <span class="font-bold text-[#111827]" style="font-size:13px;">{{ $cat->name }}</span>
                                    </div>
                                </td>

                                {{-- Description --}}
                                <td class="px-5 py-3.5 border-b border-gray-50">
                                    <span class="text-[13px] text-gray-500">{{ $cat->description ?: '—' }}</span>
                                </td>

                                {{-- Jobs Count --}}
                                <td class="px-5 py-3.5 border-b border-gray-50 text-center">
                                    <span class="inline-flex items-center justify-center font-bold text-[#21255E] text-[12px]"
                                        style="width:28px;height:28px;border-radius:8px;background:rgba(219,234,254,0.7);">
                                        {{ $cat->vacancies_count ?? 0 }}
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td class="px-5 py-3.5 border-b border-gray-50">
                                    @if($cat->is_active)
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
                                    <div class="flex items-center justify-end gap-2">

                                        {{-- Toggle Active --}}
                                        <form action="{{ route('superadmin.job-categories.update', $cat) }}" method="POST"
                                            class="inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="name" value="{{ $cat->name }}">
                                            <input type="hidden" name="is_active" value="{{ $cat->is_active ? 0 : 1 }}">
                                            <button type="submit"
                                                class="inline-flex items-center justify-center transition-all border text-gray-500 hover:text-white"
                                                style="width:32px;height:32px;border-radius:10px;background:#f8fafc;border-color:#e5e7eb;"
                                                onmouseover="this.style.background='{{ $cat->is_active ? '#21255e' : '#22c55e' }}';this.style.color='white';this.style.borderColor='transparent';"
                                                onmouseout="this.style.background='#f8fafc';this.style.color='#6b7280';this.style.borderColor='#e5e7eb';"
                                                title="{{ $cat->is_active ? 'Disable Category' : 'Enable Category' }}">
                                                <span class="material-symbols-outlined"
                                                    style="font-size:16px;">{{ $cat->is_active ? 'toggle_on' : 'toggle_off' }}</span>
                                            </button>
                                        </form>

                                        {{-- Delete --}}
                                        <form action="{{ route('superadmin.job-categories.destroy', $cat) }}" method="POST"
                                            class="inline" data-confirm="Are you sure you want to delete category '{{ $cat->name }}'? This action cannot be undone."
                                            data-confirm-title="Delete Category" data-confirm-btn="Yes, Delete">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center transition-all border text-gray-500 hover:text-white hover:bg-red-500 hover:border-transparent cursor-pointer"
                                                style="width:32px;height:32px;border-radius:10px;background:#f8fafc;border-color:#e5e7eb;"
                                                title="Delete Category">
                                                <span class="material-symbols-outlined" style="font-size:16px;">delete</span>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="flex items-center justify-center text-gray-300"
                                            style="width:56px;height:56px;border-radius:18px;background:#f1f5f9;">
                                            <span class="material-symbols-outlined" style="font-size:28px;">category</span>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-700 text-sm">No job categories yet</p>
                                            <p class="text-xs text-gray-400 mt-1">Add your first category using the form above.
                                            </p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            @if($categories->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between gap-4"
                    style="background:#fafbfc;">
                    <p class="text-[12px] text-gray-400">
                        Page <span class="font-bold text-gray-700">{{ $categories->currentPage() }}</span> of
                        <span class="font-bold text-gray-700">{{ $categories->lastPage() }}</span>
                    </p>
                    {{ $categories->links() }}
                </div>
            @endif

        </div>

    </div>
@endsection