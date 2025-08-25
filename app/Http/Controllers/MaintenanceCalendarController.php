<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceRecord;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MaintenanceCalendarController extends Controller
{
    /**
     * Display the maintenance calendar view.
     */
    public function index()
    {
        return view('maintenance.calendar');
    }

    /**
     * Get maintenance events for the calendar.
     */
    public function events(Request $request)
    {
        $start = Carbon::parse($request->get('start', now()->startOfMonth()));
        $end = Carbon::parse($request->get('end', now()->endOfMonth()));

        // Get all maintenance records within the date range
        $maintenances = MaintenanceRecord::with(['equipment', 'equipment.equipmentType', 'performedBy'])
            ->whereBetween('scheduled_date', [$start, $end])
            ->orWhereBetween('completed_date', [$start, $end])
            ->get();

        $events = [];

        foreach ($maintenances as $maintenance) {
            // Scheduled maintenance event
            if ($maintenance->scheduled_date) {
                $events[] = [
                    'id' => 'scheduled-' . $maintenance->id,
                    'title' => $maintenance->equipment->name . ' - ' . $maintenance->type,
                    'start' => $maintenance->scheduled_date,
                    'end' => $maintenance->scheduled_date,
                    'color' => $this->getEventColor($maintenance->status),
                    'extendedProps' => [
                        'maintenance_id' => $maintenance->id,
                        'equipment' => $maintenance->equipment->name,
                        'type' => $maintenance->type,
                        'status' => $maintenance->status,
                        'technician' => $maintenance->performedBy->name ?? 'No asignado',
                        'event_type' => 'scheduled'
                    ],
                    'url' => route('maintenance.show', $maintenance->id)
                ];
            }

            // Completed maintenance event (if different from scheduled)
            if ($maintenance->completed_date && 
                (!$maintenance->scheduled_date || 
                 Carbon::parse($maintenance->completed_date)->format('Y-m-d') !== 
                 Carbon::parse($maintenance->scheduled_date)->format('Y-m-d'))) {
                $events[] = [
                    'id' => 'completed-' . $maintenance->id,
                    'title' => $maintenance->equipment->name . ' - Completado',
                    'start' => $maintenance->completed_date,
                    'end' => $maintenance->completed_date,
                    'color' => '#10b981', // Verde para completados
                    'extendedProps' => [
                        'maintenance_id' => $maintenance->id,
                        'equipment' => $maintenance->equipment->name,
                        'type' => $maintenance->type,
                        'status' => 'completed',
                        'technician' => $maintenance->performedBy->name ?? 'No asignado',
                        'event_type' => 'completed'
                    ],
                    'url' => route('maintenance.show', $maintenance->id)
                ];
            }
        }

        // Add upcoming maintenances based on equipment next maintenance date
        $upcomingEquipment = Equipment::with('equipmentType')
            ->whereNotNull('next_maintenance_date')
            ->whereBetween('next_maintenance_date', [$start, $end])
            ->get();

        foreach ($upcomingEquipment as $equipment) {
            // Check if there's already a maintenance record for this date
            $existingMaintenance = $maintenances->first(function ($m) use ($equipment) {
                return $m->equipment_id === $equipment->id && 
                       $m->scheduled_date && 
                       Carbon::parse($m->scheduled_date)->format('Y-m-d') === 
                       Carbon::parse($equipment->next_maintenance_date)->format('Y-m-d');
            });

            if (!$existingMaintenance) {
                $events[] = [
                    'id' => 'upcoming-equipment-' . $equipment->id,
                    'title' => $equipment->name . ' - Mantenimiento Programado',
                    'start' => $equipment->next_maintenance_date,
                    'end' => $equipment->next_maintenance_date,
                    'color' => '#f59e0b', // Amarillo para próximos
                    'extendedProps' => [
                        'equipment_id' => $equipment->id,
                        'equipment' => $equipment->name,
                        'type' => 'preventivo',
                        'status' => 'próximo',
                        'event_type' => 'upcoming'
                    ],
                    'url' => route('equipment.show', $equipment->id)
                ];
            }
        }

        return response()->json($events);
    }

    /**
     * Get the color for an event based on maintenance status.
     */
    private function getEventColor($status)
    {
        return match($status) {
            'scheduled' => '#f59e0b', // Amarillo
            'in_progress' => '#3b82f6', // Azul
            'completed' => '#10b981', // Verde
            'cancelled' => '#ef4444', // Rojo
            default => '#6b7280' // Gris
        };
    }
}
