@extends('layouts.app')

@section('title', 'Proveedores')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Proveedores</h2>
            <a href="{{ route('suppliers.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                Nuevo Proveedor
            </a>
        </div>

        <!-- Filtros de búsqueda -->
        <div class="mb-6 bg-gray-50 p-4 rounded-lg">
            <form method="GET" action="{{ route('suppliers.index') }}" class="flex gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Buscar por nombre, contacto o email..."
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex gap-2">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                        Buscar
                    </button>
                    @if(request('search'))
                    <a href="{{ route('suppliers.index') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md">
                        Limpiar
                    </a>
                    @endif
                </div>
            </form>
        </div>

        @if($suppliers->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Proveedor
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Contacto
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email / Teléfono
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            RFC
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Equipos
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Contratos
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($suppliers as $supplier)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $supplier->name }}</div>
                            @if($supplier->address)
                            <div class="text-sm text-gray-500 max-w-xs truncate">{{ $supplier->address }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $supplier->contact_name ?: 'Sin contacto' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                @if($supplier->email)
                                    <div>{{ $supplier->email }}</div>
                                @endif
                                @if($supplier->phone)
                                    <div class="text-gray-500">{{ $supplier->phone }}</div>
                                @endif
                                @if(!$supplier->email && !$supplier->phone)
                                    <span class="text-gray-400">Sin información</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $supplier->tax_id ?: 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                                {{ $supplier->equipment_count }} equipo{{ $supplier->equipment_count != 1 ? 's' : '' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                                {{ $supplier->contracts_count }} contrato{{ $supplier->contracts_count != 1 ? 's' : '' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('suppliers.show', $supplier) }}" 
                                   class="text-green-600 hover:text-green-900">
                                    Ver
                                </a>
                                <a href="{{ route('suppliers.edit', $supplier) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    Editar
                                </a>
                                @if($supplier->equipment_count == 0 && $supplier->contracts_count == 0)
                                <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}" 
                                      class="inline"
                                      onsubmit="return confirm('¿Está seguro de que desea eliminar este proveedor?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        Eliminar
                                    </button>
                                </form>
                                @else
                                <span class="text-gray-400" title="No se puede eliminar porque tiene equipos o contratos asociados">
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
            <div class="text-gray-500 text-lg mb-4">
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