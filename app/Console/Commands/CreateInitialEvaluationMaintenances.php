<?php

namespace App\Console\Commands;

use App\Models\Equipment;
use App\Models\EquipmentRating;
use App\Models\MaintenanceRecord;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CreateInitialEvaluationMaintenances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maintenance:create-initial-evaluations 
                            {--user-id=1 : ID del usuario que realizará las evaluaciones}
                            {--dry-run : Solo mostrar qué se haría sin ejecutar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea mantenimientos de evaluación inicial para equipos sin calificaciones previas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->option('user-id');
        $dryRun = $this->option('dry-run');
        
        // Verificar que el usuario existe
        $user = User::find($userId);
        if (!$user) {
            $this->error("Usuario con ID {$userId} no encontrado.");
            return 1;
        }
        
        $this->info("Buscando equipos sin calificaciones previas...");
        
        // Encontrar equipos que no tienen calificaciones
        $equipmentsWithoutRatings = Equipment::whereDoesntHave('ratings')
            ->with(['equipmentType', 'currentAssignment.itUser'])
            ->get();
            
        if ($equipmentsWithoutRatings->isEmpty()) {
            $this->info("✅ Todos los equipos ya tienen calificaciones.");
            return 0;
        }
        
        $this->info("📋 Encontrados {$equipmentsWithoutRatings->count()} equipos sin calificaciones:");
        
        $this->table(
            ['ID', 'Tipo', 'Marca/Modelo', 'Serie', 'Usuario Asignado'],
            $equipmentsWithoutRatings->map(function ($equipment) {
                return [
                    $equipment->id,
                    $equipment->equipmentType->name ?? 'N/A',
                    ($equipment->brand ?? '') . ' ' . ($equipment->model ?? ''),
                    $equipment->serial_number ?? 'N/A',
                    $equipment->currentAssignment->itUser->name ?? 'Sin asignar'
                ];
            })
        );
        
        if ($dryRun) {
            $this->warn("🔍 [DRY RUN] No se realizarán cambios en la base de datos.");
            $this->info("Se crearían {$equipmentsWithoutRatings->count()} mantenimientos de evaluación inicial.");
            return 0;
        }
        
        if (!$this->confirm("¿Deseas crear mantenimientos de evaluación inicial para estos {$equipmentsWithoutRatings->count()} equipos?")) {
            $this->info("Operación cancelada.");
            return 0;
        }
        
        $this->info("🚀 Creando mantenimientos de evaluación inicial...");
        $progressBar = $this->output->createProgressBar($equipmentsWithoutRatings->count());
        
        $created = 0;
        
        foreach ($equipmentsWithoutRatings as $equipment) {
            try {
                $maintenance = MaintenanceRecord::create([
                    'equipment_id' => $equipment->id,
                    'type' => 'preventive',
                    'status' => 'in_progress',
                    'scheduled_date' => now(),
                    'description' => "Evaluación inicial del equipo para establecer calificación base.\n\nEste mantenimiento fue generado automáticamente para permitir la primera evaluación cuantificada del equipo.",
                    'performed_by' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $created++;
                $progressBar->advance();
                
            } catch (\Exception $e) {
                $this->error("\nError creando mantenimiento para equipo {$equipment->id}: " . $e->getMessage());
            }
        }
        
        $progressBar->finish();
        
        $this->newLine(2);
        $this->info("✅ Proceso completado!");
        $this->info("📊 Mantenimientos creados: {$created}");
        $this->info("👤 Usuario asignado: {$user->name} (ID: {$userId})");
        
        $this->newLine();
        $this->warn("📝 SIGUIENTE PASO:");
        $this->info("1. Ve al módulo de Mantenimientos");
        $this->info("2. Filtra por estado 'En Progreso'");
        $this->info("3. Completa cada mantenimiento con su evaluación inicial");
        $this->info("4. Una vez completados, el sistema funcionará normalmente");
        
        return 0;
    }
}
