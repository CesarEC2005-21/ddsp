@extends('layouts.admin')
@section('content')
    <div class="page-header">
        <h2 class="page-title">Gestión de Zonas</h2>
        <button onclick="openModal('newZonaModal')" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Zona
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

    <!-- Modal Nueva Zona -->
    <div id="newZonaModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-map-signs"></i> Nueva Zona</h3>
                <span class="close-modal" onclick="closeModal('newZonaModal')">&times;</span>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.zonas.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Nombre de la Zona</label>
                        <input type="text" name="nombre_zona" class="form-control" placeholder="Ej. Lima Norte" required>
                    </div>
                    <div style="margin-top: 20px; display: flex; justify-content: flex-end; gap: 10px;">
                        <button type="button" class="btn" style="background: #e5e7eb;" onclick="closeModal('newZonaModal')">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Zona</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Zona -->
    <div id="editZonaModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-edit"></i> Editar Zona</h3>
                <span class="close-modal" onclick="closeModal('editZonaModal')">&times;</span>
            </div>
            <div class="modal-body">
                <form id="editZonaForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label class="form-label">Nombre de la Zona</label>
                        <input type="text" name="nombre_zona" id="edit-nombre_zona" class="form-control" required>
                    </div>
                    <div style="margin-top: 20px; display: flex; justify-content: flex-end; gap: 10px;">
                        <button type="button" class="btn" style="background: #e5e7eb;" onclick="closeModal('editZonaModal')">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar Zona</button>
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
        openModal('editZonaModal');
    }
    function toggleStatusZona(id) {
        console.log('Toggling status for zona:', id);
    }
</script>
@endpush
