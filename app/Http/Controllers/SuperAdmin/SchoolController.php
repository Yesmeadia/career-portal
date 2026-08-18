<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSchoolRequest;
use App\Models\School;
use App\Repositories\Contracts\SchoolRepositoryInterface;
use App\Services\SchoolService;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function __construct(
        protected SchoolRepositoryInterface $schoolRepository,
        protected SchoolService $schoolService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status']);
        $schools = $this->schoolRepository->paginate(15, $filters);
        return view('superadmin.schools.index', compact('schools', 'filters'));
    }

    public function create()
    {
        return view('superadmin.schools.create');
    }

    public function store(StoreSchoolRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('schools/logos', 'public');
        }
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('schools/covers', 'public');
        }

        $schoolData = array_diff_key($validated, array_flip(['admin_name', 'admin_email', 'admin_password']));
        $adminData = [
            'name' => $validated['admin_name'],
            'email' => $validated['admin_email'],
            'password' => $validated['admin_password'],
        ];

        $this->schoolService->createSchoolWithAdmin($schoolData, $adminData);

        return redirect()->route('superadmin.schools.index')->with('success', 'School and Admin account created successfully.');
    }

    public function edit(School $school)
    {
        $school->load('users');
        return view('superadmin.schools.edit', compact('school'));
    }

    public function update(StoreSchoolRequest $request, School $school)
    {
        $validated = $request->validated();

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('schools/logos', 'public');
        }
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('schools/covers', 'public');
        }

        $this->schoolService->updateSchool($school, $validated);

        return redirect()->route('superadmin.schools.index')->with('success', 'School updated successfully.');
    }

    public function toggleStatus(School $school)
    {
        $this->schoolService->toggleSchoolStatus($school);
        return redirect()->back()->with('success', 'School status updated successfully.');
    }

    public function destroy(School $school)
    {
        $this->schoolRepository->delete($school);
        return redirect()->route('superadmin.schools.index')->with('success', 'School deleted successfully.');
    }
}
