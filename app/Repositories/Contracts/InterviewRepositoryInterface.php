<?php

namespace App\Repositories\Contracts;

use App\Models\Interview;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface InterviewRepositoryInterface
{
    public function paginateAdmin(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function getUpcoming(): Collection;
    public function findById(int $id): ?Interview;
    public function create(array $data): Interview;
    public function update(Interview $interview, array $data): bool;
}
