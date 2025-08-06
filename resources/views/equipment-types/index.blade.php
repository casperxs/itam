@extends('layouts.app')

@section('title', 'Tipos de Equipo')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Tipos de Equipo</h2>
            <a href="{{ route('equipment-types.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                Nuevo Tipo
            </a>
        </div>

        @if($equipmentTypes->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nombre
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Categoría
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Descripción
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Equipos
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($equipmentTypes as $type)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $type->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                @switch($type->category)
                                    @case('computer') bg-blue-100 text-blue-800 @break
                                    @case('phone') bg-green-100 text-green-800 @break
                                    @case('printer') bg-purple-100 text-purple-800 @break
                                    @case('license') bg-yellow-100 text-yellow-800 @break
                                    @case('software') bg-red-100 text-red-800 @break
                                    @default bg-gray-100 text-gray-800 @break
                                @endswitch">
                                @switch($type->category)
                                    @case('computer') Computadora @break
                                    @case('phone') Teléfono @break
                                    @case('printer') Impresora @break
                                    @case('license') Licencia @break
                                    @case('software') Software @break
                                    @default {{ ucfirst($type->category) }} @break
                                @endswitch
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-xs truncate">
                                {{ $type->description ?: 'Sin descripción' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs font-medium">
                                {{ $type->equipment_count }} equipo{{ $type->equipment_count != 1 ? 's' : '' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('equipment-types.edit', $type) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    Editar
                                </a>
                                @if($type->equipment_count == 0)
                                <form method="POST" action="{{ route('equipment-types.destroy', $type) }}" 
                                      class="inline"
                                      onsubmit="return confirm('¿Está seguro de que desea eliminar este tipo de equipo?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        Eliminar
                                    </button>
                                </form>
                                @else
                                <span class="text-gray-400" title="No se puede eliminar porque tiene equipos asociados">
                                    Eliminar
                                </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $equipmentTypes->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <div class="text-gray-500 text-lg mb-4">No hay tipos de equipo registrados</div>
            <a href="{{ route('equipment-types.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md inline-block">
                Crear Primer Tipo de Equipo
            </a>
        </div>
        @endif
    </div>
</div>
@endsection