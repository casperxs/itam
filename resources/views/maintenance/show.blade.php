@extends('layouts.app')

@section('title', 'Detalle de Mantenimiento - ITAM System')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Detalle de Mantenimiento</h1>
            <p class="text-gray-600">{{ $maintenance->equipment->equipmentType->name ?? 'N/A' }} - {{ $maintenance->equipment->serial_number ?? 'N/A' }}</p>
        </div>
        <div class="flex space-x-2">
            @if($maintenance->status === 'completed')
                <a href="{{ route('maintenance.checklist', $maintenance) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    Descargar Checklist
                </a>
            @endif
            <a href="{{ route('maintenance.edit', $maintenance) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Editar
            </a>
            <a href="{{ route('maintenance.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                Volver al Listado
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Información del Mantenimiento -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Información del Mantenimiento</h2>
        </div>
        <div class="px-6 py-4">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Mantenimiento</label>
                    <span class="px-2 py-1 rounded-full text-sm font-medium
                        @if($maintenance->type === 'preventive') bg-blue-100 text-blue-800
                        @elseif($maintenance->type === 'corrective') bg-red-100 text-red-800
                        @elseif($maintenance->type === 'update') bg-purple-100 text-purple-800
                        @endif">
                        @switch($maintenance->type)
                            @case('preventive') Preventivo @break
                            @case('corrective') Correctivo @break
                            @case('update') Actualización @break
                        @endswitch
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <span class="px-2 py-1 rounded-full text-sm font-medium
                        @if($maintenance->status === 'scheduled') bg-gray-100 text-gray-800
                        @elseif($maintenance->status === 'in_progress') bg-yellow-100 text-yellow-800
                        @elseif($maintenance->status === 'completed') bg-green-100 text-green-800
                        @elseif($maintenance->status === 'cancelled') bg-red-100 text-red-800
                        @endif">
                        @switch($maintenance->status)
                            @case('scheduled') Programado @break
                            @case('in_progress') En Progreso @break
                            @case('completed') Completado @break
                            @case('cancelled') Cancelado @break
                        @endswitch
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Programada</label>
                    <p class="text-gray-900">{{ $maintenance->scheduled_date ? $maintenance->scheduled_date->format('d/m/Y H:i') : 'N/A' }}</p>
                </div>

                @if($maintenance->completed_date)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Finalización</label>
                    <p class="text-gray-900">{{ $maintenance->completed_date->format('d/m/Y H:i') }}</p>
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Realizado por</label>
                    <p class="text-gray-900">{{ $maintenance->performedBy->name ?? 'N/A' }}</p>
                </div>

                @if($maintenance->cost)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Costo</label>
                    <p class="text-gray-900">${{ number_format($maintenance->cost, 2) }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Información del Equipo -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Información del Equipo</h2>
        </div>
        <div class="px-6 py-4">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Equipo</label>
                    <p class="text-gray-900">{{ $maintenance->equipment->equipmentType->name ?? 'N/A' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Marca y Modelo</label>
                    <p class="text-gray-900">{{ $maintenance->equipment->brand ?? 'N/A' }} {{ $maintenance->equipment->model ?? 'N/A' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Número de Serie</label>
                    <p class="text-gray-900">{{ $maintenance->equipment->serial_number ?? 'N/A' }}</p>
                </div>

                @if($maintenance->equipment->asset_tag)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Etiqueta de Activo</label>
                    <p class="text-gray-900">{{ $maintenance->equipment->asset_tag }}</p>
                </div>
                @endif

                @if($maintenance->equipment->currentAssignment)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Usuario Asignado</label>
                    <p class="text-gray-900">{{ $maintenance->equipment->currentAssignment->itUser->name ?? 'N/A' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Departamento</label>
                    <p class="text-gray-900">{{ $maintenance->equipment->currentAssignment->itUser->department ?? 'N/A' }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Descripción -->
<div class="mt-6 bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-900">Descripción del Trabajo</h2>
    </div>
    <div class="px-6 py-4">
        <p class="text-gray-700 whitespace-pre-line">{{ $maintenance->description ?? 'Sin descripción' }}</p>
    </div>
</div>

@if($maintenance->performed_actions)
<!-- Acciones Realizadas -->
<div class="mt-6 bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-900">Acciones Realizadas</h2>
    </div>
    <div class="px-6 py-4">
        <p class="text-gray-700 whitespace-pre-line">{{ $maintenance->performed_actions }}</p>
    </div>
</div>
@endif

@if($maintenance->notes)
<!-- Notas Adicionales -->
<div class="mt-6 bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-900">Notas Adicionales</h2>
    </div>
    <div class="px-6 py-4">
        <p class="text-gray-700 whitespace-pre-line">{{ $maintenance->notes }}</p>
    </div>
</div>
@endif

<!-- Acciones -->
@if($maintenance->status !== 'completed' && $maintenance->status !== 'cancelled')
<div class="mt-6 bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-900">Acciones</h2>
    </div>
    <div class="px-6 py-4">
        <div class="flex space-x-4">
            @if($maintenance->status === 'scheduled')
                <form method="POST" action="{{ route('maintenance.start', $maintenance) }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700">
                        Iniciar Mantenimiento
                    </button>
                </form>
            @endif

            @if($maintenance->status === 'in_progress')
                <!-- Botón para completar mantenimiento - se puede expandir con un modal -->
                <button onclick="document.getElementById('completeModal').classList.remove('hidden')" 
                        class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                    Completar Mantenimiento
                </button>
            @endif
        </div>
    </div>
</div>

<!-- Modal para completar mantenimiento -->
@if($maintenance->status === 'in_progress')
<div id="completeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Completar Mantenimiento</h3>
                <button onclick="document.getElementById('completeModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form method="POST" action="{{ route('maintenance.complete', $maintenance) }}">
                @csrf
                
                <div class="mb-4">
                    <label for="completed_date" class="block text-sm font-medium text-gray-700 mb-2">Fecha de Finalización</label>
                    <input type="datetime-local" id="completed_date" name="completed_date" 
                           value="{{ now()->format('Y-m-d\TH:i') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div class="mb-4">
                    <label for="performed_actions" class="block text-sm font-medium text-gray-700 mb-2">Acciones Realizadas</label>
                    <textarea id="performed_actions" name="performed_actions" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Describe las acciones realizadas..." required></textarea>
                </div>
                
                <div class="mb-4">
                    <label for="cost" class="block text-sm font-medium text-gray-700 mb-2">Costo (opcional)</label>
                    <input type="number" id="cost" name="cost" step="0.01" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notas Adicionales</label>
                    <textarea id="notes" name="notes" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Notas adicionales..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('completeModal').classList.add('hidden')" 
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                        Completar Mantenimiento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endif
@endsection