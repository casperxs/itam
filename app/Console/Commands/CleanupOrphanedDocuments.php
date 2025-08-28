<?php

namespace App\Console\Commands;

use App\Models\Assignment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupOrphanedDocuments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assignment:cleanup-orphaned-documents {--dry-run : Only show what would be cleaned up without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up orphaned assignment documents (documents referenced in DB but file does not exist)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Searching for orphaned assignment documents...');
        
        $assignments = Assignment::whereNotNull('assignment_document')->get();
        $orphanedCount = 0;
        $fixedCount = 0;
        
        $this->info("Found {$assignments->count()} assignments with documents to check.");
        
        foreach ($assignments as $assignment) {
            $documentPath = $assignment->assignment_document;
            $fullPath = storage_path('app/private/assignments/' . $documentPath);
            
            if (!file_exists($fullPath)) {
                $orphanedCount++;
                
                $this->warn("Orphaned document found: ID {$assignment->id} - {$documentPath}");
                
                if (!$this->option('dry-run')) {
                    // Clear the document reference from the assignment
                    $assignment->update(['assignment_document' => null]);
                    $fixedCount++;
                    $this->info("  → Cleared document reference from assignment ID {$assignment->id}");
                }
            }
        }
        
        if ($this->option('dry-run')) {
            $this->info("\n[DRY RUN] Found {$orphanedCount} orphaned documents.");
            $this->info('Run without --dry-run to actually clean them up.');
        } else {
            $this->info("\nCleaned up {$fixedCount} orphaned document references.");
        }
        
        // Also check for files on disk that are not referenced in DB
        $this->info('\nChecking for unreferenced files on disk...');
        
        $assignmentsPath = storage_path('app/private/assignments');
        if (is_dir($assignmentsPath)) {
            $files = glob($assignmentsPath . '/*.pdf');
            $unreferencedFiles = [];
            
            foreach ($files as $filePath) {
                $fileName = basename($filePath);
                $isReferenced = Assignment::where('assignment_document', $fileName)->exists();
                
                if (!$isReferenced) {
                    $unreferencedFiles[] = $fileName;
                    $this->warn("Unreferenced file: {$fileName}");
                }
            }
            
            if (!empty($unreferencedFiles)) {
                $this->info("Found " . count($unreferencedFiles) . " unreferenced files.");
                
                if ($this->option('dry-run')) {
                    $this->info('[DRY RUN] Would delete these unreferenced files.');
                } else {
                    if ($this->confirm('Do you want to delete these unreferenced files?')) {
                        foreach ($unreferencedFiles as $fileName) {
                            $filePath = $assignmentsPath . '/' . $fileName;
                            if (unlink($filePath)) {
                                $this->info("  → Deleted {$fileName}");
                            }
                        }
                    }
                }
            } else {
                $this->info('No unreferenced files found.');
            }
        }
        
        $this->info('\nCleanup completed!');
        
        return 0;
    }
}
