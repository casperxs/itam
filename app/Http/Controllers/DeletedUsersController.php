<?php

namespace App\Http\Controllers;

use App\Models\DeletedUserHistory;
use App\Models\ItUser;
use Illuminate\Http\Request;

class DeletedUsersController extends Controller
{
    public function index(Request $request)
    {
        $query = DeletedUserHistory::query();

        // Filtros
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department')) {
            $query->where('department', $request->get('department'));
        }

        if ($request->filled('deleted_reason')) {
            $query->where('deleted_reason', 'like', "%{$request->get('deleted_reason')}%");
        }

        $deletedUsers = $query->orderBy('deleted_at', 'desc')->paginate(15);

        // Obtener departamentos únicos para el filtro
        $departments = DeletedUserHistory::distinct()
            ->whereNotNull('department')
            ->pluck('department')
            ->sort();

        return view('deleted-users.index', compact('deletedUsers', 'departments'));
    }

    public function show(DeletedUserHistory $deletedUser)
    {
        // Obtener assignments históricos de este usuario
        $assignments = \App\Models\Assignment::where('user_employee_id', $deletedUser->employee_id)
            ->orWhere('user_email', $deletedUser->email)
            ->orWhere('user_name', $deletedUser->name)
            ->with('equipment')
            ->orderBy('assigned_at', 'desc')
            ->get();

        return view('deleted-users.show', compact('deletedUser', 'assignments'));
    }

    public function restore(DeletedUserHistory $deletedUser)
    {
        // Buscar si el usuario aún existe en la tabla principal
        $existingUser = ItUser::where('id', $deletedUser->original_user_id)->first();
        
        if ($existingUser) {
            // Restaurar usuario existente
            $existingUser->restore();
            
            return redirect()->route('deleted-users.index')
                ->with('success', 'Usuario restaurado exitosamente.');
        } else {
            // Crear nuevo usuario con los datos históricos
            $newUser = ItUser::create([
                'name' => $deletedUser->name,
                'email' => $deletedUser->email,
                'employee_id' => $deletedUser->employee_id,
                'department' => $deletedUser->department,
                'position' => $deletedUser->position,
                'status' => $deletedUser->status ?? 'active',
                'notes' => $deletedUser->notes . "\n\nRestaurado el " . now()->format('Y-m-d H:i:s'),
            ]);

            return redirect()->route('deleted-users.index')
                ->with('success', 'Usuario recreado exitosamente con ID: ' . $newUser->id);
        }
    }

    public function export(Request $request)
    {
        $query = DeletedUserHistory::query();

        // Aplicar los mismos filtros que en index
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department')) {
            $query->where('department', $request->get('department'));
        }

        if ($request->filled('deleted_reason')) {
            $query->where('deleted_reason', 'like', "%{$request->get('deleted_reason')}%");
        }

        $deletedUsers = $query->orderBy('deleted_at', 'desc')->get();

        $filename = 'usuarios_eliminados_' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($deletedUsers) {
            $handle = fopen('php://output', 'w');
            
            // Headers CSV
            fputcsv($handle, [
                'ID Original',
                'Nombre',
                'Email',
                'ID Empleado',
                'Departamento',
                'Posición',
                'Estado',
                'Fecha Eliminación',
                'Razón Eliminación',
                'Notas'
            ]);

            // Datos
            foreach ($deletedUsers as $user) {
                fputcsv($handle, [
                    $user->original_user_id,
                    $user->name,
                    $user->email,
                    $user->employee_id,
                    $user->department,
                    $user->position,
                    $user->status,
                    $user->deleted_at?->format('Y-m-d H:i:s'),
                    $user->deleted_reason,
                    $user->notes
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}