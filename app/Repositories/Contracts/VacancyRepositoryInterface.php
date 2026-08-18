<?php

namespace App\Repositories\Contracts;

use App\Models\Vacancy;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface VacancyRepositoryInterface
{
    public function searchPublic(array $filters = [], int $perPage = 12): LengthAwarePaginator;
    public function paginateAdmin(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function findById(int $id): ?Vacancy;
    public function findBySlug(string $slug): ?Vacancy;
    public function create(array $data): Vacancy;
    public function update(Vacancy $vacancy, array $data): bool;
    public function delete(Vacancy $vacancy): bool;
    public function getFeatured(): Collection;
    public function getLatest(int $limit = 6): Collection;
}
