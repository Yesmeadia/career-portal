<?php

namespace App\Services;

use App\Models\School;
use App\Models\Vacancy;
use App\Models\Application;
use App\Models\Interview;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReportingService
{
    public function getSuperAdminDashboardStats(): array
    {
        return [
            'total_schools' => School::count(),
            'active_schools' => School::where('status', 'active')->count(),
            'school_admins' => User::role('School Admin')->count(),
            'total_vacancies' => Vacancy::withoutGlobalScopes()->count(),
            'open_vacancies' => Vacancy::withoutGlobalScopes()->where('status', 'published')->count(),
            'total_applications' => Application::withoutGlobalScopes()->count(),
            'interviews_conducted' => Interview::withoutGlobalScopes()->where('status', 'completed')->count(),
            'monthly_applications' => Application::withoutGlobalScopes()
                ->selectRaw('MONTHNAME(created_at) as month, MONTH(created_at) as month_num, COUNT(*) as count')
                ->groupByRaw('MONTH(created_at), MONTHNAME(created_at)')
                ->orderByRaw('MONTH(created_at) asc')
                ->limit(6)
                ->pluck('count', 'month')
                ->toArray(),
        ];
    }

    public function getSchoolAdminDashboardStats(?int $schoolId = null): array
    {
        $schoolId = $schoolId ?? auth()->user()?->school_id;

        $vacanciesQuery = Vacancy::query();
        $applicationsQuery = Application::query();
        $interviewsQuery = Interview::query();

        if ($schoolId) {
            $vacanciesQuery->where('school_id', $schoolId);
            $applicationsQuery->where('school_id', $schoolId);
            $interviewsQuery->where('school_id', $schoolId);
        }

        return [
            'open_jobs' => (clone $vacanciesQuery)->where('status', 'published')->count(),
            'closed_jobs' => (clone $vacanciesQuery)->where('status', 'closed')->count(),
            'total_applications' => (clone $applicationsQuery)->count(),
            'today_applications' => (clone $applicationsQuery)->whereDate('created_at', now()->toDateString())->count(),
            'upcoming_interviews' => (clone $interviewsQuery)->where('status', 'scheduled')->where('scheduled_date', '>=', now()->toDateString())->count(),
            'selected_candidates' => (clone $applicationsQuery)->whereIn('status', ['selected', 'hired'])->count(),
            'rejected_candidates' => (clone $applicationsQuery)->where('status', 'rejected')->count(),
        ];
    }
}
