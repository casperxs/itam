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
                <label for="equipment_id" class="block text-sm font-medium text-gray-700 mb-2">Equipo *</label>
                <select
                    id="equipment_id"
                    name="equipment_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('equipment_id') border-red-500 @enderror"
                    required
                >
                    <option value="">Seleccionar Equipo</option>
                    @foreach($equipment ?? [] as $item)
                        <option value="{{ $item->id }}" {{ old('equipment_id') == $item->id ? 'selected' : '' }}>
                            {{ $item->equipmentType->name ?? 'N/A' }} - {{ $item->brand }} {{ $item->model }} ({{ $item->serial_number }})
                        </option>
                    @endforeach
                </select>
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

<!-- Resumen de Equipos -->
<div class="mt-8 bg-gray-50 rounded-lg p-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Equipos en el Sistema</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @if(isset($equipment) && $equipment->count() > 0)
            @foreach($equipment->groupBy('equipmentType.name') as $type => $items)
                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <div class="font-medium text-gray-900">{{ $type }}</div>
                    <div class="text-sm text-gray-600">{{ $items->count() }} equipo(s)</div>
                    <div class="text-xs text-gray-500 mt-1">
                        Disponibles: {{ $items->where('status', 'available')->count() }}
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-span-full text-center text-gray-500 py-4">
                No hay equipos registrados en el sistema
            </div>
        @endif
    </div>
</div>
@endsection