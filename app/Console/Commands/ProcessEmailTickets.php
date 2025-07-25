<?php // app/Console/Commands/ProcessEmailTickets.php
namespace App\Console\Commands;

use App\Jobs\ProcessEmailTicket;
use Illuminate\Console\Command;

class ProcessEmailTickets extends Command
{
    protected $signature = 'email:process-tickets';
    protected $description = 'Process incoming emails from Office 365 and create tickets';

    public function handle()
    {
        $this->info('Processing email tickets...');
        
        ProcessEmailTicket::dispatch();
        
        $this->info('Email tickets processing job dispatched.');
        
        return 0;
    }
}
