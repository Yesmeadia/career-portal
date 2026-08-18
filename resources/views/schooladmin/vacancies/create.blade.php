@extends('layouts.admin')

@section('title', 'Post New Vacancy')
@section('page-title', 'Create Vacancy')
@section('page-subtitle', 'Fill out the form below to post a new job vacancy for your school')

@section('content')
<div class="max-w-4xl mx-auto">
    <a href="{{ route('schooladmin.vacancies.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-blue-600 mb-6 transition-colors font-medium">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Vacancies
    </a>

    <form action="{{ route('schooladmin.vacancies.store') }}" method="POST" class="space-y-8">
        @csrf
        <input type="hidden" name="school_id" value="{{ auth()->user()->school_id }}">

        {{-- Main Info --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900 mb-6">Vacancy Details</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Job Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. Senior PGT English Teacher"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm font-figtree">
                    @error('title')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Vacancy Scope <span class="text-red-500">*</span></label>
                    <select name="vacancy_type" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white font-figtree">
                        <option value="campus">Campus Wide</option>
                        <option value="class">Specific Class Level</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Department <span class="text-red-500">*</span></label>
                    <select name="department_id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white font-figtree">
                        <option value="">Select Department</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Global Class Level <span class="text-slate-400 font-normal">(optional)</span></label>
                    <select name="global_class_id" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white font-figtree">
                        <option value="">Campus Wide / All Classes</option>
                        @foreach($globalClasses as $gc)
                        <option value="{{ $gc->id }}">{{ $gc->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Job Category <span class="text-red-500">*</span></label>
                    <select name="job_category_id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white font-figtree">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Employment Type <span class="text-red-500">*</span></label>
                    <select name="employment_type" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white font-figtree">
                        <option value="full_time">Full Time</option>
                        <option value="part_time">Part Time</option>
                        <option value="contract">Contract</option>
                        <option value="temporary">Temporary</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Experience Level</label>
                    <input type="text" name="experience_level" value="{{ old('experience_level') }}" placeholder="e.g. 3-5 Years"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm font-figtree">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Minimum Qualification</label>
                    <input type="text" name="min_qualification" value="{{ old('min_qualification') }}" placeholder="e.g. M.A., B.Ed"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm font-figtree">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Salary From (₹/month)</label>
                    <input type="number" name="salary_from" value="{{ old('salary_from') }}" placeholder="30000"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm font-figtree">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Salary To (₹/month)</label>
                    <input type="number" name="salary_to" value="{{ old('salary_to') }}" placeholder="50000"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm font-figtree">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Gender Preference</label>
                    <select name="gender_preference" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white font-figtree">
                        <option value="any">Any Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Number of Vacancies <span class="text-red-500">*</span></label>
                    <input type="number" name="number_of_vacancies" value="{{ old('number_of_vacancies', 1) }}" min="1" required
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm font-figtree">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Location / Campus</label>
                    <input type="text" name="location" value="{{ old('location') }}" placeholder="Main Campus, Bangalore"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm font-figtree">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Application Deadline</label>
                    <input type="date" name="deadline" value="{{ old('deadline') }}"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm font-figtree">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Publish Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white font-figtree">
                        <option value="published">Published (Live)</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>

                <div class="flex items-center pt-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" class="w-4 h-4 text-blue-600 rounded">
                        <span class="text-sm font-semibold text-slate-700">Mark as Featured Job ⭐</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Description & Details --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6">
            <h2 class="text-lg font-bold text-slate-900">Job Description &amp; Content</h2>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Full Job Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="5" required placeholder="Detailed job overview..."
                          class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm font-figtree resize-y">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Key Responsibilities</label>
                <textarea name="responsibilities" rows="4" placeholder="Bullet points or paragraph of responsibilities..."
                          class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm font-figtree resize-y">{{ old('responsibilities') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Requirements &amp; Qualifications</label>
                <textarea name="requirements" rows="4" placeholder="Educational degrees, certifications, skills needed..."
                          class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm font-figtree resize-y">{{ old('requirements') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Benefits &amp; Perks</label>
                <textarea name="benefits" rows="3" placeholder="Health insurance, accommodation, transport..."
                          class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm font-figtree resize-y">{{ old('benefits') }}</textarea>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex justify-end gap-4">
            <a href="{{ route('schooladmin.vacancies.index') }}" class="px-6 py-3 bg-slate-100 text-slate-700 font-semibold rounded-xl text-sm hover:bg-slate-200 transition-colors">Cancel</a>
            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white font-semibold rounded-xl text-sm transition-all shadow-lg shadow-blue-600/30">
                Post Vacancy
            </button>
        </div>
    </form>
</div>
@endsection
