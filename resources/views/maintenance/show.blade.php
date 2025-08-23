@extends('layouts.app')

@section('title', 'Detalle de Mantenimiento - ITAM System')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Detalle de Mantenimiento</h1>
            <p class="text-gray-600">{{ $maintenance->equipment->equipmentType->name ?? 'N/A' }} - {{ $maintenance->equipment->serial_number ?? 'N/A' }}</p>
        </div>
        <div class="flex space-x-2">
            @if($maintenance->status === 'completed')
                <a href="{{ route('maintenance.checklist', $maintenance) }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 font-bold">
                    📋 DESCARGAR CHECKLIST PDF
                </a>
            @endif
            <a href="{{ route('maintenance.edit', $maintenance) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Editar
            </a>
            <a href="{{ route('maintenance.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                Volver al Listado
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Información del Mantenimiento -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Información del Mantenimiento</h2>
        </div>
        <div class="px-6 py-4">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Mantenimiento</label>
                    <span class="px-2 py-1 rounded-full text-sm font-medium
                        @if($maintenance->type === 'preventive') bg-blue-100 text-blue-800
                        @elseif($maintenance->type === 'corrective') bg-red-100 text-red-800
                        @elseif($maintenance->type === 'update') bg-purple-100 text-purple-800
                        @endif">
                        @switch($maintenance->type)
                            @case('preventive') Preventivo @break
                            @case('corrective') Correctivo @break
                            @case('update') Actualización @break
                        @endswitch
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <span class="px-2 py-1 rounded-full text-sm font-medium
                        @if($maintenance->status === 'scheduled') bg-gray-100 text-gray-800
                        @elseif($maintenance->status === 'in_progress') bg-yellow-100 text-yellow-800
                        @elseif($maintenance->status === 'completed') bg-green-100 text-green-800
                        @elseif($maintenance->status === 'cancelled') bg-red-100 text-red-800
                        @endif">
                        @switch($maintenance->status)
                            @case('scheduled') Programado @break
                            @case('in_progress') En Progreso @break
                            @case('completed') Completado @break
                            @case('cancelled') Cancelado @break
                        @endswitch
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Programada</label>
                    <p class="text-gray-900">{{ $maintenance->scheduled_date ? $maintenance->scheduled_date->format('d/m/Y H:i') : 'N/A' }}</p>
                </div>

                @if($maintenance->completed_date)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Finalización</label>
                    <p class="text-gray-900">{{ $maintenance->completed_date->format('d/m/Y H:i') }}</p>
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Realizado por</label>
                    <p class="text-gray-900">{{ $maintenance->performedBy->name ?? 'N/A' }}</p>
                </div>

                @if($maintenance->cost)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Costo</label>
                    <p class="text-gray-900">${{ number_format($maintenance->cost, 2) }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Información del Equipo -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Información del Equipo</h2>
        </div>
        <div class="px-6 py-4">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Equipo</label>
                    <p class="text-gray-900">{{ $maintenance->equipment->equipmentType->name ?? 'N/A' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Marca y Modelo</label>
                    <p class="text-gray-900">{{ $maintenance->equipment->brand ?? 'N/A' }} {{ $maintenance->equipment->model ?? 'N/A' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Número de Serie</label>
                    <p class="text-gray-900">{{ $maintenance->equipment->serial_number ?? 'N/A' }}</p>
                </div>

                @if($maintenance->equipment->asset_tag)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Etiqueta de Activo</label>
                    <p class="text-gray-900">{{ $maintenance->equipment->asset_tag }}</p>
                </div>
                @endif

                @if($maintenance->equipment->currentAssignment)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Usuario Asignado</label>
                    <p class="text-gray-900">{{ $maintenance->equipment->currentAssignment->itUser->name ?? 'N/A' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Departamento</label>
                    <p class="text-gray-900">{{ $maintenance->equipment->currentAssignment->itUser->department ?? 'N/A' }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Descripción -->
<div class="mt-6 bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-900">Descripción del Trabajo</h2>
    </div>
    <div class="px-6 py-4">
        <p class="text-gray-700 whitespace-pre-line">{{ $maintenance->description ?? 'Sin descripción' }}</p>
    </div>
</div>

@if($maintenance->performed_actions)
<!-- Acciones Realizadas -->
<div class="mt-6 bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-900">Acciones Realizadas</h2>
    </div>
    <div class="px-6 py-4">
        <p class="text-gray-700 whitespace-pre-line">{{ $maintenance->performed_actions }}</p>
    </div>
</div>
@endif

@if($maintenance->notes)
<!-- Notas Adicionales -->
<div class="mt-6 bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-900">Notas Adicionales</h2>
    </div>
    <div class="px-6 py-4">
        <p class="text-gray-700 whitespace-pre-line">{{ $maintenance->notes }}</p>
    </div>
</div>
@endif

<!-- Acciones -->
@if($maintenance->status !== 'completed' && $maintenance->status !== 'cancelled')
<div class="mt-6 bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-900">Acciones</h2>
    </div>
    <div class="px-6 py-4">
        <div class="flex space-x-4">
            @if($maintenance->status === 'scheduled')
                <form method="POST" action="{{ route('maintenance.start', $maintenance) }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700">
                        Iniciar Mantenimiento
                    </button>
                </form>
            @endif

            @if($maintenance->status === 'in_progress')
                <!-- Botón para completar mantenimiento - se puede expandir con un modal -->
                <button onclick="document.getElementById('completeModal').classList.remove('hidden')" 
                        class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                    Completar Mantenimiento
                </button>
            @endif
        </div>
    </div>
</div>

<!-- Modal para completar mantenimiento -->
@if($maintenance->status === 'in_progress')
<div id="completeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-10 mx-auto p-5 border max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Completar Mantenimiento</h3>
                <button onclick="document.getElementById('completeModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form method="POST" action="{{ route('maintenance.complete', $maintenance) }}">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Información del Mantenimiento -->
                    <div class="space-y-4">
                        <h4 class="text-lg font-semibold text-gray-900">Información del Mantenimiento</h4>
                        
                        <div>
                            <label for="completed_date" class="block text-sm font-medium text-gray-700 mb-2">Fecha de Finalización</label>
                            <input type="datetime-local" id="completed_date" name="completed_date" 
                                   value="{{ now()->format('Y-m-d\TH:i') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        
                        <div>
                            <label for="performed_actions" class="block text-sm font-medium text-gray-700 mb-2">Acciones Realizadas</label>
                            <textarea id="performed_actions" name="performed_actions" rows="4" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Describe las acciones realizadas..." required></textarea>
                        </div>
                        
                        <div>
                            <label for="cost" class="block text-sm font-medium text-gray-700 mb-2">Costo (opcional)</label>
                            <input type="number" id="cost" name="cost" step="0.01" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notas Adicionales</label>
                            <textarea id="notes" name="notes" rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Notas adicionales..."></textarea>
                        </div>
                        
                        <!-- Sección de Checklist -->
                        <div class="mt-6">
                            <h5 class="text-md font-semibold text-gray-900 mb-4">Checklist de Actividades</h5>
                            <p class="text-sm text-gray-600 mb-4">Complete el checklist para poder finalizar el mantenimiento:</p>
                            
                            @php
                                $checklistActivities = [
                                    'Temporales',
                                    'Historial y Cookies',
                                    'Contraseñas',
                                    'Actualizaciones',
                                    'Formateo',
                                    'Respaldo',
                                    'Restauración de Información',
                                    'Limpieza',
                                    'Idioma del SO',
                                    'Idioma de Navegador(es)'
                                ];
                            @endphp
                            
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="space-y-4">
                                    @foreach($checklistActivities as $index => $activity)
                                    <div class="border-b border-gray-200 pb-3">
                                        <div class="flex items-start space-x-4">
                                            <div class="flex-1">
                                                <label class="text-sm font-medium text-gray-700">{{ $index + 1 }}. {{ $activity }}</label>
                                                
                                                <div class="flex items-center space-x-4 mt-2">
                                                    <label class="flex items-center">
                                                        <input type="radio" name="checklist[{{ $index }}][status]" value="correcto" 
                                                               class="mr-2 text-green-600 focus:ring-green-500" required>
                                                        <span class="text-sm text-green-700">Correcto</span>
                                                    </label>
                                                    <label class="flex items-center">
                                                        <input type="radio" name="checklist[{{ $index }}][status]" value="na" 
                                                               class="mr-2 text-gray-600 focus:ring-gray-500" required>
                                                        <span class="text-sm text-gray-700">N/A</span>
                                                    </label>
                                                    <label class="flex items-center">
                                                        <input type="radio" name="checklist[{{ $index }}][status]" value="incorrecto" 
                                                               class="mr-2 text-red-600 focus:ring-red-500" required>
                                                        <span class="text-sm text-red-700">Incorrecto</span>
                                                    </label>
                                                </div>
                                                
                                                <div class="mt-2 details-section" id="details-{{ $index }}" style="display: none;">
                                                    <input type="text" name="checklist[{{ $index }}][details]" 
                                                           placeholder="Especifique los detalles del problema..." 
                                                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                                                </div>
                                                
                                                <input type="hidden" name="checklist[{{ $index }}][activity]" value="{{ $activity }}">
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <div id="checklist-validation" class="text-sm text-gray-600">
                                    Complete todas las actividades del checklist para continuar.
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Evaluación de Equipo -->
                    <div class="space-y-4">
                        <h4 class="text-lg font-semibold text-gray-900">Evaluación Cuantificada del Equipo</h4>
                        
                        @php
                            // Force get the rating criteria directly
                            $modalRatingCriteria = \App\Models\RatingCriterion::getAllActive();
                            $lastRating = \App\Models\EquipmentRating::where('equipment_id', $maintenance->equipment_id)
                                                ->orderBy('created_at', 'desc')
                                                ->first();
                            $previousScore = $lastRating ? $lastRating->total_score : null;
                            
                            // Calculate equipment age in months
                            $equipmentAge = $maintenance->equipment->purchase_date 
                                ? $maintenance->equipment->purchase_date->diffInMonths(now()) 
                                : null;
                            $isNewEquipment = $equipmentAge && $equipmentAge <= 6;
                        @endphp
                        
                        @if($modalRatingCriteria->count() == 0)
                        <div class="bg-red-50 border border-red-200 p-3 rounded-md">
                            <p class="text-sm text-red-800">
                                <strong>⚠️ Error:</strong> No se encontraron criterios de evaluación.
                            </p>
                            <p class="text-xs text-red-600 mt-1">
                                Ejecuta: <code>php artisan db:seed --class=RatingCriteriaSeeder</code>
                            </p>
                        </div>
                        @endif
                        
                        @if($previousScore)
                        <div class="bg-yellow-50 border border-yellow-200 p-3 rounded-md">
                            <p class="text-sm text-yellow-800">
                                <strong>Evaluación anterior:</strong> {{ $previousScore }}% ({{ \App\Models\EquipmentRating::calculateCategory($previousScore) }})
                            </p>
                            <p class="text-xs text-yellow-600 mt-1">
                                La nueva evaluación no debe exceder este valor (sistema de degradación)
                            </p>
                        </div>
                        @elseif($isNewEquipment)
                        <div class="bg-blue-50 border border-blue-200 p-3 rounded-md">
                            <p class="text-sm text-blue-800">
                                <strong>🎆 Equipo nuevo:</strong> Menos de 6 meses desde la compra. Primera evaluación.
                            </p>
                        </div>
                        @else
                        <div class="bg-green-50 border border-green-200 p-3 rounded-md">
                            <p class="text-sm text-green-800">
                                <strong>🎯 Evaluación inicial:</strong> Este es el primer registro de calificación para este equipo.
                            </p>
                            <p class="text-xs text-green-600 mt-1">
                                Puedes asignar la calificación que consideres adecuada según el estado actual del equipo.
                            </p>
                        </div>
                        @endif
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-1 gap-4">
                                @foreach($modalRatingCriteria as $criterion)
                                <div class="border-b border-gray-200 pb-3 mb-3">
                                    <div class="flex justify-between items-center mb-2">
                                        <label class="text-sm font-medium text-gray-700">
                                            {{ $criterion->label }}
                                            <span class="text-xs text-gray-500">({{ $criterion->weight_percentage }}%)</span>
                                        </label>
                                    </div>
                                    
                                    @if($criterion->auto_calculated && $criterion->name === 'equipment_age')
                                        @php
                                            $ageValue = 10; // Default
                                            if ($equipmentAge) {
                                                foreach($criterion->options as $option) {
                                                    // Extraer el número de meses de la etiqueta (ej: "6 meses" -> 6)
                                                    preg_match('/\d+/', $option['label'], $matches);
                                                    $months = isset($matches[0]) ? (int)$matches[0] : 0;
                                                    if ($equipmentAge >= $months) {
                                                        $ageValue = $option['value'];
                                                        break;
                                                    }
                                                }
                                            }
                                        @endphp
                                        <select name="rating[{{ $criterion->id }}]" 
                                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                                required>
                                            @foreach($criterion->options as $option)
                                                <option value="{{ $option['value'] }}" 
                                                        {{ $option['value'] == $ageValue ? 'selected' : '' }}>
                                                    {{ $option['label'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <p class="text-xs text-gray-500 mt-1">Auto-calculado basado en fecha de compra ({{ $equipmentAge }} meses)</p>
                                    @else
                                        <select name="rating[{{ $criterion->id }}]" 
                                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                                required>
                                            <option value="">Seleccionar...</option>
                                            @foreach($criterion->options as $option)
                                                <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="bg-blue-50 border border-blue-200 p-3 rounded-md">
                            <div class="text-sm text-blue-800">
                                <strong>Resultado de la Evaluación:</strong>
                                <div id="calculatedScore" class="text-lg font-bold">0.00%</div>
                                <div id="ratingCategory" class="text-sm"></div>
                            </div>
                        </div>
                        
                        <div>
                            <label for="rating_notes" class="block text-sm font-medium text-gray-700 mb-2">Notas de la Evaluación</label>
                            <textarea id="rating_notes" name="rating_notes" rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Observaciones sobre la evaluación..."></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('completeModal').classList.add('hidden')" 
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Cancelar
                    </button>
                    <button type="submit" id="submitButton" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700" disabled>
                        Completar Mantenimiento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const criteriaData = @json($modalRatingCriteria ?? []);
    const previousScore = {{ $previousScore ?? 0 }};
    const submitButton = document.getElementById('submitButton');
    
    // Handle checklist radio button changes
    document.querySelectorAll('input[name^="checklist["]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const match = this.name.match(/checklist\[(\d+)\]\[status\]/);
            if (match) {
                const index = match[1];
                const detailsDiv = document.getElementById(`details-${index}`);
                const detailsInput = detailsDiv.querySelector('input[type="text"]');
                
                if (this.value === 'incorrecto') {
                    detailsDiv.style.display = 'block';
                    detailsInput.required = true;
                } else {
                    detailsDiv.style.display = 'none';
                    detailsInput.required = false;
                    detailsInput.value = '';
                }
            }
            validateForm();
        });
    });
    
    // Handle details input changes
    document.querySelectorAll('input[name^="checklist["][name$="[details]"]').forEach(function(input) {
        input.addEventListener('input', validateForm);
    });
    
    function validateForm() {
        // Check if maintenance fields are filled
        const completedDate = document.getElementById('completed_date').value;
        const performedActions = document.getElementById('performed_actions').value.trim();
        
        // Check if all checklist items are completed
        let allChecklistCompleted = true;
        let checklistValidationMessage = '';
        const checklistItems = document.querySelectorAll('input[name^="checklist["][name$="[status]"]');
        const totalItems = checklistItems.length / 3; // 3 options per item
        let completedItems = 0;
        
        for (let i = 0; i < totalItems; i++) {
            const radios = document.querySelectorAll(`input[name="checklist[${i}][status]"]`);
            let itemCompleted = false;
            let selectedValue = null;
            
            radios.forEach(function(radio) {
                if (radio.checked) {
                    itemCompleted = true;
                    selectedValue = radio.value;
                }
            });
            
            if (!itemCompleted) {
                allChecklistCompleted = false;
            } else {
                completedItems++;
                
                // Check if details are required and provided
                if (selectedValue === 'incorrecto') {
                    const detailsInput = document.querySelector(`input[name="checklist[${i}][details]"]`);
                    if (!detailsInput.value.trim()) {
                        allChecklistCompleted = false;
                        checklistValidationMessage = 'Complete los detalles para los elementos marcados como incorrectos.';
                    }
                }
            }
        }
        
        if (!checklistValidationMessage) {
            if (completedItems === totalItems) {
                checklistValidationMessage = `✅ Checklist completado (${completedItems}/${totalItems})`;
            } else {
                checklistValidationMessage = `📋 Checklist: ${completedItems}/${totalItems} completados`;
            }
        }
        
        // Update checklist validation message
        const checklistValidationDiv = document.getElementById('checklist-validation');
        if (allChecklistCompleted && completedItems === totalItems) {
            checklistValidationDiv.className = 'text-sm text-green-600';
        } else {
            checklistValidationDiv.className = 'text-sm text-orange-600';
        }
        checklistValidationDiv.textContent = checklistValidationMessage;
        
        // Check if all rating criteria are selected
        let allRatingsSelected = true;
        criteriaData.forEach(function(criterion) {
            const select = document.querySelector(`select[name="rating[${criterion.id}]"]`);
            if (!select || !select.value) {
                allRatingsSelected = false;
            }
        });
        
        // Check if form is complete (now including checklist)
        const isFormComplete = completedDate && performedActions && allRatingsSelected && allChecklistCompleted;
        
        // Calculate rating and validate
        let totalScore = 0;
        let isValidRating = true;
        
        criteriaData.forEach(function(criterion) {
            const select = document.querySelector(`select[name="rating[${criterion.id}]"]`);
            if (select && select.value) {
                const value = parseInt(select.value);
                const weighted = (criterion.weight_percentage * value) / 10;
                totalScore += weighted;
            }
        });
        
        // Update score display
        document.getElementById('calculatedScore').textContent = totalScore.toFixed(2) + '%';
        
        // Update category - Lógica: 100% = Excelente, hacia abajo = peor calidad
        let category = '';
        if (totalScore > 90) category = 'Excelente 🟢';    // 100% - 90.1%
        else if (totalScore > 80) category = 'Óptimo 🔵'; // 90% - 80.1%
        else if (totalScore > 70) category = 'Regular 🟡'; // 80% - 70.1%
        else if (totalScore > 60) category = 'Para Cambio 🟠'; // 70% - 60.1%
        else category = 'Reemplazo 🔴'; // 60% - 0%
        
        document.getElementById('ratingCategory').textContent = `Categoría: ${category}`;
        
        // Validate against previous score (degradation only)
        if (previousScore > 0 && totalScore > previousScore) {
            isValidRating = false;
        }
        
        // Show validation message
        let validationDiv = document.getElementById('validationMessage');
        if (!validationDiv) {
            validationDiv = document.createElement('div');
            validationDiv.id = 'validationMessage';
            validationDiv.className = 'mt-2 text-sm';
            document.getElementById('ratingCategory').parentNode.appendChild(validationDiv);
        }
        
        if (!isValidRating && previousScore > 0) {
            validationDiv.className = 'mt-2 text-sm text-red-600';
            validationDiv.textContent = `La nueva evaluación (${totalScore.toFixed(2)}%) no puede ser mejor que la anterior (${previousScore}%)`;
        } else if (allRatingsSelected) {
            validationDiv.className = 'mt-2 text-sm text-green-600';
            validationDiv.textContent = 'Evaluación válida';
        } else {
            validationDiv.className = 'mt-2 text-sm text-gray-600';
            validationDiv.textContent = 'Complete todos los criterios de evaluación';
        }
        
        // Enable/disable submit button
        const canSubmit = isFormComplete && isValidRating;
        submitButton.disabled = !canSubmit;
        
        if (canSubmit) {
            submitButton.className = 'bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700';
        } else {
            submitButton.className = 'bg-gray-400 text-white px-4 py-2 rounded-md cursor-not-allowed';
        }
    }
    
    // Add event listeners to form fields
    document.getElementById('completed_date').addEventListener('change', validateForm);
    document.getElementById('performed_actions').addEventListener('input', validateForm);
    
    // Add event listeners to all rating selects
    document.querySelectorAll('select[name^="rating["]').forEach(function(select) {
        select.addEventListener('change', validateForm);
    });
    
    // Initial validation
    validateForm();
});
</script>
@endif
@endif
@endsection