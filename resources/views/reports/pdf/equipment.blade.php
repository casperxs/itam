<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Equipos - {{ date('d/m/Y') }}</title>
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
        .status-inactive { color: #dc3545; }
        .status-maintenance { color: #ffc107; }
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
        <h1>Reporte de Inventario de Equipos</h1>
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
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Serie</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Estado</th>
                <th>Proveedor</th>
                <th>Precio</th>
                <th>F. Compra</th>
            </tr>
        </thead>
        <tbody>
            @foreach($equipment as $item)
            <tr>
                <td>{{ $item->equipmentType->name }}</td>
                <td>{{ $item->serial_number }}</td>
                <td>{{ $item->brand }}</td>
                <td>{{ $item->model }}</td>
                <td class="status-{{ $item->status }}">{{ ucfirst($item->status) }}</td>
                <td>{{ $item->supplier->name }}</td>
                <td>${{ number_format($item->purchase_price, 2) }}</td>
                <td>{{ $item->purchase_date ? $item->purchase_date->format('d/m/Y') : 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Página {PAGE_NUM} de {PAGE_COUNT} - Reporte generado automáticamente por Sistema ITAM</p>
    </div>
</body>
</html>