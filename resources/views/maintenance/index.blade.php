@extends('layouts.app')

@section('title', 'Mantenimiento - ITAM System')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">Mantenimiento de Equipos</h1>
        <p class="text-gray-600 dark:text-gray-400">Gestión de mantenimientos programados y ejecutados</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('maintenance.completed') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 font-semibold">
            📋 MANTENIMIENTOS COMPLETADOS
        </a>
        <a href="{{ route('maintenance.calendar') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
            Ver Calendario
        </a>
        <a href="{{ route('maintenance.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Programar Mantenimiento
        </a>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <form method="GET" class="flex gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar</label>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Equipo, técnico, marca, serie, usuario asignado..."
                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                <select name="status" class="px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Programado</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>En Progreso</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completado</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo</label>
                <select name="type" class="px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="preventive" {{ request('type') === 'preventive' ? 'selected' : '' }}>Preventivo</option>
                    <option value="corrective" {{ request('type') === 'corrective' ? 'selected' : '' }}>Correctivo</option>
                    <option value="update" {{ request('type') === 'update' ? 'selected' : '' }}>Actualización</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha</label>
                <input
                    type="date"
                    name="date"
                    value="{{ request('date') }}"
                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>
            <button type="submit" class="bg-gray-600 dark:bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-700 dark:hover:bg-gray-600">
                Filtrar
            </button>
            <a href="{{ route('maintenance.index') }}" class="bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500">
                Limpiar
            </a>
        </form>
    </div>

    <!-- Loading indicator -->
    <div id="loading-indicator" class="hidden px-6 py-4 text-center">
        <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-blue-500 bg-blue-100 dark:bg-blue-900 dark:text-blue-200 transition ease-in-out duration-150">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Buscando...
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Equipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha Programada</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Técnico</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Costo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody id="maintenance-table-body" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @include('maintenance.partials.table-rows')
            </tbody>
        </table>
    </div>

    @if(isset($maintenanceRecords) && $maintenanceRecords->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $maintenanceRecords->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<!-- Próximos mantenimientos -->
<div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Próximos Mantenimientos (7 días)</h3>
    </div>
    <div class="px-6 py-4">
        @if(isset($upcomingMaintenance) && $upcomingMaintenance->count() > 0)
            <div class="space-y-3">
                @foreach($upcomingMaintenance as $maintenance)
                    <div class="flex items-center justify-between p-4 bg-yellow-50 dark:bg-yellow-900 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-gray-100">
                                {{ $maintenance->equipment->brand }} {{ $maintenance->equipment->model }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $maintenance->description }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $maintenance->scheduled_date->format('d/m/Y H:i') }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $maintenance->performedBy->name ?? 'Sin asignar' }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 dark:text-gray-400 text-center py-4">No hay mantenimientos programados para los próximos 7 días</p>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="search"]');
    const statusSelect = document.querySelector('select[name="status"]');
    const typeSelect = document.querySelector('select[name="type"]');
    const dateInput = document.querySelector('input[name="date"]');
    const tableBody = document.getElementById('maintenance-table-body');
    const loadingIndicator = document.getElementById('loading-indicator');
    let searchTimeout;

    // Función para realizar la búsqueda AJAX
    function performSearch() {
        const formData = new FormData();
        formData.append('search', searchInput.value);
        formData.append('status', statusSelect.value);
        formData.append('type', typeSelect.value);
        formData.append('date', dateInput.value);

        // Mostrar indicador de carga
        loadingIndicator.classList.remove('hidden');
        tableBody.style.opacity = '0.6';

        fetch('{{ route("api.maintenance.search") }}?' + new URLSearchParams(formData), {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            // Actualizar el contenido de la tabla
            tableBody.innerHTML = data.html;
            
            // Ocultar indicador de carga
            loadingIndicator.classList.add('hidden');
            tableBody.style.opacity = '1';

            // TODO: Actualizar paginación si es necesario
            // console.log('Total encontrados:', data.count);
        })
        .catch(error => {
            console.error('Error en la búsqueda:', error);
            loadingIndicator.classList.add('hidden');
            tableBody.style.opacity = '1';
        });
    }

    // Event listeners para búsqueda en tiempo real
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performSearch, 500); // Esperar 500ms después de que el usuario deje de escribir
    });

    // Event listeners para los filtros (cambio inmediato)
    statusSelect.addEventListener('change', performSearch);
    typeSelect.addEventListener('change', performSearch);
    dateInput.addEventListener('change', performSearch);

    // Prevenir el envío del formulario tradicional cuando se hace clic en "Filtrar"
    const filterButton = document.querySelector('button[type="submit"]');
    filterButton.addEventListener('click', function(e) {
        e.preventDefault();
        performSearch();
    });
});
</script>
@endsection
