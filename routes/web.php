<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicCareerController;
use App\Http\Controllers\PublicApplicationController;
use App\Http\Controllers\SuperAdmin as SuperAdmin;
use App\Http\Controllers\SchoolAdmin as SchoolAdmin;

// Public Website & Career Portal Routes
Route::get('/', [PublicCareerController::class, 'home'])->name('home');
Route::get('/vacancies', [PublicCareerController::class, 'index'])->name('vacancies.index');
Route::get('/vacancies/{slug}', [PublicCareerController::class, 'show'])->name('vacancies.show');

Route::get('/apply/{vacancy:slug}', [PublicApplicationController::class, 'create'])->name('applications.create');
Route::post('/apply', [PublicApplicationController::class, 'store'])->name('applications.store');
Route::get('/apply/success/{referenceNo}', [PublicApplicationController::class, 'success'])->name('applications.success');
Route::get('/track-application', [PublicApplicationController::class, 'track'])->name('applications.track');

// Application PDF Download (Restricted to Super Admin & School Admin)
Route::middleware(['auth', 'role:Super Admin|School Admin'])->group(function () {
    Route::get('/apply/pdf/{referenceNo}', [PublicApplicationController::class, 'downloadPdf'])->name('applications.download-pdf');
    Route::get('/applications/{referenceNo}/pdf', [PublicApplicationController::class, 'downloadPdf'])->name('applications.pdf');
});

Route::get('/faq', [PublicCareerController::class, 'faq'])->name('faq');
Route::get('/contact', [PublicCareerController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicCareerController::class, 'submitContact'])->name('contact.submit');
Route::get('/terms', [PublicCareerController::class, 'terms'])->name('terms');
Route::get('/privacy', [PublicCareerController::class, 'privacy'])->name('privacy');

// Dynamic Media file handler (bypasses local web server /storage 403 folder restrictions)
Route::get('/media/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath) || !is_file($fullPath)) {
        abort(404);
    }
    return response()->file($fullPath);
})->where('path', '.*')->name('media.file');

// Fallback Route for storage files
Route::get('/storage/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath) || !is_file($fullPath)) {
        abort(404);
    }
    return response()->file($fullPath);
})->where('path', '.*');

Route::get('/dashboard', function () {
    $user = auth()->user();
    if (!$user) return redirect()->route('login');
    if ($user->hasRole('Super Admin') || in_array(strtolower($user->email), ['admin@school.edu']) || $user->id === 1) {
        return redirect()->route('superadmin.dashboard');
    }
    if ($user->hasRole('School Admin') || $user->school_id) {
        return redirect()->route('schooladmin.dashboard');
    }
    return redirect()->route('home');
})->middleware(['auth'])->name('dashboard');

// SUPER ADMIN ROUTES
Route::middleware(['auth', 'role:Super Admin'])->prefix('super-admin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperAdmin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('schools', SuperAdmin\SchoolController::class);
    Route::patch('schools/{school}/toggle-status', [SuperAdmin\SchoolController::class, 'toggleStatus'])->name('schools.toggle-status');

    Route::resource('vacancies', SuperAdmin\VacancyController::class);
    Route::patch('vacancies/{vacancy}/toggle-status', [SuperAdmin\VacancyController::class, 'toggleStatus'])->name('vacancies.toggle-status');

    Route::resource('global-classes', SuperAdmin\GlobalClassController::class)->except(['create', 'edit']);
    Route::resource('job-categories', SuperAdmin\JobCategoryController::class)->except(['create', 'edit']);
    Route::resource('departments', SuperAdmin\DepartmentController::class)->except(['create', 'edit']);
    Route::patch('departments/{department}/toggle-status', [SuperAdmin\DepartmentController::class, 'toggleStatus'])->name('departments.toggle-status');

    Route::get('/reports', [SuperAdmin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-applications', [SuperAdmin\ReportController::class, 'exportApplicationsCsv'])->name('reports.export-applications');

    Route::get('/cms', [SuperAdmin\CmsController::class, 'index'])->name('cms.index');
    Route::post('/cms/homepage', [SuperAdmin\CmsController::class, 'updateHomepage'])->name('cms.update-homepage');
    Route::post('/cms/mail-config', [SuperAdmin\CmsController::class, 'updateMailConfig'])->name('cms.update-mail-config');
    Route::put('/cms/email-templates/{template}', [SuperAdmin\CmsController::class, 'updateEmailTemplate'])->name('cms.update-email-template');

    Route::get('/audit-logs', [SuperAdmin\AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('/audit', [SuperAdmin\AuditLogController::class, 'index'])->name('audit.index');

    // Applications
    Route::get('/applications', [SuperAdmin\ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [SuperAdmin\ApplicationController::class, 'show'])->name('applications.show');
    Route::patch('/applications/{application}/status', [SuperAdmin\ApplicationController::class, 'updateStatus'])->name('applications.update-status');
    Route::patch('/applications/{application}/notes', [SuperAdmin\ApplicationController::class, 'updateNotes'])->name('applications.update-notes');
    Route::post('/applications/{application}/send-email', [SuperAdmin\ApplicationController::class, 'sendEmail'])->name('applications.send-email');

    // Contact Inbox
    Route::get('/contact-messages', [SuperAdmin\ContactMessageController::class, 'index'])->name('contact-messages.index');
    Route::get('/contact-messages/{contactMessage}', [SuperAdmin\ContactMessageController::class, 'show'])->name('contact-messages.show');
    Route::get('/contact-messages/{contactMessage}/reply', [SuperAdmin\ContactMessageController::class, 'reply'])->name('contact-messages.reply');
    Route::post('/contact-messages/{contactMessage}/send-reply', [SuperAdmin\ContactMessageController::class, 'sendReply'])->name('contact-messages.send-reply');
    Route::patch('/contact-messages/{contactMessage}/status', [SuperAdmin\ContactMessageController::class, 'updateStatus'])->name('contact-messages.update-status');
    Route::delete('/contact-messages/{contactMessage}', [SuperAdmin\ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');
});

// SCHOOL ADMIN ROUTES (Tenant Aware Isolation & Protected Access)
Route::middleware(['auth', 'role:School Admin|Super Admin', \App\Http\Middleware\EnsureTenantAccess::class])
    ->prefix('school-admin')
    ->name('schooladmin.')
    ->group(function () {
        Route::get('/dashboard', [SchoolAdmin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('vacancies', SchoolAdmin\VacancyController::class);
        Route::patch('vacancies/{vacancy}/toggle-status', [SchoolAdmin\VacancyController::class, 'toggleStatus'])->name('vacancies.toggle-status');

        Route::get('/applications', [SchoolAdmin\ApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/{application}', [SchoolAdmin\ApplicationController::class, 'show'])->name('applications.show');
        Route::patch('/applications/{application}/status', [SchoolAdmin\ApplicationController::class, 'updateStatus'])->name('applications.update-status');
        Route::patch('/applications/{application}/notes', [SchoolAdmin\ApplicationController::class, 'updateNotes'])->name('applications.update-notes');
        Route::post('/applications/{application}/send-email', [SchoolAdmin\ApplicationController::class, 'sendEmail'])->name('applications.send-email');
        Route::post('/applications/{application}/bookmark', [SchoolAdmin\ApplicationController::class, 'toggleBookmark'])->name('applications.toggle-bookmark');
        Route::get('/applications/{application}/download-cv', [SchoolAdmin\ApplicationController::class, 'downloadCv'])->name('applications.download-cv');

        Route::get('/interviews', [SchoolAdmin\InterviewController::class, 'index'])->name('interviews.index');
        Route::get('/interviews/calendar', [SchoolAdmin\InterviewController::class, 'calendar'])->name('interviews.calendar');
        Route::post('/interviews', [SchoolAdmin\InterviewController::class, 'store'])->name('interviews.store');
        Route::put('/interviews/{interview}', [SchoolAdmin\InterviewController::class, 'update'])->name('interviews.update');

        Route::get('/reports', [SchoolAdmin\ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export-applications', [SchoolAdmin\ReportController::class, 'exportApplicationsCsv'])->name('reports.export-applications');
    });

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
});

require __DIR__.'/auth.php';
