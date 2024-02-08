<?php

namespace Codedor\FormArchitect\Mail;

use Codedor\FilamentMailTemplates\Models\MailTemplate;
use Codedor\FormArchitect\Models\Form;
use Codedor\FormArchitect\Models\FormSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\HtmlString;

class SendFormSubmission extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public FormSubmission $formSubmission,
        public Form $form
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->form->getFromEmail()),
            subject: $this->form->email_subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'filament-mail-templates::mail.template',
            with: [
                'body' => new HtmlString(parse_link_picker_json($this->form->email_body)),
                'template' => new MailTemplate([
                    'from_email' => $this->form->getFromEmail(),
                    'from_name' => config('app.name'),
                ])
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
