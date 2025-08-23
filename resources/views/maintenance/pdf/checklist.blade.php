<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checklist de Mantenimiento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.2;
            color: #333;
            margin: 0;
            padding: 15px;
        }
        .header {
            border: 2px solid #333;
            padding: 20px;
            margin-bottom: 20px;
            position: relative;
            min-height: 80px;
        }
        .header-codes {
            position: absolute;
            top: 15px;
            left: 15px;
            font-weight: bold;
            font-size: 10px;
            line-height: 1.3;
        }
        .company-info {
            text-align: center;
            padding: 5px;
            margin: 0 auto;
        }
        .document-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        .section {
            margin-bottom: 12px;
        }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            background-color: #f5f5f5;
            padding: 4px;
            border-left: 3px solid #333;
            margin-bottom: 6px;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            font-weight: bold;
            width: 25%;
            padding: 2px 5px 2px 0;
            vertical-align: top;
        }
        .info-value {
            display: table-cell;
            padding: 2px 0;
            border-bottom: 1px dotted #ccc;
            vertical-align: top;
        }
        .checklist-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 9px;
        }
        .checklist-table th,
        .checklist-table td {
            border: 1px solid #333;
            padding: 3px 2px;
            text-align: center;
        }
        .checklist-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .checklist-table td.text-left {
            text-align: left;
        }
        .checkbox {
            width: 12px;
            height: 12px;
            border: 1px solid #333;
            display: inline-block;
        }
        /* BARRA DE VALORACIÓN */
        .valuation-bar {
            height: 15px;
            background-color: #e0e0e0;
            border-radius: 8px;
            position: relative;
            overflow: hidden;
            margin: 5px 0;
            border: 1px solid #ccc;
        }
        .valuation-fill {
            height: 100%;
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 8px;
        }
        .val-excelente { background-color: #4CAF50; }
        .val-optimo { background-color: #8BC34A; }
        .val-bueno { background-color: #CDDC39; color: #333; }
        .val-regular { background-color: #FF9800; }
        .val-malo { background-color: #F44336; }
        .observations {
            width: 100%;
            min-height: 40px;
            border: 1px solid #333;
            padding: 5px;
            margin-bottom: 10px;
        }
        .signature-section {
            margin-top: 15px;
        }
        .signature-boxes {
            display: table;
            width: 100%;
        }
        .signature-box {
            display: table-cell;
            width: 32%;
            text-align: center;
            padding: 15px 5px;
            border: 1px solid #333;
            margin-right: 2%;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 30px;
            padding-top: 3px;
            font-size: 8px;
        }
        .two-column {
            display: table;
            width: 100%;
        }
        .left-column,
        .right-column {
            display: table-cell;
            width: 48%;
            vertical-align: top;
        }
        .right-column {
            padding-left: 4%;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-codes">
            PRO-22-A<br>
            Rev.00
        </div>
        <div class="company-info">
            <div class="document-title">CHECKLIST DE MANTENIMIENTO</div>
            <div style="margin-top: 5px; font-size: 10px;">
                ID Mantenimiento: {{ $maintenance->id }} | Fecha: {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>
    </div>

    <div class="two-column">
        <div class="left-column">
            <div class="section">
                <div class="section-title">DATOS DEL EQUIPO</div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Tipo:</div>
                        <div class="info-value">{{ $maintenance->equipment->equipmentType->name ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Marca/Modelo:</div>
                        <div class="info-value">{{ $maintenance->equipment->brand ?? 'N/A' }} {{ $maintenance->equipment->model ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Serie:</div>
                        <div class="info-value">{{ $maintenance->equipment->serial_number ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Tag:</div>
                        <div class="info-value">{{ $maintenance->equipment->asset_tag ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="right-column">
            <div class="section">
                <div class="section-title">DATOS DEL USUARIO</div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Nombre:</div>
                        <div class="info-value">{{ $maintenance->equipment->currentAssignment->itUser->name ?? 'No asignado' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Departamento:</div>
                        <div class="info-value">{{ $maintenance->equipment->currentAssignment->itUser->department ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">ID Empleado:</div>
                        <div class="info-value">{{ $maintenance->equipment->currentAssignment->itUser->employee_id ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">VALORACIÓN DEL EQUIPO</div>
        @php
            // Obtener la valoración del equipo
            $valoracion = $maintenance->equipment->valoracion ?? 'Regular';
            $class = '';
            $percentage = 70; // Default para Regular

            // Lógica de valoración como en otros PDFs
            if (stripos($valoracion, 'excelente') !== false || $valoracion == '100%') {
                $class = 'val-excelente';
                $percentage = 100;
            } elseif (stripos($valoracion, 'óptimo') !== false || stripos($valoracion, 'optimo') !== false || $valoracion == '90%') {
                $class = 'val-optimo';
                $percentage = 90;
            } elseif (stripos($valoracion, 'bueno') !== false || $valoracion == '80%') {
                $class = 'val-bueno';
                $percentage = 80;
            } elseif (stripos($valoracion, 'regular') !== false || $valoracion == '70%') {
                $class = 'val-regular';
                $percentage = 70;
            } elseif (stripos($valoracion, 'malo') !== false || stripos($valoracion, 'para cambio') !== false || stripos($valoracion, 'reemplazo') !== false || $valoracion == '60%') {
                $class = 'val-malo';
                $percentage = 60;
            } else {
                $class = 'val-regular';
                $percentage = 70;
            }
        @endphp
        <div style="margin-bottom: 10px; font-size: 10px;">
            <strong>Valoración:</strong>
            <div class="valuation-bar">
                <div class="valuation-fill {{ $class }}" style="width: {{ $percentage }}%;">
                    {{ $valoracion }}
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">ACTIVIDADES REALIZADAS</div>
        <table class="checklist-table">
            <thead>
                <tr>
                    <th style="width: 35%;">Actividad</th>
                    <th style="width: 12%;">Correcto</th>
                    <th style="width: 12%;">N/A</th>
                    <th style="width: 12%;">Incorrecto</th>
                    <th style="width: 29%;">Detalles</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $checklistData = $maintenance->checklist_data ?? [];
                    // Create a map for easy lookup
                    $checklistMap = [];
                    foreach ($checklistData as $item) {
                        $checklistMap[$item['activity']] = $item;
                    }
                @endphp
                
                @foreach([
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
                ] as $index => $activity)
                    @php
                        $item = $checklistMap[$activity] ?? null;
                        $status = $item['status'] ?? null;
                        $details = $item['details'] ?? '';
                    @endphp
                    <tr>
                        <td class="text-left">{{ $index + 1 }}. {{ $activity }}</td>
                        <td><span class="checkbox">{{ $status === 'correcto' ? '✓' : '' }}</span></td>
                        <td><span class="checkbox">{{ $status === 'na' ? '✓' : '' }}</span></td>
                        <td><span class="checkbox">{{ $status === 'incorrecto' ? '✓' : '' }}</span></td>
                        <td class="text-left" style="font-size: 8px;">{{ $details }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">OBSERVACIONES DEL INGENIERO</div>
        <div class="observations">
            {{ $maintenance->notes ?? '' }}
        </div>
    </div>

    <div class="signature-section">
        <div class="signature-boxes">
            <div class="signature-box">
                <div class="signature-line">
                    <strong>{{ $maintenance->equipment->currentAssignment->itUser->name ?? 'Usuario' }}</strong><br>
                    Usuario Final<br>
                    Fecha: _______________
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line">
                    <strong>Supervisor de TI</strong><br>
                    Supervisor<br>
                    Fecha: _______________
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line">
                    <strong>{{ $maintenance->performedBy->name ?? 'Ingeniero' }}</strong><br>
                    Ingeniero de Mantenimiento<br>
                    Fecha: _______________
                </div>
            </div>
        </div>
    </div>
</body>
</html>