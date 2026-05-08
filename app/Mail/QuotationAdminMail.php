<?php

namespace App\Mail;

use App\Models\Quotation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuotationAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $quotation;
    public $pdfContent;
    public $excelContent;
    public $company;

    public function __construct(Quotation $quotation, $pdfContent, $excelContent, $company)
    {
        $this->quotation = $quotation;
        $this->pdfContent = $pdfContent;
        $this->excelContent = $excelContent;
        $this->company = $company;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🛒 Nueva Cotización #' . str_pad($this->quotation->id, 6, '0', STR_PAD_LEFT)
                . ' de ' . $this->quotation->nombre . ' ' . $this->quotation->apellidos,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.quotation_admin',
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfContent, 'Cotizacion_' . str_pad($this->quotation->id, 6, '0', STR_PAD_LEFT) . '.pdf')
                ->withMime('application/pdf'),
            Attachment::fromData(fn () => $this->excelContent, 'Cotizacion_' . str_pad($this->quotation->id, 6, '0', STR_PAD_LEFT) . '.xlsx')
                ->withMime('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
        ];
    }
}
