<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Vacancy;
use App\Services\ReportingService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function __construct(
        protected ReportingService $reportingService
    ) {}

    public function index()
    {
        $schoolId = auth()->user()->school_id;
        $stats = $this->reportingService->getSchoolAdminDashboardStats($schoolId);

        $vacanciesWithCount = Vacancy::where('school_id', $schoolId)->withCount('applications')->orderBy('applications_count', 'desc')->get();

        return view('schooladmin.reports.index', compact('stats', 'vacanciesWithCount'));
    }

    public function exportApplicationsCsv(): StreamedResponse
    {
        $schoolId = auth()->user()->school_id;
        $applications = Application::where('school_id', $schoolId)->with(['vacancy', 'school'])->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="Applications_Report_' . date('Y-m-d') . '.csv"',
        ];

        return response()->stream(function () use ($applications) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Reference No', 'Job Title', 'Candidate Name', 'Email', 'Phone', 'Qualification', 'Experience', 'Status', 'Applied Date']);

            foreach ($applications as $app) {
                fputcsv($handle, [
                    $app->reference_no,
                    $app->vacancy->title,
                    $app->full_name,
                    $app->email,
                    $app->phone,
                    $app->highest_qualification,
                    $app->experience_years,
                    strtoupper(str_replace('_', ' ', $app->status)),
                    $app->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($handle);
        }, 200, $headers);
    }
}
