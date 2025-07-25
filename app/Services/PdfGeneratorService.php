<?php // app/Services/PdfGeneratorService.php
namespace App\Services;

use App\Models\Assignment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfGeneratorService
{
    public function generateAssignmentDocument(Assignment $assignment)
    {
        $assignment->load(['equipment.equipmentType', 'itUser', 'assignedBy']);
        
        $pdf = PDF::loadView('assignments.pdf.assignment-document', compact('assignment'));
        
        $fileName = 'assignment_' . $assignment->id . '_' . time() . '.pdf';
        $path = 'assignments/' . $fileName;
        
        Storage::disk('private')->put($path, $pdf->output());
        
        return $fileName;
    }

    public function generateEquipmentReport($equipment, $filters = [])
    {
        $pdf = PDF::loadView('reports.pdf.equipment', compact('equipment', 'filters'));
        
        return $pdf->stream('reporte_equipos_' . date('Y-m-d') . '.pdf');
    }

    public function generateMaintenanceReport($maintenanceRecords, $filters = [])
    {
        $pdf = PDF::loadView('reports.pdf.maintenance', compact('maintenanceRecords', 'filters'));
        
        return $pdf->stream('reporte_mantenimientos_' . date('Y-m-d') . '.pdf');
    }
}
