@extends('layouts.app')

@section('title', 'Crear Tipo de Equipo')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Crear Nuevo Tipo de Equipo</h2>
            <a href="{{ route('equipment-types.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                Volver
            </a>
        </div>

        <form action="{{ route('equipment-types.store') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre *
                    </label>
                    <input type="text" name="name" id="name" 
                           value="{{ old('name') }}"
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
                        <option value="computer" {{ old('category') == 'computer' ? 'selected' : '' }}>Computadora</option>
                        <option value="phone" {{ old('category') == 'phone' ? 'selected' : '' }}>Teléfono</option>
                        <option value="printer" {{ old('category') == 'printer' ? 'selected' : '' }}>Impresora</option>
                        <option value="license" {{ old('category') == 'license' ? 'selected' : '' }}>Licencia</option>
                        <option value="software" {{ old('category') == 'software' ? 'selected' : '' }}>Software</option>
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
                              placeholder="Descripción detallada del tipo de equipo...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex gap-4">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                    Crear Tipo de Equipo
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