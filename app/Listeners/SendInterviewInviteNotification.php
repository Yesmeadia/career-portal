<?php

namespace App\Listeners;

use App\Events\InterviewScheduledEvent;
use App\Mail\InterviewScheduledMail;
use App\Models\ActivityLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendInterviewInviteNotification implements ShouldQueue
{
    public function handle(InterviewScheduledEvent $event): void
    {
        try {
            $interview = $event->interview;
            $application = $interview->application;

            if ($application && $application->email) {
                Mail::to($application->email)->send(new InterviewScheduledMail($interview));

                ActivityLog::create([
                    'school_id'    => $interview->school_id,
                    'user_id'      => auth()->id(),
                    'log_name'     => 'email',
                    'description'  => "Automated email: Interview scheduled invitation dispatched to candidate '{$application->first_name} {$application->last_name}' ({$application->email}) for {$interview->scheduled_date}.",
                    'subject_type' => get_class($interview),
                    'subject_id'   => $interview->id,
                    'causer_type'  => auth()->check() ? get_class(auth()->user()) : null,
                    'causer_id'    => auth()->id(),
                    'properties'   => [
                        'type'            => 'interview_invitation_email',
                        'candidate_email' => $application->email,
                        'scheduled_date'  => $interview->scheduled_date,
                        'location_type'   => $interview->location_type,
                    ],
                    'ip_address'   => request()->ip(),
                    'user_agent'   => request()->userAgent(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error("Failed to send interview invitation email: " . $e->getMessage());
        }
    }
}
