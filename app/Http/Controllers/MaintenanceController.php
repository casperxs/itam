<?php // app/Http/Controllers/MaintenanceController.php
namespace App\Http\Controllers;

use App\Models\MaintenanceRecord;
use App\Models\Equipment;
use App\Models\User;
use App\Models\RatingCriterion;
use App\Models\EquipmentRating;
use App\Services\PdfGeneratorService;
use App\Services\IcsGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        // Ya no necesitamos cargar todos los equipos, solo los técnicos
        $technicians = User::all();
        
        return view('maintenance.create', compact('technicians'));
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

        $maintenance = MaintenanceRecord::create([
            ...$validated,
            'status' => 'scheduled',
        ]);

        // Redirigir a la página de notificación para generar ICS y correo
        return redirect()->route('maintenance.send-notification', $maintenance);
    }

    public function show(MaintenanceRecord $maintenance)
    {
        $maintenance->load(['equipment.equipmentType', 'equipment.latestRating', 'performedBy']);
        
        // Load rating criteria for the evaluation form
        $ratingCriteria = RatingCriterion::getAllActive();
        
        // Get the last rating for this equipment
        $lastRating = EquipmentRating::where('equipment_id', $maintenance->equipment_id)
            ->orderBy('created_at', 'desc')
            ->first();
        $previousScore = $lastRating ? $lastRating->total_score : null;
        
        return view('maintenance.show', compact('maintenance', 'ratingCriteria', 'previousScore'));
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
            'rating' => 'required|array',
            'rating.*' => 'required|integer|min:0|max:10',
            'rating_notes' => 'nullable|string',
            'checklist' => 'required|array',
            'checklist.*.activity' => 'required|string',
            'checklist.*.status' => 'required|in:correcto,na,incorrecto',
            'checklist.*.details' => 'nullable|string',
        ]);

        // Calculate equipment rating
        $ratingCriteria = RatingCriterion::getAllActive();
        $totalScore = 0;
        $criteriaEvaluations = [];
        
        foreach ($ratingCriteria as $criterion) {
            $value = $validated['rating'][$criterion->id];
            // Formula corregida: (Peso × Puntuación) / 10 para obtener valores como en el ejemplo
            $weightedScore = ($criterion->weight_percentage * $value) / 10;
            $totalScore += $weightedScore;
            
            $criteriaEvaluations[$criterion->id] = [
                'criterion_name' => $criterion->name,
                'value' => $value,
                'weight_percentage' => $criterion->weight_percentage,
                'weighted_score' => $weightedScore,
            ];
        }
        
        $ratingCategory = EquipmentRating::calculateCategory($totalScore);
        
        // Validate against previous rating (degradation only)
        $lastRating = EquipmentRating::where('equipment_id', $maintenance->equipment_id)
            ->orderBy('created_at', 'desc')
            ->first();
            
        if ($lastRating && $totalScore > $lastRating->total_score) {
            return back()->withErrors([
                'rating' => "La nueva evaluación ({$totalScore}%) no puede ser mejor que la anterior ({$lastRating->total_score}%). El sistema permite solo degradación."
            ])->withInput();
        }
        
        // For new equipment (less than 6 months), allow any score
        $equipmentAge = $maintenance->equipment->purchase_date 
            ? $maintenance->equipment->purchase_date->diffInMonths(now()) 
            : null;
            
        // Process checklist data
        $checklistData = [];
        foreach ($validated['checklist'] as $item) {
            $checklistData[] = [
                'activity' => $item['activity'],
                'status' => $item['status'],
                'details' => $item['details'] ?? null,
            ];
        }
        
        // Update maintenance record with checklist data
        $maintenance->update([
            'completed_date' => $validated['completed_date'],
            'performed_actions' => $validated['performed_actions'],
            'cost' => $validated['cost'],
            'notes' => $validated['notes'],
            'status' => 'completed',
            'checklist_data' => $checklistData,
        ]);
        
        // Create equipment rating record
        EquipmentRating::create([
            'equipment_id' => $maintenance->equipment_id,
            'maintenance_record_id' => $maintenance->id,
            'evaluated_by' => Auth::id(),
            'criteria_evaluations' => $criteriaEvaluations,
            'total_score' => $totalScore,
            'rating_category' => $ratingCategory,
            'notes' => $validated['rating_notes'],
        ]);
        
        // Update equipment valoracion
        $maintenance->equipment->update([
            'status' => 'available',
            'valoracion' => $ratingCategory,
        ]);

        return redirect()->route('maintenance.show', $maintenance)
            ->with('success', 'Mantenimiento completado exitosamente con evaluación de equipo.');
    }

    public function downloadChecklist(MaintenanceRecord $maintenance)
    {
        if ($maintenance->status !== 'completed') {
            return redirect()->back()->with('error', 'Solo se puede generar checklist para mantenimientos completados.');
        }

        $maintenance->load(['equipment.equipmentType', 'equipment.supplier', 'equipment.currentAssignment.itUser', 'performedBy']);
        
        return $this->pdfService->generateMaintenanceChecklist($maintenance);
    }

    public function destroy(MaintenanceRecord $maintenance)
    {
        // Verificar que el mantenimiento no esté completado (opcional, según reglas de negocio)
        if ($maintenance->status === 'completed') {
            return redirect()->back()->with('error', 'No se puede eliminar un mantenimiento completado.');
        }

        // Si el mantenimiento está en progreso, cambiar el estado del equipo a disponible
        if ($maintenance->status === 'in_progress' && $maintenance->equipment) {
            $maintenance->equipment->update(['status' => 'available']);
        }

        // Eliminar registro de evaluación relacionado si existe
        if ($maintenance->equipmentRating) {
            $maintenance->equipmentRating->delete();
        }

        // Eliminar el mantenimiento
        $maintenance->delete();

        return redirect()->route('maintenance.index')
            ->with('success', 'Mantenimiento eliminado exitosamente.');
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

    public function searchEquipment(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        $equipment = Equipment::with(['equipmentType', 'currentAssignment.itUser'])
            ->where(function($q) use ($query) {
                // Búsqueda por tipo de equipo
                $q->whereHas('equipmentType', function($subQ) use ($query) {
                    $subQ->where('name', 'like', "%{$query}%");
                })
                // Búsqueda por marca
                ->orWhere('brand', 'like', "%{$query}%")
                // Búsqueda por modelo
                ->orWhere('model', 'like', "%{$query}%")
                // Búsqueda por número de serie
                ->orWhere('serial_number', 'like', "%{$query}%")
                // Búsqueda por asset tag
                ->orWhere('asset_tag', 'like', "%{$query}%")
                // Búsqueda por nombre de usuario asignado
                ->orWhereHas('currentAssignment.itUser', function($subQ) use ($query) {
                    $subQ->where('name', 'like', "%{$query}%")
                         ->orWhere('employee_id', 'like', "%{$query}%")
                         ->orWhere('department', 'like', "%{$query}%");
                });
            })
            ->limit(10)
            ->get();
        
        $results = $equipment->map(function($item) {
            $assignedUser = $item->currentAssignment ? $item->currentAssignment->itUser : null;
            
            return [
                'id' => $item->id,
                'text' => $this->formatEquipmentText($item),
                'type' => $item->equipmentType->name ?? 'N/A',
                'brand' => $item->brand ?? 'N/A',
                'model' => $item->model ?? 'N/A',
                'serial_number' => $item->serial_number ?? 'N/A',
                'asset_tag' => $item->asset_tag,
                'status' => $item->status,
                'assigned_user' => $assignedUser ? $assignedUser->name : null,
                'user_department' => $assignedUser ? $assignedUser->department : null,
                'user_employee_id' => $assignedUser ? $assignedUser->employee_id : null,
            ];
        });
        
        return response()->json($results);
    }
    
    private function formatEquipmentText($equipment)
    {
        $text = ($equipment->equipmentType->name ?? 'N/A') . ' - ' . 
                ($equipment->brand ?? 'N/A') . ' ' . 
                ($equipment->model ?? 'N/A') . 
                ' (' . ($equipment->serial_number ?? 'N/A') . ')';
        
        if ($equipment->currentAssignment && $equipment->currentAssignment->itUser) {
            $user = $equipment->currentAssignment->itUser;
            $text .= ' | Usuario: ' . $user->name;
            if ($user->department) {
                $text .= ' (' . $user->department . ')';
            }
        } else {
            $text .= ' | Sin asignar';
        }
        
        return $text;
    }

    public function sendNotification(MaintenanceRecord $maintenance, IcsGeneratorService $icsService)
    {
        $maintenance->load(['equipment.equipmentType', 'equipment.currentAssignment.itUser', 'performedBy']);
        
        // Generar archivo ICS
        $icsFilename = $icsService->generateMaintenanceIcs($maintenance);
        
        // Preparar datos para el correo
        $emailContent = $icsService->generateEmailContent($maintenance, $icsFilename);
        
        // Convertir formato del servicio existente al formato esperado por la vista
        $emailData = [
            'to' => !empty($emailContent['to']) ? [$emailContent['to']] : [],
            'cc' => !empty($emailContent['cc']) ? [$emailContent['cc']] : [],
            'subject' => $emailContent['subject'] ?? 'Mantenimiento Programado',
            'body' => urldecode(str_replace('%0A', "\n", $emailContent['body'] ?? 'Mantenimiento programado sin detalles disponibles.'))
        ];
        
        // Si no hay emails, agregar un mensaje por defecto
        if (empty($emailData['to']) && empty($emailData['cc'])) {
            $emailData['to'] = ['soporteit@bkb.mx'];
        }
        
        return view('maintenance.notification-success', compact('maintenance', 'emailData', 'icsFilename'));
    }

    public function downloadIcs(MaintenanceRecord $maintenance, IcsGeneratorService $icsService)
    {
        $maintenance->load(['equipment.equipmentType']);
        
        $icsFilename = $icsService->generateMaintenanceIcs($maintenance);
        $filePath = storage_path('app/public/ics/' . $icsFilename);
        
        if (file_exists($filePath)) {
            return response()->download($filePath, $icsFilename, [
                'Content-Type' => 'text/calendar',
                'Content-Disposition' => 'attachment; filename="' . $icsFilename . '"'
            ])->deleteFileAfterSend(true);
        }
        
        return redirect()->back()->with('error', 'No se pudo generar el archivo ICS.');
    }
}
