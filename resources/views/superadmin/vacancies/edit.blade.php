@extends('layouts.admin')

@section('title', 'Edit Vacancy — ' . $vacancy->title)
@section('page-title', 'Edit Vacancy')
@section('page-subtitle', 'Update job vacancy details, scope, compensation, and publication status')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <a href="{{ route('superadmin.vacancies.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-blue-600 transition-colors font-medium">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Vacancies
    </a>

    @if ($errors->any())
        <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-sm">
            <div class="font-bold flex items-center gap-2 mb-1">
                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                Please fix the following errors before submitting:
            </div>
            <ul class="list-disc list-inside space-y-0.5 text-xs text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('superadmin.vacancies.update', $vacancy) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        {{-- Institution Selection --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-600"></span> Target Institution
            </h2>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">School Institution <span class="text-red-500">*</span></label>
                <select name="school_id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white font-figtree font-semibold">
                    @foreach($schools as $sch)
                        <option value="{{ $sch->id }}" {{ old('school_id', $vacancy->school_id) == $sch->id ? 'selected' : '' }}>
                            {{ $sch->name }}
                        </option>
                    @endforeach
                </select>
                @error('school_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Main Info --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 mb-6 pb-2 border-b border-slate-100 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-600"></span> Vacancy Details
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Job Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $vacancy->title) }}" required placeholder="e.g. Senior PGT English Teacher"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm font-figtree">
                    @error('title')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Vacancy Scope <span class="text-red-500">*</span></label>
                    <select name="vacancy_type" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white font-figtree">
                        <option value="campus" {{ old('vacancy_type', $vacancy->vacancy_type) === 'campus' ? 'selected' : '' }}>Campus Wide</option>
                        <option value="class" {{ old('vacancy_type', $vacancy->vacancy_type) === 'class' ? 'selected' : '' }}>Specific Class Level</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Department <span class="text-red-500">*</span></label>
                    <select name="department_id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white font-figtree">
                        <option value="">Select Department</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id', $vacancy->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Global Class Level <span class="text-slate-400 font-normal lowercase">(optional)</span></label>
                    <select name="global_class_id" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white font-figtree">
                        <option value="">Campus Wide / All Classes</option>
                        @foreach($globalClasses as $gc)
                        <option value="{{ $gc->id }}" {{ old('global_class_id', $vacancy->global_class_id) == $gc->id ? 'selected' : '' }}>{{ $gc->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Job Category <span class="text-red-500">*</span></label>
                    <select name="job_category_id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white font-figtree">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('job_category_id', $vacancy->job_category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('job_category_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Employment Type <span class="text-red-500">*</span></label>
                    <select name="employment_type" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white font-figtree">
                        <option value="full_time" {{ old('employment_type', $vacancy->employment_type) === 'full_time' ? 'selected' : '' }}>Full Time</option>
                        <option value="part_time" {{ old('employment_type', $vacancy->employment_type) === 'part_time' ? 'selected' : '' }}>Part Time</option>
                        <option value="contract" {{ old('employment_type', $vacancy->employment_type) === 'contract' ? 'selected' : '' }}>Contract</option>
                        <option value="temporary" {{ old('employment_type', $vacancy->employment_type) === 'temporary' ? 'selected' : '' }}>Temporary</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Experience Level</label>
                    <input type="text" name="experience_level" value="{{ old('experience_level', $vacancy->experience_level) }}" placeholder="e.g. 3-5 Years"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm font-figtree">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Gender Preference</label>
                    <select name="gender_preference" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white font-figtree">
                        <option value="any" {{ old('gender_preference', $vacancy->gender_preference) === 'any' ? 'selected' : '' }}>Any Gender</option>
                        <option value="male" {{ old('gender_preference', $vacancy->gender_preference) === 'male' ? 'selected' : '' }}>Male Only</option>
                        <option value="female" {{ old('gender_preference', $vacancy->gender_preference) === 'female' ? 'selected' : '' }}>Female Only</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Location / Campus</label>
                    <input type="text" name="location" value="{{ old('location', $vacancy->location) }}" placeholder="e.g. South Campus, New Delhi"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm font-figtree">
                </div>
            </div>
        </div>

        {{-- Compensation & Additional Details --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 mb-6 pb-2 border-b border-slate-100 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-600"></span> Requirements, Numbers &amp; Status
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Minimum Qualification</label>
                    <input type="text" name="min_qualification" value="{{ old('min_qualification', $vacancy->min_qualification) }}" placeholder="e.g. Master's in Physics with B.Ed."
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm font-figtree">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Number of Openings <span class="text-red-500">*</span></label>
                    <input type="number" name="number_of_vacancies" value="{{ old('number_of_vacancies', $vacancy->number_of_vacancies ?: 1) }}" min="1" required
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm font-figtree">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Salary Range From (₹/month)</label>
                    <input type="number" name="salary_from" value="{{ old('salary_from', $vacancy->salary_from) }}" placeholder="e.g. 25000"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm font-figtree">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Salary Range To (₹/month)</label>
                    <input type="number" name="salary_to" value="{{ old('salary_to', $vacancy->salary_to) }}" placeholder="e.g. 40000"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm font-figtree">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Application Deadline</label>
                    <input type="date" name="deadline" value="{{ old('deadline', $vacancy->deadline?->format('Y-m-d')) }}"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white font-figtree">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white font-figtree font-bold">
                        @foreach(['published'=>'Published (Live)','draft'=>'Draft','closed'=>'Closed','expired'=>'Expired','archived'=>'Archived'] as $val => $lbl)
                            <option value="{{ $val }}" {{ old('status', $vacancy->status) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2 pt-2">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $vacancy->is_featured) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded">
                        <span class="text-sm font-semibold text-slate-700">Mark as Featured Job Opportunity</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Description & Details --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6">
            <h2 class="text-base font-bold text-slate-900 mb-6 pb-2 border-b border-slate-100 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-600"></span> Detailed Content
            </h2>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Job Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="5" required placeholder="Detailed job overview and expectations..."
                          class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm font-figtree resize-y">{{ old('description', $vacancy->description) }}</textarea>
                @error('description')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Key Responsibilities</label>
                <textarea name="responsibilities" rows="4" placeholder="List of day-to-day responsibilities..."
                          class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm font-figtree resize-y">{{ old('responsibilities', $vacancy->responsibilities) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Requirements &amp; Qualifications</label>
                <textarea name="requirements" rows="4" placeholder="Skills, certifications, educational background..."
                          class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm font-figtree resize-y">{{ old('requirements', $vacancy->requirements) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Benefits &amp; Perks</label>
                <textarea name="benefits" rows="3" placeholder="Health perks, housing allowance, PF..."
                          class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm font-figtree resize-y">{{ old('benefits', $vacancy->benefits) }}</textarea>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('superadmin.vacancies.index') }}" class="px-6 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-8 py-3 rounded-xl bg-[#21255E] text-white text-sm font-bold shadow-md hover:bg-[#191c49] transition-all cursor-pointer flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
