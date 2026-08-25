<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\EmployeeResignation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResignationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $event,
        public EmployeeResignation $resignation,
        public string $customSubject,
        public string $content,
        public ?string $actionUrl = null,
        public ?string $messageIdHeader = null,
        public ?string $inReplyToHeader = null,
        public ?string $referencesHeader = null
    ) {}

    public function build(): self
    {
        $mail = $this->subject($this->customSubject)
            ->view('emails.email_layout', [
                'subject' => $this->customSubject,
                'content' => $this->content,
                'actionUrl' => $this->actionUrl,
                'actionText' => 'Access i2u2 Resignation Request',
            ]);

        $mail->withSymfonyMessage(function ($message) {
            if (!empty($this->messageIdHeader)) {
                $message->getHeaders()->addHeader('Message-ID', $this->messageIdHeader);
            }
            if (!empty($this->inReplyToHeader)) {
                $message->getHeaders()->addHeader('In-Reply-To', $this->inReplyToHeader);
            }
            if (!empty($this->referencesHeader)) {
                $message->getHeaders()->addHeader('References', $this->referencesHeader);
            }
        });

        return $mail;
    }
}
