<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Interview;
use App\Models\Vacancy;
use App\Models\ActivityLog;
use App\Mail\DirectMessageMail;
use App\Repositories\Contracts\ApplicationRepositoryInterface;
use App\Services\ApplicationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function __construct(
        protected ApplicationRepositoryInterface $applicationRepository,
        protected ApplicationService $applicationService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status', 'vacancy_id', 'bookmarked']);
        $filters['school_id'] = auth()->user()->school_id;

        $applications = $this->applicationRepository->paginateAdmin($filters, 15);
        $vacancies = Vacancy::where('school_id', auth()->user()->school_id)->get();

        return view('schooladmin.applications.index', compact('applications', 'vacancies', 'filters'));
    }

    public function show(Application $application)
    {
        $this->authorize('view', $application);
        $application->load(['vacancy', 'school', 'interviews']);
        return view('schooladmin.applications.show', compact('application'));
    }

    public function updateStatus(Request $request, Application $application)
    {
        $this->authorize('update', $application);

        $rules = [
            'status' => 'required|in:submitted,new,under_review,shortlisted,interview_scheduled,interview_completed,selected,rejected,on_hold,hired',
            'admin_notes' => 'nullable|string|max:2000',
        ];

        if ($request->status === 'interview_scheduled') {
            $rules['scheduled_date'] = 'required|date';
            $rules['scheduled_time'] = 'required';
            $rules['location_type'] = 'required|in:in_person,online';
            $rules['location_address_or_link'] = 'required|string|max:500';
            $rules['panel_members'] = 'nullable|string|max:500';
            $rules['remarks'] = 'nullable|string|max:1000';
        }

        $validated = $request->validate($rules);

        if ($request->status === 'interview_scheduled') {
            Interview::updateOrCreate(
                ['application_id' => $application->id],
                [
                    'school_id' => $application->school_id,
                    'scheduled_date' => $request->scheduled_date,
                    'scheduled_time' => $request->scheduled_time,
                    'location_type' => $request->location_type,
                    'location_address_or_link' => $request->location_address_or_link,
                    'panel_members' => $request->panel_members,
                    'remarks' => $request->remarks,
                    'status' => 'scheduled',
                ]
            );
        }

        $this->applicationService->updateStatus($application, $request->status, $request->admin_notes);

        $successMessage = $request->status === 'interview_scheduled'
            ? 'Interview scheduled successfully and full details dispatched to candidate.'
            : 'Application status updated successfully. Candidate notification email dispatched.';

        return redirect()->back()->with('success', $successMessage);
    }

    public function updateNotes(Request $request, Application $application)
    {
        $this->authorize('update', $application);

        $request->validate([
            'admin_notes' => 'nullable|string|max:5000',
        ]);

        $application->update([
            'admin_notes' => $request->admin_notes,
        ]);

        ActivityLog::record(
            "Updated interview / assessment notes for candidate {$application->full_name} (Ref: {$application->reference_no})",
            $application,
            'applications'
        );

        return redirect()->back()->with('success', 'Interview & Assessment notes saved successfully.');
    }

    public function sendEmail(Request $request, Application $application)
    {
        $this->authorize('update', $application);

        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:10000',
        ]);

        try {
            if (!$application->email) {
                return redirect()->back()->with('error', 'Candidate email address is missing.');
            }

            Mail::to($application->email)->send(
                new DirectMessageMail(
                    $application,
                    $request->subject,
                    $request->message,
                    auth()->user()->name
                )
            );

            ActivityLog::record(
                "Direct email sent to {$application->full_name} ({$application->email}): {$request->subject}",
                $application,
                'applications'
            );

            return redirect()->back()->with('success', 'Email sent successfully to ' . $application->email . '.');
        } catch (\Throwable $e) {
            Log::error("Direct email to candidate {$application->email} failed: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    public function toggleBookmark(Application $application)
    {
        $this->authorize('update', $application);
        $application->update(['is_bookmarked' => !$application->is_bookmarked]);

        return redirect()->back()->with('success', 'Bookmark status updated.');
    }

    public function downloadCv(Application $application)
    {
        $this->authorize('view', $application);

        if (!$application->resume_path || !Storage::disk('public')->exists($application->resume_path)) {
            return redirect()->back()->with('error', 'Resume file not found.');
        }

        return Storage::disk('public')->download($application->resume_path, "CV_{$application->reference_no}_" . basename($application->resume_path));
    }
}
