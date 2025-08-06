@extends('layouts.app')

@section('title', 'Editar Mantenimiento - ITAM System')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Editar Mantenimiento</h1>
            <p class="text-gray-600">Actualiza la información del mantenimiento</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('maintenance.show', $maintenance) }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                Ver Mantenimiento
            </a>
            <a href="{{ route('maintenance.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                Volver al Listado
            </a>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow">
    <form method="POST" action="{{ route('maintenance.update', $maintenance) }}" class="p-6">
        @csrf
        @method('PUT')

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
                    @foreach($equipment as $item)
                        <option value="{{ $item->id }}" {{ old('equipment_id', $maintenance->equipment_id) == $item->id ? 'selected' : '' }}>
                            {{ $item->equipmentType->name ?? 'N/A' }} - {{ $item->serial_number }} 
                            @if($item->asset_tag) ({{ $item->asset_tag }}) @endif
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
                    @foreach($technicians as $technician)
                        <option value="{{ $technician->id }}" {{ old('performed_by', $maintenance->performed_by) == $technician->id ? 'selected' : '' }}>
                            {{ $technician->name }}
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
                    <option value="preventive" {{ old('type', $maintenance->type) === 'preventive' ? 'selected' : '' }}>Preventivo</option>
                    <option value="corrective" {{ old('type', $maintenance->type) === 'corrective' ? 'selected' : '' }}>Correctivo</option>
                    <option value="update" {{ old('type', $maintenance->type) === 'update' ? 'selected' : '' }}>Actualización</option>
                </select>
                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Estado *</label>
                <select
                    id="status"
                    name="status"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror"
                    required
                >
                    <option value="">Seleccionar Estado</option>
                    <option value="scheduled" {{ old('status', $maintenance->status) === 'scheduled' ? 'selected' : '' }}>Programado</option>
                    <option value="in_progress" {{ old('status', $maintenance->status) === 'in_progress' ? 'selected' : '' }}>En Progreso</option>
                    <option value="completed" {{ old('status', $maintenance->status) === 'completed' ? 'selected' : '' }}>Completado</option>
                    <option value="cancelled" {{ old('status', $maintenance->status) === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="scheduled_date" class="block text-sm font-medium text-gray-700 mb-2">Fecha Programada *</label>
                <input
                    type="datetime-local"
                    id="scheduled_date"
                    name="scheduled_date"
                    value="{{ old('scheduled_date', $maintenance->scheduled_date ? $maintenance->scheduled_date->format('Y-m-d\TH:i') : '') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('scheduled_date') border-red-500 @enderror"
                    required
                >
                @error('scheduled_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="completed_date" class="block text-sm font-medium text-gray-700 mb-2">Fecha de Finalización</label>
                <input
                    type="datetime-local"
                    id="completed_date"
                    name="completed_date"
                    value="{{ old('completed_date', $maintenance->completed_date ? $maintenance->completed_date->format('Y-m-d\TH:i') : '') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('completed_date') border-red-500 @enderror"
                >
                @error('completed_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="cost" class="block text-sm font-medium text-gray-700 mb-2">Costo</label>
                <input
                    type="number"
                    id="cost"
                    name="cost"
                    step="0.01"
                    value="{{ old('cost', $maintenance->cost) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('cost') border-red-500 @enderror"
                    placeholder="0.00"
                >
                @error('cost')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Descripción del Trabajo *</label>
            <textarea
                id="description"
                name="description"
                rows="4"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                placeholder="Describe el trabajo a realizar o el problema a solucionar..."
                required
            >{{ old('description', $maintenance->description) }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-6">
            <label for="performed_actions" class="block text-sm font-medium text-gray-700 mb-2">Acciones Realizadas</label>
            <textarea
                id="performed_actions"
                name="performed_actions"
                rows="4"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('performed_actions') border-red-500 @enderror"
                placeholder="Describe las acciones que se realizaron durante el mantenimiento..."
            >{{ old('performed_actions', $maintenance->performed_actions) }}</textarea>
            @error('performed_actions')
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
                placeholder="Notas adicionales, observaciones, recomendaciones..."
            >{{ old('notes', $maintenance->notes) }}</textarea>
            @error('notes')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('maintenance.show', $maintenance) }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400">
                Cancelar
            </a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                Actualizar Mantenimiento
            </button>
        </div>
    </form>
</div>

<script>
// Habilitar/deshabilitar fecha de finalización según el estado
document.getElementById('status').addEventListener('change', function() {
    const completedDateField = document.getElementById('completed_date');
    const performedActionsField = document.getElementById('performed_actions');
    
    if (this.value === 'completed') {
        completedDateField.required = true;
        performedActionsField.required = true;
        if (!completedDateField.value) {
            completedDateField.value = new Date().toISOString().slice(0, 16);
        }
    } else {
        completedDateField.required = false;
        performedActionsField.required = false;
    }
});

// Ejecutar al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('status').dispatchEvent(new Event('change'));
});
</script>
@endsection