@extends('layouts.app')

@section('title', 'Devolver Equipo - ITAM System')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Devolver Equipo</h1>
            <p class="text-gray-600">Registrar la devolución del equipo asignado</p>
        </div>
        <a href="{{ route('assignments.show', $assignment) }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            Volver al Detalle
        </a>
    </div>
</div>

<!-- Información de la Asignación Actual -->
<div class="bg-blue-50 rounded-lg p-6 mb-6">
    <h3 class="text-lg font-medium text-blue-900 mb-4">Información de la Asignación</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h4 class="text-sm font-medium text-gray-700 mb-2">Equipo</h4>
            <div class="bg-white rounded-lg border border-blue-200 p-4">
                <div class="font-medium text-gray-900">{{ $assignment->equipment->brand }} {{ $assignment->equipment->model }}</div>
                <div class="text-sm text-gray-600">S/N: {{ $assignment->equipment->serial_number }}</div>
                @if($assignment->equipment->asset_tag)
                    <div class="text-sm text-gray-600">Tag: {{ $assignment->equipment->asset_tag }}</div>
                @endif
                <div class="text-sm text-gray-600">Tipo: {{ $assignment->equipment->equipmentType->name }}</div>
            </div>
        </div>
        
        <div>
            <h4 class="text-sm font-medium text-gray-700 mb-2">Usuario</h4>
            <div class="bg-white rounded-lg border border-blue-200 p-4">
                <div class="flex items-center">
                    <div class="h-10 w-10 flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-blue-600 font-medium text-sm">
                                {{ substr($assignment->itUser->name, 0, 2) }}
                            </span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <div class="font-medium text-gray-900">{{ $assignment->itUser->name }}</div>
                        <div class="text-sm text-gray-600">{{ $assignment->itUser->email }}</div>
                        @if($assignment->itUser->department)
                            <div class="text-sm text-gray-600">{{ $assignment->itUser->department }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <span class="text-sm font-medium text-gray-700">Fecha de Asignación:</span>
            <div class="text-gray-900">{{ $assignment->assigned_at ? $assignment->assigned_at->format('d/m/Y H:i') : 'N/A' }}</div>
        </div>
        
        <div>
            <span class="text-sm font-medium text-gray-700">Asignado por:</span>
            <div class="text-gray-900">{{ $assignment->assignedBy->name ?? 'N/A' }}</div>
        </div>
        
        <div>
            <span class="text-sm font-medium text-gray-700">Estado del Documento:</span>
            <div>
                @if($assignment->document_signed)
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                        Firmado
                    </span>
                @else
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        Pendiente
                    </span>
                @endif
            </div>
        </div>
    </div>
    
    @if($assignment->assignment_notes)
        <div class="mt-4">
            <span class="text-sm font-medium text-gray-700">Notas de Asignación:</span>
            <div class="mt-1 text-gray-900 bg-white rounded-lg border border-blue-200 p-3">
                {{ $assignment->assignment_notes }}
            </div>
        </div>
    @endif
</div>

<!-- Formulario de Devolución -->
<div class="bg-white rounded-lg shadow">
    <form method="POST" action="{{ route('assignments.process-return', $assignment) }}" class="p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="returned_at" class="block text-sm font-medium text-gray-700 mb-2">Fecha y Hora de Devolución *</label>
                <input
                    type="datetime-local"
                    id="returned_at"
                    name="returned_at"
                    value="{{ old('returned_at', now()->format('Y-m-d\TH:i')) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('returned_at') border-red-500 @enderror"
                    required
                >
                @error('returned_at')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6">
            <label for="return_notes" class="block text-sm font-medium text-gray-700 mb-2">Notas de Devolución</label>
            <textarea
                id="return_notes"
                name="return_notes"
                rows="4"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('return_notes') border-red-500 @enderror"
                placeholder="Estado del equipo al momento de la devolución, accesorios devueltos, daños identificados, etc."
            >{{ old('return_notes') }}</textarea>
            @error('return_notes')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Checklist de Devolución -->
        <div class="mt-8 bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Checklist de Devolución</h3>
            <div class="space-y-3">
                <label class="flex items-center">
                    <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">El equipo se encuentra en buenas condiciones físicas</span>
                </label>
                
                <label class="flex items-center">
                    <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">Se han devuelto todos los accesorios originales</span>
                </label>
                
                <label class="flex items-center">
                    <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">Los datos del usuario han sido eliminados del equipo</span>
                </label>
                
                <label class="flex items-center">
                    <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">El equipo funciona correctamente</span>
                </label>
                
                <label class="flex items-center">
                    <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">Se ha completado el documento de devolución</span>
                </label>
            </div>
            
            <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">
                            Importante
                        </h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Una vez procesada la devolución, el equipo estará disponible para una nueva asignación. Asegúrese de que todos los elementos del checklist han sido verificados.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('assignments.show', $assignment) }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400">
                Cancelar
            </a>
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700">
                Procesar Devolución
            </button>
        </div>
    </form>
</div>
@endsection