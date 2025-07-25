<?php // app/Jobs/ProcessBulkEquipmentImport.php
namespace App\Jobs;

use App\Models\BulkImport;
use App\Services\BulkImportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessBulkEquipmentImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $import;

    public function __construct(BulkImport $import)
    {
        $this->import = $import;
    }

    public function handle(BulkImportService $importService)
    {
        $importService->processImport($this->import);
    }
}
