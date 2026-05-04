@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map-new, #map-edit { height: 350px; border-radius: 12px; margin-bottom: 15px; border: 1px solid #e2e8f0; }
    .map-search-container {
        position: relative;
        margin-bottom: 10px;
        display: flex;
        gap: 8px;
    }
    .map-search-input {
        flex: 1;
        padding: 10px 15px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.9rem;
    }
    .map-search-btn {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 0 15px;
        border-radius: 8px;
        cursor: pointer;
        transition: var(--transition);
    }
    .map-search-btn:hover {
        background: var(--primary-hover);
    }

</style>
@endpush

@section('content')
    <div class="page-header">
        <h2 class="page-title">Gestión de Boticas</h2>
        <button onclick="openNewModal()" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Botica
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
                        <th>Nombre</th>
                        <th>Ubicación</th>
                        <th>Estado</th>
                        <th>Coordenadas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pharmacies as $pharmacy)
                    <tr>
                        <td style="font-weight: 500;">{{ $pharmacy->nombre }}</td>
                        <td>{{ $pharmacy->ubicacion }}</td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" {{ $pharmacy->estado ? 'checked' : '' }} onchange="toggleStatusPharmacy({{ $pharmacy->id }})">
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td style="color: #888; font-size: 0.85rem;">{{ $pharmacy->latitud }}, {{ $pharmacy->longitud }}</td>
                        <td>
                            <button onclick="openEditModal({{ $pharmacy }})" class="btn" style="background: #f3f4f6; color: #333; padding: 6px 10px;" title="Editar"><i class="fas fa-edit"></i></button>
                            <button onclick="confirmDelete({{ $pharmacy->id }}, 'BOT-{{ $pharmacy->id }}', '{{ $pharmacy->nombre }}', '{{ route('admin.pharmacies.destroy', $pharmacy->id) }}')" class="btn" style="background: #fee2e2; color: #ef4444; padding: 6px 10px;" title="Eliminar"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding: 30px; text-align: center; color: #888;">No hay boticas registradas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Nueva Botica -->
    <div id="newPharmacyModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-store"></i> Nueva Botica</h3>
                <span class="close-modal" onclick="closeModal('newPharmacyModal')">&times;</span>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.pharmacies.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Nombre de la Botica</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Ej. Botica Mi Salud" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Dirección / Ubicación</label>
                        <input type="text" name="ubicacion" class="form-control" placeholder="Ej. Av. Larco 123" required>
                    </div>
                    
                    <label class="form-label">Ubicación exacta en mapa:</label>
                    <div class="map-search-container">
                        <input type="text" id="search-new" class="map-search-input" placeholder="Buscar dirección o lugar...">
                        <button type="button" class="map-search-btn" onclick="searchLocation('new')">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <div id="map-new"></div>


                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Latitud</label>
                            <input type="text" name="latitud" id="lat-new" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Longitud</label>
                            <input type="text" name="longitud" id="lng-new" class="form-control" readonly>
                        </div>
                    </div>
                    <div style="margin-top: 20px; display: flex; justify-content: flex-end; gap: 10px;">
                        <button type="button" class="btn" style="background: #e5e7eb;" onclick="closeModal('newPharmacyModal')">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Botica</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Botica -->
    <div id="editPharmacyModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-edit"></i> Editar Botica</h3>
                <span class="close-modal" onclick="closeModal('editPharmacyModal')">&times;</span>
            </div>
            <div class="modal-body">
                <form id="editPharmacyForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label class="form-label">Nombre de la Botica</label>
                        <input type="text" name="nombre" id="edit-nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Dirección / Ubicación</label>
                        <input type="text" name="ubicacion" id="edit-ubicacion" class="form-control" required>
                    </div>

                    <label class="form-label">Actualizar ubicación en mapa:</label>
                    <div class="map-search-container">
                        <input type="text" id="search-edit" class="map-search-input" placeholder="Buscar dirección o lugar...">
                        <button type="button" class="map-search-btn" onclick="searchLocation('edit')">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <div id="map-edit"></div>


                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Latitud</label>
                            <input type="text" name="latitud" id="lat-edit" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Longitud</label>
                            <input type="text" name="longitud" id="lng-edit" class="form-control" readonly>
                        </div>
                    </div>
                    <div style="margin-top: 20px; display: flex; justify-content: flex-end; gap: 10px;">
                        <button type="button" class="btn" style="background: #e5e7eb;" onclick="closeModal('editPharmacyModal')">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar Botica</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let mapNew, mapEdit, markerNew, markerEdit;

    async function searchLocation(type) {
        const query = document.getElementById('search-' + type).value;
        if (!query) return;

        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`);
            const data = await response.json();

            if (data.length > 0) {
                const lat = parseFloat(data[0].lat);
                const lon = parseFloat(data[0].lon);
                const map = type === 'new' ? mapNew : mapEdit;
                const latInput = document.getElementById('lat-' + type);
                const lngInput = document.getElementById('lng-' + type);

                map.setView([lat, lon], 16);

                if (type === 'new') {
                    if (markerNew) markerNew.remove();
                    markerNew = L.marker([lat, lon]).addTo(mapNew);
                } else {
                    if (markerEdit) markerEdit.remove();
                    markerEdit = L.marker([lat, lon]).addTo(mapEdit);
                }

                latInput.value = lat.toFixed(8);
                lngInput.value = lon.toFixed(8);
            } else {
                alert('No se encontró la ubicación');
            }
        } catch (error) {
            console.error('Error al buscar ubicación:', error);
        }
    }


    function initMap(type, lat = -12.046374, lng = -77.042793) {
        const mapId = 'map-' + type;
        const latInput = document.getElementById('lat-' + type);
        const lngInput = document.getElementById('lng-' + type);

        if (type === 'new') {
            if (mapNew) mapNew.remove();
            mapNew = L.map(mapId).setView([lat, lng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapNew);
            mapNew.on('click', function(e) {
                if (markerNew) markerNew.remove();
                markerNew = L.marker(e.latlng).addTo(mapNew);
                latInput.value = e.latlng.lat.toFixed(8);
                lngInput.value = e.latlng.lng.toFixed(8);
            });
            setTimeout(() => mapNew.invalidateSize(), 200);
        } else {
            if (mapEdit) mapEdit.remove();
            mapEdit = L.map(mapId).setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapEdit);
            if (lat && lng) {
                markerEdit = L.marker([lat, lng]).addTo(mapEdit);
            }
            mapEdit.on('click', function(e) {
                if (markerEdit) markerEdit.remove();
                markerEdit = L.marker(e.latlng).addTo(mapEdit);
                latInput.value = e.latlng.lat.toFixed(8);
                lngInput.value = e.latlng.lng.toFixed(8);
            });
            setTimeout(() => mapEdit.invalidateSize(), 200);
        }
    }

    function openNewModal() {
        openModal('newPharmacyModal');
        initMap('new');
    }

    function openEditModal(pharmacy) {
        const form = document.getElementById('editPharmacyForm');
        form.action = `/admin/pharmacies/${pharmacy.id}`;
        document.getElementById('edit-nombre').value = pharmacy.nombre;
        document.getElementById('edit-ubicacion').value = pharmacy.ubicacion;
        document.getElementById('lat-edit').value = pharmacy.latitud;
        document.getElementById('lng-edit').value = pharmacy.longitud;
        
        openModal('editPharmacyModal');
        initMap('edit', pharmacy.latitud || -12.046374, pharmacy.longitud || -77.042793);
    }

    function toggleStatusPharmacy(id) {
        console.log('Toggling status for pharmacy:', id);
    }
</script>
@endpush
