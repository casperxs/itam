<?php // app/Mail/ContractExpiring.php
namespace App\Mail;

use App\Models\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContractExpiring extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Contract $contract
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Alerta: Contrato Próximo a Vencer - ' . $this->contract->contract_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contract-expiring',
        );
    }
}
