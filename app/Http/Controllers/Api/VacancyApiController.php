<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\VacancyRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VacancyApiController extends Controller
{
    public function __construct(
        protected VacancyRepositoryInterface $vacancyRepository
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['keyword', 'school_id', 'department_id', 'global_class_id', 'category_id', 'employment_type', 'location', 'min_salary']);
        $vacancies = $this->vacancyRepository->searchPublic($filters, $request->get('per_page', 10));

        return response()->json([
            'status' => 'success',
            'data' => $vacancies,
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $vacancy = $this->vacancyRepository->findBySlug($slug);

        if (!$vacancy || $vacancy->status !== 'published') {
            return response()->json(['status' => 'error', 'message' => 'Vacancy not found'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $vacancy,
        ]);
    }
}
