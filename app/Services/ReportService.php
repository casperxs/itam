<?php // app/Services/ReportService.php
namespace App\Services;

use App\Models\Equipment;
use App\Models\Assignment;
use App\Models\MaintenanceRecord;
use App\Models\Contract;
use App\Models\ItUser;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportService
{
    public function generateEquipmentReport($filters = [])
    {
        $query = Equipment::with(['equipmentType', 'supplier', 'currentAssignment.itUser']);

        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }
        
        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }
        
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (isset($filters['type'])) {
            $query->where('equipment_type_id', $filters['type']);
        }
        
        if (isset($filters['supplier'])) {
            $query->where('supplier_id', $filters['supplier']);
        }

        $equipment = $query->get();

        return [
            'equipment' => $equipment,
            'totals' => [
                'total_equipment' => $equipment->count(),
                'total_value' => $equipment->sum('purchase_price'),
                'by_status' => $equipment->groupBy('status')->map->count(),
                'by_type' => $equipment->groupBy('equipmentType.name')->map->count(),
            ]
        ];
    }

    public function generateAssignmentReport($filters = [])
    {
        $query = Assignment::with(['equipment.equipmentType', 'itUser', 'assignedBy']);

        if (isset($filters['date_from'])) {
            $query->where('assigned_at', '>=', $filters['date_from']);
        }
        
        if (isset($filters['date_to'])) {
            $query->where('assigned_at', '<=', $filters['date_to']);
        }
        
        if (isset($filters['user'])) {
            $query->where('it_user_id', $filters['user']);
        }
        
        if (isset($filters['department'])) {
            $query->whereHas('itUser', function($q) use ($filters) {
                $q->where('department', $filters['department']);
            });
        }
        
        if (isset($filters['status'])) {
            if ($filters['status'] === 'active') {
                $query->whereNull('returned_at');
            } elseif ($filters['status'] === 'returned') {
                $query->whereNotNull('returned_at');
            }
        }

        $assignments = $query->get();

        return [
            'assignments' => $assignments,
            'totals' => [
                'total_assignments' => $assignments->count(),
                'active_assignments' => $assignments->where('returned_at', null)->count(),
                'returned_assignments' => $assignments->where('returned_at', '!=', null)->count(),
                'by_department' => $assignments->groupBy('itUser.department')->map->count(),
            ]
        ];
    }

    public function generateMaintenanceReport($filters = [])
    {
        $query = MaintenanceRecord::with(['equipment.equipmentType', 'performedBy']);

        if (isset($filters['date_from'])) {
            $query->where('scheduled_date', '>=', $filters['date_from']);
        }
        
        if (isset($filters['date_to'])) {
            $query->where('scheduled_date', '<=', $filters['date_to']);
        }
        
        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (isset($filters['technician'])) {
            $query->where('performed_by', $filters['technician']);
        }

        $maintenanceRecords = $query->get();

        return [
            'maintenance_records' => $maintenanceRecords,
            'totals' => [
                'total_maintenance' => $maintenanceRecords->count(),
                'total_cost' => $maintenanceRecords->sum('cost'),
                'by_type' => $maintenanceRecords->groupBy('type')->map->count(),
                'by_status' => $maintenanceRecords->groupBy('status')->map->count(),
            ]
        ];
    }

    public function generateContractReport($filters = [])
    {
        $query = Contract::with('supplier');

        if (isset($filters['date_from'])) {
            $query->where('start_date', '>=', $filters['date_from']);
        }
        
        if (isset($filters['date_to'])) {
            $query->where('end_date', '<=', $filters['date_to']);
        }
        
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (isset($filters['supplier'])) {
            $query->where('supplier_id', $filters['supplier']);
        }

        $contracts = $query->get();

        return [
            'contracts' => $contracts,
            'totals' => [
                'total_contracts' => $contracts->count(),
                'total_monthly_cost' => $contracts->sum('monthly_cost'),
                'total_contract_value' => $contracts->sum('total_cost'),
                'by_status' => $contracts->groupBy('status')->map->count(),
                'expiring_soon' => $contracts->filter->needsAlert()->count(),
            ]
        ];
    }

    public function exportEquipmentReport($data, $format)
    {
        if ($format === 'excel') {
            return $this->exportToExcel($data['equipment'], 'equipos');
        } elseif ($format === 'pdf') {
            return (new PdfGeneratorService())->generateEquipmentReport($data['equipment']);
        }
    }

    public function exportAssignmentReport($data, $format)
    {
        if ($format === 'excel') {
            return $this->exportToExcel($data['assignments'], 'asignaciones');
        }
    }

    public function exportMaintenanceReport($data, $format)
    {
        if ($format === 'excel') {
            return $this->exportToExcel($data['maintenance_records'], 'mantenimientos');
        } elseif ($format === 'pdf') {
            return (new PdfGeneratorService())->generateMaintenanceReport($data['maintenance_records']);
        }
    }

    public function exportContractReport($data, $format)
    {
        if ($format === 'excel') {
            return $this->exportToExcel($data['contracts'], 'contratos');
        }
    }

    private function exportToExcel($data, $type)
    {
        // Implementar exportación a Excel usando Laravel Excel
        // Por ahora, retornamos un CSV simple
        $filename = "{$type}_" . date('Y-m-d') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($data, $type) {
            $file = fopen('php://output', 'w');
            
            // Headers basados en el tipo
            if ($type === 'equipos') {
                fputcsv($file, ['Tipo', 'Número Serie', 'Marca', 'Modelo', 'Estado', 'Proveedor', 'Precio', 'Fecha Compra']);
                foreach ($data as $item) {
                    fputcsv($file, [
                        $item->equipmentType->name,
                        $item->serial_number,
                        $item->brand,
                        $item->model,
                        $item->status,
                        $item->supplier->name,
                        $item->purchase_price,
                        $item->purchase_date?->format('Y-m-d'),
                    ]);
                }
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
