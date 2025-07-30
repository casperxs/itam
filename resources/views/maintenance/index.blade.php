@extends('layouts.app')

@section('title', 'Mantenimiento - ITAM System')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Mantenimiento de Equipos</h1>
        <p class="text-gray-600">Gestión de mantenimientos programados y ejecutados</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('maintenance.calendar') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
            Ver Calendario
        </a>
        <a href="{{ route('maintenance.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Programar Mantenimiento
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <form method="GET" class="flex gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Equipo, técnico, descripción..."
                    class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select name="status" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Programado</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>En Progreso</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completado</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                <select name="type" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="preventive" {{ request('type') === 'preventive' ? 'selected' : '' }}>Preventivo</option>
                    <option value="corrective" {{ request('type') === 'corrective' ? 'selected' : '' }}>Correctivo</option>
                    <option value="upgrade" {{ request('type') === 'upgrade' ? 'selected' : '' }}>Actualización</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                <input
                    type="date"
                    name="date"
                    value="{{ request('date') }}"
                    class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>
            <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                Filtrar
            </button>
            <a href="{{ route('maintenance.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Programada</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Técnico</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Costo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($maintenanceRecords ?? [] as $maintenance)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $maintenance->equipment->brand ?? 'N/A' }} {{ $maintenance->equipment->model ?? 'N/A' }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    S/N: {{ $maintenance->equipment->serial_number ?? 'N/A' }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($maintenance->maintenance_type === 'preventive') bg-green-100 text-green-800
                                @elseif($maintenance->maintenance_type === 'corrective') bg-red-100 text-red-800
                                @else bg-blue-100 text-blue-800
                                @endif
                            ">
                                {{ ucfirst($maintenance->maintenance_type ?? 'N/A') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $maintenance->scheduled_date ? $maintenance->scheduled_date->format('d/m/Y H:i') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $maintenance->performedBy->name ?? 'No asignado' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($maintenance->status === 'scheduled') bg-yellow-100 text-yellow-800
                                @elseif($maintenance->status === 'in_progress') bg-blue-100 text-blue-800
                                @elseif($maintenance->status === 'completed') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800
                                @endif
                            ">
                                {{ ucfirst($maintenance->status ?? 'N/A') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($maintenance->cost)
                                ${{ number_format($maintenance->cost, 2) }}
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('maintenance.show', $maintenance) }}" class="text-blue-600 hover:text-blue-900 mr-3">Ver</a>

                            @if($maintenance->status === 'scheduled')
                                <form method="POST" action="{{ route('maintenance.start', $maintenance) }}" class="inline mr-3">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900">Iniciar</button>
                                </form>
                            @endif

                            @if($maintenance->status === 'in_progress')
                                <form method="POST" action="{{ route('maintenance.complete', $maintenance) }}" class="inline mr-3">
                                    @csrf
                                    <button type="submit" class="text-purple-600 hover:text-purple-900">Completar</button>
                                </form>
                            @endif

                            <a href="{{ route('maintenance.edit', $maintenance) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>

                            <form method="POST" action="{{ route('maintenance.destroy', $maintenance) }}" class="inline">
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
                            No se encontraron registros de mantenimiento
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($maintenanceRecords) && $maintenanceRecords->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $maintenanceRecords->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<!-- Próximos mantenimientos -->
<div class="mt-8 bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Próximos Mantenimientos (7 días)</h3>
    </div>
    <div class="px-6 py-4">
        @if(isset($upcomingMaintenance) && $upcomingMaintenance->count() > 0)
            <div class="space-y-3">
                @foreach($upcomingMaintenance as $maintenance)
                    <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">
                                {{ $maintenance->equipment->brand }} {{ $maintenance->equipment->model }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $maintenance->description }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">
                                {{ $maintenance->scheduled_date->format('d/m/Y H:i') }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $maintenance->performedBy->name ?? 'Sin asignar' }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center py-4">No hay mantenimientos programados para los próximos 7 días</p>
        @endif
    </div>
</div>
@endsection
