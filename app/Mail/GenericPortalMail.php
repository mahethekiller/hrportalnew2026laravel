<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GenericPortalMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $mailSubject,
        public string $htmlContent,
        public ?string $actionUrl = null,
        public ?string $actionText = null,
        public ?string $fromEmail = null,
        public ?string $fromName = null,
        public ?string $appName = null
    ) {}

    public function envelope(): Envelope
    {
        $envelope = new Envelope(
            subject: $this->mailSubject
        );

        if ($this->fromEmail) {
            $envelope->from = new Address($this->fromEmail, $this->fromName ?? config('app.name', 'Antigravity HR Portal'));
        }

        return $envelope;
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.email_layout',
            with: [
                'subject' => $this->mailSubject,
                'content' => $this->htmlContent,
                'actionUrl' => $this->actionUrl,
                'actionText' => $this->actionText,
                'appName' => $this->appName ?? config('app.name', 'Antigravity HR Portal'),
            ]
        );
    }
}
