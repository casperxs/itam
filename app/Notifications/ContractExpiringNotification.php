<?php // app/Notifications/ContractExpiringNotification.php
namespace App\Notifications;

use App\Models\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContractExpiringNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Contract $contract
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Contrato Próximo a Vencer')
            ->greeting('¡Atención!')
            ->line('El siguiente contrato está próximo a vencer:')
            ->line('**Número de Contrato:** ' . $this->contract->contract_number)
            ->line('**Proveedor:** ' . $this->contract->supplier->name)
            ->line('**Servicio:** ' . $this->contract->service_description)
            ->line('**Fecha de Vencimiento:** ' . $this->contract->end_date->format('d/m/Y'))
            ->line('**Días Restantes:** ' . $this->contract->end_date->diffInDays(now()))
            ->action('Ver Contrato', url('/contracts/' . $this->contract->id))
            ->line('Por favor, revise y procese la renovación correspondiente.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'contract_id' => $this->contract->id,
            'contract_number' => $this->contract->contract_number,
            'supplier_name' => $this->contract->supplier->name,
            'end_date' => $this->contract->end_date,
            'days_remaining' => $this->contract->end_date->diffInDays(now()),
        ];
    }
}
