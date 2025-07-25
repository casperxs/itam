<?php // app/Http/Controllers/SupplierController.php
namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::withCount(['equipment', 'contracts']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('contact_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $suppliers = $query->paginate(15);

        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'tax_id' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        Supplier::create($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Proveedor creado exitosamente.');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load(['equipment.equipmentType', 'contracts']);
        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'tax_id' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $supplier->update($validated);

        return redirect()->route('suppliers.show', $supplier)
            ->with('success', 'Proveedor actualizado exitosamente.');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->equipment()->exists() || $supplier->contracts()->exists()) {
            return redirect()->back()
                ->with('error', 'No se puede eliminar el proveedor porque tiene equipos o contratos asociados.');
        }

        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', 'Proveedor eliminado exitosamente.');
    }
}
