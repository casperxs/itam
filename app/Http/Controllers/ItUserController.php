<?php // app/Http/Controllers/ItUserController.php
namespace App\Http\Controllers;

use App\Models\ItUser;
use App\Models\UserDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItUserController extends Controller
{
    public function index(Request $request)
    {
        $query = ItUser::active()->withCount(['currentAssignments', 'documents']);

        if ($request->has('search') && !empty(trim($request->search))) {
            $search = $request->search;
            $searchTerms = explode(' ', trim($search));
            
            $query->where(function($q) use ($search, $searchTerms) {
                // Búsqueda del término completo en cada campo
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
                
                // Si hay múltiples términos, buscar combinaciones entre campos
                if (count($searchTerms) > 1) {
                    foreach ($searchTerms as $term) {
                        if (!empty(trim($term))) {
                            $q->orWhere('name', 'like', "%{$term}%")
                              ->orWhere('email', 'like', "%{$term}%")
                              ->orWhere('employee_id', 'like', "%{$term}%")
                              ->orWhere('department', 'like', "%{$term}%");
                        }
                    }
                    
                    // Búsqueda cruzada: nombre + departamento, nombre + email, etc.
                    $q->orWhere(function($subQ) use ($searchTerms) {
                        foreach ($searchTerms as $i => $term1) {
                            foreach ($searchTerms as $j => $term2) {
                                if ($i !== $j && !empty(trim($term1)) && !empty(trim($term2))) {
                                    // Nombre + Departamento
                                    $subQ->orWhere(function($crossQ) use ($term1, $term2) {
                                        $crossQ->where('name', 'like', "%{$term1}%")
                                               ->where('department', 'like', "%{$term2}%");
                                    })->orWhere(function($crossQ) use ($term1, $term2) {
                                        $crossQ->where('name', 'like', "%{$term2}%")
                                               ->where('department', 'like', "%{$term1}%");
                                    })
                                    // Nombre + Email
                                    ->orWhere(function($crossQ) use ($term1, $term2) {
                                        $crossQ->where('name', 'like', "%{$term1}%")
                                               ->where('email', 'like', "%{$term2}%");
                                    })->orWhere(function($crossQ) use ($term1, $term2) {
                                        $crossQ->where('name', 'like', "%{$term2}%")
                                               ->where('email', 'like', "%{$term1}%");
                                    })
                                    // Departamento + Email
                                    ->orWhere(function($crossQ) use ($term1, $term2) {
                                        $crossQ->where('department', 'like', "%{$term1}%")
                                               ->where('email', 'like', "%{$term2}%");
                                    })->orWhere(function($crossQ) use ($term1, $term2) {
                                        $crossQ->where('department', 'like', "%{$term2}%")
                                               ->where('email', 'like', "%{$term1}%");
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

        if ($request->has('department') && !empty($request->department)) {
            $query->where('department', $request->department);
        }

        $departments = ItUser::distinct()->pluck('department');
        $itUsers = $query->with('assignments')->paginate(15);

        return view('it-users.index', compact('itUsers', 'departments'));
    }

    public function create()
    {
        return view('it-users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:it_users',
            'employee_id' => 'required|string|unique:it_users',
            'department' => 'required|string',
            'position' => 'required|string',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        ItUser::create($validated);

        return redirect()->route('it-users.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    public function show(ItUser $itUser)
    {
        $itUser->load([
            'currentAssignments.equipment.equipmentType',
            'assignments.equipment.equipmentType',
            'documents',
            'emailTickets'
        ]);

        return view('it-users.show', compact('itUser'));
    }

    public function edit(ItUser $itUser)
    {
        return view('it-users.edit', compact('itUser'));
    }

    public function update(Request $request, ItUser $itUser)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:it_users,email,' . $itUser->id,
            'employee_id' => 'required|string|unique:it_users,employee_id,' . $itUser->id,
            'department' => 'required|string',
            'position' => 'required|string',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        $itUser->update($validated);

        return redirect()->route('it-users.show', $itUser)
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy(Request $request, ItUser $itUser)
    {
        if ($itUser->currentAssignments()->exists()) {
            return redirect()->back()
                ->with('error', 'No se puede eliminar el usuario porque tiene equipos asignados activos. Primero debe devolver todos los equipos.');
        }

        // Usar soft delete con razón opcional
        $reason = $request->input('delete_reason', 'Eliminado por el administrador');
        $itUser->softDelete($reason);

        return redirect()->route('it-users.index')
            ->with('success', 'Usuario eliminado exitosamente. Se ha guardado en el histórico.');
    }

    public function documents(ItUser $itUser)
    {
        $documents = $itUser->documents()->orderBy('created_at', 'desc')->get();
        return view('it-users.documents', compact('itUser', 'documents'));
    }

    public function uploadDocument(Request $request, ItUser $itUser)
    {
        $validated = $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'document_type' => 'required|string|in:manual,contrato,identificacion,capacitacion,politica,otro',
            'description' => 'nullable|string',
        ]);

        $file = $request->file('document');
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->format('YmdHis');
        $documentType = $validated['document_type'];
        
        // Formato: EMP001_manual_20250731235959.pdf
        $filename = $itUser->employee_id . '_' . $documentType . '_' . $timestamp . '.' . $extension;
        
        $filePath = $file->storeAs('user-documents', $filename, 'private');

        UserDocument::create([
            'it_user_id' => $itUser->id,
            'original_name' => $originalName,
            'filename' => $filename,
            'file_path' => $filePath,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'document_type' => $documentType,
            'description' => $validated['description'],
        ]);

        return redirect()->back()->with('success', 'Documento subido exitosamente.');
    }

    public function downloadDocument(ItUser $itUser, UserDocument $userDocument)
    {
        if ($userDocument->it_user_id !== $itUser->id) {
            abort(404);
        }

        $filePath = storage_path('app/private/' . $userDocument->file_path);
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'Archivo no encontrado.');
        }

        return response()->download($filePath, $userDocument->original_name);
    }

    public function deleteDocument(ItUser $itUser, UserDocument $userDocument)
    {
        if ($userDocument->it_user_id !== $itUser->id) {
            abort(404);
        }

        // Eliminar archivo físico
        Storage::disk('private')->delete($userDocument->file_path);
        
        // Eliminar registro de la base de datos
        $userDocument->delete();

        return redirect()->back()->with('success', 'Documento eliminado exitosamente.');
    }

    /**
     * Búsqueda AJAX para usuarios activos (para asignaciones)
     */
    public function searchActive(Request $request)
    {
        $search = $request->get('search', '');
        
        $query = ItUser::where('status', 'active');
            
        if (!empty($search)) {
            $searchTerms = explode(' ', $search);
            
            $query->where(function($q) use ($search, $searchTerms) {
                // Búsqueda del término completo en cada campo
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
                
                // Si hay múltiples términos, buscar combinaciones entre campos
                if (count($searchTerms) > 1) {
                    foreach ($searchTerms as $term) {
                        if (!empty(trim($term))) {
                            $q->orWhere('name', 'like', "%{$term}%")
                              ->orWhere('email', 'like', "%{$term}%")
                              ->orWhere('employee_id', 'like', "%{$term}%")
                              ->orWhere('department', 'like', "%{$term}%")
                              ->orWhere('position', 'like', "%{$term}%");
                        }
                    }
                    
                    // Búsqueda cruzada: nombre + departamento, nombre + email, etc.
                    $q->orWhere(function($subQ) use ($searchTerms) {
                        foreach ($searchTerms as $i => $term1) {
                            foreach ($searchTerms as $j => $term2) {
                                if ($i !== $j && !empty(trim($term1)) && !empty(trim($term2))) {
                                    // Nombre + Departamento
                                    $subQ->orWhere(function($crossQ) use ($term1, $term2) {
                                        $crossQ->where('name', 'like', "%{$term1}%")
                                               ->where('department', 'like', "%{$term2}%");
                                    })->orWhere(function($crossQ) use ($term1, $term2) {
                                        $crossQ->where('name', 'like', "%{$term2}%")
                                               ->where('department', 'like', "%{$term1}%");
                                    })
                                    // Nombre + Email
                                    ->orWhere(function($crossQ) use ($term1, $term2) {
                                        $crossQ->where('name', 'like', "%{$term1}%")
                                               ->where('email', 'like', "%{$term2}%");
                                    })->orWhere(function($crossQ) use ($term1, $term2) {
                                        $crossQ->where('name', 'like', "%{$term2}%")
                                               ->where('email', 'like', "%{$term1}%");
                                    });
                                }
                            }
                        }
                    });
                }
            });
        }
        
        $users = $query->limit(50)->get();
        
        return response()->json([
            'results' => $users->map(function($user) {
                return [
                    'id' => $user->id,
                    'text' => $user->name . ' (' . $user->employee_id . ') - ' . $user->department
                ];
            })
        ]);
    }
}
