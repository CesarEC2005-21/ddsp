@extends('layouts.landing')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .about-hero {
        background: linear-gradient(rgba(27, 94, 32, 0.8), rgba(27, 94, 32, 0.9)), url('https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover;
        color: white; text-align: center; padding: 60px 5%; border-radius: 0 0 50px 50px; margin-bottom: 40px;
    }

    .nosotros-layout { display: grid; grid-template-columns: 350px 1fr; gap: 30px; padding: 0 5% 100px; max-width: 1400px; margin: 0 auto; min-height: 800px; }
    
    .sidebar-filters { background: white; border-radius: 25px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; height: fit-content; position: sticky; top: 120px; z-index: 10; animation: fadeInLeft 0.8s ease-out; }
    
    .filter-section { margin-bottom: 30px; }
    .filter-label { display: block; font-size: 0.85rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 15px; letter-spacing: 1px; }
    
    .type-selector { display: flex; gap: 10px; margin-bottom: 25px; }
    .type-btn { flex: 1; padding: 12px; border-radius: 12px; border: 2px solid #f1f5f9; background: white; cursor: pointer; font-weight: 600; color: #64748b; transition: 0.3s; font-size: 0.9rem; display: flex; flex-direction: column; align-items: center; gap: 5px; }
    .type-btn.active { border-color: var(--primary-green); background: #f0fdf4; color: var(--primary-green); }
    .type-btn i { font-size: 1.2rem; }

    .select-input { width: 100%; padding: 12px 15px; border-radius: 12px; border: 1px solid #e2e8f0; background: #f8fafc; outline: none; font-size: 0.95rem; color: #1e293b; cursor: pointer; }

    .content-display { background: white; border-radius: 30px; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; display: flex; flex-direction: column; min-height: 800px; animation: fadeInRight 0.8s ease-out; }
    
    .map-container { position: relative; flex-grow: 1; min-height: 600px; }
    #map-display { width: 100%; height: 100%; min-height: 800px; }

    .rep-overlay-card {
        position: absolute; bottom: 30px; left: 30px; width: 380px; background: white; border-radius: 25px; 
        box-shadow: 0 20px 60px rgba(0,0,0,0.15); z-index: 1000; overflow: hidden;
        display: none; animation: fadeInUp 0.5s cubic-bezier(0.18, 0.89, 0.32, 1.28);
        border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(5px);
    }
    .rep-overlay-img { height: 220px; background-size: cover; background-position: center; position: relative; }
    .rep-overlay-img::after { content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 50%; background: linear-gradient(transparent, white); }
    .rep-overlay-info { padding: 25px; position: relative; margin-top: -40px; background: white; border-radius: 25px 25px 0 0; }

    @keyframes fadeInLeft { from { opacity: 0; transform: translateX(-30px); } to { opacity: 1; transform: translateX(0); } }
    @keyframes fadeInRight { from { opacity: 0; transform: translateX(30px); } to { opacity: 1; transform: translateX(0); } }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endpush

@section('content')
    <div class="about-hero">
        <h1 style="font-size: 3rem; font-family: 'Poppins', sans-serif; color: white !important; font-weight: 800;">Nuestra Red de Ejecutivos</h1>
        <p style="font-size: 1.1rem; opacity: 0.9; color: white;">Contáctate con nuestros representantes autorizados en todo el país.</p>
    </div>

    <div class="nosotros-layout">
        <!-- Sidebar de Filtros -->
        <aside class="sidebar-filters">
            <div class="filter-section" id="section-reps">
                <span class="filter-label">Selecciona un Ejecutivo</span>
                <select id="select-rep" class="select-input" onchange="showRep(this.value)">
                    <option value="">Seleccione...</option>
                    @foreach($representatives as $rep)
                        <option value="{{ $rep->id }}">{{ $rep->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div id="contact-info-short" style="display: none; margin-top: 30px; padding: 25px; border-radius: 20px; background: var(--primary-green); color: white; border: none; animation: fadeInUp 0.5s; box-shadow: 0 10px 30px rgba(46, 125, 50, 0.3);">
                <h4 style="margin-bottom: 15px; color: white; font-size: 1rem; display: flex; align-items: center; gap: 8px; border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 10px;">
                    <i class="fas fa-info-circle"></i> <span id="info-type-title">Información</span>
                </h4>
                <div id="rep-photo-container" style="margin-bottom: 20px; border-radius: 20px; overflow: hidden; border: 4px solid white; box-shadow: 0 10px 30px rgba(0,0,0,0.1); background: #f8fafc;">
                    <img id="side-rep-img" src="" alt="Imagen" style="width: 100%; height: auto; max-height: 400px; display: block; object-fit: cover;">
                </div>
                <div id="rep-contact-data">
                    <p id="side-rep-name" style="font-weight: 700; color: white; margin-bottom: 12px; font-size: 1.2rem;"></p>
                    <p style="font-size: 0.9rem; margin-bottom: 10px; color: rgba(255,255,255,0.9);"><i class="fas fa-phone-alt" style="width: 20px;"></i> <span id="text-phone"></span></p>
                    <p style="font-size: 0.9rem; margin-bottom: 15px; color: rgba(255,255,255,0.9);"><i class="fas fa-map-marker-alt" style="width: 20px;"></i> <span id="text-email"></span></p>
                    <a id="side-rep-call" href="#" target="_blank" class="btn" style="width: 100%; text-align: center; font-size: 0.9rem; padding: 12px; background: white; color: var(--primary-green); border-radius: 12px; font-weight: 700; border: none; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                </div>
            </div>
        </aside>

        <!-- Área de Visualización -->
        <main class="content-display">
            <div class="map-container">
                <div id="map-display"></div>
            </div>
        </main>
    </div>

    <!-- Sección Nuestro Equipo de Trabajo -->
    <section style="padding: 80px 5%; background: #f8fafc; border-top: 1px solid #e2e8f0;">
        <div style="text-align: center; margin-bottom: 60px;">
            <span style="color: var(--primary-green); font-weight: 800; text-transform: uppercase; letter-spacing: 2px; font-size: 0.9rem;">Representantes Autorizados</span>
            <h2 style="font-size: 3rem; color: #1e293b; margin: 15px 0;">Nuestro Equipo de Trabajo</h2>
            <div style="width: 80px; height: 4px; background: var(--primary-green); margin: 0 auto; border-radius: 2px;"></div>
            <p style="color: #64748b; font-size: 1.1rem; max-width: 600px; margin: 20px auto 0;">Contáctate directamente con nuestro equipo de vendedores a través de WhatsApp para una atención personalizada y rápida.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 40px; max-width: 1400px; margin: 0 auto;">
            @foreach($representatives as $rep)
                @php
                    $phone = $rep->telefono ? preg_replace('/[^0-9]/', '', $rep->telefono) : '999999999';
                    $message = urlencode("Hola {$rep->nombre}, deseo realizar una consulta sobre sus productos.");
                    $waLink = "https://wa.me/51{$phone}?text={$message}";
                @endphp
                <div style="background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; transition: 0.4s;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.05)';">
                    <div style="height: 300px; overflow: hidden; background: #e2e8f0; position: relative;">
                        @if($rep->imagen)
                            <img src="{{ asset('storage/' . $rep->imagen) }}" alt="{{ $rep->nombre }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($rep->nombre) }}&size=400&background=10b981&color=fff" alt="{{ $rep->nombre }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @endif
                        <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 50%; background: linear-gradient(transparent, rgba(15, 23, 42, 0.8));"></div>
                        <h3 style="position: absolute; bottom: 20px; left: 20px; color: white; margin: 0; font-size: 1.4rem; font-weight: 700;">{{ $rep->nombre }}</h3>
                    </div>
                    <div style="padding: 25px;">
                        <p style="color: #64748b; margin-bottom: 15px; font-size: 0.95rem; display: flex; align-items: center; gap: 10px;"><i class="fas fa-map-marked-alt" style="color: var(--primary-green); width: 20px;"></i> Cobertura: <strong>{{ $rep->locations->first()->zona->nombre_zona ?? 'Múltiples zonas' }}</strong></p>
                        <p style="color: #64748b; margin-bottom: 15px; font-size: 0.95rem; display: flex; align-items: center; gap: 10px;"><i class="fas fa-phone-alt" style="color: var(--primary-green); width: 20px;"></i> {{ $rep->telefono ?? 'No especificado' }}</p>
                        <p style="color: #64748b; margin-bottom: 25px; font-size: 0.95rem; display: flex; align-items: center; gap: 10px;"><i class="fas fa-envelope" style="color: var(--primary-green); width: 20px;"></i> {{ $rep->email ?? 'No especificado' }}</p>
                        
                        <a href="{{ $waLink }}" target="_blank" style="display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; padding: 15px; background: #25D366; color: white; border-radius: 12px; text-decoration: none; font-weight: 700; transition: 0.3s; font-size: 1.05rem;" onmouseover="this.style.background='#1da851'" onmouseout="this.style.background='#25D366'">
                            <i class="fab fa-whatsapp" style="font-size: 1.3rem;"></i> Contactar por WhatsApp
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let map, marker, markersGroup;
    const reps = @json($representatives->load('locations.zona'));
    
    console.log('Representatives loaded:', reps.length);
    console.log('First rep:', reps[0] ? reps[0].nombre : 'none');
    console.log('First rep locations:', reps[0] ? reps[0].locations : 'none');

    // Initialize map on load
    document.addEventListener('DOMContentLoaded', () => {
        initMap(-9.189967, -75.015152, 6);
        showAllMarkers();
    });

    function initMap(lat, lng, zoom = 6) {
        if (map) map.remove();
        map = L.map('map-display', {
            zoomControl: false
        }).setView([lat, lng], zoom);
        
        L.control.zoom({ position: 'topright' }).addTo(map);
        
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; Sanchez Pharma'
        }).addTo(map);
        
        markersGroup = L.featureGroup().addTo(map);
    }

    function showAllMarkers() {
        markersGroup.clearLayers();
        
        const colors = ['#ef4444', '#3b82f6', '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4', '#f97316'];
        
        // Add Reps (Different colors per representative)
        reps.forEach((r, idx) => {
            const repColor = colors[idx % colors.length];
            r.locations.forEach(loc => {
                const m = L.marker([loc.latitud, loc.longitud], {
                    icon: createIcon(repColor)
                }).addTo(markersGroup).on('click', () => showRepOverlay(r));
            });
        });
    }

    function createIcon(color) {
        return L.divIcon({
            className: 'custom-icon',
            html: `<div style="background:${color}; width:20px; height:20px; border-radius:50%; border:3px solid white; box-shadow:0 0 15px rgba(0,0,0,0.2)"></div>`,
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });
    }

    function showRep(id) {
        if (!id) return showAllMarkers();
        const r = reps.find(x => x.id == id);
        if (!r) return;
        
        markersGroup.clearLayers();
        showRepOverlay(r);
        
        if (r.locations && r.locations.length > 0) {
            const colors = ['#ef4444', '#3b82f6', '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4', '#f97316'];
            const repColor = colors[reps.indexOf(r) % colors.length];
            
            r.locations.forEach(loc => {
                if (loc.latitud && loc.longitud) {
                    L.marker([loc.latitud, loc.longitud], {
                        icon: createIcon(repColor)
                    }).addTo(markersGroup).bindPopup(`<b>Zona: ${loc.zona?.nombre_zona || 'Atención'}</b><br>${loc.direccion || ''}`);
                }
            });
            
            if (markersGroup.getLayers().length > 0) {
                const group = L.featureGroup(markersGroup.getLayers());
                map.flyToBounds(group.getBounds().pad(0.5));
            }
        }
    }

    function showRepOverlay(r) {
        document.getElementById('contact-info-short').style.display = 'block';
        document.getElementById('info-type-title').innerText = 'Información del Ejecutivo';
        document.getElementById('rep-photo-container').style.display = 'block';
        
        const imgSrc = r.imagen 
            ? (r.imagen.startsWith('http') ? r.imagen : '/storage/' + r.imagen) 
            : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(r.nombre) + '&size=400&background=10b981&color=fff';
        document.getElementById('side-rep-img').src = imgSrc;
        
        document.getElementById('side-rep-name').innerText = r.nombre;
        document.getElementById('text-phone').innerText = r.telefono || 'Consultar';
        
        const zona = r.locations && r.locations.length > 0 && r.locations[0].zona ? r.locations[0].zona.nombre_zona : 'Múltiples zonas';
        document.getElementById('text-email').innerText = zona;
        
        const message = encodeURIComponent("Hola, quisiera atención para cotizar un pedido por favor.");
        const phone = r.telefono ? r.telefono.replace(/\D/g,'') : '';
        const waLink = phone ? `https://wa.me/51${phone}?text=${message}` : '#';
        
        const btn = document.getElementById('side-rep-call');
        btn.href = waLink;
        if (phone) {
            btn.onclick = (e) => { window.open(waLink, '_blank'); return false; };
            btn.innerHTML = '<i class="fab fa-whatsapp"></i> Contactar';
            btn.style.display = 'flex';
        } else {
            btn.style.display = 'none';
        }
    }

    function hideOverlay() {
        document.getElementById('contact-info-short').style.display = 'none';
    }

    function resetView() {
        showAllMarkers();
        hideOverlay();
        document.getElementById('select-rep').value = '';
        map.flyTo([-9.189967, -75.015152], 6);
    }
</script>
@endpush
