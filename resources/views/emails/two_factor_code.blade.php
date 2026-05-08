<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Código de Verificación</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            line-height: 1.6;
            color: #1a202c;
            margin: 0;
            padding: 0;
            background-color: #f7fafc;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 40px;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        }
        .header {
            text-align: center;
            margin-bottom: 32px;
        }
        .header h1 {
            color: #2d3748;
            font-size: 24px;
            font-weight: 700;
            margin: 0;
        }
        .content {
            text-align: center;
        }
        .code-container {
            background-color: #f8fafc;
            border: 2px dashed #cbd5e0;
            border-radius: 12px;
            padding: 24px;
            margin: 32px 0;
            letter-spacing: 8px;
        }
        .code {
            font-size: 48px;
            font-weight: 800;
            color: #4a5568;
            margin: 0;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 14px;
            color: #718096;
        }
        .warning {
            font-size: 13px;
            color: #e53e3e;
            margin-top: 24px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Verificación de Acceso</h1>
        </div>
        <div class="content">
            <p>Hola,</p>
            <p>Se ha solicitado un código de verificación para acceder al Intranet de DDSP. Utiliza el siguiente código para completar tu inicio de sesión:</p>
            
            <div class="code-container">
                <p class="code">{{ $code }}</p>
            </div>
            
            <p>Este código expirará en 10 minutos.</p>
            
            <p class="warning">
                Si no has intentado iniciar sesión, por favor ignora este correo o contacta al administrador.
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} DDSP. Todos los derechos reservados.
        </div>
    </div>
</body>
</html>
