@forelse($maintenanceRecords ?? [] as $maintenance)
    <tr>
        <td class="px-6 py-4 whitespace-nowrap">
            <div>
                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                    {{ $maintenance->equipment->brand ?? 'N/A' }} {{ $maintenance->equipment->model ?? 'N/A' }}
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    S/N: {{ $maintenance->equipment->serial_number ?? 'N/A' }}
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                @if($maintenance->type === 'preventive') bg-green-100 text-green-800
                @elseif($maintenance->type === 'corrective') bg-red-100 text-red-800
                @else bg-blue-100 text-blue-800
                @endif
            ">
                @switch($maintenance->type ?? 'N/A')
                    @case('preventive') Preventivo @break
                    @case('corrective') Correctivo @break
                    @case('update') Actualización @break
                    @default {{ ucfirst($maintenance->type ?? 'N/A') }}
                @endswitch
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
            {{ $maintenance->scheduled_date ? $maintenance->scheduled_date->format('d/m/Y H:i') : 'N/A' }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
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
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
            @if($maintenance->cost)
                ${{ number_format($maintenance->cost, 2) }}
            @else
                <span class="text-gray-500 dark:text-gray-400">-</span>
            @endif
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('maintenance.show', $maintenance) }}" class="text-blue-600 hover:text-blue-900">Ver</a>

                @if($maintenance->status === 'completed')
                    <a href="{{ route('maintenance.checklist', $maintenance) }}" 
                       class="bg-red-600 text-white px-2 py-1 rounded text-xs hover:bg-red-700 font-semibold" 
                       title="Descargar Checklist PDF">
                        📋 CHECKLIST
                    </a>
                @endif

                @if($maintenance->status === 'scheduled')
                    <form method="POST" action="{{ route('maintenance.start', $maintenance) }}" class="inline">
                        @csrf
                        <button type="submit" class="text-green-600 hover:text-green-900">Iniciar</button>
                    </form>
                @endif


                <a href="{{ route('maintenance.edit', $maintenance) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>

                <form method="POST" action="{{ route('maintenance.destroy', $maintenance) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Está seguro?')">
                        Eliminar
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
            No se encontraron registros de mantenimiento
        </td>
    </tr>
@endforelse
