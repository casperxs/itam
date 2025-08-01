@extends('layouts.app')

@section('title', 'Dashboard Ejecutivo - ITAM System')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Dashboard Ejecutivo</h1>
        <p class="text-gray-600">Métricas generales del sistema ITAM</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('reports.dashboard', array_merge(request()->query(), ['export' => 'pdf'])) }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
            Exportar PDF
        </a>
        <a href="{{ route('reports.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            Volver a Reportes
        </a>
    </div>
</div>

<!-- Equipos por Estado -->
@if(isset($data['equipment_by_status']) && $data['equipment_by_status']->count() > 0)
<div class="bg-white rounded-lg shadow mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Equipos por Estado</h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($data['equipment_by_status'] as $status)
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">{{ ucfirst($status->status) }}</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $status->count }}</p>
                        </div>
                        <div class="p-2 rounded-full
                            @if($status->status === 'active') bg-green-100
                            @elseif($status->status === 'inactive') bg-gray-100
                            @elseif($status->status === 'maintenance') bg-yellow-100
                            @elseif($status->status === 'retired') bg-red-100
                            @else bg-blue-100
                            @endif">
                            <svg class="w-6 h-6 
                                @if($status->status === 'active') text-green-600
                                @elseif($status->status === 'inactive') text-gray-600
                                @elseif($status->status === 'maintenance') text-yellow-600
                                @elseif($status->status === 'retired') text-red-600
                                @else text-blue-600
                                @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Equipos por Tipo -->
@if(isset($data['equipment_by_type']) && $data['equipment_by_type']->count() > 0)
<div class="bg-white rounded-lg shadow mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Equipos por Tipo</h3>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-2 text-sm font-medium text-gray-500">Tipo</th>
                        <th class="text-right py-2 text-sm font-medium text-gray-500">Cantidad</th>
                        <th class="text-right py-2 text-sm font-medium text-gray-500">Porcentaje</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php
                        $total = $data['equipment_by_type']->sum('count');
                    @endphp
                    @foreach($data['equipment_by_type'] as $type)
                        <tr>
                            <td class="py-3 text-sm text-gray-900">{{ $type->name }}</td>
                            <td class="py-3 text-sm text-gray-900 text-right">{{ $type->count }}</td>
                            <td class="py-3 text-sm text-gray-500 text-right">
                                {{ $total > 0 ? number_format(($type->count / $total) * 100, 1) : 0 }}%
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Asignaciones por Mes -->
@if(isset($data['assignments_by_month']) && $data['assignments_by_month']->count() > 0)
<div class="bg-white rounded-lg shadow mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Asignaciones por Mes (Último Año)</h3>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-2 text-sm font-medium text-gray-500">Mes</th>
                        <th class="text-right py-2 text-sm font-medium text-gray-500">Asignaciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($data['assignments_by_month'] as $assignment)
                        <tr>
                            <td class="py-3 text-sm text-gray-900">
                                {{ DateTime::createFromFormat('!m', $assignment->month)->format('F') }} {{ $assignment->year }}
                            </td>
                            <td class="py-3 text-sm text-gray-900 text-right">{{ $assignment->count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Mantenimientos por Tipo -->
@if(isset($data['maintenance_by_type']) && $data['maintenance_by_type']->count() > 0)
<div class="bg-white rounded-lg shadow mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Mantenimientos por Tipo</h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($data['maintenance_by_type'] as $maintenance)
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">
                                @switch($maintenance->type)
                                    @case('preventive')
                                        Preventivo
                                        @break
                                    @case('corrective')
                                        Correctivo
                                        @break
                                    @case('update')
                                        Actualización
                                        @break
                                    @default
                                        {{ ucfirst($maintenance->type) }}
                                @endswitch
                            </p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $maintenance->count }}</p>
                        </div>
                        <div class="p-2 rounded-full
                            @if($maintenance->type === 'preventive') bg-blue-100
                            @elseif($maintenance->type === 'corrective') bg-red-100
                            @elseif($maintenance->type === 'update') bg-purple-100
                            @else bg-gray-100
                            @endif">
                            <svg class="w-6 h-6 
                                @if($maintenance->type === 'preventive') text-blue-600
                                @elseif($maintenance->type === 'corrective') text-red-600
                                @elseif($maintenance->type === 'update') text-purple-600
                                @else text-gray-600
                                @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Top Usuarios por Asignaciones -->
@if(isset($data['top_users_by_assignments']) && $data['top_users_by_assignments']->count() > 0)
<div class="bg-white rounded-lg shadow mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Top 10 Usuarios por Asignaciones</h3>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-2 text-sm font-medium text-gray-500">Usuario</th>
                        <th class="text-left py-2 text-sm font-medium text-gray-500">Departamento</th>
                        <th class="text-left py-2 text-sm font-medium text-gray-500">Email</th>
                        <th class="text-right py-2 text-sm font-medium text-gray-500">Total Asignaciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($data['top_users_by_assignments'] as $user)
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-900">{{ $user->name }}</td>
                            <td class="py-3 text-sm text-gray-600">{{ $user->department ?? 'N/A' }}</td>
                            <td class="py-3 text-sm text-gray-600">{{ $user->email ?? 'N/A' }}</td>
                            <td class="py-3 text-sm text-gray-900 text-right">{{ $user->assignments_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Resumen General -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Alertas y Notificaciones -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Alertas del Sistema</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @php
                    $totalEquipment = collect($data['equipment_by_status'] ?? [])->sum('count');
                    $activeEquipment = collect($data['equipment_by_status'] ?? [])->where('status', 'active')->sum('count');
                    $maintenanceEquipment = collect($data['equipment_by_status'] ?? [])->where('status', 'maintenance')->sum('count');
                    $totalMaintenance = collect($data['maintenance_by_type'] ?? [])->sum('count');
                @endphp
                
                <div class="flex items-center p-3 bg-blue-50 rounded-lg">
                    <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-blue-800">Total de Equipos</p>
                        <p class="text-sm text-blue-600">{{ $totalEquipment }} equipos registrados</p>
                    </div>
                </div>

                @if($maintenanceEquipment > 0)
                <div class="flex items-center p-3 bg-yellow-50 rounded-lg">
                    <svg class="w-5 h-5 text-yellow-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.728-.833-2.498 0L4.616 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-yellow-800">Equipos en Mantenimiento</p>
                        <p class="text-sm text-yellow-600">{{ $maintenanceEquipment }} equipos requieren atención</p>
                    </div>
                </div>
                @endif

                <div class="flex items-center p-3 bg-green-50 rounded-lg">
                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-green-800">Equipos Activos</p>
                        <p class="text-sm text-green-600">{{ $activeEquipment }} equipos operativos</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas Rápidas -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Estadísticas Rápidas</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-600">Total Mantenimientos</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $totalMaintenance }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-600">Usuarios Activos</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $data['top_users_by_assignments']->count() ?? 0 }}</span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-600">Tipos de Equipo</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $data['equipment_by_type']->count() ?? 0 }}</span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-600">Asignaciones (Último Año)</span>
                    <span class="text-sm font-semibold text-gray-900">{{ collect($data['assignments_by_month'] ?? [])->sum('count') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Información del Reporte -->
<div class="mt-6 bg-gray-50 rounded-lg p-4">
    <div class="flex items-center text-sm text-gray-600">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>Dashboard generado el {{ now()->format('d/m/Y \a \l\a\s H:i') }}</span>
    </div>
</div>
@endsection