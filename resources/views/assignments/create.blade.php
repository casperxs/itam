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
                    <option value="">Seleccionar Equipo</option>
                    @foreach($availableEquipment ?? [] as $equipment)
                        <option value="{{ $equipment->id }}" {{ old('equipment_id') == $equipment->id ? 'selected' : '' }}>
                            {{ $equipment->equipmentType->name ?? 'N/A' }} - {{ $equipment->brand }} {{ $equipment->model }} ({{ $equipment->serial_number }})
                        </option>
                    @endforeach
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
                    <option value="">Seleccionar Usuario</option>
                    @foreach($users ?? [] as $user)
                        <option value="{{ $user->id }}" {{ old('it_user_id', request('user_id')) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->employee_id }}) - {{ $user->department }}
                        </option>
                    @endforeach
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

<!-- Información de Equipos Disponibles -->
<div class="mt-8 bg-blue-50 rounded-lg p-6">
    <h3 class="text-lg font-medium text-blue-900 mb-4">Equipos Disponibles para Asignación</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($availableEquipment ?? [] as $equipment)
            <div class="bg-white rounded-lg border border-blue-200 p-4">
                <div class="font-medium text-gray-900">{{ $equipment->equipmentType->name ?? 'N/A' }}</div>
                <div class="text-sm text-gray-600">{{ $equipment->brand }} {{ $equipment->model }}</div>
                <div class="text-xs text-gray-500">Serie: {{ $equipment->serial_number }}</div>
                <div class="text-xs text-gray-500">Tag: {{ $equipment->asset_tag }}</div>
            </div>
        @empty
            <div class="col-span-full text-center text-gray-500 py-4">
                No hay equipos disponibles para asignación
            </div>
        @endforelse
    </div>
</div>
@endsection
