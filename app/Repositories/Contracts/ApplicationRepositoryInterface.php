<?php

namespace App\Repositories\Contracts;

use App\Models\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ApplicationRepositoryInterface
{
    public function paginateAdmin(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function findById(int $id): ?Application;
    public function findByReference(string $referenceNo): ?Application;
    public function create(array $data): Application;
    public function updateStatus(Application $application, string $status, ?string $notes = null): bool;
    public function checkDuplicate(int $vacancyId, string $email, string $phone): bool;
    public function delete(Application $application): bool;
}
