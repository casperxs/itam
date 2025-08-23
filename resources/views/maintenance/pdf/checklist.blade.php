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
            line-height: 1.3;
            color: #333;
            margin: 0;
            padding: 15px;
            height: 100vh;
            box-sizing: border-box;
        }

        /* ENCABEZADO CON IMAGEN DE FONDO IGUAL QUE OTROS DOCUMENTOS */
        .invoice-header {
            border: 2px solid #333;
            padding: 20px;
            margin-bottom: 20px;
            position: relative;
            min-height: 160px;
            background-image: url('data:image/png;base64,{{ base64_encode(file_get_contents(storage_path('app/public/images/background/bg_bkb_registros_nIzquierdo.png'))) }}');
            background-repeat: no-repeat;
            background-position: left top;
            background-size: 400px auto;
        }

        .header-codes {
            position: absolute;
            top: 25px;
            left: 25px;
            color: white;
            font-weight: bold;
            font-size: 12px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.9);
            line-height: 1.3;
        }

        .company-info {
            text-align: right;
            padding: 5px;
            margin: 0 auto 20px auto;
            max-width: 60%;
            position: relative;
            z-index: 2;
        }

        .document-title {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin-bottom: 0;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
        }
        /* DETALLES GENERALES */
        .user-details {
            display: table;
            width: 100%;
        }
        .user-details-left, .user-details-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .user-details-right {
            text-align: right;
        }
        .detail-row {
            margin-bottom: 8px;
            font-size: 11px;
        }
        .detail-label {
            font-weight: bold;
            display: inline-block;
            min-width: 120px;
        }
        .detail-value {
            border-bottom: 1px solid #333;
            padding: 2px 5px;
            min-width: 150px;
            display: inline-block;
        }

        .section {
            margin-bottom: 12px;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            background-color: #f0f0f0;
            padding: 8px;
            border: 1px solid #333;
            text-align: center;
            margin-bottom: 0;
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
            border: 2px solid #333;
            display: inline-block;
            text-align: center;
            line-height: 8px;
            font-size: 10px;
            font-weight: bold;
        }
        /* BARRA DE VALORACIÓN - POSICIÓN MEJORADA Y MÁS PEQUEÑA */
        .valuation-section {
            margin: 10px 0;
            padding: 8px;
            border: 1px solid #333;
            background-color: #f9f9f9;
        }
        .valuation-bar {
            height: 12px;
            background-color: #e0e0e0;
            border-radius: 6px;
            position: relative;
            overflow: hidden;
            margin: 3px 0;
            border: 1px solid #ccc;
            max-width: 300px; /* Limitar ancho */
        }
        .valuation-fill {
            height: 100%;
            border-radius: 5px;
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
        .valuation-percentage {
            font-weight: bold;
            color: #333;
        }
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
    <!-- ENCABEZADO ESTILO PROFESIONAL IGUAL QUE OTROS DOCUMENTOS -->
    <div class="invoice-header">
        <div class="header-codes">
            PRO-22-A<br>
            Rev. 01
        </div>

        <div class="document-title" style="text-align: right; margin-top: 10px; margin-bottom: 20px;">
            CHECKLIST DE MANTENIMIENTO
        </div>

        <div class="user-details">
            <div class="user-details-left">
                <div class="detail-row">
                    <span class="detail-label">ID Mantenimiento:</span>
                    <span class="detail-value">{{ $maintenance->id }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tipo Mantenimiento:</span>
                    <span class="detail-value">
                        @switch($maintenance->type)
                            @case('preventive') Preventivo @break
                            @case('corrective') Correctivo @break
                            @case('update') Actualización @break
                        @endswitch
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Usuario:</span>
                    <span class="detail-value">{{ $maintenance->equipment->currentAssignment->itUser->name ?? 'No asignado' }}</span>
                </div>
            </div>
            <div class="user-details-right">
                <div class="detail-row">
                    <span class="detail-label">Fecha de Finalización:</span>
                    <span class="detail-value">{{ $maintenance->completed_date ? $maintenance->completed_date->format('d/m/Y H:i') : 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Técnico:</span>
                    <span class="detail-value">{{ $maintenance->performedBy->name ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Equipo:</span>
                    <span class="detail-value">{{ $maintenance->equipment->serial_number ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN DE DATOS DEL EQUIPO Y VALORACIÓN -->
    <div class="section">
        <div class="section-title">DATOS DEL EQUIPO Y VALORACIÓN</div>
        <div style="padding: 10px; border: 1px solid #333; border-top: none;">
            <div class="two-column">
                <div class="left-column">
                    <div style="margin-bottom: 8px; font-size: 9px;">
                        <strong>Tipo:</strong> {{ $maintenance->equipment->equipmentType->name ?? 'N/A' }}<br>
                        <strong>Marca/Modelo:</strong> {{ $maintenance->equipment->brand ?? 'N/A' }} {{ $maintenance->equipment->model ?? 'N/A' }}<br>
                        <strong>Serie:</strong> {{ $maintenance->equipment->serial_number ?? 'N/A' }}<br>
                        <strong>Tag:</strong> {{ $maintenance->equipment->asset_tag ?? 'N/A' }}
                    </div>
                </div>
                <div class="right-column">
                    <div class="valuation-section">
                        <strong style="font-size: 9px;">Valoración del Equipo:</strong>
                        @php
                            // Obtener la valoración del equipo
                            $valoracion = $maintenance->equipment->valoracion ?? 'Regular';
                            $class = '';
                            $percentage = 70; // Default para Regular
                            $percentageText = '';

                            // Lógica de valoración como en otros PDFs
                            if (stripos($valoracion, 'excelente') !== false) {
                                $class = 'val-excelente';
                                $percentage = 95;
                                $percentageText = '95%';
                            } elseif (stripos($valoracion, 'óptimo') !== false || stripos($valoracion, 'optimo') !== false) {
                                $class = 'val-optimo';
                                $percentage = 85;
                                $percentageText = '85%';
                            } elseif (stripos($valoracion, 'regular') !== false) {
                                $class = 'val-regular';
                                $percentage = 75;
                                $percentageText = '75%';
                            } elseif (stripos($valoracion, 'para cambio') !== false) {
                                $class = 'val-malo';
                                $percentage = 65;
                                $percentageText = '65%';
                            } elseif (stripos($valoracion, 'reemplazo') !== false) {
                                $class = 'val-malo';
                                $percentage = 50;
                                $percentageText = '50%';
                            } else {
                                // Si contiene un porcentaje directo, extraerlo
                                if (preg_match('/([0-9]+(?:\.[0-9]+)?)%/', $valoracion, $matches)) {
                                    $percentage = (float)$matches[1];
                                    $percentageText = $matches[0];
                                    if ($percentage >= 90) $class = 'val-excelente';
                                    elseif ($percentage >= 80) $class = 'val-optimo';
                                    elseif ($percentage >= 70) $class = 'val-regular';
                                    else $class = 'val-malo';
                                } else {
                                    $class = 'val-regular';
                                    $percentage = 70;
                                    $percentageText = '70%';
                                }
                            }
                        @endphp
                        <div class="valuation-bar">
                            <div class="valuation-fill {{ $class }}" style="width: {{ $percentage }}%;">
                                <span class="valuation-percentage">{{ $percentageText }} - {{ $valoracion }}</span>
                            </div>
                        </div>
                    </div>
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
                        <td><span class="checkbox">{{ $status === 'correcto' ? 'X' : '' }}</span></td>
                        <td><span class="checkbox">{{ $status === 'na' ? 'X' : '' }}</span></td>
                        <td><span class="checkbox">{{ $status === 'incorrecto' ? 'X' : '' }}</span></td>
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

    <div class="footer" style="position: fixed; bottom: 10px; left: 15px; right: 15px; text-align: center; font-size: 8px; color: #666; border-top: 1px solid #ccc; padding-top: 5px;">
        EXL Automotive S.C. - Documento generado automáticamente el {{ now()->format('d/m/Y H:i') }}
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