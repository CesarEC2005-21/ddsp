<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sanchez Pharma | Droguería y Distribuidora</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Base styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <!-- Dynamic page styles -->
    @stack('styles')
</head>
<body>

    <!-- Navigation -->
    <nav>
        <div class="nav-container">
            <a href="{{ route('home') }}" class="logo-container">
                <img src="{{ asset('img/logo.png') }}" alt="Sanchez Pharma Logo" class="logo-img">
                <div class="logo-text">
                    <strong>DROGUERIA</strong><br>SANCHEZ PHARMA
                </div>
            </a>
            
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>

            <ul class="nav-links" id="navLinks" style="display: flex; align-items: center;">
                <li style="margin-right: 25px;">
                    <form action="{{ route('products') }}" method="GET" style="display: flex; background: white; border-radius: 12px; padding: 6px 18px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar productos..." style="border: none; background: transparent; outline: none; width: 220px; font-size: 0.95rem; color: #1e293b;">
                        <button type="submit" style="border: none; background: transparent; color: var(--primary-green); cursor: pointer; font-size: 1rem;"><i class="fas fa-search"></i></button>
                    </form>
                </li>
                <li><a href="{{ route('home') }}">Inicio</a></li>
                <li><a href="{{ route('about') }}">Nosotros</a></li>
                <li><a href="{{ route('products') }}">Productos</a></li>
                <li><a href="{{ route('contact') }}" class="nav-cta">Contáctanos</a></li>
                <li>
                    <a href="{{ route('cart.index') }}" class="cart-icon" style="color: var(--primary-green); font-size: 1.4rem; position: relative; margin-left: 10px;">
                        <i class="fas fa-shopping-basket"></i>
                        @if(count(session('cart', [])) > 0)
                            <span id="nav-cart-badge" style="position: absolute; top: -8px; right: -8px; background: #ef4444; color: white; width: 18px; height: 18px; border-radius: 50%; font-size: 0.7rem; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 2px solid white;">
                                 {{ count(session('cart', [])) }}
                            </span>
                        @else
                            <span id="nav-cart-badge" style="position: absolute; top: -8px; right: -8px; background: #ef4444; color: white; width: 18px; height: 18px; border-radius: 50%; font-size: 0.7rem; display: none; align-items: center; justify-content: center; font-weight: bold; border: 2px solid white;">
                                0
                            </span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <main style="padding-top: 100px; min-height: calc(100vh - 400px);">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-logo">
                <img src="{{ asset('img/logo.png') }}" alt="Sanchez Pharma Logo" height="60" style="margin-bottom: 15px;">
                <p>Droguería y Distribuidora Sanchez Pharma E.I.R.L. <br> Comprometidos con la salud de todos.</p>
            </div>
            <div class="footer-links">
                <h4>Enlaces Rápidos</h4>
                <ul>
                    <li><a href="{{ route('home') }}">Inicio</a></li>
                    <li><a href="{{ route('about') }}">Nosotros</a></li>
                    <li><a href="{{ route('products') }}">Productos</a></li>
                    <li><a href="{{ route('contact') }}">Contacto</a></li>
                    <li><a href="/login">Intranet</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>Contacto</h4>
                <ul>
                    <li><i class="fas fa-map-marker-alt"></i> Lima, Perú</li>
                    <li><i class="fas fa-phone"></i> +51 987 654 321</li>
                    <li><i class="fas fa-envelope"></i> ventas@sanchezpharma.com</li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>Siguenos</h4>
                <div style="display: flex; gap: 15px; font-size: 1.5rem;">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 Droguería y Distribuidora Sanchez Pharma. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script>
        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            document.getElementById('navLinks').classList.toggle('active');
            this.querySelector('i').classList.toggle('fa-bars');
            this.querySelector('i').classList.toggle('fa-times');
        });
    </script>
    <script src="{{ asset('js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>
