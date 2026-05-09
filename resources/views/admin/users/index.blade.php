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
        <div class="modal-content" style="max-width: 850px; padding: 0; overflow: hidden; border: none; border-radius: 20px;">
            <div class="modal-header" style="background: #1e293b; color: white; padding: 20px 30px; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="background: rgba(16, 185, 129, 0.2); width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user-plus" style="color: #10b981; font-size: 1.2rem;"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 1.25rem; font-weight: 700; color: #ffffff;">Registrar Nuevo Usuario</h3>
                </div>
                <span class="close-modal" onclick="closeModal('newUserModal')" style="background: rgba(255,255,255,0.1); width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; cursor: pointer;">&times;</span>
            </div>
            <div class="modal-body" style="padding: 40px;">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    
                    <div style="margin-bottom: 35px;">
                        <h4 style="display: flex; align-items: center; gap: 12px; color: #1e293b; margin-bottom: 20px; font-weight: 700;">
                            <div style="background: #10b981; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem;">
                                <i class="fas fa-key"></i>
                            </div>
                            Credenciales de Acceso
                        </h4>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label class="form-label" style="color: #475569; font-weight: 600;">Nombre Completo</label>
                                <div style="position: relative;">
                                    <i class="fas fa-user" style="position: absolute; left: 18px; top: 16px; color: #94a3b8;"></i>
                                    <input type="text" name="name" class="form-control" style="padding-left: 50px; border-radius: 12px;" placeholder="Ej. Juan Pérez" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label" style="color: #475569; font-weight: 600;">Correo Electrónico</label>
                                <div style="position: relative;">
                                    <i class="fas fa-envelope" style="position: absolute; left: 18px; top: 16px; color: #94a3b8;"></i>
                                    <input type="email" name="email" class="form-control" style="padding-left: 50px; border-radius: 12px;" placeholder="ejemplo@sanchezpharma.com" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label" style="color: #475569; font-weight: 600;">Rol de Sistema</label>
                                <div style="position: relative;">
                                    <i class="fas fa-user-tag" style="position: absolute; left: 18px; top: 16px; color: #94a3b8;"></i>
                                    <select name="role" class="form-control" style="padding-left: 50px; border-radius: 12px;" required>
                                        <option value="">Seleccione rol...</option>
                                        <option value="admin">Administrador</option>
                                        <option value="supervisor">Supervisor</option>
                                        <option value="ing_sistemas">Ing. Sistemas</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label" style="color: #475569; font-weight: 600;">Contraseña de Acceso</label>
                                <div style="position: relative;">
                                    <i class="fas fa-lock" style="position: absolute; left: 18px; top: 16px; color: #94a3b8;"></i>
                                    <input type="password" name="password" class="form-control" style="padding-left: 50px; border-radius: 12px;" placeholder="********" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 style="display: flex; align-items: center; gap: 12px; color: #1e293b; margin-bottom: 20px; font-weight: 700;">
                            <div style="background: #10b981; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem;">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            Matriz de Permisos
                        </h4>
                        <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 15px;">Seleccione los módulos a los que tendrá acceso:</p>
                        
                        <div style="background: #f8fafc; border-radius: 15px; border: 1.5px solid #f1f5f9; padding: 25px;">
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 15px;">
                                @php
                                    $modules = [
                                        'productos' => 'Productos',
                                        'laboratorios' => 'Laboratorios',
                                        'zonas' => 'Zonas',
                                        'unidad_medidas' => 'Unidades de Medida',
                                        'noticias' => 'Noticias / Promociones',
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
                                <label style="display: flex; align-items: center; gap: 10px; background: white; padding: 12px; border-radius: 10px; border: 1px solid #e2e8f0; cursor: pointer; transition: all 0.2s;">
                                    <input type="checkbox" name="permissions[]" value="{{ $key }}" style="width: 18px; height: 18px; accent-color: #10b981;">
                                    <span style="font-size: 0.9rem; font-weight: 600; color: #475569;">{{ $label }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 40px; border-top: 1px solid #f1f5f9; padding-top: 30px; display: flex; justify-content: flex-end; gap: 15px;">
                        <button type="button" class="btn" style="background: #f1f5f9; color: #475569; padding: 12px 30px; border-radius: 12px; font-weight: 700;" onclick="closeModal('newUserModal')">Cancelar</button>
                        <button type="submit" class="btn btn-primary" style="background: #10b981; padding: 12px 35px; border-radius: 12px; font-weight: 800; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);">
                            <i class="fas fa-check-circle" style="margin-right: 8px;"></i> Finalizar y Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Usuario -->
    <div id="editUserModal" class="modal">
        <div class="modal-content" style="max-width: 850px; padding: 0; overflow: hidden; border: none; border-radius: 20px;">
            <div class="modal-header" style="background: #1e293b; color: white; padding: 20px 30px; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="background: rgba(245, 158, 11, 0.2); width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user-edit" style="color: #f59e0b; font-size: 1.2rem;"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 1.25rem; font-weight: 700; color: #ffffff;">Editar Usuario</h3>
                </div>
                <span class="close-modal" onclick="closeModal('editUserModal')" style="background: rgba(255,255,255,0.1); width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; cursor: pointer;">&times;</span>
            </div>
            <div class="modal-body" style="padding: 40px;">
                <form id="editUserForm" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div style="margin-bottom: 35px;">
                        <h4 style="display: flex; align-items: center; gap: 12px; color: #1e293b; margin-bottom: 20px; font-weight: 700;">
                            <div style="background: #f59e0b; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem;">
                                <i class="fas fa-user"></i>
                            </div>
                            Perfil de Usuario
                        </h4>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label class="form-label" style="color: #475569; font-weight: 600;">Nombre Completo</label>
                                <div style="position: relative;">
                                    <i class="fas fa-id-card" style="position: absolute; left: 18px; top: 16px; color: #94a3b8;"></i>
                                    <input type="text" name="name" id="edit-user-name" class="form-control" style="padding-left: 50px; border-radius: 12px;" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label" style="color: #475569; font-weight: 600;">Correo Electrónico</label>
                                <div style="position: relative;">
                                    <i class="fas fa-envelope-open-text" style="position: absolute; left: 18px; top: 16px; color: #94a3b8;"></i>
                                    <input type="email" name="email" id="edit-user-email" class="form-control" style="padding-left: 50px; border-radius: 12px;" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label" style="color: #475569; font-weight: 600;">Rol de Sistema</label>
                                <div style="position: relative;">
                                    <i class="fas fa-user-tag" style="position: absolute; left: 18px; top: 16px; color: #94a3b8;"></i>
                                    <select name="role" id="edit-user-role" class="form-control" style="padding-left: 50px; border-radius: 12px;" required>
                                        <option value="admin">Administrador</option>
                                        <option value="supervisor">Supervisor</option>
                                        <option value="ing_sistemas">Ing. Sistemas</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label" style="color: #475569; font-weight: 600;">Nueva Contraseña (Opcional)</label>
                                <div style="position: relative;">
                                    <i class="fas fa-lock" style="position: absolute; left: 18px; top: 16px; color: #94a3b8;"></i>
                                    <input type="password" name="password" class="form-control" style="padding-left: 50px; border-radius: 12px;" placeholder="Dejar en blanco para no cambiar">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 style="display: flex; align-items: center; gap: 12px; color: #1e293b; margin-bottom: 20px; font-weight: 700;">
                            <div style="background: #f59e0b; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem;">
                                <i class="fas fa-user-lock"></i>
                            </div>
                            Gestión de Permisos
                        </h4>
                        
                        <div style="background: #fffbeb; border-radius: 15px; border: 1.5px solid #fef3c7; padding: 25px;">
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 15px;">
                                @foreach($modules as $key => $label)
                                <label style="display: flex; align-items: center; gap: 10px; background: white; padding: 12px; border-radius: 10px; border: 1px solid #fde68a; cursor: pointer;">
                                    <input type="checkbox" name="permissions[]" id="edit-perm-{{ $key }}" value="{{ $key }}" class="edit-user-permission" style="width: 18px; height: 18px; accent-color: #f59e0b;">
                                    <span style="font-size: 0.9rem; font-weight: 600; color: #92400e;">{{ $label }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 40px; border-top: 1px solid #f1f5f9; padding-top: 30px; display: flex; justify-content: flex-end; gap: 15px;">
                        <button type="button" class="btn" style="background: #f1f5f9; color: #475569; padding: 12px 30px; border-radius: 12px; font-weight: 700;" onclick="closeModal('editUserModal')">Cancelar</button>
                        <button type="submit" class="btn btn-primary" style="background: #10b981; padding: 12px 35px; border-radius: 12px; font-weight: 800; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);">
                            <i class="fas fa-save" style="margin-right: 8px;"></i> Guardar Cambios
                        </button>
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
