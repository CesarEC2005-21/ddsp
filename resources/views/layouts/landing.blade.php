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
    
    <style>
    .nav-dropdown { position: relative; }
    .nav-dropdown-toggle { display: flex; align-items: center; gap: 5px; }
    .nav-dropdown-toggle i { font-size: 0.7rem; transition: 0.3s; }
    .nav-dropdown:hover .nav-dropdown-toggle i { transform: rotate(180deg); }
    .nav-dropdown-menu {
        position: absolute; top: 100%; left: 50%; transform: translateX(-50%); min-width: 220px;
        background: white; border-radius: 12px; box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        padding: 12px 0; opacity: 0; visibility: hidden; transition: 0.3s; z-index: 1000;
        border: 1px solid #f1f5f9; margin-top: 15px;
    }
    .nav-dropdown:hover .nav-dropdown-menu { opacity: 1; visibility: visible; margin-top: 5px; }
    .nav-dropdown-menu a {
        display: block; padding: 10px 20px; color: #1e293b; text-decoration: none; font-size: 0.9rem;
        transition: 0.2s;
    }
    .nav-dropdown-menu a:hover { background: #f0fdf4; color: var(--primary-green); }
    .nav-dropdown-divider { height: 1px; background: #e2e8f0; margin: 8px 0; }
    .nav-dropdown-label {
        display: block; padding: 8px 20px 5px; font-size: 0.75rem; font-weight: 700;
        color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;
    }
    @media (max-width: 768px) {
        .nav-dropdown-menu {
            position: static; transform: none; box-shadow: none; opacity: 1; visibility: visible;
            padding: 0; margin: 10px 0 0 20px; border: none; background: transparent;
        }
    }
    </style>
    
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
                <li style="margin-right: 25px; position: relative;" class="nav-search-container">
                    <form action="{{ route('products') }}" method="GET" style="display: flex; align-items: center; background: #f8fafc; border-radius: 50px; padding: 4px 6px 4px 20px; border: 1px solid #e2e8f0; transition: 0.3s;" onmouseover="this.style.borderColor='var(--primary-green)'; this.style.boxShadow='0 4px 10px rgba(16, 185, 129, 0.1)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                        <input type="text" id="nav-search-input" name="search" autocomplete="off" value="{{ request('search') }}" placeholder="Buscar en catálogo..." style="border: none; background: transparent; outline: none; width: 200px; font-size: 0.9rem; color: #1e293b;" onfocus="this.parentElement.style.background='white'; this.parentElement.style.borderColor='var(--primary-green)';" onblur="this.parentElement.style.background='#f8fafc';">
                        <button type="submit" style="border: none; background: var(--primary-green); color: white; cursor: pointer; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; transition: 0.3s;" onmouseover="this.style.background='var(--dark-green)'" onmouseout="this.style.background='var(--primary-green)'"><i class="fas fa-search"></i></button>
                    </form>
                    <div id="search-autocomplete-results" style="display: none; position: absolute; top: 110%; left: 0; width: 300px; background: white; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; z-index: 9999; overflow: hidden; flex-direction: column;">
                    </div>
                </li>
                <li><a href="{{ route('home') }}">Inicio</a></li>
                <li><a href="{{ route('nosotros') }}">Nosotros</a></li>
                <li><a href="{{ route('about') }}">Ejecutivos</a></li>
                <li class="nav-dropdown">
                    <a href="{{ route('products') }}" class="nav-dropdown-toggle">Laboratorios <i class="fas fa-chevron-down"></i></a>
                    <div class="nav-dropdown-menu">
                        <a href="{{ route('products') }}">Ver Catálogo Completo</a>
                        <div class="nav-dropdown-divider"></div>
                        <span class="nav-dropdown-label">Filtrar por Laboratorio</span>
                        @foreach($laboratories as $lab)
                        <a href="{{ route('products') }}?lab={{ $lab->id }}">{{ $lab->descripcion }}</a>
                        @endforeach
                    </div>
                </li>
                <li><a href="{{ route('contact') }}" class="nav-cta">Contacto</a></li>
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
                    <li><a href="{{ route('nosotros') }}">Nosotros</a></li>
                    <li><a href="{{ route('about') }}">Ejecutivos</a></li>
                    <li><a href="{{ route('products') }}">Laboratorios</a></li>
                    <li><a href="{{ route('contact') }}">Contacto</a></li>
                    <li><a href="{{ route('login') }}">Intranet</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>Contacto</h4>
                <ul>
                    <li><i class="fas fa-map-marker-alt"></i> Chiclayo, Perú</li>
                    <li><i class="fas fa-phone"></i>+51 922 911 909</li>
                    <li><i class="fas fa-envelope"></i>drogueriasanchezpharma@gmail.com</li>
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
        
        // Mobile dropdown toggle
        document.querySelectorAll('.nav-dropdown-toggle').forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    this.nextElementSibling.style.display = this.nextElementSibling.style.display === 'block' ? 'none' : 'block';
                }
            });
        });

        // Auto-suggest logic
        const searchInput = document.getElementById('nav-search-input');
        const searchResults = document.getElementById('search-autocomplete-results');
        let searchTimeout = null;

        if (searchInput && searchResults) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const query = this.value.trim();
                
                if (query.length < 2) {
                    searchResults.style.display = 'none';
                    return;
                }

                searchTimeout = setTimeout(() => {
                    fetch('{{ url("/api/search-products") }}?q=' + encodeURIComponent(query))
                        .then(res => res.json())
                        .then(data => {
                            if (data.length > 0) {
                                searchResults.innerHTML = '';
                                data.forEach(product => {
                                    const imgUrl = product.imagen_url ? product.imagen_url : 'https://placehold.co/40x40/f1f5f9/94a3b8?text=SP';
                                    const itemHtml = `
                                        <a href="/producto/${product.id}" style="display: flex; align-items: center; padding: 10px 15px; border-bottom: 1px solid #f1f5f9; text-decoration: none; transition: background 0.2s;" onmouseover="this.style.background='#f0fdf4'" onmouseout="this.style.background='transparent'">
                                            <img src="${imgUrl}" style="width: 40px; height: 40px; object-fit: contain; border-radius: 6px; margin-right: 12px; border: 1px solid #e2e8f0; background: white;">
                                            <div>
                                                <div style="color: #1e293b; font-size: 0.85rem; font-weight: 600; line-height: 1.2; margin-bottom: 3px;">${product.nombre}</div>
                                                <div style="color: var(--primary-green); font-size: 0.8rem; font-weight: 700;">S/ ${parseFloat(product.precio).toFixed(2)}</div>
                                            </div>
                                        </a>
                                    `;
                                    searchResults.insertAdjacentHTML('beforeend', itemHtml);
                                });
                                searchResults.insertAdjacentHTML('beforeend', `
                                    <a href="{{ route('products') }}?search=${encodeURIComponent(query)}" style="display: block; text-align: center; padding: 10px; background: #f8fafc; color: var(--primary-green); font-size: 0.8rem; font-weight: 700; text-decoration: none;">Ver todos los resultados</a>
                                `);
                                searchResults.style.display = 'flex';
                            } else {
                                searchResults.innerHTML = '<div style="padding: 15px; text-align: center; color: #94a3b8; font-size: 0.85rem;">No se encontraron productos.</div>';
                                searchResults.style.display = 'block';
                            }
                        });
                }, 300);
            });

            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                    searchResults.style.display = 'none';
                }
            });
            
            searchInput.addEventListener('focus', function() {
                if (this.value.trim().length >= 2) {
                    searchResults.style.display = 'flex';
                }
            });
        }
    </script>
    <script src="{{ asset('js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>
