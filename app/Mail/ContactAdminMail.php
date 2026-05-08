<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nuevo Mensaje de Contacto - ' . $this->data['empresa'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact_admin',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
