<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DirectMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Application $application,
        public string $customSubject,
        public string $customMessage,
        public ?string $senderName = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->customSubject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.direct_message',
        );
    }
}
