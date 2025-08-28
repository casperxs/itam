@extends('layouts.app')

@section('title', 'Equipos - ITAM System')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">Equipos</h1>
        <p class="text-gray-600 dark:text-gray-400">Gestión de equipos informáticos</p>
    </div>
    <a href="{{ route('equipment.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
        Nuevo Equipo
    </a>
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
                    placeholder="Equipo, usuario, marca, proveedor, tipo..."
                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                <select name="status" class="px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Disponible</option>
                    <option value="assigned" {{ request('status') === 'assigned' ? 'selected' : '' }}>Asignado</option>
                    <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Mantenimiento</option>
                    <option value="retired" {{ request('status') === 'retired' ? 'selected' : '' }}>Retirado</option>
                    <option value="lost" {{ request('status') === 'lost' ? 'selected' : '' }}>Perdido</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo</label>
                <select name="type" class="px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    @foreach($equipmentTypes as $type)
                        <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-gray-600 dark:bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-700 dark:hover:bg-gray-600">
                Filtrar
            </button>
            <a href="{{ route('equipment.index') }}" class="bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500">
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Valoración</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Asignado a</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Garantía</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody id="equipment-table-body" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @include('equipment.partials.table-rows')
            </tbody>
        </table>
    </div>

    @if($equipment->hasPages())
        <div id="pagination-container" class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $equipment->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="search"]');
    const statusSelect = document.querySelector('select[name="status"]');
    const typeSelect = document.querySelector('select[name="type"]');
    const tableBody = document.getElementById('equipment-table-body');
    const loadingIndicator = document.getElementById('loading-indicator');
    const paginationContainer = document.getElementById('pagination-container');
    let searchTimeout;

    // Función para realizar la búsqueda AJAX
    function performSearch() {
        const formData = new FormData();
        formData.append('search', searchInput.value);
        formData.append('status', statusSelect.value);
        formData.append('type', typeSelect.value);

        // Mostrar indicador de carga
        loadingIndicator.classList.remove('hidden');
        tableBody.style.opacity = '0.6';

        fetch('{{ route("api.equipment.ajax-search") }}?' + new URLSearchParams(formData), {
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
            
            // Actualizar paginación si existe
            if (paginationContainer && data.pagination) {
                paginationContainer.innerHTML = data.pagination;
            }
            
            // Ocultar indicador de carga
            loadingIndicator.classList.add('hidden');
            tableBody.style.opacity = '1';

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

    // Prevenir el envío del formulario tradicional cuando se hace clic en "Filtrar"
    const filterButton = document.querySelector('button[type="submit"]');
    filterButton.addEventListener('click', function(e) {
        e.preventDefault();
        performSearch();
    });
});
</script>
@endsection
