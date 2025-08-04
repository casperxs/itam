<!DOCTYPE html>
<html lang="es" class="{{ $isDarkMode ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ITAM - Sistema de Gestión de Activos TI')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 dark:bg-gray-900 transition-colors duration-200">
    <nav class="bg-blue-600 dark:bg-gray-800 text-white p-4 transition-colors duration-200">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">
                <a href="{{ route('dashboard') }}">ITAM System</a>
            </h1>

            @auth
            <div class="flex items-center space-x-4">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-200 dark:hover:text-gray-300">Dashboard</a>
                <a href="{{ route('equipment.index') }}" class="hover:text-blue-200 dark:hover:text-gray-300">Equipos</a>
                <a href="{{ route('it-users.index') }}" class="hover:text-blue-200 dark:hover:text-gray-300">Usuarios TI</a>
                <a href="{{ route('assignments.index') }}" class="hover:text-blue-200 dark:hover:text-gray-300">Asignaciones</a>
                <a href="{{ route('maintenance.index') }}" class="hover:text-blue-200 dark:hover:text-gray-300">Mantenimiento</a>
                <a href="{{ route('contracts.index') }}" class="hover:text-blue-200 dark:hover:text-gray-300">Contratos</a>
                <a href="{{ route('suppliers.index') }}" class="hover:text-blue-200 dark:hover:text-gray-300">Proveedores</a>
                <a href="{{ route('reports.index') }}" class="hover:text-blue-200 dark:hover:text-gray-300">Reportes</a>

                <!-- Dark Mode Toggle -->
                <button id="darkModeToggle" class="p-2 rounded-lg hover:bg-blue-700 dark:hover:bg-gray-700 transition-colors duration-200">
                    <svg id="darkModeIcon" class="w-5 h-5 {{ $isDarkMode ? 'hidden' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                    <svg id="lightModeIcon" class="w-5 h-5 {{ $isDarkMode ? '' : 'hidden' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </button>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="hover:text-blue-200 dark:hover:text-gray-300">Cerrar Sesión</button>
                </form>
            </div>
            @endauth
        </div>
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
</body>
</html>
