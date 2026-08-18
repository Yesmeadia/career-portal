@extends('layouts.admin')

@section('title', 'Manage Institutional Departments')

@section('content')
    <div class="max-w-[1400px] mx-auto space-y-6" x-data="{
        showModal: false,
        isEdit: false,
        modalTitle: 'Add New Department',
        formAction: '{{ route('superadmin.departments.store') }}',
        formMethod: 'POST',
        formData: {
            id: '',
            school_id: '{{ $schools->first()?->id ?? '' }}',
            name: '',
            code: '',
            description: '',
            is_active: true
        },
        showDeleteModal: false,
        deleteAction: '',
        deleteDeptName: '',
        confirmDelete(dept) {
            this.deleteAction = '{{ url('/super-admin/departments') }}/' + dept.id;
            this.deleteDeptName = dept.name;
            this.showDeleteModal = true;
        },
        openAdd() {
            this.isEdit = false;
            this.modalTitle = 'Add New Department';
            this.formAction = '{{ route('superadmin.departments.store') }}';
            this.formMethod = 'POST';
            this.formData = {
                id: '',
                school_id: '{{ $schools->first()?->id ?? '' }}',
                name: '',
                code: '',
                description: '',
                is_active: true
            };
            this.showModal = true;
        },
        openEdit(dept) {
            this.isEdit = true;
            this.modalTitle = 'Edit Department: ' + dept.name;
            this.formAction = '{{ url('/super-admin/departments') }}/' + dept.id;
            this.formMethod = 'PUT';
            this.formData = {
                id: dept.id,
                school_id: dept.school_id,
                name: dept.name,
                code: dept.code || '',
                description: dept.description || '',
                is_active: dept.is_active ? true : false
            };
            this.showModal = true;
        }
    }">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="font-extrabold text-[#111827] tracking-tight leading-none" style="font-size: 38px;">School Departments</h1>
                <p class="text-gray-500 text-sm mt-1.5 font-medium">Manage academic and administrative departments across all school campuses.</p>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" @click="openAdd()"
                    class="text-white font-bold text-xs px-6 py-3 rounded-full transition-all shadow-md flex items-center gap-2 active:scale-95 cursor-pointer"
                    style="background-color: #D7B56D;">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    <span>Add Department</span>
                </button>
            </div>
        </div>

        {{-- Filters Section --}}
        <div class="bg-white shadow-sm border border-gray-100 p-5" style="border-radius: 20px;">
            <form action="{{ route('superadmin.departments.index') }}" method="GET" class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-3 flex-1">
                    {{-- Search Input --}}
                    <div class="relative w-full sm:w-64">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">search</span>
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                            placeholder="Search departments..."
                            class="pl-10 pr-4 py-2.5 rounded-full border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-xs text-gray-700 w-full bg-gray-50/50">
                    </div>

                    {{-- School Filter --}}
                    <select name="school_id"
                        class="px-4 py-2.5 rounded-full border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-xs text-gray-700 bg-gray-50/50">
                        <option value="">All Campuses</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ ($filters['school_id'] ?? '') == $school->id ? 'selected' : '' }}>
                                {{ $school->name }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Status Filter --}}
                    <select name="status"
                        class="px-4 py-2.5 rounded-full border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-xs text-gray-700 bg-gray-50/50">
                        <option value="">All Statuses</option>
                        <option value="active" {{ ($filters['status'] ?? '') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>

                    <button type="submit"
                        class="bg-[#21255E] hover:bg-[#1a1d4b] text-white px-5 py-2.5 rounded-full text-xs font-semibold transition-all shadow-xs flex items-center gap-1.5 cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">filter_alt</span> Filter
                    </button>

                    @if(array_filter($filters))
                        <a href="{{ route('superadmin.departments.index') }}"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2.5 rounded-full text-xs font-semibold transition-colors flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">close</span> Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Departments Table Card --}}
        <div class="bg-white shadow-sm border border-gray-100 overflow-hidden" style="border-radius: 20px;">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div>
                        <h3 class="text-base font-bold text-[#111827]">Departments Directory</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Total {{ $departments->total() }} department(s) configured.</p>
                    </div>
                </div>
                <div class="text-[12px] text-gray-500 font-medium">
                    Showing <span class="font-bold text-[#111827]">{{ $departments->count() }}</span> of
                    <span class="font-bold text-[#111827]">{{ $departments->total() }}</span> entries
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50/70 border-b border-gray-100 text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Department Info</th>
                            <th class="px-6 py-4">Campus / School</th>
                            <th class="px-6 py-4 text-center">Vacancies</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @forelse($departments as $dept)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div>
                                            <p class="font-bold text-[#111827] text-[14px] leading-tight">{{ $dept->name }}</p>
                                            @if($dept->code)
                                                <span class="inline-block text-[10px] font-mono font-bold bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded mt-0.5">Code: {{ $dept->code }}</span>
                                            @endif
                                            @if($dept->description)
                                                <p class="text-[11px] text-gray-500 mt-1 max-w-sm line-clamp-1">{{ $dept->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[16px] text-gray-400">school</span>
                                        <span class="font-semibold text-gray-800">{{ $dept->school->name ?? 'N/A' }}</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-purple-50 text-purple-700 font-bold text-xs border border-purple-200/60">
                                        {{ $dept->vacancies_count ?? 0 }} Vacancies
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($dept->is_active)
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
                                        {{-- Edit Button --}}
                                        <button type="button" @click="openEdit({{ json_encode($dept) }})"
                                            class="w-8 h-8 rounded-full bg-gray-50 border border-gray-200 hover:bg-blue-50 hover:text-blue-700 hover:border-blue-200 flex items-center justify-center transition-colors text-gray-600 shadow-2xs cursor-pointer"
                                            title="Edit Department">
                                            <span class="material-symbols-outlined text-[17px]">edit</span>
                                        </button>

                                        {{-- Toggle Status --}}
                                        <form action="{{ route('superadmin.departments.toggle-status', $dept) }}" method="POST" class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="w-8 h-8 rounded-full bg-gray-50 border border-gray-200 hover:bg-amber-50 hover:text-amber-700 hover:border-amber-200 flex items-center justify-center transition-colors text-gray-600 shadow-2xs cursor-pointer"
                                                title="{{ $dept->is_active ? 'Deactivate' : 'Activate' }}">
                                                <span class="material-symbols-outlined text-[17px]">
                                                    {{ $dept->is_active ? 'toggle_off' : 'toggle_on' }}
                                                </span>
                                            </button>
                                        </form>

                                        {{-- Delete Button (Trigger Custom Modal) --}}
                                        <button type="button" @click="confirmDelete({{ json_encode($dept) }})"
                                            class="w-8 h-8 rounded-full bg-gray-50 border border-gray-200 hover:bg-red-50 hover:text-red-700 hover:border-red-200 flex items-center justify-center transition-colors text-gray-600 shadow-2xs cursor-pointer"
                                            title="Delete Department">
                                            <span class="material-symbols-outlined text-[17px]">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                    <p class="font-bold text-gray-800 text-base">No departments found</p>
                                    <p class="text-xs text-gray-400 mt-1 mb-4">Start by adding your first institutional department.</p>
                                    <button type="button" @click="openAdd()"
                                        class="inline-flex items-center gap-2 text-white text-xs px-5 py-2.5 rounded-full font-bold transition-all shadow-md cursor-pointer"
                                        style="background-color: #D7B56D;">
                                        <span class="material-symbols-outlined text-[18px]">add</span> Add Department
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($departments->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $departments->links() }}
                </div>
            @endif
        </div>

        {{-- Add / Edit Department Modal --}}
        <div x-show="showModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            x-cloak>

            <div class="bg-white rounded-3xl shadow-xl w-full max-w-lg overflow-hidden border border-gray-100 transform transition-all"
                @click.away="showModal = false">
                
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <h3 class="text-lg font-extrabold text-[#111827]" x-text="modalTitle"></h3>
                    </div>
                    <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-600 text-lg cursor-pointer">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <form :action="formAction" method="POST" class="p-6 space-y-4">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    {{-- Target Campus --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">School Campus *</label>
                        <select name="school_id" x-model="formData.school_id" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50">
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Department Name --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Department Name *</label>
                        <input type="text" name="name" x-model="formData.name" required
                            placeholder="e.g. Mathematics & Computer Science"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50">
                    </div>

                    {{-- Department Code --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Department Code (Optional)</label>
                        <input type="text" name="code" x-model="formData.code"
                            placeholder="e.g. MCS or TCH"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50">
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Description</label>
                        <textarea name="description" x-model="formData.description" rows="3"
                            placeholder="Brief description of the department's mandate..."
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50 resize-none"></textarea>
                    </div>

                    {{-- Active Status Toggle --}}
                    <div class="flex items-center gap-3 pt-2">
                        <input type="checkbox" id="dept_active" name="is_active" value="1" x-model="formData.is_active"
                            class="w-4 h-4 rounded text-[#21255E] focus:ring-[#21255E]">
                        <label for="dept_active" class="text-xs font-bold text-gray-700 cursor-pointer">Active status (available for posting vacancies)</label>
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                        <button type="button" @click="showModal = false"
                            class="px-5 py-2.5 rounded-full border border-gray-200 text-gray-600 text-xs font-bold hover:bg-gray-50 transition-colors cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2.5 rounded-full text-white text-xs font-bold transition-all shadow-md hover:opacity-90 cursor-pointer"
                            style="background-color: #D7B56D;"
                            x-text="isEdit ? 'Update Department' : 'Save Department'">
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Custom Browser Alert / Confirmation Dialog Design for Delete --}}
        <div x-show="showDeleteModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            x-cloak>

            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden border border-gray-100 transform transition-all p-6 text-center"
                @click.away="showDeleteModal = false">
                
                {{-- Red Warning Icon Badge --}}
                <div class="w-16 h-16 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-4 border border-red-100 shadow-xs">
                    <span class="material-symbols-outlined text-[34px]">delete_forever</span>
                </div>

                <h3 class="text-xl font-extrabold text-[#111827] mb-2">Delete Department?</h3>
                
                <p class="text-xs text-gray-500 leading-relaxed mb-4">
                    Are you sure you want to delete <strong class="text-gray-900 font-bold" x-text="deleteDeptName"></strong>?
                </p>

                {{-- Important Notice Guaranteeing Job Categories & Classes are Untouched --}}
                <div class="bg-amber-50/80 border border-amber-200/80 rounded-2xl p-4 text-left mb-6 flex items-start gap-3">
                    <span class="material-symbols-outlined text-amber-600 text-[20px] shrink-0 mt-0.5">verified_user</span>
                    <div>
                        <p class="text-xs text-amber-900 font-bold leading-tight">Data Safety Guarantee</p>
                        <p class="text-[11px] text-amber-800 font-medium mt-1 leading-normal">
                            All <strong>Job Categories</strong> and <strong>Global Classes</strong> will <strong>NOT</strong> be deleted and remain completely intact across the portal.
                        </p>
                    </div>
                </div>

                {{-- Modal Action Buttons --}}
                <form :action="deleteAction" method="POST" class="flex items-center justify-center gap-3">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="showDeleteModal = false"
                        class="flex-1 py-3 rounded-full border border-gray-200 text-gray-700 text-xs font-bold hover:bg-gray-50 transition-all cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 py-3 rounded-full bg-red-600 hover:bg-red-700 text-white text-xs font-bold transition-all shadow-md active:scale-95 cursor-pointer">
                        Yes, Delete
                    </button>
                </form>
            </div>
        </div>

    </div>
@endsection
