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

        $equipment = $query->paginate(15);

        return [
            'equipment' => $equipment,
            'types' => \App\Models\EquipmentType::all(),
            'suppliers' => \App\Models\Supplier::all(),
            'summary' => [
                'total' => $equipment->total(),
                'active' => Equipment::where('status', 'active')->count(),
                'assigned' => Equipment::whereHas('currentAssignment')->count(),
                'total_value' => Equipment::sum('purchase_price'),
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

        $assignments = $query->paginate(15);

        return [
            'assignments' => $assignments,
            'users' => ItUser::all(),
            'summary' => [
                'total' => $assignments->total(),
                'active' => Assignment::whereNull('returned_at')->count(),
                'returned' => Assignment::whereNotNull('returned_at')->count(),
                'unique_users' => Assignment::distinct('it_user_id')->count(),
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

        $maintenance = $query->paginate(15);

        return [
            'maintenance' => $maintenance,
            'technicians' => \App\Models\User::all(),
            'summary' => [
                'total' => $maintenance->total(),
                'scheduled' => MaintenanceRecord::where('status', 'scheduled')->count(),
                'in_progress' => MaintenanceRecord::where('status', 'in_progress')->count(),
                'completed' => MaintenanceRecord::where('status', 'completed')->count(),
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

        $contracts = $query->paginate(15);

        return [
            'contracts' => $contracts,
            'suppliers' => \App\Models\Supplier::all(),
            'summary' => [
                'total' => $contracts->total(),
                'active' => Contract::where('status', 'active')->count(),
                'expired' => Contract::where('end_date', '<', Carbon::now())->count(),
                'expiring' => Contract::where('end_date', '<=', Carbon::now()->addDays(30))->where('end_date', '>=', Carbon::now())->count(),
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
