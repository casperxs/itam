@extends('layouts.app')

@section('title', 'Crear Equipo')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Crear Nuevo Equipo</h2>
            <a href="{{ route('equipment.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                Volver
            </a>
        </div>

        <form action="{{ route('equipment.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="equipment_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo de Equipo *
                    </label>
                    <select name="equipment_type_id" id="equipment_type_id" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            required>
                        <option value="">Seleccionar tipo</option>
                        @foreach($equipmentTypes as $type)
                            <option value="{{ $type->id }}" {{ old('equipment_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('equipment_type_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Proveedor *
                    </label>
                    <select name="supplier_id" id="supplier_id" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            required>
                        <option value="">Seleccionar proveedor</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="serial_number" class="block text-sm font-medium text-gray-700 mb-2">
                        Número de Serie *
                    </label>
                    <input type="text" name="serial_number" id="serial_number" 
                           value="{{ old('serial_number') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           required>
                    @error('serial_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="asset_tag" class="block text-sm font-medium text-gray-700 mb-2">
                        Etiqueta de Activo
                    </label>
                    <input type="text" name="asset_tag" id="asset_tag" 
                           value="{{ old('asset_tag') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('asset_tag')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="brand" class="block text-sm font-medium text-gray-700 mb-2">
                        Marca *
                    </label>
                    <input type="text" name="brand" id="brand" 
                           value="{{ old('brand') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           required>
                    @error('brand')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="model" class="block text-sm font-medium text-gray-700 mb-2">
                        Modelo *
                    </label>
                    <input type="text" name="model" id="model" 
                           value="{{ old('model') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           required>
                    @error('model')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Estado *
                    </label>
                    <select name="status" id="status" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            required>
                        <option value="">Seleccionar estado</option>
                        <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Disponible</option>
                        <option value="assigned" {{ old('status') == 'assigned' ? 'selected' : '' }}>Asignado</option>
                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>En Mantenimiento</option>
                        <option value="retired" {{ old('status') == 'retired' ? 'selected' : '' }}>Retirado</option>
                        <option value="lost" {{ old('status') == 'lost' ? 'selected' : '' }}>Perdido</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="valoracion" class="block text-sm font-medium text-gray-700 mb-2">
                        Valoración
                    </label>
                    <select name="valoracion" id="valoracion" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Seleccionar valoración</option>
                        <option value="100%" {{ old('valoracion') == '100%' ? 'selected' : '' }}>100%</option>
                        <option value="90%" {{ old('valoracion') == '90%' ? 'selected' : '' }}>90%</option>
                        <option value="80%" {{ old('valoracion') == '80%' ? 'selected' : '' }}>80%</option>
                        <option value="70%" {{ old('valoracion') == '70%' ? 'selected' : '' }}>70%</option>
                        <option value="60%" {{ old('valoracion') == '60%' ? 'selected' : '' }}>60%</option>
                    </select>
                    @error('valoracion')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="purchase_price" class="block text-sm font-medium text-gray-700 mb-2">
                        Precio de Compra
                    </label>
                    <input type="number" step="0.01" name="purchase_price" id="purchase_price" 
                           value="{{ old('purchase_price') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('purchase_price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="purchase_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha de Compra
                    </label>
                    <input type="date" name="purchase_date" id="purchase_date" 
                           value="{{ old('purchase_date') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('purchase_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="warranty_end_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Fin de Garantía
                    </label>
                    <input type="date" name="warranty_end_date" id="warranty_end_date" 
                           value="{{ old('warranty_end_date') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('warranty_end_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="invoice_number" class="block text-sm font-medium text-gray-700 mb-2">
                        Número de Factura
                    </label>
                    <input type="text" name="invoice_number" id="invoice_number" 
                           value="{{ old('invoice_number') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('invoice_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="invoice_file" class="block text-sm font-medium text-gray-700 mb-2">
                        Archivo de Factura (PDF)
                    </label>
                    <input type="file" name="invoice_file" id="invoice_file" accept=".pdf"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('invoice_file')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label for="specifications" class="block text-sm font-medium text-gray-700 mb-2">
                    Especificaciones
                </label>
                <textarea name="specifications" id="specifications" rows="3"
                          class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Describa las especificaciones técnicas del equipo...">{{ old('specifications') }}</textarea>
                @error('specifications')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <label for="observations" class="block text-sm font-medium text-gray-700 mb-2">
                    Observaciones
                </label>
                <textarea name="observations" id="observations" rows="3"
                          class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Observaciones adicionales...">{{ old('observations') }}</textarea>
                @error('observations')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6 flex gap-4">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                    Crear Equipo
                </button>
                <a href="{{ route('equipment.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-md">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection