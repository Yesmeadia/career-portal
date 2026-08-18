<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScheduleInterviewRequest;
use App\Models\Application;
use App\Models\Interview;
use App\Repositories\Contracts\InterviewRepositoryInterface;
use App\Services\InterviewService;
use Illuminate\Http\Request;

class InterviewController extends Controller
{
    public function __construct(
        protected InterviewRepositoryInterface $interviewRepository,
        protected InterviewService $interviewService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'date']);
        $filters['school_id'] = auth()->user()->school_id;

        $interviews = $this->interviewRepository->paginateAdmin($filters, 15);

        return view('schooladmin.interviews.index', compact('interviews', 'filters'));
    }

    public function calendar()
    {
        $interviews = Interview::where('school_id', auth()->user()->school_id)->with(['application.vacancy'])->get();
        return view('schooladmin.interviews.calendar', compact('interviews'));
    }

    public function store(ScheduleInterviewRequest $request)
    {
        $application = Application::findOrFail($request->application_id);
        $this->authorize('view', $application);

        $this->interviewService->scheduleInterview($application, $request->validated());

        return redirect()->back()->with('success', 'Interview scheduled and email notification sent to candidate.');
    }

    public function update(Request $request, Interview $interview)
    {
        $this->authorize('update', $interview);

        $validated = $request->validate([
            'status' => 'required|in:scheduled,completed,cancelled,rescheduled',
            'feedback' => 'nullable|string|max:2000',
            'score' => 'nullable|integer|min:1|max:100',
        ]);

        $this->interviewRepository->update($interview, $validated);

        return redirect()->back()->with('success', 'Interview feedback updated successfully.');
    }
}
