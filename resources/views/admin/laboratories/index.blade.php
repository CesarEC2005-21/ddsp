@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <h2 class="page-title">Gestión de Laboratorios</h2>
        <button onclick="openModal('newLabModal')" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Laboratorio
        </button>
    </div>

    @if(session('success'))
        <div style="background: #D1FAE5; color: #065F46; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

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
    </div>

    <!-- Modal Nuevo Laboratorio -->
    <div id="newLabModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-flask"></i> Nuevo Laboratorio</h3>
                <span class="close-modal" onclick="closeModal('newLabModal')">&times;</span>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.laboratories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Logo / Imagen</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nombre / Descripción</label>
                        <input type="text" name="descripcion" class="form-control" placeholder="Ej. Bayer" required>
                    </div>
                    <div style="margin-top: 20px; display: flex; justify-content: flex-end; gap: 10px;">
                        <button type="button" class="btn" style="background: #e5e7eb;" onclick="closeModal('newLabModal')">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Laboratorio</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Laboratorio -->
    <div id="editLabModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-edit"></i> Editar Laboratorio</h3>
                <span class="close-modal" onclick="closeModal('editLabModal')">&times;</span>
            </div>
            <div class="modal-body">
                <form id="editLabForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label class="form-label">Actualizar Logo</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nombre / Descripción</label>
                        <input type="text" name="descripcion" id="edit-lab-descripcion" class="form-control" required>
                    </div>
                    <div style="margin-top: 20px; display: flex; justify-content: flex-end; gap: 10px;">
                        <button type="button" class="btn" style="background: #e5e7eb;" onclick="closeModal('editLabModal')">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar Cambios</button>
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
        openModal('editLabModal');
    }

    function toggleStatusLab(id) {
        console.log('Toggling status for lab:', id);
    }
</script>
@endpush
