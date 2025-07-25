<?php // app/Http/Controllers/EquipmentTypeController.php
namespace App\Http\Controllers;

use App\Models\EquipmentType;
use Illuminate\Http\Request;

class EquipmentTypeController extends Controller
{
    public function index()
    {
        $equipmentTypes = EquipmentType::withCount('equipment')->paginate(15);
        return view('equipment-types.index', compact('equipmentTypes'));
    }

    public function create()
    {
        return view('equipment-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:computer,phone,printer,license,software',
            'description' => 'nullable|string',
        ]);

        EquipmentType::create($validated);

        return redirect()->route('equipment-types.index')
            ->with('success', 'Tipo de equipo creado exitosamente.');
    }

    public function edit(EquipmentType $equipmentType)
    {
        return view('equipment-types.edit', compact('equipmentType'));
    }

    public function update(Request $request, EquipmentType $equipmentType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:computer,phone,printer,license,software',
            'description' => 'nullable|string',
        ]);

        $equipmentType->update($validated);

        return redirect()->route('equipment-types.index')
            ->with('success', 'Tipo de equipo actualizado exitosamente.');
    }

    public function destroy(EquipmentType $equipmentType)
    {
        if ($equipmentType->equipment()->exists()) {
            return redirect()->back()
                ->with('error', 'No se puede eliminar el tipo de equipo porque tiene equipos asociados.');
        }

        $equipmentType->delete();

        return redirect()->route('equipment-types.index')
            ->with('success', 'Tipo de equipo eliminado exitosamente.');
    }
}
