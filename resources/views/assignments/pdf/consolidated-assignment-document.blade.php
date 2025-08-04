<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documento Consolidado de Asignación de Equipos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #333;
            margin: 0;
            padding: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .document-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 8px;
        }
        .header-info {
            display: table;
            width: 100%;
            margin-top: 15px;
            font-size: 10px;
        }
        .header-left, .header-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .header-right {
            text-align: right;
        }
        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            background-color: #f5f5f5;
            padding: 6px;
            border-left: 4px solid #333;
            margin-bottom: 8px;
        }
        .user-info {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .user-row {
            display: table-row;
        }
        .user-label {
            display: table-cell;
            font-weight: bold;
            width: 25%;
            padding: 3px 8px 3px 0;
            vertical-align: top;
        }
        .user-value {
            display: table-cell;
            padding: 3px 0;
            border-bottom: 1px dotted #ccc;
            vertical-align: top;
        }
        .equipment-item {
            border: 1px solid #ddd;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #fafafa;
        }
        .equipment-header {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 8px;
            background-color: #e9e9e9;
            padding: 5px;
            border-left: 3px solid #666;
        }
        .equipment-details {
            display: table;
            width: 100%;
        }
        .detail-row {
            display: table-row;
        }
        .detail-label {
            display: table-cell;
            font-weight: bold;
            width: 20%;
            padding: 2px 8px 2px 0;
            vertical-align: top;
            font-size: 10px;
        }
        .detail-value {
            display: table-cell;
            padding: 2px 0;
            vertical-align: top;
            font-size: 10px;
        }
        .valuation-bar {
            margin-top: 5px;
            height: 20px;
            background-color: #e0e0e0;
            border-radius: 10px;
            position: relative;
            overflow: hidden;
        }
        .valuation-fill {
            height: 100%;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 9px;
        }
        .val-100 { background-color: #4CAF50; }
        .val-90 { background-color: #8BC34A; }
        .val-80 { background-color: #CDDC39; }
        .val-70 { background-color: #FF9800; }
        .val-60 { background-color: #F44336; }
        .terms {
            background-color: #f9f9f9;
            padding: 12px;
            border: 1px solid #ddd;
            margin-top: 20px;
            font-size: 10px;
            page-break-inside: avoid;
        }
        .terms-title {
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 11px;
        }
        .terms ul {
            margin: 8px 0;
            padding-left: 15px;
        }
        .terms li {
            margin-bottom: 3px;
        }
        .signature-section {
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .signature-title {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 15px;
            text-align: center;
        }
        .signature-boxes {
            margin-bottom: 25px;
        }
        .signature-box {
            border: 1px solid #333;
            padding: 15px;
            margin-bottom: 10px;
            min-height: 80px;
        }
        .signature-header {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 10px;
            text-align: center;
            background-color: #f0f0f0;
            padding: 5px;
            margin: -15px -15px 10px -15px;
        }
        .signature-content {
            text-align: center;
            padding-top: 20px;
        }
        .signature-line {
            border-top: 2px solid #333;
            margin-top: 40px;
            padding-top: 5px;
            text-align: center;
        }
        .promissory-note {
            background-color: #fff8dc;
            border: 2px solid #d4a574;
            padding: 15px;
            margin: 20px 0;
            font-size: 10px;
        }
        .promissory-title {
            font-weight: bold;
            text-align: center;
            font-size: 12px;
            margin-bottom: 10px;
            text-decoration: underline;
        }
        .promissory-content {
            text-align: justify;
            line-height: 1.4;
        }
        .footer {
            position: fixed;
            bottom: 15px;
            left: 15px;
            right: 15px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 8px;
        }
        @media print {
            body { margin: 0; }
            .footer { position: fixed; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">SISTEMA DE GESTIÓN DE ACTIVOS TI</div>
        <div class="document-title">DOCUMENTO CONSOLIDADO DE ASIGNACIÓN DE EQUIPOS</div>
        
        <div class="header-info">
            <div class="header-left">
                <strong>No. Empleado:</strong> {{ $itUser->employee_id ?? 'N/A' }}<br>
                <strong>Departamento:</strong> {{ $itUser->department ?? 'N/A' }}
            </div>
            <div class="header-right">
                <strong>Fecha:</strong> {{ now()->format('d/m/Y') }}<br>
                <strong>Asignación:</strong> {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">INFORMACIÓN DEL EMPLEADO</div>
        <div class="user-info">
            <div class="user-row">
                <div class="user-label">Asignado A:</div>
                <div class="user-value">{{ $itUser->name ?? 'N/A' }}</div>
            </div>
            <div class="user-row">
                <div class="user-label">No. Empleado:</div>
                <div class="user-value">{{ $itUser->employee_id ?? 'N/A' }}</div>
            </div>
            <div class="user-row">
                <div class="user-label">Departamento:</div>
                <div class="user-value">{{ $itUser->department ?? 'N/A' }}</div>
            </div>
            <div class="user-row">
                <div class="user-label">Cargo:</div>
                <div class="user-value">{{ $itUser->position ?? 'N/A' }}</div>
            </div>
            <div class="user-row">
                <div class="user-label">Correo Electrónico:</div>
                <div class="user-value">{{ $itUser->email ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">EQUIPOS ASIGNADOS</div>
        @forelse($assignments as $assignment)
        <div class="equipment-item">
            <div class="equipment-header">
                EQUIPO #{{ $loop->iteration }} - {{ $assignment->equipment->equipmentType->name ?? 'N/A' }}
            </div>
            
            <div class="equipment-details">
                <div class="detail-row">
                    <div class="detail-label">Tipo:</div>
                    <div class="detail-value">{{ $assignment->equipment->equipmentType->name ?? 'N/A' }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Marca y Modelo:</div>
                    <div class="detail-value">{{ $assignment->equipment->brand ?? 'N/A' }} {{ $assignment->equipment->model ?? 'N/A' }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">No. Serie:</div>
                    <div class="detail-value">{{ $assignment->equipment->serial_number ?? 'N/A' }}</div>
                </div>
                @if($assignment->equipment->asset_tag)
                <div class="detail-row">
                    <div class="detail-label">Etiqueta Activo:</div>
                    <div class="detail-value">{{ $assignment->equipment->asset_tag }}</div>
                </div>
                @endif
                @if($assignment->equipment->invoice_number)
                <div class="detail-row">
                    <div class="detail-label">No. Factura:</div>
                    <div class="detail-value">{{ $assignment->equipment->invoice_number }}</div>
                </div>
                @endif
                @if($assignment->equipment->specifications)
                <div class="detail-row">
                    <div class="detail-label">Especificaciones:</div>
                    <div class="detail-value">{{ $assignment->equipment->specifications }}</div>
                </div>
                @endif
                @if($assignment->equipment->valoracion)
                <div class="detail-row">
                    <div class="detail-label">Valoración:</div>
                    <div class="detail-value">
                        @php
                            $valoracion = $assignment->equipment->valoracion;
                            $percentage = (int) str_replace('%', '', $valoracion);
                            $class = '';
                            if ($percentage >= 90) $class = 'val-100';
                            elseif ($percentage >= 80) $class = 'val-90';
                            elseif ($percentage >= 70) $class = 'val-80';
                            elseif ($percentage >= 60) $class = 'val-70';
                            else $class = 'val-60';
                        @endphp
                        <div class="valuation-bar">
                            <div class="valuation-fill {{ $class }}" style="width: {{ $percentage }}%;">
                                {{ $valoracion }}
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="detail-row">
                    <div class="detail-label">Fecha Asignación:</div>
                    <div class="detail-value">{{ $assignment->assigned_at ? $assignment->assigned_at->format('d/m/Y H:i') : 'N/A' }}</div>
                </div>
                @if($assignment->assignment_notes)
                <div class="detail-row">
                    <div class="detail-label">Notas:</div>
                    <div class="detail-value">{{ $assignment->assignment_notes }}</div>
                </div>
                @endif
            </div>
        </div>
        @empty
        <p>No hay equipos asignados.</p>
        @endforelse
    </div>

    <div class="terms">
        <div class="terms-title">TÉRMINOS Y CONDICIONES DE USO</div>
        <ul>
            <li>Los equipos asignados son propiedad de la empresa y deben ser utilizados exclusivamente para fines laborales.</li>
            <li>El usuario es responsable del cuidado y buen uso de todos los equipos asignados.</li>
            <li>Cualquier daño, pérdida o mal funcionamiento debe ser reportado inmediatamente al departamento de TI.</li>
            <li>Los equipos deben ser devueltos en las mismas condiciones en que fueron entregados, considerando el desgaste normal.</li>
            <li>No está permitido instalar software no autorizado o realizar modificaciones a los equipos.</li>
            <li>El usuario debe seguir las políticas de seguridad informática de la empresa.</li>
            <li>Al finalizar la relación laboral, todos los equipos deben ser devueltos inmediatamente.</li>
            <li>El usuario se hace responsable de mantener en buen estado todos los equipos asignados.</li>
        </ul>
    </div>

    <div class="signature-section">
        <div class="signature-title">PRIMERA FIRMA - ACEPTACIÓN DE EQUIPOS</div>
        <div class="signature-boxes">
            <div class="signature-box">
                <div class="signature-header">USUARIO RECEPTOR</div>
                <div class="signature-content">
                    <p>Por la presente, acepto en calidad de asignación los equipos detallados anteriormente, comprometiéndome a cumplir con todos los términos y condiciones establecidos.</p>
                    <div class="signature-line">
                        <strong>{{ $itUser->name ?? 'N/A' }}</strong><br>
                        Firma: ________________________<br>
                        Fecha: ________________________
                    </div>
                </div>
            </div>
            
            <div class="signature-box">
                <div class="signature-header">DEPARTAMENTO DE TI</div>
                <div class="signature-content">
                    <p>Entrego los equipos mencionados en perfectas condiciones de funcionamiento y autorizo su uso conforme a las políticas establecidas.</p>
                    <div class="signature-line">
                        <strong>{{ $assignedBy->name ?? 'N/A' }}</strong><br>
                        Firma: ________________________<br>
                        Fecha: ________________________
                    </div>
                </div>
            </div>
        </div>

        <div class="promissory-note">
            <div class="promissory-title">PAGARÉ POR EQUIPOS ASIGNADOS</div>
            <div class="promissory-content">
                <p>Por el presente pagaré, me obligo a pagar incondicionalmente a la orden de <strong>SISTEMA DE GESTIÓN DE ACTIVOS TI</strong>, el valor total de los equipos que me han sido asignados en las condiciones siguientes:</p>
                
                <p><strong>VALOR TOTAL:</strong> $______________________</p>
                
                <p>Este pagaré se hará efectivo en caso de:</p>
                <ul>
                    <li>Pérdida total o parcial de cualquiera de los equipos asignados</li>
                    <li>Daño intencional o por negligencia a los equipos</li>
                    <li>No devolución de los equipos al término de la relación laboral</li>
                    <li>Uso indebido que resulte en daños irreparables</li>
                </ul>
                
                <p>Acepto que este documento tiene plena validez legal y me comprometo a su cumplimiento.</p>
            </div>
        </div>

        <div class="signature-title">SEGUNDA FIRMA - ACEPTACIÓN DEL PAGARÉ</div>
        <div class="signature-box">
            <div class="signature-header">USUARIO - ACEPTACIÓN DE RESPONSABILIDAD ECONÓMICA</div>
            <div class="signature-content">
                <p>Acepto la responsabilidad económica sobre los equipos asignados y las condiciones del pagaré arriba mencionado.</p>
                <div class="signature-line">
                    <strong>{{ $itUser->name ?? 'N/A' }}</strong><br>
                    Firma: ________________________<br>
                    Fecha: ________________________<br>
                    Cédula: _______________________
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        Sistema de Gestión de Activos TI - Documento generado automáticamente el {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>