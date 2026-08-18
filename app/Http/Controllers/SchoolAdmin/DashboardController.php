<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Services\ReportingService;
use App\Models\Vacancy;
use App\Models\Application;
use App\Models\Interview;

class DashboardController extends Controller
{
    public function __construct(
        protected ReportingService $reportingService
    ) {}

    public function index()
    {
        $schoolId = auth()->user()->school_id;
        $stats = $this->reportingService->getSchoolAdminDashboardStats($schoolId);

        $recentApplications = Application::where('school_id', $schoolId)->with('vacancy')->latest()->take(6)->get();
        $upcomingInterviews = Interview::where('school_id', $schoolId)->with('application.vacancy')->where('status', 'scheduled')->where('scheduled_date', '>=', now()->toDateString())->orderBy('scheduled_date')->take(5)->get();
        $activeVacancies = Vacancy::where('school_id', $schoolId)->where('status', 'published')->withCount('applications')->latest()->take(5)->get();

        return view('schooladmin.dashboard', compact('stats', 'recentApplications', 'upcomingInterviews', 'activeVacancies'));
    }
}
