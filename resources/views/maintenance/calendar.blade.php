@extends('layouts.app')

@section('title', 'Calendario de Mantenimientos')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">Calendario de Mantenimientos</h1>
            <p class="text-gray-600 dark:text-gray-400">Vista de calendario para programación de mantenimientos</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('maintenance.index') }}" 
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                Lista
            </a>
            <a href="{{ route('maintenance.create') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Nuevo Mantenimiento
            </a>
        </div>
    </div>
</div>

<!-- Leyenda de colores -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-6">
    <h3 class="text-lg font-semibold mb-3 text-gray-900 dark:text-gray-100">Leyenda:</h3>
    <div class="flex flex-wrap gap-6">
        <div class="flex items-center">
            <div class="w-4 h-4 bg-yellow-500 rounded mr-2"></div>
            <span class="text-sm">Programado</span>
        </div>
        <div class="flex items-center">
            <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
            <span class="text-sm">En Progreso</span>
        </div>
        <div class="flex items-center">
            <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
            <span class="text-sm">Completado</span>
        </div>
        <div class="flex items-center">
            <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
            <span class="text-sm">Cancelado</span>
        </div>
        <div class="flex items-center">
            <div class="w-4 h-4 bg-yellow-500 rounded mr-2"></div>
            <span class="text-sm">Próximos (de equipos)</span>
        </div>
    </div>
</div>

<!-- Calendario -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
    <div id="calendar"></div>
</div>

<!-- Modal para detalles del evento -->
<div id="eventModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Detalles del Mantenimiento</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="modalContent"></div>
            <div class="mt-6 flex justify-end">
                <button onclick="viewDetails()" class="bg-blue-600 text-white px-4 py-2 rounded mr-2 hover:bg-blue-700">
                    Ver Detalles
                </button>
                <button onclick="closeModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<style>
    .fc-toolbar-title {
        font-size: 1.5rem !important;
        font-weight: 600 !important;
    }
    .fc-button {
        background-color: #3b82f6 !important;
        border-color: #3b82f6 !important;
    }
    .fc-button:hover {
        background-color: #2563eb !important;
        border-color: #2563eb !important;
    }
    .fc-button:disabled {
        opacity: 0.6 !important;
    }
    .fc-event {
        border-radius: 4px !important;
        border: none !important;
        color: white !important;
    }
    .fc-daygrid-event {
        margin: 1px 0 !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales/es.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado, inicializando calendario...');
    
    const calendarEl = document.getElementById('calendar');
    let currentEventUrl = '';
    
    console.log('Elemento calendario:', calendarEl);
    console.log('URL de eventos:', '{{ route("maintenance.calendar.events") }}');

    // Verificar si FullCalendar está cargado
    if (typeof FullCalendar === 'undefined') {
        console.error('FullCalendar no está cargado');
        calendarEl.innerHTML = '<p class="text-red-600 text-center py-8">Error: No se pudo cargar el calendario. Por favor, recarga la página.</p>';
        return;
    }
    
    console.log('FullCalendar detectado, versión:', FullCalendar.VERSION || 'desconocida');
    
    try {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                day: 'Día'
            },
            events: {
                url: '{{ route("maintenance.calendar.events") }}',
                method: 'GET',
                failure: function(error) {
                    console.error('Error al cargar eventos:', error);
                    alert('Error al cargar los eventos del calendario. Revisa la consola para más detalles.');
                },
                success: function(events) {
                    console.log('Eventos cargados exitosamente:', events.length, 'eventos');
                }
            },
            eventClick: function(info) {
                info.jsEvent.preventDefault();
                
                const event = info.event;
                currentEventUrl = event.url;
                
                // Obtener información del evento
                const modalContent = document.getElementById('modalContent');
                const extendedProps = event.extendedProps || {};
                
                modalContent.innerHTML = `
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Equipo:</label>
                            <p class="text-sm text-gray-900">${extendedProps.equipment || event.title}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipo de Mantenimiento:</label>
                            <p class="text-sm text-gray-900">${extendedProps.type || 'No especificado'}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fecha:</label>
                            <p class="text-sm text-gray-900">${event.start.toLocaleDateString('es-ES', {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            })}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Estado:</label>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full" 
                                  style="background-color: ${event.backgroundColor}20; color: ${event.backgroundColor};">
                                ${extendedProps.status || getStatusText(event.backgroundColor)}
                            </span>
                        </div>
                        ${extendedProps.technician ? `
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Técnico:</label>
                            <p class="text-sm text-gray-900">${extendedProps.technician}</p>
                        </div>
                        ` : ''}
                    </div>
                `;
                
                // Mostrar modal
                document.getElementById('eventModal').classList.remove('hidden');
            },
            eventMouseEnter: function(info) {
                info.el.style.cursor = 'pointer';
            },
            height: 'auto',
            dayMaxEvents: 3,
            moreLinkClick: 'popover'
        });

        calendar.render();
        console.log('Calendario renderizado exitosamente');
    } catch (error) {
        console.error('Error al inicializar el calendario:', error);
        calendarEl.innerHTML = '<p class="text-red-600 text-center py-8">Error al inicializar el calendario. Por favor, verifica la consola para más detalles.</p>';
    }

    // Funciones del modal
    window.closeModal = function() {
        document.getElementById('eventModal').classList.add('hidden');
    };

    window.viewDetails = function() {
        if (currentEventUrl) {
            window.location.href = currentEventUrl;
        }
    };

    function getStatusText(color) {
        const statusMap = {
            '#f59e0b': 'Programado',    // Amarillo - scheduled
            '#3b82f6': 'En Progreso',   // Azul - in_progress 
            '#10b981': 'Completado',    // Verde - completed
            '#ef4444': 'Cancelado',     // Rojo - cancelled
            '#6b7280': 'Otro'           // Gris - default
        };
        return statusMap[color] || 'Desconocido';
    }

    // Cerrar modal al hacer clic fuera
    document.getElementById('eventModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
});
</script>
@endpush