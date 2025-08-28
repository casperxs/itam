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
        $query = Equipment::with(['equipmentType', 'supplier', 'currentAssignment.itUser', 'latestRating']);

        if ($request->has('search') && !empty(trim($request->search))) {
            $search = trim($request->search);
            $searchTerms = explode(' ', $search);
            
            $query->where(function($q) use ($search, $searchTerms) {
                // Búsqueda directa en campos del equipo
                $q->where('serial_number', 'like', "%{$search}%")
                  ->orWhere('asset_tag', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('specifications', 'like', "%{$search}%")
                  ->orWhere('observations', 'like', "%{$search}%")
                  ->orWhere('invoice_number', 'like', "%{$search}%")
                  
                  // Búsqueda en tipo de equipo
                  ->orWhereHas('equipmentType', function($typeQ) use ($search) {
                      $typeQ->where('name', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%");
                  })
                  
                  // Búsqueda en proveedor
                  ->orWhereHas('supplier', function($supplierQ) use ($search) {
                      $supplierQ->where('name', 'like', "%{$search}%")
                               ->orWhere('contact_name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  })
                  
                  // Búsqueda en usuario asignado actual
                  ->orWhereHas('currentAssignment.itUser', function($userQ) use ($search) {
                      $userQ->where('name', 'like', "%{$search}%")
                           ->orWhere('employee_id', 'like', "%{$search}%")
                           ->orWhere('department', 'like', "%{$search}%")
                           ->orWhere('position', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
                  });
                
                // Búsqueda por múltiples términos
                if (count($searchTerms) > 1) {
                    foreach ($searchTerms as $term) {
                        $term = trim($term);
                        if (!empty($term)) {
                            $q->orWhere('serial_number', 'like', "%{$term}%")
                              ->orWhere('asset_tag', 'like', "%{$term}%")
                              ->orWhere('brand', 'like', "%{$term}%")
                              ->orWhere('model', 'like', "%{$term}%")
                              
                              // Búsqueda de términos individuales en relaciones
                              ->orWhereHas('equipmentType', function($typeQ) use ($term) {
                                  $typeQ->where('name', 'like', "%{$term}%");
                              })
                              ->orWhereHas('supplier', function($supplierQ) use ($term) {
                                  $supplierQ->where('name', 'like', "%{$term}%");
                              })
                              ->orWhereHas('currentAssignment.itUser', function($userQ) use ($term) {
                                  $userQ->where('name', 'like', "%{$term}%")
                                       ->orWhere('employee_id', 'like', "%{$term}%")
                                       ->orWhere('department', 'like', "%{$term}%");
                              });
                        }
                    }
                    
                    // Búsqueda cruzada: marca + modelo
                    $q->orWhere(function($subQ) use ($searchTerms) {
                        foreach ($searchTerms as $i => $term1) {
                            foreach ($searchTerms as $j => $term2) {
                                if ($i !== $j && !empty(trim($term1)) && !empty(trim($term2))) {
                                    $subQ->orWhere(function($crossQ) use ($term1, $term2) {
                                        $crossQ->where('brand', 'like', "%{$term1}%")
                                               ->where('model', 'like', "%{$term2}%");
                                    })->orWhere(function($crossQ) use ($term1, $term2) {
                                        $crossQ->where('brand', 'like', "%{$term2}%")
                                               ->where('model', 'like', "%{$term1}%");
                                    });
                                }
                            }
                        }
                    });
                }
            });
        }

        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        if ($request->has('type') && !empty($request->type)) {
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
        $equipment->load(['equipmentType', 'supplier', 'assignments.itUser', 'maintenanceRecords.performedBy', 'latestRating']);
        return view('equipment.show', compact('equipment'));
    }

    public function edit(Equipment $equipment)
    {
        $equipment->load(['latestRating']);
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

    /**
     * Búsqueda AJAX para equipos
     */
    public function ajaxSearch(Request $request)
    {
        $query = Equipment::with(['equipmentType', 'supplier', 'currentAssignment.itUser', 'latestRating']);

        if ($request->has('search') && !empty(trim($request->search))) {
            $search = trim($request->search);
            $searchTerms = explode(' ', $search);
            
            $query->where(function($q) use ($search, $searchTerms) {
                // Búsqueda directa en campos del equipo
                $q->where('serial_number', 'like', "%{$search}%")
                  ->orWhere('asset_tag', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('specifications', 'like', "%{$search}%")
                  ->orWhere('observations', 'like', "%{$search}%")
                  ->orWhere('invoice_number', 'like', "%{$search}%")
                  
                  // Búsqueda en tipo de equipo
                  ->orWhereHas('equipmentType', function($typeQ) use ($search) {
                      $typeQ->where('name', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%");
                  })
                  
                  // Búsqueda en proveedor
                  ->orWhereHas('supplier', function($supplierQ) use ($search) {
                      $supplierQ->where('name', 'like', "%{$search}%")
                               ->orWhere('contact_name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  })
                  
                  // Búsqueda en usuario asignado actual
                  ->orWhereHas('currentAssignment.itUser', function($userQ) use ($search) {
                      $userQ->where('name', 'like', "%{$search}%")
                           ->orWhere('employee_id', 'like', "%{$search}%")
                           ->orWhere('department', 'like', "%{$search}%")
                           ->orWhere('position', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
                  });
                
                // Búsqueda por múltiples términos
                if (count($searchTerms) > 1) {
                    foreach ($searchTerms as $term) {
                        $term = trim($term);
                        if (!empty($term)) {
                            $q->orWhere('serial_number', 'like', "%{$term}%")
                              ->orWhere('asset_tag', 'like', "%{$term}%")
                              ->orWhere('brand', 'like', "%{$term}%")
                              ->orWhere('model', 'like', "%{$term}%")
                              
                              // Búsqueda de términos individuales en relaciones
                              ->orWhereHas('equipmentType', function($typeQ) use ($term) {
                                  $typeQ->where('name', 'like', "%{$term}%");
                              })
                              ->orWhereHas('supplier', function($supplierQ) use ($term) {
                                  $supplierQ->where('name', 'like', "%{$term}%");
                              })
                              ->orWhereHas('currentAssignment.itUser', function($userQ) use ($term) {
                                  $userQ->where('name', 'like', "%{$term}%")
                                       ->orWhere('employee_id', 'like', "%{$term}%")
                                       ->orWhere('department', 'like', "%{$term}%");
                              });
                        }
                    }
                    
                    // Búsqueda cruzada: marca + modelo
                    $q->orWhere(function($subQ) use ($searchTerms) {
                        foreach ($searchTerms as $i => $term1) {
                            foreach ($searchTerms as $j => $term2) {
                                if ($i !== $j && !empty(trim($term1)) && !empty(trim($term2))) {
                                    $subQ->orWhere(function($crossQ) use ($term1, $term2) {
                                        $crossQ->where('brand', 'like', "%{$term1}%")
                                               ->where('model', 'like', "%{$term2}%");
                                    })->orWhere(function($crossQ) use ($term1, $term2) {
                                        $crossQ->where('brand', 'like', "%{$term2}%")
                                               ->where('model', 'like', "%{$term1}%");
                                    });
                                }
                            }
                        }
                    });
                }
            });
        }

        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        if ($request->has('type') && !empty($request->type)) {
            $query->where('equipment_type_id', $request->type);
        }

        $equipment = $query->paginate(15);
        
        // Generar el HTML de la tabla
        $html = view('equipment.partials.table-rows', compact('equipment'))->render();
        
        return response()->json([
            'html' => $html,
            'pagination' => $equipment->appends(request()->query())->links()->toHtml(),
            'count' => $equipment->total()
        ]);
    }

    /**
     * Búsqueda AJAX para equipos disponibles (para asignaciones)
     */
    public function searchAvailable(Request $request)
    {
        $search = $request->get('search', '');
        
        // Solo equipos con status 'available' - equipos listos para asignación
        $query = Equipment::with(['equipmentType'])
            ->where('status', 'available')
            ->whereDoesntHave('currentAssignment'); // Asegurar que no tenga asignación activa
            
        if (!empty($search)) {
            $searchTerms = explode(' ', $search);
            
            $query->where(function($q) use ($search, $searchTerms) {
                // Búsqueda directa en campos del equipo
                $q->where('serial_number', 'like', "%{$search}%")
                  ->orWhere('asset_tag', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('specifications', 'like', "%{$search}%")
                  
                  // Búsqueda en tipo de equipo
                  ->orWhereHas('equipmentType', function($typeQ) use ($search) {
                      $typeQ->where('name', 'like', "%{$search}%");
                  });
                
                // Búsqueda por múltiples términos
                if (count($searchTerms) > 1) {
                    foreach ($searchTerms as $term) {
                        $term = trim($term);
                        if (!empty($term)) {
                            $q->orWhere('brand', 'like', "%{$term}%")
                              ->orWhere('model', 'like', "%{$term}%")
                              ->orWhere('serial_number', 'like', "%{$term}%")
                              ->orWhereHas('equipmentType', function($typeQ) use ($term) {
                                  $typeQ->where('name', 'like', "%{$term}%");
                              });
                        }
                    }
                    
                    // Búsqueda cruzada: marca + modelo
                    $q->orWhere(function($subQ) use ($searchTerms) {
                        foreach ($searchTerms as $i => $term1) {
                            foreach ($searchTerms as $j => $term2) {
                                if ($i !== $j && !empty(trim($term1)) && !empty(trim($term2))) {
                                    $subQ->orWhere(function($crossQ) use ($term1, $term2) {
                                        $crossQ->where('brand', 'like', "%{$term1}%")
                                               ->where('model', 'like', "%{$term2}%");
                                    })->orWhere(function($crossQ) use ($term1, $term2) {
                                        $crossQ->where('brand', 'like', "%{$term2}%")
                                               ->where('model', 'like', "%{$term1}%");
                                    });
                                }
                            }
                        }
                    });
                }
            });
        }
        
        $equipment = $query->limit(50)->get();
        
        return response()->json([
            'results' => $equipment->map(function($item) {
                return [
                    'id' => $item->id,
                    'text' => ($item->equipmentType->name ?? 'N/A') . ' - ' . $item->brand . ' ' . $item->model . ' (' . $item->serial_number . ')'
                ];
            })
        ]);
    }
}
