@extends('layouts.admin')

@section('title', 'My Profile')

@section('content')
<div class="space-y-8">

    <!-- Page Title Banner -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-2">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-1 tracking-tight">Account Profile</h1>
            <p class="text-gray-500 text-xs sm:text-sm">Manage your personal information, security credentials, and portal preferences.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-3.5 py-1.5 rounded-full text-xs font-semibold flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Active {{ auth()->user()->roles->first()?->name ?? 'User' }}
            </span>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-sm">
            <div class="font-bold flex items-center gap-2 mb-1">
                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                Please correct the following errors:
            </div>
            <ul class="list-disc list-inside space-y-0.5 text-xs text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Stats / Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <!-- User Identity Card -->
        <div class="bg-emerald-700 rounded-2xl p-6 text-white relative overflow-hidden shadow-sm flex items-center gap-4">
            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-full border-2 border-white/40 object-cover shrink-0 shadow-md">
            <div class="min-w-0">
                <h3 class="text-lg font-bold text-white truncate">{{ $user->name }}</h3>
                <p class="text-xs text-emerald-100 truncate">{{ $user->email }}</p>
                <span class="mt-2 inline-block bg-white/20 text-white text-[10px] font-semibold uppercase px-2.5 py-0.5 rounded-full">
                    {{ $user->roles->first()?->name ?? 'Admin Account' }}
                </span>
            </div>
        </div>

        <!-- School Context Card -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-gray-500 font-medium text-sm">Assigned Institution</h3>
                <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-building-columns"></i>
                </div>
            </div>
            <div>
                <p class="text-lg font-bold text-gray-900 truncate">{{ $user->school?->name ?? 'Global Portal' }}</p>
                <p class="text-xs text-gray-400 mt-1">
                    {{ $user->school ? ($user->school->city . ', ' . $user->school->country) : 'Super Admin Scope' }}
                </p>
            </div>
        </div>

        <!-- Member Since Card -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-gray-500 font-medium text-sm">Account Status</h3>
                <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
            </div>
            <div>
                <p class="text-lg font-bold text-gray-900">Member Since {{ $user->created_at->format('M Y') }}</p>
                <p class="text-xs text-emerald-600 font-medium mt-1">
                    <i class="fa-solid fa-circle-check text-[10px]"></i> Email & Role Verified
                </p>
            </div>
        </div>
    </div>

    <!-- Forms Section (2 Columns) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Column 1 & 2: General Profile Info -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-100 space-y-6">
            <div class="border-b border-gray-100 pb-4">
                <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-user-pen text-emerald-600"></i>
                    <span>Profile Details</span>
                </h2>
                <p class="text-xs text-gray-500 mt-1">Update your display name, contact email, and profile avatar.</p>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Full Name *</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50">
                        @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Email Address *</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50">
                        @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+91 98765 43210"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50">
                        @error('phone')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Profile Avatar</label>
                        <input type="file" name="avatar" accept="image/*"
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-colors">
                        @error('avatar')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="bg-emerald-700 hover:bg-emerald-800 text-white font-medium text-xs px-6 py-3 rounded-full transition-colors flex items-center gap-2 shadow-xs">
                        <i class="fa-solid fa-check text-xs"></i>
                        <span>Save Changes</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Column 3: Change Password -->
        <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-100 space-y-6">
            <div class="border-b border-gray-100 pb-4">
                <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-key text-emerald-600"></i>
                    <span>Security & Password</span>
                </h2>
                <p class="text-xs text-gray-500 mt-1">Ensure your account is using a strong password.</p>
            </div>

            <form action="{{ route('profile.password') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Current Password *</label>
                    <input type="password" name="current_password" required placeholder="••••••••"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 outline-none text-sm text-gray-800 bg-gray-50/50">
                    @error('current_password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">New Password *</label>
                    <input type="password" name="password" required placeholder="••••••••"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 outline-none text-sm text-gray-800 bg-gray-50/50">
                    @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Confirm New Password *</label>
                    <input type="password" name="password_confirmation" required placeholder="••••••••"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 outline-none text-sm text-gray-800 bg-gray-50/50">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white font-medium text-xs py-3 px-6 rounded-full transition-colors flex items-center justify-center gap-2 shadow-xs">
                        <i class="fa-solid fa-lock text-xs"></i>
                        <span>Update Password</span>
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>
@endsection
