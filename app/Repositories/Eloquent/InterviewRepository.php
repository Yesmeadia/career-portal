<?php

namespace App\Repositories\Eloquent;

use App\Models\Interview;
use App\Repositories\Contracts\InterviewRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class InterviewRepository implements InterviewRepositoryInterface
{
    public function paginateAdmin(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Interview::with(['application.vacancy', 'school']);

        if (!empty($filters['school_id'])) {
            $query->where('school_id', $filters['school_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['date'])) {
            $query->where('scheduled_date', $filters['date']);
        }

        return $query->orderBy('scheduled_date', 'asc')->orderBy('scheduled_time', 'asc')->paginate($perPage);
    }

    public function getUpcoming(): Collection
    {
        return Interview::with(['application.vacancy', 'school'])
            ->where('scheduled_date', '>=', now()->toDateString())
            ->where('status', 'scheduled')
            ->orderBy('scheduled_date', 'asc')
            ->take(10)
            ->get();
    }

    public function findById(int $id): ?Interview
    {
        return Interview::with(['application.vacancy', 'school'])->find($id);
    }

    public function create(array $data): Interview
    {
        return Interview::create($data);
    }

    public function update(Interview $interview, array $data): bool
    {
        return $interview->update($data);
    }
}
