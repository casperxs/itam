<?php

namespace App\Console\Commands;

use App\Models\MaintenanceRecord;
use App\Models\Equipment;
use App\Mail\MaintenanceScheduled;
use App\Notifications\MaintenanceDueNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendMaintenanceNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maintenance:send-notifications 
                           {--days=1 : Days in advance to send notifications} 
                           {--dry-run : Show what would be sent without actually sending}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email notifications for upcoming maintenance schedules';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $dryRun = $this->option('dry-run');
        
        $this->info('🔧 Enviando notificaciones de mantenimiento...');
        $this->info('📅 Buscando mantenimientos programados para los próximos ' . $days . ' días');
        
        if ($dryRun) {
            $this->warn('⚠️  MODO DE PRUEBA - No se enviarán emails reales');
        }
        
        // Buscar mantenimientos programados en el rango de días especificado
        $startDate = Carbon::now();
        $endDate = Carbon::now()->addDays($days);
        
        $upcomingMaintenances = MaintenanceRecord::with(['equipment.equipmentType', 'equipment.currentAssignment.itUser', 'performedBy'])
            ->where('status', 'scheduled')
            ->whereBetween('scheduled_date', [$startDate, $endDate])
            ->get();
            
        if ($upcomingMaintenances->isEmpty()) {
            $this->info('✅ No hay mantenimientos programados en el rango de fechas especificado.');
            return 0;
        }
        
        $this->info('📋 Encontrados ' . $upcomingMaintenances->count() . ' mantenimientos programados:');
        
        $sentCount = 0;
        $errorCount = 0;
        
        foreach ($upcomingMaintenances as $maintenance) {
            $equipment = $maintenance->equipment;
            $user = $equipment->currentAssignment?->itUser;
            $technician = $maintenance->performedBy;
            
            $scheduledDate = Carbon::parse($maintenance->scheduled_date);
            $hoursUntil = now()->diffInHours($scheduledDate, false);
            
            $this->line('');
            $this->info("📌 Mantenimiento #{$maintenance->id}");
            $this->line("   Equipo: {$equipment->name}");
            $this->line("   Usuario: " . ($user?->name ?? 'No asignado'));
            $this->line("   Email: " . ($user?->email ?? 'N/A'));
            $this->line("   Técnico: " . ($technician?->name ?? 'No asignado'));
            $this->line("   Fecha: {$scheduledDate->format('d/m/Y H:i')}");
            $this->line("   Tiempo restante: " . abs($hoursUntil) . ' horas');
            
            if ($dryRun) {
                $this->comment('   [DRY-RUN] Se enviaría notificación...');
                $sentCount++;
                continue;
            }
            
            try {
                // Enviar por email usando la clase Mail
                if ($user && $user->email) {
                    Mail::to($user->email)->send(new MaintenanceScheduled($maintenance));
                    $this->comment('   ✅ Email enviado a usuario: ' . $user->email);
                }
                
                // Copiar al técnico si tiene email
                if ($technician && $technician->email && $technician->email !== ($user?->email ?? '')) {
                    Mail::to($technician->email)->send(new MaintenanceScheduled($maintenance));
                    $this->comment('   ✅ Email enviado a técnico: ' . $technician->email);
                }
                
                // Enviar notificación al sistema (base de datos) si el usuario tiene cuenta
                if ($user) {
                    // Buscar si hay un User asociado al ItUser
                    $systemUser = \App\Models\User::where('email', $user->email)->first();
                    if ($systemUser) {
                        $systemUser->notify(new MaintenanceDueNotification($maintenance));
                        $this->comment('   ✅ Notificación del sistema enviada');
                    }
                }
                
                $sentCount++;
                
            } catch (\Exception $e) {
                $this->error('   ❌ Error enviando notificación: ' . $e->getMessage());
                $errorCount++;
            }
            
            // Pequeña pausa para no sobrecargar el servidor de email
            usleep(500000); // 0.5 segundos
        }
        
        $this->line('');
        $this->info('📊 RESUMEN:');
        $this->info("   • Notificaciones enviadas: {$sentCount}");
        if ($errorCount > 0) {
            $this->error("   • Errores: {$errorCount}");
        }
        
        // También revisar equipos con next_maintenance_date próxima
        $this->line('');
        $this->info('🔍 Revisando equipos con mantenimiento próximo programado...');
        
        $upcomingEquipment = Equipment::with(['equipmentType', 'currentAssignment.itUser'])
            ->whereNotNull('next_maintenance_date')
            ->whereBetween('next_maintenance_date', [$startDate, $endDate])
            ->whereDoesntHave('maintenanceRecords', function($query) use ($startDate, $endDate) {
                $query->where('status', 'scheduled')
                      ->whereBetween('scheduled_date', [$startDate, $endDate]);
            })
            ->get();
            
        if (!$upcomingEquipment->isEmpty()) {
            $this->info('📋 Equipos que necesitan programación de mantenimiento: ' . $upcomingEquipment->count());
            
            foreach ($upcomingEquipment as $equipment) {
                $user = $equipment->currentAssignment?->itUser;
                $nextDate = Carbon::parse($equipment->next_maintenance_date);
                
                $this->line("   📌 {$equipment->name} - Usuario: " . ($user?->name ?? 'No asignado') . " - Fecha: {$nextDate->format('d/m/Y')}");
            }
            
            $this->comment('💡 Sugerencia: Crear registros de mantenimiento para estos equipos.');
        }
        
        $this->line('');
        $this->info('✅ Proceso completado.');
        
        return 0;
    }
}
