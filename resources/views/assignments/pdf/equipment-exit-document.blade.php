<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documento de Salida de Equipo de Cómputo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            line-height: 1.2;
            color: #333;
            margin: 0;
            padding: 10px;
            height: 100vh;
            box-sizing: border-box;
        }

        /* ENCABEZADO ESTILO FACTURA */
        .invoice-header {
            border: 2px solid #333;
            padding: 10px;
            margin-bottom: 15px;
        }
        .company-info {
            text-align: center;
            border-bottom: 1px solid #333;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .document-title {
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }

        /* RECUADRO TIPO DE DOCUMENTO */
        .document-type-section {
            border: 1px solid #333;
            padding: 8px 10px;
            margin-bottom: 15px;
            text-align: center;
        }
        .document-type-inline {
            display: inline-flex;
            align-items: center;
            gap: 20px;
            font-size: 11px;
            font-weight: bold;
        }
        .checkbox-item {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .checkbox {
            width: 12px;
            height: 12px;
            border: 2px solid #333;
            display: inline-block;
        }

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

        /* SECCIÓN DE EQUIPOS COMO PARTIDAS */
        .equipment-section {
            margin-bottom: 20px;
        }
        .section-header {
            background-color: #f0f0f0;
            border: 1px solid #333;
            padding: 8px;
            font-weight: bold;
            font-size: 12px;
            text-align: center;
            margin-bottom: 0;
        }
        .equipment-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #333;
        }
        .equipment-row {
            border-bottom: 1px solid #333;
        }
        .equipment-item {
            padding: 10px;
            vertical-align: top;
            width: 100%;
        }
        .item-header {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 5px;
            color: #333;
        }
        .item-detail {
            margin-bottom: 3px;
            font-size: 9px;
        }
        .item-specs {
            font-style: italic;
            color: #666;
            margin-top: 5px;
            font-size: 8px;
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
        .val-100 { background-color: #4CAF50; }
        .val-90 { background-color: #8BC34A; }
        .val-80 { background-color: #CDDC39; color: #333; }
        .val-70 { background-color: #FF9800; }
        .val-60 { background-color: #F44336; }

        /* PAGARÉ OPTIMIZADO */
        .promissory-note {
            background-color: #fafafa;
            border: 2px solid #333;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 8px;
        }
        .promissory-title {
            font-weight: bold;
            text-align: center;
            font-size: 10px;
            margin-bottom: 8px;
            text-decoration: underline;
        }
        .promissory-content {
            text-align: justify;
            line-height: 1.3;
            margin-bottom: 10px;
        }
        .promissory-blanks {
            display: inline-block;
            border-bottom: 1px solid #333;
            min-width: 150px;
            height: 16px;
        }
        .promissory-blanks-short {
            display: inline-block;
            border-bottom: 1px solid #333;
            min-width: 80px;
            height: 16px;
        }
        .promissory-blanks-medium {
            display: inline-block;
            border-bottom: 1px solid #333;
            min-width: 120px;
            height: 16px;
        }

        /* SECCIÓN DE FIRMAS EN 4 COLUMNAS */
        .signatures-section {
            margin-top: 15px;
            border: 1px solid #333;
            padding: 10px;
        }
        .signatures-grid {
            display: table;
            width: 100%;
            table-layout: fixed;
        }
        .signatures-row {
            display: table-row;
        }
        .signature-column {
            display: table-cell;
            width: 23%;
            border: 1px solid #333;
            padding: 10px;
            vertical-align: top;
        }
        .signature-spacer {
            display: table-cell;
            width: 2%;
        }
        .signature-title {
            font-weight: bold;
            font-size: 8px;
            text-align: center;
            background-color: #f0f0f0;
            padding: 3px;
            margin: -10px -10px 8px -10px;
        }
        .signature-content {
            text-align: center;
            font-size: 7px;
            line-height: 1.2;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 15px;
            padding-top: 2px;
            text-align: center;
            font-size: 7px;
        }

        .footer {
            position: fixed;
            bottom: 10px;
            left: 15px;
            right: 15px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }

        @media print {
            body { margin: 0; height: 100vh; }
            .footer { position: fixed; }
        }
    </style>
</head>
<body>
    <!-- ENCABEZADO ESTILO FACTURA -->
    <div class="invoice-header">
        <div class="company-info">
            <div class="company-name">EXL AUTOMOTIVE S.C.</div>
            <div class="document-title">DOCUMENTO DE SALIDA DE EQUIPO DE CÓMPUTO</div>
        </div>

        <!-- RECUADRO TIPO DE DOCUMENTO -->
        <div class="document-type-section">
            <div class="document-type-inline">
                <span>TIPO DE SALIDA:</span>
                <div class="checkbox-item">
                    <span class="checkbox"></span>
                    <span>SEMESTRAL</span>
                </div>
                <div class="checkbox-item">
                    <span class="checkbox"></span>
                    <span>TEMPORAL</span>
                </div>
            </div>
        </div>

        <div class="user-details">
            <div class="user-details-left">
                <div class="detail-row">
                    <span class="detail-label">Usuario Receptor:</span>
                    <span class="detail-value">{{ $itUser->name ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">No. Empleado:</span>
                    <span class="detail-value">{{ $itUser->employee_id ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Departamento:</span>
                    <span class="detail-value">{{ $itUser->department ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="user-details-right">
                <div class="detail-row">
                    <span class="detail-label">Fecha de Salida:</span>
                    <span class="detail-value">{{ now()->format('d/m/Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Autorizado por:</span>
                    <span class="detail-value">{{ $assignedBy->name ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Total de Equipos:</span>
                    <span class="detail-value">{{ $assignments->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN DE EQUIPOS COMO PARTIDAS DE FACTURA -->
    <div class="equipment-section">
        <div class="section-header">EQUIPO(S) QUE SALE(N) DE LAS INSTALACIONES</div>

        <table class="equipment-table">
            @forelse($assignments as $assignment)
            <tr class="equipment-row">
                <td class="equipment-item">
                    <div class="item-header">
                        {{ $assignment->equipment->equipmentType->name ?? 'N/A' }} #{{ $loop->iteration }}
                    </div>

                    <div class="item-detail">
                        <strong>Marca/Modelo:</strong> {{ $assignment->equipment->brand ?? 'N/A' }} {{ $assignment->equipment->model ?? 'N/A' }}
                    </div>

                    <div class="item-detail">
                        <strong>Serie:</strong> {{ $assignment->equipment->serial_number ?? 'N/A' }}
                        @if($assignment->equipment->asset_tag)
                            | <strong>Tag:</strong> {{ $assignment->equipment->asset_tag }}
                        @endif
                        @if($assignment->equipment->invoice_number)
                            | <strong>Factura:</strong> {{ $assignment->equipment->invoice_number }}
                        @endif
                    </div>

                    @if($assignment->equipment->specifications)
                    <div class="item-specs">
                        <strong>Especificaciones:</strong> {{ $assignment->equipment->specifications }}
                    </div>
                    @endif

                    @if($assignment->equipment->valoracion)
                    <div class="item-detail">
                        <strong>Valoración:</strong>
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
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td style="text-align: center; padding: 20px;">
                    No hay equipos asignados.
                </td>
            </tr>
            @endforelse
        </table>
    </div>

    <!-- PAGARÉ MOVIDO AQUÍ -->
    <div class="promissory-note">
        <div class="promissory-title">PAGARÉ POR SALIDA DE EQUIPO DE CÓMPUTO</div>
        <div class="promissory-content">
            A través de este pagaré, yo <span class="promissory-blanks"></span> quedo consciente que los equipos arriba mencionados son propiedad de EXL Automotive S.C. y me es conferido en calidad de préstamo para el desarrollo de mis actividades laborales fuera de las instalaciones de EXL Automotive S.C. a partir del día <span class="promissory-blanks-short"></span> del mes <span class="promissory-blanks-medium"></span> del año <span class="promissory-blanks-short"></span>, así mismo, acepto que el(los) equipo(s) son completamente mi responsabilidad y deberé regresarlo el día <span class="promissory-blanks-short"></span> del mes <span class="promissory-blanks-medium"></span> del año <span class="promissory-blanks-short"></span>. Yo responderé por cualquier daño de cualquier índole que le ocurra al(os) equipo(s) de cómputo mientras este(n) fuera de las instalaciones de EXL Automotive S.C. En caso de que el(los) equipo(s) no regrese a las instalaciones de EXL Automotive S.C. por cualquier motivo, cubriré el valor del(os) equipo(s) incondicionalmente.
        </div>
    </div>

    <!-- SECCIÓN DE FIRMAS EN 4 COLUMNAS -->
    <div class="signatures-section">
        <div class="signatures-grid">
            <div class="signatures-row">
                <div class="signature-column">
                    <div class="signature-title">USUARIO RECEPTOR</div>
                    <div class="signature-content">
                        <strong>{{ $itUser->name ?? 'N/A' }}</strong><br>
                        {{ $itUser->employee_id ?? 'N/A' }}<br>
                        {{ $itUser->department ?? 'N/A' }}
                        <div class="signature-line">
                            Firma: ______________
                        </div>
                    </div>
                </div>
                <div class="signature-spacer"></div>
                <div class="signature-column">
                    <div class="signature-title">JEFE INMEDIATO</div>
                    <div class="signature-content">
                        <strong>Nombre y Firma del Jefe Inmediato</strong><br>
                        <br>
                        <div class="signature-line">
                            Firma: ______________
                        </div>
                    </div>
                </div>
                <div class="signature-spacer"></div>
                <div class="signature-column">
                    <div class="signature-title">ADMINISTRADOR</div>
                    <div class="signature-content">
                        <strong>{{ $assignedBy->name ?? 'N/A' }}</strong><br>
                        Administrador/Técnico<br>
                        EXL Automotive S.C.
                        <div class="signature-line">
                            Firma: ______________
                        </div>
                    </div>
                </div>
                <div class="signature-spacer"></div>
                <div class="signature-column">
                    <div class="signature-title">INGENIERO RECEPTOR</div>
                    <div class="signature-content">
                        <strong>Ingeniero que Recibe</strong><br>
                        <br>
                        <div class="signature-line">
                            Firma: ______________
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        EXL Automotive S.C. - Documento generado automáticamente el {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
