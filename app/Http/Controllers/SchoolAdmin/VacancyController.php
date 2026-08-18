<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\DTOs\VacancyData;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVacancyRequest;
use App\Models\Vacancy;
use App\Models\Department;
use App\Models\GlobalClass;
use App\Models\JobCategory;
use App\Repositories\Contracts\VacancyRepositoryInterface;
use App\Services\VacancyService;
use Illuminate\Http\Request;

class VacancyController extends Controller
{
    public function __construct(
        protected VacancyRepositoryInterface $vacancyRepository,
        protected VacancyService $vacancyService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status']);
        $filters['school_id'] = auth()->user()->school_id;
        $vacancies = $this->vacancyRepository->paginateAdmin($filters, 15);

        return view('schooladmin.vacancies.index', compact('vacancies', 'filters'));
    }

    public function create()
    {
        $departments = Department::where('is_active', true)->get();
        $globalClasses = GlobalClass::where('is_active', true)->get();
        $categories = JobCategory::where('is_active', true)->get();

        return view('schooladmin.vacancies.create', compact('departments', 'globalClasses', 'categories'));
    }

    public function store(StoreVacancyRequest $request)
    {
        $validated = $request->validated();
        $validated['school_id'] = auth()->user()->school_id;

        $dto = VacancyData::fromArray($validated);
        $this->vacancyService->createVacancy($dto);

        return redirect()->route('schooladmin.vacancies.index')->with('success', 'Vacancy created successfully.');
    }

    public function edit(Vacancy $vacancy)
    {
        $this->authorize('update', $vacancy);

        $departments = Department::where('is_active', true)->get();
        $globalClasses = GlobalClass::where('is_active', true)->get();
        $categories = JobCategory::where('is_active', true)->get();

        return view('schooladmin.vacancies.edit', compact('vacancy', 'departments', 'globalClasses', 'categories'));
    }

    public function update(StoreVacancyRequest $request, Vacancy $vacancy)
    {
        $this->authorize('update', $vacancy);

        $validated = $request->validated();
        $validated['school_id'] = auth()->user()->school_id;

        $dto = VacancyData::fromArray($validated);
        $this->vacancyService->updateVacancy($vacancy, $dto);

        return redirect()->route('schooladmin.vacancies.index')->with('success', 'Vacancy updated successfully.');
    }

    public function destroy(Vacancy $vacancy)
    {
        $this->authorize('delete', $vacancy);
        $this->vacancyService->deleteVacancy($vacancy);

        return redirect()->route('schooladmin.vacancies.index')->with('success', 'Vacancy deleted successfully.');
    }

    public function toggleStatus(Vacancy $vacancy)
    {
        $this->authorize('update', $vacancy);

        $newStatus = (strtolower($vacancy->status) === 'published') ? 'draft' : 'published';
        $vacancy->status = $newStatus;
        $vacancy->save();

        $message = ($newStatus === 'published')
            ? 'Vacancy enabled and published successfully.'
            : 'Vacancy disabled (moved to Draft).';

        return redirect()->back()->with('success', $message);
    }
}
