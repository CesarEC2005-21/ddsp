@extends('layouts.admin')
@section('content')
    <div class="page-header">
        <h2 class="page-title">Gestión de Unidades de Medida</h2>
        <button onclick="openModal('newUMModal')" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva U.M.
        </button>
    </div>


    <div class="card" style="max-width: 800px; margin-bottom: 20px; padding: 20px; background: white; border-radius: 12px; box-shadow: var(--shadow-sm);">
        <form action="{{ route('admin.unidad-medidas.index') }}" method="GET">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Unidad de Medida (U.M.)</label>
                    <input type="text" name="um" class="form-control" value="{{ request('um') }}" placeholder="Buscar unidad...">
                </div>
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;"><i class="fas fa-search"></i> Filtrar</button>
                    @if(request()->filled('um'))
                        <a href="{{ route('admin.unidad-medidas.index') }}" class="btn" style="background: #f1f5f9; color: #475569;" title="Limpiar"><i class="fas fa-times"></i></a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <div class="card" style="max-width: 800px;">
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Unidad de Medida</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($unidadMedidas as $um)
                    <tr>
                        <td>{{ $um->id }}</td>
                        <td style="font-weight: 500;">{{ $um->um }}</td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" {{ $um->estado ? 'checked' : '' }} onchange="toggleStatusUM({{ $um->id }})">
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td>
                            <button onclick="openEditModal({{ $um }})" class="btn" style="background: #f3f4f6; color: #333; padding: 6px 10px;" title="Editar"><i class="fas fa-edit"></i></button>
                            <button onclick="confirmDelete({{ $um->id }}, 'UM-{{ $um->id }}', '{{ $um->um }}', '{{ route('admin.unidad-medidas.destroy', $um->id) }}')" class="btn" style="background: #fee2e2; color: #ef4444; padding: 6px 10px;" title="Eliminar"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="padding: 30px; text-align: center; color: #888;">No hay unidades de medida registradas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding: 20px; border-top: 1px solid #eee; display: flex; justify-content: center;">
            {{ $unidadMedidas->appends(request()->query())->links('partials.pagination') }}
        </div>
    </div>

    <!-- Modal Nueva UM -->
    <div id="newUMModal" class="modal">
        <div class="modal-content" style="max-width: 500px; padding: 0; max-height: 90vh; display: flex; flex-direction: column; overflow: hidden; border: none; border-radius: 20px;">
            <div class="modal-header" style="background: #1e293b; color: white; padding: 20px 30px; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="background: rgba(16, 185, 129, 0.2); width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-ruler-combined" style="color: #10b981; font-size: 1.2rem;"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 1.25rem; font-weight: 700; color: #ffffff;">Registrar Nueva Unidad</h3>
                </div>
                <span class="close-modal" onclick="closeModal('newUMModal')" style="background: rgba(255,255,255,0.1); width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; cursor: pointer;">&times;</span>
            </div>
            <div class="modal-body" style="padding: 40px; overflow-y: auto; flex: 1;">
                <form action="{{ route('admin.unidad-medidas.store') }}" method="POST">
                    @csrf
                    
                    <div style="margin-bottom: 25px;">
                        <h4 style="display: flex; align-items: center; gap: 12px; color: #1e293b; margin-bottom: 8px; font-weight: 700;">
                            <div style="background: #10b981; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem;">
                                <i class="fas fa-info"></i>
                            </div>
                            Detalle de la Unidad
                        </h4>
                        <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 25px;">Especifique el formato de medida del producto.</p>
                        
                        <div class="form-group">
                            <label class="form-label" style="color: #475569; font-weight: 600;">Nombre de la Unidad (U.M.)</label>
                            <div style="position: relative;">
                                <i class="fas fa-box" style="position: absolute; left: 18px; top: 16px; color: #94a3b8;"></i>
                                <input type="text" name="um" class="form-control" style="padding: 14px 14px 14px 50px; border-radius: 12px; border: 1.5px solid #e2e8f0; background: #fcfcfc;" placeholder="Ej. Frasco x 100ml / Caja x 30 Tab." required>
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 20px;">
                            <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; background: #f0fdf4; padding: 15px; border-radius: 12px; border: 1.5px solid #dcfce7;">
                                <input type="checkbox" name="estado" value="1" checked style="width: 20px; height: 20px; accent-color: #10b981;">
                                <span style="font-weight: 700; color: #166534;">Unidad Activa</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 25px 40px; background: white; border-top: 1px solid #f1f5f9;">
                    <button type="button" class="btn" style="background: #f1f5f9; color: #64748b; font-weight: 600; padding: 12px 25px;" onclick="closeModal('newUMModal')">Cancelar</button>
                    <button type="submit" class="btn btn-primary" style="padding: 12px 40px; font-weight: 700; box-shadow: 0 4px 12px rgba(46, 125, 50, 0.2);">
                        <i class="fas fa-check-circle"></i> Finalizar y Guardar
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar UM -->
    <div id="editUMModal" class="modal">
        <div class="modal-content" style="max-width: 500px; padding: 0; max-height: 90vh; display: flex; flex-direction: column; overflow: hidden; border: none; border-radius: 20px;">
            <div class="modal-header" style="background: #1e293b; color: white; padding: 20px 30px; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="background: rgba(245, 158, 11, 0.2); width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-edit" style="color: #f59e0b; font-size: 1.2rem;"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 1.25rem; font-weight: 700; color: #ffffff;">Editar Unidad</h3>
                </div>
                <span class="close-modal" onclick="closeModal('editUMModal')" style="background: rgba(255,255,255,0.1); width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; cursor: pointer;">&times;</span>
            </div>
            <div class="modal-body" style="padding: 40px; overflow-y: auto; flex: 1;">
                <form id="editUMForm" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div style="margin-bottom: 25px;">
                        <h4 style="display: flex; align-items: center; gap: 12px; color: #1e293b; margin-bottom: 8px; font-weight: 700;">
                            <div style="background: #f59e0b; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem;">
                                <i class="fas fa-info"></i>
                            </div>
                            Actualizar Información
                        </h4>
                        
                        <div class="form-group">
                            <label class="form-label" style="color: #475569; font-weight: 600;">Nombre de la Unidad (U.M.)</label>
                            <div style="position: relative;">
                                <i class="fas fa-box" style="position: absolute; left: 18px; top: 16px; color: #94a3b8;"></i>
                                <input type="text" name="um" id="edit-um" class="form-control" style="padding: 14px 14px 14px 50px; border-radius: 12px; border: 1.5px solid #e2e8f0;" required>
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 20px;">
                            <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; background: #fdfaf3; padding: 15px; border-radius: 12px; border: 1.5px solid #fef3c7;">
                                <input type="checkbox" name="estado" id="edit-um-estado" value="1" style="width: 20px; height: 20px; accent-color: #f59e0b;">
                                <span style="font-weight: 700; color: #92400e;">Unidad Activa</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 25px 40px; background: white; border-top: 1px solid #f1f5f9;">
                    <button type="button" class="btn" style="background: #f1f5f9; color: #64748b; font-weight: 600; padding: 12px 25px;" onclick="closeModal('editUMModal')">Cancelar</button>
                    <button type="submit" class="btn btn-primary" style="padding: 12px 40px; font-weight: 700; box-shadow: 0 4px 12px rgba(46, 125, 50, 0.2);">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openEditModal(um) {
        const form = document.getElementById('editUMForm');
        form.action = `/admin/unidad-medidas/${um.id}`;
        document.getElementById('edit-um').value = um.um;
        document.getElementById('edit-um-estado').checked = !!um.estado;
        openModal('editUMModal');
    }
    function toggleStatusUM(id) {
        console.log('Toggling status for UM:', id);
    }
</script>
@endpush
