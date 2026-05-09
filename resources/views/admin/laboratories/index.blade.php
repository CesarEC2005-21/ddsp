@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <h2 class="page-title">Gestión de Laboratorios</h2>
        <button onclick="openModal('newLabModal')" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Laboratorio
        </button>
    </div>


    <div class="card" style="margin-bottom: 20px; padding: 20px; background: white; border-radius: 12px; box-shadow: var(--shadow-sm);">
        <form action="{{ route('admin.laboratories.index') }}" method="GET">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; align-items: end;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Código</label>
                    <input type="text" name="codigo" class="form-control" value="{{ request('codigo') }}" placeholder="Ej. LAB-001">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Descripción</label>
                    <input type="text" name="descripcion" class="form-control" value="{{ request('descripcion') }}" placeholder="Buscar...">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Destacado (TOP)</label>
                    <select name="is_top" class="form-control">
                        <option value="">Todos</option>
                        <option value="1" {{ request('is_top') === '1' ? 'selected' : '' }}>Sí</option>
                        <option value="0" {{ request('is_top') === '0' ? 'selected' : '' }}>No</option>
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
                    @if(request()->anyFilled(['codigo', 'descripcion', 'is_top', 'estado']))
                        <a href="{{ route('admin.laboratories.index') }}" class="btn" style="background: #f1f5f9; color: #475569;" title="Limpiar"><i class="fas fa-times"></i></a>
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
                        <th>Logo</th>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Destacado (TOP)</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laboratories as $lab)
                    <tr>
                        <td>
                            @if($lab->logo)
                                <img src="{{ asset('storage/' . $lab->logo) }}" style="width: 40px; height: 40px; object-fit: contain; background: white; padding: 3px; border: 1px solid #eee; border-radius: 5px;">
                            @else
                                <div style="width: 40px; height: 40px; background: #f8fafc; display: flex; align-items: center; justify-content: center; border-radius: 5px; color: #cbd5e1;"><i class="fas fa-image"></i></div>
                            @endif
                        </td>
                        <td><span class="badge badge-success">{{ $lab->codigo }}</span></td>
                        <td style="font-weight: 500;">{{ $lab->descripcion }}</td>
                        <td>
                            <form action="{{ route('admin.laboratories.toggle-top', $lab->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn" style="background: {{ $lab->is_top ? '#D1FAE5' : '#F3F4F6' }}; color: {{ $lab->is_top ? '#065F46' : '#888' }}; padding: 6px 12px; font-size: 0.85rem; border: 1px solid {{ $lab->is_top ? '#34D399' : '#ddd' }}; border-radius: 50px;">
                                    <i class="fa{{ $lab->is_top ? 's' : 'r' }} fa-star"></i> {{ $lab->is_top ? 'Destacado' : 'Normal' }}
                                </button>
                            </form>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" {{ $lab->estado ? 'checked' : '' }} onchange="toggleStatusLab({{ $lab->id }})">
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td>
                            <button onclick="openEditModal({{ $lab }})" class="btn" style="background: #f3f4f6; color: #333; padding: 6px 10px;" title="Editar"><i class="fas fa-edit"></i></button>
                            <button onclick="confirmDelete({{ $lab->id }}, '{{ $lab->codigo }}', '{{ $lab->descripcion }}', '{{ route('admin.laboratories.destroy', $lab->id) }}')" class="btn" style="background: #fee2e2; color: #ef4444; padding: 6px 10px;" title="Eliminar"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding: 30px; text-align: center; color: #888;">No hay laboratorios registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding: 20px; border-top: 1px solid #eee; display: flex; justify-content: center;">
            {{ $laboratories->appends(request()->query())->links('partials.pagination') }}
        </div>
    </div>

    <!-- Modal Nuevo Laboratorio -->
    <div id="newLabModal" class="modal">
        <div class="modal-content" style="max-width: 500px; padding: 0; overflow: hidden; border: none; border-radius: 20px;">
            <div class="modal-header" style="background: #1e293b; color: white; padding: 20px 30px; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="background: rgba(16, 185, 129, 0.2); width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-flask" style="color: #10b981; font-size: 1.2rem;"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 1.25rem; font-weight: 700; color: #ffffff;">Registrar Nuevo Laboratorio</h3>
                </div>
                <span class="close-modal" onclick="closeModal('newLabModal')" style="background: rgba(255,255,255,0.1); width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; cursor: pointer;">&times;</span>
            </div>
            <div class="modal-body" style="padding: 40px;">
                <form action="{{ route('admin.laboratories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div style="margin-bottom: 25px;">
                        <h4 style="display: flex; align-items: center; gap: 12px; color: #1e293b; margin-bottom: 8px; font-weight: 700;">
                            <div style="background: #10b981; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem;">
                                <i class="fas fa-info"></i>
                            </div>
                            Identidad del Laboratorio
                        </h4>
                        
                        <div class="form-group" style="margin-top: 20px;">
                            <label class="form-label" style="color: #475569; font-weight: 600;">Logo Corporativo</label>
                            <div style="background: #f8fafc; padding: 25px; border-radius: 15px; border: 2px dashed #e2e8f0; text-align: center; margin-bottom: 10px;">
                                <i class="fas fa-cloud-upload-alt" style="font-size: 2.5rem; color: #cbd5e1; display: block; margin-bottom: 10px;"></i>
                                <input type="file" name="logo" class="form-control" accept="image/*" style="border: none; background: transparent; text-align: center;">
                            </div>
                            <small style="color: #64748b;">Formatos recomendados: PNG, JPG (Máx 2MB).</small>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" style="color: #475569; font-weight: 600;">Nombre del Laboratorio</label>
                            <div style="position: relative;">
                                <i class="fas fa-building" style="position: absolute; left: 18px; top: 16px; color: #94a3b8;"></i>
                                <input type="text" name="descripcion" class="form-control" style="padding: 14px 14px 14px 50px; border-radius: 12px; border: 1.5px solid #e2e8f0; background: #fcfcfc;" placeholder="Ej. Laboratorios Pfizer S.A." required>
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 20px;">
                            <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; background: #f0fdf4; padding: 15px; border-radius: 12px; border: 1.5px solid #dcfce7;">
                                <input type="checkbox" name="estado" value="1" checked style="width: 20px; height: 20px; accent-color: #10b981;">
                                <span style="font-weight: 700; color: #166534;">Laboratorio Activo</span>
                            </label>
                        </div>
                    </div>

                    <div style="margin-top: 40px; display: flex; justify-content: flex-end; gap: 15px;">
                        <button type="button" class="btn" style="background: #f1f5f9; color: #475569; padding: 12px 30px; border-radius: 12px; font-weight: 700;" onclick="closeModal('newLabModal')">Cancelar</button>
                        <button type="submit" class="btn btn-primary" style="background: #10b981; padding: 12px 35px; border-radius: 12px; font-weight: 800; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);">
                            <i class="fas fa-check-circle" style="margin-right: 8px;"></i> Finalizar y Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Laboratorio -->
    <div id="editLabModal" class="modal">
        <div class="modal-content" style="max-width: 500px; padding: 0; overflow: hidden; border: none; border-radius: 20px;">
            <div class="modal-header" style="background: #1e293b; color: white; padding: 20px 30px; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="background: rgba(245, 158, 11, 0.2); width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-edit" style="color: #f59e0b; font-size: 1.2rem;"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 1.25rem; font-weight: 700; color: #ffffff;">Editar Laboratorio</h3>
                </div>
                <span class="close-modal" onclick="closeModal('editLabModal')" style="background: rgba(255,255,255,0.1); width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; cursor: pointer;">&times;</span>
            </div>
            <div class="modal-body" style="padding: 40px;">
                <form id="editLabForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div style="margin-bottom: 25px;">
                        <h4 style="display: flex; align-items: center; gap: 12px; color: #1e293b; margin-bottom: 8px; font-weight: 700;">
                            <div style="background: #f59e0b; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem;">
                                <i class="fas fa-info"></i>
                            </div>
                            Actualizar Información
                        </h4>
                        
                        <div class="form-group" style="margin-top: 20px;">
                            <label class="form-label" style="color: #475569; font-weight: 600;">Cambiar Logo</label>
                            <div style="background: #f8fafc; padding: 15px; border-radius: 12px; border: 1.5px solid #e2e8f0; display: flex; align-items: center;">
                                <input type="file" name="logo" class="form-control" accept="image/*" style="border: none; background: transparent;">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" style="color: #475569; font-weight: 600;">Nombre del Laboratorio</label>
                            <div style="position: relative;">
                                <i class="fas fa-building" style="position: absolute; left: 18px; top: 16px; color: #94a3b8;"></i>
                                <input type="text" name="descripcion" id="edit-lab-descripcion" class="form-control" style="padding: 14px 14px 14px 50px; border-radius: 12px; border: 1.5px solid #e2e8f0;" required>
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 20px;">
                            <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; background: #fdfaf3; padding: 15px; border-radius: 12px; border: 1.5px solid #fef3c7;">
                                <input type="checkbox" name="estado" id="edit-lab-estado" value="1" style="width: 20px; height: 20px; accent-color: #f59e0b;">
                                <span style="font-weight: 700; color: #92400e;">Laboratorio Activo</span>
                            </label>
                        </div>
                    </div>

                    <div style="margin-top: 40px; display: flex; justify-content: flex-end; gap: 15px;">
                        <button type="button" class="btn" style="background: #f1f5f9; color: #475569; padding: 12px 30px; border-radius: 12px; font-weight: 700;" onclick="closeModal('editLabModal')">Cancelar</button>
                        <button type="submit" class="btn btn-primary" style="background: #10b981; padding: 12px 35px; border-radius: 12px; font-weight: 800; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);">
                            <i class="fas fa-save" style="margin-right: 8px;"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openEditModal(lab) {
        const form = document.getElementById('editLabForm');
        form.action = `/admin/laboratories/${lab.id}`;
        document.getElementById('edit-lab-descripcion').value = lab.descripcion;
        document.getElementById('edit-lab-estado').checked = !!lab.estado;
        openModal('editLabModal');
    }

    function toggleStatusLab(id) {
        console.log('Toggling status for lab:', id);
    }
</script>
@endpush
