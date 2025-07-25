<?php // app/Services/EmailService.php
namespace App\Services;

use App\Models\EmailTicket;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model\Message;
use Illuminate\Support\Facades\Log;

class EmailService
{
    protected $graph;

    public function __construct()
    {
        $this->graph = new Graph();
        $this->graph->setAccessToken($this->getAccessToken());
    }

    public function processIncomingEmails()
    {
        try {
            $messages = $this->graph->createRequest('GET', '/me/messages')
                ->addHeaders(['Prefer' => 'outlook.body-content-type="text"'])
                ->setReturnType(Message::class)
                ->execute();

            foreach ($messages as $message) {
                $this->processMessage($message);
            }
        } catch (\Exception $e) {
            Log::error('Error processing emails: ' . $e->getMessage());
        }
    }

    private function processMessage(Message $message)
    {
        // Verificar si ya existe el ticket
        if (EmailTicket::where('message_id', $message->getId())->exists()) {
            return;
        }

        EmailTicket::create([
            'message_id' => $message->getId(),
            'subject' => $message->getSubject(),
            'from_email' => $message->getFrom()->getEmailAddress()->getAddress(),
            'from_name' => $message->getFrom()->getEmailAddress()->getName(),
            'body' => $message->getBody()->getContent(),
            'received_at' => $message->getReceivedDateTime(),
            'status' => 'pending',
        ]);
    }

    private function getAccessToken()
    {
        // Implementar obtención de token de Office 365
        // Esto requiere configuración OAuth2 con Microsoft Graph API
        return config('services.microsoft.access_token');
    }
}