<?php

namespace App\Repositories\Eloquent;

use App\Models\Vacancy;
use App\Repositories\Contracts\VacancyRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class VacancyRepository implements VacancyRepositoryInterface
{
    public function searchPublic(array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        $query = Vacancy::withoutGlobalScopes()->published()->with(['school', 'department', 'globalClass', 'category']);

        if (!empty($filters['keyword'])) {
            $keyword = $filters['keyword'];
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%")
                  ->orWhere('location', 'like', "%{$keyword}%");
            });
        }

        if (!empty($filters['school_id'])) {
            $query->where('school_id', $filters['school_id']);
        }

        if (!empty($filters['department_id'])) {
            $query->where('department_id', $filters['department_id']);
        }

        if (!empty($filters['global_class_id'])) {
            $query->where('global_class_id', $filters['global_class_id']);
        }

        if (!empty($filters['category_id'])) {
            $query->where('job_category_id', $filters['category_id']);
        }

        if (!empty($filters['employment_type'])) {
            $query->where('employment_type', $filters['employment_type']);
        }

        if (!empty($filters['location'])) {
            $query->where('location', 'like', "%{$filters['location']}%");
        }

        if (!empty($filters['min_salary'])) {
            $query->where('salary_to', '>=', (float)$filters['min_salary']);
        }

        return $query->latest('publish_date')->paginate($perPage);
    }

    public function paginateAdmin(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Vacancy::with(['school', 'department', 'category', 'globalClass'])->withCount('applications');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['school_id'])) {
            $query->where('school_id', $filters['school_id']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function findById(int $id): ?Vacancy
    {
        return Vacancy::withoutGlobalScopes()->with(['school', 'department', 'globalClass', 'category', 'applications'])->find($id);
    }

    public function findBySlug(string $slug): ?Vacancy
    {
        return Vacancy::withoutGlobalScopes()->where('slug', $slug)->with(['school', 'department', 'globalClass', 'category'])->first();
    }

    public function create(array $data): Vacancy
    {
        return Vacancy::create($data);
    }

    public function update(Vacancy $vacancy, array $data): bool
    {
        return $vacancy->update($data);
    }

    public function delete(Vacancy $vacancy): bool
    {
        return $vacancy->delete();
    }

    public function getFeatured(): Collection
    {
        return Vacancy::withoutGlobalScopes()->published()->featured()->with(['school', 'department', 'category'])->latest()->take(6)->get();
    }

    public function getLatest(int $limit = 6): Collection
    {
        return Vacancy::withoutGlobalScopes()->published()->with(['school', 'department', 'category'])->latest()->take($limit)->get();
    }
}
