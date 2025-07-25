<?php // app/Console/Commands/ProcessBulkImports.php
namespace App\Console\Commands;

use App\Models\BulkImport;
use App\Services\BulkImportService;
use Illuminate\Console\Command;

class ProcessBulkImports extends Command
{
    protected $signature = 'import:process-bulk';
    protected $description = 'Process pending bulk imports';

    public function handle(BulkImportService $importService)
    {
        $this->info('Processing pending bulk imports...');
        
        $pendingImports = BulkImport::where('status', 'pending')->get();
        
        foreach ($pendingImports as $import) {
            $this->info("Processing import ID: {$import->id}");
            $importService->processImport($import);
        }
        
        $this->info("Processed {$pendingImports->count()} bulk imports.");
        
        return 0;
    }
}