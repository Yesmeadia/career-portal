<?php

namespace App\Http\Controllers\SuperAdmin;

use App\DTOs\VacancyData;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVacancyRequest;
use App\Models\Department;
use App\Models\GlobalClass;
use App\Models\JobCategory;
use App\Models\School;
use App\Models\Vacancy;
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
        $filters = $request->only(['search', 'status', 'school_id']);
        
        $query = Vacancy::withoutGlobalScopes()
            ->with(['school', 'department', 'globalClass', 'category'])
            ->withCount('applications')
            ->latest();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('department', function ($dq) use ($search) {
                      $dq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('school', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['school_id'])) {
            $query->where('school_id', $filters['school_id']);
        }

        $vacancies = $query->paginate(15)->withQueryString();
        $schools = School::orderBy('name')->get();

        return view('superadmin.vacancies.index', compact('vacancies', 'filters', 'schools'));
    }

    public function create()
    {
        $schools = School::where('status', 'active')->orderBy('name')->get();
        $departments = Department::withoutGlobalScopes()->where('is_active', true)->get();
        $globalClasses = GlobalClass::where('is_active', true)->get();
        $categories = JobCategory::where('is_active', true)->get();

        return view('superadmin.vacancies.create', compact('schools', 'departments', 'globalClasses', 'categories'));
    }

    public function store(StoreVacancyRequest $request)
    {
        $validated = $request->validated();
        
        if (empty($validated['school_id'])) {
            return redirect()->back()->withInput()->with('error', 'Please select a school.');
        }

        $dto = VacancyData::fromArray($validated);
        $this->vacancyService->createVacancy($dto);

        return redirect()->route('superadmin.vacancies.index')->with('success', 'Vacancy created successfully.');
    }

    public function edit(Vacancy $vacancy)
    {
        $vacancy = Vacancy::withoutGlobalScopes()->findOrFail($vacancy->id);
        $schools = School::where('status', 'active')->orderBy('name')->get();
        $departments = Department::withoutGlobalScopes()->where('is_active', true)->get();
        $globalClasses = GlobalClass::where('is_active', true)->get();
        $categories = JobCategory::where('is_active', true)->get();

        return view('superadmin.vacancies.edit', compact('vacancy', 'schools', 'departments', 'globalClasses', 'categories'));
    }

    public function update(StoreVacancyRequest $request, Vacancy $vacancy)
    {
        $vacancy = Vacancy::withoutGlobalScopes()->findOrFail($vacancy->id);
        $validated = $request->validated();

        $dto = VacancyData::fromArray($validated);
        $this->vacancyService->updateVacancy($vacancy, $dto);

        return redirect()->route('superadmin.vacancies.index')->with('success', 'Vacancy updated successfully.');
    }

    public function toggleStatus(Vacancy $vacancy)
    {
        $vacancy = Vacancy::withoutGlobalScopes()->findOrFail($vacancy->id);
        
        $newStatus = (strtolower($vacancy->status) === 'published') ? 'draft' : 'published';
        $vacancy->status = $newStatus;
        $vacancy->save();

        $message = ($newStatus === 'published')
            ? 'Vacancy enabled and published successfully.'
            : 'Vacancy disabled (moved to Draft).';

        return redirect()->back()->with('success', $message);
    }

    public function destroy(Vacancy $vacancy)
    {
        $vacancy = Vacancy::withoutGlobalScopes()->findOrFail($vacancy->id);
        $this->vacancyService->deleteVacancy($vacancy);

        return redirect()->route('superadmin.vacancies.index')->with('success', 'Vacancy deleted successfully.');
    }
}
