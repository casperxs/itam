<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Equipment;
use App\Models\MaintenanceRecord;
use Carbon\Carbon;

class MaintenanceCalendarSeeder extends Seeder
{
    public function run()
    {
        $equipments = Equipment::with('equipmentType')->get();
        
        if ($equipments->count() === 0) {
            $this->command->info('No hay equipos en la base de datos. Ejecuta primero los seeders de equipos.');
            return;
        }

        // Primero asegurémonos de que los equipos tengan brand y model
        foreach ($equipments as $equipment) {
            $updated = false;
            if (empty($equipment->brand)) {
                $equipment->brand = 'Generic';
                $updated = true;
            }
            if (empty($equipment->model)) {
                $equipment->model = $equipment->equipmentType->name . ' ' . str_pad($equipment->id, 3, '0', STR_PAD_LEFT);
                $updated = true;
            }
            if ($updated) {
                $equipment->save();
            }
        }

        $this->command->info('Creando registros de mantenimiento para el calendario...');

        // Crear mantenimientos pasados (completados)
        $laptop1 = $equipments->where('equipment_type_id', 1)->first();
        if ($laptop1) {
            MaintenanceRecord::create([
                'equipment_id' => $laptop1->id,
                'type' => 'preventive',
                'description' => 'Mantenimiento preventivo - Limpieza y actualización de software',
                'scheduled_date' => Carbon::now()->subDays(15),
                'completed_date' => Carbon::now()->subDays(14),
                'performed_by' => 1, // ID del usuario
                'status' => 'completed',
                'cost' => 50.00,
                'notes' => 'Mantenimiento realizado satisfactoriamente. Sistema optimizado.',
                'performed_actions' => 'Limpieza física del equipo, actualización de software y optimización del sistema'
            ]);
        }

        // Crear mantenimientos en progreso
        $desktop = $equipments->where('equipment_type_id', 2)->first();
        if ($desktop) {
            MaintenanceRecord::create([
                'equipment_id' => $desktop->id,
                'type' => 'corrective',
                'description' => 'Reparación de ventilador defectuoso',
                'scheduled_date' => Carbon::now()->subDays(2),
                'performed_by' => 1,
                'status' => 'in_progress',
                'cost' => 75.00,
                'notes' => 'Esperando llegada de repuesto. Ventilador ordenado.',
                'performed_actions' => 'Diagnóstico realizado, ventilador defectuoso identificado'
            ]);
        }

        // Crear mantenimientos programados para hoy
        $printer = $equipments->where('equipment_type_id', 5)->first();
        if ($printer) {
            MaintenanceRecord::create([
                'equipment_id' => $printer->id,
                'type' => 'preventive',
                'description' => 'Limpieza y calibración de impresora',
                'scheduled_date' => Carbon::today(),
                'performed_by' => 1,
                'status' => 'scheduled',
                'cost' => 30.00,
                'notes' => 'Mantenimiento rutinario programado'
            ]);
        }

        // Crear mantenimientos futuros (próximos días)
        $laptop2 = $equipments->where('equipment_type_id', 1)->skip(1)->first();
        if ($laptop2) {
            MaintenanceRecord::create([
                'equipment_id' => $laptop2->id,
                'type' => 'preventive',
                'description' => 'Actualización de antivirus y limpieza de archivos temporales',
                'scheduled_date' => Carbon::now()->addDays(3),
                'performed_by' => 1,
                'status' => 'scheduled',
                'cost' => 40.00,
                'notes' => 'Mantenimiento programado mensual'
            ]);
        }

        $mobile = $equipments->where('equipment_type_id', 4)->first();
        if ($mobile) {
            MaintenanceRecord::create([
                'equipment_id' => $mobile->id,
                'type' => 'preventive',
                'description' => 'Revisión de batería y actualización de firmware',
                'scheduled_date' => Carbon::now()->addDays(7),
                'performed_by' => 1,
                'status' => 'scheduled',
                'cost' => 25.00,
                'notes' => 'Revisión semestral programada'
            ]);
        }

        // Crear mantenimientos para la próxima semana
        if ($equipments->count() >= 2) {
            MaintenanceRecord::create([
                'equipment_id' => $equipments->get(0)->id,
                'type' => 'corrective',
                'description' => 'Revisión de teclado con teclas que no responden',
                'scheduled_date' => Carbon::now()->addDays(10),
                'performed_by' => 1,
                'status' => 'scheduled',
                'cost' => 60.00,
                'notes' => 'Usuario reporta falla en varias teclas'
            ]);
        }

        // Crear mantenimiento cancelado
        if ($equipments->count() >= 3) {
            MaintenanceRecord::create([
                'equipment_id' => $equipments->get(2)->id,
                'type' => 'preventive',
                'description' => 'Mantenimiento programado - CANCELADO por equipo en garantía',
                'scheduled_date' => Carbon::now()->addDays(5),
                'performed_by' => 1,
                'status' => 'cancelled',
                'cost' => 0.00,
                'notes' => 'Cancelado - equipo cubierto por garantía del proveedor'
            ]);
        }

        // Actualizar fechas de próximo mantenimiento en equipos
        foreach ($equipments as $equipment) {
            if (!$equipment->next_maintenance_date) {
                // Programar próximo mantenimiento en 1-3 meses
                $equipment->next_maintenance_date = Carbon::now()->addMonths(rand(1, 3));
                $equipment->save();
            }
        }

        $maintenanceCount = MaintenanceRecord::count();
        $this->command->info("Se crearon {$maintenanceCount} registros de mantenimiento para el calendario.");
    }
}
