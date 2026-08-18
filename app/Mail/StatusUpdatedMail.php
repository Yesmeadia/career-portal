<?php

namespace App\Mail;

use App\Models\Application;
use App\Models\Interview;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public ?Interview $interview = null;

    public function __construct(
        public Application $application,
        public string $oldStatus,
        public string $newStatus,
        public ?string $remarks = null,
        ?Interview $interview = null
    ) {
        $this->interview = $interview ?? ($this->newStatus === 'interview_scheduled' ? $this->application->interviews()->latest()->first() : null);
    }

    public function envelope(): Envelope
    {
        $schoolName = $this->application->school->name ?? 'Career Portal';
        if ($this->newStatus === 'interview_scheduled') {
            return new Envelope(
                subject: "Interview Invitation & Schedule Details - {$this->application->vacancy->title} ({$this->application->reference_no})",
            );
        }

        $statusFormatted = ucwords(str_replace('_', ' ', $this->newStatus));
        return new Envelope(
            subject: "Update on Your Application [{$statusFormatted}] - {$this->application->vacancy->title} ({$this->application->reference_no})",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.status_updated',
            with: [
                'interview' => $this->interview,
            ],
        );
    }
}
