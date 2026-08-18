<?php

namespace App\Listeners;

use App\Events\ApplicationStatusUpdated;
use App\Mail\StatusUpdatedMail;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendApplicationStatusUpdatedListener
{
    /**
     * Prevent duplicate execution within the same request lifecycle.
     */
    protected static array $handledUpdates = [];

    public function handle(ApplicationStatusUpdated $event): void
    {
        $application = $event->application;
        $key = $application->id . '-' . $event->newStatus;

        if (!empty(self::$handledUpdates[$key])) {
            Log::info("Skipping duplicate SendApplicationStatusUpdatedListener execution for Application ID {$application->id}");
            return;
        }
        self::$handledUpdates[$key] = true;

        try {
            if ($application->email) {
                $interview = $application->interviews()->latest()->first();
                Mail::to($application->email)->send(
                    new StatusUpdatedMail($application, $event->oldStatus, $event->newStatus, $application->admin_notes, $interview)
                );

                ActivityLog::create([
                    'school_id'    => $application->school_id,
                    'user_id'      => auth()->id(),
                    'log_name'     => 'email',
                    'description'  => "Automated email: Status update notification ('" . ucfirst(str_replace('_', ' ', $event->oldStatus)) . "' → '" . ucfirst(str_replace('_', ' ', $event->newStatus)) . "') sent to candidate '{$application->first_name} {$application->last_name}' ({$application->email}) for Ref #{$application->reference_no}.",
                    'subject_type' => get_class($application),
                    'subject_id'   => $application->id,
                    'causer_type'  => auth()->check() ? get_class(auth()->user()) : null,
                    'causer_id'    => auth()->id(),
                    'properties'   => [
                        'type'         => 'status_update_notification',
                        'old_status'   => $event->oldStatus,
                        'new_status'   => $event->newStatus,
                        'email'        => $application->email,
                        'reference_no' => $application->reference_no,
                    ],
                    'ip_address'   => request()->ip(),
                    'user_agent'   => request()->userAgent(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error("Failed to send status update email to candidate {$application->email} for Ref {$application->reference_no}: " . $e->getMessage());
        }
    }
}
