@extends('layouts.admin')

@section('content')
<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h2 style="font-family: 'Poppins', sans-serif; font-weight: 700; color: var(--text);">
            <i class="fas fa-certificate" style="color: var(--primary); margin-right: 10px;"></i>
            Certificaciones
        </h2>
        <p style="color: var(--text-muted);">Gestiona los certificados de calidad de la empresa.</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('createModal')">
        <i class="fas fa-plus"></i> Nuevo Certificado
    </button>
</div>

<!-- Barra de Búsqueda -->
<div class="card" style="margin-bottom: 20px; padding: 20px; background: white; border-radius: 12px; box-shadow: var(--shadow-sm);">
    <form action="{{ route('admin.certificados.index') }}" method="GET">
        <div style="display: flex; gap: 15px; align-items: flex-end;">
            <div class="form-group" style="margin-bottom: 0; flex-grow: 1;">
                <label class="form-label" style="font-size: 0.85rem;">Buscar Certificado</label>
                <input type="text" name="nombre" class="form-control" placeholder="Buscar por nombre..." value="{{ request('nombre') }}">
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
                @if(request()->anyFilled(['nombre']))
                    <a href="{{ route('admin.certificados.index') }}" class="btn" style="background: #f1f5f9; color: #475569;" title="Limpiar"><i class="fas fa-times"></i></a>
                @endif
            </div>
        </div>
    </form>
</div>

<!-- Tabla de Resultados -->
<div class="card">
    <div class="table-container">
        <table class="admin-table">
            <thead>
            <tr>
                <th width="100" style="text-align: center;">Imagen</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th width="120" style="text-align: center;">Estado</th>
                <th width="120" style="text-align: center;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($certificados as $cert)
                <tr>
                    <td style="text-align: center;">
                        @if($cert->imagen)
                            <div style="width: 65px; height: 65px; margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                                <img src="{{ asset('storage/' . $cert->imagen) }}" alt="{{ $cert->nombre }}" style="max-width: 100%; max-height: 100%; object-fit: contain; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));">
                            </div>
                        @else
                            <div style="width: 50px; height: 50px; margin: 0 auto; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #94a3b8;">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                    </td>
                    <td class="fw-bold">{{ $cert->nombre }}</td>
                    <td style="color: var(--text-muted); font-size: 0.95rem;">
                        {{ Str::limit($cert->descripcion, 70) }}
                    </td>
                    <td style="text-align: center;">
                        @if($cert->activo)
                            <span class="badge badge-success">Activo</span>
                        @else
                            <span class="badge badge-danger">Inactivo</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <button class="btn-action btn-edit" onclick="openEditModal({{ $cert->id }}, '{{ addslashes($cert->nombre) }}', '{{ addslashes($cert->descripcion) }}', {{ $cert->activo ? 'true' : 'false' }})" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-action btn-delete" onclick="confirmDelete({{ $cert->id }}, '{{ addslashes($cert->nombre) }}', '', '{{ route('admin.certificados.destroy', $cert) }}')" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px;">
                        <div style="color: #94a3b8; font-size: 3rem; margin-bottom: 15px;"><i class="fas fa-folder-open"></i></div>
                        <p style="color: var(--text-muted);">No se encontraron certificados.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
    <div style="padding: 20px; border-top: 1px solid #eee; display: flex; justify-content: center;">
        {{ $certificados->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>

<!-- Modal Crear -->
<div id="createModal" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <h3><i class="fas fa-plus"></i> Nuevo Certificado</h3>
            <button class="close-modal" onclick="closeModal('createModal')">&times;</button>
        </div>
        <form action="{{ route('admin.certificados.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Nombre del Certificado <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Imagen</label>
                    <input type="file" name="imagen" class="form-control" accept="image/*">
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="activo" value="1" checked>
                        <span>Certificado Activo</span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('createModal')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar -->
<div id="editModal" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <h3><i class="fas fa-edit"></i> Editar Certificado</h3>
            <button class="close-modal" onclick="closeModal('editModal')">&times;</button>
        </div>
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Nombre del Certificado <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Descripción</label>
                    <textarea name="descripcion" id="edit_descripcion" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Actualizar Imagen (Opcional)</label>
                    <input type="file" name="imagen" class="form-control" accept="image/*">
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="activo" id="edit_activo" value="1">
                        <span>Certificado Activo</span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editModal')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openEditModal(id, nombre, descripcion, activo) {
    const form = document.getElementById('editForm');
    form.action = `/admin/certificados/${id}`;
    
    document.getElementById('edit_nombre').value = nombre;
    document.getElementById('edit_descripcion').value = descripcion;
    document.getElementById('edit_activo').checked = activo;
    
    openModal('editModal');
}
</script>
@endpush
