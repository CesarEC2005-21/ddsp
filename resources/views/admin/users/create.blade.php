@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <a href="{{ route('admin.users.index') }}" style="color: var(--text-muted); text-decoration: none; margin-bottom: 10px; display: inline-block;">
                <i class="fas fa-arrow-left"></i> Volver a usuarios
            </a>
            <h2 class="page-title">Crear Nuevo Usuario</h2>
        </div>
    </div>

    <div class="card" style="max-width: 800px;">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nombre Completo *</label>
                    <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                    @error('name') <span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Correo Electrónico *</label>
                    <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                    @error('email') <span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Contraseña *</label>
                    <input type="password" name="password" class="form-control" required minlength="8">
                    @error('password') <span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Rol de Acceso *</label>
                    <select name="role" class="form-control" required>
                        <option value="">Seleccione un rol...</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                        <option value="supervisor" {{ old('role') == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                        <option value="ing_sistemas" {{ old('role') == 'ing_sistemas' ? 'selected' : '' }}>Ingeniero de Sistemas</option>
                    </select>
                    @error('role') <span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border-color);">
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-save"></i> Guardar Usuario
                </button>
            </div>
        </form>
    </div>
@endsection
