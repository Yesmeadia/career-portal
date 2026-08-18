<?php

namespace App\Services;

use App\DTOs\VacancyData;
use App\Models\Vacancy;
use App\Models\ActivityLog;
use App\Repositories\Contracts\VacancyRepositoryInterface;

class VacancyService
{
    public function __construct(
        protected VacancyRepositoryInterface $vacancyRepository
    ) {}

    public function createVacancy(VacancyData $dto): Vacancy
    {
        $vacancy = $this->vacancyRepository->create($dto->toArray());
        ActivityLog::record("Created vacancy {$vacancy->title}", $vacancy, 'vacancies');
        return $vacancy;
    }

    public function updateVacancy(Vacancy $vacancy, VacancyData $dto): bool
    {
        $updated = $this->vacancyRepository->update($vacancy, $dto->toArray());
        if ($updated) {
            ActivityLog::record("Updated vacancy {$vacancy->title}", $vacancy, 'vacancies');
        }
        return $updated;
    }

    public function deleteVacancy(Vacancy $vacancy): bool
    {
        ActivityLog::record("Deleted vacancy {$vacancy->title}", $vacancy, 'vacancies');
        return $this->vacancyRepository->delete($vacancy);
    }
}
