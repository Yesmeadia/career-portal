<?php

namespace App\Services;

use App\DTOs\ApplicationData;
use App\Models\Application;
use App\Models\ActivityLog;
use App\Repositories\Contracts\ApplicationRepositoryInterface;
use App\Events\ApplicationSubmitted;
use App\Events\ApplicationStatusUpdated;
use Illuminate\Validation\ValidationException;

class ApplicationService
{
    public function __construct(
        protected ApplicationRepositoryInterface $applicationRepository
    ) {}

    public function submitApplication(ApplicationData $dto): Application
    {
        // Duplicate application check
        if ($this->applicationRepository->checkDuplicate($dto->vacancy_id, $dto->email, $dto->phone)) {
            throw ValidationException::withMessages([
                'email' => 'An application with this email or phone number has already been submitted for this vacancy.',
            ]);
        }

        $application = $this->applicationRepository->create($dto->toArray());

        ActivityLog::record("Application submitted by {$application->full_name} ({$application->reference_no})", $application, 'applications');

        event(new ApplicationSubmitted($application));

        return $application;
    }

    public function updateStatus(Application $application, string $newStatus, ?string $notes = null): bool
    {
        $oldStatus = $application->status;
        $updated = $this->applicationRepository->updateStatus($application, $newStatus, $notes);

        if ($updated) {
            ActivityLog::record("Application {$application->reference_no} status changed from {$oldStatus} to {$newStatus}", $application, 'applications');

            event(new ApplicationStatusUpdated($application, $oldStatus, $newStatus));
        }

        return $updated;
    }
}
