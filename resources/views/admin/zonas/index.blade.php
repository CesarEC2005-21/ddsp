@extends('layouts.admin')
@section('content')
    <div class="page-header">
        <h2 class="page-title">Gestión de Zonas</h2>
        <button onclick="openModal('newZonaModal')" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Zona
        </button>
    </div>


    <div class="card" style="max-width: 800px; margin-bottom: 20px; padding: 20px; background: white; border-radius: 12px; box-shadow: var(--shadow-sm);">
        <form action="{{ route('admin.zonas.index') }}" method="GET">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Nombre de la Zona</label>
                    <input type="text" name="nombre" class="form-control" value="{{ request('nombre') }}" placeholder="Buscar...">
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
                    @if(request()->anyFilled(['nombre', 'estado']))
                        <a href="{{ route('admin.zonas.index') }}" class="btn" style="background: #f1f5f9; color: #475569;" title="Limpiar"><i class="fas fa-times"></i></a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <div class="card" style="max-width: 800px; margin-bottom: 20px;">
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre de la Zona</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($zonas as $zona)
                    <tr>
                        <td>{{ $zona->id }}</td>
                        <td style="font-weight: 500;">{{ $zona->nombre_zona }}</td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" {{ $zona->estado ? 'checked' : '' }} onchange="toggleStatusZona({{ $zona->id }})">
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td>
                            <button onclick="openEditModal({{ $zona }})" class="btn" style="background: #f3f4f6; color: #333; padding: 6px 10px;" title="Editar"><i class="fas fa-edit"></i></button>
                            <button onclick="confirmDelete({{ $zona->id }}, 'ZONA-{{ $zona->id }}', '{{ $zona->nombre_zona }}', '{{ route('admin.zonas.destroy', $zona->id) }}')" class="btn" style="background: #fee2e2; color: #ef4444; padding: 6px 10px;" title="Eliminar"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="padding: 30px; text-align: center; color: #888;">No hay zonas registradas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="max-width: 800px; display: flex; justify-content: center;">
        {{ $zonas->appends(request()->query())->links('partials.pagination') }}
    </div>

    <!-- Modal Nueva Zona -->
    <div id="newZonaModal" class="modal">
        <div class="modal-content" style="max-width: 500px; padding: 0; max-height: 90vh; display: flex; flex-direction: column; overflow: hidden; border: none; border-radius: 20px;">
            <div class="modal-header" style="background: #1e293b; color: white; padding: 20px 30px; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="background: rgba(16, 185, 129, 0.2); width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-map-marked-alt" style="color: #10b981; font-size: 1.2rem;"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 1.25rem; font-weight: 700; color: #ffffff;">Registrar Nueva Zona</h3>
                </div>
                <span class="close-modal" onclick="closeModal('newZonaModal')" style="background: rgba(255,255,255,0.1); width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; cursor: pointer;">&times;</span>
            </div>
            <div class="modal-body" style="padding: 40px; overflow-y: auto; flex: 1;">
                <form action="{{ route('admin.zonas.store') }}" method="POST">
                    @csrf
                    
                    <div style="margin-bottom: 25px;">
                        <h4 style="display: flex; align-items: center; gap: 12px; color: #1e293b; margin-bottom: 8px; font-weight: 700;">
                            <div style="background: #10b981; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem;">
                                <i class="fas fa-info"></i>
                            </div>
                            Ubicación Geográfica
                        </h4>
                        <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 25px;">Defina el nombre de la zona para el personal.</p>
                        
                        <div class="form-group">
                            <label class="form-label" style="color: #475569; font-weight: 600;">Nombre de la Zona</label>
                            <div style="position: relative;">
                                <i class="fas fa-map-marker-alt" style="position: absolute; left: 18px; top: 16px; color: #94a3b8;"></i>
                                <input type="text" name="nombre_zona" class="form-control" style="padding: 14px 14px 14px 50px; border-radius: 12px; border: 1.5px solid #e2e8f0; background: #fcfcfc;" placeholder="Ej. Lima Metropolitana / Provincias" required>
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 20px;">
                            <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; background: #f0fdf4; padding: 15px; border-radius: 12px; border: 1.5px solid #dcfce7;">
                                <input type="checkbox" name="estado" value="1" checked style="width: 20px; height: 20px; accent-color: #10b981;">
                                <span style="font-weight: 700; color: #166534;">Zona Activa</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 25px 40px; background: white; border-top: 1px solid #f1f5f9;">
                    <button type="button" class="btn" style="background: #f1f5f9; color: #64748b; font-weight: 600; padding: 12px 25px;" onclick="closeModal('newZonaModal')">Cancelar</button>
                    <button type="submit" class="btn btn-primary" style="padding: 12px 40px; font-weight: 700; box-shadow: 0 4px 12px rgba(46, 125, 50, 0.2);">
                        <i class="fas fa-check-circle"></i> Finalizar y Guardar
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Zona -->
    <div id="editZonaModal" class="modal">
        <div class="modal-content" style="max-width: 500px; padding: 0; max-height: 90vh; display: flex; flex-direction: column; overflow: hidden; border: none; border-radius: 20px;">
            <div class="modal-header" style="background: #1e293b; color: white; padding: 20px 30px; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="background: rgba(245, 158, 11, 0.2); width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-edit" style="color: #f59e0b; font-size: 1.2rem;"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 1.25rem; font-weight: 700; color: #ffffff;">Editar Zona</h3>
                </div>
                <span class="close-modal" onclick="closeModal('editZonaModal')" style="background: rgba(255,255,255,0.1); width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; cursor: pointer;">&times;</span>
            </div>
            <div class="modal-body" style="padding: 40px; overflow-y: auto; flex: 1;">
                <form id="editZonaForm" method="POST">
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
                            <label class="form-label" style="color: #475569; font-weight: 600;">Nombre de la Zona</label>
                            <div style="position: relative;">
                                <i class="fas fa-map-marker-alt" style="position: absolute; left: 18px; top: 16px; color: #94a3b8;"></i>
                                <input type="text" name="nombre_zona" id="edit-nombre_zona" class="form-control" style="padding: 14px 14px 14px 50px; border-radius: 12px; border: 1.5px solid #e2e8f0;" required>
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 20px;">
                            <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; background: #fdfaf3; padding: 15px; border-radius: 12px; border: 1.5px solid #fef3c7;">
                                <input type="checkbox" name="estado" id="edit-zona-estado" value="1" style="width: 20px; height: 20px; accent-color: #f59e0b;">
                                <span style="font-weight: 700; color: #92400e;">Zona Activa</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 25px 40px; background: white; border-top: 1px solid #f1f5f9;">
                    <button type="button" class="btn" style="background: #f1f5f9; color: #64748b; font-weight: 600; padding: 12px 25px;" onclick="closeModal('editZonaModal')">Cancelar</button>
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
    function openEditModal(zona) {
        const form = document.getElementById('editZonaForm');
        form.action = `/admin/zonas/${zona.id}`;
        document.getElementById('edit-nombre_zona').value = zona.nombre_zona;
        document.getElementById('edit-zona-estado').checked = !!zona.estado;
        openModal('editZonaModal');
    }
    function toggleStatusZona(id) {
        console.log('Toggling status for zona:', id);
    }
</script>
@endpush
