@extends('layouts.app')

@section('title', 'Nuevo Contrato - ITAM System')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">{{ isset($isRenewal) && $isRenewal ? 'Renovar Contrato' : 'Nuevo Contrato' }}</h1>
        <p class="text-gray-600">{{ isset($isRenewal) && $isRenewal ? 'Renovación de contrato existente' : 'Crear un nuevo contrato de proveedor' }}</p>
    </div>
    <a href="{{ route('contracts.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
        Volver
    </a>
</div>

<div class="bg-white rounded-lg shadow">
    <form method="POST" action="{{ route('contracts.store') }}" enctype="multipart/form-data" class="p-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Proveedor -->
            <div>
                <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-2">Proveedor *</label>
                <select name="supplier_id" id="supplier_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('supplier_id') border-red-500 @enderror">
                    <option value="">Seleccionar proveedor</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ (old('supplier_id', $contract->supplier_id ?? '') == $supplier->id) ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
                @error('supplier_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Número de Contrato -->
            <div>
                <label for="contract_number" class="block text-sm font-medium text-gray-700 mb-2">Número de Contrato *</label>
                <input type="text" name="contract_number" id="contract_number" value="{{ old('contract_number', $contract->contract_number ?? '') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('contract_number') border-red-500 @enderror">
                @error('contract_number')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Descripción del Servicio -->
            <div class="md:col-span-2">
                <label for="service_description" class="block text-sm font-medium text-gray-700 mb-2">Descripción del Servicio *</label>
                <textarea name="service_description" id="service_description" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('service_description') border-red-500 @enderror">{{ old('service_description', $contract->service_description ?? '') }}</textarea>
                @error('service_description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Fecha de Inicio -->
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Fecha de Inicio *</label>
                <input type="date" name="start_date" id="start_date" value="{{ old('start_date', isset($contract->start_date) ? $contract->start_date->format('Y-m-d') : '') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('start_date') border-red-500 @enderror">
                @error('start_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Fecha de Fin -->
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Fecha de Fin *</label>
                <input type="date" name="end_date" id="end_date" value="{{ old('end_date', isset($contract->end_date) ? $contract->end_date->format('Y-m-d') : '') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('end_date') border-red-500 @enderror">
                @error('end_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Costo Mensual -->
            <div>
                <label for="monthly_cost" class="block text-sm font-medium text-gray-700 mb-2">Costo Mensual</label>
                <input type="number" step="0.01" name="monthly_cost" id="monthly_cost" value="{{ old('monthly_cost', $contract->monthly_cost ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('monthly_cost') border-red-500 @enderror">
                @error('monthly_cost')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Costo Total -->
            <div>
                <label for="total_cost" class="block text-sm font-medium text-gray-700 mb-2">Costo Total</label>
                <input type="number" step="0.01" name="total_cost" id="total_cost" value="{{ old('total_cost', $contract->total_cost ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('total_cost') border-red-500 @enderror">
                @error('total_cost')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Estado -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Estado *</label>
                <select name="status" id="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                    <option value="active" {{ old('status', $contract->status ?? 'active') === 'active' ? 'selected' : '' }}>Activo</option>
                    <option value="expired" {{ old('status', $contract->status ?? '') === 'expired' ? 'selected' : '' }}>Vencido</option>
                    <option value="cancelled" {{ old('status', $contract->status ?? '') === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Días de Alerta -->
            <div>
                <label for="alert_days_before" class="block text-sm font-medium text-gray-700 mb-2">Días de Alerta Antes del Vencimiento *</label>
                <input type="number" min="1" name="alert_days_before" id="alert_days_before" value="{{ old('alert_days_before', $contract->alert_days_before ?? 30) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('alert_days_before') border-red-500 @enderror">
                @error('alert_days_before')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Archivo del Contrato -->
            <div class="md:col-span-2">
                <label for="contract_file" class="block text-sm font-medium text-gray-700 mb-2">Archivo del Contrato (PDF)</label>
                <input type="file" name="contract_file" id="contract_file" accept=".pdf" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('contract_file') border-red-500 @enderror">
                <p class="mt-1 text-sm text-gray-500">Máximo 10MB, solo archivos PDF</p>
                @error('contract_file')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notas -->
            <div class="md:col-span-2">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notas</label>
                <textarea name="notes" id="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes', $contract->notes ?? '') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end space-x-4 mt-6 pt-6 border-t border-gray-200">
            <a href="{{ route('contracts.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400">
                Cancelar
            </a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                {{ isset($isRenewal) && $isRenewal ? 'Renovar Contrato' : 'Crear Contrato' }}
            </button>
        </div>
    </form>
</div>
@endsection