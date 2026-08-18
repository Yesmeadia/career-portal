<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\JobCategory;
use App\Models\School;
use App\Models\Vacancy;
use App\Models\Interview;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['school_id', 'status', 'category_id', 'date_from', 'date_to']);

        $applicationsQuery = Application::withoutGlobalScopes()->with(['school', 'vacancy.category']);
        $vacanciesQuery = Vacancy::withoutGlobalScopes();

        // Apply Filters
        if (!empty($filters['school_id'])) {
            $applicationsQuery->where('school_id', $filters['school_id']);
            $vacanciesQuery->where('school_id', $filters['school_id']);
        }

        if (!empty($filters['status'])) {
            $applicationsQuery->where('status', $filters['status']);
        }

        if (!empty($filters['category_id'])) {
            $applicationsQuery->whereHas('vacancy', function ($q) use ($filters) {
                $q->where('job_category_id', $filters['category_id']);
            });
            $vacanciesQuery->where('job_category_id', $filters['category_id']);
        }

        if (!empty($filters['date_from'])) {
            $applicationsQuery->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $applicationsQuery->whereDate('created_at', '<=', $filters['date_to']);
        }

        // Summary Statistics
        $stats = [
            'total_applications' => (clone $applicationsQuery)->count(),
            'today_applications' => (clone $applicationsQuery)->whereDate('created_at', now()->toDateString())->count(),
            'total_vacancies' => (clone $vacanciesQuery)->count(),
            'open_vacancies' => (clone $vacanciesQuery)->where('status', 'published')->count(),
            'total_schools' => School::count(),
            'hired_candidates' => (clone $applicationsQuery)->whereIn('status', ['hired', 'selected'])->count(),
            'shortlisted' => (clone $applicationsQuery)->where('status', 'shortlisted')->count(),
            'interviews_scheduled' => (clone $applicationsQuery)->where('status', 'interview_scheduled')->count(),
            'under_review' => (clone $applicationsQuery)->whereIn('status', ['submitted', 'under_review'])->count(),
            'rejected' => (clone $applicationsQuery)->where('status', 'rejected')->count(),
        ];

        // Status Breakdown for Chart
        $statusCounts = [
            'Submitted' => (clone $applicationsQuery)->where('status', 'submitted')->count(),
            'Under Review' => (clone $applicationsQuery)->where('status', 'under_review')->count(),
            'Shortlisted' => (clone $applicationsQuery)->where('status', 'shortlisted')->count(),
            'Interview' => (clone $applicationsQuery)->where('status', 'interview_scheduled')->count(),
            'Selected / Hired' => (clone $applicationsQuery)->whereIn('status', ['selected', 'hired'])->count(),
            'Rejected' => (clone $applicationsQuery)->where('status', 'rejected')->count(),
        ];

        // Continuous 6-Month Rolling Trend Data (Multi-stage breakdown)
        $monthsList = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthsList->push([
                'year' => $date->year,
                'month' => $date->month,
                'month_name' => $date->format('M Y'),
                'short_month' => $date->format('M'),
            ]);
        }

        $appData = Application::withoutGlobalScopes()
            ->when(!empty($filters['school_id']), fn($q) => $q->where('school_id', $filters['school_id']))
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as total_count, SUM(CASE WHEN status IN ("shortlisted", "interview_scheduled", "interview_completed", "selected", "hired") THEN 1 ELSE 0 END) as shortlisted_count, SUM(CASE WHEN status IN ("selected", "hired") THEN 1 ELSE 0 END) as hired_count')
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->get()
            ->keyBy(fn($item) => $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT));

        $monthlyTrend = $monthsList->map(function ($m) use ($appData) {
            $key = $m['year'] . '-' . str_pad($m['month'], 2, '0', STR_PAD_LEFT);
            $record = $appData->get($key);
            return (object) [
                'month_name' => $m['month_name'],
                'short_month' => $m['short_month'],
                'total_count' => (int) ($record?->total_count ?? 0),
                'shortlisted_count' => (int) ($record?->shortlisted_count ?? 0),
                'hired_count' => (int) ($record?->hired_count ?? 0),
            ];
        });

        // Campus Breakdown Table
        $campusReports = School::withCount([
            'vacancies',
            'vacancies as open_vacancies_count' => function ($q) {
                $q->where('status', 'published');
            },
            'applications',
            'applications as hired_count' => function ($q) {
                $q->whereIn('status', ['selected', 'hired']);
            },
            'applications as shortlisted_count' => function ($q) {
                $q->where('status', 'shortlisted');
            },
            'interviews',
        ])->orderBy('name')->get();

        // Job Categories Breakdown
        $categoryReports = JobCategory::withCount([
            'vacancies',
        ])->get()->map(function ($cat) {
            $cat->applications_count = Application::withoutGlobalScopes()
                ->whereHas('vacancy', fn($q) => $q->where('job_category_id', $cat->id))
                ->count();
            return $cat;
        });

        $schools = School::orderBy('name')->get();
        $categories = JobCategory::orderBy('name')->get();

        return view('superadmin.reports.index', compact(
            'stats',
            'statusCounts',
            'monthlyTrend',
            'campusReports',
            'categoryReports',
            'schools',
            'categories',
            'filters'
        ));
    }

    public function exportApplicationsCsv(Request $request): StreamedResponse
    {
        $filters = $request->only(['school_id', 'status', 'category_id', 'date_from', 'date_to']);

        $query = Application::withoutGlobalScopes()->with(['school', 'vacancy.category', 'vacancy.department']);

        if (!empty($filters['school_id'])) {
            $query->where('school_id', $filters['school_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['category_id'])) {
            $query->whereHas('vacancy', function ($q) use ($filters) {
                $q->where('job_category_id', $filters['category_id']);
            });
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $applications = $query->latest()->get();

        $filename = 'Institutional_Recruitment_Report_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($applications) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Reference No',
                'School / Campus',
                'Job Vacancy',
                'Department',
                'Category',
                'Candidate Name',
                'Email',
                'Phone',
                'Highest Qualification',
                'Experience',
                'City',
                'State',
                'Status',
                'Applied At'
            ]);

            foreach ($applications as $app) {
                fputcsv($handle, [
                    $app->reference_no,
                    $app->school->name ?? 'N/A',
                    $app->vacancy->title ?? 'N/A',
                    $app->vacancy->department->name ?? 'N/A',
                    $app->vacancy->category->name ?? 'N/A',
                    $app->full_name,
                    $app->email,
                    $app->phone,
                    $app->highest_qualification ?? 'N/A',
                    $app->experience_years ? $app->experience_years . ' Years' : 'N/A',
                    $app->city ?? 'N/A',
                    $app->state ?? 'N/A',
                    strtoupper(str_replace('_', ' ', $app->status)),
                    $app->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($handle);
        }, 200, $headers);
    }
}
