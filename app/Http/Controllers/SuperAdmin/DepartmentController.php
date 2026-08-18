<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\School;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'school_id', 'status']);
        
        $query = Department::withoutGlobalScopes()->with(['school'])->withCount('vacancies');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['school_id'])) {
            $query->where('school_id', $filters['school_id']);
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('is_active', $filters['status'] === 'active');
        }

        $departments = $query->latest()->paginate(15)->withQueryString();
        $schools = School::orderBy('name')->get();

        $stats = [
            'total_departments' => Department::withoutGlobalScopes()->count(),
            'active_departments' => Department::withoutGlobalScopes()->where('is_active', true)->count(),
            'schools_count' => School::has('departments')->count(),
            'vacancies_count' => Department::withoutGlobalScopes()->withCount('vacancies')->get()->sum('vacancies_count'),
        ];

        return view('superadmin.departments.index', compact('departments', 'schools', 'filters', 'stats'));
    }

    public function show($id = null)
    {
        return redirect()->route('superadmin.departments.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : true;

        Department::withoutGlobalScopes()->create($validated);

        return redirect()->route('superadmin.departments.index')->with('success', 'Department created successfully.');
    }

    public function update(Request $request, $id)
    {
        $department = Department::withoutGlobalScopes()->findOrFail($id);

        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $department->update($validated);

        return redirect()->route('superadmin.departments.index')->with('success', 'Department updated successfully.');
    }

    public function toggleStatus($id)
    {
        $department = Department::withoutGlobalScopes()->findOrFail($id);

        $department->update([
            'is_active' => !$department->is_active,
        ]);

        return redirect()->back()->with('success', 'Department status updated successfully.');
    }

    public function destroy($id)
    {
        $department = Department::withoutGlobalScopes()->find($id);

        if ($department) {
            $name = $department->name;
            $department->delete();
            return redirect()->route('superadmin.departments.index')
                ->with('success', "Department '{$name}' deleted successfully. Job Categories and Classes remain untouched.");
        }

        return redirect()->route('superadmin.departments.index')
            ->with('info', 'Department has already been deleted.');
    }
}
