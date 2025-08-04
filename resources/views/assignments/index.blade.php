@extends('layouts.app')

@section('title', 'Asignaciones - ITAM System')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Asignaciones de Equipos</h1>
        <p class="text-gray-600 dark:text-gray-300">Gestión de asignaciones de equipos a usuarios</p>
    </div>
    <a href="{{ route('assignments.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
        Nueva Asignación
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
                    placeholder="Equipo, usuario, empleado..."
                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select name="status" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activas</option>
                    <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Devueltas</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Desde</label>
                <input
                    type="date"
                    name="date_from"
                    value="{{ request('date_from') }}"
                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Hasta</label>
                <input
                    type="date"
                    name="date_to"
                    value="{{ request('date_to') }}"
                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>
            <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                Filtrar
            </button>
            <a href="{{ route('assignments.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md">
                Limpiar
            </a>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Equipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Usuario</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha Asignación</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha Devolución</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($assignments ?? [] as $assignment)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $assignment->equipment->brand ?? 'N/A' }} {{ $assignment->equipment->model ?? 'N/A' }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    S/N: {{ $assignment->equipment->serial_number ?? 'N/A' }}
                                </div>
                                @if($assignment->equipment && $assignment->equipment->asset_tag)
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        Tag: {{ $assignment->equipment->asset_tag }}
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-blue-600 font-medium text-xs">
                                            {{ substr($assignment->itUser->name ?? 'N/A', 0, 2) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $assignment->itUser->name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $assignment->itUser->department ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $assignment->assigned_at ? $assignment->assigned_at->format('d/m/Y') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $assignment->returned_at ? $assignment->returned_at->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($assignment->returned_at)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Devuelto
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Activo
                                </span>
                                @if($assignment->document_signed)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 ml-1">
                                        Firmado
                                    </span>
                                @endif
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('assignments.show', $assignment) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 mr-3">Ver</a>

                            @if(!$assignment->returned_at)
                                <a href="{{ route('assignments.return', $assignment) }}" class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300 mr-3">Devolver</a>
                            @endif

                            @if($assignment->assignment_document)
                                <a href="{{ route('assignments.download', $assignment) }}" class="text-purple-600 dark:text-purple-400 hover:text-purple-900 dark:hover:text-purple-300 mr-3">Descargar</a>
                            @endif

                            <form method="POST" action="{{ route('assignments.destroy', $assignment) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300" onclick="return confirm('¿Está seguro?')">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            No se encontraron asignaciones
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($assignments) && $assignments->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $assignments->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
