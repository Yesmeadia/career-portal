<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\ReportingService;
use App\Models\School;
use App\Models\Vacancy;
use App\Models\Application;
use App\Models\User;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function __construct(
        protected ReportingService $reportingService
    ) {}

    public function index()
    {
        $stats = $this->reportingService->getSuperAdminDashboardStats();
        $recentSchools = School::latest()->take(5)->get();
        $recentVacancies = Vacancy::withoutGlobalScopes()->with('school')->latest()->take(5)->get();
        $recentApplications = Application::withoutGlobalScopes()->with(['school', 'vacancy'])->latest()->take(5)->get();
        $schoolAdminsCount = User::role('School Admin')->count();
        $recentLogs = ActivityLog::with('user')->latest()->take(5)->get();

        // Calculate dynamic last 7 days activity
        $weeklyActivity = [];
        $maxCount = 1;
        for ($i = 6; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subDays($i);
            $count = Application::withoutGlobalScopes()
                ->whereDate('created_at', $date->toDateString())
                ->count();
            if ($count > $maxCount) {
                $maxCount = $count;
            }
            $weeklyActivity[] = [
                'day' => $date->format('D'),
                'letter' => strtoupper(substr($date->format('D'), 0, 1)),
                'date' => $date->format('M d'),
                'count' => $count,
                'is_today' => $date->isToday(),
            ];
        }

        foreach ($weeklyActivity as &$item) {
            $item['height_pct'] = max(15, min(100, round(($item['count'] / $maxCount) * 100)));
        }
        unset($item);

        return view('superadmin.dashboard', compact(
            'stats',
            'recentSchools',
            'recentVacancies',
            'recentApplications',
            'weeklyActivity',
            'schoolAdminsCount',
            'recentLogs'
        ));
    }
}
