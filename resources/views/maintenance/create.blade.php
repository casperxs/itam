@extends('layouts.app')

@section('title', 'Programar Mantenimiento - ITAM System')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Programar Mantenimiento</h1>
            <p class="text-gray-600">Crear un nuevo registro de mantenimiento programado</p>
        </div>
        <a href="{{ route('maintenance.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            Volver al Listado
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow">
    <form method="POST" action="{{ route('maintenance.store') }}" class="p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="equipment_search" class="block text-sm font-medium text-gray-700 mb-2">Buscar Equipo *</label>
                <div class="relative">
                    <input
                        type="text"
                        id="equipment_search"
                        placeholder="Buscar por tipo, marca, modelo, serie, tag o usuario asignado..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('equipment_id') border-red-500 @enderror"
                        autocomplete="off"
                    >
                    <input type="hidden" id="equipment_id" name="equipment_id" value="{{ old('equipment_id') }}" required>
                    
                    <!-- Dropdown de resultados -->
                    <div id="equipment_results" class="absolute z-10 w-full bg-white border border-gray-300 rounded-md mt-1 max-h-60 overflow-y-auto hidden shadow-lg">
                        <!-- Los resultados se cargarán aquí dinámicamente -->
                    </div>
                    
                    <!-- Equipo seleccionado -->
                    <div id="selected_equipment" class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-md hidden">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="font-medium text-blue-900" id="selected_equipment_text"></div>
                                <div class="text-sm text-blue-700" id="selected_equipment_details"></div>
                            </div>
                            <button type="button" onclick="clearSelection()" class="text-blue-600 hover:text-blue-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                @error('equipment_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="performed_by" class="block text-sm font-medium text-gray-700 mb-2">Técnico Asignado *</label>
                <select
                    id="performed_by"
                    name="performed_by"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('performed_by') border-red-500 @enderror"
                    required
                >
                    <option value="">Seleccionar Técnico</option>
                    @foreach($technicians ?? [] as $technician)
                        <option value="{{ $technician->id }}" {{ old('performed_by') == $technician->id ? 'selected' : '' }}>
                            {{ $technician->name }} - {{ $technician->email }}
                        </option>
                    @endforeach
                </select>
                @error('performed_by')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Mantenimiento *</label>
                <select
                    id="type"
                    name="type"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('type') border-red-500 @enderror"
                    required
                >
                    <option value="">Seleccionar Tipo</option>
                    <option value="preventive" {{ old('type') == 'preventive' ? 'selected' : '' }}>Preventivo</option>
                    <option value="corrective" {{ old('type') == 'corrective' ? 'selected' : '' }}>Correctivo</option>
                    <option value="update" {{ old('type') == 'update' ? 'selected' : '' }}>Actualización</option>
                </select>
                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="scheduled_date" class="block text-sm font-medium text-gray-700 mb-2">Fecha Programada *</label>
                <input
                    type="datetime-local"
                    id="scheduled_date"
                    name="scheduled_date"
                    value="{{ old('scheduled_date', now()->addDays(1)->format('Y-m-d\TH:i')) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('scheduled_date') border-red-500 @enderror"
                    required
                >
                @error('scheduled_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="cost" class="block text-sm font-medium text-gray-700 mb-2">Costo Estimado</label>
                <input
                    type="number"
                    id="cost"
                    name="cost"
                    value="{{ old('cost') }}"
                    step="0.01"
                    min="0"
                    placeholder="0.00"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('cost') border-red-500 @enderror"
                >
                @error('cost')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Descripción del Mantenimiento *</label>
            <textarea
                id="description"
                name="description"
                rows="4"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                placeholder="Describe el mantenimiento a realizar: componentes a revisar, acciones preventivas, reparaciones necesarias, etc."
                required
            >{{ old('description') }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-6">
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notas Adicionales</label>
            <textarea
                id="notes"
                name="notes"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror"
                placeholder="Notas adicionales, consideraciones especiales, herramientas requeridas, etc."
            >{{ old('notes') }}</textarea>
            @error('notes')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('maintenance.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400">
                Cancelar
            </a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                Programar Mantenimiento
            </button>
        </div>
    </form>
</div>

<!-- Información de Tipos de Mantenimiento -->
<div class="mt-8 bg-blue-50 rounded-lg p-6">
    <h3 class="text-lg font-medium text-blue-900 mb-4">Tipos de Mantenimiento</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg border border-green-200 p-4">
            <div class="flex items-center mb-2">
                <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                <span class="font-medium text-gray-900">Preventivo</span>
            </div>
            <p class="text-sm text-gray-600">
                Mantenimiento programado regularmente para prevenir fallas y prolongar la vida útil del equipo.
            </p>
        </div>
        
        <div class="bg-white rounded-lg border border-red-200 p-4">
            <div class="flex items-center mb-2">
                <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                <span class="font-medium text-gray-900">Correctivo</span>
            </div>
            <p class="text-sm text-gray-600">
                Reparación de fallas o problemas identificados en el equipo que afectan su funcionamiento.
            </p>
        </div>
        
        <div class="bg-white rounded-lg border border-blue-200 p-4">
            <div class="flex items-center mb-2">
                <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                <span class="font-medium text-gray-900">Actualización</span>
            </div>
            <p class="text-sm text-gray-600">
                Actualización de software, firmware o componentes para mejorar el rendimiento o seguridad.
            </p>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('equipment_search');
    const equipmentIdInput = document.getElementById('equipment_id');
    const resultsDiv = document.getElementById('equipment_results');
    const selectedDiv = document.getElementById('selected_equipment');
    const selectedTextDiv = document.getElementById('selected_equipment_text');
    const selectedDetailsDiv = document.getElementById('selected_equipment_details');
    
    let searchTimeout;
    let selectedEquipment = null;
    
    // Si hay un equipment_id de old(), cargar la información del equipo
    @if(old('equipment_id'))
        // Hacer una llamada para obtener la información del equipo seleccionado previamente
        fetch(`/api/equipment/search?q={{ old('equipment_id') }}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    selectEquipment(data[0]);
                }
            })
            .catch(error => console.error('Error:', error));
    @endif
    
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        // Limpiar timeout anterior
        clearTimeout(searchTimeout);
        
        if (query.length < 2) {
            hideResults();
            return;
        }
        
        // Debounce la búsqueda
        searchTimeout = setTimeout(() => {
            search(query);
        }, 300);
    });
    
    // Ocultar resultados al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !resultsDiv.contains(e.target)) {
            hideResults();
        }
    });
    
    function search(query) {
        fetch(`/api/equipment/search?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                showResults(data);
            })
            .catch(error => {
                console.error('Error:', error);
                hideResults();
            });
    }
    
    function showResults(equipment) {
        if (equipment.length === 0) {
            resultsDiv.innerHTML = '<div class="p-3 text-gray-500 text-center">No se encontraron equipos</div>';
        } else {
            resultsDiv.innerHTML = equipment.map(item => {
                return `
                    <div class="p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0" onclick="selectEquipment(${JSON.stringify(item).replace(/"/g, '&quot;')})">
                        <div class="font-medium text-gray-900">${item.text}</div>
                        <div class="text-sm text-gray-600">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ${
                                item.status === 'available' ? 'bg-green-100 text-green-800' :
                                item.status === 'assigned' ? 'bg-blue-100 text-blue-800' :
                                item.status === 'maintenance' ? 'bg-yellow-100 text-yellow-800' :
                                'bg-gray-100 text-gray-800'
                            }">
                                ${item.status.charAt(0).toUpperCase() + item.status.slice(1)}
                            </span>
                            ${item.asset_tag ? ` | Tag: ${item.asset_tag}` : ''}
                        </div>
                    </div>
                `;
            }).join('');
        }
        
        resultsDiv.classList.remove('hidden');
    }
    
    function hideResults() {
        resultsDiv.classList.add('hidden');
    }
    
    window.selectEquipment = function(equipment) {
        selectedEquipment = equipment;
        
        // Actualizar campos
        equipmentIdInput.value = equipment.id;
        searchInput.value = '';
        
        // Mostrar equipo seleccionado
        selectedTextDiv.textContent = `${equipment.type} - ${equipment.brand} ${equipment.model}`;
        selectedDetailsDiv.innerHTML = `
            <strong>Serie:</strong> ${equipment.serial_number}
            ${equipment.asset_tag ? ` | <strong>Tag:</strong> ${equipment.asset_tag}` : ''}
            ${equipment.assigned_user ? ` | <strong>Usuario:</strong> ${equipment.assigned_user}` : ' | Sin asignar'}
            ${equipment.user_department ? ` (${equipment.user_department})` : ''}
        `;
        
        selectedDiv.classList.remove('hidden');
        hideResults();
    }
    
    window.clearSelection = function() {
        selectedEquipment = null;
        equipmentIdInput.value = '';
        searchInput.value = '';
        selectedDiv.classList.add('hidden');
        searchInput.focus();
    }
    
    // Manejar teclas de navegación
    searchInput.addEventListener('keydown', function(e) {
        const results = resultsDiv.querySelectorAll('[onclick]');
        const currentFocus = resultsDiv.querySelector('.bg-blue-100');
        let currentIndex = Array.from(results).indexOf(currentFocus);
        
        switch(e.key) {
            case 'ArrowDown':
                e.preventDefault();
                if (results.length > 0) {
                    if (currentFocus) currentFocus.classList.remove('bg-blue-100');
                    currentIndex = currentIndex < results.length - 1 ? currentIndex + 1 : 0;
                    results[currentIndex].classList.add('bg-blue-100');
                }
                break;
                
            case 'ArrowUp':
                e.preventDefault();
                if (results.length > 0) {
                    if (currentFocus) currentFocus.classList.remove('bg-blue-100');
                    currentIndex = currentIndex > 0 ? currentIndex - 1 : results.length - 1;
                    results[currentIndex].classList.add('bg-blue-100');
                }
                break;
                
            case 'Enter':
                e.preventDefault();
                if (currentFocus) {
                    currentFocus.click();
                }
                break;
                
            case 'Escape':
                hideResults();
                break;
        }
    });
});
</script>
@endsection
