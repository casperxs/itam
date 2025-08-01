<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ITAM - Sistema de Gestión de Activos TI')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">
                <a href="{{ route('dashboard') }}">ITAM System</a>
            </h1>

            @auth
            <div class="flex space-x-4">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-200">Dashboard</a>
                <a href="{{ route('equipment.index') }}" class="hover:text-blue-200">Equipos</a>
                <a href="{{ route('it-users.index') }}" class="hover:text-blue-200">Usuarios TI</a>
                <a href="{{ route('assignments.index') }}" class="hover:text-blue-200">Asignaciones</a>
                <a href="{{ route('maintenance.index') }}" class="hover:text-blue-200">Mantenimiento</a>
                <a href="{{ route('contracts.index') }}" class="hover:text-blue-200">Contratos</a>
                <a href="{{ route('suppliers.index') }}" class="hover:text-blue-200">Proveedores</a>
                <a href="{{ route('reports.index') }}" class="hover:text-blue-200">Reportes</a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="hover:text-blue-200">Cerrar Sesión</button>
                </form>
            </div>
            @endauth
        </div>
    </nav>

    <div class="container mx-auto mt-6 px-4">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
