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
        $query = ItUser::withCount(['currentAssignments', 'documents']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('department')) {
            $query->where('department', $request->department);
        }

        $users = $query->paginate(15);
        $departments = ItUser::distinct()->pluck('department');

        return view('it-users.index', compact('users', 'departments'));
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

    public function destroy(ItUser $itUser)
    {
        if ($itUser->currentAssignments()->exists()) {
            return redirect()->back()
                ->with('error', 'No se puede eliminar el usuario porque tiene equipos asignados.');
        }

        $itUser->delete();

        return redirect()->route('it-users.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }

    public function documents(ItUser $itUser)
    {
        $documents = $itUser->documents()->orderBy('created_at', 'desc')->get();
        return view('it-users.documents', compact('itUser', 'documents'));
    }

    public function uploadDocument(Request $request, ItUser $itUser)
    {
        $validated = $request->validate([
            'document_type' => 'required|string',
            'document_name' => 'required|string',
            'file' => 'required|file|mimes:pdf|max:10240',
            'has_signature' => 'boolean',
            'signature_type' => 'nullable|in:physical,digital',
            'description' => 'nullable|string',
        ]);

        $filePath = $request->file('file')->store('user-documents', 'private');

        UserDocument::create([
            'it_user_id' => $itUser->id,
            'document_type' => $validated['document_type'],
            'document_name' => $validated['document_name'],
            'file_path' => $filePath,
            'has_signature' => $request->boolean('has_signature'),
            'signature_type' => $validated['signature_type'],
            'description' => $validated['description'],
        ]);

        return redirect()->back()->with('success', 'Documento subido exitosamente.');
    }
}
