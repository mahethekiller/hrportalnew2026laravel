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
        $systemSetting = \App\Models\SystemSetting::first();
        $appName = $this->appName ?? $systemSetting?->application_name ?? config('app.name', 'I2U2 Portal');

        $envelope = new Envelope(
            subject: $this->mailSubject
        );

        if ($this->fromEmail) {
            $envelope->from = new Address($this->fromEmail, $this->fromName ?? $appName);
        }

        return $envelope;
    }

    public function content(): Content
    {
        $systemSetting = \App\Models\SystemSetting::first();
        $appName = $this->appName ?? $systemSetting?->application_name ?? config('app.name', 'I2U2 Portal');
        $footerText = $systemSetting?->footer_text ?? ('© ' . date('Y') . ' ' . $appName . '. All rights reserved.');
        $supportEmail = $systemSetting?->support_email ?? 'support@i2k2.com';

        return new Content(
            view: 'emails.email_layout',
            with: [
                'subject' => $this->mailSubject,
                'content' => $this->htmlContent,
                'actionUrl' => $this->actionUrl,
                'actionText' => $this->actionText,
                'appName' => $appName,
                'footerText' => $footerText,
                'supportEmail' => $supportEmail,
                'logoUrl' => asset('assets/images/portal_logo_120h.png'),
            ]
        );
    }
}
