<?php

namespace App\Services;

use App\Models\MaintenanceRecord;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class IcsGeneratorService
{
    /**
     * Genera un archivo ICS para un mantenimiento programado
     */
    public function generateMaintenanceIcs(MaintenanceRecord $maintenance): string
    {
        $maintenance->load(['equipment.equipmentType', 'equipment.currentAssignment.itUser', 'performedBy']);
        
        // Datos básicos
        $equipment = $maintenance->equipment;
        $user = $equipment->currentAssignment?->itUser;
        $technician = $maintenance->performedBy;
        
        // Fecha y hora del mantenimiento
        $startDate = Carbon::parse($maintenance->scheduled_date);
        $endDate = $startDate->copy()->addHours(2); // Duración estimada de 2 horas
        
        // Generar UID único para el evento
        $uid = 'maintenance-' . $maintenance->id . '-' . time() . '@bkb.mx';
        
        // Crear contenido del archivo ICS
        $icsContent = $this->buildIcsContent([
            'uid' => $uid,
            'summary' => $this->generateSummary($maintenance),
            'description' => $this->generateDescription($maintenance),
            'location' => $this->generateLocation($user),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'created' => now(),
            'last_modified' => now(),
            'organizer_email' => 'soporteit@bkb.mx',
            'organizer_name' => 'Soporte IT - BKB',
            'attendee_email' => $user?->email ?? '',
            'attendee_name' => $user?->name ?? 'Usuario',
            'technician_email' => $technician?->email ?? '',
            'technician_name' => $technician?->name ?? 'Técnico',
        ]);
        
        // Guardar archivo
        $fileName = 'BKBMantenimiento_' . $maintenance->id . '_' . date('Y-m-d') . '.ics';
        $filePath = 'ics/' . $fileName;
        
        Storage::disk('public')->put($filePath, $icsContent);
        
        return $fileName;
    }
    
    /**
     * Construye el contenido del archivo ICS
     */
    private function buildIcsContent(array $data): string
    {
        $ics = [];
        
        // Encabezado ICS
        $ics[] = 'BEGIN:VCALENDAR';
        $ics[] = 'VERSION:2.0';
        $ics[] = 'PRODID:-//BKB//ITAM System//ES';
        $ics[] = 'METHOD:REQUEST';
        $ics[] = 'CALSCALE:GREGORIAN';
        
        // Evento principal
        $ics[] = 'BEGIN:VEVENT';
        $ics[] = 'UID:' . $data['uid'];
        $ics[] = 'DTSTAMP:' . $data['created']->utc()->format('Ymd\THis\Z');
        $ics[] = 'DTSTART:' . $data['start_date']->utc()->format('Ymd\THis\Z');
        $ics[] = 'DTEND:' . $data['end_date']->utc()->format('Ymd\THis\Z');
        $ics[] = 'CREATED:' . $data['created']->utc()->format('Ymd\THis\Z');
        $ics[] = 'LAST-MODIFIED:' . $data['last_modified']->utc()->format('Ymd\THis\Z');
        $ics[] = 'SUMMARY:' . $this->escapeIcsText($data['summary']);
        $ics[] = 'DESCRIPTION:' . $this->escapeIcsText($data['description']);
        $ics[] = 'LOCATION:' . $this->escapeIcsText($data['location']);
        
        // Organizador (Soporte IT)
        $ics[] = 'ORGANIZER;CN="' . $data['organizer_name'] . '":MAILTO:' . $data['organizer_email'];
        
        // Asistente (Usuario del equipo)
        if ($data['attendee_email']) {
            $ics[] = 'ATTENDEE;CN="' . $data['attendee_name'] . '";ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE:MAILTO:' . $data['attendee_email'];
        }
        
        // Técnico (como asistente opcional)
        if ($data['technician_email'] && $data['technician_email'] !== $data['organizer_email']) {
            $ics[] = 'ATTENDEE;CN="' . $data['technician_name'] . '";ROLE=REQ-PARTICIPANT;PARTSTAT=ACCEPTED:MAILTO:' . $data['technician_email'];
        }
        
        // Recordatorios
        $ics[] = 'BEGIN:VALARM';
        $ics[] = 'TRIGGER:-PT15M'; // 15 minutos antes
        $ics[] = 'DESCRIPTION:Recordatorio: Mantenimiento programado en 15 minutos';
        $ics[] = 'ACTION:DISPLAY';
        $ics[] = 'END:VALARM';
        
        $ics[] = 'BEGIN:VALARM';
        $ics[] = 'TRIGGER:-P1D'; // 1 día antes
        $ics[] = 'DESCRIPTION:Recordatorio: Mantenimiento programado mañana';
        $ics[] = 'ACTION:DISPLAY';
        $ics[] = 'END:VALARM';
        
        // Propiedades adicionales
        $ics[] = 'STATUS:CONFIRMED';
        $ics[] = 'TRANSP:OPAQUE';
        $ics[] = 'PRIORITY:5';
        $ics[] = 'CLASS:PUBLIC';
        $ics[] = 'CATEGORIES:MANTENIMIENTO,ITAM,BKB';
        
        $ics[] = 'END:VEVENT';
        $ics[] = 'END:VCALENDAR';
        
        return implode("\r\n", $ics);
    }
    
    /**
     * Genera el resumen del evento
     */
    private function generateSummary(MaintenanceRecord $maintenance): string
    {
        $type = match($maintenance->type) {
            'preventive' => 'Preventivo',
            'corrective' => 'Correctivo',
            'update' => 'Actualización',
            default => 'Mantenimiento'
        };
        
        $equipment = $maintenance->equipment->brand . ' ' . $maintenance->equipment->model;
        
        return "Mantenimiento {$type} - {$equipment}";
    }
    
    /**
     * Genera la descripción detallada del evento
     */
    private function generateDescription(MaintenanceRecord $maintenance): string
    {
        $equipment = $maintenance->equipment;
        $user = $equipment->currentAssignment?->itUser;
        $technician = $maintenance->performedBy;
        
        $description = [];
        $description[] = "🔧 MANTENIMIENTO PROGRAMADO - SISTEMA ITAM BKB";
        $description[] = "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━";
        $description[] = "";
        $description[] = "📋 DETALLES DEL MANTENIMIENTO:";
        $description[] = "• ID: " . $maintenance->id;
        $description[] = "• Tipo: " . match($maintenance->type) {
            'preventive' => 'Mantenimiento Preventivo',
            'corrective' => 'Mantenimiento Correctivo', 
            'update' => 'Actualización',
            default => 'Mantenimiento'
        };
        $description[] = "• Descripción: " . ($maintenance->description ?? 'No especificada');
        $description[] = "";
        $description[] = "💻 INFORMACIÓN DEL EQUIPO:";
        $description[] = "• Tipo: " . ($equipment->equipmentType->name ?? 'N/A');
        $description[] = "• Marca/Modelo: " . ($equipment->brand ?? 'N/A') . ' ' . ($equipment->model ?? 'N/A');
        $description[] = "• Número de Serie: " . ($equipment->serial_number ?? 'N/A');
        if ($equipment->asset_tag) {
            $description[] = "• Tag: " . $equipment->asset_tag;
        }
        $description[] = "";
        $description[] = "👤 USUARIO RESPONSABLE:";
        $description[] = "• Nombre: " . ($user->name ?? 'No asignado');
        if ($user) {
            $description[] = "• Email: " . ($user->email ?? 'N/A');
            $description[] = "• Departamento: " . ($user->department ?? 'N/A');
            if ($user->employee_id) {
                $description[] = "• ID Empleado: " . $user->employee_id;
            }
        }
        $description[] = "";
        $description[] = "🔧 TÉCNICO ASIGNADO:";
        $description[] = "• Nombre: " . ($technician->name ?? 'No asignado');
        if ($technician && $technician->email) {
            $description[] = "• Email: " . $technician->email;
        }
        $description[] = "";
        if ($maintenance->cost) {
            $description[] = "💰 COSTO ESTIMADO: $" . number_format($maintenance->cost, 2);
            $description[] = "";
        }
        if ($maintenance->notes) {
            $description[] = "📝 NOTAS ADICIONALES:";
            $description[] = $maintenance->notes;
            $description[] = "";
        }
        $description[] = "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━";
        $description[] = "📧 Generado automáticamente por Sistema ITAM BKB";
        $description[] = "🌐 Para más información, contacte: soporteit@bkb.mx";
        
        return implode("\\n", $description);
    }
    
    /**
     * Genera la ubicación del evento
     */
    private function generateLocation(mixed $user): string
    {
        if (!$user) {
            return 'Oficinas BKB - Ubicación por definir';
        }
        
        $location = 'Oficinas BKB';
        if ($user->department) {
            $location .= ' - Departamento: ' . $user->department;
        }
        
        return $location;
    }
    
    /**
     * Escapa texto para formato ICS
     */
    private function escapeIcsText(string $text): string
    {
        $text = str_replace(['\\', ',', ';', "\n", "\r"], ['\\\\', '\\,', '\\;', '\\n', ''], $text);
        return $text;
    }
    
    /**
     * Genera el contenido del email
     */
    public function generateEmailContent(MaintenanceRecord $maintenance, string $icsFileName): array
    {
        $maintenance->load(['equipment.equipmentType', 'equipment.currentAssignment.itUser', 'performedBy']);
        
        $user = $maintenance->equipment->currentAssignment?->itUser;
        $equipment = $maintenance->equipment;
        $technician = $maintenance->performedBy;
        
        $subject = $this->generateSummary($maintenance) . ' - ' . Carbon::parse($maintenance->scheduled_date)->format('d/m/Y H:i');
        
        $body = [];
        $body[] = "Estimado/a " . ($user->name ?? 'Usuario') . ",";
        $body[] = "";
        $body[] = "Se ha programado un mantenimiento para su equipo de cómputo:";
        $body[] = "";
        $body[] = "📋 DETALLES DEL MANTENIMIENTO:";
        $body[] = "• Fecha y Hora: " . Carbon::parse($maintenance->scheduled_date)->format('d/m/Y H:i');
        $body[] = "• Tipo: " . match($maintenance->type) {
            'preventive' => 'Mantenimiento Preventivo',
            'corrective' => 'Mantenimiento Correctivo',
            'update' => 'Actualización',
            default => 'Mantenimiento'
        };
        $body[] = "• Técnico asignado: " . ($technician->name ?? 'Por asignar');
        $body[] = "";
        $body[] = "💻 EQUIPO:";
        $body[] = "• " . ($equipment->equipmentType->name ?? 'N/A') . " - " . ($equipment->brand ?? 'N/A') . ' ' . ($equipment->model ?? 'N/A');
        $body[] = "• Serie: " . ($equipment->serial_number ?? 'N/A');
        if ($equipment->asset_tag) {
            $body[] = "• Tag: " . $equipment->asset_tag;
        }
        $body[] = "";
        if ($maintenance->description) {
            $body[] = "🔧 TRABAJO A REALIZAR:";
            $body[] = $maintenance->description;
            $body[] = "";
        }
        if ($maintenance->notes) {
            $body[] = "📝 NOTAS IMPORTANTES:";
            $body[] = $maintenance->notes;
            $body[] = "";
        }
        $body[] = "Se adjunta un archivo de calendario (.ics) que puede agregar a su calendario personal.";
        $body[] = "";
        $body[] = "Por favor, asegúrese de tener su equipo disponible en la fecha y hora programada.";
        $body[] = "";
        $body[] = "Saludos cordiales,";
        $body[] = "Departamento de Soporte IT - BKB";
        $body[] = "📧 soporteit@bkb.mx";
        
        return [
            'subject' => $subject,
            'body' => implode('%0A', $body), // URL encode para mailto
            'to' => $user?->email ?? '',
            'cc' => $technician?->email ?? '',
        ];
    }
}
