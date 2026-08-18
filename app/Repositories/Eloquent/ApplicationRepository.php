<?php

namespace App\Repositories\Eloquent;

use App\Models\Application;
use App\Repositories\Contracts\ApplicationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ApplicationRepository implements ApplicationRepositoryInterface
{
    public function paginateAdmin(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Application::with(['vacancy', 'school', 'interviews']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('reference_no', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['vacancy_id'])) {
            $query->where('vacancy_id', $filters['vacancy_id']);
        }

        if (!empty($filters['school_id'])) {
            $query->where('school_id', $filters['school_id']);
        }

        if (!empty($filters['bookmarked'])) {
            $query->where('is_bookmarked', true);
        }

        return $query->latest()->paginate($perPage);
    }

    public function findById(int $id): ?Application
    {
        return Application::with(['vacancy.department', 'vacancy.school', 'school', 'interviews'])->find($id);
    }

    public function findByReference(string $referenceNo): ?Application
    {
        return Application::where('reference_no', $referenceNo)->with(['vacancy.school'])->first();
    }

    public function create(array $data): Application
    {
        return Application::create($data);
    }

    public function updateStatus(Application $application, string $status, ?string $notes = null): bool
    {
        $payload = ['status' => $status];
        if ($notes !== null) {
            $payload['admin_notes'] = $notes;
        }
        return $application->update($payload);
    }

    public function checkDuplicate(int $vacancyId, string $email, string $phone): bool
    {
        return Application::where('vacancy_id', $vacancyId)
            ->where(function ($q) use ($email, $phone) {
                $q->where('email', $email)->orWhere('phone', $phone);
            })
            ->exists();
    }

    public function delete(Application $application): bool
    {
        return $application->delete();
    }
}
