<?php // app/Http/Controllers/MaintenanceController.php
namespace App\Http\Controllers;

use App\Models\MaintenanceRecord;
use App\Models\Equipment;
use App\Models\User;
use App\Services\PdfGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    protected $pdfService;

    public function __construct(PdfGeneratorService $pdfService)
    {
        $this->pdfService = $pdfService;
    }
    public function index(Request $request)
    {
        $query = MaintenanceRecord::with(['equipment.equipmentType', 'performedBy']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('equipment', function($q) use ($search) {
                $q->where('serial_number', 'like', "%{$search}%")
                  ->orWhere('asset_tag', 'like', "%{$search}%");
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $maintenanceRecords = $query->orderBy('scheduled_date', 'desc')->paginate(15);
        
        return view('maintenance.index', compact('maintenanceRecords'));
    }

    public function create()
    {
        $equipment = Equipment::with('equipmentType')->get();
        $technicians = User::all();
        
        return view('maintenance.create', compact('equipment', 'technicians'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'performed_by' => 'required|exists:users,id',
            'type' => 'required|in:preventive,corrective,update',
            'scheduled_date' => 'required|date',
            'description' => 'required|string',
            'cost' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        MaintenanceRecord::create([
            ...$validated,
            'status' => 'scheduled',
        ]);

        return redirect()->route('maintenance.index')
            ->with('success', 'Mantenimiento programado exitosamente.');
    }

    public function show(MaintenanceRecord $maintenance)
    {
        $maintenance->load(['equipment.equipmentType', 'performedBy']);
        return view('maintenance.show', compact('maintenance'));
    }

    public function edit(MaintenanceRecord $maintenance)
    {
        $equipment = Equipment::with('equipmentType')->get();
        $technicians = User::all();
        
        return view('maintenance.edit', compact('maintenance', 'equipment', 'technicians'));
    }

    public function update(Request $request, MaintenanceRecord $maintenance)
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'performed_by' => 'required|exists:users,id',
            'type' => 'required|in:preventive,corrective,update',
            'scheduled_date' => 'required|date',
            'completed_date' => 'nullable|date',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'description' => 'required|string',
            'performed_actions' => 'nullable|string',
            'cost' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        $maintenance->update($validated);

        // Si se marca como completado, cambiar el estado del equipo
        if ($validated['status'] === 'completed' && $maintenance->equipment->status === 'maintenance') {
            $maintenance->equipment->update(['status' => 'available']);
        }

        return redirect()->route('maintenance.show', $maintenance)
            ->with('success', 'Mantenimiento actualizado exitosamente.');
    }

    public function startMaintenance(MaintenanceRecord $maintenance)
    {
        $maintenance->update([
            'status' => 'in_progress',
        ]);

        $maintenance->equipment->update(['status' => 'maintenance']);

        return redirect()->back()->with('success', 'Mantenimiento iniciado.');
    }

    public function completeMaintenance(Request $request, MaintenanceRecord $maintenance)
    {
        $validated = $request->validate([
            'completed_date' => 'required|date',
            'performed_actions' => 'required|string',
            'cost' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        $maintenance->update([
            ...$validated,
            'status' => 'completed',
        ]);

        $maintenance->equipment->update(['status' => 'available']);

        return redirect()->route('maintenance.show', $maintenance)
            ->with('success', 'Mantenimiento completado exitosamente.');
    }

    public function downloadChecklist(MaintenanceRecord $maintenance)
    {
        if ($maintenance->status !== 'completed') {
            return redirect()->back()->with('error', 'Solo se puede generar checklist para mantenimientos completados.');
        }

        $maintenance->load(['equipment.equipmentType.supplier', 'equipment.currentAssignment.itUser', 'performedBy']);
        
        return $this->pdfService->generateMaintenanceChecklist($maintenance);
    }

    public function completedMaintenance(Request $request)
    {
        $query = MaintenanceRecord::with(['equipment.equipmentType', 'equipment.currentAssignment.itUser', 'performedBy'])
            ->where('status', 'completed');

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('equipment', function($q) use ($search) {
                $q->where('serial_number', 'like', "%{$search}%")
                  ->orWhere('asset_tag', 'like', "%{$search}%");
            });
        }

        $completedMaintenances = $query->orderBy('completed_date', 'desc')->paginate(15);
        
        return view('maintenance.completed', compact('completedMaintenances'));
    }

    public function calendar()
    {
        $maintenanceRecords = MaintenanceRecord::with(['equipment.equipmentType'])
            ->where('status', '!=', 'cancelled')
            ->get()
            ->map(function ($record) {
                return [
                    'id' => $record->id,
                    'title' => $record->equipment->equipmentType->name . ' - ' . $record->equipment->serial_number,
                    'start' => $record->scheduled_date->format('Y-m-d H:i:s'),
                    'color' => $this->getStatusColor($record->status),
                    'url' => route('maintenance.show', $record),
                ];
            });

        return view('maintenance.calendar', compact('maintenanceRecords'));
    }

    private function getStatusColor($status)
    {
        return match($status) {
            'scheduled' => '#3498db',
            'in_progress' => '#f39c12',
            'completed' => '#27ae60',
            'cancelled' => '#e74c3c',
            default => '#95a5a6',
        };
    }
}