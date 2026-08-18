<?php

namespace App\Services;

use App\Models\Interview;
use App\Models\Application;
use App\Models\ActivityLog;
use App\Repositories\Contracts\InterviewRepositoryInterface;
use App\Events\InterviewScheduledEvent;

class InterviewService
{
    public function __construct(
        protected InterviewRepositoryInterface $interviewRepository
    ) {}

    public function scheduleInterview(Application $application, array $data): Interview
    {
        $interviewData = array_merge($data, [
            'application_id' => $application->id,
            'school_id' => $application->school_id,
            'status' => 'scheduled',
        ]);

        $interview = $this->interviewRepository->create($interviewData);

        // Update application status automatically to 'interview_scheduled'
        $application->update(['status' => 'interview_scheduled']);

        ActivityLog::record("Interview scheduled for application {$application->reference_no} on {$interview->scheduled_date}", $interview, 'interviews');

        event(new InterviewScheduledEvent($interview));

        return $interview;
    }
}
