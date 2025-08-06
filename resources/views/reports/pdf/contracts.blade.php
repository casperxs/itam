<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Contratos - {{ date('d/m/Y') }}</title>
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
        .status-active { color: #28a745; background-color: #d4edda; }
        .status-expired { color: #dc3545; background-color: #f8d7da; }
        .status-cancelled { color: #6c757d; background-color: #e9ecef; }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .value {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Contratos</h1>
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
            <p><strong>Estado:</strong> {{ ucfirst($filters['status']) }}</p>
        @endif
        @if(isset($filters['supplier']))
            <p><strong>Proveedor ID:</strong> {{ $filters['supplier'] }}</p>
        @endif
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Proveedor</th>
                <th>N° Contrato</th>
                <th>Tipo</th>
                <th>Estado</th>
                <th>F. Inicio</th>
                <th>F. Fin</th>
                <th>Valor</th>
                <th>Días Restantes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contracts as $contract)
            <tr>
                <td>{{ $contract->supplier->name }}</td>
                <td>{{ $contract->contract_number }}</td>
                <td>{{ ucfirst($contract->type) }}</td>
                <td class="status-{{ $contract->status }}">
                    {{ ucfirst($contract->status) }}
                </td>
                <td>{{ $contract->start_date ? $contract->start_date->format('d/m/Y') : 'N/A' }}</td>
                <td>{{ $contract->end_date ? $contract->end_date->format('d/m/Y') : 'N/A' }}</td>
                <td class="value">${{ number_format($contract->value, 2) }}</td>
                <td>
                    @if($contract->end_date)
                        @php
                            $daysRemaining = now()->diffInDays($contract->end_date, false);
                        @endphp
                        @if($daysRemaining < 0)
                            <span style="color: #dc3545;">Vencido ({{ abs($daysRemaining) }} días)</span>
                        @elseif($daysRemaining <= 30)
                            <span style="color: #ffc107;">{{ $daysRemaining }} días</span>
                        @else
                            {{ $daysRemaining }} días
                        @endif
                    @else
                        N/A
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; border-top: 1px solid #ddd; padding-top: 15px;">
        <h3>Resumen:</h3>
        <p><strong>Total de contratos:</strong> {{ $contracts->count() }}</p>
        <p><strong>Valor total:</strong> ${{ number_format($contracts->sum('value'), 2) }}</p>
        <p><strong>Contratos activos:</strong> {{ $contracts->where('status', 'active')->count() }}</p>
        <p><strong>Contratos vencidos:</strong> {{ $contracts->where('end_date', '<', now())->count() }}</p>
    </div>

    <div class="footer">
        <p>Página {PAGE_NUM} de {PAGE_COUNT} - Reporte generado automáticamente por Sistema ITAM</p>
    </div>
</body>
</html>