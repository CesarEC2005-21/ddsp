@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <h2 class="page-title">Gestión de Usuarios</h2>
        <button onclick="openModal('newUserModal')" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Usuario
        </button>
    </div>

    @if(session('success'))
        <div style="background: #D1FAE5; color: #065F46; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div style="background: #FEE2E2; color: #991B1B; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    <div class="card" style="margin-bottom: 20px; padding: 20px; background: white; border-radius: 12px; box-shadow: var(--shadow-sm);">
        <form action="{{ route('admin.users.index') }}" method="GET">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Buscar (Nombre o Correo)</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Buscar...">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Rol</label>
                    <select name="role" class="form-control">
                        <option value="">Todos</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrador</option>
                        <option value="supervisor" {{ request('role') === 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                        <option value="ing_sistemas" {{ request('role') === 'ing_sistemas' ? 'selected' : '' }}>Ing. Sistemas</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Estado</label>
                    <select name="estado" class="form-control">
                        <option value="">Todos</option>
                        <option value="1" {{ request('estado') === '1' ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ request('estado') === '0' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;"><i class="fas fa-search"></i> Filtrar</button>
                    @if(request()->anyFilled(['search', 'role', 'estado']))
                        <a href="{{ route('admin.users.index') }}" class="btn" style="background: #f1f5f9; color: #475569;" title="Limpiar"><i class="fas fa-times"></i></a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo Electrónico</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Fecha de Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td style="font-weight: 500;">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role == 'admin')
                                <span class="badge badge-danger">Administrador</span>
                            @elseif($user->role == 'supervisor')
                                <span class="badge badge-warning">Supervisor</span>
                            @else
                                <span class="badge badge-success">Ing. Sistemas</span>
                            @endif
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" {{ $user->estado ? 'checked' : '' }} onchange="toggleStatusUser({{ $user->id }})">
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td style="color: var(--text-muted);">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            <button onclick="openEditModal({{ $user }})" class="btn" style="background: #f3f4f6; color: #333; padding: 6px 10px;" title="Editar"><i class="fas fa-edit"></i></button>
                            <button onclick="confirmDelete({{ $user->id }}, 'USR-{{ $user->id }}', '{{ $user->name }}', '{{ route('admin.users.destroy', $user->id) }}')" class="btn" style="background: #fee2e2; color: #ef4444; padding: 6px 10px;" title="Eliminar"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding: 30px; text-align: center; color: #888;">No hay usuarios registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Nuevo Usuario -->
    <div id="newUserModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-user-plus"></i> Nuevo Usuario</h3>
                <span class="close-modal" onclick="closeModal('newUserModal')">&times;</span>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" name="name" class="form-control" placeholder="Ej. Juan Pérez" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control" placeholder="ejemplo@correo.com" required>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" class="form-control" placeholder="********" required minlength="8">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Rol de Acceso</label>
                            <select name="role" class="form-control" required>
                                <option value="">Seleccione rol...</option>
                                <option value="admin">Administrador</option>
                                <option value="supervisor">Supervisor</option>
                                <option value="ing_sistemas">Ing. Sistemas</option>
                            </select>
                        </div>
                    </div>

                    <div style="margin-top: 20px; display: flex; justify-content: flex-end; gap: 10px;">
                        <button type="button" class="btn" style="background: #e5e7eb;" onclick="closeModal('newUserModal')">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Usuario -->
    <div id="editUserModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-edit"></i> Editar Usuario</h3>
                <span class="close-modal" onclick="closeModal('editUserModal')">&times;</span>
            </div>
            <div class="modal-body">
                <form id="editUserForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" name="name" id="edit-user-name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" name="email" id="edit-user-email" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Nueva Contraseña (Opcional)</label>
                            <input type="password" name="password" class="form-control" placeholder="Dejar en blanco para no cambiar">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Rol de Acceso</label>
                            <select name="role" id="edit-user-role" class="form-control" required>
                                <option value="admin">Administrador</option>
                                <option value="supervisor">Supervisor</option>
                                <option value="ing_sistemas">Ing. Sistemas</option>
                            </select>
                        </div>
                    </div>

                    <div style="margin-top: 20px; display: flex; justify-content: flex-end; gap: 10px;">
                        <button type="button" class="btn" style="background: #e5e7eb;" onclick="closeModal('editUserModal')">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openEditModal(user) {
        const form = document.getElementById('editUserForm');
        form.action = `/admin/users/${user.id}`;
        document.getElementById('edit-user-name').value = user.name;
        document.getElementById('edit-user-email').value = user.email;
        document.getElementById('edit-user-role').value = user.role;
        openModal('editUserModal');
    }
    function toggleStatusUser(id) {
        console.log('Toggling status for user:', id);
    }
</script>
@endpush
