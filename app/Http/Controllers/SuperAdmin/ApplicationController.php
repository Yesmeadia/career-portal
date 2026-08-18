<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Interview;
use App\Models\School;
use App\Models\Vacancy;
use App\Models\ActivityLog;
use App\Mail\DirectMessageMail;
use App\Services\ApplicationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = Application::withoutGlobalScopes()
            ->with(['vacancy', 'school'])
            ->latest();

        // Filters
        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('reference_no', 'like', "%{$search}%");
            });
        }

        if ($request->filled('vacancy_id')) {
            $query->where('vacancy_id', $request->vacancy_id);
        }

        $applications = $query->paginate(15)->withQueryString();
        $schools      = School::where('status', 'active')->orderBy('name')->get();
        $vacancies    = Vacancy::withoutGlobalScopes()->with('school')->orderBy('title')->get();
        $filters      = $request->only(['search', 'school_id', 'vacancy_id', 'status']);

        $statusOptions = [
            'submitted'            => 'Submitted',
            'new'                  => 'New',
            'under_review'         => 'Under Review',
            'shortlisted'          => 'Shortlisted',
            'interview_scheduled'  => 'Interview Scheduled',
            'interview_completed'  => 'Interview Completed',
            'selected'             => 'Selected',
            'rejected'             => 'Rejected',
            'on_hold'              => 'On Hold',
            'hired'                => 'Hired',
        ];

        return view('superadmin.applications.index', compact(
            'applications', 'schools', 'vacancies', 'filters', 'statusOptions'
        ));
    }

    public function show(Application $application)
    {
        $application->load(['vacancy', 'school', 'interviews']);
        return view('superadmin.applications.show', compact('application'));
    }

    public function updateStatus(Request $request, Application $application)
    {
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

        $request->validate($rules);

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

        $appService = app(ApplicationService::class);
        $appService->updateStatus($application, $request->status, $request->admin_notes);

        $successMessage = $request->status === 'interview_scheduled'
            ? 'Interview scheduled successfully and details dispatched to candidate.'
            : 'Application status updated and status notification email sent to candidate.';

        return redirect()->back()->with('success', $successMessage);
    }

    public function updateNotes(Request $request, Application $application)
    {
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
                "Direct email sent by Super Admin to {$application->full_name} ({$application->email}): {$request->subject}",
                $application,
                'applications'
            );

            return redirect()->back()->with('success', 'Email sent successfully to ' . $application->email . '.');
        } catch (\Throwable $e) {
            Log::error("Direct email to candidate {$application->email} failed: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }
}
