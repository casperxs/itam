@extends('layouts.app')

@section('title', 'Contrato: ' . $contract->contract_number . ' - ITAM System')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Contrato: {{ $contract->contract_number }}</h1>
        <p class="text-gray-600">Detalles del contrato con {{ $contract->supplier->name }}</p>
    </div>
    <div class="flex space-x-3">
        @if($contract->contract_file)
            <a href="{{ route('contracts.download', $contract) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                Descargar PDF
            </a>
        @endif
        @if($contract->isExpired() || $contract->needsAlert())
            <a href="{{ route('contracts.renew', $contract) }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                Renovar
            </a>
        @endif
        <a href="{{ route('contracts.edit', $contract) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Editar
        </a>
        <a href="{{ route('contracts.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            Volver
        </a>
    </div>
</div>

<!-- Estado del Contrato -->
<div class="mb-6">
    @if($contract->isExpired())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium text-red-800">Este contrato ha vencido</span>
            </div>
        </div>
    @elseif($contract->needsAlert())
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.728-.833-2.498 0L4.616 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <span class="font-medium text-yellow-800">Este contrato vence pronto - {{ $contract->end_date->diffForHumans() }}</span>
            </div>
        </div>
    @else
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium text-green-800">Contrato activo</span>
            </div>
        </div>
    @endif
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Información Principal -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Información del Contrato</h3>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Número de Contrato</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $contract->contract_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Estado</dt>
                        <dd class="mt-1">
                            @if($contract->isExpired())
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Vencido
                                </span>
                            @elseif($contract->needsAlert())
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Por Vencer
                                </span>
                            @elseif($contract->status === 'active')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Activo
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ ucfirst($contract->status) }}
                                </span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Fecha de Inicio</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $contract->start_date ? $contract->start_date->format('d/m/Y') : 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Fecha de Fin</dt>
                        <dd class="mt-1 text-sm text-gray-900 {{ $contract->isExpired() ? 'text-red-600' : ($contract->needsAlert() ? 'text-yellow-600' : '') }}">
                            {{ $contract->end_date ? $contract->end_date->format('d/m/Y') : 'N/A' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Duración</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($contract->start_date && $contract->end_date)
                                {{ $contract->start_date->diffInDays($contract->end_date) }} días
                            @else
                                N/A
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Días de Alerta</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $contract->alert_days_before }} días antes</dd>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Descripción del Servicio</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $contract->service_description }}</dd>
                    </div>
                    @if($contract->notes)
                    <div class="md:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Notas</dt>
                        <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $contract->notes }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>

    <!-- Información del Proveedor y Costos -->
    <div class="space-y-6">
        <!-- Proveedor -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Proveedor</h3>
            </div>
            <div class="p-6">
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $contract->supplier->name }}</dd>
                    </div>
                    @if($contract->supplier->email)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <a href="mailto:{{ $contract->supplier->email }}" class="text-blue-600 hover:text-blue-900">
                                {{ $contract->supplier->email }}
                            </a>
                        </dd>
                    </div>
                    @endif
                    @if($contract->supplier->phone)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <a href="tel:{{ $contract->supplier->phone }}" class="text-blue-600 hover:text-blue-900">
                                {{ $contract->supplier->phone }}
                            </a>
                        </dd>
                    </div>
                    @endif
                    @if($contract->supplier->contact_person)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Persona de Contacto</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $contract->supplier->contact_person }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>

        <!-- Información de Costos -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Información de Costos</h3>
            </div>
            <div class="p-6">
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Costo Mensual</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">
                            @if($contract->monthly_cost)
                                ${{ number_format($contract->monthly_cost, 2) }}
                            @else
                                <span class="text-gray-500 text-sm">No especificado</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Costo Total</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">
                            @if($contract->total_cost)
                                ${{ number_format($contract->total_cost, 2) }}
                            @else
                                <span class="text-gray-500 text-sm">No especificado</span>
                            @endif
                        </dd>
                    </div>
                    @if($contract->monthly_cost && $contract->start_date && $contract->end_date)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Costo Estimado Total</dt>
                        <dd class="mt-1 text-sm text-gray-600">
                            ${{ number_format($contract->monthly_cost * $contract->start_date->diffInMonths($contract->end_date), 2) }}
                            <span class="text-xs text-gray-500">({{ $contract->start_date->diffInMonths($contract->end_date) }} meses)</span>
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>

        <!-- Archivo del Contrato -->
        @if($contract->contract_file)
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Archivo del Contrato</h3>
            </div>
            <div class="p-6 text-center">
                <svg class="w-12 h-12 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                <p class="text-sm text-gray-600 mb-4">Archivo PDF del contrato</p>
                <a href="{{ route('contracts.download', $contract) }}" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Descargar
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Metadatos -->
<div class="mt-8 bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Metadatos</h3>
    </div>
    <div class="p-6">
        <dl class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <dt class="text-sm font-medium text-gray-500">Creado</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $contract->created_at ? $contract->created_at->format('d/m/Y H:i') : 'N/A' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Última Actualización</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $contract->updated_at ? $contract->updated_at->format('d/m/Y H:i') : 'N/A' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">ID del Contrato</dt>
                <dd class="mt-1 text-sm text-gray-900">#{{ $contract->id }}</dd>
            </div>
        </dl>
    </div>
</div>
@endsection