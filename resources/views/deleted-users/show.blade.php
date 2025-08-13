@extends('layouts.app')

@section('title', 'Detalle Usuario Eliminado - ITAM System')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">{{ $deletedUser->name }} (Eliminado)</h1>
        <p class="text-gray-600">Detalle completo del usuario eliminado</p>
    </div>
    <div class="flex gap-2">
        <form method="POST" action="{{ route('deleted-users.restore', $deletedUser) }}" class="inline">
            @csrf
            @method('PATCH')
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700" onclick="return confirm('¿Restaurar este usuario?')">
                Restaurar Usuario
            </button>
        </form>
        <a href="{{ route('deleted-users.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            Volver al Listado
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Información del Usuario -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Información Personal</h2>
            
            <div class="space-y-4">
                <div class="flex items-center justify-center w-20 h-20 mx-auto bg-red-100 rounded-full mb-4">
                    <span class="text-2xl font-bold text-red-600">{{ substr($deletedUser->name, 0, 2) }}</span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">ID Original</label>
                    <p class="text-lg text-gray-900">{{ $deletedUser->original_user_id }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Nombre Completo</label>
                    <p class="text-lg text-gray-900">{{ $deletedUser->name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Email</label>
                    <p class="text-lg text-gray-900">{{ $deletedUser->email }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">ID Empleado</label>
                    <p class="text-lg text-gray-900">{{ $deletedUser->employee_id ?? 'No especificado' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Departamento</label>
                    <p class="text-lg text-gray-900">{{ $deletedUser->department ?? 'No especificado' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Posición</label>
                    <p class="text-lg text-gray-900">{{ $deletedUser->position ?? 'No especificado' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Estado Anterior</label>
                    <p class="text-lg text-gray-900">{{ $deletedUser->status ?? 'No especificado' }}</p>
                </div>
            </div>
        </div>

        <!-- Información de Eliminación -->
        <div class="bg-red-50 rounded-lg shadow p-6 mt-6">
            <h2 class="text-xl font-semibold text-red-800 mb-4">Información de Eliminación</h2>
            
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-red-600">Fecha de Eliminación</label>
                    <p class="text-lg text-red-900">{{ $deletedUser->deleted_at->format('d/m/Y H:i:s') }}</p>
                    <p class="text-sm text-red-600">{{ $deletedUser->deleted_at->diffForHumans() }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-red-600">Razón de Eliminación</label>
                    <p class="text-lg text-red-900">{{ $deletedUser->deleted_reason ?? 'Sin especificar' }}</p>
                </div>

                @if($deletedUser->notes)
                <div>
                    <label class="block text-sm font-medium text-red-600">Notas</label>
                    <p class="text-sm text-red-900 whitespace-pre-line">{{ $deletedUser->notes }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Histórico de Asignaciones -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Histórico de Asignaciones de Equipos</h2>
            
            @if($assignments->count() > 0)
                <div class="space-y-4">
                    @foreach($assignments as $assignment)
                        <div class="border border-gray-200 rounded-lg p-4 {{ $assignment->isActive() ? 'bg-yellow-50 border-yellow-300' : 'bg-gray-50' }}">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">
                                        {{ $assignment->equipment->name ?? 'Equipo eliminado' }}
                                        @if($assignment->isActive())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 ml-2">
                                                Activa
                                            </span>
                                        @endif
                                    </h3>
                                    
                                    @if($assignment->equipment)
                                        <p class="text-sm text-gray-600">
                                            {{ $assignment->equipment->equipmentType->name ?? 'Tipo no especificado' }} - 
                                            Serie: {{ $assignment->equipment->serial_number ?? 'N/A' }}
                                        </p>
                                    @endif

                                    <div class="mt-2 grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <span class="font-medium text-gray-500">Fecha de Asignación:</span>
                                            <p class="text-gray-900">{{ $assignment->assigned_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                        @if($assignment->returned_at)
                                            <div>
                                                <span class="font-medium text-gray-500">Fecha de Devolución:</span>
                                                <p class="text-gray-900">{{ $assignment->returned_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    @if($assignment->assignment_notes)
                                        <div class="mt-2">
                                            <span class="font-medium text-gray-500 text-sm">Notas de Asignación:</span>
                                            <p class="text-sm text-gray-700">{{ $assignment->assignment_notes }}</p>
                                        </div>
                                    @endif

                                    @if($assignment->return_notes)
                                        <div class="mt-2">
                                            <span class="font-medium text-gray-500 text-sm">Notas de Devolución:</span>
                                            <p class="text-sm text-gray-700">{{ $assignment->return_notes }}</p>
                                        </div>
                                    @endif
                                </div>

                                @if($assignment->equipment)
                                    <a href="{{ route('equipment.show', $assignment->equipment) }}" 
                                       class="ml-4 text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Ver Equipo
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <h3 class="font-semibold text-blue-900 mb-2">Resumen de Asignaciones</h3>
                    <div class="grid grid-cols-3 gap-4 text-sm">
                        <div class="text-center">
                            <p class="font-bold text-2xl text-blue-600">{{ $assignments->count() }}</p>
                            <p class="text-blue-800">Total Asignaciones</p>
                        </div>
                        <div class="text-center">
                            <p class="font-bold text-2xl text-green-600">{{ $assignments->whereNotNull('returned_at')->count() }}</p>
                            <p class="text-green-800">Devueltos</p>
                        </div>
                        <div class="text-center">
                            <p class="font-bold text-2xl text-yellow-600">{{ $assignments->whereNull('returned_at')->count() }}</p>
                            <p class="text-yellow-800">Sin Devolver</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <p>No se encontraron asignaciones para este usuario.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@if(session('success'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
        {{ session('success') }}
    </div>
@endif
@endsection