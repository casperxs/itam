<?php // app/Console/Commands/GenerateMonthlyReports.php
namespace App\Console\Commands;

use App\Services\ReportService;
use App\Models\User;
use App\Mail\MonthlyReport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class GenerateMonthlyReports extends Command
{
    protected $signature = 'reports:generate-monthly';
    protected $description = 'Generate and send monthly reports to administrators';

    public function handle(ReportService $reportService)
    {
        $this->info('Generating monthly reports...');
        
        $equipmentData = $reportService->generateEquipmentReport();
        $assignmentData = $reportService->generateAssignmentReport();
        $maintenanceData = $reportService->generateMaintenanceReport();
        $contractData = $reportService->generateContractReport();
        
        $admins = User::where('role', 'admin')->get();
        
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new MonthlyReport([
                'equipment' => $equipmentData,
                'assignments' => $assignmentData,
                'maintenance' => $maintenanceData,
                'contracts' => $contractData,
            ]));
        }
        
        $this->info('Monthly reports sent to administrators.');
        
        return 0;
    }
}
