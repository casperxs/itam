<!DOCTYPE html>
<html lang="es" class="{{ $isDarkMode ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>@yield('title', 'ITAM - Sistema de Gestión de Activos TI')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ROUND 2: DESTRUCCIÓN TOTAL DEL CACHE EN DASHBOARD */
        html, body { 
            color-scheme: {{ $isDarkMode ? 'dark' : 'light' }} !important;
            background-color: {{ $isDarkMode ? '#0f172a' : '#f9fafb' }} !important;
        }
        
        /* LIGHT MODE FORZADO */
        @if (!$isDarkMode)
        html, body { 
            background-color: #f9fafb !important;
            color: #111827 !important;
        }
        .bg-white { background-color: #ffffff !important; }
        .bg-gray-50 { background-color: #f9fafb !important; }
        .text-gray-900 { color: #111827 !important; }
        .text-gray-600 { color: #6b7280 !important; }
        .text-gray-500 { color: #6b7280 !important; }
        .border-gray-200 { border-color: #e5e7eb !important; }
        .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important; }
        @endif
        
        /* DARK MODE FORZADO */
        @if ($isDarkMode)
        html, body { 
            background-color: #0f172a !important;
            color: #f1f5f9 !important;
        }
        .dark\:bg-slate-900 { background-color: #0f172a !important; }
        .dark\:bg-slate-800 { background-color: #1e293b !important; }
        .dark\:text-slate-100 { color: #f1f5f9 !important; }
        .dark\:text-slate-400 { color: #94a3b8 !important; }
        .dark\:border-slate-700 { border-color: #334155 !important; }
        @endif
    </style>
    @yield('styles')
</head>
<body class="bg-gray-50 dark:bg-slate-900 transition-colors duration-200">
    <nav class="bg-white dark:bg-slate-900 border-b border-gray-200 dark:border-slate-700 transition-colors duration-200">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Logo/Título -->
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-gray-900 dark:text-slate-100">
                        <a href="{{ route('dashboard') }}" class="hover:text-orange-500 transition-colors">ITAM-BKB</a>
                    </h1>
                </div>

                @auth
                <!-- Controles de la derecha -->
                <div class="flex items-center space-x-2">
                    <!-- Dark Mode Toggle -->
                    <form method="POST" action="{{ route('dark-mode.toggle') }}" class="inline" id="darkModeForm">
                        @csrf
                        <button type="submit" id="darkModeToggle" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors duration-200 text-gray-600 dark:text-slate-400">
                            <svg id="darkModeIcon" class="w-5 h-5 {{ $isDarkMode ? 'hidden' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                            <svg id="lightModeIcon" class="w-5 h-5 {{ $isDarkMode ? '' : 'hidden' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </button>
                    </form>

                    <!-- Cerrar Sesión -->
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-200 text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300" title="Cerrar Sesión">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>

                    <!-- Menú Hamburguesa -->
                    <button id="hamburger-menu" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors duration-200 text-gray-600 dark:text-slate-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
                @endauth
            </div>
        </div>

        @auth
        <!-- Menú desplegable (oculto por defecto) -->
        <div id="mobile-menu" class="hidden bg-white dark:bg-slate-900 border-t border-gray-200 dark:border-slate-700">
            <div class="container mx-auto px-4 py-4">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors text-gray-700 dark:text-slate-300 hover:text-orange-500">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10zM8 21l4-4 4 4M3 7l9-4 9 4"></path>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('equipment.index') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors text-gray-700 dark:text-slate-300 hover:text-orange-500">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                        </svg>
                        Equipos
                    </a>
                    <a href="{{ route('it-users.index') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors text-gray-700 dark:text-slate-300 hover:text-orange-500">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Usuarios TI
                    </a>
                    <a href="{{ route('users.index') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors text-gray-700 dark:text-slate-300 hover:text-orange-500">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        Administradores
                    </a>
                    <a href="{{ route('assignments.index') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors text-gray-700 dark:text-slate-300 hover:text-orange-500">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Asignaciones
                    </a>
                    <a href="{{ route('maintenance.index') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors text-gray-700 dark:text-slate-300 hover:text-orange-500">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Mantenimiento
                    </a>
                    <a href="{{ route('contracts.index') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors text-gray-700 dark:text-slate-300 hover:text-orange-500">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Contratos
                    </a>
                    <a href="{{ route('suppliers.index') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors text-gray-700 dark:text-slate-300 hover:text-orange-500">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Proveedores
                    </a>
                    <a href="{{ route('reports.index') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors text-gray-700 dark:text-slate-300 hover:text-orange-500">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Reportes
                    </a>
                </div>
            </div>
        </div>
        @endauth
    </nav>

    <div class="container mx-auto mt-6 px-4">
        @if(session('success'))
            <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>
    
    <!-- jQuery necesario para Select2 y otros componentes -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- JavaScript para menú hamburguesa y GUERRA CONTRA CACHE -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburgerBtn = document.getElementById('hamburger-menu');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (hamburgerBtn && mobileMenu) {
                hamburgerBtn.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
                
                // Cerrar menú al hacer click fuera
                document.addEventListener('click', function(e) {
                    if (!hamburgerBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
                        mobileMenu.classList.add('hidden');
                    }
                });
                
                // Cerrar menú al hacer click en un enlace (en móviles)
                const menuLinks = mobileMenu.querySelectorAll('a');
                menuLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        mobileMenu.classList.add('hidden');
                    });
                });
            }
            
            // AJAX TOGGLE - No refresh, solo AJAX
            const darkModeToggle = document.getElementById('darkModeToggle');
            const darkModeForm = document.getElementById('darkModeForm');
            
            if (darkModeToggle && darkModeForm) {
                darkModeToggle.addEventListener('click', function(e) {
                    e.preventDefault(); // Prevenir submit normal
                    
                    console.log('Toggle clicked - usando AJAX');
                    
                    // Hacer request AJAX
                    fetch(darkModeForm.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Response:', data);
                        
                        if (data.success) {
                            // Aplicar inmediatamente el nuevo modo
                            const isDark = data.dark_mode;
                            
                            if (isDark) {
                                document.documentElement.classList.add('dark');
                            } else {
                                document.documentElement.classList.remove('dark');
                            }
                            
                            // Cambiar iconos
                            const darkIcon = document.getElementById('darkModeIcon');
                            const lightIcon = document.getElementById('lightModeIcon');
                            
                            if (isDark) {
                                darkIcon.classList.add('hidden');
                                lightIcon.classList.remove('hidden');
                            } else {
                                darkIcon.classList.remove('hidden');
                                lightIcon.classList.add('hidden');
                            }
                            
                            // Aplicar estilos
                            applyModeStyles(isDark);
                            
                            console.log('Dark mode cambiado a:', isDark);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                });
            }
        });
        
        // SIMPLE: Solo verificar cada segundo si cambió el modo
        let lastMode = document.documentElement.classList.contains('dark');
        
        setInterval(function() {
            const currentMode = document.documentElement.classList.contains('dark');
            if (currentMode !== lastMode) {
                lastMode = currentMode;
                applyModeStyles(currentMode);
            }
        }, 500);
        
        function applyModeStyles(isDark) {
            // Remover estilo previo
            const existing = document.getElementById('mode-override');
            if (existing) existing.remove();
            
            // Crear nuevo estilo
            const style = document.createElement('style');
            style.id = 'mode-override';
            
            if (isDark) {
                style.textContent = `
                    body { background-color: #0f172a !important; }
                    nav { background-color: #0f172a !important; }
                    .bg-white { background-color: #1e293b !important; }
                    .text-gray-900 { color: #f1f5f9 !important; }
                `;
            } else {
                style.textContent = `
                    body { background-color: #f9fafb !important; }
                    nav { background-color: #ffffff !important; }
                    .bg-white { background-color: #ffffff !important; }
                    .text-gray-900 { color: #111827 !important; }
                `;
            }
            
            document.head.appendChild(style);
        }
        
        // SOLUCIÓN DEFINITIVA: Usar SOLO el valor de la DB
        const realDarkMode = {{ auth()->user()->fresh()->dark_mode ? 'true' : 'false' }};
        
        // Aplicar el modo real
        if (realDarkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        
        applyModeStyles(realDarkMode);
    </script>
    
    @yield('scripts')
</body>
</html>
