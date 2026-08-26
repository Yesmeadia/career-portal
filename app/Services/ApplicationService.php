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
            $msg = $dto->vacancy_id
                ? 'An application with this email or phone number has already been submitted for this vacancy.'
                : 'A general application with this email or phone number has already been submitted recently.';
            throw ValidationException::withMessages([
                'email' => $msg,
            ]);
        }

        $application = $this->applicationRepository->create($dto->toArray());

        $typeLabel = $application->isGeneral() ? ' [General Application]' : '';
        ActivityLog::record("Application submitted by {$application->full_name} ({$application->reference_no}){$typeLabel}", $application, 'applications');

        event(new ApplicationSubmitted($application));

        return $application;
    }

    public function updateStatus(Application $application, string $newStatus, ?string $notes = null): bool
    {
        $oldStatus = $application->status;
        $updated = $this->applicationRepository->updateStatus($application, $newStatus, $notes);

        if ($updated) {
            // Auto-sync interview round statuses when application stage changes
            if (in_array($newStatus, ['interview_completed', 'selected', 'hired'])) {
                $application->interviews()->where('status', 'scheduled')->update(['status' => 'completed']);
            } elseif ($newStatus === 'rejected') {
                $application->interviews()->where('status', 'scheduled')->update(['status' => 'cancelled']);
            }

            ActivityLog::record("Application {$application->reference_no} status changed from {$oldStatus} to {$newStatus}", $application, 'applications');

            event(new ApplicationStatusUpdated($application, $oldStatus, $newStatus));
        }

        return $updated;
    }

    public function transferOrRecommend(Application $application, int $targetSchoolId, ?int $targetVacancyId = null, ?string $recommendationNotes = null): bool
    {
        $sourceSchoolName = $application->school?->name ?? 'Original Institution';

        $updated = $this->applicationRepository->transferOrRecommend(
            $application,
            $targetSchoolId,
            $targetVacancyId,
            $recommendationNotes
        );

        if ($updated) {
            $application->refresh();
            $targetSchoolName = $application->school?->name ?? "School #{$targetSchoolId}";
            $noteSnippet = $recommendationNotes ? " with note: \"{$recommendationNotes}\"" : '';

            ActivityLog::record(
                "Super Admin transferred & recommended candidate {$application->full_name} ({$application->reference_no}) from {$sourceSchoolName} to {$targetSchoolName}{$noteSnippet}",
                $application,
                'applications'
            );
        }

        return $updated;
    }
}
