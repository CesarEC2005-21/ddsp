<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Dos Pasos - Sanchez Pharma</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #10b981;
            --primary-dark: #059669;
            --text-dark: #0f172a;
            --text-gray: #64748b;
            --bg-color: #f8fafc;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body { 
            font-family: 'Inter', sans-serif; 
            background: linear-gradient(135deg, #f0fdf4 0%, #d1fae5 100%);
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh;
            color: var(--text-dark);
        }

        .login-wrapper {
            background: white;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            display: flex;
            width: 1000px;
            max-width: 95%;
            overflow: hidden;
            position: relative;
        }

        .login-image {
            flex: 1;
            background: linear-gradient(rgba(16, 185, 129, 0.8), rgba(5, 150, 105, 0.9)), url('https://images.unsplash.com/photo-1558494949-ef010cbdcc51?auto=format&fit=crop&w=1000&q=80') center/cover;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: white;
        }

        .login-image h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 20px;
        }

        .login-image p {
            font-size: 1.1rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        .login-form-container {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: white;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo-container img {
            height: 120px;
            object-fit: contain;
            margin-bottom: 15px;
        }

        .logo-container h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.8rem;
            color: var(--text-dark);
            font-weight: 700;
        }

        .logo-container p {
            color: var(--text-gray);
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #475569;
            font-size: 0.9rem;
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.1rem;
            transition: 0.3s;
        }

        .form-group input {
            width: 100%;
            padding: 15px 15px 15px 50px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1.2rem;
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            outline: none;
            transition: all 0.3s ease;
            background: #f8fafc;
            letter-spacing: 10px;
            text-align: center;
        }

        .form-group input:focus {
            border-color: var(--primary-color);
            background: white;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            padding: 16px;
            width: 100%;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.2);
            margin-top: 10px;
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px rgba(16, 185, 129, 0.3);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-secondary {
            background: transparent;
            color: var(--primary-dark);
            border: 2px solid var(--primary-color);
            padding: 12px;
            width: 100%;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 15px;
            transition: all 0.3s;
        }

        .btn-secondary:hover:not(:disabled) {
            background: rgba(16, 185, 129, 0.05);
        }

        .error-message {
            background: #fef2f2;
            color: #ef4444;
            padding: 12px 15px;
            border-radius: 8px;
            font-size: 0.9rem;
            margin-bottom: 20px;
            border: 1px solid #fecaca;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .success-message {
            background: #f0fdf4;
            color: #166534;
            padding: 12px 15px;
            border-radius: 8px;
            font-size: 0.9rem;
            margin-bottom: 20px;
            border: 1px solid #bbf7d0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        @media (max-width: 860px) {
            .login-wrapper { flex-direction: column; width: 500px; }
            .login-image { display: none; }
            .login-form-container { padding: 40px 30px; }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-image">
            <div>
                <i class="fas fa-key" style="font-size: 3rem; margin-bottom: 20px; opacity: 0.9;"></i>
                <h1>Verificación Requerida</h1>
                <p>Hemos enviado un código de 6 dígitos a su correo electrónico registrado. Por favor, ingréselo para confirmar su identidad.</p>
            </div>
            <div style="font-size: 0.85rem; opacity: 0.8;">
                &copy; {{ date('Y') }} Sanchez Pharma. Todos los derechos reservados.
            </div>
        </div>
        
        <div class="login-form-container">
            <div class="logo-container">
                <img src="{{ asset('img/logo.png') }}" alt="Logo Sanchez Pharma">
                <h2>Seguridad de Cuenta</h2>
                <p>Código enviado a: <strong>{{ substr(session('2fa_email'), 0, 3) . '***' . strstr(session('2fa_email'), '@') }}</strong></p>
            </div>

            @if($errors->any())
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('2fa.verify') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Código de Verificación</label>
                    <div class="input-group">
                        <i class="fas fa-shield-alt"></i>
                        <input type="text" name="code" maxlength="6" placeholder="000000" required autofocus autocomplete="one-time-code">
                    </div>
                </div>
                
                <button type="submit" class="btn-primary">
                    Verificar y Acceder <i class="fas fa-check" style="margin-left: 8px;"></i>
                </button>
            </form>

            <form action="{{ route('2fa.resend') }}" method="POST">
                @csrf
                <button type="submit" class="btn-secondary" @if(session('2fa_resend_count', 0) >= 2) disabled @endif>
                    @if(session('2fa_resend_count', 0) >= 2)
                        Límite de reenvíos alcanzado
                    @else
                        Reenviar código ({{ 2 - session('2fa_resend_count', 0) }} restantes)
                    @endif
                </button>
            </form>

            <div style="text-align: center; margin-top: 20px;">
                <a href="{{ route('login') }}" style="color: var(--text-gray); text-decoration: none; font-size: 0.9rem;">
                    <i class="fas fa-arrow-left"></i> Volver al inicio de sesión
                </a>
            </div>
        </div>
    </div>
</body>
</html>
