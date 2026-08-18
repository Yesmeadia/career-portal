<?php

namespace App\Mail;

use App\Models\Interview;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InterviewScheduledMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Interview $interview
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Interview Invitation - ' . $this->interview->application->vacancy->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.interview_scheduled',
        );
    }
}
