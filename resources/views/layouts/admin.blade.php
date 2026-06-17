<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intranet - Sanchez Pharma</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ time() }}">
    @stack('styles')
</head>
<body>
    <!-- ===================== SIDEBAR ===================== -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <button class="sidebar-toggle-mobile" onclick="toggleSidebar()"><i class="fas fa-times"></i></button>
            <img src="{{ asset('img/logo.png') }}" alt="Logo Sanchez Pharma">
            <h3>Droguería y Distribuidora<br>Sanchez Pharma</h3>
            <p>Intranet Administrativa</p>
        </div>

        @php
            $u = auth()->user();
            $perms = $u->permissions ?? [];
            $can = function($p) use ($u, $perms) {
                // Solo el Ingeniero de Sistemas tiene acceso total incondicional
                // El Administrador y Supervisor dependen de los permisos marcados
                return $u->role === 'ing_sistemas' || in_array($p, $perms);
            };
        @endphp

        <nav class="sidebar-nav">
            <div class="nav-section-title">Principal</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i> Dashboard
            </a>

            <div class="nav-section-title">Gestión Operativa</div>
            @if($can('productos'))
            <a href="{{ route('admin.products.index') }}" class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="fas fa-pills"></i> Productos
            </a>
            @endif
            @if($can('laboratorios'))
            <a href="{{ route('admin.laboratories.index') }}" class="nav-item {{ request()->routeIs('admin.laboratories.*') ? 'active' : '' }}">
                <i class="fas fa-flask"></i> Laboratorios
            </a>
            @endif
            @if($can('zonas'))
            <a href="{{ route('admin.zonas.index') }}" class="nav-item {{ request()->routeIs('admin.zonas.*') ? 'active' : '' }}">
                <i class="fas fa-map-signs"></i> Zonas
            </a>
            @endif
            @if($can('unidad_medidas'))
            <a href="{{ route('admin.unidad-medidas.index') }}" class="nav-item {{ request()->routeIs('admin.unidad-medidas.*') ? 'active' : '' }}">
                <i class="fas fa-ruler-combined"></i> Unidades de Medida
            </a>
            @endif

            <div class="nav-section-title">Marketing & Contenido</div>
            @if($can('noticias'))
            <a href="{{ route('admin.noticias.index') }}" class="nav-item {{ request()->routeIs('admin.noticias.*') ? 'active' : '' }}">
                <i class="fas fa-newspaper"></i> Noticias / Promociones
            </a>
            @endif
            @if($can('certificados'))
            <a href="{{ route('admin.certificados.index') }}" class="nav-item {{ request()->routeIs('admin.certificados.*') ? 'active' : '' }}">
                <i class="fas fa-certificate"></i> Certificaciones
            </a>
            @endif
            @if($can('banners'))
            <a href="{{ route('admin.banners.index') }}" class="nav-item {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                <i class="fas fa-images"></i> Banners
            </a>
            @endif

            <div class="nav-section-title">Ventas & Distribución</div>
            @if($can('representatives'))
            <a href="{{ route('admin.representatives.index') }}" class="nav-item {{ request()->routeIs('admin.representatives.*') ? 'active' : '' }}">
                <i class="fas fa-briefcase"></i> Ejecutivos
            </a>
            @endif
            @if($can('quotations'))
            <a href="{{ route('admin.quotations.index') }}" class="nav-item {{ request()->routeIs('admin.quotations.*') ? 'active' : '' }}">
                <i class="fas fa-file-invoice"></i> Cotizaciones
                @php $pending = \App\Models\Quotation::where('estado', 'pendiente')->count(); @endphp
                @if($pending > 0)
                    <span class="badge badge-warning" style="margin-left: auto; font-size: 0.7rem; padding: 2px 8px;">{{ $pending }}</span>
                @endif
            </a>
            @endif

            <div class="nav-section-title">Administración</div>
            @if($can('users'))
            <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Usuarios
            </a>
            @endif
            
            @if($can('audit') || $can('access_logs'))
            <div class="nav-section-title">Seguridad</div>
            @if($can('audit'))
            <a href="{{ route('admin.security.audit') }}" class="nav-item {{ request()->routeIs('admin.security.audit') ? 'active' : '' }}">
                <i class="fas fa-history"></i> Auditoría
            </a>
            @endif
            @if($can('access_logs'))
            <a href="{{ route('admin.security.access') }}" class="nav-item {{ request()->routeIs('admin.security.access') ? 'active' : '' }}">
                <i class="fas fa-sign-in-alt"></i> Registro Accesos
            </a>
            @endif
            @endif
            
            <div class="nav-section-title">Sistema</div>
            @if($can('reports'))
            <a href="{{ route('admin.reports.index') }}" class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i> Reportes
            </a>
            @endif
            @if($can('backups'))
            <a href="{{ route('admin.backups.index') }}" class="nav-item {{ request()->routeIs('admin.backups.*') ? 'active' : '' }}">
                <i class="fas fa-database"></i> Backups
            </a>
            @endif
            @if($can('settings'))
            <a href="{{ route('admin.settings.index') }}" class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i> Configuración
            </a>
            @endif
        </nav>

        <!-- Logout removido de aquí -->
    </div>
    </div>

    <!-- ===================== MAIN CONTENT ===================== -->
    <div class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <div style="display: flex; align-items: center; gap: 15px;">
                <button class="hamburger-btn" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="topbar-info">
                    <span>Bienvenido,</span>
                    <span class="fw-bold" style="color: var(--text);">{{ auth()->user()->name }}</span>
                    <span class="role-badge">{{ strtoupper(auth()->user()->role ?? 'Admin') }}</span>
                </div>
            </div>

            <div class="user-profile">
                <!-- Notifications -->
                @php
                    $pendingQuotations = \App\Models\Quotation::where('estado', 'pendiente')->latest()->take(5)->get();
                    $pendingCount = \App\Models\Quotation::where('estado', 'pendiente')->count();
                @endphp
                <div class="notifications-wrapper">
                    <button class="notification-btn" id="notifBtn" type="button">
                        <i class="fas fa-bell"></i>
                        @if($pendingCount > 0)
                            <span class="notification-badge">{{ $pendingCount > 9 ? '9+' : $pendingCount }}</span>
                        @endif
                    </button>

                    <div class="notifications-dropdown" id="notifDropdown">
                        <div class="notif-header">
                            <h4><i class="fas fa-bell" style="color: var(--primary); margin-right: 6px;"></i> Notificaciones</h4>
                            @if($pendingCount > 0)
                                <span class="badge badge-warning">{{ $pendingCount }} nueva{{ $pendingCount > 1 ? 's' : '' }}</span>
                            @endif
                        </div>
                        <div class="notif-list">
                            @forelse($pendingQuotations as $notif)
                                <a href="{{ route('admin.quotations.index') }}" class="notif-item">
                                    <div class="notif-icon"><i class="fas fa-file-invoice"></i></div>
                                    <div class="notif-content">
                                        <span class="notif-title">Cotización #{{ str_pad($notif->id, 5, '0', STR_PAD_LEFT) }}</span>
                                        <span class="notif-text">{{ $notif->nombre }} {{ $notif->apellidos }}</span>
                                        <span class="notif-time">{{ $notif->created_at->diffForHumans() }}</span>
                                    </div>
                                </a>
                            @empty
                                <div class="notif-empty">
                                    <i class="fas fa-check-circle" style="color: var(--primary); font-size: 2rem; display: block; margin-bottom: 8px;"></i>
                                    Sin cotizaciones pendientes
                                </div>
                            @endforelse
                        </div>
                        <div class="notif-footer">
                            <a href="{{ route('admin.quotations.index') }}">Ver todas las cotizaciones →</a>
                        </div>
                    </div>
                </div>

                <div style="width: 1px; height: 28px; background: var(--border);"></div>

                <div class="profile-dropdown-wrapper">
                    <button class="profile-toggle-btn" id="profileBtn">
                        <div class="avatar-circle">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="profile-info-text">
                            <span class="profile-name">{{ auth()->user()->name }}</span>
                            <span class="profile-role">{{ strtoupper(auth()->user()->role ?? 'Admin') }}</span>
                        </div>
                        <i class="fas fa-chevron-down" style="font-size: 0.7rem; opacity: 0.5;"></i>
                    </button>

                    <div class="profile-dropdown" id="profileDropdown">
                        <div class="dropdown-section">
                            <span class="dropdown-label">MI CUENTA</span>
                        </div>
                        <a href="{{ route('admin.settings.index') }}" class="dropdown-item">
                            <i class="fas fa-cog"></i> Configuración
                        </a>
                        <div style="border-top: 1px solid #f1f5f9; margin: 5px 0;"></div>
                        <form action="{{ route('logout') }}" method="POST" id="logout-form-dropdown">
                            @csrf
                            <button type="submit" class="dropdown-item logout-link">
                                <i class="fas fa-sign-out-alt"></i> Salir
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- ===================== SCRIPTS ===================== -->
    <script>
        // Modal Helpers
        function openModal(id) {
            const el = document.getElementById(id);
            if (el) { el.style.display = 'flex'; document.body.style.overflow = 'hidden'; }
        }
        function closeModal(id) {
            const el = document.getElementById(id);
            if (el) { el.style.display = 'none'; document.body.style.overflow = ''; }
        }

        // Delete Confirm
        function confirmDelete(id, code, name, action) {
            Swal.fire({
                title: '¿Eliminar registro?',
                html: `<div style="text-align:left; font-size: 0.95rem; color: #475569;">
                    <p style="margin-bottom: 8px;"><strong>Código:</strong> ${code}</p>
                    <p style="margin-bottom: 0;"><strong>Descripción:</strong> ${name}</p>
                </div>`,
                icon: 'warning',
                iconColor: '#f59e0b',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="fas fa-trash"></i> Sí, eliminar',
                cancelButtonText: 'Cancelar',
                customClass: { popup: 'swal-custom', confirmButton: 'swal-btn-confirm', cancelButton: 'swal-btn-cancel' }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = action;
                    form.innerHTML = `@csrf @method('DELETE')`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Sidebar Toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            
            if (window.innerWidth <= 968) {
                sidebar.classList.toggle('show-mobile');
            } else {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
            }
        }

        // Notification Dropdown
        const notifBtn = document.getElementById('notifBtn');
        const notifDropdown = document.getElementById('notifDropdown');
        const profileBtn = document.getElementById('profileBtn');
        const profileDropdown = document.getElementById('profileDropdown');

        if (notifBtn) {
            notifBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                notifDropdown.classList.toggle('show');
                profileDropdown?.classList.remove('show');
            });
        }

        if (profileBtn) {
            profileBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                profileDropdown.classList.toggle('show');
                notifDropdown?.classList.remove('show');
            });
        }

        document.addEventListener('click', (e) => {
            if (!e.target.closest('.notifications-wrapper')) {
                notifDropdown?.classList.remove('show');
            }
            if (!e.target.closest('.profile-dropdown-wrapper')) {
                profileDropdown?.classList.remove('show');
            }
        });

        // Close modal on backdrop click
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal')) closeModal(e.target.id);
        });

        // Close modal on ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal').forEach(m => {
                    if (m.style.display === 'flex') closeModal(m.id);
                });
            }
        });
    </script>

    <!-- Global Delete Modal (fallback) -->
    <div id="deleteModal" class="modal">
        <div class="modal-content" style="max-width: 420px;">
            <div class="modal-header">
                <h3><i class="fas fa-exclamation-triangle" style="background: #fef3c7; color: #f59e0b;"></i> Confirmar Eliminación</h3>
                <button class="close-modal" onclick="closeModal('deleteModal')">&times;</button>
            </div>
            <div class="modal-body" style="text-align: center; padding: 32px;">
                <div style="width: 72px; height: 72px; border-radius: 50%; background: #fef3c7; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 2rem; color: #f59e0b;">
                    <i class="fas fa-exclamation"></i>
                </div>
                <p style="color: #475569; margin-bottom: 16px;">¿Estás seguro de eliminar este registro?</p>
                <div style="background: #f8fafc; border-radius: 10px; padding: 14px; text-align: left; font-size: 0.9rem; margin-bottom: 24px;">
                    <p><strong>Código:</strong> <span id="delete-item-code"></span></p>
                    <p style="margin-top: 6px;"><strong>Nombre:</strong> <span id="delete-item-name"></span></p>
                </div>
                <p style="color: #ef4444; font-size: 0.85rem;"><i class="fas fa-exclamation-circle"></i> Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('deleteModal')">Cancelar</button>
                <form id="delete-form" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Eliminar</button>
                </form>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
