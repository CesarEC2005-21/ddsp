@extends('layouts.landing')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .about-hero {
        background: linear-gradient(rgba(27, 94, 32, 0.8), rgba(27, 94, 32, 0.9)), url('https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover;
        color: white; text-align: center; padding: 60px 5%; border-radius: 0 0 50px 50px; margin-bottom: 40px;
    }

    .nosotros-layout { display: grid; grid-template-columns: 350px 1fr; gap: 30px; padding: 0 5% 100px; max-width: 1400px; margin: 0 auto; min-height: 700px; }
    
    .sidebar-filters { background: white; border-radius: 25px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; height: fit-content; position: sticky; top: 120px; z-index: 10; }
    
    .filter-section { margin-bottom: 30px; }
    .filter-label { display: block; font-size: 0.85rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 15px; letter-spacing: 1px; }
    
    .type-selector { display: flex; gap: 10px; margin-bottom: 25px; }
    .type-btn { flex: 1; padding: 12px; border-radius: 12px; border: 2px solid #f1f5f9; background: white; cursor: pointer; font-weight: 600; color: #64748b; transition: 0.3s; font-size: 0.9rem; display: flex; flex-direction: column; align-items: center; gap: 5px; }
    .type-btn.active { border-color: var(--primary-green); background: #f0fdf4; color: var(--primary-green); }
    .type-btn i { font-size: 1.2rem; }

    .select-input { width: 100%; padding: 12px 15px; border-radius: 12px; border: 1px solid #e2e8f0; background: #f8fafc; outline: none; font-size: 0.95rem; color: #1e293b; cursor: pointer; }

    .content-display { background: white; border-radius: 30px; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; display: flex; flex-direction: column; min-height: 600px; }
    
    .rep-detail-view { display: grid; grid-template-columns: 400px 1fr; height: 100%; min-height: 600px; opacity: 0; transform: translateY(20px); transition: 0.5s ease; }
    .rep-detail-view.visible { opacity: 1; transform: translateY(0); }

    .rep-photo-side { background-size: cover; background-position: center; position: relative; }
    .rep-photo-side::after { content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 40%; background: linear-gradient(transparent, rgba(0,0,0,0.8)); }
    .rep-photo-info { position: absolute; bottom: 30px; left: 30px; right: 30px; color: white; z-index: 2; }

    .map-side { position: relative; }
    #map-display { width: 100%; height: 100%; min-height: 600px; }

    .placeholder-view { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 600px; color: #cbd5e1; text-align: center; padding: 50px; }
    .placeholder-view i { font-size: 5rem; margin-bottom: 20px; }
</style>
@endpush

@section('content')
    <div class="about-hero">
        <h1 style="font-size: 3rem; font-family: 'Poppins', sans-serif;">Nuestra Red de Distribución</h1>
        <p style="font-size: 1.1rem; opacity: 0.9;">Ubica nuestras boticas aliadas y representantes autorizados en todo el país.</p>
    </div>

    <div class="nosotros-layout">
        <!-- Sidebar de Filtros -->
        <aside class="sidebar-filters">
            <div class="filter-section">
                <span class="filter-label">¿Qué deseas buscar?</span>
                <div class="type-selector">
                    <button class="type-btn active" id="btn-type-botica" onclick="selectType('botica')">
                        <i class="fas fa-store"></i>
                        Boticas
                    </button>
                    <button class="type-btn" id="btn-type-rep" onclick="selectType('rep')">
                        <i class="fas fa-user-tie"></i>
                        Vendedores
                    </button>
                </div>
            </div>

            <div class="filter-section" id="section-boticas">
                <span class="filter-label">Selecciona una Botica</span>
                <select id="select-botica" class="select-input" onchange="showBotica(this.value)">
                    <option value="">Seleccione...</option>
                    @foreach($pharmacies as $pharmacy)
                        <option value="{{ $pharmacy->id }}">{{ $pharmacy->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-section" id="section-reps" style="display: none;">
                <span class="filter-label">Selecciona un Vendedor</span>
                <select id="select-rep" class="select-input" onchange="showRep(this.value)">
                    <option value="">Seleccione...</option>
                    @foreach($representatives as $rep)
                        <option value="{{ $rep->id }}">{{ $rep->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div id="contact-info-short" style="display: none; margin-top: 30px; padding-top: 25px; border-top: 1px solid #f1f5f9;">
                <h4 style="margin-bottom: 15px; color: #1e293b;">Contacto Directo</h4>
                <div id="rep-contact-data">
                    <p style="font-size: 0.9rem; margin-bottom: 8px;"><i class="fas fa-phone" style="color: var(--primary-green); width: 20px;"></i> <span id="text-phone"></span></p>
                    <p style="font-size: 0.9rem;"><i class="fas fa-envelope" style="color: var(--primary-green); width: 20px;"></i> <span id="text-email"></span></p>
                </div>
            </div>
        </aside>

        <!-- Área de Visualización -->
        <main class="content-display">
            <div id="view-placeholder" class="placeholder-view">
                <i class="fas fa-map-marked-alt"></i>
                <h2>Explora nuestra red nacional</h2>
                <p>Selecciona una opción a la izquierda para ver su ubicación y detalles.</p>
            </div>

            <div id="view-details" style="display: none; height: 100%;">
                <div class="rep-detail-view" id="rep-layout">
                    <div class="rep-photo-side" id="rep-photo">
                        <div class="rep-photo-info">
                            <h2 id="rep-name" style="font-size: 2rem; margin-bottom: 5px;"></h2>
                            <p id="rep-city" style="opacity: 0.8; font-weight: 500;"></p>
                        </div>
                    </div>
                    <div class="map-side">
                        <div id="map-display"></div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let map, marker, markersGroup;
    const boticas = @json($pharmacies);
    const reps = @json($representatives->load('locations.zona'));

    function selectType(type) {
        document.getElementById('btn-type-botica').classList.toggle('active', type === 'botica');
        document.getElementById('btn-type-rep').classList.toggle('active', type === 'rep');
        
        document.getElementById('section-boticas').style.display = type === 'botica' ? 'block' : 'none';
        document.getElementById('section-reps').style.display = type === 'rep' ? 'block' : 'none';
        
        resetView();
    }

    function initMap(lat, lng, zoom = 13) {
        if (map) map.remove();
        map = L.map('map-display').setView([lat, lng], zoom);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png').addTo(map);
        markersGroup = L.featureGroup().addTo(map);
    }

    function showBotica(id) {
        if (!id) return resetView();
        const b = boticas.find(x => x.id == id);
        if (!b) return;

        document.getElementById('view-placeholder').style.display = 'none';
        document.getElementById('view-details').style.display = 'block';
        setTimeout(() => document.getElementById('rep-layout').classList.add('visible'), 50);
        
        document.getElementById('rep-layout').style.gridTemplateColumns = "1fr"; 
        document.getElementById('rep-photo').style.display = 'none';
        
        initMap(b.latitud, b.longitud, 15);
        L.marker([b.latitud, b.longitud], {
            icon: L.divIcon({
                className: 'custom-icon',
                html: `<div style="background:#10b981; width:20px; height:20px; border-radius:50%; border:3px solid white; box-shadow:0 0 10px rgba(0,0,0,0.2)"></div>`,
                iconSize: [20, 20]
            })
        }).addTo(markersGroup).bindPopup(`<b>${b.nombre}</b><br>${b.ubicacion}`).openPopup();
    }

    function showRep(id) {
        if (!id) return resetView();
        const r = reps.find(x => x.id == id);
        if (!r) return;

        document.getElementById('view-placeholder').style.display = 'none';
        document.getElementById('view-details').style.display = 'block';
        setTimeout(() => document.getElementById('rep-layout').classList.add('visible'), 50);
        
        document.getElementById('rep-layout').style.gridTemplateColumns = "400px 1fr";
        document.getElementById('rep-photo').style.display = 'block';
        
        document.getElementById('rep-photo').style.backgroundImage = `url(${r.imagen ? '/storage/' + r.imagen : 'https://ui-avatars.com/api/?name=' + r.nombre + '&size=400'})`;
        document.getElementById('rep-name').innerText = r.nombre;
        document.getElementById('rep-city').innerText = r.ubicacion;
        
        document.getElementById('contact-info-short').style.display = 'block';
        document.getElementById('text-phone').innerText = r.telefono || 'Consultar';
        document.getElementById('text-email').innerText = r.email || 'Consultar';

        if (r.locations.length > 0) {
            initMap(r.locations[0].latitud, r.locations[0].longitud, 10);
            r.locations.forEach(loc => {
                L.marker([loc.latitud, loc.longitud], {
                    icon: L.divIcon({
                        className: 'custom-icon',
                        html: `<div style="background:#ef4444; width:18px; height:18px; border-radius:50%; border:3px solid white; box-shadow:0 0 10px rgba(0,0,0,0.2)"></div>`,
                        iconSize: [18, 18]
                    })
                }).addTo(markersGroup).bindPopup(`<b>Zona: ${loc.zona?.nombre_zona || 'Atención'}</b>`);
            });
            map.fitBounds(markersGroup.getBounds().pad(0.5));
        } else {
            initMap(-9.189967, -75.015152, 5); 
        }
    }

    function resetView() {
        document.getElementById('view-placeholder').style.display = 'flex';
        document.getElementById('view-details').style.display = 'none';
        document.getElementById('rep-layout').classList.remove('visible');
        document.getElementById('contact-info-short').style.display = 'none';
        document.getElementById('select-botica').value = '';
        document.getElementById('select-rep').value = '';
    }
</script>
@endpush
