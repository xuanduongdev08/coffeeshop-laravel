<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DynamicTemplateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $template;
    public $placeholders;

    /**
     * Create a new message instance.
     */
    public function __construct(EmailTemplate $template, array $placeholders = [])
    {
        $this->template = $template;
        $this->placeholders = $placeholders;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->template->renderSubject($this->placeholders),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $renderedContent = $this->template->render($this->placeholders);
        return new Content(
            view: 'emails.layout',
            with: [
                'content' => $renderedContent,
                'subject' => $this->template->renderSubject($this->placeholders)
            ]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
