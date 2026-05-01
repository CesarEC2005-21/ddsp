<?php

namespace App\Mail;

use App\Models\Quotation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuotationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $quotation;
    public $pdfContent;
    public $company;

    public function __construct(Quotation $quotation, $pdfContent, $company)
    {
        $this->quotation = $quotation;
        $this->pdfContent = $pdfContent;
        $this->company = $company;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu solicitud de cotización #' . str_pad($this->quotation->id, 6, '0', STR_PAD_LEFT) . ' - ' . $this->company['name'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.quotation',
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfContent, 'Cotizacion_' . $this->quotation->id . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
