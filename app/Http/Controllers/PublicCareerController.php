<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\GlobalClass;
use App\Models\JobCategory;
use App\Models\Department;
use App\Repositories\Contracts\VacancyRepositoryInterface;
use App\Services\CmsService;
use Illuminate\Http\Request;

class PublicCareerController extends Controller
{
    public function __construct(
        protected VacancyRepositoryInterface $vacancyRepository,
        protected CmsService $cmsService
    ) {}

    public function home()
    {
        $cms = $this->cmsService->getHomepageContent();
        $featuredVacancies = $this->vacancyRepository->getFeatured();
        $latestVacancies = $this->vacancyRepository->getLatest(6);
        $schools = School::where('status', 'active')->withCount(['vacancies' => function($q) {
            $q->withoutGlobalScopes()->where('status', 'published');
        }])->get();
        $categories = JobCategory::where('is_active', true)->withCount(['vacancies' => function($q) {
            $q->withoutGlobalScopes()->where('status', 'published');
        }])->get();

        return view('public.home', compact('cms', 'featuredVacancies', 'latestVacancies', 'schools', 'categories'));
    }

    public function index(Request $request)
    {
        $filters = $request->only(['keyword', 'school_id', 'department_id', 'global_class_id', 'category_id', 'employment_type', 'location', 'min_salary']);
        $vacancies = $this->vacancyRepository->searchPublic($filters, 12);
        
        $schools = School::where('status', 'active')->get();
        $categories = JobCategory::where('is_active', true)->get();
        $classes = GlobalClass::where('is_active', true)->get();
        $departments = Department::where('is_active', true)->get();

        return view('public.vacancies.index', compact('vacancies', 'schools', 'categories', 'classes', 'departments', 'filters'));
    }

    public function show(string $slug)
    {
        $vacancy = $this->vacancyRepository->findBySlug($slug);

        if (!$vacancy || $vacancy->status !== 'published') {
            abort(404, 'Vacancy not found or no longer available.');
        }

        // Increment view count
        $vacancy->increment('view_count');

        $relatedVacancies = \App\Models\Vacancy::withoutGlobalScopes()
            ->where('status', 'published')
            ->where('id', '!=', $vacancy->id)
            ->latest()
            ->take(4)
            ->get();

        // JSON-LD for Google Jobs
        $jsonLd = [
            '@context' => 'https://schema.org/',
            '@type' => 'JobPosting',
            'title' => $vacancy->title,
            'description' => strip_tags($vacancy->description),
            'identifier' => [
                '@type' => 'PropertyValue',
                'name' => $vacancy->school->name,
                'value' => (string) $vacancy->id,
            ],
            'datePosted' => $vacancy->publish_date ? $vacancy->publish_date->toIso8601String() : $vacancy->created_at->toIso8601String(),
            'validThrough' => $vacancy->deadline ? $vacancy->deadline->toIso8601String() : null,
            'employmentType' => strtoupper($vacancy->employment_type),
            'hiringOrganization' => [
                '@type' => 'Organization',
                'name' => $vacancy->school->name,
                'sameAs' => $vacancy->school->website,
                'logo' => $vacancy->school->logo_url,
            ],
            'jobLocation' => [
                '@type' => 'Place',
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => $vacancy->location ?: $vacancy->school->address,
                    'addressLocality' => $vacancy->school->city,
                    'addressRegion' => $vacancy->school->state,
                    'addressCountry' => $vacancy->school->country,
                ]
            ]
        ];

        return view('public.vacancies.show', compact('vacancy', 'relatedVacancies', 'jsonLd'));
    }

    public function faq()
    {
        return view('public.faq');
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'required|string|max:255',
            'reference_no' => 'nullable|string|max:100',
            'message' => 'required|string|max:5000',
        ]);

        $contactMsg = \App\Models\ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'],
            'reference_no' => $validated['reference_no'] ?? null,
            'message' => $validated['message'],
            'status' => 'unread',
            'ip_address' => $request->ip(),
        ]);

        \App\Models\ActivityLog::create([
            'school_id'    => null,
            'user_id'      => null,
            'log_name'     => 'contact',
            'description'  => "Public inquiry submitted by {$validated['name']} ({$validated['email']}): {$validated['subject']}",
            'subject_type' => get_class($contactMsg),
            'subject_id'   => $contactMsg->id,
            'properties'   => [
                'name'         => $validated['name'],
                'email'        => $validated['email'],
                'reference_no' => $validated['reference_no'] ?? null,
            ],
            'ip_address'   => $request->ip(),
            'user_agent'   => $request->userAgent(),
        ]);

        return redirect()->route('contact')->with('success', 'Thank you for your message! Our institutional candidate support team will review your inquiry and get back to you shortly.');
    }

    public function terms()
    {
        return view('public.terms');
    }

    public function privacy()
    {
        return view('public.privacy');
    }
}
