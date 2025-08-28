@forelse($equipment as $item)
    <tr>
        <td class="px-6 py-4 whitespace-nowrap">
            <div>
                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $item->brand }} {{ $item->model }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">S/N: {{ $item->serial_number }}</div>
                @if($item->asset_tag)
                    <div class="text-sm text-gray-500 dark:text-gray-400">Tag: {{ $item->asset_tag }}</div>
                @endif
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
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
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
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
                    <span class="text-gray-500 dark:text-gray-400">Sin Evaluar</span>
                @endif
            @endif
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
            @if($item->currentAssignment)
                {{ $item->currentAssignment->itUser->name }}
            @else
                <span class="text-gray-500 dark:text-gray-400">No asignado</span>
            @endif
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
            @if($item->warranty_end_date)
                <div class="{{ $item->warrantyExpiresIn(30) ? 'text-red-600' : ($item->isWarrantyExpired() ? 'text-gray-500' : 'text-gray-900 dark:text-gray-100') }}">
                    {{ $item->warranty_end_date->format('d/m/Y') }}
                </div>
            @else
                <span class="text-gray-500 dark:text-gray-400">N/A</span>
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
        <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
            No se encontraron equipos
        </td>
    </tr>
@endforelse
