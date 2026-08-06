@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<style>
    #map-new, #map-edit { height: 400px; border-radius: 12px; margin-bottom: 15px; border: 1px solid #e2e8f0; }
    .location-item { background: white; padding: 12px 15px; border-radius: 10px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    .location-list { max-height: 250px; overflow-y: auto; margin-top: 15px; border: 1px solid #f1f5f9; padding: 15px; border-radius: 12px; background: #f8fafc; }
    .search-box-map { margin-bottom: 10px; display: flex; gap: 10px; }
</style>
@endpush

@section('content')
    <div class="page-header">
        <h2 class="page-title">Gestión de Representantes de Ventas</h2>
        <button onclick="openNewModal()" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Representante
        </button>
    </div>

    <div class="card" style="margin-bottom: 20px; padding: 20px; background: white; border-radius: 12px; box-shadow: var(--shadow-sm);">
        <form action="{{ route('admin.representatives.index') }}" method="GET">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="{{ request('nombre') }}" placeholder="Buscar...">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Zona</label>
                    <select name="zona_id" class="form-control">
                        <option value="">Todas</option>
                        @foreach($zonas as $zona)
                            <option value="{{ $zona->id }}" {{ request('zona_id') == $zona->id ? 'selected' : '' }}>{{ $zona->nombre_zona }}</option>
                        @endforeach
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
                    @if(request()->anyFilled(['nombre', 'zona_id', 'estado']))
                        <a href="{{ route('admin.representatives.index') }}" class="btn" style="background: #f1f5f9; color: #475569;" title="Limpiar"><i class="fas fa-times"></i></a>
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
                        <th>Foto</th>
                        <th>Nombre</th>
                        <th>Contacto</th>
                        <th>Puntos / Zonas</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($representatives as $rep)
                    <tr>
                        <td>
                            @if($rep->imagen)
                                <img src="{{ asset('storage/' . $rep->imagen) }}" style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid #f1f5f9;">
                            @else
                                <div style="width: 45px; height: 45px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #cbd5e1;"><i class="fas fa-user"></i></div>
                            @endif
                        </td>
                        <td style="font-weight: 700; color: #1e293b;">{{ $rep->nombre }}</td>
                        <td>
                            <div style="font-size: 0.85rem; color: #64748b;">
                                <i class="fas fa-phone" style="width: 15px;"></i> {{ $rep->telefono ?? 'N/A' }}<br>
                                <i class="fas fa-envelope" style="width: 15px;"></i> {{ $rep->email ?? 'N/A' }}
                            </div>
                        </td>
                        <td>
                            <span class="badge" style="background: #f0fdf4; color: #166534; font-weight: 700;">{{ $rep->locations->count() }} Puntos</span>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" {{ $rep->estado ? 'checked' : '' }} onchange="toggleStatusRep({{ $rep->id }})">
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td>
                            <button onclick='openDetailModal(@json($rep))' class="btn" style="background: #e0e7ff; color: #4338ca; padding: 6px 10px;" title="Ver Detalle"><i class="fas fa-eye"></i></button>
                            <button onclick='openEditModal(@json($rep))' class="btn" style="background: #f3f4f6; color: #333; padding: 6px 10px;" title="Editar"><i class="fas fa-edit"></i></button>
                            <button onclick="confirmDelete({{ $rep->id }}, 'REP-{{ $rep->id }}', '{{ $rep->nombre }}', '{{ route('admin.representatives.destroy', $rep->id) }}')" class="btn" style="background: #fee2e2; color: #ef4444; padding: 6px 10px;" title="Eliminar"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding: 30px; text-align: center; color: #888;">No hay representantes registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="detailRepModal" class="modal">
        <div class="modal-content" style="max-width: 800px; padding: 0; max-height: 90vh; display: flex; flex-direction: column; overflow: hidden; border: none; border-radius: 20px;">
            <div class="modal-header" style="background: #1e293b; color: white; padding: 20px 30px; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="background: rgba(59, 130, 246, 0.2); width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-id-card-alt" style="color: #3b82f6; font-size: 1.2rem;"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 1.25rem; font-weight: 700; color: #ffffff;">Detalle de Ejecutivo</h3>
                </div>
                <span class="close-modal" onclick="closeModal('detailRepModal')" style="background: rgba(255,255,255,0.1); width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; cursor: pointer;">&times;</span>
            </div>
            <div class="modal-body" id="detailRepBody" style="padding: 40px; overflow-y: auto; flex: 1;">
            </div>
            <div class="modal-footer" style="padding: 20px 40px; background: white; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end;">
                <button type="button" class="btn btn-primary" onclick="closeModal('detailRepModal')">Cerrar</button>
            </div>
        </div>
    </div>

    <!-- Modal Nuevo Representante -->
    <div id="newRepModal" class="modal">
        <div class="modal-content" style="max-width: 1000px; padding: 0; max-height: 90vh; display: flex; flex-direction: column; overflow: hidden; border: none; border-radius: 20px;">
            <div class="modal-header" style="background: #1e293b; color: white; padding: 20px 30px; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="background: rgba(16, 185, 129, 0.2); width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user-tie" style="color: #10b981; font-size: 1.2rem;"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 1.25rem; font-weight: 700; color: #ffffff;">Registrar Nuevo Ejecutivo</h3>
                </div>
                <span class="close-modal" onclick="closeModal('newRepModal')" style="background: rgba(255,255,255,0.1); width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; cursor: pointer;">&times;</span>
            </div>
            <div class="modal-body" style="padding: 40px; overflow-y: auto; flex: 1;">
                <form action="{{ route('admin.representatives.store') }}" method="POST" enctype="multipart/form-data" id="form-new">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 35px;">
                        <!-- Columna Info -->
                        <div>
                            <h4 style="display: flex; align-items: center; gap: 12px; color: #1e293b; margin-bottom: 20px; font-weight: 700;">
                                <div style="background: #10b981; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem;">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                Datos Personales
                            </h4>

                            <div class="form-group">
                                <label class="form-label" style="color: #475569; font-weight: 600;">Foto de Perfil</label>
                                <input type="file" name="imagen" class="form-control" style="border-radius: 12px; padding: 8px;" accept="image/*">
                            </div>

                            <div class="form-group">
                                <label class="form-label" style="color: #475569; font-weight: 600;">Nombre Completo</label>
                                <div style="position: relative;">
                                    <i class="fas fa-user" style="position: absolute; left: 18px; top: 16px; color: #94a3b8;"></i>
                                    <input type="text" name="nombre" class="form-control" style="padding-left: 50px; border-radius: 12px;" placeholder="Ej. Carlos Mendoza" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" style="color: #475569; font-weight: 600;">Teléfono / WhatsApp</label>
                                <div style="position: relative;">
                                    <i class="fas fa-phone" style="position: absolute; left: 18px; top: 16px; color: #94a3b8;"></i>
                                    <input type="text" name="telefono" class="form-control" style="padding-left: 50px; border-radius: 12px;" placeholder="+51 999 888 777">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" style="color: #475569; font-weight: 600;">Correo Electrónico</label>
                                <div style="position: relative;">
                                    <i class="fas fa-envelope" style="position: absolute; left: 18px; top: 16px; color: #94a3b8;"></i>
                                    <input type="email" name="email" class="form-control" style="padding-left: 50px; border-radius: 12px;" placeholder="ejecutivo@sanchezpharma.com">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" style="color: #475569; font-weight: 600;">Sede Principal</label>
                                <div style="position: relative;">
                                    <i class="fas fa-city" style="position: absolute; left: 18px; top: 16px; color: #94a3b8;"></i>
                                    <input type="text" name="ubicacion" class="form-control" style="padding-left: 50px; border-radius: 12px;" placeholder="Ej. Lima Norte" required>
                                </div>
                            </div>
                            
                            <input type="hidden" name="zona_id" value="1">
                            <input type="hidden" name="locations" id="locations-json-new">
                        </div>

                        <!-- Columna Mapa y Zonas -->
                        <div>
                            <h4 style="display: flex; align-items: center; gap: 12px; color: #1e293b; margin-bottom: 20px; font-weight: 700;">
                                <div style="background: #10b981; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem;">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                Zonas de Cobertura
                            </h4>
                            <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 15px;">Marque los puntos de atención en el mapa:</p>
                            
                            <div id="map-new" style="border-radius: 15px; border: 1.5px solid #e2e8f0; margin-bottom: 20px;"></div>
                            
                            <div id="location-list-new" class="location-list" style="border-radius: 15px; border: 1.5px solid #f1f5f9; background: #f8fafc; padding: 15px;">
                                <p style="text-align: center; color: #94a3b8; font-size: 0.9rem;">No hay puntos marcados.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 25px 40px; background: white; border-top: 1px solid #f1f5f9;">
                    <button type="button" class="btn" style="background: #f1f5f9; color: #64748b; font-weight: 600; padding: 12px 25px;" onclick="closeModal('newRepModal')">Cancelar</button>
                    <button type="submit" class="btn btn-primary" style="padding: 12px 40px; font-weight: 700; box-shadow: 0 4px 12px rgba(46, 125, 50, 0.2);">
                        <i class="fas fa-check-circle"></i> Finalizar y Guardar
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Representante -->
    <div id="editRepModal" class="modal">
        <div class="modal-content" style="max-width: 1000px; padding: 0; max-height: 90vh; display: flex; flex-direction: column; overflow: hidden; border: none; border-radius: 20px;">
            <div class="modal-header" style="background: #1e293b; color: white; padding: 20px 30px; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="background: rgba(245, 158, 11, 0.2); width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-edit" style="color: #f59e0b; font-size: 1.2rem;"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 1.25rem; font-weight: 700; color: #ffffff;">Editar Ejecutivo</h3>
                </div>
                <span class="close-modal" onclick="closeModal('editRepModal')" style="background: rgba(255,255,255,0.1); width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; cursor: pointer;">&times;</span>
            </div>
            <div class="modal-body" style="padding: 40px; overflow-y: auto; flex: 1;">
                <form id="editRepForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 35px;">
                        <div>
                            <h4 style="display: flex; align-items: center; gap: 12px; color: #1e293b; margin-bottom: 20px; font-weight: 700;">
                                <div style="background: #f59e0b; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem;">
                                    <i class="fas fa-user"></i>
                                </div>
                                Datos del Ejecutivo
                            </h4>

                            <div class="form-group">
                                <label class="form-label" style="color: #475569; font-weight: 600;">Actualizar Foto</label>
                                <input type="file" name="imagen" class="form-control" style="border-radius: 12px; padding: 8px;" accept="image/*">
                            </div>

                            <div class="form-group">
                                <label class="form-label" style="color: #475569; font-weight: 600;">Nombre Completo</label>
                                <div style="position: relative;">
                                    <i class="fas fa-id-badge" style="position: absolute; left: 18px; top: 16px; color: #94a3b8;"></i>
                                    <input type="text" name="nombre" id="edit-nombre" class="form-control" style="padding-left: 50px; border-radius: 12px;" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" style="color: #475569; font-weight: 600;">Teléfono</label>
                                <div style="position: relative;">
                                    <i class="fas fa-phone" style="position: absolute; left: 18px; top: 16px; color: #94a3b8;"></i>
                                    <input type="text" name="telefono" id="edit-telefono" class="form-control" style="padding-left: 50px; border-radius: 12px;">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" style="color: #475569; font-weight: 600;">Correo Electrónico</label>
                                <div style="position: relative;">
                                    <i class="fas fa-envelope" style="position: absolute; left: 18px; top: 16px; color: #94a3b8;"></i>
                                    <input type="email" name="email" id="edit-email" class="form-control" style="padding-left: 50px; border-radius: 12px;">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" style="color: #475569; font-weight: 600;">Sede Principal</label>
                                <div style="position: relative;">
                                    <i class="fas fa-map-marker-alt" style="position: absolute; left: 18px; top: 16px; color: #94a3b8;"></i>
                                    <input type="text" name="ubicacion" id="edit-ubicacion" class="form-control" style="padding-left: 50px; border-radius: 12px;" required>
                                </div>
                            </div>
                            
                            <input type="hidden" name="zona_id" id="edit-zona_id">
                            <input type="hidden" name="locations" id="locations-json-edit">
                        </div>

                        <div>
                            <h4 style="display: flex; align-items: center; gap: 12px; color: #1e293b; margin-bottom: 20px; font-weight: 700;">
                                <div style="background: #f59e0b; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem;">
                                    <i class="fas fa-globe-americas"></i>
                                </div>
                                Cobertura Geográfica
                            </h4>
                            
                            <div id="map-edit" style="border-radius: 15px; border: 1.5px solid #e2e8f0; margin-bottom: 20px;"></div>
                            
                            <div id="location-list-edit" class="location-list" style="border-radius: 15px; border: 1.5px solid #f1f5f9; background: #f8fafc; padding: 15px;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 25px 40px; background: white; border-top: 1px solid #f1f5f9;">
                    <button type="button" class="btn" style="background: #f1f5f9; color: #64748b; font-weight: 600; padding: 12px 25px;" onclick="closeModal('editRepModal')">Cancelar</button>
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
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script>
    let mapNew, mapEdit, markersNew = [], markersEdit = [], locationsNew = [], locationsEdit = [];
    const zones = @json($zonas);

    function initMap(type, lat = -9.189967, lng = -75.015152) {
        const mapId = 'map-' + type;
        const currentMarkers = type === 'new' ? markersNew : markersEdit;
        const currentLocations = type === 'new' ? locationsNew : locationsEdit;

        let mapObj;
        if (type === 'new') {
            if (mapNew) mapNew.remove();
            mapNew = L.map(mapId).setView([lat, lng], 5);
            mapObj = mapNew;
        } else {
            if (mapEdit) mapEdit.remove();
            mapEdit = L.map(mapId).setView([lat, lng], 10);
            mapObj = mapEdit;
        }

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapObj);
        
        // Buscador Geocoder
        L.Control.geocoder({
            defaultMarkGeocode: false,
            placeholder: "Escribe ciudad...",
        }).on('markgeocode', function(e) {
            const center = e.geocode.center;
            mapObj.setView(center, 12);
            
            Swal.fire({
                title: '¿Confirmar ubicación?',
                text: `¿Desea marcar "${e.geocode.name}" como punto de atención?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: 'var(--primary)',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sí, marcar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    addLocation(type, center.lat, center.lng);
                }
            });
        }).addTo(mapObj);

        mapObj.on('click', function(e) {
            addLocation(type, e.latlng.lat, e.latlng.lng);
        });

        setTimeout(() => mapObj.invalidateSize(), 200);
        
        if (type === 'edit') {
            currentLocations.forEach(loc => {
                const marker = L.marker([loc.lat, loc.lng]).addTo(mapEdit);
                currentMarkers.push(marker);
            });
            renderLocationList('edit');
        }
    }

    function addLocation(type, lat, lng) {
        const zoneOptions = {};
        zones.forEach(z => { zoneOptions[z.id] = z.nombre_zona; });

        Swal.fire({
            title: '<i class="fas fa-map-marker-alt" style="color: var(--primary);"></i> Punto Detectado',
            text: 'Seleccione la zona de influencia para este punto:',
            input: 'select',
            inputOptions: zoneOptions,
            inputPlaceholder: 'Seleccione una zona...',
            showCancelButton: true,
            confirmButtonColor: 'var(--primary)',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Confirmar Punto',
            cancelButtonText: 'Descartar',
            inputValidator: (value) => {
                return new Promise((resolve) => {
                    if (value) { resolve(); } else { resolve('Debe seleccionar una zona'); }
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const zoneId = result.value;
                const zone = zones.find(z => z.id == zoneId);
                const locObj = { lat, lng, zona_id: zone.id, zona_name: zone.nombre_zona };

                const currentMap = type === 'new' ? mapNew : mapEdit;
                const currentLocations = type === 'new' ? locationsNew : locationsEdit;
                const currentMarkers = type === 'new' ? markersNew : markersEdit;

                currentLocations.push(locObj);
                const marker = L.marker([lat, lng]).addTo(currentMap).bindPopup(zone.nombre_zona).openPopup();
                currentMarkers.push(marker);
                renderLocationList(type);
            }
        });
    }

    function removeLocation(type, index) {
        if (type === 'new') {
            mapNew.removeLayer(markersNew[index]);
            markersNew.splice(index, 1);
            locationsNew.splice(index, 1);
            renderLocationList('new');
        } else {
            mapEdit.removeLayer(markersEdit[index]);
            markersEdit.splice(index, 1);
            locationsEdit.splice(index, 1);
            renderLocationList('edit');
        }
    }

    function renderLocationList(type) {
        const list = document.getElementById('location-list-' + type);
        const dataInput = document.getElementById('locations-json-' + type);
        const currentLocations = type === 'new' ? locationsNew : locationsEdit;

        list.innerHTML = currentLocations.length === 0 ? '<p style="text-align: center; color: #94a3b8;">Sin puntos.</p>' : '';
        currentLocations.forEach((loc, i) => {
            const item = document.createElement('div');
            item.className = 'location-item';
            item.innerHTML = `<div><b>${loc.zona_name}</b><br><small>${loc.lat.toFixed(4)}, ${loc.lng.toFixed(4)}</small></div>
                <button type="button" onclick="removeLocation('${type}', ${i})" style="border:none; background:none; color:red; cursor:pointer;"><i class="fas fa-trash"></i></button>`;
            list.appendChild(item);
        });
        dataInput.value = JSON.stringify(currentLocations);
    }

    function openNewModal() {
        locationsNew = []; markersNew = [];
        openModal('newRepModal');
        initMap('new');
    }

    function openDetailModal(rep) {
        const body = document.getElementById('detailRepBody');
        const img = rep.imagen ? `/storage/${rep.imagen}` : null;
        let locationsHtml = rep.locations.length > 0 ? rep.locations.map(l => {
            const z = zones.find(zn => zn.id == l.zona_id);
            return `<div style="background: #f1f5f9; padding: 10px; border-radius: 8px; margin-bottom: 5px; font-size: 0.9rem;">
                <b>${z ? z.nombre_zona : 'N/A'}</b><br>
                <small>Lat: ${l.latitud}, Lng: ${l.longitud}</small>
            </div>`;
        }).join('') : '<p style="color: #94a3b8; font-size: 0.9rem;">Sin zonas asignadas</p>';

        body.innerHTML = `
            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
                <div style="text-align: center;">
                    ${img ? `<img src="${img}" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 4px solid #f1f5f9; box-shadow: var(--shadow-sm);">` : `<div style="width: 150px; height: 150px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 4rem; color: #cbd5e1; margin: 0 auto; box-shadow: var(--shadow-sm);"><i class="fas fa-user"></i></div>`}
                    <h4 style="margin-top: 15px; color: #1e293b; font-weight: 700;">${rep.nombre}</h4>
                    <span class="badge ${rep.estado ? 'badge-success' : 'badge-danger'}" style="margin-top: 5px;">${rep.estado ? 'Activo' : 'Inactivo'}</span>
                </div>
                <div>
                    <h5 style="color: #475569; font-weight: 700; margin-bottom: 15px; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px;">Información de Contacto</h5>
                    <p style="margin-bottom: 8px;"><b><i class="fas fa-phone" style="width: 20px; color: #94a3b8;"></i> Teléfono:</b> ${rep.telefono || 'N/A'}</p>
                    <p style="margin-bottom: 8px;"><b><i class="fas fa-envelope" style="width: 20px; color: #94a3b8;"></i> Email:</b> ${rep.email || 'N/A'}</p>
                    <p style="margin-bottom: 8px;"><b><i class="fas fa-map-marker-alt" style="width: 20px; color: #94a3b8;"></i> Sede Principal:</b> ${rep.ubicacion || 'N/A'}</p>

                    <h5 style="color: #475569; font-weight: 700; margin-top: 25px; margin-bottom: 15px; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px;">Zonas de Cobertura</h5>
                    <div style="max-height: 200px; overflow-y: auto;">
                        ${locationsHtml}
                    </div>
                </div>
            </div>
        `;
        openModal('detailRepModal');
    }

    function openEditModal(rep) {
        locationsEdit = rep.locations.map(l => ({ lat: parseFloat(l.latitud), lng: parseFloat(l.longitud), zona_id: l.zona_id, zona_name: zones.find(z => z.id == l.zona_id)?.nombre_zona || 'N/A' }));
        markersEdit = [];
        const form = document.getElementById('editRepForm');
        form.action = `/admin/representatives/${rep.id}`;
        document.getElementById('edit-nombre').value = rep.nombre;
        document.getElementById('edit-telefono').value = rep.telefono;
        document.getElementById('edit-email').value = rep.email;
        document.getElementById('edit-ubicacion').value = rep.ubicacion;
        document.getElementById('edit-zona_id').value = rep.zona_id;
        openModal('editRepModal');
        initMap('edit', locationsEdit.length > 0 ? locationsEdit[0].lat : -9.189967, locationsEdit.length > 0 ? locationsEdit[0].lng : -75.015152);
    }

    function toggleStatusRep(id) { console.log('Toggle', id); }
</script>
@endpush
