<?php
// app/Console/Kernel.php
namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\ProcessEmailTickets::class,
        Commands\SendContractAlerts::class,
        Commands\ProcessBulkImports::class,
        Commands\GenerateMonthlyReports::class,
        Commands\CleanupTempFiles::class,
        Commands\CheckWarranties::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // Procesar emails cada 15 minutos
        $schedule->command('email:process-tickets')
                 ->everyFifteenMinutes();

        // Verificar contratos diariamente a las 8:00 AM
        $schedule->command('contracts:send-alerts')
                 ->dailyAt('08:00');

        // Verificar garantías diariamente a las 9:00 AM
        $schedule->command('warranties:check')
                 ->dailyAt('09:00');

        // Procesar importaciones masivas cada hora
        $schedule->command('import:process-bulk')
                 ->hourly();

        // Generar reportes mensuales el primer día de cada mes
        $schedule->command('reports:generate-monthly')
                 ->monthlyOn(1, '10:00');

        // Limpiar archivos temporales semanalmente
        $schedule->command('cleanup:temp-files')
                 ->weekly();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
