@extends('layouts.app')

@section('title', 'Equipos - ITAM System')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">Equipos</h1>
        <p class="text-gray-600 dark:text-gray-400">Gestión de equipos informáticos</p>
    </div>
    <a href="{{ route('equipment.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
        Nuevo Equipo
    </a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <form method="GET" class="flex gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar</label>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Serial, Asset Tag, Marca, Modelo..."
                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                <select name="status" class="px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Disponible</option>
                    <option value="assigned" {{ request('status') === 'assigned' ? 'selected' : '' }}>Asignado</option>
                    <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Mantenimiento</option>
                    <option value="retired" {{ request('status') === 'retired' ? 'selected' : '' }}>Retirado</option>
                    <option value="lost" {{ request('status') === 'lost' ? 'selected' : '' }}>Perdido</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo</label>
                <select name="type" class="px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    @foreach($equipmentTypes as $type)
                        <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-gray-600 dark:bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-700 dark:hover:bg-gray-600">
                Filtrar
            </button>
            <a href="{{ route('equipment.index') }}" class="bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500">
                Limpiar
            </a>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Equipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valoración</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asignado a</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Garantía</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($equipment as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $item->brand }} {{ $item->model }}</div>
                                <div class="text-sm text-gray-500">S/N: {{ $item->serial_number }}</div>
                                @if($item->asset_tag)
                                    <div class="text-sm text-gray-500">Tag: {{ $item->asset_tag }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->equipmentType->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($item->status === 'available') bg-green-100 text-green-800
                                @elseif($item->status === 'assigned') bg-blue-100 text-blue-800
                                @elseif($item->status === 'maintenance') bg-yellow-100 text-yellow-800
                                @elseif($item->status === 'retired') bg-gray-100 text-gray-800
                                @else bg-red-100 text-red-800
                                @endif
                            ">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($item->valoracion)
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    @if(str_contains($item->valoracion, 'Excelente')) bg-green-100 text-green-800
                                    @elseif(str_contains($item->valoracion, 'Óptimo')) bg-blue-100 text-blue-800
                                    @elseif(str_contains($item->valoracion, 'Regular')) bg-yellow-100 text-yellow-800
                                    @elseif(str_contains($item->valoracion, 'Para Cambio')) bg-orange-100 text-orange-800
                                    @elseif(str_contains($item->valoracion, 'Reemplazo')) bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $item->valoracion }}
                                </span>
                            @else
                                @if($item->isNewEquipment())
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Nuevo</span>
                                @else
                                    <span class="text-gray-500">Sin Evaluar</span>
                                @endif
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($item->currentAssignment)
                                {{ $item->currentAssignment->itUser->name }}
                            @else
                                <span class="text-gray-500">No asignado</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($item->warranty_end_date)
                                <div class="{{ $item->warrantyExpiresIn(30) ? 'text-red-600' : ($item->isWarrantyExpired() ? 'text-gray-500' : 'text-gray-900') }}">
                                    {{ $item->warranty_end_date->format('d/m/Y') }}
                                </div>
                            @else
                                <span class="text-gray-500">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('equipment.show', $item) }}" class="text-blue-600 hover:text-blue-900 mr-3">Ver</a>
                            <a href="{{ route('equipment.edit', $item) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                            <form method="POST" action="{{ route('equipment.destroy', $item) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Está seguro?')">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            No se encontraron equipos
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($equipment->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $equipment->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
