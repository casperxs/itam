@extends('layouts.app')

@section('title', 'Editar Usuario: ' . $user->name . ' - ITAM')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Editar Usuario</h2>
        <div class="flex space-x-2">
            <a href="{{ route('users.show', $user) }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                Ver
            </a>
            <a href="{{ route('users.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                Cancelar
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg transition-colors duration-200">
        <div class="px-4 py-5 sm:p-6">
            <!-- User Info Header -->
            <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <div class="flex items-center">
                    <div class="h-12 w-12 rounded-full bg-blue-500 flex items-center justify-center mr-4">
                        <span class="text-white font-semibold">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </span>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-6">
                @csrf
                @method('PATCH')

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Nombre completo <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', $user->name) }}"
                           required
                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Correo electrónico <span class="text-red-500">*</span>
                    </label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           value="{{ old('email', $user->email) }}"
                           required
                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Nueva contraseña
                    </label>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Dejar en blanco si no deseas cambiar la contraseña
                    </p>
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Confirmar nueva contraseña
                    </label>
                    <input type="password" 
                           name="password_confirmation" 
                           id="password_confirmation" 
                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Rol <span class="text-red-500">*</span>
                    </label>
                    <select name="role" 
                            id="role" 
                            required
                            @if(\App\Models\User::where('role', 'admin')->count() <= 1 && $user->role === 'admin') disabled @endif
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 @error('role') border-red-500 @enderror">
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrador</option>
                        <option value="operator" {{ old('role', $user->role) == 'operator' ? 'selected' : '' }}>Operador</option>
                    </select>
                    @if(\App\Models\User::where('role', 'admin')->count() <= 1 && $user->role === 'admin')
                        <input type="hidden" name="role" value="admin">
                        <p class="mt-1 text-xs text-yellow-600 dark:text-yellow-400">
                            ⚠️ Este es el último administrador del sistema. No se puede cambiar su rol.
                        </p>
                    @endif
                    @error('role')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Employee ID -->
                <div>
                    <label for="employee_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        ID de empleado
                    </label>
                    <input type="text" 
                           name="employee_id" 
                           id="employee_id" 
                           value="{{ old('employee_id', $user->employee_id) }}"
                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 @error('employee_id') border-red-500 @enderror">
                    @error('employee_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Department -->
                <div>
                    <label for="department" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Departamento
                    </label>
                    <input type="text" 
                           name="department" 
                           id="department" 
                           value="{{ old('department', $user->department) }}"
                           placeholder="ej. Tecnologías de la Información"
                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 @error('department') border-red-500 @enderror">
                    @error('department')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Position -->
                <div>
                    <label for="position" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Cargo
                    </label>
                    <input type="text" 
                           name="position" 
                           id="position" 
                           value="{{ old('position', $user->position) }}"
                           placeholder="ej. Administrador de Sistemas"
                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 @error('position') border-red-500 @enderror">
                    @error('position')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <span class="text-red-500">*</span> Campos obligatorios
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('users.show', $user) }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded transition duration-200">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                            Actualizar Usuario
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Account Info -->
    <div class="mt-6 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
        <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Información de la cuenta</h3>
        <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
            <p><strong>Creado:</strong> {{ $user->created_at->format('d/m/Y H:i') }} ({{ $user->created_at->diffForHumans() }})</p>
            <p><strong>Última actualización:</strong> {{ $user->updated_at->format('d/m/Y H:i') }} ({{ $user->updated_at->diffForHumans() }})</p>
            <p><strong>Modo oscuro:</strong> {{ $user->dark_mode ? 'Activado 🌙' : 'Desactivado ☀️' }}</p>
        </div>
    </div>

    <!-- Warning for Current User -->
    @if($user->id === auth()->id())
    <div class="mt-6 bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                    Estás editando tu propia cuenta
                </h3>
                <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                    <p>Ten cuidado al cambiar tu rol o información de acceso, ya que esto podría afectar tu capacidad para administrar el sistema.</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
