<?php // app/Console/Commands/CheckWarranties.php
namespace App\Console\Commands;

use App\Models\Equipment;
use App\Models\User;
use App\Notifications\WarrantyExpiringNotification;
use Illuminate\Console\Command;

class CheckWarranties extends Command
{
    protected $signature = 'warranties:check';
    protected $description = 'Check for equipment with expiring warranties';

    public function handle()
    {
        $this->info('Checking equipment warranties...');
        
        $expiringWarranties = Equipment::whereDate('warranty_end_date', '<=', now()->addDays(30))
            ->whereDate('warranty_end_date', '>=', now())
            ->with(['equipmentType', 'supplier'])
            ->get();
        
        if ($expiringWarranties->count() > 0) {
            $admins = User::where('role', 'admin')->get();
            
            foreach ($admins as $admin) {
                $admin->notify(new WarrantyExpiringNotification($expiringWarranties));
            }
        }
        
        $this->info("Found {$expiringWarranties->count()} equipment with expiring warranties.");
        
        return 0;
    }
}