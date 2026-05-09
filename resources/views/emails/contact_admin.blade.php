<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Mensaje de Contacto</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0fdf4; color: #1e293b; line-height: 1.6; padding: 30px 15px; }
        .email-container { max-width: 580px; margin: 0 auto; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 30px rgba(0,0,0,0.08); }
        .email-header { background: linear-gradient(135deg, #10b981 0%, #059669 60%, #047857 100%); color: white; padding: 35px 30px; text-align: center; }
        .email-header h1 { font-size: 22px; font-weight: 800; color: white; letter-spacing: 0.5px; margin-bottom: 4px; }
        .email-header .subtitle { font-size: 13px; opacity: 0.85; font-weight: 400; }
        .divider { height: 1px; background: rgba(255,255,255,0.25); margin: 18px 0; }
        .badge-header { display: inline-block; background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); border-radius: 50px; padding: 6px 18px; font-size: 12px; font-weight: 600; color: white; letter-spacing: 1px; text-transform: uppercase; }
        .email-body { padding: 35px 30px; }
        .greeting { font-size: 16px; color: #334155; margin-bottom: 8px; }
        .intro { font-size: 14px; color: #64748b; margin-bottom: 25px; }
        .data-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; margin-bottom: 25px; }
        .data-card-header { background: #10b981; padding: 10px 20px; }
        .data-card-header span { font-size: 11px; font-weight: 700; color: white; text-transform: uppercase; letter-spacing: 1.5px; }
        .data-row { display: flex; align-items: flex-start; padding: 12px 20px; border-bottom: 1px solid #f1f5f9; }
        .data-row:last-child { border-bottom: none; }
        .data-label { font-weight: 700; color: #64748b; width: 130px; flex-shrink: 0; font-size: 13px; padding-top: 1px; }
        .data-value { color: #0f172a; font-weight: 500; font-size: 14px; }
        .data-value a { color: #10b981; text-decoration: none; }
        .message-section { margin-bottom: 25px; }
        .message-label { font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        .message-box { background: #f0fdf4; border: 1px solid #bbf7d0; border-left: 4px solid #10b981; padding: 18px 20px; border-radius: 0 10px 10px 0; color: #334155; font-size: 14px; line-height: 1.8; font-style: italic; }
        .cta-section { text-align: center; padding: 10px 0 5px; }
        .btn-reply { display: inline-block; background: linear-gradient(135deg, #10b981, #059669); color: #ffffff !important; text-decoration: none !important; padding: 14px 35px; border-radius: 50px; font-weight: 700; font-size: 15px; letter-spacing: 0.3px; box-shadow: 0 4px 15px rgba(16,185,129,0.3); }
        .email-footer { background: #f8fafc; border-top: 1px solid #e2e8f0; text-align: center; padding: 20px 30px; font-size: 12px; color: #94a3b8; line-height: 1.7; }
        .footer-brand { font-weight: 700; color: #10b981; font-size: 13px; margin-bottom: 5px; }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>Droguería y Distribuidora<br>Sánchez Pharma</h1>
            <p class="subtitle">Sistema de Gestión de Contactos</p>
            <div class="divider"></div>
            <span class="badge-header">✉ Nueva Solicitud de Contacto</span>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p class="greeting">Hola, Administrador,</p>
            <p class="intro">Has recibido una nueva solicitud a través del formulario web. A continuación los detalles del remitente:</p>

            <div class="data-card">
                <div class="data-card-header"><span>Datos del Solicitante</span></div>
                <div class="data-row">
                    <div class="data-label">Empresa / Nombre:</div>
                    <div class="data-value">{{ $data['empresa'] }}</div>
                </div>
                <div class="data-row">
                    <div class="data-label">RUC / Documento:</div>
                    <div class="data-value">{{ $data['ruc'] }}</div>
                </div>
                <div class="data-row">
                    <div class="data-label">Correo:</div>
                    <div class="data-value"><a href="mailto:{{ $data['email'] }}">{{ $data['email'] }}</a></div>
                </div>
                <div class="data-row">
                    <div class="data-label">Teléfono:</div>
                    <div class="data-value">{{ $data['telefono'] ?? 'No especificado' }}</div>
                </div>
                <div class="data-row">
                    <div class="data-label">Asunto:</div>
                    <div class="data-value" style="text-transform: capitalize; font-weight: 700; color: #10b981;">{{ $data['asunto'] }}</div>
                </div>
            </div>

            <div class="message-section">
                <div class="message-label">📝 Mensaje del Cliente</div>
                <div class="message-box">
                    {!! nl2br(e($data['mensaje'])) !!}
                </div>
            </div>

            <div class="cta-section">
                <a href="mailto:{{ $data['email'] }}" class="btn-reply" style="color: #ffffff !important; text-decoration: none !important;">
                    ↩ Responder al Cliente
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <div class="footer-brand">Sánchez Pharma — Droguería y Distribuidora</div>
            Correo generado automáticamente por el sistema web.<br>
            Por favor no respondas directamente a esta dirección de envío.
        </div>
    </div>
</body>
</html>
