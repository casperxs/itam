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
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .document-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 5px;
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
        .conditions-section {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .conditions-left,
        .conditions-right {
            display: table-cell;
            width: 48%;
            vertical-align: top;
            padding: 5px;
            border: 1px solid #333;
        }
        .conditions-right {
            margin-left: 4%;
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
    <div class="header">
        <div class="company-name">SISTEMA DE GESTIÓN DE ACTIVOS TI</div>
        <div class="document-title">CHECKLIST DE MANTENIMIENTO</div>
        <div style="margin-top: 5px; font-size: 10px;">
            ID Mantenimiento: {{ $maintenance->id }} | Fecha: {{ now()->format('d/m/Y H:i') }}
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
        <div class="section-title">CONDICIONES DEL EQUIPO</div>
        <div class="conditions-section">
            <div class="conditions-left">
                <strong>Condiciones Externas:</strong><br>
                <span class="checkbox"></span> Excelente
                <span class="checkbox"></span> Bueno
                <span class="checkbox"></span> Regular
                <span class="checkbox"></span> Malo<br><br>
                Detalles: _________________________<br>
                _________________________________
            </div>
            <div class="conditions-right">
                <strong>Funcionamiento:</strong><br>
                <span class="checkbox"></span> Excelente
                <span class="checkbox"></span> Bueno
                <span class="checkbox"></span> Regular
                <span class="checkbox"></span> Malo<br><br>
                Detalles: _________________________<br>
                _________________________________
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">OBSERVACIONES DEL INGENIERO</div>
        <div class="observations">
            {{ $maintenance->notes ?? '' }}
            <br><br><br>
        </div>
    </div>

    <div class="section">
        <div class="section-title">ACTIVIDADES REALIZADAS</div>
        <table class="checklist-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Actividad</th>
                    <th style="width: 15%;">Correcto</th>
                    <th style="width: 15%;">Incorrecto</th>
                    <th style="width: 30%;">Detalles</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-left">1. Temporales</td>
                    <td><span class="checkbox"></span></td>
                    <td><span class="checkbox"></span></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-left">2. Historial y Cookies</td>
                    <td><span class="checkbox"></span></td>
                    <td><span class="checkbox"></span></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-left">3. Contraseñas</td>
                    <td><span class="checkbox"></span></td>
                    <td><span class="checkbox"></span></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-left">4. Actualizaciones</td>
                    <td><span class="checkbox"></span></td>
                    <td><span class="checkbox"></span></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-left">5. Formateo</td>
                    <td><span class="checkbox"></span></td>
                    <td><span class="checkbox"></span></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-left">6. Respaldo</td>
                    <td><span class="checkbox"></span></td>
                    <td><span class="checkbox"></span></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-left">7. Restauración de Información</td>
                    <td><span class="checkbox"></span></td>
                    <td><span class="checkbox"></span></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-left">8. Limpieza</td>
                    <td><span class="checkbox"></span></td>
                    <td><span class="checkbox"></span></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-left">9. Idioma del SO</td>
                    <td><span class="checkbox"></span></td>
                    <td><span class="checkbox"></span></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-left">10. Idioma de Navegador(es)</td>
                    <td><span class="checkbox"></span></td>
                    <td><span class="checkbox"></span></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">SOFTWARE Y CARPETAS</div>
        <table class="checklist-table">
            <thead>
                <tr>
                    <th style="width: 10%;">No.</th>
                    <th style="width: 50%;">Carpeta / Software</th>
                    <th style="width: 15%;">Correcto</th>
                    <th style="width: 15%;">Incorrecto</th>
                    <th style="width: 10%;">Detalles</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td></td>
                    <td><span class="checkbox"></span></td>
                    <td><span class="checkbox"></span></td>
                    <td></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td></td>
                    <td><span class="checkbox"></span></td>
                    <td><span class="checkbox"></span></td>
                    <td></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td></td>
                    <td><span class="checkbox"></span></td>
                    <td><span class="checkbox"></span></td>
                    <td></td>
                </tr>
                <tr>
                    <td>4</td>
                    <td></td>
                    <td><span class="checkbox"></span></td>
                    <td><span class="checkbox"></span></td>
                    <td></td>
                </tr>
                <tr>
                    <td>5</td>
                    <td></td>
                    <td><span class="checkbox"></span></td>
                    <td><span class="checkbox"></span></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
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