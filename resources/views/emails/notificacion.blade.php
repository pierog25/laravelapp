<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estado de tu envío</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f7f7; margin: 0; padding: 0; }
        .container { background: #fff; max-width: 600px; margin: 40px auto; padding: 32px 24px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.06);}
        .estado { font-size: 20px; font-weight: bold; color: #0b8043; }
        .id-envio { font-size: 16px; color: #555; margin-bottom: 24px;}
        .saludo { font-size: 18px; color: #333;}
        .mensaje { font-size: 16px; color: #444; margin: 20px 0;}
        .footer { font-size: 13px; color: #888; margin-top: 40px; text-align: center;}
        .btn {
            display: inline-block;
            padding: 12px 28px;
            font-size: 16px;
            color: #fff !important;
            background: #2196f3;
            border-radius: 4px;
            text-decoration: none;
            margin-top: 18px;
            font-weight: 600;
        }
    </style>
</head>
<body>
<div class="container">
    <p class="saludo">Hola {{ $nombre }},</p>
    <p class="mensaje">
        Queremos informarte sobre el estado de tu envío.
    </p>
    <div class="estado">
        Estado actual: {{ $estado }}
    </div>
    <div class="id-envio">
        ID de tu envío: <strong>{{ $id_envio }}</strong>
    </div>
    @if(!empty($mensaje_extra))
    <p class="mensaje">{{ $mensaje_extra }}</p>
    @endif

    @if(strtolower($estado) === 'entregado' && !empty($link_encuesta))
    <p>
        <a href="{{ $link_encuesta }}" class="btn" target="_blank">
            Califica tu experiencia
        </a>
    </p>
    @endif

    <p>
        Te mantendremos informado sobre cualquier cambio en el estado de tu pedido.<br>
        ¡Gracias por confiar en nosotros!
    </p>
    <div class="footer">
        Este es un correo automático, por favor no respondas a este mensaje.<br>
        &copy; {{ date('Y') }} JBC TEXTIL.
    </div>
</div>
</body>
</html>