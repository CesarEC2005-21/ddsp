@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h2 class="page-title"><i class="fas fa-users-cog"></i> Gestión de Usuarios</h2>
            <p class="text-muted">Administra los accesos y permisos del personal administrativo.</p>
        </div>
        <button class="btn btn-primary" onclick="openModal('newUserModal')">
            <i class="fas fa-user-plus"></i> Nuevo Usuario
        </button>
    </div>

    <div class="card">
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Fecha Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.1rem;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $user->name }}</div>
                                    <div style="font-size: 0.85rem; color: #666;">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge badge-info">Administrador</span>
                            @elseif($user->role === 'supervisor')
                                <span class="badge badge-warning">Supervisor</span>
                            @else
                                <span class="badge badge-success">Ing. Sistemas</span>
                            @endif
                        </td>
                        <td>
                            @if($user->estado)
                                <span class="badge badge-success">Activo</span>
                            @else
                                <span class="badge badge-danger">Bloqueado</span>
                            @endif
                        </td>
                        <td style="color: var(--text-muted);">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <button onclick="openEditModal({{ $user }})" class="btn" style="background: #f3f4f6; color: #333; padding: 6px 10px;" title="Editar"><i class="fas fa-edit"></i></button>
                                
                                @if(auth()->user()->role === 'ing_sistemas' && $user->id !== auth()->id())
                                    @if($user->estado)
                                        <button onclick="openBlockModal({{ $user->id }}, '{{ $user->name }}')" class="btn" style="background: #f59e0b; color: white; padding: 6px 10px;" title="Bloquear"><i class="fas fa-lock"></i> Bloquear</button>
                                    @else
                                        <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn" style="background: #10b981; color: white; padding: 6px 10px;" title="Desbloquear"><i class="fas fa-unlock"></i> Desbloquear</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.users.block-history', $user->id) }}" class="btn" style="background: #3b82f6; color: white; padding: 6px 10px;" title="Historial"><i class="fas fa-history"></i> Historial</a>
                                @endif
                                
                                <button onclick="confirmDelete({{ $user->id }}, 'USR-{{ $user->id }}', '{{ $user->name }}', '{{ route('admin.users.destroy', $user->id) }}')" class="btn" style="background: #fee2e2; color: #ef4444; padding: 6px 10px;" title="Eliminar"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding: 30px; text-align: center; color: #888;">No hay usuarios registrados.</td>
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

                    <div class="form-group" style="margin-top: 15px;">
                        <label class="form-label">Permisos Adicionales (Accesos)</label>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 10px; background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid var(--border-color);">
                            @php
                                $modules = [
                                    'productos' => 'Productos',
                                    'laboratorios' => 'Laboratorios',
                                    'zonas' => 'Zonas',
                                    'unidad_medidas' => 'Unidades de Medida',
                                    'representatives' => 'Ejecutivos',
                                    'quotations' => 'Cotizaciones',
                                    'users' => 'Gestión Usuarios',
                                    'audit' => 'Auditoría',
                                    'access_logs' => 'Registro Accesos',
                                    'settings' => 'Configuración Web',
                                    'reports' => 'Reportes',
                                    'backups' => 'Backups'
                                ];
                            @endphp
                            @foreach($modules as $key => $label)
                            <label style="display: flex; align-items: center; gap: 8px; font-size: 0.9rem; cursor: pointer;">
                                <input type="checkbox" name="permissions[]" value="{{ $key }}" style="width: 16px; height: 16px; accent-color: var(--primary-color);"> {{ $label }}
                            </label>
                            @endforeach
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

                    <div class="form-group" style="margin-top: 15px;">
                        <label class="form-label">Permisos Adicionales (Accesos)</label>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 10px; background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid var(--border-color);">
                            @foreach($modules as $key => $label)
                            <label style="display: flex; align-items: center; gap: 8px; font-size: 0.9rem; cursor: pointer;">
                                <input type="checkbox" name="permissions[]" id="edit-perm-{{ $key }}" value="{{ $key }}" style="width: 16px; height: 16px; accent-color: var(--primary-color);"> {{ $label }}
                            </label>
                            @endforeach
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

    <!-- Modal Bloquear Usuario -->
    <div id="blockUserModal" class="modal">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h3><i class="fas fa-user-slash"></i> Bloquear Usuario</h3>
                <span class="close-modal" onclick="closeModal('blockUserModal')">&times;</span>
            </div>
            <div class="modal-body">
                <p style="margin-bottom: 15px; color: #4b5563;">
                    ¿Estás seguro de bloquear a <strong id="block-user-name"></strong>? 
                    <br>El usuario no podrá acceder al sistema.
                </p>
                <form id="blockUserForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label class="form-label">Razón del bloqueo</label>
                        <textarea name="reason" class="form-control" rows="4" placeholder="Ingresa la razón por la cual se bloqueará este usuario..." required></textarea>
                    </div>

                    <div style="margin-top: 20px; display: flex; justify-content: center; gap: 10px;">
                        <button type="submit" class="btn" style="background: #ef4444; color: white; padding: 10px 25px;">Bloquear</button>
                        <button type="button" class="btn" style="background: #6b7280; color: white; padding: 10px 25px;" onclick="closeModal('blockUserModal')">Cancelar</button>
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
        
        // Reset checkboxes
        document.querySelectorAll('input[name="permissions[]"]').forEach(cb => cb.checked = false);
        
        // Check user permissions
        if (user.permissions && Array.isArray(user.permissions)) {
            user.permissions.forEach(perm => {
                const cb = document.getElementById(`edit-perm-${perm}`);
                if (cb) cb.checked = true;
            });
        }
        
        openModal('editUserModal');
    }

    function openBlockModal(id, name) {
        document.getElementById('block-user-name').innerText = name;
        document.getElementById('blockUserForm').action = `/admin/users/${id}/toggle-status`;
        openModal('blockUserModal');
    }
</script>
@endpush
