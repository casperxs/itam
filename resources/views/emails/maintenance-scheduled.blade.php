<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantenimiento Programado</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header .icon {
            font-size: 40px;
            margin-bottom: 10px;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .maintenance-info {
            background-color: #f8f9ff;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .maintenance-info h3 {
            color: #667eea;
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 20px;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
            align-items: center;
        }
        .info-label {
            font-weight: 600;
            color: #34495e;
            min-width: 120px;
            margin-right: 10px;
        }
        .info-value {
            color: #2c3e50;
        }
        .equipment-info {
            background-color: #f0f8ff;
            border-left: 4px solid #3498db;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .equipment-info h3 {
            color: #3498db;
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 20px;
        }
        .technician-info {
            background-color: #f0fff0;
            border-left: 4px solid #27ae60;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .technician-info h3 {
            color: #27ae60;
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 20px;
        }
        .important-notice {
            background-color: #fef5e7;
            border-left: 4px solid #f39c12;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .important-notice h4 {
            color: #d68910;
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 18px;
        }
        .important-notice p {
            color: #7d6608;
            margin: 0;
        }
        .attachment-info {
            background-color: #f5f5f5;
            border: 2px dashed #bdc3c7;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            text-align: center;
        }
        .attachment-info h4 {
            color: #34495e;
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .attachment-info ul {
            text-align: left;
            display: inline-block;
            color: #7f8c8d;
        }
        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .footer p {
            margin: 5px 0;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-scheduled {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-in-progress {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .type-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }
        .type-preventive {
            background-color: #d4edda;
            color: #155724;
        }
        .type-corrective {
            background-color: #f8d7da;
            color: #721c24;
        }
        .type-update {
            background-color: #cce7ff;
            color: #004085;
        }
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            .content {
                padding: 20px 15px;
            }
            .info-row {
                flex-direction: column;
                align-items: flex-start;
            }
            .info-label {
                min-width: auto;
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="icon">🔧</div>
            <h1>Mantenimiento Programado</h1>
            <p>Sistema ITAM - BKB</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Estimado/a <strong>{{ $maintenance->equipment->currentAssignment->itUser->name ?? 'Usuario' }}</strong>,
            </div>

            <p>Se ha programado un mantenimiento para su equipo de cómputo. A continuación encontrará todos los detalles:</p>

            <!-- Maintenance Information -->
            <div class="maintenance-info">
                <h3>📋 Detalles del Mantenimiento</h3>
                <div class="info-row">
                    <div class="info-label">ID Mantenimiento:</div>
                    <div class="info-value"><strong>#{{ $maintenance->id }}</strong></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Fecha y Hora:</div>
                    <div class="info-value"><strong>{{ $maintenance->scheduled_date->format('d/m/Y H:i') }}</strong></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tipo:</div>
                    <div class="info-value">
                        <span class="type-badge type-{{ $maintenance->type }}">
                            @switch($maintenance->type)
                                @case('preventive') Preventivo @break
                                @case('corrective') Correctivo @break
                                @case('update') Actualización @break
                                @default Mantenimiento @break
                            @endswitch
                        </span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Estado:</div>
                    <div class="info-value">
                        <span class="status-badge status-{{ $maintenance->status }}">
                            @switch($maintenance->status)
                                @case('scheduled') Programado @break
                                @case('in_progress') En Progreso @break
                                @case('completed') Completado @break
                                @case('cancelled') Cancelado @break
                                @default {{ $maintenance->status }} @break
                            @endswitch
                        </span>
                    </div>
                </div>
                @if($maintenance->end_date)
                    <div class="info-row">
                        <div class="info-label">Duración estimada:</div>
                        <div class="info-value">
                            {{ \Carbon\Carbon::parse($maintenance->scheduled_date)->diffForHumans(\Carbon\Carbon::parse($maintenance->end_date), true) }}
                        </div>
                    </div>
                @else
                    <div class="info-row">
                        <div class="info-label">Duración estimada:</div>
                        <div class="info-value">1 hora</div>
                    </div>
                @endif
                @if($maintenance->description)
                    <div class="info-row">
                        <div class="info-label">Descripción:</div>
                        <div class="info-value">{{ $maintenance->description }}</div>
                    </div>
                @endif
            </div>

            <!-- Equipment Information -->
            <div class="equipment-info">
                <h3>💻 Información del Equipo</h3>
                <div class="info-row">
                    <div class="info-label">Tipo:</div>
                    <div class="info-value">{{ $maintenance->equipment->equipmentType->name ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Marca/Modelo:</div>
                    <div class="info-value">{{ $maintenance->equipment->brand ?? 'N/A' }} {{ $maintenance->equipment->model ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Número de Serie:</div>
                    <div class="info-value">{{ $maintenance->equipment->serial_number ?? 'N/A' }}</div>
                </div>
                @if($maintenance->equipment->asset_tag)
                    <div class="info-row">
                        <div class="info-label">Tag:</div>
                        <div class="info-value">{{ $maintenance->equipment->asset_tag }}</div>
                    </div>
                @endif
            </div>

            <!-- Technician Information -->
            <div class="technician-info">
                <h3>🔧 Técnico Asignado</h3>
                <div class="info-row">
                    <div class="info-label">Nombre:</div>
                    <div class="info-value">{{ $maintenance->performedBy->name ?? 'Por asignar' }}</div>
                </div>
                @if($maintenance->performedBy && $maintenance->performedBy->email)
                    <div class="info-row">
                        <div class="info-label">Email:</div>
                        <div class="info-value">{{ $maintenance->performedBy->email }}</div>
                    </div>
                @endif
            </div>

            @if($maintenance->cost)
                <div class="info-row">
                    <div class="info-label"><strong>💰 Costo Estimado:</strong></div>
                    <div class="info-value"><strong>${{ number_format($maintenance->cost, 2) }}</strong></div>
                </div>
            @endif

            @if($maintenance->notes)
                <div class="maintenance-info">
                    <h3>📝 Notas Adicionales</h3>
                    <p>{{ $maintenance->notes }}</p>
                </div>
            @endif

            <!-- Attachment Information -->
            <div class="attachment-info">
                <h4>📎 Archivo de Calendario Adjunto</h4>
                <p>Se incluye un archivo de calendario (.ics) que puede:</p>
                <ul>
                    <li>Abrir directamente para agregar el evento a su calendario</li>
                    <li>Importar a Outlook, Gmail, Apple Calendar, etc.</li>
                    <li>El archivo incluye recordatorios automáticos</li>
                </ul>
            </div>

            <!-- Important Notice -->
            <div class="important-notice">
                <h4>⚠️ Importante</h4>
                <p>Por favor, asegúrese de tener su equipo disponible en la fecha y hora programada. Si tiene alguna pregunta o necesita reprogramar, contacte al departamento de soporte IT.</p>
            </div>

        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Departamento de Soporte IT - BKB</strong></p>
            <p>📧 soporteit@bkb.mx</p>
            <p><small>Este mensaje fue generado automáticamente por el Sistema ITAM</small></p>
        </div>
    </div>
</body>
</html>
