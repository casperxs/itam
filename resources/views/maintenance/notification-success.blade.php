@extends('layouts.app')

@section('title', 'Mantenimiento Programado - ITAM System')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Mensaje de Éxito -->
    <div class="bg-green-50 border-l-4 border-green-400 p-6 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-green-800">¡Mantenimiento Programado Exitosamente!</h3>
                <div class="mt-2 text-sm text-green-700">
                    <p>El mantenimiento ha sido registrado en el sistema y se ha generado un evento de calendario.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Detalles del Mantenimiento -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">📋 Detalles del Mantenimiento</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">ID de Mantenimiento</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">#{{ $maintenance->id }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tipo</dt>
                        <dd class="mt-1">
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                @if($maintenance->type === 'preventive') bg-green-100 text-green-800
                                @elseif($maintenance->type === 'corrective') bg-red-100 text-red-800
                                @else bg-blue-100 text-blue-800
                                @endif">
                                @switch($maintenance->type)
                                    @case('preventive') Mantenimiento Preventivo @break
                                    @case('corrective') Mantenimiento Correctivo @break
                                    @case('update') Actualización @break
                                @endswitch
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Fecha y Hora Programada</dt>
                        <dd class="mt-1 text-gray-900">{{ $maintenance->scheduled_date->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Técnico Asignado</dt>
                        <dd class="mt-1 text-gray-900">{{ $maintenance->performedBy->name ?? 'No asignado' }}</dd>
                    </div>
                </dl>
            </div>
            
            <div>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Equipo</dt>
                        <dd class="mt-1 text-gray-900">
                            {{ $maintenance->equipment->equipmentType->name ?? 'N/A' }}<br>
                            <span class="text-sm text-gray-600">
                                {{ $maintenance->equipment->brand ?? 'N/A' }} {{ $maintenance->equipment->model ?? 'N/A' }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Usuario Asignado</dt>
                        <dd class="mt-1 text-gray-900">
                            {{ $maintenance->equipment->currentAssignment->itUser->name ?? 'Sin asignar' }}
                            @if($maintenance->equipment->currentAssignment->itUser->department)
                                <br><span class="text-sm text-gray-600">{{ $maintenance->equipment->currentAssignment->itUser->department }}</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Estado</dt>
                        <dd class="mt-1">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                Programado
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
        
        @if($maintenance->description)
        <div class="mt-6">
            <dt class="text-sm font-medium text-gray-500">Descripción del Trabajo</dt>
            <dd class="mt-1 text-gray-900">{{ $maintenance->description }}</dd>
        </div>
        @endif
    </div>

    <!-- Notificación por Correo -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
        <div class="flex items-center mb-4">
            <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            <h3 class="text-lg font-medium text-blue-900">📧 Notificación por Correo Electrónico</h3>
        </div>
        
        <p class="text-blue-800 mb-4">
            Se ha preparado un correo electrónico de notificación con un archivo de calendario (.ics) para el usuario. 
            Este archivo puede ser importado directamente a su calendario personal (Outlook, Gmail, Apple Calendar, etc.).
        </p>

        @if(!empty($emailData['to']))
        <div class="bg-white border border-blue-300 rounded-lg p-4 mb-4">
            <h4 class="font-medium text-gray-900 mb-2">📬 Detalles del Correo:</h4>
            <ul class="text-sm text-gray-700 space-y-1">
                <li><strong>Para:</strong> {{ implode(', ', $emailData['to']) }}</li>
                @if(!empty($emailData['cc']))
                <li><strong>Copia:</strong> {{ implode(', ', $emailData['cc']) }}</li>
                @endif
                <li><strong>De:</strong> soporteit@bkb.mx</li>
                <li><strong>Asunto:</strong> {{ $emailData['subject'] }}</li>
                <li><strong>Adjunto:</strong> {{ $icsFilename }}</li>
            </ul>
        </div>
        @endif

        <div class="flex flex-wrap gap-3">
            <!-- Botón para abrir cliente de correo -->
            <a href="mailto:{{ implode(',', $emailData['to']) }}{{ !empty($emailData['cc']) ? '?cc=' . implode(',', $emailData['cc']) : '?' }}subject={{ urlencode($emailData['subject']) }}&body={{ urlencode($emailData['body']) }}" 
               class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-semibold transition-colors inline-flex items-center" 
               id="email-link">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                📧 Abrir Cliente de Correo
            </a>

            <!-- Botón para descargar ICS directamente -->
            <a href="{{ route('maintenance.download-ics', $maintenance) }}" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 font-semibold transition-colors inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                </svg>
                📅 Descargar Archivo de Calendario
            </a>
        </div>

        <div class="mt-4 p-3 bg-blue-100 rounded-lg">
            <p class="text-sm text-blue-800">
                <strong>💡 Tip:</strong> El archivo .ics incluye recordatorios automáticos (15 minutos y 1 día antes del mantenimiento) 
                y puede ser compartido con otros usuarios si es necesario.
            </p>
        </div>
    </div>

    <!-- Acciones Adicionales -->
    <div class="bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">🎯 ¿Qué sigue?</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="text-center">
                    <svg class="w-8 h-8 text-blue-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h4 class="font-medium text-gray-900">Ver Detalles</h4>
                    <p class="text-sm text-gray-600 mb-3">Revisar toda la información del mantenimiento</p>
                    <a href="{{ route('maintenance.show', $maintenance) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                        Ver Mantenimiento →
                    </a>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="text-center">
                    <svg class="w-8 h-8 text-green-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h4 class="font-medium text-gray-900">Ver Calendario</h4>
                    <p class="text-sm text-gray-600 mb-3">Visualizar todos los mantenimientos programados</p>
                    <a href="{{ route('maintenance.calendar') }}" class="text-green-600 hover:text-green-800 font-medium">
                        Abrir Calendario →
                    </a>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="text-center">
                    <svg class="w-8 h-8 text-purple-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <h4 class="font-medium text-gray-900">Programar Otro</h4>
                    <p class="text-sm text-gray-600 mb-3">Crear un nuevo mantenimiento</p>
                    <a href="{{ route('maintenance.create') }}" class="text-purple-600 hover:text-purple-800 font-medium">
                        Nuevo Mantenimiento →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Botones de Navegación -->
    <div class="mt-6 flex justify-center">
        <a href="{{ route('maintenance.index') }}" class="bg-gray-600 text-white px-8 py-3 rounded-lg hover:bg-gray-700 font-semibold">
            ← Volver al Listado de Mantenimientos
        </a>
    </div>
</div>

<!-- Script para abrir automáticamente el correo (opcional) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-abrir cliente de correo después de 2 segundos si el usuario no ha hecho clic
    let emailOpened = false;
    
    document.getElementById('email-link').addEventListener('click', function() {
        emailOpened = true;
    });
    
    setTimeout(function() {
        if (!emailOpened) {
            // Preguntar al usuario si quiere abrir el cliente de correo automáticamente
            if (confirm('¿Deseas abrir automáticamente el cliente de correo para enviar la notificación?')) {
                document.getElementById('email-link').click();
            }
        }
    }, 2000);
});
</script>
@endsection
