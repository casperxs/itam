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
        $maintenances = MaintenanceRecord::with(['equipment', 'equipment.equipmentType'])
            ->whereBetween('scheduled_date', [$start, $end])
            ->orWhereBetween('completion_date', [$start, $end])
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
                        'technician' => $maintenance->technician,
                        'event_type' => 'scheduled'
                    ],
                    'url' => route('maintenance.show', $maintenance->id)
                ];
            }

            // Completed maintenance event (if different from scheduled)
            if ($maintenance->completion_date && 
                (!$maintenance->scheduled_date || 
                 Carbon::parse($maintenance->completion_date)->format('Y-m-d') !== 
                 Carbon::parse($maintenance->scheduled_date)->format('Y-m-d'))) {
                $events[] = [
                    'id' => 'completed-' . $maintenance->id,
                    'title' => $maintenance->equipment->name . ' - Completado',
                    'start' => $maintenance->completion_date,
                    'end' => $maintenance->completion_date,
                    'color' => '#10b981', // Verde para completados
                    'extendedProps' => [
                        'maintenance_id' => $maintenance->id,
                        'equipment' => $maintenance->equipment->name,
                        'type' => $maintenance->type,
                        'status' => 'completado',
                        'technician' => $maintenance->technician,
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
            'pendiente' => '#f59e0b', // Amarillo
            'en_progreso' => '#3b82f6', // Azul
            'completado' => '#10b981', // Verde
            'cancelado' => '#ef4444', // Rojo
            default => '#6b7280' // Gris
        };
    }
}
