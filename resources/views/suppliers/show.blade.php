@extends('layouts.app')

@section('title', 'Detalle de Proveedor')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Detalle de Proveedor</h2>
            <div class="flex gap-2">
                <a href="{{ route('suppliers.edit', $supplier) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    Editar
                </a>
                <a href="{{ route('suppliers.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                    Volver
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Información Principal -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Información de la Empresa</h3>
                
                <div class="space-y-4">
                    <div>
                        <strong class="text-gray-600">Nombre:</strong>
                        <p class="text-gray-800">{{ $supplier->name }}</p>
                    </div>

                    @if($supplier->contact_name)
                    <div>
                        <strong class="text-gray-600">Contacto Principal:</strong>
                        <p class="text-gray-800">{{ $supplier->contact_name }}</p>
                    </div>
                    @endif

                    @if($supplier->tax_id)
                    <div>
                        <strong class="text-gray-600">RFC / NIT:</strong>
                        <p class="text-gray-800">{{ $supplier->tax_id }}</p>
                    </div>
                    @endif

                    @if($supplier->address)
                    <div>
                        <strong class="text-gray-600">Dirección:</strong>
                        <p class="text-gray-800 whitespace-pre-line">{{ $supplier->address }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Información de Contacto -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Información de Contacto</h3>
                
                <div class="space-y-4">
                    @if($supplier->email)
                    <div>
                        <strong class="text-gray-600">Email:</strong>
                        <p class="text-gray-800">
                            <a href="mailto:{{ $supplier->email }}" class="text-blue-600 hover:text-blue-800">
                                {{ $supplier->email }}
                            </a>
                        </p>
                    </div>
                    @endif

                    @if($supplier->phone)
                    <div>
                        <strong class="text-gray-600">Teléfono:</strong>
                        <p class="text-gray-800">
                            <a href="tel:{{ $supplier->phone }}" class="text-blue-600 hover:text-blue-800">
                                {{ $supplier->phone }}
                            </a>
                        </p>
                    </div>
                    @endif

                    @if(!$supplier->email && !$supplier->phone)
                    <div class="text-gray-500 italic">
                        No hay información de contacto disponible
                    </div>
                    @endif

                    <!-- Estadísticas rápidas -->
                    <div class="pt-4 border-t border-gray-200">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600">{{ $supplier->equipment->count() }}</div>
                                <div class="text-sm text-gray-600">Equipos</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ $supplier->contracts->count() }}</div>
                                <div class="text-sm text-gray-600">Contratos</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($supplier->notes)
        <div class="mt-8 bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Notas Adicionales</h3>
            <p class="text-gray-700 whitespace-pre-line">{{ $supplier->notes }}</p>
        </div>
        @endif

        <!-- Equipos del Proveedor -->
        @if($supplier->equipment->count() > 0)
        <div class="mt-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Equipos Suministrados</h3>
            <div class="bg-white rounded-lg border overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Equipo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tipo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Número de Serie
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Precio
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($supplier->equipment->sortBy('brand') as $equipment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $equipment->brand }} {{ $equipment->model }}</div>
                                @if($equipment->asset_tag)
                                <div class="text-sm text-gray-500">Tag: {{ $equipment->asset_tag }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $equipment->equipmentType->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $equipment->serial_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
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
                                        @case('maintenance') Mantenimiento @break
                                        @case('retired') Retirado @break
                                        @case('lost') Perdido @break
                                    @endswitch
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($equipment->purchase_price)
                                    ${{ number_format($equipment->purchase_price, 2) }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('equipment.show', $equipment) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    Ver Detalle
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Contratos del Proveedor -->
        @if($supplier->contracts->count() > 0)
        <div class="mt-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Contratos</h3>
            <div class="bg-white rounded-lg border overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nombre del Contrato
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tipo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Inicio
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fin
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Valor
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($supplier->contracts->sortByDesc('start_date') as $contract)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $contract->contract_name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ ucfirst($contract->contract_type) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $contract->start_date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $contract->end_date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($contract->contract_value)
                                    ${{ number_format($contract->contract_value, 2) }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 rounded-full text-sm font-medium
                                    @if($contract->status === 'active') bg-green-100 text-green-800
                                    @elseif($contract->status === 'expired') bg-red-100 text-red-800
                                    @elseif($contract->status === 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    @switch($contract->status)
                                        @case('active') Activo @break
                                        @case('expired') Expirado @break
                                        @case('pending') Pendiente @break
                                        @case('cancelled') Cancelado @break
                                        @default {{ ucfirst($contract->status) }} @break
                                    @endswitch
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('contracts.show', $contract) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    Ver Detalle
                                </a>
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