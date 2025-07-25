<?php // app/Mail/AssignmentCreated.php
namespace App\Mail;

use App\Models\Assignment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AssignmentCreated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Assignment $assignment
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nueva Asignación de Equipo - ' . $this->assignment->equipment->serial_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.assignment-created',
        );
    }

    public function attachments(): array
    {
        $attachments = [];
        
        if ($this->assignment->assignment_document) {
            $attachments[] = storage_path('app/private/assignments/' . $this->assignment->assignment_document);
        }
        
        return $attachments;
    }
}
