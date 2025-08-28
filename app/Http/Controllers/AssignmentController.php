<?php // app/Http/Controllers/AssignmentController.php
namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Equipment;
use App\Models\ItUser;
use App\Services\PdfGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    protected $pdfService;

    public function __construct(PdfGeneratorService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    public function index(Request $request)
    {
        $query = Assignment::with(['equipment.equipmentType', 'itUser', 'assignedBy']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('itUser', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            })->orWhereHas('equipment', function($q) use ($search) {
                $q->where('serial_number', 'like', "%{$search}%")
                  ->orWhere('asset_tag', 'like', "%{$search}%");
            });
        }

        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->whereNull('returned_at');
            } elseif ($request->status === 'returned') {
                $query->whereNotNull('returned_at');
            }
        }

        $assignments = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('assignments.index', compact('assignments'));
    }

    public function create()
    {
        $availableEquipment = Equipment::where('status', 'available')
            ->with('equipmentType')
            ->get();
        $users = ItUser::where('status', 'active')->get();
        
        return view('assignments.create', compact('availableEquipment', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'it_user_id' => 'required|exists:it_users,id',
            'assignment_notes' => 'nullable|string',
            'assigned_at' => 'required|date',
        ]);

        $equipment = Equipment::findOrFail($validated['equipment_id']);
        $user = ItUser::findOrFail($validated['it_user_id']);
        
        if ($equipment->status !== 'available') {
            return redirect()->back()->with('error', 'El equipo no está disponible para asignación.');
        }

        $assignment = Assignment::create([
            ...$validated,
            'assigned_by' => Auth::id(),
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_employee_id' => $user->employee_id,
            'user_department' => $user->department,
            'user_position' => $user->position,
        ]);

        $equipment->update(['status' => 'assigned']);

        // Generar documento PDF
        $pdfPath = $this->pdfService->generateAssignmentDocument($assignment);
        $assignment->update(['assignment_document' => $pdfPath]);

        return redirect()->route('assignments.show', $assignment)
            ->with('success', 'Asignación creada exitosamente.');
    }

    public function show(Assignment $assignment)
    {
        $assignment->load(['equipment.equipmentType', 'itUser', 'assignedBy']);
        return view('assignments.show', compact('assignment'));
    }

    public function returnEquipment(Assignment $assignment)
    {
        return view('assignments.return', compact('assignment'));
    }

    public function processReturn(Request $request, Assignment $assignment)
    {
        $validated = $request->validate([
            'return_notes' => 'nullable|string',
            'returned_at' => 'required|date',
        ]);

        $assignment->update([
            'returned_at' => $validated['returned_at'],
            'return_notes' => $validated['return_notes'],
        ]);

        $assignment->equipment->update(['status' => 'available']);

        return redirect()->route('assignments.index')
            ->with('success', 'Equipo devuelto exitosamente.');
    }

    public function downloadDocument(Assignment $assignment)
    {
        if (!$assignment->assignment_document) {
            return redirect()->back()->with('error', 'Documento no encontrado.');
        }

        $filePath = storage_path('app/private/assignments/' . $assignment->assignment_document);
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'El archivo del documento no existe. Por favor, regenera el documento.');
        }

        return response()->download($filePath);
    }

    public function markSigned(Assignment $assignment)
    {
        $assignment->update(['document_signed' => true]);
        
        return redirect()->back()->with('success', 'Documento marcado como firmado.');
    }

    public function regenerateDocument(Assignment $assignment)
    {
        try {
            // Generar documento PDF
            $pdfPath = $this->pdfService->generateAssignmentDocument($assignment);
            $assignment->update(['assignment_document' => $pdfPath]);
            
            return redirect()->back()->with('success', 'Documento regenerado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al regenerar el documento: ' . $e->getMessage());
        }
    }

    public function generateConsolidatedDocument(ItUser $itUser)
    {
        $assignments = $itUser->currentAssignments()
            ->with(['equipment.equipmentType', 'equipment.supplier', 'assignedBy'])
            ->get();

        if ($assignments->isEmpty()) {
            return redirect()->back()->with('error', 'El usuario no tiene equipos asignados actualmente.');
        }

        $pdfPath = $this->pdfService->generateConsolidatedAssignmentDocument($itUser, $assignments);
        
        // Actualizar todos los assignments con el documento consolidado
        foreach ($assignments as $assignment) {
            $assignment->update(['assignment_document' => $pdfPath]);
        }

        return redirect()->back()->with('success', 'Documento consolidado generado exitosamente.');
    }

    public function downloadConsolidatedDocument(ItUser $itUser)
    {
        $assignment = $itUser->currentAssignments()->first();
        
        if (!$assignment || !$assignment->assignment_document) {
            return redirect()->back()->with('error', 'Documento consolidado no encontrado.');
        }

        $filePath = storage_path('app/private/assignments/' . $assignment->assignment_document);
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'El archivo del documento consolidado no existe. Por favor, regenera el documento.');
        }

        return response()->download($filePath);
    }

    public function generateExitDocument(ItUser $itUser)
    {
        $assignments = $itUser->currentAssignments()
            ->with(['equipment.equipmentType', 'equipment.supplier', 'assignedBy'])
            ->get();

        if ($assignments->isEmpty()) {
            return redirect()->back()->with('error', 'El usuario no tiene equipos asignados actualmente.');
        }

        try {
            $pdfPath = $this->pdfService->generateEquipmentExitDocument($itUser, $assignments);
            
            $fullPath = storage_path('app/private/assignments/' . $pdfPath);
            
            if (!file_exists($fullPath)) {
                return redirect()->back()->with('error', 'Error al generar el documento de salida.');
            }
            
            return response()->download($fullPath)
                ->deleteFileAfterSend(false);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar el documento de salida: ' . $e->getMessage());
        }
    }
}
