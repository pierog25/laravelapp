<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cotización Nro. {{ $cotizacion['numero'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
        }

        .header img {
            width: 140px;
            margin-bottom: 5px;
        }

        .empresa-info {
            text-align: center;
            font-size: 12px;
        }

        .empresa-info p {
            margin: 1px 0;
        }

        .cotizacion-titulo {
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
            font-size: 14px;
        }

        .cliente-info {
            margin: 20px 0;
            font-size: 12px;
        }

        .cliente-info p {
            margin: 2px 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .table th, .table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        .table th {
            background-color: #f0f0f0;
        }

        .totales {
            margin-top: 10px;
            font-size: 12px;
        }

        .totales p {
            margin: 2px 0;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
        }

        .footer p {
            margin: 2px 0;
        }

        .firma {
            margin-top: 30px;
            font-size: 12px;
        }

        .firma p {
            margin: 3px 0;
        }
    </style>
</head>
<body>

<table width="100%" style="margin-bottom: 10px;">
    <tr>
        <td style="width: 33%; vertical-align: top;">
            <p style="margin: 0; font-size: 16px;"><strong>JBC Textil</strong></p>
            <p style="margin: 0; font-size: 11px;"><strong>CONFECCIONES TEXTILES</strong></p>
            <p style="margin: 0; font-size: 11px;">RUC: 10176243886</p>
            <p style="margin: 0; font-size: 11px;">
                <a href="https://jbctextil.com/seguimiento#/" style="color: #0000EE;">www.jbctextil.com</a>
            </p>
            <p style="margin: 0; font-size: 11px;">
                <a href="mailto:jbctextil@gmail.com" style="color: #0000EE;">jbctextil@gmail.com</a>
            </p>
            <p style="margin: 0; font-size: 11px;">
                <a href="mailto:ventas@jbctextil.com" style="color: #0000EE;">ventas@jbctextil.com</a>
            </p>
        </td>

        <td style="width: 34%; text-align: center;">
            <img src="{{ public_path('images/JBCTEXTIL.png') }}" alt="Logo" style="height: 80px;">
        </td>

        <td style="width: 33%; text-align: right; vertical-align: top;">
            <p style="font-size: 11px;">{{ $cotizacion['fecha'] }}</p>
        </td>
    </tr>
</table>

<p style="text-align: center; font-size: 13px;">
    <strong><u>Cotización Nro. {{ $cotizacion['numero'] }}&nbsp;&nbsp;{{ strtoupper($cotizacion['cliente']) }}</u></strong>
</p>

<div class="cliente-info">
    <p><strong>Fecha:</strong> {{ $cotizacion['fecha'] }}</p>
    <p><strong>Atención:</strong> {{ $cotizacion['atencion'] }}</p>
</div>

<table class="table">
    <thead>
    <tr>
        <th>ITEM</th>
        <th>CANTIDAD</th>
        <th>UNIDAD</th>
        <th>DESCRIPCIÓN</th>
        <th>P. UNITARIO</th>
        <th>P. TOTAL</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($cotizacion['items'] as $item)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $item['cantidad'] }}</td>
        <td>{{ $item['unidad'] }}</td>
        <td style="text-align: left;">{{ $item['descripcion'] }}</td>
        <td>S/ {{ number_format($item['precio_unitario'], 2) }}</td>
        <td>S/ {{ number_format($item['precio_total'], 2) }}</td>
    </tr>
    @endforeach
    </tbody>
</table>

<div class="totales">
    <p><strong>Total:</strong> S/ {{ number_format($cotizacion['total'], 2) }}</p>
    <p><strong>Moneda:</strong> Soles</p>
    <p><strong>Precios:</strong> Incluyen IGV</p>
    <p><strong>Forma de pago:</strong> 50% adelanto - saldo contra entrega</p>
    <p><strong>Tiempo de entrega:</strong> Por coordinar</p>
    <p><strong>Nota:</strong> La entrega se toma en cuenta desde el día del adelanto del 50%</p>
</div>

<div class="footer">
    <p><strong>Nro. Cta. Ahorros BCP (Soles):</strong> 194-29428573-0-29</p>
    <p><strong>A nombre de:</strong> Jorge Benito Bravo Calderón</p>
</div>

<div class="firma">
    <p><strong>Jorge Bravo</strong></p>
    <p>Asesor Comercial</p>
    <p>Celular: 971813121</p>
</div>

</body>
</html>
