<?php

namespace App\Console\Commands;

use App\Models\Equipment;
use Illuminate\Console\Command;

class SyncEquipmentAssignmentStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'equipment:sync-status {--dry-run : Show what would be updated without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize equipment status based on current assignments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info('🔍 Running in dry-run mode - no changes will be made');
        }
        
        $this->info('🔄 Analyzing equipment assignment status...');
        
        // Find equipment marked as 'assigned' but without current assignment
        $assignedWithoutUser = Equipment::where('status', 'assigned')
            ->whereDoesntHave('currentAssignment')
            ->get();
            
        // Find equipment with current assignment but not marked as 'assigned'
        $userWithoutStatus = Equipment::whereHas('currentAssignment')
            ->where('status', '!=', 'assigned')
            ->get();
            
        $totalInconsistencies = $assignedWithoutUser->count() + $userWithoutStatus->count();
        
        if ($totalInconsistencies === 0) {
            $this->info('✅ No inconsistencies found. All equipment status is synchronized.');
            return;
        }
        
        $this->warn("⚠️  Found {$totalInconsistencies} inconsistencies:");
        
        if ($assignedWithoutUser->count() > 0) {
            $this->line("\n📋 Equipment marked as 'assigned' but without current assignment ({$assignedWithoutUser->count()}):" );
            foreach ($assignedWithoutUser as $equipment) {
                $this->line("  - {$equipment->brand} {$equipment->model} (SN: {$equipment->serial_number})");
                if (!$dryRun) {
                    $equipment->update(['status' => 'available']);
                    $this->info("    ✅ Updated to 'available'");
                }
            }
        }
        
        if ($userWithoutStatus->count() > 0) {
            $this->line("\n👤 Equipment with current assignment but not marked as 'assigned' ({$userWithoutStatus->count()}):" );
            foreach ($userWithoutStatus as $equipment) {
                $assignedTo = $equipment->currentAssignment->itUser->name ?? 'Unknown User';
                $this->line("  - {$equipment->brand} {$equipment->model} (SN: {$equipment->serial_number}) - Assigned to: {$assignedTo}");
                if (!$dryRun) {
                    $equipment->update(['status' => 'assigned']);
                    $this->info("    ✅ Updated to 'assigned'");
                }
            }
        }
        
        if (!$dryRun) {
            $this->info("\n✅ Synchronization completed. {$totalInconsistencies} inconsistencies resolved.");
        } else {
            $this->info("\n💡 Run without --dry-run flag to apply these changes.");
        }
    }
}
