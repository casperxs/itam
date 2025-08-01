@extends('layouts.app')

@section('title', 'Detalle de Equipo')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Detalle de Equipo</h2>
            <div class="flex gap-2">
                <a href="{{ route('equipment.edit', $equipment) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    Editar
                </a>
                <a href="{{ route('equipment.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                    Volver
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Información Principal -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Información Principal</h3>
                
                <div class="space-y-4">
                    <div>
                        <strong class="text-gray-600">Número de Serie:</strong>
                        <p class="text-gray-800">{{ $equipment->serial_number }}</p>
                    </div>

                    @if($equipment->asset_tag)
                    <div>
                        <strong class="text-gray-600">Etiqueta de Activo:</strong>
                        <p class="text-gray-800">{{ $equipment->asset_tag }}</p>
                    </div>
                    @endif

                    <div>
                        <strong class="text-gray-600">Tipo de Equipo:</strong>
                        <p class="text-gray-800">{{ $equipment->equipmentType->name }}</p>
                    </div>

                    <div>
                        <strong class="text-gray-600">Proveedor:</strong>
                        <p class="text-gray-800">{{ $equipment->supplier->name }}</p>
                    </div>

                    <div>
                        <strong class="text-gray-600">Marca y Modelo:</strong>
                        <p class="text-gray-800">{{ $equipment->brand }} {{ $equipment->model }}</p>
                    </div>

                    <div>
                        <strong class="text-gray-600">Estado:</strong>
                        <span class="px-2 py-1 rounded-full text-sm font-medium
                            @if($equipment->status === 'available') bg-green-100 text-green-800
                            @elseif($equipment->status === 'assigned') bg-blue-100 text-blue-800
                            @elseif($equipment->status === 'maintenance') bg-yellow-100 text-yellow-800
                            @elseif($equipment->status === 'retired') bg-gray-100 text-gray-800
                            @elseif($equipment->status === 'lost') bg-red-100 text-red-800
                            @endif">
                            @switch($equipment->status)
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

            <!-- Información de Compra -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Información de Compra</h3>
                
                <div class="space-y-4">
                    @if($equipment->purchase_price)
                    <div>
                        <strong class="text-gray-600">Precio de Compra:</strong>
                        <p class="text-gray-800">${{ number_format($equipment->purchase_price, 2) }}</p>
                    </div>
                    @endif

                    @if($equipment->purchase_date)
                    <div>
                        <strong class="text-gray-600">Fecha de Compra:</strong>
                        <p class="text-gray-800">{{ $equipment->purchase_date ? $equipment->purchase_date->format('d/m/Y') : 'N/A' }}</p>
                    </div>
                    @endif

                    @if($equipment->warranty_end_date)
                    <div>
                        <strong class="text-gray-600">Fin de Garantía:</strong>
                        <p class="text-gray-800 {{ $equipment->warranty_end_date && $equipment->warranty_end_date->isPast() ? 'text-red-600 font-semibold' : '' }}">
                            {{ $equipment->warranty_end_date ? $equipment->warranty_end_date->format('d/m/Y') : 'N/A' }}
                            @if($equipment->warranty_end_date && $equipment->warranty_end_date->isPast())
                                (Expirada)
                            @endif
                        </p>
                    </div>
                    @endif

                    @if($equipment->invoice_number)
                    <div>
                        <strong class="text-gray-600">Número de Factura:</strong>
                        <p class="text-gray-800">{{ $equipment->invoice_number }}</p>
                    </div>
                    @endif

                    @if($equipment->invoice_file)
                    <div>
                        <strong class="text-gray-600">Factura:</strong>
                        <a href="{{ route('equipment.download-invoice', $equipment) }}" 
                           class="text-blue-600 hover:text-blue-800 underline">
                            Descargar Factura
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        @if($equipment->specifications)
        <div class="mt-8 bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Especificaciones</h3>
            <p class="text-gray-700 whitespace-pre-line">{{ $equipment->specifications }}</p>
        </div>
        @endif

        @if($equipment->observations)
        <div class="mt-8 bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Observaciones</h3>
            <p class="text-gray-700 whitespace-pre-line">{{ $equipment->observations }}</p>
        </div>
        @endif

        <!-- Asignación Actual -->
        @if($equipment->currentAssignment)
        <div class="mt-8 bg-blue-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Asignación Actual</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <strong class="text-gray-600">Usuario Asignado:</strong>
                    <p class="text-gray-800">{{ $equipment->currentAssignment->itUser->name }}</p>
                </div>
                <div>
                    <strong class="text-gray-600">Fecha de Asignación:</strong>
                    <p class="text-gray-800">{{ $equipment->currentAssignment->assignment_date ? $equipment->currentAssignment->assignment_date->format('d/m/Y') : 'N/A' }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Historial de Asignaciones -->
        @if($equipment->assignments->count() > 0)
        <div class="mt-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Historial de Asignaciones</h3>
            <div class="bg-white rounded-lg border overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Usuario
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha Asignación
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha Devolución
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($equipment->assignments->sortByDesc('assignment_date') as $assignment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $assignment->itUser->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $assignment->assignment_date ? $assignment->assignment_date->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $assignment->return_date ? $assignment->return_date->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    {{ $assignment->return_date ? 'bg-gray-100 text-gray-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $assignment->return_date ? 'Devuelto' : 'Activo' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Historial de Mantenimiento -->
        @if($equipment->maintenanceRecords->count() > 0)
        <div class="mt-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Historial de Mantenimiento</h3>
            <div class="bg-white rounded-lg border overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tipo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Descripción
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha Programada
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($equipment->maintenanceRecords->sortByDesc('scheduled_date') as $maintenance)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ ucfirst($maintenance->maintenance_type) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $maintenance->description }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $maintenance->scheduled_date ? $maintenance->scheduled_date->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    @if($maintenance->status === 'completed') bg-green-100 text-green-800
                                    @elseif($maintenance->status === 'in_progress') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    @switch($maintenance->status)
                                        @case('completed') Completado @break
                                        @case('in_progress') En Progreso @break
                                        @default Programado @break
                                    @endswitch
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection