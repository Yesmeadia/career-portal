<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\JobCategory;
use Illuminate\Http\Request;

class JobCategoryController extends Controller
{
    public function index()
    {
        $categories = JobCategory::withCount('vacancies')->paginate(15);
        return view('superadmin.categories.index', compact('categories'));
    }

    public function show($id = null)
    {
        return redirect()->route('superadmin.job-categories.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:100',
        ]);

        JobCategory::create($validated);
        return redirect()->back()->with('success', 'Job Category created successfully.');
    }

    public function update(Request $request, $id)
    {
        $category = JobCategory::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'icon'        => 'nullable|string|max:100',
            'is_active'   => 'nullable',
        ]);

        // Cast is_active to bool
        $validated['is_active'] = isset($validated['is_active']) ? (bool) $validated['is_active'] : $category->is_active;

        $category->update($validated);
        return redirect()->back()->with('success', 'Job Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = JobCategory::find($id);
        if ($category) {
            $name = $category->name;
            $category->delete();
            return redirect()->back()->with('success', "Job Category '{$name}' deleted successfully.");
        }
        return redirect()->back()->with('info', 'Job Category already removed.');
    }
}
