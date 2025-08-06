@extends('layouts.app')

@section('title', 'Editar Usuario - ITAM System')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Editar Usuario</h1>
            <p class="text-gray-600">Actualiza la información del usuario {{ $itUser->name }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('it-users.show', $itUser) }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                Ver Usuario
            </a>
            <a href="{{ route('it-users.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                Volver al Listado
            </a>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow">
    <form method="POST" action="{{ route('it-users.update', $itUser) }}" class="p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo *</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $itUser->name) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                    required
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico *</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $itUser->email) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                    required
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-2">ID de Empleado *</label>
                <input
                    type="text"
                    id="employee_id"
                    name="employee_id"
                    value="{{ old('employee_id', $itUser->employee_id) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('employee_id') border-red-500 @enderror"
                    required
                >
                @error('employee_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Departamento *</label>
                <select
                    id="department"
                    name="department"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('department') border-red-500 @enderror"
                    required
                >
                    <option value="">Seleccionar Departamento</option>
                    <option value="Liderazgo" {{ old('department', $itUser->department) === 'Liderazgo' ? 'selected' : '' }}>Liderazgo</option>
                    <option value="TI" {{ old('department', $itUser->department) === 'TI' ? 'selected' : '' }}>TI</option>
                    <option value="Administración" {{ old('department', $itUser->department) === 'Administración' ? 'selected' : '' }}>Administración</option>
                    <option value="Recursos Humanos" {{ old('department', $itUser->department) === 'Recursos Humanos' ? 'selected' : '' }}>Recursos Humanos</option>
                    <option value="Training" {{ old('department', $itUser->department) === 'Training' ? 'selected' : '' }}>Training</option>
                    <option value="Directos (FullTruck)" {{ old('department', $itUser->department) === 'Directos (FullTruck)' ? 'selected' : '' }}>Directos (FullTruck)</option>
                    <option value="Bodegas" {{ old('department', $itUser->department) === 'Bodegas' ? 'selected' : '' }}>Bodegas</option>
                    <option value="Milk Run" {{ old('department', $itUser->department) === 'Milk Run' ? 'selected' : '' }}>Milk Run</option>
                    <option value="Virtuales" {{ old('department', $itUser->department) === 'Virtuales' ? 'selected' : '' }}>Virtuales</option>
                    <option value="Áereos" {{ old('department', $itUser->department) === 'Áereos' ? 'selected' : '' }}>Áereos</option>
                    <option value="Material Vehículos" {{ old('department', $itUser->department) === 'Material Vehículos' ? 'selected' : '' }}>Material Vehículos</option>
                    <option value="Compliance" {{ old('department', $itUser->department) === 'Compliance' ? 'selected' : '' }}>Compliance</option>
                    <option value="Calidad" {{ old('department', $itUser->department) === 'Calidad' ? 'selected' : '' }}>Calidad</option>
                    <option value="Tramitadores" {{ old('department', $itUser->department) === 'Tramitadores' ? 'selected' : '' }}>Tramitadores</option>
                    <option value="Servicio al Cliente" {{ old('department', $itUser->department) === 'Servicio al Cliente' ? 'selected' : '' }}>Servicio al Cliente</option>
                </select>
                @error('department')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="position" class="block text-sm font-medium text-gray-700 mb-2">Cargo *</label>
                <input
                    type="text"
                    id="position"
                    name="position"
                    value="{{ old('position', $itUser->position) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('position') border-red-500 @enderror"
                    required
                >
                @error('position')
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
                    <option value="active" {{ old('status', $itUser->status) === 'active' ? 'selected' : '' }}>Activo</option>
                    <option value="inactive" {{ old('status', $itUser->status) === 'inactive' ? 'selected' : '' }}>Inactivo</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6">
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notas</label>
            <textarea
                id="notes"
                name="notes"
                rows="4"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror"
                placeholder="Información adicional sobre el usuario..."
            >{{ old('notes', $itUser->notes) }}</textarea>
            @error('notes')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('it-users.show', $itUser) }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400">
                Cancelar
            </a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                Actualizar Usuario
            </button>
        </div>
    </form>
</div>
@endsection
