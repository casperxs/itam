<?php // app/Console/Commands/CleanupTempFiles.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CleanupTempFiles extends Command
{
    protected $signature = 'cleanup:temp-files';
    protected $description = 'Clean up temporary and old files';

    public function handle()
    {
        $this->info('Cleaning up temporary files...');
        
        // Limpiar archivos de importación bulk antiguos (más de 30 días)
        $oldImports = Storage::disk('private')->files('bulk-imports');
        $cleaned = 0;
        
        foreach ($oldImports as $file) {
            $lastModified = Storage::disk('private')->lastModified($file);
            if (Carbon::createFromTimestamp($lastModified)->addDays(30)->isPast()) {
                Storage::disk('private')->delete($file);
                $cleaned++;
            }
        }
        
        // Limpiar logs antiguos (más de 60 días)
        $logFiles = Storage::disk('local')->files('logs');
        foreach ($logFiles as $file) {
            if (str_contains($file, '.log')) {
                $lastModified = Storage::disk('local')->lastModified($file);
                if (Carbon::createFromTimestamp($lastModified)->addDays(60)->isPast()) {
                    Storage::disk('local')->delete($file);
                    $cleaned++;
                }
            }
        }
        
        $this->info("Cleaned up {$cleaned} temporary files.");
        
        return 0;
    }
}
