@extends('layouts.app')

@section('title', 'Nueva Asignación - ITAM System')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Nueva Asignación de Equipo</h1>
            <p class="text-gray-600">Asigna un equipo a un usuario del sistema</p>
        </div>
        <a href="{{ route('assignments.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            Volver al Listado
        </a>
    </div>
</div>

<!-- Indicador de equipos disponibles -->
<div class="bg-blue-50 rounded-lg p-4 mb-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-blue-800 font-medium">Equipos Disponibles para Asignación</span>
        </div>
        <div class="text-blue-600 font-bold text-lg" id="available-count">
            {{ isset($availableEquipment) ? $availableEquipment->count() : 0 }} equipos
        </div>
    </div>
    <p class="text-blue-700 text-sm mt-1">Usa la búsqueda para encontrar equipos específicos por tipo, marca, modelo o número de serie</p>
</div>

<!-- Equipos disponibles recientes -->
@if(isset($availableEquipment) && $availableEquipment->count() > 0)
<div class="bg-gray-50 rounded-lg p-4 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-3">Equipos Disponibles Recientemente</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
        @foreach($availableEquipment->take(6) as $equipment)
        <div class="bg-white p-3 rounded border border-green-200 hover:border-green-400 transition-colors cursor-pointer equipment-quick-select"
             data-equipment-id="{{ $equipment->id }}"
             data-equipment-text="{{ $equipment->equipmentType->name ?? 'N/A' }} - {{ $equipment->brand }} {{ $equipment->model }} ({{ $equipment->serial_number }})">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-1">
                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                        <span class="text-sm font-medium text-gray-900">{{ $equipment->equipmentType->name ?? 'N/A' }}</span>
                    </div>
                    <p class="text-sm text-gray-600">{{ $equipment->brand }} {{ $equipment->model }}</p>
                    <p class="text-xs text-gray-500">S/N: {{ $equipment->serial_number }}</p>
                </div>
                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Disponible</span>
            </div>
        </div>
        @endforeach
    </div>
    @if($availableEquipment->count() > 6)
    <p class="text-sm text-gray-600 mt-3 text-center">
        Mostrando 6 de {{ $availableEquipment->count() }} equipos disponibles. Usa la búsqueda para ver más opciones.
    </p>
    @endif
</div>
@endif

<div class="bg-white rounded-lg shadow">
    <form method="POST" action="{{ route('assignments.store') }}" class="p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="equipment_id" class="block text-sm font-medium text-gray-700 mb-2">Equipo *</label>
                <select
                    id="equipment_id"
                    name="equipment_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('equipment_id') border-red-500 @enderror"
                    required
                >
                    <option value="">Buscar equipo...</option>
                    @if(old('equipment_id') && $availableEquipment ?? false)
                        @foreach($availableEquipment as $equipment)
                            @if($equipment->id == old('equipment_id'))
                                <option value="{{ $equipment->id }}" selected>
                                    {{ $equipment->equipmentType->name ?? 'N/A' }} - {{ $equipment->brand }} {{ $equipment->model }} ({{ $equipment->serial_number }})
                                </option>
                            @endif
                        @endforeach
                    @endif
                </select>
                @error('equipment_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="it_user_id" class="block text-sm font-medium text-gray-700 mb-2">Usuario *</label>
                <select
                    id="it_user_id"
                    name="it_user_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('it_user_id') border-red-500 @enderror"
                    required
                >
                    <option value="">Buscar usuario...</option>
                    @if(old('it_user_id') && $users ?? false)
                        @foreach($users as $user)
                            @if($user->id == old('it_user_id', request('user_id')))
                                <option value="{{ $user->id }}" selected>
                                    {{ $user->name }} ({{ $user->employee_id }}) - {{ $user->department }}
                                </option>
                            @endif
                        @endforeach
                    @endif
                </select>
                @error('it_user_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="assigned_at" class="block text-sm font-medium text-gray-700 mb-2">Fecha de Asignación *</label>
                <input
                    type="datetime-local"
                    id="assigned_at"
                    name="assigned_at"
                    value="{{ old('assigned_at', now()->format('Y-m-d\TH:i')) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('assigned_at') border-red-500 @enderror"
                    required
                >
                @error('assigned_at')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6">
            <label for="assignment_notes" class="block text-sm font-medium text-gray-700 mb-2">Notas de Asignación</label>
            <textarea
                id="assignment_notes"
                name="assignment_notes"
                rows="4"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('assignment_notes') border-red-500 @enderror"
                placeholder="Detalles adicionales sobre la asignación, condiciones del equipo, accesorios incluidos, etc."
            >{{ old('assignment_notes') }}</textarea>
            @error('assignment_notes')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('assignments.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400">
                Cancelar
            </a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                Crear Asignación
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const equipmentSelect = document.getElementById('equipment_id');
    const userSelect = document.getElementById('it_user_id');
    
    let equipmentTimeout;
    let userTimeout;

    // Función para crear input de búsqueda personalizado para equipos
    function setupEquipmentSearch() {
        const container = equipmentSelect.parentNode;
        
        // Crear input de búsqueda
        const searchInput = document.createElement('input');
        searchInput.type = 'text';
        searchInput.placeholder = 'Escribe para buscar equipos...';
        searchInput.className = equipmentSelect.className;
        searchInput.style.display = 'none';
        
        // Crear dropdown personalizado
        const dropdown = document.createElement('div');
        dropdown.className = 'absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto hidden';
        dropdown.style.position = 'absolute';
        dropdown.style.top = '100%';
        dropdown.style.left = '0';
        
        // Posicionar container como relativo
        container.style.position = 'relative';
        
        // Agregar elementos
        container.appendChild(searchInput);
        container.appendChild(dropdown);
        
        // Mostrar input al hacer click en el select
        equipmentSelect.addEventListener('click', function(e) {
            e.preventDefault();
            equipmentSelect.style.display = 'none';
            searchInput.style.display = 'block';
            searchInput.focus();
        });
        
        // Buscar equipos mientras se escribe
        searchInput.addEventListener('input', function() {
            clearTimeout(equipmentTimeout);
            const query = this.value.trim();
            
            if (query.length < 2) {
                dropdown.classList.add('hidden');
                return;
            }
            
            equipmentTimeout = setTimeout(() => {
                searchEquipment(query, dropdown, searchInput, equipmentSelect);
            }, 300);
        });
        
        // Ocultar dropdown al hacer click fuera
        document.addEventListener('click', function(e) {
            if (!container.contains(e.target)) {
                dropdown.classList.add('hidden');
                if (equipmentSelect.value === '') {
                    searchInput.style.display = 'none';
                    equipmentSelect.style.display = 'block';
                }
            }
        });
    }
    
    // Función para crear input de búsqueda personalizado para usuarios
    function setupUserSearch() {
        const container = userSelect.parentNode;
        
        // Crear input de búsqueda
        const searchInput = document.createElement('input');
        searchInput.type = 'text';
        searchInput.placeholder = 'Escribe para buscar usuarios...';
        searchInput.className = userSelect.className;
        searchInput.style.display = 'none';
        
        // Crear dropdown personalizado
        const dropdown = document.createElement('div');
        dropdown.className = 'absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto hidden';
        dropdown.style.position = 'absolute';
        dropdown.style.top = '100%';
        dropdown.style.left = '0';
        
        // Posicionar container como relativo
        container.style.position = 'relative';
        
        // Agregar elementos
        container.appendChild(searchInput);
        container.appendChild(dropdown);
        
        // Mostrar input al hacer click en el select
        userSelect.addEventListener('click', function(e) {
            e.preventDefault();
            userSelect.style.display = 'none';
            searchInput.style.display = 'block';
            searchInput.focus();
        });
        
        // Buscar usuarios mientras se escribe
        searchInput.addEventListener('input', function() {
            clearTimeout(userTimeout);
            const query = this.value.trim();
            
            if (query.length < 2) {
                dropdown.classList.add('hidden');
                return;
            }
            
            userTimeout = setTimeout(() => {
                searchUsers(query, dropdown, searchInput, userSelect);
            }, 300);
        });
        
        // Ocultar dropdown al hacer click fuera
        document.addEventListener('click', function(e) {
            if (!container.contains(e.target)) {
                dropdown.classList.add('hidden');
                if (userSelect.value === '') {
                    searchInput.style.display = 'none';
                    userSelect.style.display = 'block';
                }
            }
        });
    }
    
    // Función para buscar equipos
    function searchEquipment(query, dropdown, input, select) {
        dropdown.innerHTML = '<div class="p-2 text-gray-500">Buscando...</div>';
        dropdown.classList.remove('hidden');
        
        fetch(`{{ route("api.equipment.search-available") }}?search=${encodeURIComponent(query)}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.results && data.results.length > 0) {
                dropdown.innerHTML = data.results.map(item => 
                    `<div class="p-2 hover:bg-gray-100 cursor-pointer border-l-4 border-green-500" data-id="${item.id}">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                            <span class="flex-1">${item.text}</span>
                            <span class="text-xs text-green-600 font-medium">DISPONIBLE</span>
                        </div>
                    </div>`
                ).join('');
                
                // Agregar event listeners a las opciones
                dropdown.querySelectorAll('[data-id]').forEach(option => {
                    option.addEventListener('click', function() {
                        select.value = this.dataset.id;
                        input.value = this.textContent;
                        input.style.display = 'none';
                        select.style.display = 'block';
                        
                        // Crear option si no existe
                        if (!select.querySelector(`option[value="${this.dataset.id}"]`)) {
                            const newOption = document.createElement('option');
                            newOption.value = this.dataset.id;
                            newOption.textContent = this.textContent;
                            newOption.selected = true;
                            select.appendChild(newOption);
                        } else {
                            select.querySelector(`option[value="${this.dataset.id}"]`).selected = true;
                        }
                        
                        dropdown.classList.add('hidden');
                    });
                });
            } else {
                dropdown.innerHTML = '<div class="p-2 text-gray-500">No se encontraron equipos</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            dropdown.innerHTML = '<div class="p-2 text-red-500">Error en la búsqueda</div>';
        });
    }
    
    // Función para buscar usuarios
    function searchUsers(query, dropdown, input, select) {
        dropdown.innerHTML = '<div class="p-2 text-gray-500">Buscando...</div>';
        dropdown.classList.remove('hidden');
        
        fetch(`{{ route("api.users.search-active") }}?search=${encodeURIComponent(query)}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.results && data.results.length > 0) {
                dropdown.innerHTML = data.results.map(item => 
                    `<div class="p-2 hover:bg-gray-100 cursor-pointer" data-id="${item.id}">${item.text}</div>`
                ).join('');
                
                // Agregar event listeners a las opciones
                dropdown.querySelectorAll('[data-id]').forEach(option => {
                    option.addEventListener('click', function() {
                        select.value = this.dataset.id;
                        input.value = this.textContent;
                        input.style.display = 'none';
                        select.style.display = 'block';
                        
                        // Crear option si no existe
                        if (!select.querySelector(`option[value="${this.dataset.id}"]`)) {
                            const newOption = document.createElement('option');
                            newOption.value = this.dataset.id;
                            newOption.textContent = this.textContent;
                            newOption.selected = true;
                            select.appendChild(newOption);
                        } else {
                            select.querySelector(`option[value="${this.dataset.id}"]`).selected = true;
                        }
                        
                        dropdown.classList.add('hidden');
                    });
                });
            } else {
                dropdown.innerHTML = '<div class="p-2 text-gray-500">No se encontraron usuarios</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            dropdown.innerHTML = '<div class="p-2 text-red-500">Error en la búsqueda</div>';
        });
    }
    
    // Funcionalidad de selección rápida de equipos
    document.querySelectorAll('.equipment-quick-select').forEach(card => {
        card.addEventListener('click', function() {
            const equipmentId = this.dataset.equipmentId;
            const equipmentText = this.dataset.equipmentText;
            
            // Limpiar selecciones previas
            document.querySelectorAll('.equipment-quick-select').forEach(c => {
                c.classList.remove('border-blue-500', 'bg-blue-50');
                c.classList.add('border-green-200');
            });
            
            // Marcar como seleccionado
            this.classList.remove('border-green-200');
            this.classList.add('border-blue-500', 'bg-blue-50');
            
            // Actualizar el select
            equipmentSelect.value = equipmentId;
            
            // Crear option si no existe
            if (!equipmentSelect.querySelector(`option[value="${equipmentId}"]`)) {
                const newOption = document.createElement('option');
                newOption.value = equipmentId;
                newOption.textContent = equipmentText;
                newOption.selected = true;
                equipmentSelect.appendChild(newOption);
            } else {
                equipmentSelect.querySelector(`option[value="${equipmentId}"]`).selected = true;
            }
            
            // Scroll suave al formulario
            document.querySelector('form').scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
    
    // Inicializar las búsquedas
    setupEquipmentSearch();
    setupUserSearch();
});
</script>
@endsection
