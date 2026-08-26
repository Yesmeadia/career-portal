<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Application $application
    ) {}

    public function envelope(): Envelope
    {
        $schoolName = $this->application->school?->name ?? ($this->application->vacancy?->school?->name ?? 'Career Portal');
        $vacancyTitle = $this->application->vacancy?->title ?? ($this->application->preferred_position ?: 'General Application');
        return new Envelope(
            subject: "Application Received - {$vacancyTitle} ({$this->application->reference_no}) | {$schoolName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.application_submitted',
        );
    }
}
