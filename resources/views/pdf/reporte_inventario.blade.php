<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Inventario - Empresa Levay</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #2563eb; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #2563eb; color: white; padding: 8px; }
        td { border: 1px solid #ddd; padding: 8px; }
        .total { margin-top: 20px; font-size: 16px; font-weight: bold; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>EMPRESA LEVAY</h1>
        <p>Reporte General de Inventario | Fecha: {{ $fecha }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Categoría</th>
                <th>Stock</th>
                <th>Precio (USD)</th>
                <th>Subtotal (USD)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $p)
            <tr>
                <td>{{ $p->nombre }}</td>
                <td>{{ $p->categoria->nombre ?? 'N/A' }}</td>
                <td style="text-align: center;">{{ $p->stock }}</td>
                <td style="text-align: right;">${{ number_format($p->precio, 2) }}</td>
                <td style="text-align: right;">${{ number_format($p->precio * $p->stock, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Valor Total del Inventario: ${{ number_format($total, 2) }} USD
    </div>
</body>
</html>
