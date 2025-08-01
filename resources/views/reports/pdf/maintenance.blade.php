<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Mantenimientos - {{ date('d/m/Y') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .filters {
            background: #f8f9fa;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .filters h3 {
            margin-top: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .status-scheduled { color: #007bff; }
        .status-in_progress { color: #ffc107; }
        .status-completed { color: #28a745; }
        .status-cancelled { color: #dc3545; }
        .type-preventive { background-color: #e7f3ff; }
        .type-corrective { background-color: #fff2e7; }
        .type-update { background-color: #f0e7ff; }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Mantenimientos</h1>
        <p>Generado el {{ date('d/m/Y H:i:s') }}</p>
        <p>Sistema ITAM - Gestión de Activos de TI</p>
    </div>

    @if(!empty($filters))
    <div class="filters">
        <h3>Filtros Aplicados:</h3>
        @if(isset($filters['date_from']))
            <p><strong>Desde:</strong> {{ \Carbon\Carbon::parse($filters['date_from'])->format('d/m/Y') }}</p>
        @endif
        @if(isset($filters['date_to']))
            <p><strong>Hasta:</strong> {{ \Carbon\Carbon::parse($filters['date_to'])->format('d/m/Y') }}</p>
        @endif
        @if(isset($filters['type']))
            <p><strong>Tipo:</strong> {{ ucfirst($filters['type']) }}</p>
        @endif
        @if(isset($filters['status']))
            <p><strong>Estado:</strong> {{ ucfirst(str_replace('_', ' ', $filters['status'])) }}</p>
        @endif
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Equipo</th>
                <th>Tipo</th>
                <th>Estado</th>
                <th>F. Programada</th>
                <th>F. Completado</th>
                <th>Técnico</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($maintenanceRecords as $maintenance)
            <tr class="type-{{ $maintenance->type }}">
                <td>{{ $maintenance->equipment->equipmentType->name }}<br>
                    <small>{{ $maintenance->equipment->serial_number }}</small>
                </td>
                <td>{{ ucfirst($maintenance->type) }}</td>
                <td class="status-{{ $maintenance->status }}">
                    {{ ucfirst(str_replace('_', ' ', $maintenance->status)) }}
                </td>
                <td>{{ $maintenance->scheduled_date ? $maintenance->scheduled_date->format('d/m/Y') : 'N/A' }}</td>
                <td>{{ $maintenance->completed_at ? $maintenance->completed_at->format('d/m/Y') : 'N/A' }}</td>
                <td>{{ $maintenance->performedBy->name ?? 'No asignado' }}</td>
                <td>{{ $maintenance->notes ? Str::limit($maintenance->notes, 50) : 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Página {PAGE_NUM} de {PAGE_COUNT} - Reporte generado automáticamente por Sistema ITAM</p>
    </div>
</body>
</html>