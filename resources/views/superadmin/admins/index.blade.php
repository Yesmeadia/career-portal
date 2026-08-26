@extends('layouts.admin')

@section('title', 'Manage Super Admins')

@section('content')
    <div class="max-w-[1400px] mx-auto space-y-6" x-data="{
        showModal: {{ $errors->any() ? 'true' : 'false' }},
        isEdit: false,
        modalTitle: 'Add New Super Admin',
        formAction: '{{ route('superadmin.admins.store') }}',
        formData: {
            id: '',
            name: '{{ old('name', '') }}',
            email: '{{ old('email', '') }}',
            phone: '{{ old('phone', '') }}',
            status: '{{ old('status', 'active') }}'
        },
        showDeleteModal: false,
        deleteAction: '',
        deleteAdminName: '',
        deleteAdminEmail: '',
        confirmDelete(admin) {
            this.deleteAction = '{{ url('/super-admin/admins') }}/' + admin.id;
            this.deleteAdminName = admin.name;
            this.deleteAdminEmail = admin.email;
            this.showDeleteModal = true;
        },
        openAdd() {
            this.isEdit = false;
            this.modalTitle = 'Add New Super Admin';
            this.formAction = '{{ route('superadmin.admins.store') }}';
            this.formData = {
                id: '',
                name: '',
                email: '',
                phone: '',
                status: 'active'
            };
            this.showModal = true;
        },
        openEdit(admin) {
            this.isEdit = true;
            this.modalTitle = 'Edit Super Admin: ' + admin.name;
            this.formAction = '{{ url('/super-admin/admins') }}/' + admin.id;
            this.formData = {
                id: admin.id,
                name: admin.name,
                email: admin.email,
                phone: admin.phone || '',
                status: admin.status
            };
            this.showModal = true;
        }
    }">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="font-extrabold text-[#111827] tracking-tight leading-none" style="font-size: 38px;">Super Admins</h1>
                    <span class="bg-[#21255E]/10 text-[#21255E] text-xs px-3 py-1 rounded-full font-bold">System Privileges</span>
                </div>
                <p class="text-gray-500 text-sm mt-1.5 font-medium">Manage top-level administrators with full access to school settings, users, and global metrics.</p>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" @click="openAdd()"
                    class="text-white font-bold text-xs px-6 py-3 rounded-full transition-all shadow-md flex items-center gap-2 active:scale-95 cursor-pointer"
                    style="background-color: #D7B56D;">
                    <span>Add Super Admin</span>
                </button>
            </div>
        </div>

        {{-- Stat Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-2xs">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Administrators</p>
                <h3 class="text-2xl font-extrabold text-[#111827] mt-1">{{ $stats['total_admins'] }}</h3>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-2xs">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Active Administrators</p>
                <h3 class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $stats['active_admins'] }}</h3>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-2xs">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Inactive Accounts</p>
                <h3 class="text-2xl font-extrabold text-red-600 mt-1">{{ $stats['inactive_admins'] }}</h3>
            </div>
        </div>

        {{-- Filters Section --}}
        <div class="bg-white shadow-sm border border-gray-100 p-5" style="border-radius: 20px;">
            <form action="{{ route('superadmin.admins.index') }}" method="GET" class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-3 flex-1">
                    {{-- Search Input --}}
                    <div class="relative w-full sm:w-72">
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                            placeholder="Search by name, email, or phone..."
                            class="px-4 py-2.5 rounded-full border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-xs text-gray-700 w-full bg-gray-50/50">
                    </div>

                    {{-- Status Filter --}}
                    <select name="status"
                        class="px-4 py-2.5 rounded-full border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-xs text-gray-700 bg-gray-50/50">
                        <option value="">All Statuses</option>
                        <option value="active" {{ ($filters['status'] ?? '') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>

                    <button type="submit"
                        class="bg-[#21255E] hover:bg-[#1a1d4b] text-white px-5 py-2.5 rounded-full text-xs font-semibold transition-all shadow-xs cursor-pointer">
                        Filter
                    </button>

                    @if(array_filter($filters))
                        <a href="{{ route('superadmin.admins.index') }}"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2.5 rounded-full text-xs font-semibold transition-colors">
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Validation Errors Notification --}}
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 text-xs px-5 py-4 rounded-2xl space-y-1">
            <div class="font-bold mb-1 text-sm">
                Please fix the following validation errors:
            </div>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Admins Table Card --}}
        <div class="bg-white shadow-sm border border-gray-100 overflow-hidden" style="border-radius: 20px;">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-[#111827]">Super Administrators Directory</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Showing total {{ $admins->total() }} super administrator(s).</p>
                </div>
                <div class="text-[12px] text-gray-500 font-medium">
                    Showing <span class="font-bold text-[#111827]">{{ $admins->count() }}</span> of
                    <span class="font-bold text-[#111827]">{{ $admins->total() }}</span> entries
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50/70 border-b border-gray-100 text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Administrator Profile</th>
                            <th class="px-6 py-4">Contact Phone</th>
                            <th class="px-6 py-4">Role & Access</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4">Added On</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @forelse($admins as $admin)
                            <tr class="hover:bg-slate-50/80 transition-colors {{ auth()->id() === $admin->id ? 'bg-amber-50/20' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $admin->avatar_url }}" alt="{{ $admin->name }}"
                                             class="w-10 h-10 rounded-full object-cover border border-gray-200 shrink-0">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <p class="font-bold text-[#111827] text-[14px] leading-tight">{{ $admin->name }}</p>
                                                @if(auth()->id() === $admin->id)
                                                    <span class="text-[10px] font-bold bg-[#21255E] text-white px-2 py-0.5 rounded-full">You</span>
                                                @endif
                                            </div>
                                            <p class="text-[12px] text-gray-500 mt-0.5">
                                                {{ $admin->email }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    @if($admin->phone)
                                        <span class="font-medium text-gray-800">
                                            {{ $admin->phone }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 italic">Not provided</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 font-bold text-xs border border-indigo-200/60">
                                        Super Admin
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($admin->status === 'active')
                                        <span class="rounded-full px-3 py-1 text-[11px] font-bold bg-green-50 text-green-700 border border-green-200 inline-flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span> Active
                                        </span>
                                    @else
                                        <span class="rounded-full px-3 py-1 text-[11px] font-bold bg-red-50 text-red-700 border border-red-200 inline-flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full bg-red-500"></span> Inactive
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-gray-500 font-medium">
                                    {{ $admin->created_at ? $admin->created_at->format('M d, Y') : 'N/A' }}
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        {{-- Edit Button --}}
                                        <button type="button" @click="openEdit({{ json_encode($admin) }})"
                                            class="px-3 py-1 rounded-full bg-gray-50 border border-gray-200 hover:bg-blue-50 hover:text-blue-700 hover:border-blue-200 text-xs font-semibold text-gray-600 transition-colors cursor-pointer"
                                            title="Edit Super Admin">
                                            Edit
                                        </button>

                                        {{-- Toggle Status Button --}}
                                        @if(auth()->id() !== $admin->id)
                                            <form action="{{ route('superadmin.admins.toggle-status', $admin) }}" method="POST" class="inline">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                    class="px-3 py-1 rounded-full bg-gray-50 border border-gray-200 hover:bg-amber-50 hover:text-amber-700 hover:border-amber-200 text-xs font-semibold text-gray-600 transition-colors cursor-pointer">
                                                    {{ $admin->status === 'active' ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </form>

                                            {{-- Delete Button --}}
                                            <button type="button" @click="confirmDelete({{ json_encode($admin) }})"
                                                class="px-3 py-1 rounded-full bg-gray-50 border border-gray-200 hover:bg-red-50 hover:text-red-700 hover:border-red-200 text-xs font-semibold text-gray-600 transition-colors cursor-pointer"
                                                title="Delete Super Admin">
                                                Delete
                                            </button>
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-gray-100 text-xs font-semibold text-gray-400 cursor-not-allowed">
                                                Protected
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                    <p class="font-bold text-gray-800 text-base">No Super Admins found</p>
                                    <p class="text-xs text-gray-400 mt-1 mb-4">Click below to add a new Super Admin account.</p>
                                    <button type="button" @click="openAdd()"
                                        class="inline-flex items-center text-white text-xs px-5 py-2.5 rounded-full font-bold transition-all shadow-md cursor-pointer"
                                        style="background-color: #D7B56D;">
                                        Add Super Admin
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($admins->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $admins->links() }}
                </div>
            @endif
        </div>

        {{-- Add / Edit Super Admin Modal --}}
        <div x-show="showModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            x-cloak>

            <div class="bg-white rounded-3xl shadow-xl w-full max-w-lg overflow-hidden border border-gray-100 transform transition-all max-h-[90vh] flex flex-col"
                @click.away="showModal = false">
                
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between shrink-0">
                    <h3 class="text-lg font-extrabold text-[#111827]" x-text="modalTitle"></h3>
                    <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-600 text-sm font-bold cursor-pointer">
                        ✕
                    </button>
                </div>

                <form :action="formAction" method="POST" class="p-6 space-y-4 overflow-y-auto flex-1">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    {{-- Full Name --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Full Name *</label>
                        <input type="text" name="name" x-model="formData.name" required
                            placeholder="e.g. Dr. John Doe"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50">
                    </div>

                    {{-- Email Address --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Email Address *</label>
                        <input type="email" name="email" x-model="formData.email" required
                            placeholder="admin@school.edu"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50">
                    </div>

                    {{-- Phone Number --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Phone Number (Optional)</label>
                        <input type="text" name="phone" x-model="formData.phone"
                            placeholder="+91 99999 88888"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50">
                    </div>

                    {{-- Password Fields --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                <span x-text="isEdit ? 'New Password (Optional)' : 'Password *'"></span>
                            </label>
                            <input type="password" name="password" :required="!isEdit" minlength="8"
                                placeholder="••••••••"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Confirm Password</label>
                            <input type="password" name="password_confirmation" minlength="8"
                                placeholder="••••••••"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50">
                        </div>
                    </div>
                    <p class="text-[11px] text-gray-400" x-show="isEdit">Leave password blank if you don't want to change it.</p>

                    {{-- Account Status --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Account Status *</label>
                        <div class="flex items-center gap-4">
                            <label class="flex items-center gap-2 text-xs font-bold text-gray-700 cursor-pointer">
                                <input type="radio" name="status" value="active" x-model="formData.status"
                                    class="w-4 h-4 text-[#21255E] focus:ring-[#21255E]">
                                <span>Active</span>
                            </label>
                            <label class="flex items-center gap-2 text-xs font-bold text-gray-700 cursor-pointer">
                                <input type="radio" name="status" value="inactive" x-model="formData.status"
                                    class="w-4 h-4 text-red-600 focus:ring-red-600">
                                <span>Inactive</span>
                            </label>
                        </div>
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100 shrink-0">
                        <button type="button" @click="showModal = false"
                            class="px-5 py-2.5 rounded-full border border-gray-200 text-gray-600 text-xs font-bold hover:bg-gray-50 transition-colors cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2.5 rounded-full text-white text-xs font-bold transition-all shadow-md hover:opacity-90 cursor-pointer"
                            style="background-color: #D7B56D;"
                            x-text="isEdit ? 'Update Super Admin' : 'Save Super Admin'">
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Custom Delete Confirmation Modal --}}
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

                <h3 class="text-xl font-extrabold text-[#111827] mb-2">Delete Super Admin Account?</h3>
                
                <p class="text-xs text-gray-500 leading-relaxed mb-4">
                    Are you sure you want to permanently remove <strong class="text-gray-900 font-bold" x-text="deleteAdminName"></strong> (<span x-text="deleteAdminEmail"></span>)?
                </p>

                <div class="bg-red-50/80 border border-red-200/80 rounded-2xl p-4 text-left mb-6">
                    <p class="text-xs text-red-900 font-bold leading-tight">Permanent Removal Notice</p>
                    <p class="text-[11px] text-red-800 font-medium mt-1 leading-normal">
                        This administrator will immediately lose all administrative privileges to access this portal.
                    </p>
                </div>

                {{-- Action Buttons --}}
                <form :action="deleteAction" method="POST" class="flex items-center justify-center gap-3">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="showDeleteModal = false"
                        class="flex-1 py-3 rounded-full border border-gray-200 text-gray-700 text-xs font-bold hover:bg-gray-50 transition-all cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 py-3 rounded-full bg-red-600 hover:bg-red-700 text-white text-xs font-bold transition-all shadow-md active:scale-95 cursor-pointer">
                        Yes, Delete Account
                    </button>
                </form>
            </div>
        </div>

    </div>
@endsection
