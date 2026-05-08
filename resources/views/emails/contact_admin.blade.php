<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Mensaje de Contacto</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f1f5f9; color: #1e293b; line-height: 1.6; padding: 20px; margin: 0; }
        .email-container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .email-header { background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 30px 20px; text-align: center; }
        .email-header h1 { margin: 0; font-size: 20px; font-weight: 700; letter-spacing: 1px; display: flex; align-items: center; justify-content: center; gap: 15px; }
        .email-header h1 img { height: 40px; background: white; padding: 5px; border-radius: 6px; }
        .email-header p { margin: 8px 0 0; opacity: 0.9; font-size: 14px; }
        .email-body { padding: 30px; }
        .data-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin-bottom: 25px; }
        .data-row { display: flex; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px dashed #cbd5e1; }
        .data-row:last-child { margin-bottom: 0; padding-bottom: 0; border-bottom: none; }
        .data-label { font-weight: 600; color: #64748b; width: 140px; flex-shrink: 0; }
        .data-value { color: #0f172a; font-weight: 500; }
        .message-box { background: white; border-left: 4px solid #10b981; padding: 15px 20px; margin-top: 10px; border-radius: 0 8px 8px 0; box-shadow: 0 2px 5px rgba(0,0,0,0.02); color: #334155; font-style: italic; }
        .email-footer { text-align: center; padding: 20px; font-size: 12px; color: #94a3b8; border-top: 1px solid #f1f5f9; background: #f8fafc; }
        .btn-reply { display: inline-block; background: #1e293b; color: white; text-decoration: none; padding: 10px 25px; border-radius: 50px; font-weight: 600; margin-top: 20px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="vertical-align: middle; padding-right: 15px; width: 60px;">
                        <img src="{{ url('img/logo.png') }}" alt="Logo" style="height: 50px; width: auto; display: block; background: white; padding: 5px; border-radius: 8px;">
                    </td>
                    <td style="vertical-align: middle;">
                        <div style="font-size: 18px; font-weight: 800; color: white; line-height: 1.3;">Droguería y Distribuidora<br>Sanchez Pharma</div>
                    </td>
                </tr>
            </table>
            <p style="margin: 15px 0 0; opacity: 0.9; font-size: 14px; border-top: 1px solid rgba(255,255,255,0.3); padding-top: 12px;">Nuevo Mensaje de Contacto</p>
        </div>
        
        <div class="email-body">
            <p style="margin-top: 0;">Hola Administrador,</p>
            <p>Has recibido una nueva solicitud a través del formulario web. Aquí están los detalles:</p>
            
            <div class="data-card">
                <div class="data-row">
                    <div class="data-label">Empresa:</div>
                    <div class="data-value">{{ $data['empresa'] }}</div>
                </div>
                <div class="data-row">
                    <div class="data-label">RUC:</div>
                    <div class="data-value">{{ $data['ruc'] }}</div>
                </div>
                <div class="data-row">
                    <div class="data-label">Correo:</div>
                    <div class="data-value"><a href="mailto:{{ $data['email'] }}" style="color: #10b981;">{{ $data['email'] }}</a></div>
                </div>
                <div class="data-row">
                    <div class="data-label">Teléfono:</div>
                    <div class="data-value">{{ $data['telefono'] ?? 'No especificado' }}</div>
                </div>
                <div class="data-row">
                    <div class="data-label">Asunto:</div>
                    <div class="data-value" style="text-transform: capitalize;">{{ $data['asunto'] }}</div>
                </div>
            </div>
            
            <div style="font-weight: 600; color: #475569;">Mensaje del Cliente:</div>
            <div class="message-box">
                {!! nl2br(e($data['mensaje'])) !!}
            </div>
            
            <div style="text-align: center;">
                <a href="mailto:{{ $data['email'] }}" class="btn-reply">Responder al Cliente</a>
            </div>
        </div>
        
        <div class="email-footer">
            Este es un correo automático generado por la plataforma web de Sánchez Pharma.<br>
            Por favor, no respondas directamente a esta dirección de envío.
        </div>
    </div>
</body>
</html>
