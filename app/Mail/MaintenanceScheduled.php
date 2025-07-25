<?php // app/Mail/MaintenanceScheduled.php
namespace App\Mail;

use App\Models\MaintenanceRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MaintenanceScheduled extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public MaintenanceRecord $maintenance
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Mantenimiento Programado - ' . $this->maintenance->equipment->serial_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.maintenance-scheduled',
        );
    }
}
