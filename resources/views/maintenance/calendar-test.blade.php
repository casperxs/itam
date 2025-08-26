@extends('layouts.app')

@section('title', 'Prueba de Calendario')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">Prueba de Calendario</h1>
    <p class="text-gray-600 dark:text-gray-400">Diagnóstico del calendario de mantenimientos</p>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Información de Debug</h3>
    <div id="debug-info" class="space-y-2 text-sm font-mono"></div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Prueba de Fetch Directo</h3>
    <button onclick="testDirectFetch()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-4">
        Probar carga de eventos
    </button>
    <pre id="fetch-result" class="text-xs bg-gray-100 dark:bg-gray-700 p-4 rounded overflow-x-auto"></pre>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Calendario FullCalendar</h3>
    <div id="calendar-container">
        <div id="calendar" style="min-height: 500px;"></div>
    </div>
</div>

<script>
// Información de debug
function updateDebugInfo() {
    const debugEl = document.getElementById('debug-info');
    const info = [
        `🌐 User Agent: ${navigator.userAgent}`,
        `📅 Date: ${new Date().toISOString()}`,
        `🔧 Document Ready State: ${document.readyState}`,
        `📜 Scripts en head: ${document.head.querySelectorAll('script').length}`,
        `🎨 CSS links: ${document.head.querySelectorAll('link[rel="stylesheet"]').length}`,
        `🔍 FullCalendar disponible: ${typeof FullCalendar !== 'undefined' ? '✅ Sí' : '❌ No'}`,
        `📍 URL actual: ${window.location.href}`,
        `🔗 URL eventos: {{ route("maintenance.calendar.events") }}`
    ];
    
    debugEl.innerHTML = info.map(item => `<div>${item}</div>`).join('');
}

// Prueba de fetch directo
function testDirectFetch() {
    const resultEl = document.getElementById('fetch-result');
    resultEl.textContent = 'Cargando...';
    
    console.log('[TEST] Iniciando fetch directo...');
    
    fetch('{{ route("maintenance.calendar.events") }}')
        .then(response => {
            console.log('[TEST] Response status:', response.status);
            console.log('[TEST] Response headers:', [...response.headers.entries()]);
            return response.json();
        })
        .then(data => {
            console.log('[TEST] Data recibida:', data);
            resultEl.textContent = JSON.stringify(data, null, 2);
        })
        .catch(error => {
            console.error('[TEST] Error en fetch:', error);
            resultEl.textContent = `Error: ${error.message}`;
        });
}

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    console.log('[TEST] DOM cargado');
    updateDebugInfo();
    
    // Actualizar debug info cada 2 segundos
    setInterval(updateDebugInfo, 2000);
    
    // Cargar FullCalendar dinámicamente
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js';
    script.onload = function() {
        console.log('[TEST] FullCalendar cargado');
        updateDebugInfo();
        
        // Intentar crear calendario
        if (typeof FullCalendar !== 'undefined') {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: {
                    url: '{{ route("maintenance.calendar.events") }}',
                    method: 'GET'
                }
            });
            calendar.render();
            console.log('[TEST] Calendario renderizado');
        }
    };
    document.head.appendChild(script);
});
</script>
@endsection
