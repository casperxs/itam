@extends('layouts.app')

@section('title', 'Documentos de Usuario - ITAM System')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Documentos de {{ $itUser->name }}</h1>
            <p class="text-gray-600">{{ $itUser->department }} - {{ $itUser->position }}</p>
        </div>
        <div class="flex space-x-2">
            <button onclick="document.getElementById('uploadModal').classList.remove('hidden')" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Subir Documento
            </button>
            <a href="{{ route('it-users.show', $itUser) }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                Volver al Usuario
            </a>
        </div>
    </div>
</div>

<!-- Lista de Documentos -->
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-900">Documentos del Usuario</h2>
    </div>
    <div class="px-6 py-4">
        @if($itUser->documents && $itUser->documents->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tamaño</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subido</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($itUser->documents as $document)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                @if(Str::endsWith($document->filename, '.pdf'))
                                                    <svg class="h-6 w-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M4 18h12V6l-4-4H4v16zm8-14v4h4l-4-4z"/>
                                                    </svg>
                                                @else
                                                    <svg class="h-6 w-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M4 4c0-1.1.9-2 2-2h8l4 4v10c0 1.1-.9 2-2 2H6c-1.1 0-2-.9-2-2V4z"/>
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $document->original_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $document->description ?? 'Sin descripción' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div>{{ strtoupper(pathinfo($document->filename, PATHINFO_EXTENSION)) }}</div>
                                    @if($document->document_type)
                                        <div class="text-xs text-gray-500 mt-1">
                                            @switch($document->document_type)
                                                @case('manual') Manual @break
                                                @case('contrato') Contrato @break
                                                @case('identificacion') Identificación @break
                                                @case('capacitacion') Capacitación @break
                                                @case('politica') Política @break
                                                @default {{ ucfirst($document->document_type) }} @break
                                            @endswitch
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $document->file_size ? number_format($document->file_size / 1024, 0) . ' KB' : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $document->created_at ? $document->created_at->format('d/m/Y H:i') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('it-users.download-document', [$itUser, $document]) }}" class="text-blue-600 hover:text-blue-900">
                                            Descargar
                                        </a>
                                        <form method="POST" action="{{ route('it-users.delete-document', [$itUser, $document]) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Estás seguro de eliminar este documento?')">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay documentos</h3>
                <p class="mt-1 text-sm text-gray-500">Comienza subiendo el primer documento para este usuario.</p>
                <div class="mt-6">
                    <button onclick="document.getElementById('uploadModal').classList.remove('hidden')" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Subir Documento
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal de Subir Documento -->
<div id="uploadModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Subir Documento</h3>
                <button onclick="document.getElementById('uploadModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form method="POST" action="{{ route('it-users.upload-document', $itUser) }}" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-4">
                    <label for="document_type" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Documento</label>
                    <select id="document_type" name="document_type" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Seleccionar tipo</option>
                        <option value="manual">Manual de usuario</option>
                        <option value="contrato">Contrato/Acuerdo</option>
                        <option value="identificacion">Identificación</option>
                        <option value="capacitacion">Capacitación</option>
                        <option value="politica">Política/Procedimiento</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="document" class="block text-sm font-medium text-gray-700 mb-2">Seleccionar Archivo</label>
                    <input type="file" id="document" name="document" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <p class="mt-1 text-xs text-gray-500">Formatos permitidos: PDF, DOC, DOCX, JPG, PNG (máx. 10MB)</p>
                </div>
                
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Descripción (opcional)</label>
                    <textarea id="description" name="description" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Descripción del documento..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('uploadModal').classList.add('hidden')" 
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Subir Documento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection