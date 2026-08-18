<?php

namespace App\Listeners;

use App\Events\ApplicationSubmitted;
use App\Mail\ApplicationSubmittedMail;
use App\Mail\CampusNotificationMail;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendApplicationSubmittedListener
{
    /**
     * Prevent duplicate execution within the same request lifecycle.
     */
    protected static array $handledApplications = [];

    public function handle(ApplicationSubmitted $event): void
    {
        $application = $event->application;

        if (!empty(self::$handledApplications[$application->id])) {
            Log::info("Skipping duplicate SendApplicationSubmittedListener execution for Application ID {$application->id}");
            return;
        }
        self::$handledApplications[$application->id] = true;

        // 1. Send confirmation email to candidate
        try {
            if ($application->email) {
                Mail::to($application->email)->send(new ApplicationSubmittedMail($application));

                ActivityLog::create([
                    'school_id'    => $application->school_id,
                    'user_id'      => null,
                    'log_name'     => 'email',
                    'description'  => "Automated email: Application submission confirmation sent to candidate '{$application->first_name} {$application->last_name}' ({$application->email}) for Ref #{$application->reference_no}.",
                    'subject_type' => get_class($application),
                    'subject_id'   => $application->id,
                    'properties'   => [
                        'type'         => 'candidate_submission_confirmation',
                        'email'        => $application->email,
                        'reference_no' => $application->reference_no,
                        'vacancy'      => $application->vacancy?->title,
                    ],
                    'ip_address'   => request()->ip(),
                    'user_agent'   => request()->userAgent(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error("Failed to send candidate application confirmation email for Ref {$application->reference_no}: " . $e->getMessage());
        }

        // 2. Send notification email to campus / school admin (only if campus email is distinct from candidate email)
        try {
            $campusEmail = $application->school->email ?? config('mail.from.address');
            if ($campusEmail && strtolower(trim($campusEmail)) !== strtolower(trim($application->email))) {
                Mail::to($campusEmail)->send(new CampusNotificationMail($application));

                ActivityLog::create([
                    'school_id'    => $application->school_id,
                    'user_id'      => null,
                    'log_name'     => 'email',
                    'description'  => "Automated email: New candidate application alert dispatched to campus admin ({$campusEmail}) for Ref #{$application->reference_no}.",
                    'subject_type' => get_class($application),
                    'subject_id'   => $application->id,
                    'properties'   => [
                        'type'         => 'campus_admin_notification',
                        'campus_email' => $campusEmail,
                        'reference_no' => $application->reference_no,
                    ],
                    'ip_address'   => request()->ip(),
                    'user_agent'   => request()->userAgent(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error("Failed to send campus notification email for Ref {$application->reference_no}: " . $e->getMessage());
        }
    }
}
