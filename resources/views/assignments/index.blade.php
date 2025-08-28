@extends('layouts.app')

@section('title', 'Asignaciones - ITAM System')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Asignaciones de Equipos</h1>
        <p class="text-gray-600">Gestión de asignaciones de equipos a usuarios</p>
    </div>
    <a href="{{ route('assignments.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
        Nueva Asignación
    </a>
</div>

<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <form id="assignmentSearchForm" class="flex gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                <input
                    type="text"
                    id="searchInput"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Equipo, usuario, empleado..."
                    class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select id="statusFilter" name="status" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activas</option>
                    <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Devueltas</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Desde</label>
                <input
                    type="date"
                    id="dateFromFilter"
                    name="date_from"
                    value="{{ request('date_from') }}"
                    class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Hasta</label>
                <input
                    type="date"
                    id="dateToFilter"
                    name="date_to"
                    value="{{ request('date_to') }}"
                    class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>
            <button type="button" id="clearFiltersBtn" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                Limpiar
            </button>
        </form>
        
        <!-- Loading indicator -->
        <div id="loadingIndicator" class="hidden mt-4">
            <div class="flex items-center justify-center py-4">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-600">Cargando asignaciones...</span>
            </div>
        </div>
        
        <!-- Results counter -->
        <div id="resultsCounter" class="mt-4 text-sm text-gray-600">
            <span id="resultsCount">{{ $assignments->total() ?? 0 }}</span> asignaciones encontradas
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Equipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Asignación</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Devolución</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody id="assignmentTableBody" class="bg-white divide-y divide-gray-200">
                @include('assignments.partials.table-rows', ['assignments' => $assignments])
            </tbody>
        </table>
    </div>

    <div id="paginationContainer" class="px-6 py-4 border-t border-gray-200">
        @if(isset($assignments) && $assignments->hasPages())
            {{ $assignments->appends(request()->query())->links() }}
        @endif
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const dateFromFilter = document.getElementById('dateFromFilter');
    const dateToFilter = document.getElementById('dateToFilter');
    const clearFiltersBtn = document.getElementById('clearFiltersBtn');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const resultsCount = document.getElementById('resultsCount');
    const tableBody = document.getElementById('assignmentTableBody');
    const paginationContainer = document.getElementById('paginationContainer');

    // Función para realizar búsqueda AJAX
    function performSearch(url = null) {
        const formData = new FormData();
        
        if (searchInput.value.trim()) {
            formData.append('search', searchInput.value.trim());
        }
        
        if (statusFilter.value) {
            formData.append('status', statusFilter.value);
        }
        
        if (dateFromFilter.value) {
            formData.append('date_from', dateFromFilter.value);
        }
        
        if (dateToFilter.value) {
            formData.append('date_to', dateToFilter.value);
        }

        // Mostrar indicador de carga
        loadingIndicator.classList.remove('hidden');
        tableBody.style.opacity = '0.5';

        const searchUrl = url || '{{ route("api.assignments.ajax-search") }}';
        const queryString = new URLSearchParams(formData).toString();
        
        fetch(`${searchUrl}?${queryString}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Actualizar tabla
            tableBody.innerHTML = data.html;
            
            // Actualizar contador de resultados
            resultsCount.textContent = data.count || 0;
            
            // Actualizar paginación
            paginationContainer.innerHTML = data.pagination || '';
            
            // Ocultar indicador de carga
            loadingIndicator.classList.add('hidden');
            tableBody.style.opacity = '1';
            
            // Re-bindear eventos de paginación
            bindPaginationEvents();
        })
        .catch(error => {
            console.error('Error en la búsqueda:', error);
            loadingIndicator.classList.add('hidden');
            tableBody.style.opacity = '1';
            
            // Mostrar mensaje de error
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-red-500">
                        Error al realizar la búsqueda. Por favor, inténtelo de nuevo.
                    </td>
                </tr>
            `;
        });
    }

    // Función para bindear eventos de paginación
    function bindPaginationEvents() {
        const paginationLinks = paginationContainer.querySelectorAll('a[href]');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.href.replace(window.location.origin, '').replace('/assignments', '/api/assignments/ajax-search');
                performSearch(url);
            });
        });
    }

    // Event listeners
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            performSearch();
        }, 500); // Debounce de 500ms
    });

    statusFilter.addEventListener('change', function() {
        performSearch();
    });

    dateFromFilter.addEventListener('change', function() {
        performSearch();
    });

    dateToFilter.addEventListener('change', function() {
        performSearch();
    });

    clearFiltersBtn.addEventListener('click', function() {
        searchInput.value = '';
        statusFilter.value = '';
        dateFromFilter.value = '';
        dateToFilter.value = '';
        performSearch();
    });

    // Bindear eventos de paginación inicial
    bindPaginationEvents();
});
</script>
@endsection
