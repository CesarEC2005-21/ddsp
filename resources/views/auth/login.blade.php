<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intranet Login - Sanchez Pharma</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-green: #2E7D32;
            --dark-green: #1B5E20;
            --bg-light: #F9FAFB;
        }
        body { margin: 0; font-family: 'Inter', sans-serif; background: var(--bg-light); display: flex; justify-content: center; align-items: center; height: 100vh; }
        .login-card { background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 400px; text-align: center; }
        .login-card img { height: 60px; margin-bottom: 20px; }
        .login-card h2 { font-family: 'Poppins', sans-serif; color: var(--dark-green); margin-bottom: 30px; }
        .form-group { text-align: left; margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: #555; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; box-sizing: border-box; outline: none; transition: 0.3s; }
        .form-group input:focus { border-color: var(--primary-green); }
        .btn-primary { background: var(--primary-green); color: white; border: none; padding: 15px; width: 100%; border-radius: 8px; font-size: 1.1rem; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .btn-primary:hover { background: var(--dark-green); }
        .error { color: #d32f2f; font-size: 0.9rem; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="login-card">
        <img src="{{ asset('img/logo.png') }}" alt="Logo Sanchez Pharma">
        <h2>Acceso Intranet</h2>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Correo Electrónico</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn-primary">Ingresar al Panel</button>
        </form>
    </div>
</body>
</html>
