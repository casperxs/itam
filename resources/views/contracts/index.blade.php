@extends('layouts.app')

@section('title', 'Contratos - ITAM System')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Contratos de Proveedores</h1>
        <p class="text-gray-600 dark:text-gray-300">Gestión de contratos y servicios con proveedores</p>
    </div>
    <a href="{{ route('contracts.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
        Nuevo Contrato
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
                    placeholder="Número de contrato, proveedor, servicio..."
                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                <select name="status" class="px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activo</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Vencido</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Proveedor</label>
                <select name="supplier_id" class="px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    @foreach($suppliers ?? [] as $supplier)
                        <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Vencimiento</label>
                <select name="expiry" class="px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="30" {{ request('expiry') === '30' ? 'selected' : '' }}>Próximos 30 días</option>
                    <option value="60" {{ request('expiry') === '60' ? 'selected' : '' }}>Próximos 60 días</option>
                    <option value="90" {{ request('expiry') === '90' ? 'selected' : '' }}>Próximos 90 días</option>
                </select>
            </div>
            <button type="submit" class="bg-gray-600 dark:bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-700 dark:hover:bg-gray-600">
                Filtrar
            </button>
            <a href="{{ route('contracts.index') }}" class="bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500">
                Limpiar
            </a>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Contrato</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Proveedor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Servicio</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Vigencia</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Costo Mensual</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($contracts ?? [] as $contract)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $contract->contract_number }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    Creado: {{ $contract->created_at ? $contract->created_at->format('d/m/Y') : 'N/A' }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ $contract->supplier->name ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ $contract->supplier->email ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ $contract->service_description }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            <div>
                                <div>Inicio: {{ $contract->start_date ? $contract->start_date->format('d/m/Y') : 'N/A' }}</div>
                                <div class="{{ $contract->isExpired() ? 'text-red-600 dark:text-red-400' : ($contract->needsAlert() ? 'text-yellow-600 dark:text-yellow-400' : 'text-gray-900 dark:text-gray-100') }}">
                                    Fin: {{ $contract->end_date ? $contract->end_date->format('d/m/Y') : 'N/A' }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            @if($contract->monthly_cost)
                                ${{ number_format($contract->monthly_cost, 2) }}
                            @else
                                <span class="text-gray-500 dark:text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($contract->isExpired())
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                    Vencido
                                </span>
                            @elseif($contract->needsAlert())
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">
                                    Por Vencer
                                </span>
                            @elseif($contract->status === 'active')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                    Activo
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200">
                                    {{ ucfirst($contract->status ?? 'N/A') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('contracts.show', $contract) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 mr-3">Ver</a>

                            @if($contract->contract_file)
                                <a href="{{ route('contracts.download', $contract) }}" class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300 mr-3">Descargar</a>
                            @endif

                            @if($contract->isExpired() || $contract->needsAlert())
                                <a href="{{ route('contracts.renew', $contract) }}" class="text-purple-600 dark:text-purple-400 hover:text-purple-900 dark:hover:text-purple-300 mr-3">Renovar</a>
                            @endif

                            <a href="{{ route('contracts.edit', $contract) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-3">Editar</a>

                            <form method="POST" action="{{ route('contracts.destroy', $contract) }}" class="inline">
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
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            No se encontraron contratos
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($contracts) && $contracts->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $contracts->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<!-- Alertas de contratos próximos a vencer -->
@if(isset($expiringContracts) && $expiringContracts->count() > 0)
<div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
            <svg class="w-5 h-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.728-.833-2.498 0L4.616 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            Contratos Próximos a Vencer
        </h3>
    </div>
    <div class="px-6 py-4">
        <div class="space-y-3">
            @foreach($expiringContracts as $contract)
                <div class="flex items-center justify-between p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-700">
                    <div>
                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ $contract->contract_number }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $contract->supplier->name }} - {{ $contract->service_description }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-red-600 dark:text-red-400">
                            Vence: {{ $contract->end_date->format('d/m/Y') }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $contract->end_date->diffForHumans() }}
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('contracts.renew', $contract) }}" class="bg-yellow-600 text-white px-3 py-1 rounded text-sm hover:bg-yellow-700">
                            Renovar
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection
