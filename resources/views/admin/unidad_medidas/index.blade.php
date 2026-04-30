@extends('layouts.admin')
@section('content')
    <div class="page-header">
        <h2 class="page-title">Gestión de Unidades de Medida</h2>
        <button onclick="openModal('newUMModal')" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva U.M.
        </button>
    </div>

    @if(session('success'))
        <div style="background: #D1FAE5; color: #065F46; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

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
    </div>

    <!-- Modal Nueva UM -->
    <div id="newUMModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-ruler-combined"></i> Nueva Unidad de Medida</h3>
                <span class="close-modal" onclick="closeModal('newUMModal')">&times;</span>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.unidad-medidas.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Unidad de Medida (U.M.)</label>
                        <input type="text" name="um" class="form-control" placeholder="Ej. Frasco x 100ml" required>
                    </div>
                    <div style="margin-top: 20px; display: flex; justify-content: flex-end; gap: 10px;">
                        <button type="button" class="btn" style="background: #e5e7eb;" onclick="closeModal('newUMModal')">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar U.M.</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar UM -->
    <div id="editUMModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-edit"></i> Editar Unidad de Medida</h3>
                <span class="close-modal" onclick="closeModal('editUMModal')">&times;</span>
            </div>
            <div class="modal-body">
                <form id="editUMForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label class="form-label">Unidad de Medida (U.M.)</label>
                        <input type="text" name="um" id="edit-um" class="form-control" required>
                    </div>
                    <div style="margin-top: 20px; display: flex; justify-content: flex-end; gap: 10px;">
                        <button type="button" class="btn" style="background: #e5e7eb;" onclick="closeModal('editUMModal')">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar U.M.</button>
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
        openModal('editUMModal');
    }
    function toggleStatusUM(id) {
        console.log('Toggling status for UM:', id);
    }
</script>
@endpush
