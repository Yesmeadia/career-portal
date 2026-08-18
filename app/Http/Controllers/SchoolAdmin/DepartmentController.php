<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDepartmentRequest;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount('vacancies')->paginate(15);
        return view('schooladmin.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('schooladmin.departments.create');
    }

    public function store(StoreDepartmentRequest $request)
    {
        $validated = $request->validated();
        $validated['school_id'] = auth()->user()->school_id;

        Department::create($validated);
        return redirect()->back()->with('success', 'Department created successfully.');
    }

    public function update(StoreDepartmentRequest $request, Department $department)
    {
        $this->authorize('update', $department);
        $department->update($request->validated());
        return redirect()->back()->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        $this->authorize('delete', $department);
        $department->delete();
        return redirect()->back()->with('success', 'Department deleted successfully.');
    }
}
