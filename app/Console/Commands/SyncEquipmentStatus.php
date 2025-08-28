<?php

namespace App\Console\Commands;

use App\Models\Equipment;
use Illuminate\Console\Command;

class SyncEquipmentStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'equipment:sync-status {--dry-run : Show what would be changed without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza el status de todos los equipos basado en sus asignaciones actuales';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sincronizando status de equipos...');
        
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->warn('MODO DRY-RUN: Solo se mostrarán los cambios sin aplicarlos');
        }
        
        // Obtener todos los equipos con sus asignaciones actuales
        $equipment = Equipment::with('currentAssignment')->get();
        
        $changes = 0;
        $analyzed = 0;
        
        $this->info("Analizando {$equipment->count()} equipos...");
        
        foreach ($equipment as $item) {
            $analyzed++;
            $currentStatus = $item->status;
            $hasAssignment = $item->currentAssignment !== null;
            
            // Determinar el status correcto
            $correctStatus = $this->determineCorrectStatus($item, $hasAssignment);
            
            if ($currentStatus !== $correctStatus) {
                $changes++;
                
                $this->line(sprintf(
                    'ID: %d | S/N: %s | Status actual: %s -> Nuevo status: %s | Asignado: %s',
                    $item->id,
                    $item->serial_number,
                    $currentStatus,
                    $correctStatus,
                    $hasAssignment ? 'Sí (' . $item->currentAssignment->user_name . ')' : 'No'
                ));
                
                if (!$dryRun) {
                    $item->update(['status' => $correctStatus]);
                }
            }
            
            // Progress indicator every 50 items
            if ($analyzed % 50 === 0) {
                $this->info("Progreso: {$analyzed}/{$equipment->count()} equipos analizados...");
            }
        }
        
        if ($changes > 0) {
            if ($dryRun) {
                $this->warn("Se encontraron {$changes} equipos que necesitan sincronización.");
                $this->info('Ejecuta el comando sin --dry-run para aplicar los cambios.');
            } else {
                $this->info("\u2713 Se sincronizaron {$changes} equipos exitosamente.");
            }
        } else {
            $this->info('\u2713 Todos los equipos ya tienen el status correcto.');
        }
        
        $this->info("Proceso completado. {$analyzed} equipos analizados, {$changes} cambios" . ($dryRun ? ' requeridos' : ' aplicados') . '.');
        
        return 0;
    }
    
    /**
     * Determina el status correcto para un equipo
     */
    private function determineCorrectStatus(Equipment $equipment, bool $hasAssignment): string
    {
        // No cambiar equipos en mantenimiento, retirados o perdidos
        if (in_array($equipment->status, ['maintenance', 'retired', 'lost'])) {
            return $equipment->status;
        }
        
        // Si tiene asignación activa, debe estar 'assigned'
        if ($hasAssignment) {
            return 'assigned';
        }
        
        // Si no tiene asignación activa, debe estar 'available'
        return 'available';
    }
}
