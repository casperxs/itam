<?php // app/Jobs/SendContractExpirationAlert.php
namespace App\Jobs;

use App\Models\Contract;
use App\Models\User;
use App\Notifications\ContractExpiringNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendContractExpirationAlert implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $contract;

    public function __construct(Contract $contract)
    {
        $this->contract = $contract;
    }

    public function handle()
    {
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new ContractExpiringNotification($this->contract));
        }
    }
}
