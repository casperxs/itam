<?php // app/Console/Commands/SendContractAlerts.php
namespace App\Console\Commands;

use App\Models\Contract;
use App\Jobs\SendContractExpirationAlert;
use Illuminate\Console\Command;

class SendContractAlerts extends Command
{
    protected $signature = 'contracts:send-alerts';
    protected $description = 'Send alerts for contracts expiring soon';

    public function handle()
    {
        $this->info('Checking for expiring contracts...');
        
        $expiringContracts = Contract::where('status', 'active')
            ->where(function($query) {
                $query->whereRaw('DATEDIFF(end_date, NOW()) <= alert_days_before')
                      ->where('end_date', '>', now());
            })
            ->get();

        foreach ($expiringContracts as $contract) {
            SendContractExpirationAlert::dispatch($contract);
        }

        $this->info("Processed {$expiringContracts->count()} expiring contracts.");
        
        return 0;
    }
}
