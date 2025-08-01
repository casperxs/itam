@extends('layouts.app')

@section('title', 'Detalle de Asignación')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Detalle de Asignación</h2>
            <div class="flex gap-2">
                @if(!$assignment->returned_at)
                    <a href="{{ route('assignments.return', $assignment) }}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                        Devolver Equipo
                    </a>
                @endif
                
                @if($assignment->assignment_document)
                    <a href="{{ route('assignments.download', $assignment) }}" 
                       class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md">
                        Descargar Documento
                    </a>
                @endif

                @if(!$assignment->document_signed && !$assignment->returned_at)
                    <form method="POST" action="{{ route('assignments.mark-signed', $assignment) }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                            Marcar como Firmado
                        </button>
                    </form>
                @endif
                
                <a href="{{ route('assignments.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                    Volver
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Información del Equipo -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Información del Equipo</h3>
                
                <div class="space-y-4">
                    <div>
                        <strong class="text-gray-600">Marca y Modelo:</strong>
                        <p class="text-gray-800">{{ $assignment->equipment->brand }} {{ $assignment->equipment->model }}</p>
                    </div>

                    <div>
                        <strong class="text-gray-600">Número de Serie:</strong>
                        <p class="text-gray-800">{{ $assignment->equipment->serial_number }}</p>
                    </div>

                    @if($assignment->equipment->asset_tag)
                    <div>
                        <strong class="text-gray-600">Etiqueta de Activo:</strong>
                        <p class="text-gray-800">{{ $assignment->equipment->asset_tag }}</p>
                    </div>
                    @endif

                    <div>
                        <strong class="text-gray-600">Tipo de Equipo:</strong>
                        <p class="text-gray-800">{{ $assignment->equipment->equipmentType->name }}</p>
                    </div>

                    <div>
                        <strong class="text-gray-600">Estado del Equipo:</strong>
                        <span class="px-2 py-1 rounded-full text-sm font-medium
                            @if($assignment->equipment->status === 'available') bg-green-100 text-green-800
                            @elseif($assignment->equipment->status === 'assigned') bg-blue-100 text-blue-800
                            @elseif($assignment->equipment->status === 'maintenance') bg-yellow-100 text-yellow-800
                            @elseif($assignment->equipment->status === 'retired') bg-gray-100 text-gray-800
                            @elseif($assignment->equipment->status === 'lost') bg-red-100 text-red-800
                            @endif">
                            @switch($assignment->equipment->status)
                                @case('available') Disponible @break
                                @case('assigned') Asignado @break
                                @case('maintenance') En Mantenimiento @break
                                @case('retired') Retirado @break
                                @case('lost') Perdido @break
                            @endswitch
                        </span>
                    </div>
                </div>
            </div>

            <!-- Información del Usuario -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Información del Usuario</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center mb-4">
                        <div class="h-12 w-12 flex-shrink-0">
                            <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-blue-600 font-medium text-lg">
                                    {{ substr($assignment->itUser->name, 0, 2) }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-lg font-medium text-gray-900">{{ $assignment->itUser->name }}</div>
                            <div class="text-sm text-gray-500">{{ $assignment->itUser->email }}</div>
                        </div>
                    </div>

                    @if($assignment->itUser->employee_id)
                    <div>
                        <strong class="text-gray-600">ID Empleado:</strong>
                        <p class="text-gray-800">{{ $assignment->itUser->employee_id }}</p>
                    </div>
                    @endif

                    @if($assignment->itUser->department)
                    <div>
                        <strong class="text-gray-600">Departamento:</strong>
                        <p class="text-gray-800">{{ $assignment->itUser->department }}</p>
                    </div>
                    @endif

                    @if($assignment->itUser->position)
                    <div>
                        <strong class="text-gray-600">Cargo:</strong>
                        <p class="text-gray-800">{{ $assignment->itUser->position }}</p>
                    </div>
                    @endif

                    @if($assignment->itUser->phone)
                    <div>
                        <strong class="text-gray-600">Teléfono:</strong>
                        <p class="text-gray-800">{{ $assignment->itUser->phone }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Información de la Asignación -->
        <div class="mt-8 bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Detalles de la Asignación</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <strong class="text-gray-600">Fecha de Asignación:</strong>
                    <p class="text-gray-800">{{ $assignment->assigned_at->format('d/m/Y H:i') }}</p>
                </div>

                @if($assignment->returned_at)
                <div>
                    <strong class="text-gray-600">Fecha de Devolución:</strong>
                    <p class="text-gray-800">{{ $assignment->returned_at->format('d/m/Y H:i') }}</p>
                </div>
                @endif

                <div>
                    <strong class="text-gray-600">Asignado por:</strong>
                    <p class="text-gray-800">{{ $assignment->assignedBy->name ?? 'N/A' }}</p>
                </div>

                <div>
                    <strong class="text-gray-600">Estado de la Asignación:</strong>
                    @if($assignment->returned_at)
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                            Devuelto
                        </span>
                    @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Activo
                        </span>
                        @if($assignment->document_signed)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 ml-1">
                                Firmado
                            </span>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        @if($assignment->assignment_notes)
        <div class="mt-8 bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Notas de Asignación</h3>
            <p class="text-gray-700 whitespace-pre-line">{{ $assignment->assignment_notes }}</p>
        </div>
        @endif

        @if($assignment->return_notes)
        <div class="mt-8 bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Notas de Devolución</h3>
            <p class="text-gray-700 whitespace-pre-line">{{ $assignment->return_notes }}</p>
        </div>
        @endif
    </div>
</div>
@endsection