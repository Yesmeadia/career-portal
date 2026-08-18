@extends('layouts.admin')

@section('title', 'Edit Vacancy — ' . $vacancy->title)
@section('page-title', 'Edit Vacancy')
@section('page-subtitle', 'Update vacancy parameters, requirements, deadline, and publication status')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <a href="{{ route('schooladmin.vacancies.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-indigo-600 transition-colors font-medium">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Back to Vacancies
    </a>

    <form action="{{ route('schooladmin.vacancies.update', $vacancy) }}" method="POST" class="space-y-8">
        @csrf @method('PUT')
        <input type="hidden" name="school_id" value="{{ auth()->user()->school_id }}">

        {{-- Main Info --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6">
            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                <i data-lucide="briefcase" class="w-4 h-4 text-indigo-600"></i>
                Vacancy Details
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Job Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $vacancy->title) }}" required placeholder="e.g. Senior PGT English Teacher"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none text-sm font-sans">
                    @error('title')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Vacancy Scope <span class="text-red-500">*</span></label>
                    <select name="vacancy_type" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none text-sm bg-white font-sans">
                        <option value="campus" {{ old('vacancy_type', $vacancy->vacancy_type) === 'campus' ? 'selected' : '' }}>Campus Wide</option>
                        <option value="class" {{ old('vacancy_type', $vacancy->vacancy_type) === 'class' ? 'selected' : '' }}>Specific Class Level</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Department <span class="text-red-500">*</span></label>
                    <select name="department_id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none text-sm bg-white font-sans">
                        <option value="">Select Department</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id', $vacancy->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Global Class Level <span class="text-slate-400 font-normal">(optional)</span></label>
                    <select name="global_class_id" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none text-sm bg-white font-sans">
                        <option value="">Campus Wide / All Classes</option>
                        @foreach($globalClasses as $gc)
                        <option value="{{ $gc->id }}" {{ old('global_class_id', $vacancy->global_class_id) == $gc->id ? 'selected' : '' }}>{{ $gc->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Job Category <span class="text-red-500">*</span></label>
                    <select name="job_category_id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none text-sm bg-white font-sans">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('job_category_id', $vacancy->job_category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Employment Type <span class="text-red-500">*</span></label>
                    <select name="employment_type" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none text-sm bg-white font-sans">
                        <option value="full_time" {{ old('employment_type', $vacancy->employment_type) === 'full_time' ? 'selected' : '' }}>Full Time</option>
                        <option value="part_time" {{ old('employment_type', $vacancy->employment_type) === 'part_time' ? 'selected' : '' }}>Part Time</option>
                        <option value="contract" {{ old('employment_type', $vacancy->employment_type) === 'contract' ? 'selected' : '' }}>Contract</option>
                        <option value="temporary" {{ old('employment_type', $vacancy->employment_type) === 'temporary' ? 'selected' : '' }}>Temporary</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Experience Level</label>
                    <input type="text" name="experience_level" value="{{ old('experience_level', $vacancy->experience_level) }}" placeholder="e.g. 3-5 Years"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none text-sm font-sans">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Minimum Qualification</label>
                    <input type="text" name="min_qualification" value="{{ old('min_qualification', $vacancy->min_qualification) }}" placeholder="e.g. M.A., B.Ed"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none text-sm font-sans">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Salary From (₹/month)</label>
                    <input type="number" name="salary_from" value="{{ old('salary_from', $vacancy->salary_from) }}" placeholder="30000"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none text-sm font-sans">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Salary To (₹/month)</label>
                    <input type="number" name="salary_to" value="{{ old('salary_to', $vacancy->salary_to) }}" placeholder="50000"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none text-sm font-sans">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Gender Preference</label>
                    <select name="gender_preference" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none text-sm bg-white font-sans">
                        <option value="any" {{ old('gender_preference', $vacancy->gender_preference) === 'any' ? 'selected' : '' }}>Any Gender</option>
                        <option value="male" {{ old('gender_preference', $vacancy->gender_preference) === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender_preference', $vacancy->gender_preference) === 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Number of Vacancies <span class="text-red-500">*</span></label>
                    <input type="number" name="number_of_vacancies" value="{{ old('number_of_vacancies', $vacancy->number_of_vacancies ?: 1) }}" min="1" required
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none text-sm font-sans">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Location / Campus</label>
                    <input type="text" name="location" value="{{ old('location', $vacancy->location) }}" placeholder="Main Campus, Bangalore"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none text-sm font-sans">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Application Deadline</label>
                    <input type="date" name="deadline" value="{{ old('deadline', $vacancy->deadline?->format('Y-m-d')) }}"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none text-sm font-sans">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Publish Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none text-sm bg-white font-sans">
                        <option value="published" {{ old('status', $vacancy->status) === 'published' ? 'selected' : '' }}>Published (Live)</option>
                        <option value="draft" {{ old('status', $vacancy->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="closed" {{ old('status', $vacancy->status) === 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>

                <div class="flex items-center pt-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $vacancy->is_featured) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 rounded">
                        <span class="text-sm font-semibold text-slate-700">Mark as Featured Job</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Description & Details --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6">
            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                <i data-lucide="file-text" class="w-4 h-4 text-indigo-600"></i>
                Job Description &amp; Content
            </h2>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Full Job Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="5" required placeholder="Detailed job overview..."
                          class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none text-sm font-sans resize-y">{{ old('description', $vacancy->description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Key Responsibilities</label>
                <textarea name="responsibilities" rows="4" placeholder="Bullet points or paragraph of responsibilities..."
                          class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none text-sm font-sans resize-y">{{ old('responsibilities', $vacancy->responsibilities) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Requirements &amp; Qualifications</label>
                <textarea name="requirements" rows="4" placeholder="Educational degrees, certifications, skills needed..."
                          class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none text-sm font-sans resize-y">{{ old('requirements', $vacancy->requirements) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Benefits &amp; Perks</label>
                <textarea name="benefits" rows="3" placeholder="Health insurance, accommodation, transport..."
                          class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none text-sm font-sans resize-y">{{ old('benefits', $vacancy->benefits) }}</textarea>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex justify-end gap-4">
            <a href="{{ route('schooladmin.vacancies.index') }}" class="px-6 py-3 bg-slate-100 text-slate-700 font-semibold rounded-xl text-sm hover:bg-slate-200 transition-colors">Cancel</a>
            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold rounded-xl text-sm transition-all shadow-lg shadow-indigo-600/30 flex items-center gap-2">
                <i data-lucide="check" class="w-4 h-4"></i>
                Update Vacancy
            </button>
        </div>
    </form>
</div>
@endsection
