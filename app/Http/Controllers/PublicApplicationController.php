<?php

namespace App\Http\Controllers;

use App\DTOs\ApplicationData;
use App\Http\Requests\StoreApplicationRequest;
use App\Models\Vacancy;
use App\Models\Application;
use App\Services\ApplicationService;
use Illuminate\Http\Request;

class PublicApplicationController extends Controller
{
    public function __construct(
        protected ApplicationService $applicationService
    ) {}

    public function create(Vacancy $vacancy)
    {
        if ($vacancy->status !== 'published') {
            return redirect()->route('vacancies.index')->with('error', 'This vacancy is no longer accepting applications.');
        }

        return view('public.apply', compact('vacancy'));
    }

    public function store(StoreApplicationRequest $request)
    {
        $validated = $request->validated();

        // Handle Resume upload
        if ($request->hasFile('resume')) {
            $validated['resume_path'] = $request->file('resume')->store('resumes', 'public');
        }

        // Handle Photo upload
        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('photos', 'public');
        }

        $dto = ApplicationData::fromArray($validated);
        $application = $this->applicationService->submitApplication($dto);

        return redirect()->route('applications.success', $application->reference_no);
    }

    public function success(string $referenceNo)
    {
        $application = Application::where('reference_no', $referenceNo)->with(['vacancy', 'school'])->firstOrFail();
        return view('public.success', compact('application'));
    }

    public function downloadPdf(string $referenceNo)
    {
        $application = Application::withoutGlobalScopes()->where('reference_no', $referenceNo)->with(['vacancy', 'school', 'interviews'])->firstOrFail();

        $user = auth()->user();
        if (! $user || (! $user->hasRole('Super Admin') && (int) $user->school_id !== (int) $application->school_id)) {
            abort(403, 'Unauthorized access to application PDF.');
        }

        return view('public.pdf', compact('application'));
    }

    public function track(Request $request)
    {
        $application = null;
        if ($request->filled('reference_no')) {
            $application = Application::where('reference_no', trim($request->reference_no))->with(['vacancy', 'school', 'interviews'])->first();
        }
        return view('public.track', compact('application'));
    }
}
