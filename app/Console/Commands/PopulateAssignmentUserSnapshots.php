<?php

namespace App\Console\Commands;

use App\Models\Assignment;
use Illuminate\Console\Command;

class PopulateAssignmentUserSnapshots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assignments:populate-user-snapshots';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Poblar los campos de snapshot de usuario en asignaciones existentes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Poblando snapshots de usuarios en asignaciones...');
        
        $assignments = Assignment::whereNull('user_name')
            ->with('itUser')
            ->get();
        
        $updated = 0;
        $skipped = 0;
        
        foreach ($assignments as $assignment) {
            if ($assignment->itUser) {
                $assignment->update([
                    'user_name' => $assignment->itUser->name,
                    'user_email' => $assignment->itUser->email,
                    'user_employee_id' => $assignment->itUser->employee_id,
                    'user_department' => $assignment->itUser->department,
                    'user_position' => $assignment->itUser->position,
                ]);
                $updated++;
            } else {
                $this->warn("Asignación ID {$assignment->id} no tiene usuario asociado - se omitirá");
                $skipped++;
            }
        }
        
        $this->info("✅ Proceso completado:");
        $this->info("   - Asignaciones actualizadas: {$updated}");
        $this->info("   - Asignaciones omitidas: {$skipped}");
        
        return Command::SUCCESS;
    }
}
