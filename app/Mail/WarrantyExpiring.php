<?php // app/Mail/WarrantyExpiring.php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class WarrantyExpiring extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Collection $equipment
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Alerta: Garantías Próximas a Vencer',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.warranty-expiring',
        );
    }
}
