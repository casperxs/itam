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

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.select2-container .select2-selection--single {
    height: 42px !important;
    border: 1px solid #d1d5db !important;
    border-radius: 0.375rem !important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 40px !important;
    padding-left: 12px !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 40px !important;
    right: 10px !important;
}
.select2-dropdown {
    border: 1px solid #d1d5db !important;
    border-radius: 0.375rem !important;
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar Select2 para equipos
    $('#equipment_id').select2({
        ajax: {
            url: '{{ route("api.equipment.search-available") }}',
            dataType: 'json',
            delay: 300,
            data: function (params) {
                return {
                    search: params.term || ''
                };
            },
            processResults: function (data) {
                return {
                    results: data.results || []
                };
            },
            cache: true
        },
        minimumInputLength: 2,
        placeholder: 'Escribe para buscar equipos...',
        allowClear: true,
        language: {
            inputTooShort: function(args) {
                return 'Escribe al menos 2 caracteres para buscar';
            },
            noResults: function() {
                return 'No se encontraron equipos';
            },
            searching: function() {
                return 'Buscando...';
            },
            loadingMore: function() {
                return 'Cargando más resultados...';
            }
        }
    });

    // Inicializar Select2 para usuarios
    $('#it_user_id').select2({
        ajax: {
            url: '{{ route("api.users.search-active") }}',
            dataType: 'json',
            delay: 300,
            data: function (params) {
                return {
                    search: params.term || ''
                };
            },
            processResults: function (data) {
                return {
                    results: data.results || []
                };
            },
            cache: true
        },
        minimumInputLength: 2,
        placeholder: 'Escribe para buscar usuarios...',
        allowClear: true,
        language: {
            inputTooShort: function(args) {
                return 'Escribe al menos 2 caracteres para buscar';
            },
            noResults: function() {
                return 'No se encontraron usuarios';
            },
            searching: function() {
                return 'Buscando...';
            },
            loadingMore: function() {
                return 'Cargando más resultados...';
            }
        }
    });

    // Manejar errores de AJAX
    $(document).ajaxError(function(event, jqxhr, settings, thrownError) {
        if (settings.url.includes('search-available') || settings.url.includes('search-active')) {
            console.error('Error en la búsqueda AJAX:', thrownError);
            // Opcional: mostrar notificación al usuario
        }
    });

    // Pre-seleccionar opciones si hay valores old() de Laravel
    @if(old('equipment_id'))
        // Si hay un valor old para equipment_id, mantenerlo seleccionado
        var equipmentId = '{{ old("equipment_id") }}';
        var equipmentText = $('#equipment_id option[value="' + equipmentId + '"]').text();
        if (equipmentText) {
            var equipmentOption = new Option(equipmentText, equipmentId, true, true);
            $('#equipment_id').append(equipmentOption).trigger('change');
        }
    @endif

    @if(old('it_user_id', request('user_id')))
        // Si hay un valor old para it_user_id, mantenerlo seleccionado
        var userId = '{{ old("it_user_id", request("user_id")) }}';
        var userText = $('#it_user_id option[value="' + userId + '"]').text();
        if (userText) {
            var userOption = new Option(userText, userId, true, true);
            $('#it_user_id').append(userOption).trigger('change');
        }
    @endif
});
</script>
@endsection
