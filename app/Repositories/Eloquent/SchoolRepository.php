<?php

namespace App\Repositories\Eloquent;

use App\Models\School;
use App\Repositories\Contracts\SchoolRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class SchoolRepository implements SchoolRepositoryInterface
{
    public function getAllActive(): Collection
    {
        return School::where('status', 'active')->orderBy('name')->get();
    }

    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = School::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->withCount(['vacancies', 'applications'])->latest()->paginate($perPage);
    }

    public function findById(int $id): ?School
    {
        return School::find($id);
    }

    public function findBySlug(string $slug): ?School
    {
        return School::where('slug', $slug)->first();
    }

    public function create(array $data): School
    {
        return School::create($data);
    }

    public function update(School $school, array $data): bool
    {
        return $school->update($data);
    }

    public function delete(School $school): bool
    {
        return $school->delete();
    }
}
