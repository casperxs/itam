@extends('layouts.app')

@section('title', 'Editar Tipo de Equipo')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Editar Tipo de Equipo</h2>
            <a href="{{ route('equipment-types.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                Volver
            </a>
        </div>

        <form action="{{ route('equipment-types.update', $equipmentType) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre *
                    </label>
                    <input type="text" name="name" id="name" 
                           value="{{ old('name', $equipmentType->name) }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           required
                           placeholder="Ej: Laptop Dell, iPhone, Impresora HP">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                        Categoría *
                    </label>
                    <select name="category" id="category" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            required>
                        <option value="">Seleccionar categoría</option>
                        <option value="computer" {{ old('category', $equipmentType->category) == 'computer' ? 'selected' : '' }}>Computadora</option>
                        <option value="phone" {{ old('category', $equipmentType->category) == 'phone' ? 'selected' : '' }}>Teléfono</option>
                        <option value="printer" {{ old('category', $equipmentType->category) == 'printer' ? 'selected' : '' }}>Impresora</option>
                        <option value="license" {{ old('category', $equipmentType->category) == 'license' ? 'selected' : '' }}>Licencia</option>
                        <option value="software" {{ old('category', $equipmentType->category) == 'software' ? 'selected' : '' }}>Software</option>
                    </select>
                    @error('category')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción
                    </label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Descripción detallada del tipo de equipo...">{{ old('description', $equipmentType->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                @if($equipmentType->equipment()->count() > 0)
                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                    <div class="flex">
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">
                                Información
                            </h3>
                            <div class="text-sm text-blue-700 mt-1">
                                Este tipo de equipo tiene {{ $equipmentType->equipment()->count() }} equipo(s) asociado(s).
                                Los cambios se aplicarán a todos los equipos existentes.
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="mt-6 flex gap-4">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                    Actualizar Tipo de Equipo
                </button>
                <a href="{{ route('equipment-types.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-md">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection