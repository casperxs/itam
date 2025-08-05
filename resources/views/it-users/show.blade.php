@extends('layouts.app')

@section('title', 'Ver Usuario - ITAM System')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $itUser->name }}</h1>
            <p class="text-gray-600">{{ $itUser->position }} - {{ $itUser->department }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('it-users.edit', $itUser) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Editar Usuario
            </a>
            <a href="{{ route('it-users.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                Volver al Listado
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Información del Usuario -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Información Personal</h2>
            </div>
            <div class="px-6 py-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo</label>
                        <p class="text-gray-900">{{ $itUser->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                        <p class="text-gray-900">{{ $itUser->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ID de Empleado</label>
                        <p class="text-gray-900">{{ $itUser->employee_id }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Departamento</label>
                        <p class="text-gray-900">{{ $itUser->department }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cargo</label>
                        <p class="text-gray-900">{{ $itUser->position }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $itUser->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $itUser->status === 'active' ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                </div>

                @if($itUser->notes)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                        <p class="text-gray-900 bg-gray-50 p-3 rounded-md">{{ $itUser->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Equipos Asignados Actualmente -->
        <div class="mt-6 bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Equipos Asignados Actualmente</h2>
            </div>
            <div class="px-6 py-4">
                @if($itUser->currentAssignments && $itUser->currentAssignments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Equipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Serie</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Asignado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($itUser->currentAssignments as $assignment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $assignment->equipment->equipmentType->name ?? 'N/A' }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $assignment->equipment->brand }} {{ $assignment->equipment->model }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $assignment->equipment->serial_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $assignment->assigned_at ? $assignment->assigned_at->format('d/m/Y') : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('assignments.show', $assignment) }}" class="text-blue-600 hover:text-blue-900">
                                                Ver Asignación
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <h3 class="text-gray-500 text-center py-4">No tiene equipos asignados actualmente</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Panel Lateral -->
    <div class="space-y-6">
        <!-- Estadísticas -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Estadísticas</h3>
            </div>
            <div class="px-6 py-4 space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-600">Equipos Asignados:</span>
                    <span class="font-semibold">{{ $itUser->current_assignments_count ?? 0 }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Asignaciones:</span>
                    <span class="font-semibold">{{ $itUser->assignments_count ?? 0 }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Documentos:</span>
                    <span class="font-semibold">{{ $itUser->documents_count ?? 0 }}</span>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Acciones Rápidas</h3>
            </div>
            <div class="px-6 py-4 space-y-3">
                <a href="{{ route('assignments.create', ['user_id' => $itUser->id]) }}" class="block w-full bg-blue-600 text-white text-center px-4 py-2 rounded-md hover:bg-blue-700">
                    Asignar Equipo
                </a>

                @if($itUser->currentAssignments && $itUser->currentAssignments->count() > 0)
                    <form action="{{ route('assignments.generate-consolidated', $itUser) }}" method="POST" style="width: 100%; margin-bottom: 12px;">
                        @csrf
                        <button type="submit" style="
                            background: linear-gradient(135deg, #f97316, #ea580c) !important;
                            color: #ffffff !important;
                            padding: 10px 18px !important;
                            border: none !important;
                            border-radius: 6px !important;
                            font-weight: 600 !important;
                            font-size: 12px !important;
                            cursor: pointer !important;
                            display: inline-flex !important;
                            align-items: center !important;
                            justify-content: center !important;
                            gap: 6px !important;
                            box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
                            transition: all 0.2s !important;
                            width: 100% !important;
                            box-sizing: border-box !important;
                        "
                        onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
                            📄 Generar Documento Consolidado
                        </button>
                    </form>

                    @if($itUser->currentAssignments->first() && $itUser->currentAssignments->first()->assignment_document)
                        <a href="{{ route('assignments.download-consolidated', $itUser) }}" style="
                            background: linear-gradient(135deg, #22c55e, #16a34a) !important;
                            color: #ffffff !important;
                            padding: 10px 18px !important;
                            border: none !important;
                            border-radius: 6px !important;
                            font-weight: 600 !important;
                            font-size: 12px !important;
                            text-decoration: none !important;
                            display: inline-flex !important;
                            align-items: center !important;
                            justify-content: center !important;
                            gap: 6px !important;
                            box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
                            transition: all 0.2s !important;
                            width: 100% !important;
                            box-sizing: border-box !important;
                            margin-bottom: 12px !important;
                        "
                        onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
                            ⬇️ Descargar PDF Consolidado
                        </a>
                    @endif
                @endif

                <a href="{{ route('it-users.documents', $itUser) }}" style="
                    background: linear-gradient(135deg, #059669, #047857) !important;
                    color: #ffffff !important;
                    padding: 10px 18px !important;
                    border: none !important;
                    border-radius: 6px !important;
                    font-weight: 600 !important;
                    font-size: 12px !important;
                    text-decoration: none !important;
                    display: inline-flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    gap: 6px !important;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
                    transition: all 0.2s !important;
                    width: 100% !important;
                    box-sizing: border-box !important;
                    margin-bottom: 12px !important;
                "
                onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
                    📁 Ver Documentos
                </a>

                @if($itUser->currentAssignments && $itUser->currentAssignments->count() > 0)
                    <a href="{{ route('assignments.generate-exit-document', $itUser) }}" style="
                        background: linear-gradient(135deg, #7c3aed, #6d28d9) !important;
                        color: #ffffff !important;
                        padding: 10px 18px !important;
                        border: none !important;
                        border-radius: 6px !important;
                        font-weight: 600 !important;
                        font-size: 12px !important;
                        text-decoration: none !important;
                        display: inline-flex !important;
                        align-items: center !important;
                        justify-content: center !important;
                        gap: 6px !important;
                        box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
                        transition: all 0.2s !important;
                        width: 100% !important;
                        box-sizing: border-box !important;
                    "
                    onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
                        📄 Documento de Salida
                    </a>
                @else
                    <span style="
                        background: linear-gradient(135deg, #9ca3af, #6b7280) !important;
                        color: #ffffff !important;
                        padding: 10px 18px !important;
                        border: none !important;
                        border-radius: 6px !important;
                        font-weight: 600 !important;
                        font-size: 12px !important;
                        display: inline-flex !important;
                        align-items: center !important;
                        justify-content: center !important;
                        gap: 6px !important;
                        box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
                        width: 100% !important;
                        box-sizing: border-box !important;
                        cursor: not-allowed !important;
                        opacity: 0.6 !important;
                    ">
                        📄 Documento de Salida
                    </span>
                @endif
            </div>
        </div>

        <!-- Información de Sistema -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Información del Sistema</h3>
            </div>
            <div class="px-6 py-4 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Creado:</span>
                    <span>{{ $itUser->created_at ? $itUser->created_at->format('d/m/Y') : 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Actualizado:</span>
                    <span>{{ $itUser->updated_at ? $itUser->updated_at->format('d/m/Y') : 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
