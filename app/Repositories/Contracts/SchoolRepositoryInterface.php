<?php

namespace App\Repositories\Contracts;

use App\Models\School;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface SchoolRepositoryInterface
{
    public function getAllActive(): Collection;
    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator;
    public function findById(int $id): ?School;
    public function findBySlug(string $slug): ?School;
    public function create(array $data): School;
    public function update(School $school, array $data): bool;
    public function delete(School $school): bool;
}
