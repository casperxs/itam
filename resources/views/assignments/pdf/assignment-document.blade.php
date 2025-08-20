<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documento de Asignación de Equipo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .document-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            background-color: #f5f5f5;
            padding: 8px;
            border-left: 4px solid #333;
            margin-bottom: 10px;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            font-weight: bold;
            width: 30%;
            padding: 5px 10px 5px 0;
            vertical-align: top;
        }
        .info-value {
            display: table-cell;
            padding: 5px 0;
            border-bottom: 1px dotted #ccc;
            vertical-align: top;
        }
        .signature-section {
            margin-top: 40px;
            page-break-inside: avoid;
        }
        .signature-boxes {
            display: table;
            width: 100%;
            margin-top: 30px;
        }
        .signature-box {
            display: table-cell;
            width: 48%;
            text-align: center;
            padding: 20px 0;
        }
        .signature-line {
            border-top: 2px solid #333;
            margin-top: 50px;
            padding-top: 5px;
        }
        .footer {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        .terms {
            background-color: #f9f9f9;
            padding: 15px;
            border: 1px solid #ddd;
            margin-top: 20px;
            font-size: 11px;
        }
        .terms-title {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .terms ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .terms li {
            margin-bottom: 5px;
        }
        @media print {
            body { margin: 0; }
            .footer { position: fixed; }
        }
    </style>
</head>
<body>
    <div class="header" background-image: url({{ asset('storage/images/background/bg_bkb_registros_nDerecho.png') }}); >
        <div class="company-name">SISTEMA DE GESTIÓN DE ACTIVOS TI</div>
        <div class="document-title">DOCUMENTO DE ASIGNACIÓN DE EQUIPO PRO</div>
        <div style="margin-top: 10px; font-size: 12px;">
            <strong>Folio: {{ $assignment->id ?? 'N/A' }} | Fecha: {{ now()->format('d/m/Y H:i') }}</strong>
        </div>
    </div>
<div class="container">
  <div class="row">
    <div class="col-6">
      <!-- Contenido de la columna 1 -->
		    <div class="section">
        <div class="section-title">INFORMACIÓN DEL EQUIPO</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Tipo de Equipo:</div>
                <div class="info-value">{{ $assignment->equipment->equipmentType->name ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Marca y Modelo:</div>
                <div class="info-value">{{ $assignment->equipment->brand ?? 'N/A' }} {{ $assignment->equipment->model ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Número de Serie:</div>
                <div class="info-value">{{ $assignment->equipment->serial_number ?? 'N/A' }}</div>
            </div>
            @if($assignment->equipment->asset_tag)
            <div class="info-row">
                <div class="info-label">Etiqueta de Activo:</div>
                <div class="info-value">{{ $assignment->equipment->asset_tag }}</div>
            </div>
            @endif
            @if($assignment->equipment->specifications)
            <div class="info-row">
                <div class="info-label">Especificaciones:</div>
                <div class="info-value">{{ $assignment->equipment->specifications }}</div>
            </div>
            @endif
        </div>
    </div>

    </div>
    <div class="col-6">
      <!-- Contenido de la columna 2 -->
		    <div class="section">
        <div class="section-title">INFORMACIÓN DEL USUARIO</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nombre Completo:</div>
                <div class="info-value">{{ $assignment->itUser->name ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">ID de Empleado:</div>
                <div class="info-value">{{ $assignment->itUser->employee_id ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Departamento:</div>
                <div class="info-value">{{ $assignment->itUser->department ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Cargo:</div>
                <div class="info-value">{{ $assignment->itUser->position ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Correo Electrónico:</div>
                <div class="info-value">{{ $assignment->itUser->email ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    </div>
  </div>
</div>


    <div class="section">
        <div class="section-title">DETALLES DE LA ASIGNACIÓN</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Fecha de Asignación:</div>
                <div class="info-value">{{ $assignment->assigned_at ? $assignment->assigned_at->format('d/m/Y H:i') : 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Asignado por:</div>
                <div class="info-value">{{ $assignment->assignedBy->name ?? 'N/A' }}</div>
            </div>
            @if($assignment->assignment_notes)
            <div class="info-row">
                <div class="info-label">Notas de Asignación:</div>
                <div class="info-value">{{ $assignment->assignment_notes }}</div>
            </div>
            @endif
        </div>
    </div>

    <div class="terms">
        <div class="terms-title">TÉRMINOS Y CONDICIONES DE USO</div>
        <ul>
            <li>El equipo asignado es propiedad de la empresa y debe ser utilizado exclusivamente para fines laborales.</li>
            <li>El usuario es responsable del cuidado y buen uso del equipo asignado.</li>
            <li>Cualquier daño, pérdida o mal funcionamiento debe ser reportado inmediatamente al departamento de TI.</li>
            <li>El equipo debe ser devuelto en las mismas condiciones en que fue entregado, considerando el desgaste normal.</li>
            <li>No está permitido instalar software no autorizado o realizar modificaciones al equipo.</li>
            <li>El usuario debe seguir las políticas de seguridad informática de la empresa.</li>
            <li>Al finalizar la relación laboral, el equipo debe ser devuelto inmediatamente.</li>
        </ul>
    </div>

    <div class="signature-section">
        <div class="signature-boxes">
            <div class="signature-box">
                <div class="signature-line">
                    <strong>{{ $assignment->itUser->name ?? 'N/A' }}</strong><br>
                    Usuario Receptor<br>
                    Fecha: ________________
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line">
                    <strong>{{ $assignment->assignedBy->name ?? 'N/A' }}</strong><br>
                    Departamento de TI<br>
                    Fecha: ________________
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        Sistema de Gestión de Activos TI - Documento generado automáticamente el {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>