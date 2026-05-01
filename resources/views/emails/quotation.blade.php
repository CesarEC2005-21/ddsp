<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { text-align: center; margin-bottom: 30px; }
        .footer { font-size: 12px; color: #777; margin-top: 30px; text-align: center; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #2E7D32; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="color: #2E7D32;">¡Hola, {{ $quotation->nombre }}!</h2>
        </div>
        <p>Hemos recibido tu solicitud de cotización en <strong>{{ $company['name'] }}</strong>.</p>
        <p>Adjunto a este correo encontrarás un documento PDF con el detalle de los productos solicitados y los precios estimados.</p>
        <p>Uno de nuestros asesores revisará tu pedido y se pondrá en contacto contigo muy pronto a través de WhatsApp o llamada telefónica.</p>
        <div style="text-align: center; margin: 30px 0;">
            <p><strong>ID de Solicitud:</strong> #{{ str_pad($quotation->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>
        <p>Gracias por elegirnos.</p>
        <div class="footer">
            Atentamente,<br>
            El equipo de {{ $company['name'] }}<br>
            {{ $company['phone'] }} | {{ $company['email'] }}
        </div>
    </div>
</body>
</html>
