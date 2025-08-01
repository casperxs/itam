<?php // app/Http/Controllers/EquipmentController.php
namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\EquipmentType;
use App\Models\Supplier;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipment::with(['equipmentType', 'supplier', 'currentAssignment.itUser']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('serial_number', 'like', "%{$search}%")
                  ->orWhere('asset_tag', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('type')) {
            $query->where('equipment_type_id', $request->type);
        }

        $equipment = $query->paginate(15);
        $equipmentTypes = EquipmentType::all();
        $suppliers = Supplier::all();

        return view('equipment.index', compact('equipment', 'equipmentTypes', 'suppliers'));
    }

    public function create()
    {
        $equipmentTypes = EquipmentType::all();
        $suppliers = Supplier::all();
        return view('equipment.create', compact('equipmentTypes', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_type_id' => 'required|exists:equipment_types,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'serial_number' => 'required|unique:equipment',
            'asset_tag' => 'nullable|unique:equipment',
            'brand' => 'required|string',
            'model' => 'required|string',
            'specifications' => 'nullable|string',
            'status' => 'required|in:available,assigned,maintenance,retired,lost',
            'valoracion' => 'nullable|in:100%,90%,80%,70%,60%',
            'purchase_price' => 'nullable|numeric',
            'purchase_date' => 'nullable|date',
            'warranty_end_date' => 'nullable|date',
            'invoice_number' => 'nullable|string',
            'invoice_file' => 'nullable|file|mimes:pdf|max:10240',
            'observations' => 'nullable|string',
        ]);

        if ($request->hasFile('invoice_file')) {
            $validated['invoice_file'] = $request->file('invoice_file')->store('invoices', 'public');
        }

        Equipment::create($validated);

        return redirect()->route('equipment.index')->with('success', 'Equipo creado exitosamente.');
    }

    public function show(Equipment $equipment)
    {
        $equipment->load(['equipmentType', 'supplier', 'assignments.itUser', 'maintenanceRecords.performedBy']);
        return view('equipment.show', compact('equipment'));
    }

    public function edit(Equipment $equipment)
    {
        $equipmentTypes = EquipmentType::all();
        $suppliers = Supplier::all();
        return view('equipment.edit', compact('equipment', 'equipmentTypes', 'suppliers'));
    }

    public function update(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'equipment_type_id' => 'required|exists:equipment_types,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'serial_number' => 'required|unique:equipment,serial_number,' . $equipment->id,
            'asset_tag' => 'nullable|unique:equipment,asset_tag,' . $equipment->id,
            'brand' => 'required|string',
            'model' => 'required|string',
            'specifications' => 'nullable|string',
            'status' => 'required|in:available,assigned,maintenance,retired,lost',
            'valoracion' => 'nullable|in:100%,90%,80%,70%,60%',
            'purchase_price' => 'nullable|numeric',
            'purchase_date' => 'nullable|date',
            'warranty_end_date' => 'nullable|date',
            'invoice_number' => 'nullable|string',
            'invoice_file' => 'nullable|file|mimes:pdf|max:10240',
            'observations' => 'nullable|string',
        ]);

        if ($request->hasFile('invoice_file')) {
            if ($equipment->invoice_file) {
                Storage::disk('public')->delete($equipment->invoice_file);
            }
            $validated['invoice_file'] = $request->file('invoice_file')->store('invoices', 'public');
        }

        $equipment->update($validated);

        return redirect()->route('equipment.show', $equipment)->with('success', 'Equipo actualizado exitosamente.');
    }

    public function destroy(Equipment $equipment)
    {
        if ($equipment->assignments()->exists()) {
            return redirect()->back()->with('error', 'No se puede eliminar el equipo porque tiene asignaciones.');
        }

        if ($equipment->invoice_file) {
            Storage::disk('public')->delete($equipment->invoice_file);
        }

        $equipment->delete();

        return redirect()->route('equipment.index')->with('success', 'Equipo eliminado exitosamente.');
    }

    public function downloadInvoice(Equipment $equipment)
    {
        if (!$equipment->invoice_file || !Storage::disk('public')->exists($equipment->invoice_file)) {
            return redirect()->back()->with('error', 'Archivo de factura no encontrado.');
        }

        return Storage::disk('public')->download($equipment->invoice_file);
    }
}
