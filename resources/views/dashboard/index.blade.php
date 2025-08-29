@extends('layouts.app')

@section('title', 'Dashboard - ITAM BKB')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-slate-100">Dashboard</h1>
    <p class="text-gray-600 dark:text-slate-400">Resumen del sistema de gestión de activos TI</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Equipos -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6 transition-colors">
        <div class="flex items-center">
            <div class="p-3 bg-slate-100 dark:bg-slate-700 rounded-full">
                <svg class="w-6 h-6 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Total Equipos</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-slate-100">{{ $totalEquipment ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Equipos Disponibles -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6 transition-colors">
        <div class="flex items-center">
            <div class="p-3 bg-emerald-100 dark:bg-emerald-900/30 rounded-full">
                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Equipos Disponibles</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-slate-100">{{ $availableEquipment ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Garantías por Vencer -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6 transition-colors">
        <div class="flex items-center">
            <div class="p-3 bg-amber-100 dark:bg-amber-900/30 rounded-full">
                <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.728-.833-2.498 0L4.616 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Garantías por Vencer</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-slate-100">{{ $expiringSoon ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Usuarios TI -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6 transition-colors">
        <div class="flex items-center">
            <div class="p-3 bg-slate-100 dark:bg-slate-700 rounded-full">
                <svg class="w-6 h-6 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Usuarios TI</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-slate-100">{{ $totalUsers ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Proveedores -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6 transition-colors">
        <div class="flex items-center">
            <div class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-full">
                <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Proveedores</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-slate-100">{{ $totalSuppliers ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Equipos Recientes -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 transition-colors">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-slate-100">Equipos Recientes</h3>
        </div>
        <div class="px-6 py-4">
            @if(isset($recentEquipment) && $recentEquipment->count() > 0)
                <div class="space-y-3">
                    @foreach($recentEquipment as $equipment)
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-slate-100">{{ $equipment->brand }} {{ $equipment->model }}</p>
                                <p class="text-sm text-gray-500 dark:text-slate-400">{{ $equipment->serial_number }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($equipment->status === 'available') bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300
                                @elseif($equipment->status === 'assigned') bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-300
                                @elseif($equipment->status === 'maintenance') bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-300
                                @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                                @endif
                            ">
                                {{ ucfirst($equipment->status) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:text-slate-400 text-center py-4">No hay equipos registrados</p>
            @endif
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 transition-colors">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-slate-100">Acciones Rápidas</h3>
        </div>
        <div class="px-6 py-4">
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Botón principal con naranja -->
                <a href="{{ route('equipment.create') }}" class="flex items-center p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg hover:bg-orange-100 dark:hover:bg-orange-900/30 transition-colors group">
                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="text-orange-700 dark:text-orange-300 font-medium group-hover:text-orange-800 dark:group-hover:text-orange-200">Nuevo Equipo</span>
                </a>

                <!-- Botones secundarios con colores atenuados -->
                <a href="{{ route('assignments.create') }}" class="flex items-center p-4 bg-gray-50 dark:bg-slate-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors group">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300 font-medium group-hover:text-gray-800 dark:group-hover:text-gray-200">Nueva Asignación</span>
                </a>

                <a href="{{ route('it-users.create') }}" class="flex items-center p-4 bg-gray-50 dark:bg-slate-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors group">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300 font-medium group-hover:text-gray-800 dark:group-hover:text-gray-200">Nuevo Usuario</span>
                </a>

                <a href="{{ route('suppliers.index') }}" class="flex items-center p-4 bg-gray-50 dark:bg-slate-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors group">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300 font-medium group-hover:text-gray-800 dark:group-hover:text-gray-200">Proveedores</span>
                </a>

                <a href="{{ route('maintenance.create') }}" class="flex items-center p-4 bg-gray-50 dark:bg-slate-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors group">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300 font-medium group-hover:text-gray-800 dark:group-hover:text-gray-200">Agendar Mantenimiento</span>
                </a>

                <a href="{{ route('reports.index') }}" class="flex items-center p-4 bg-gray-50 dark:bg-slate-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors group">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300 font-medium group-hover:text-gray-800 dark:group-hover:text-gray-200">Reportes</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
