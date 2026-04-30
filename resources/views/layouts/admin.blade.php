<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intranet - Sanchez Pharma</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h3>Sanchez Pharma</h3>
            <p>Intranet Administrativa</p>
        </div>
        <div class="sidebar-nav">
            <div class="nav-section-title">Principal</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i> Dashboard
            </a>

            <div class="nav-section-title">Gestión Operativa</div>
            <a href="{{ route('admin.products.index') }}" class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="fas fa-pills"></i> Productos
            </a>
            <a href="{{ route('admin.laboratories.index') }}" class="nav-item {{ request()->routeIs('admin.laboratories.*') ? 'active' : '' }}">
                <i class="fas fa-flask"></i> Laboratorios
            </a>
            <a href="{{ route('admin.zonas.index') }}" class="nav-item {{ request()->routeIs('admin.zonas.*') ? 'active' : '' }}">
                <i class="fas fa-map-signs"></i> Zonas
            </a>
            <a href="{{ route('admin.unidad-medidas.index') }}" class="nav-item {{ request()->routeIs('admin.unidad-medidas.*') ? 'active' : '' }}">
                <i class="fas fa-ruler-combined"></i> Unidades de Medida
            </a>

            <div class="nav-section-title">Ventas & Distribución</div>
            <a href="{{ route('admin.pharmacies.index') }}" class="nav-item {{ request()->routeIs('admin.pharmacies.*') ? 'active' : '' }}">
                <i class="fas fa-store"></i> Boticas
            </a>
            <a href="{{ route('admin.representatives.index') }}" class="nav-item {{ request()->routeIs('admin.representatives.*') ? 'active' : '' }}">
                <i class="fas fa-briefcase"></i> Representantes
            </a>

            <div class="nav-section-title">Administración</div>
            <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Usuarios
            </a>
            <a href="{{ route('admin.reports.index') }}" class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i> Reportes
            </a>
            <a href="{{ route('admin.backups.index') }}" class="nav-item {{ request()->routeIs('admin.backups.*') ? 'active' : '' }}">
                <i class="fas fa-database"></i> Backups
            </a>
            <a href="{{ route('admin.settings.index') }}" class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i> Configuración
            </a>
        </div>
    </div>
    
    <div class="main-content">
        <div class="topbar">
            <div class="topbar-info">
                <span style="color: var(--text-muted); font-size: 0.95rem;">Bienvenido de nuevo,</span>
                <span class="role-badge">{{ strtoupper(auth()->user()->role ?? 'Admin') }}</span>
            </div>
            <div class="user-profile">
                <i class="fas fa-user-circle" style="font-size: 1.5rem; color: var(--primary-color);"></i>
                <span>{{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" style="display:inline; margin-left: 20px;">
                    @csrf
                    <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 0.85rem;">
                        <i class="fas fa-sign-out-alt"></i> Salir
                    </button>
                </form>
            </div>
        </div>
        <div class="content-area">
            @yield('content')
        </div>
    </div>
    
    <script>
        function openModal(id) {
            document.getElementById(id).style.display = 'flex';
        }
        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }
        
        function confirmDelete(id, code, name, action) {
            document.getElementById('delete-item-code').innerText = code;
            document.getElementById('delete-item-name').innerText = name;
            document.getElementById('delete-form').action = action;
            openModal('deleteModal');
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = "none";
            }
        }
    </script>

    <!-- Modal de Eliminación -->
    <div id="deleteModal" class="modal">
        <div class="modal-content" style="max-width: 400px; text-align: center; padding: 40px 30px;">
            <div style="margin-bottom: 20px;">
                <div style="width: 80px; height: 80px; border: 4px solid #fed7aa; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                    <i class="fas fa-exclamation" style="font-size: 2.5rem; color: #f97316;"></i>
                </div>
            </div>
            <h3 style="font-size: 1.5rem; margin-bottom: 20px; font-family: 'Poppins', sans-serif;">¿Eliminar Registro?</h3>
            <div style="text-align: left; margin-bottom: 20px; color: #4b5563; font-size: 0.95rem;">
                <p><strong>Código:</strong> <span id="delete-item-code"></span></p>
                <p><strong>Descripción:</strong> <span id="delete-item-name"></span></p>
                <p style="color: #ef4444; margin-top: 10px; font-size: 0.85rem;">Esta acción no se puede deshacer.</p>
            </div>
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button onclick="closeModal('deleteModal')" class="btn" style="background: #64748b; color: white; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <form id="delete-form" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-trash"></i> Sí, eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
