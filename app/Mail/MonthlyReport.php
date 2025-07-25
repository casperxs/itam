<?php // app/Mail/MonthlyReport.php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MonthlyReport extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public array $reportData
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reporte Mensual del Sistema de Inventario TI - ' . now()->format('F Y'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.monthly-report',
        );
    }
}
