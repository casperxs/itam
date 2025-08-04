@extends('layouts.app')

@section('title', 'Proveedores')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Proveedores</h2>
            <a href="{{ route('suppliers.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                Nuevo Proveedor
            </a>
        </div>

        <!-- Filtros de búsqueda -->
        <div class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
            <form method="GET" action="{{ route('suppliers.index') }}" class="flex gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Buscar por nombre, contacto o email..."
                           class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-white rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex gap-2">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                        Buscar
                    </button>
                    @if(request('search'))
                    <a href="{{ route('suppliers.index') }}" 
                       class="bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-md">
                        Limpiar
                    </a>
                    @endif
                </div>
            </form>
        </div>

        @if($suppliers->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Proveedor
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Contacto
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Email / Teléfono
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            RFC
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Equipos
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Contratos
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($suppliers as $supplier)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $supplier->name }}</div>
                            @if($supplier->address)
                            <div class="text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">{{ $supplier->address }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                {{ $supplier->contact_name ?: 'Sin contacto' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                @if($supplier->email)
                                    <div>{{ $supplier->email }}</div>
                                @endif
                                @if($supplier->phone)
                                    <div class="text-gray-500 dark:text-gray-400">{{ $supplier->phone }}</div>
                                @endif
                                @if(!$supplier->email && !$supplier->phone)
                                    <span class="text-gray-400 dark:text-gray-500">Sin información</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                {{ $supplier->tax_id ?: 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded-full text-xs font-medium">
                                {{ $supplier->equipment_count }} equipo{{ $supplier->equipment_count != 1 ? 's' : '' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-2 py-1 rounded-full text-xs font-medium">
                                {{ $supplier->contracts_count }} contrato{{ $supplier->contracts_count != 1 ? 's' : '' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('suppliers.show', $supplier) }}" 
                                   class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300">
                                    Ver
                                </a>
                                <a href="{{ route('suppliers.edit', $supplier) }}" 
                                   class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
                                    Editar
                                </a>
                                @if($supplier->equipment_count == 0 && $supplier->contracts_count == 0)
                                <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}" 
                                      class="inline"
                                      onsubmit="return confirm('¿Está seguro de que desea eliminar este proveedor?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                        Eliminar
                                    </button>
                                </form>
                                @else
                                <span class="text-gray-400 dark:text-gray-500" title="No se puede eliminar porque tiene equipos o contratos asociados">
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
            {{ $suppliers->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <div class="text-gray-500 dark:text-gray-400 text-lg mb-4">
                @if(request('search'))
                    No se encontraron proveedores que coincidan con su búsqueda
                @else
                    No hay proveedores registrados
                @endif
            </div>
            @if(!request('search'))
            <a href="{{ route('suppliers.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md inline-block">
                Crear Primer Proveedor
            </a>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection