<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Models\ActivityLog;
use App\Services\CmsService;
use App\Repositories\Contracts\SchoolRepositoryInterface;
use App\Repositories\Eloquent\SchoolRepository;
use App\Repositories\Contracts\VacancyRepositoryInterface;
use App\Repositories\Eloquent\VacancyRepository;
use App\Repositories\Contracts\ApplicationRepositoryInterface;
use App\Repositories\Eloquent\ApplicationRepository;
use App\Repositories\Contracts\InterviewRepositoryInterface;
use App\Repositories\Eloquent\InterviewRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SchoolRepositoryInterface::class, SchoolRepository::class);
        $this->app->bind(VacancyRepositoryInterface::class, VacancyRepository::class);
        $this->app->bind(ApplicationRepositoryInterface::class, ApplicationRepository::class);
        $this->app->bind(InterviewRepositoryInterface::class, InterviewRepository::class);

        // Customize public_path if public_html exists as a sibling directory (e.g. Hostinger shared hosting)
        if (is_dir(base_path('../public_html'))) {
            $this->app->usePublicPath(base_path('../public_html'));
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(CmsService $cmsService): void
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('cms_settings')) {
                \Illuminate\Support\Facades\DB::statement('ALTER TABLE cms_settings MODIFY value LONGTEXT NULL');
            }
        } catch (\Throwable $e) {
            // Ignore if DB not migrated yet or already longtext
        }

        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        View::composer('*', function ($view) use ($cmsService) {
            $view->with('siteSettings', $cmsService->getSiteSettings());
        });

        // ── Authentication Activity Logging (Login & Logout) ──────────
        Event::listen(Login::class, function (Login $event) {
            if ($event->user) {
                $user = $event->user;
                $role = $user->roles->first()?->name ?? ($user->school_id ? 'School Admin' : 'Super Admin');
                ActivityLog::create([
                    'school_id'    => $user->school_id,
                    'user_id'      => $user->id,
                    'log_name'     => 'auth',
                    'description'  => "User '{$user->name}' ({$user->email}) [{$role}] logged in successfully.",
                    'subject_type' => get_class($user),
                    'subject_id'   => $user->id,
                    'causer_type'  => get_class($user),
                    'causer_id'    => $user->id,
                    'properties'   => [
                        'action' => 'login',
                        'email'  => $user->email,
                        'role'   => $role,
                    ],
                    'ip_address'   => request()->ip(),
                    'user_agent'   => request()->userAgent(),
                ]);
            }
        });

        Event::listen(Logout::class, function (Logout $event) {
            if ($event->user) {
                $user = $event->user;
                $role = $user->roles->first()?->name ?? ($user->school_id ? 'School Admin' : 'Super Admin');
                ActivityLog::create([
                    'school_id'    => $user->school_id,
                    'user_id'      => $user->id,
                    'log_name'     => 'auth',
                    'description'  => "User '{$user->name}' ({$user->email}) [{$role}] logged out.",
                    'subject_type' => get_class($user),
                    'subject_id'   => $user->id,
                    'causer_type'  => get_class($user),
                    'causer_id'    => $user->id,
                    'properties'   => [
                        'action' => 'logout',
                        'email'  => $user->email,
                        'role'   => $role,
                    ],
                    'ip_address'   => request()->ip(),
                    'user_agent'   => request()->userAgent(),
                ]);
            }
        });
    }
}
