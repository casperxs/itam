<?php // app/Notifications/MaintenanceDueNotification.php
namespace App\Notifications;

use App\Models\MaintenanceRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MaintenanceDueNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected MaintenanceRecord $maintenance
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Mantenimiento Programado')
            ->greeting('Recordatorio de Mantenimiento')
            ->line('Tiene un mantenimiento programado:')
            ->line('**Equipo:** ' . $this->maintenance->equipment->serial_number)
            ->line('**Tipo:** ' . ucfirst($this->maintenance->type))
            ->line('**Fecha Programada:** ' . $this->maintenance->scheduled_date->format('d/m/Y H:i'))
            ->line('**Descripción:** ' . $this->maintenance->description)
            ->action('Ver Mantenimiento', url('/maintenance/' . $this->maintenance->id))
            ->line('Por favor, realice el mantenimiento en la fecha programada.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'maintenance_id' => $this->maintenance->id,
            'equipment_serial' => $this->maintenance->equipment->serial_number,
            'type' => $this->maintenance->type,
            'scheduled_date' => $this->maintenance->scheduled_date,
            'description' => $this->maintenance->description,
        ];
    }
}
