@extends('layouts.app')

@section('title', 'Reportes - ITAM System')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Centro de Reportes</h1>
    <p class="text-gray-600 dark:text-gray-300">Genera reportes y análisis del sistema de gestión de activos</p>
</div>

<!-- Reportes Rápidos -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center mb-4">
            <div class="p-3 bg-blue-100 rounded-full">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Inventario de Equipos</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300">Reporte completo de todos los equipos</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('reports.equipment', ['format' => 'html']) }}" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded text-center hover:bg-blue-700">
                Ver HTML
            </a>
            <a href="{{ route('reports.equipment', ['format' => 'pdf']) }}" class="flex-1 bg-red-600 text-white px-4 py-2 rounded text-center hover:bg-red-700">
                PDF
            </a>
            <a href="{{ route('reports.equipment', ['format' => 'excel']) }}" class="flex-1 bg-green-600 text-white px-4 py-2 rounded text-center hover:bg-green-700">
                Excel
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center mb-4">
            <div class="p-3 bg-green-100 rounded-full">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Asignaciones Activas</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300">Equipos asignados actualmente</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('reports.assignments', ['format' => 'html']) }}" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded text-center hover:bg-blue-700">
                Ver HTML
            </a>
            <a href="{{ route('reports.assignments', ['format' => 'pdf']) }}" class="flex-1 bg-red-600 text-white px-4 py-2 rounded text-center hover:bg-red-700">
                PDF
            </a>
            <a href="{{ route('reports.assignments', ['format' => 'excel']) }}" class="flex-1 bg-green-600 text-white px-4 py-2 rounded text-center hover:bg-green-700">
                Excel
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center mb-4">
            <div class="p-3 bg-yellow-100 rounded-full">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Mantenimientos</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300">Historial y programación</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('reports.maintenance', ['format' => 'html']) }}" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded text-center hover:bg-blue-700">
                Ver HTML
            </a>
            <a href="{{ route('reports.maintenance', ['format' => 'pdf']) }}" class="flex-1 bg-red-600 text-white px-4 py-2 rounded text-center hover:bg-red-700">
                PDF
            </a>
            <a href="{{ route('reports.maintenance', ['format' => 'excel']) }}" class="flex-1 bg-green-600 text-white px-4 py-2 rounded text-center hover:bg-green-700">
                Excel
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center mb-4">
            <div class="p-3 bg-purple-100 rounded-full">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Contratos</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300">Estados y vencimientos</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('reports.contracts', ['format' => 'html']) }}" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded text-center hover:bg-blue-700">
                Ver HTML
            </a>
            <a href="{{ route('reports.contracts', ['format' => 'pdf']) }}" class="flex-1 bg-red-600 text-white px-4 py-2 rounded text-center hover:bg-red-700">
                PDF
            </a>
            <a href="{{ route('reports.contracts', ['format' => 'excel']) }}" class="flex-1 bg-green-600 text-white px-4 py-2 rounded text-center hover:bg-green-700">
                Excel
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center mb-4">
            <div class="p-3 bg-indigo-100 rounded-full">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Dashboard Ejecutivo</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300">Métricas generales del sistema</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('reports.dashboard', ['format' => 'html']) }}" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded text-center hover:bg-blue-700">
                Ver HTML
            </a>
            <a href="{{ route('reports.dashboard', ['format' => 'pdf']) }}" class="flex-1 bg-red-600 text-white px-4 py-2 rounded text-center hover:bg-red-700">
                PDF
            </a>
        </div>
    </div>
</div>

<!-- Reportes Personalizados -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-8">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Reportes Personalizados</h2>
        <p class="text-sm text-gray-600 dark:text-gray-300">Genera reportes con filtros específicos</p>
    </div>
    <div class="px-6 py-4">
        <form method="GET" action="{{ route('reports.equipment') }}" class="space-y-4" id="customReportForm">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo de Reporte</label>
                    <select name="report_type" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="equipment">Inventario de Equipos</option>
                        <option value="assignments">Asignaciones</option>
                        <option value="maintenance">Mantenimientos</option>
                        <option value="contracts">Contratos</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha Desde</label>
                    <input type="date" name="date_from" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha Hasta</label>
                    <input type="date" name="date_to" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos</option>
                        <option value="active">Activo</option>
                        <option value="inactive">Inactivo</option>
                        <option value="maintenance">Mantenimiento</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Departamento</label>
                    <select name="department" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos</option>
                        <option value="TI">TI</option>
                        <option value="Contabilidad">Contabilidad</option>
                        <option value="Recursos Humanos">Recursos Humanos</option>
                        <option value="Ventas">Ventas</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Formato</label>
                    <select name="format" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="html">HTML</option>
                        <option value="pdf">PDF</option>
                        <option value="excel">Excel</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                    Generar Reporte
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Estadísticas Rápidas -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-full">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Total Equipos</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalEquipment ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-full">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Asignaciones Activas</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $activeAssignments ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-100 rounded-full">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Mantenimientos Pendientes</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $pendingMaintenance ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-red-100 rounded-full">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.728-.833-2.498 0L4.616 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Contratos Vencidos</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $expiredContracts ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('customReportForm');
    const reportTypeSelect = form.querySelector('select[name="report_type"]');
    
    reportTypeSelect.addEventListener('change', function() {
        const reportType = this.value;
        const routes = {
            'equipment': '{{ route("reports.equipment") }}',
            'assignments': '{{ route("reports.assignments") }}',
            'maintenance': '{{ route("reports.maintenance") }}', 
            'contracts': '{{ route("reports.contracts") }}'
        };
        
        if (routes[reportType]) {
            form.action = routes[reportType];
        }
    });
});
</script>
@endsection
