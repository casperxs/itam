<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consolidado de Asignación de Equipos</title>
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
        
        /* ENCABEZADO CON IMAGEN DE FONDO DETRÁS DE TODO */
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
        
        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }
        
        .document-title {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin-bottom: 0;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
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
        .val-excelente { background-color: #4CAF50; }
        .val-optimo { background-color: #8BC34A; }
        .val-bueno { background-color: #CDDC39; color: #333; }
        .val-regular { background-color: #FF9800; }
        .val-malo { background-color: #F44336; }
        
        /* SECCIÓN FINAL DE FIRMAS */
        .final-signatures {
            margin-top: 20px;
            border: 1px solid #333;
            padding: 15px;
        }
        .signatures-row {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .signature-column {
            display: table-cell;
            width: 48%;
            border: 1px solid #333;
            padding: 15px;
            vertical-align: top;
        }
        .signature-spacer {
            display: table-cell;
            width: 4%;
        }
        .signature-title {
            font-weight: bold;
            font-size: 11px;
            text-align: center;
            background-color: #f0f0f0;
            padding: 5px;
            margin: -15px -15px 10px -15px;
        }
        .signature-content {
            text-align: center;
            font-size: 10px;
        }
        .signature-line-final {
            border-top: 1px solid #333;
            margin-top: 30px;
            padding-top: 5px;
            text-align: center;
            font-size: 9px;
        }
        
        /* PAGARÉ */
        .promissory-note {
            background-color: #fafafa;
            border: 2px solid #333;
            padding: 15px;
            margin-top: 15px;
            font-size: 9px;
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
            margin-bottom: 15px;
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
        <div class="header-codes">
            PRO-73-A<br>
            Rev. 02
        </div>
        
        <div class="document-title" style="text-align: right; margin-top: 10px; margin-bottom: 20px;">
            CONSOLIDADO DE ASIGNACIÓN DE EQUIPOS
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
                    <span class="detail-label">Fecha de Asignación:</span>
                    <span class="detail-value">{{ now()->format('d/m/Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Asignado por:</span>
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
        <div class="section-header">EQUIPOS ASIGNADOS</div>
        
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
                            $class = '';
                            $percentage = 0;
                            
                            // Nueva lógica de valoración corregida
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
                                // Valor por defecto si no coincide con ninguno
                                $class = 'val-regular';
                                $percentage = 70;
                            }
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

    <!-- SECCIÓN FINAL DE FIRMAS -->
    <div class="final-signatures">
        <div class="signatures-row">
            <div class="signature-column">
                <div class="signature-title">USUARIO RECEPTOR</div>
                <div class="signature-content">
                    <strong>{{ $itUser->name ?? 'N/A' }}</strong><br>
                    {{ $itUser->employee_id ?? 'N/A' }}<br>
                    {{ $itUser->department ?? 'N/A' }}
                    <div class="signature-line-final">
                        Firma: ________________________
                    </div>
                </div>
            </div>
            <div class="signature-spacer"></div>
            <div class="signature-column">
                <div class="signature-title">DEPARTAMENTO DE TI</div>
                <div class="signature-content">
                    <strong>{{ $assignedBy->name ?? 'N/A' }}</strong><br>
                    Administrador/Técnico<br>
                    EXL Automotive S.C.
                    <div class="signature-line-final">
                        Firma: ________________________
                    </div>
                </div>
            </div>
        </div>

        <!-- PAGARÉ -->
        <div class="promissory-note">
            <div class="promissory-title">PAGARÉ POR EQUIPOS ASIGNADOS</div>
            <div class="promissory-content">
                A través de este pagaré, yo: <strong>{{ $itUser->name ?? 'N/A' }}</strong> quedo consciente que el(los) equipo(s) arriba descritos son propiedad de EXL Automotive S.C. y me es conferido en calidad de préstamo para el desarrollo de mis actividades laborales de EXL Automotive S.C. a partir de la fecha de este documento, así mismo, acepto que el(los) equipo(s) antes mencionado(s) es(son) completamente mi responsabilidad y deberé regresarlo(s) para revocación de este documento o antes si mi relación laboral con la empresa se redime o se me es solicitado por cualquier motivo. Yo responderé por daños de cualquier índole que le ocurra(n) a este(os) equipo(s) mientras este(n) a mi resguardo. En caso de pérdida o extravío del(os) equipo(s) responderé por el(los) mismo(s) de acuerdo con la valoración descrita en este documento.
            </div>
            
            <div class="signature-content">
                <strong>{{ $itUser->name ?? 'N/A' }}</strong>
                <div class="signature-line-final">
                    Firma: ________________________<br>
                    Fecha: ________________________
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        EXL Automotive S.C. - Documento generado automáticamente el {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>