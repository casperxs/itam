@forelse($assignments ?? [] as $assignment)
    <tr>
        <td class="px-6 py-4 whitespace-nowrap">
            <div>
                <div class="text-sm font-medium text-gray-900">
                    {{ $assignment->equipment->brand ?? 'N/A' }} {{ $assignment->equipment->model ?? 'N/A' }}
                </div>
                <div class="text-sm text-gray-500">
                    S/N: {{ $assignment->equipment->serial_number ?? 'N/A' }}
                </div>
                @if($assignment->equipment && $assignment->equipment->asset_tag)
                    <div class="text-sm text-gray-500">
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
                            {{ substr($assignment->getUserName(), 0, 2) }}
                        </span>
                    </div>
                </div>
                <div class="ml-3">
                    <div class="text-sm font-medium text-gray-900">{{ $assignment->getUserName() }}</div>
                    <div class="text-sm text-gray-500">{{ $assignment->getUserDepartment() ?: 'N/A' }}</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $assignment->assigned_at ? $assignment->assigned_at->format('d/m/Y') : 'N/A' }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
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
            <a href="{{ route('assignments.show', $assignment) }}" class="text-blue-600 hover:text-blue-900 mr-3">Ver</a>

            @if(!$assignment->returned_at)
                <a href="{{ route('assignments.return', $assignment) }}" class="text-green-600 hover:text-green-900 mr-3">Devolver</a>
            @endif

            @if($assignment->assignment_document)
                <a href="{{ route('assignments.download', $assignment) }}" class="text-purple-600 hover:text-purple-900 mr-3">Descargar</a>
            @endif

            <form method="POST" action="{{ route('assignments.destroy', $assignment) }}" class="inline">
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
        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
            No se encontraron asignaciones
        </td>
    </tr>
@endforelse
