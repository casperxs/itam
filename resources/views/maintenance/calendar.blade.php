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
<style>
    .calendar-loading {
        min-height: 500px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8fafc;
        border-radius: 8px;
    }
    
    .dark .calendar-loading {
        background-color: #1f2937;
    }
</style>
@endpush

@push('scripts')
<script>
// Estado de depuración
const DEBUG_MODE = true;

function log(...args) {
    if (DEBUG_MODE) {
        console.log('[CALENDAR]', ...args);
    }
}

function error(...args) {
    console.error('[CALENDAR ERROR]', ...args);
}

// Función para cargar FullCalendar
function loadFullCalendar() {
    return new Promise((resolve, reject) => {
        // Verificar si ya está cargado
        if (typeof FullCalendar !== 'undefined') {
            log('FullCalendar ya está disponible');
            resolve();
            return;
        }
        
        log('Cargando FullCalendar desde CDN...');
        
        // Cargar CSS
        const cssLink = document.createElement('link');
        cssLink.rel = 'stylesheet';
        cssLink.href = 'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css';
        document.head.appendChild(cssLink);
        
        // Cargar JS principal
        const script1 = document.createElement('script');
        script1.src = 'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js';
        
        script1.onload = function() {
            log('Script principal de FullCalendar cargado');
            
            // Cargar localización español
            const script2 = document.createElement('script');
            script2.src = 'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales/es.global.min.js';
            
            script2.onload = function() {
                log('Localización española cargada');
                resolve();
            };
            
            script2.onerror = function() {
                error('Error cargando localización española');
                // Continuar sin localización
                resolve();
            };
            
            document.head.appendChild(script2);
        };
        
        script1.onerror = function() {
            error('Error cargando FullCalendar');
            reject(new Error('No se pudo cargar FullCalendar'));
        };
        
        document.head.appendChild(script1);
    });
}

// Función para mostrar estado de carga
function showLoadingState() {
    const calendarEl = document.getElementById('calendar');
    calendarEl.innerHTML = `
        <div class="calendar-loading">
            <div class="text-center">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mb-4"></div>
                <p class="text-gray-600 dark:text-gray-400">Cargando calendario...</p>
            </div>
        </div>
    `;
}

// Función para mostrar error
function showErrorState(message) {
    const calendarEl = document.getElementById('calendar');
    calendarEl.innerHTML = `
        <div class="calendar-loading">
            <div class="text-center">
                <div class="text-red-500 text-4xl mb-4">⚠️</div>
                <p class="text-red-600 font-semibold mb-2">Error al cargar el calendario</p>
                <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">${message}</p>
                <div class="space-x-3">
                    <button onclick="initializeCalendar()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Intentar de nuevo
                    </button>
                    <button onclick="showFallbackCalendar()" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                        Vista simple
                    </button>
                </div>
            </div>
        </div>
    `;
}

// Vista de respaldo simple
function showFallbackCalendar() {
    log('Mostrando calendario de respaldo');
    
    fetch('{{ route("maintenance.calendar.events") }}')
        .then(response => response.json())
        .then(events => {
            const calendarEl = document.getElementById('calendar');
            
            // Agrupar eventos por fecha
            const eventsByDate = {};
            events.forEach(event => {
                const date = new Date(event.start).toISOString().split('T')[0];
                if (!eventsByDate[date]) {
                    eventsByDate[date] = [];
                }
                eventsByDate[date].push(event);
            });
            
            // Crear vista simple
            let html = `
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-6 text-gray-900 dark:text-gray-100">Eventos de Mantenimiento</h3>
                    <div class="space-y-4">
            `;
            
            if (Object.keys(eventsByDate).length === 0) {
                html += '<p class="text-gray-500 dark:text-gray-400 text-center py-8">No hay eventos programados</p>';
            } else {
                // Ordenar fechas
                const sortedDates = Object.keys(eventsByDate).sort();
                
                sortedDates.forEach(date => {
                    const dateObj = new Date(date + 'T12:00:00');
                    const dateStr = dateObj.toLocaleDateString('es-ES', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                    
                    html += `
                        <div class="border dark:border-gray-600 rounded-lg p-4">
                            <h4 class="font-semibold text-lg mb-3 text-gray-900 dark:text-gray-100">${dateStr}</h4>
                            <div class="space-y-2">
                    `;
                    
                    eventsByDate[date].forEach(event => {
                        html += `
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded border-l-4" style="border-left-color: ${event.color}">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">${event.title}</p>
                                    ${event.extendedProps?.technician ? `<p class="text-sm text-gray-600 dark:text-gray-400">Técnico: ${event.extendedProps.technician}</p>` : ''}
                                </div>
                                ${event.url ? `<a href="${event.url}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">Ver detalles →</a>` : ''}
                            </div>
                        `;
                    });
                    
                    html += '</div></div>';
                });
            }
            
            html += `
                    </div>
                    <div class="mt-6 text-center">
                        <button onclick="initializeCalendar()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            🔄 Intentar cargar calendario completo
                        </button>
                    </div>
                </div>
            `;
            
            calendarEl.innerHTML = html;
        })
        .catch(err => {
            error('Error cargando vista de respaldo:', err);
            showErrorState('No se pudieron cargar los eventos del servidor');
        });
}

// Función principal de inicialización
function initializeCalendar() {
    log('Iniciando carga del calendario...');
    
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) {
        error('Elemento #calendar no encontrado');
        return;
    }
    
    showLoadingState();
    
    loadFullCalendar()
        .then(() => {
            log('FullCalendar cargado, inicializando calendario...');
            
            if (typeof FullCalendar === 'undefined') {
                throw new Error('FullCalendar no está disponible después de la carga');
            }
            
            log('Versión de FullCalendar:', FullCalendar.VERSION || 'desconocida');
            
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
                height: 'auto',
                events: {
                    url: '{{ route("maintenance.calendar.events") }}',
                    method: 'GET',
                    failure: function(error) {
                        error('Error al cargar eventos:', error);
                        showErrorState('Error al cargar los eventos del servidor');
                    },
                    success: function(events) {
                        log('Eventos cargados exitosamente:', events.length, 'eventos');
                    }
                },
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    if (info.event.url) {
                        window.location.href = info.event.url;
                    }
                },
                eventMouseEnter: function(info) {
                    info.el.style.cursor = 'pointer';
                },
                dayMaxEvents: 3,
                moreLinkClick: 'popover'
            });
            
            calendar.render();
            log('✅ Calendario renderizado exitosamente');
            
        })
        .catch(err => {
            error('Error durante la inicialización:', err);
            showErrorState(err.message || 'Error desconocido');
        });
}

// Inicializar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeCalendar);
} else {
    initializeCalendar();
}

// Función global para reintento manual
window.initializeCalendar = initializeCalendar;
</script>
@endpush
