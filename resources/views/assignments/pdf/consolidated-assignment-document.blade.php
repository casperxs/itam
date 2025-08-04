<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documento Consolidado de Asignación de Equipos</title>
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
        .header {
            text-align: center;
            margin-bottom: 12px;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
        }
        .company-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .document-title {
            font-size: 12px;
            font-weight: bold;
            margin-top: 5px;
        }
        .header-info {
            display: table;
            width: 100%;
            margin-top: 8px;
            font-size: 8px;
        }
        .header-left, .header-right {
            display: table-cell;
            width: 33.33%;
            vertical-align: top;
        }
        .header-center {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            vertical-align: top;
        }
        .header-right {
            text-align: right;
        }
        .main-content {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .left-column {
            display: table-cell;
            width: 48%;
            vertical-align: top;
            padding-right: 10px;
        }
        .right-column {
            display: table-cell;
            width: 48%;
            vertical-align: top;
            padding-left: 10px;
        }
        .divider {
            display: table-cell;
            width: 4%;
            border-left: 1px solid #ddd;
        }
        .section-title {
            font-size: 10px;
            font-weight: bold;
            background-color: #f5f5f5;
            padding: 4px;
            border-left: 3px solid #333;
            margin-bottom: 6px;
        }
        .user-info {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        .user-row {
            display: table-row;
        }
        .user-label {
            display: table-cell;
            font-weight: bold;
            width: 35%;
            padding: 1px 4px 1px 0;
            vertical-align: top;
            font-size: 8px;
        }
        .user-value {
            display: table-cell;
            padding: 1px 0;
            border-bottom: 1px dotted #ccc;
            vertical-align: top;
            font-size: 8px;
        }
        .equipment-compact {
            margin-bottom: 6px;
            padding: 4px;
            border: 1px solid #ddd;
            font-size: 8px;
        }
        .equipment-line {
            margin-bottom: 2px;
        }
        .equipment-header {
            font-weight: bold;
            font-size: 9px;
            margin-bottom: 3px;
        }
        .valuation-bar {
            height: 12px;
            background-color: #e0e0e0;
            border-radius: 6px;
            position: relative;
            overflow: hidden;
            margin: 2px 0;
        }
        .valuation-fill {
            height: 100%;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 7px;
        }
        .val-100 { background-color: #4CAF50; }
        .val-90 { background-color: #8BC34A; }
        .val-80 { background-color: #CDDC39; }
        .val-70 { background-color: #FF9800; }
        .val-60 { background-color: #F44336; }
        .signature-section {
            margin-top: 10px;
        }
        .signature-boxes {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        .signature-box {
            display: table-cell;
            width: 48%;
            border: 1px solid #333;
            padding: 6px;
            vertical-align: top;
        }
        .signature-spacer {
            display: table-cell;
            width: 4%;
        }
        .signature-header {
            font-weight: bold;
            font-size: 9px;
            margin-bottom: 4px;
            text-align: center;
            background-color: #f0f0f0;
            padding: 3px;
            margin: -6px -6px 4px -6px;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 15px;
            padding-top: 3px;
            text-align: center;
            font-size: 8px;
        }
        .promissory-note {
            background-color: white;
            border: 1px solid #333;
            padding: 8px;
            margin: 8px 0;
            font-size: 8px;
        }
        .promissory-title {
            font-weight: bold;
            text-align: center;
            font-size: 9px;
            margin-bottom: 6px;
            text-decoration: underline;
        }
        .promissory-content {
            text-align: justify;
            line-height: 1.3;
        }
        .equipment-signatures {
            display: table;
            width: 100%;
            margin-top: 8px;
        }
        .equipment-signature {
            display: table-cell;
            width: 48%;
            border: 1px solid #333;
            padding: 4px;
            margin-bottom: 4px;
            text-align: center;
            font-size: 7px;
        }
        .equipment-signature:nth-child(odd) {
            margin-right: 2%;
        }
        .footer {
            position: fixed;
            bottom: 8px;
            left: 10px;
            right: 10px;
            text-align: center;
            font-size: 7px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 4px;
        }
        @media print {
            body { margin: 0; height: 100vh; }
            .footer { position: fixed; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">EXL AUTOMOTIVE S.C.</div>
        <div class="document-title">DOCUMENTO CONSOLIDADO DE ASIGNACIÓN DE EQUIPOS</div>
        
        <div class="header-info">
            <div class="header-left">
                <strong>No. Empleado:</strong> {{ $itUser->employee_id ?? 'N/A' }}<br>
                <strong>Departamento:</strong> {{ $itUser->department ?? 'N/A' }}
            </div>
            <div class="header-center">
                <strong>Fecha de Asignación:</strong> {{ now()->format('d/m/Y') }}
            </div>
            <div class="header-right">
                <strong>Usuario Receptor:</strong> {{ $itUser->name ?? 'N/A' }}
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="left-column">
            <div class="section-title">EQUIPOS ASIGNADOS</div>
            @forelse($assignments as $assignment)
            <div class="equipment-compact">
                <div class="equipment-header">{{ $assignment->equipment->equipmentType->name ?? 'N/A' }} #{{ $loop->iteration }}</div>
                <div class="equipment-line"><strong>Marca/Modelo:</strong> {{ $assignment->equipment->brand ?? 'N/A' }} {{ $assignment->equipment->model ?? 'N/A' }}</div>
                <div class="equipment-line"><strong>Serie:</strong> {{ $assignment->equipment->serial_number ?? 'N/A' }}
                @if($assignment->equipment->asset_tag) | <strong>Tag:</strong> {{ $assignment->equipment->asset_tag }}@endif
                @if($assignment->equipment->invoice_number) | <strong>Factura:</strong> {{ $assignment->equipment->invoice_number }}@endif</div>
                @if($assignment->equipment->specifications)
                <div class="equipment-line"><strong>Especificaciones:</strong> {{ $assignment->equipment->specifications }}</div>
                @endif
                @if($assignment->equipment->valoracion)
                <div class="equipment-line">
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
            </div>
            @empty
            <p>No hay equipos asignados.</p>
            @endforelse
        </div>

        <div class="divider"></div>

        <div class="right-column">
            <div class="signature-section">
                <div class="signature-boxes">
                    <div class="signature-box">
                        <div class="signature-header">USUARIO RECEPTOR</div>
                        <div class="signature-line">
                            <strong>{{ $itUser->name ?? 'N/A' }}</strong><br>
                            Firma: ________________________<br>
                            Fecha: ________________________
                        </div>
                    </div>
                    <div class="signature-spacer"></div>
                    <div class="signature-box">
                        <div class="signature-header">DEPARTAMENTO DE TI</div>
                        <div class="signature-line">
                            <strong>{{ $assignedBy->name ?? 'N/A' }}</strong><br>
                            Firma: ________________________<br>
                            Fecha: ________________________
                        </div>
                    </div>
                </div>

                <div class="promissory-note">
                    <div class="promissory-title">PAGARÉ POR EQUIPOS ASIGNADOS</div>
                    <div class="promissory-content">
                        A través de este pagaré, yo: <strong>{{ $itUser->name ?? 'N/A' }}</strong> quedo consciente que el(los) equipo(s) arriba descritos son propiedad de EXL Automotive S.C. y me es conferido en calidad de préstamo para el desarrollo de mis actividades laborales de EXL Automotive S.C. a partir de la fecha de este documento, así mismo, acepto que el(los) equipo(s) antes mencionado(s) es(son) completamente mi responsabilidad y deberé regresarlo(s) para revocación de este documento o antes si mi relación laboral con la empresa se redime o se me es solicitado por cualquier motivo. Yo responderé por daños de cualquier índole que le ocurra(n) a este(os) equipo(s) mientras este(n) a mi resguardo. En caso de pérdida o extravío del(os) equipo(s) responderé por el(los) mismo(s) de acuerdo con la valoración descrita en este documento.
                    </div>
                    
                    <div class="signature-line" style="margin-top: 12px;">
                        <strong>{{ $itUser->name ?? 'N/A' }}</strong><br>
                        Firma: ________________________<br>
                        Fecha: ________________________
                    </div>
                </div>

                <!-- Firmas por cada equipo -->
                @if($assignments && $assignments->count() > 0)
                <div style="margin-top: 8px; font-size: 8px;">
                    <strong>FIRMAS POR EQUIPO:</strong>
                    @foreach($assignments as $assignment)
                    <div style="border: 1px solid #333; padding: 3px; margin: 2px 0; text-align: center;">
                        <div style="font-weight: bold; font-size: 7px;">{{ $assignment->equipment->equipmentType->name ?? 'N/A' }} - {{ $assignment->equipment->serial_number ?? 'N/A' }}</div>
                        <div style="margin-top: 8px; border-top: 1px solid #333; padding-top: 2px; font-size: 6px;">
                            Firma: _________________ Fecha: _________
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="footer">
        EXL Automotive S.C. - Documento generado automáticamente el {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>