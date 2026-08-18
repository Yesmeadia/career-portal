@extends('layouts.admin')

@section('title', 'Add New School')

@section('content')
    <div class="max-w-[1200px] mx-auto">

        <!-- Page Title & Actions Header -->
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <a href="{{ route('superadmin.schools.index') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-500 hover:text-[#21255E] mb-3 transition-colors">
                    <span class="material-symbols-outlined text-[16px]">arrow_back</span> Back to Schools Directory
                </a>
                <h2 class="text-4xl font-extrabold text-[#111827] tracking-tight leading-none" style="font-size: 36px;">Register New School</h2>
                <p class="text-gray-500 text-sm mt-2 font-medium">Configure institution profile and generate initial school admin credentials.</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('superadmin.schools.index') }}"
                    class="bg-white border border-gray-200 text-gray-600 px-5 py-2.5 rounded-full text-xs font-bold hover:bg-gray-50 transition-all shadow-2xs">
                    Cancel
                </a>
            </div>
        </div>

        <form action="{{ route('superadmin.schools.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Section 1: School Profile & Identity -->
            <div class="bg-white shadow-sm border border-gray-100 p-6 sm:p-8 space-y-6" style="border-radius: 20px;">
                <div class="border-b border-gray-100 pb-4 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-extrabold shrink-0">
                        1
                    </div>
                    <div>
                        <h3 class="text-[17px] font-bold text-[#111827] flex items-center gap-2">
                            <span class="material-symbols-outlined text-blue-600 text-[20px]">school</span>
                            School Profile &amp; Identity
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Official institution name, contact details, and location details.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">School Full Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            placeholder="e.g. Heritage International Academy"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50 transition-all">
                        @error('name')
                        <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Status *</label>
                        <select name="status"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50 transition-all">
                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Official Email Address *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            placeholder="admin@school.edu"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50 transition-all">
                        @error('email')
                        <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                            placeholder="+91 98765 43210"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Website URL</label>
                        <input type="url" name="website" value="{{ old('website') }}"
                            placeholder="https://www.school.edu"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Timezone</label>
                        <input type="text" name="timezone" value="{{ old('timezone', 'Asia/Kolkata') }}" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50 transition-all">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Campus Address</label>
                        <textarea name="address" rows="2"
                            placeholder="Street / Area Campus address..."
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50 resize-none transition-all">{{ old('address') }}</textarea>
                    </div>

                    {{-- Pincode with auto-fetch --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">PIN / Zip Code</label>
                        <div class="relative">
                            <input type="text" id="create_pincode" name="postal_code" value="{{ old('postal_code') }}"
                                placeholder="185101"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50 pr-10 transition-all">
                            <span id="create_pincode_spinner" class="hidden absolute right-3 top-1/2 -translate-y-1/2">
                                <svg class="animate-spin h-4 w-4 text-[#21255E]" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                </svg>
                            </span>
                            <span id="create_pincode_ok"
                                class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-green-600 text-xs font-bold">✓</span>
                            <span id="create_pincode_err"
                                class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-red-500 text-xs font-bold">✗</span>
                        </div>
                        <p id="create_pincode_msg" class="mt-1.5 text-[11px] text-gray-400">Enter 6-digit PIN to auto-fill City &amp; State</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">City</label>
                        <input type="text" id="create_city" name="city" value="{{ old('city') }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">State</label>
                        <input type="text" id="create_state" name="state" value="{{ old('state') }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Country</label>
                        <input type="text" name="country" value="{{ old('country', 'India') }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50 transition-all">
                    </div>
                </div>
            </div>

            <!-- Section 2: Initial School Admin User -->
            <div class="bg-white shadow-sm border border-gray-100 p-6 sm:p-8 space-y-6" style="border-radius: 20px;">
                <div class="border-b border-gray-100 pb-4 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-sm font-extrabold shrink-0">
                        2
                    </div>
                    <div>
                        <h3 class="text-[17px] font-bold text-[#111827] flex items-center gap-2">
                            <span class="material-symbols-outlined text-purple-600 text-[20px]">manage_accounts</span>
                            Initial School Administrator Account
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Credentials for managing this institution's vacancies and candidate applications.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Admin Full Name *</label>
                        <input type="text" name="admin_name" value="{{ old('admin_name') }}" required
                            placeholder="John Doe"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50 transition-all">
                        @error('admin_name')
                        <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Admin Login Email *</label>
                        <input type="email" name="admin_email" value="{{ old('admin_email') }}" required
                            placeholder="admin.user@school.edu"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50 transition-all">
                        @error('admin_email')
                        <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Admin Account Password *</label>
                        <input type="password" name="admin_password" required
                            placeholder="••••••••••••"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50 transition-all">
                        @error('admin_password')
                        <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-4 pt-2">
                <a href="{{ route('superadmin.schools.index') }}"
                    class="px-6 py-3 rounded-full border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-all shadow-2xs">
                    Cancel
                </a>
                <button type="submit"
                    class="text-white font-bold text-xs px-8 py-3 rounded-full transition-all shadow-md flex items-center gap-2 active:scale-95"
                    style="background-color: #D7B56D;">
                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                    <span>Register School &amp; Account</span>
                </button>
            </div>
        </form>

    </div>
@endsection