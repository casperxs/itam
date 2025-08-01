<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Asignaciones - {{ date('d/m/Y') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
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
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .status-active { color: #28a745; }
        .status-returned { color: #6c757d; }
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
        <h1>Reporte de Asignaciones</h1>
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
        @if(isset($filters['status']))
            <p><strong>Estado:</strong> {{ $filters['status'] === 'active' ? 'Activas' : 'Retornadas' }}</p>
        @endif
        @if(isset($filters['department']))
            <p><strong>Departamento:</strong> {{ $filters['department'] }}</p>
        @endif
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Equipo</th>
                <th>Usuario</th>
                <th>Departamento</th>
                <th>F. Asignación</th>
                <th>F. Retorno</th>
                <th>Estado</th>
                <th>Días Asignado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assignments as $assignment)
            <tr>
                <td>{{ $assignment->equipment->equipmentType->name }} - {{ $assignment->equipment->serial_number }}</td>
                <td>{{ $assignment->itUser->full_name }}</td>
                <td>{{ $assignment->itUser->department }}</td>
                <td>{{ $assignment->assigned_at ? $assignment->assigned_at->format('d/m/Y') : 'N/A' }}</td>
                <td>{{ $assignment->returned_at ? $assignment->returned_at->format('d/m/Y') : 'N/A' }}</td>
                <td class="status-{{ $assignment->returned_at ? 'returned' : 'active' }}">
                    {{ $assignment->returned_at ? 'Retornada' : 'Activa' }}
                </td>
                <td>
                    @if($assignment->returned_at)
                        {{ $assignment->assigned_at ? $assignment->assigned_at->diffInDays($assignment->returned_at) : 'N/A' }}
                    @else
                        {{ $assignment->assigned_at ? $assignment->assigned_at->diffInDays(now()) : 'N/A' }}
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Página {PAGE_NUM} de {PAGE_COUNT} - Reporte generado automáticamente por Sistema ITAM</p>
    </div>
</body>
</html>