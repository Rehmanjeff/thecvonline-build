<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailTemplateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailTemplate;

    public function __construct(EmailTemplate $emailTemplate)
    {
        $this->emailTemplate = $emailTemplate;
    }

    public function envelope()
    {
        return new Envelope(
            subject: $this->emailTemplate->subject,
        );
    }

    public function content()
    {
        return new Content(
            markdown: 'emails.user-email',
        );
    }

    public function attachments()
    {
        return Storage::disk('public')->path($this->emailTemplate->attachment);
    }
}
