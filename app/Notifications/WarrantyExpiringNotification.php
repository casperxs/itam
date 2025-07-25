<?php // app/Notifications/WarrantyExpiringNotification.php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class WarrantyExpiringNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Collection $equipment
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Garantías Próximas a Vencer')
            ->greeting('Alerta de Garantías')
            ->line('Los siguientes equipos tienen garantías próximas a vencer:');

        foreach ($this->equipment as $item) {
            $message->line('• **' . $item->serial_number . '** - ' . $item->brand . ' ' . $item->model . ' (Vence: ' . $item->warranty_end_date->format('d/m/Y') . ')');
        }

        return $message
            ->action('Ver Equipos', url('/equipment?warranty_expiring=1'))
            ->line('Por favor, revise y gestione las renovaciones de garantía necesarias.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'equipment_count' => $this->equipment->count(),
            'equipment_list' => $this->equipment->map(function ($item) {
                return [
                    'id' => $item->id,
                    'serial_number' => $item->serial_number,
                    'brand' => $item->brand,
                    'model' => $item->model,
                    'warranty_end_date' => $item->warranty_end_date,
                ];
            })->toArray(),
        ];
    }
}