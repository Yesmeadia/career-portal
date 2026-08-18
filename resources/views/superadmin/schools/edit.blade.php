@extends('layouts.admin')

@section('title', 'Edit School: ' . $school->name)

@section('content')
<div class="max-w-[1200px] mx-auto">

    <!-- Page Title & Actions Header -->
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <a href="{{ route('superadmin.schools.index') }}"
                class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-500 hover:text-[#21255E] mb-3 transition-colors">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span> Back to Schools Directory
            </a>
            <h2 class="text-4xl font-extrabold text-[#111827] tracking-tight leading-none" style="font-size: 36px;">Edit School Institution</h2>
            <p class="text-gray-500 text-sm mt-2 font-medium">Update institution settings and information for {{ $school->name }}.</p>
        </div>

        <div class="flex items-center gap-3">
            <span class="rounded-full px-4 py-1.5 text-xs font-bold shadow-2xs {{ $school->status === 'active' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                Status: {{ ucfirst($school->status) }}
            </span>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-4 mb-6 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-sm">
            <div class="font-bold flex items-center gap-2 mb-1">
                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                Please fix the following issues:
            </div>
            <ul class="list-disc list-inside space-y-0.5 text-xs text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('superadmin.schools.update', $school) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- School Profile & Identity -->
        <div class="bg-white shadow-sm border border-gray-100 p-6 sm:p-8 space-y-6" style="border-radius: 20px;">
            <div class="border-b border-gray-100 pb-4 flex items-center justify-between">
                <div>
                    <h3 class="text-[17px] font-bold text-[#111827] flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-600 text-[20px]">school</span>
                        School Profile &amp; Identity
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Official institution name, contact details, and location.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">School Full Name *</label>
                    <input type="text" name="name" value="{{ old('name', $school->name) }}" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50 transition-all">
                    @error('name')<p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Status *</label>
                    <select name="status" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50 transition-all">
                        <option value="active" {{ old('status', $school->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $school->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Official Email Address *</label>
                    <input type="email" name="email" value="{{ old('email', $school->email) }}" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50 transition-all">
                    @error('email')<p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $school->phone) }}"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Website URL</label>
                    <input type="url" name="website" value="{{ old('website', $school->website) }}"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Timezone</label>
                    <input type="text" name="timezone" value="{{ old('timezone', $school->timezone) }}" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50 transition-all">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Campus Address</label>
                    <textarea name="address" rows="2"
                              class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50 resize-none transition-all">{{ old('address', $school->address) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">City</label>
                    <input type="text" name="city" value="{{ old('city', $school->city) }}"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">State</label>
                    <input type="text" name="state" value="{{ old('state', $school->state) }}"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Country</label>
                    <input type="text" name="country" value="{{ old('country', $school->country) }}"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">PIN / Zip Code</label>
                    <input type="text" name="postal_code" value="{{ old('postal_code', $school->postal_code) }}"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50 transition-all">
                </div>
            </div>
        </div>

        @php
            $adminUser = $school->users->first();
        @endphp

        <!-- School Admin User Account -->
        <div class="bg-white shadow-sm border border-gray-100 p-6 sm:p-8 space-y-6" style="border-radius: 20px;">
            <div class="border-b border-gray-100 pb-4 flex items-center justify-between">
                <div>
                    <h3 class="text-[17px] font-bold text-[#111827] flex items-center gap-2">
                        <span class="material-symbols-outlined text-emerald-600 text-[20px]">manage_accounts</span>
                        Assigned School Administrator Account
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Manage the login account and credentials for this school's admin.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Admin Full Name</label>
                    <input type="text" name="admin_name" value="{{ old('admin_name', $adminUser?->name) }}" placeholder="e.g. Campus Admin"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50 transition-all">
                    @error('admin_name')<p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Admin Login Email</label>
                    <input type="email" name="admin_email" value="{{ old('admin_email', $adminUser?->email) }}" placeholder="admin@school.com"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50 transition-all">
                    @error('admin_email')<p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Reset Admin Password <span class="text-gray-400 font-normal lowercase">(leave empty to keep current password)</span></label>
                    <input type="password" name="admin_password" placeholder="•••••••• (Enter new password only if changing)"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50 transition-all">
                    @error('admin_password')<p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-4 pt-2">
            <a href="{{ route('superadmin.schools.index') }}" class="px-6 py-3 rounded-full border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-all shadow-2xs">
                Cancel
            </a>
            <button type="submit"
                    class="text-white font-bold text-xs px-8 py-3 rounded-full transition-all shadow-md flex items-center gap-2 active:scale-95 cursor-pointer"
                    style="background-color: #D7B56D;">
                <span class="material-symbols-outlined text-[18px]">save</span>
                <span>Save School Changes</span>
            </button>
        </div>
    </form>

</div>
@endsection
