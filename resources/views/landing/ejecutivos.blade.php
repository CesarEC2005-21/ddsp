@extends('layouts.landing')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    :root {
        --dark-green-rgb: 27, 94, 32;
        --primary-green-rgb: 46, 125, 50;
    }

    .about-hero {
        background: linear-gradient(rgba(var(--dark-green-rgb), 0.85), rgba(var(--dark-green-rgb), 0.95)), 
                    url('{{ ($banner && $banner->image_path) ? asset("storage/" . $banner->image_path) : "https://images.unsplash.com/photo-1557426272-fc759fdf7a8d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" }}') center/cover;
        color: white; 
        text-align: center; 
        padding: 100px 5% 140px; 
        clip-path: ellipse(150% 100% at 50% 0%);
        margin-bottom: -60px;
        position: relative;
        z-index: 1;
    }

    .about-hero h1 {
        font-size: 3.8rem;
        font-family: 'Poppins', sans-serif;
        font-weight: 800;
        letter-spacing: -1px;
        margin-bottom: 15px;
        text-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }

    .nosotros-layout { 
        display: grid; 
        grid-template-columns: 380px 1fr; 
        gap: 40px; 
        padding: 0 5% 100px; 
        max-width: 1500px; 
        margin: 0 auto; 
        position: relative;
        z-index: 2;
    }
    
    .sidebar-filters { 
        background: rgba(255, 255, 255, 0.95); 
        border-radius: 30px; 
        padding: 40px; 
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1); 
        border: 1px solid rgba(var(--dark-green-rgb), 0.1); 
        height: fit-content; 
        position: sticky; 
        top: 130px; 
        backdrop-filter: blur(10px);
        animation: fadeInLeft 0.8s cubic-bezier(0.16, 1, 0.3, 1); 
    }
    
    .filter-label { 
        display: block; 
        font-size: 0.75rem; 
        font-weight: 800; 
        color: var(--dark-green); 
        text-transform: uppercase; 
        margin-bottom: 20px; 
        letter-spacing: 2px; 
        opacity: 0.8;
    }
    
    .select-input { 
        width: 100%; 
        padding: 18px 20px 18px 50px; 
        border-radius: 18px; 
        border: 2px solid #f1f5f9; 
        background: #f8fafc; 
        outline: none; 
        font-size: 1rem; 
        color: #1e293b; 
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 500;
    }

    .select-input:focus {
        border-color: var(--dark-green);
        background: white;
        box-shadow: 0 0 0 4px rgba(var(--dark-green-rgb), 0.1);
    }

    .content-display { 
        background: white; 
        border-radius: 40px; 
        overflow: hidden; 
        box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.15); 
        border: 1px solid rgba(var(--dark-green-rgb), 0.05); 
        display: flex; 
        flex-direction: column; 
        height: 850px; 
        animation: fadeInRight 0.8s cubic-bezier(0.16, 1, 0.3, 1); 
    }
    
    #map-display { width: 100%; height: 100%; }

    .rep-card-premium {
        background: white;
        border-radius: 35px;
        overflow: hidden;
        border: 1px solid #f1f5f9;
        transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        display: flex;
        flex-direction: column;
    }

    .rep-card-premium:hover {
        transform: translateY(-20px);
        box-shadow: 0 40px 80px -15px rgba(var(--dark-green-rgb), 0.2);
        border-color: rgba(var(--dark-green-rgb), 0.3);
    }

    .rep-card-img-wrapper {
        height: 420px;
        position: relative;
        overflow: hidden;
    }

    .rep-card-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 1s cubic-bezier(0.23, 1, 0.32, 1);
    }

    .rep-card-premium:hover .rep-card-img-wrapper img {
        transform: scale(1.1);
    }

    .rep-card-gradient {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(var(--dark-green-rgb), 0.9) 0%, transparent 60%);
    }

    .status-badge {
        position: absolute;
        top: 25px;
        left: 25px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 10px 18px;
        border-radius: 50px;
        color: white;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 1.5px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        background: #4ade80;
        border-radius: 50%;
        box-shadow: 0 0 10px #4ade80;
    }

    .contact-btn-circle {
        width: 60px;
        height: 60px;
        background: #f1f5f9;
        color: var(--dark-green);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        transition: all 0.4s;
        border: 1px solid #e2e8f0;
    }

    .contact-btn-circle:hover {
        background: var(--dark-green);
        color: white;
        transform: rotate(15deg) scale(1.1);
    }

    .wa-btn-premium {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        padding: 18px;
        background: linear-gradient(135deg, #25D366, #128c7e);
        color: white;
        border-radius: 20px;
        text-decoration: none;
        font-weight: 700;
        font-size: 1.05rem;
        transition: all 0.4s;
        box-shadow: 0 15px 30px rgba(37, 211, 102, 0.2);
    }

    .wa-btn-premium:hover {
        transform: scale(1.02);
        box-shadow: 0 20px 40px rgba(37, 211, 102, 0.3);
    }

    @keyframes fadeInLeft { from { opacity: 0; transform: translateX(-50px); } to { opacity: 1; transform: translateX(0); } }
    @keyframes fadeInRight { from { opacity: 0; transform: translateX(50px); } to { opacity: 1; transform: translateX(0); } }
    @keyframes pulse { 0% { transform: scale(1); opacity: 1; } 50% { transform: scale(1.5); opacity: 0.5; } 100% { transform: scale(1); opacity: 1; } }

    @media (max-width: 1024px) {
        .nosotros-layout { grid-template-columns: 1fr; }
        .sidebar-filters { position: static; margin-bottom: 30px; }
        .about-hero h1 { font-size: 2.8rem; }
    }
</style>
@endpush

@section('content')
    <div class="about-hero">
        <h1 style="color: white !important;">Nuestra Red de Ejecutivos</h1>
        <p style="font-size: 1.2rem; opacity: 0.9; max-width: 800px; margin: 0 auto; line-height: 1.6;">Encuentra al representante ideal para tu zona y recibe asesoría farmacéutica personalizada con los más altos estándares de calidad.</p>
    </div>

    <div class="nosotros-layout">
        <!-- Sidebar de Filtros -->
        <aside class="sidebar-filters">
            <div class="filter-section">
                <span class="filter-label">Localiza a tu asesor</span>
                <div style="position: relative;">
                    <select id="select-rep" class="select-input" onchange="showRep(this.value)">
                        <option value="">Explorar todos los ejecutivos</option>
                        @foreach($representatives as $rep)
                            <option value="{{ $rep->id }}">{{ $rep->nombre }}</option>
                        @endforeach
                    </select>
                    <i class="fas fa-search" style="position: absolute; left: 20px; top: 50%; transform: translateY(-50%); color: var(--dark-green); font-size: 1.1rem; opacity: 0.6;"></i>
                </div>
            </div>

            <div id="contact-info-short" style="display: none; margin-top: 40px; animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1);">
                <div id="rep-photo-container" style="margin-bottom: 25px; border-radius: 25px; overflow: hidden; border: 5px solid white; box-shadow: 0 20px 40px rgba(0,0,0,0.1); background: #f8fafc;">
                    <img id="side-rep-img" src="" alt="Representante" style="width: 100%; height: auto; max-height: 350px; display: block; object-fit: cover;">
                </div>
                <div id="rep-contact-data">
                    <h4 id="side-rep-name" style="font-weight: 800; color: #1e293b; margin-bottom: 5px; font-size: 1.5rem; line-height: 1.2;"></h4>
                    <span id="text-email" style="display: block; color: var(--dark-green); font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 25px;"></span>
                    
                    <div style="background: #f8fafc; padding: 20px; border-radius: 20px; margin-bottom: 30px; border: 1px solid #f1f5f9;">
                        <p style="font-size: 0.95rem; margin-bottom: 12px; color: #475569; display: flex; align-items: center; gap: 15px;">
                            <i class="fas fa-phone-alt" style="color: var(--dark-green); width: 20px;"></i> <span id="text-phone"></span>
                        </p>
                        <p style="font-size: 0.95rem; margin: 0; color: #475569; display: flex; align-items: center; gap: 15px;">
                            <i class="fas fa-clock" style="color: var(--dark-green); width: 20px;"></i> Lun - Vie: 8:00 AM - 6:00 PM
                        </p>
                    </div>

                    <a id="side-rep-call" href="#" target="_blank" class="wa-btn-premium">
                        <i class="fab fa-whatsapp" style="font-size: 1.3rem;"></i> Iniciar Chat
                    </a>
                </div>
            </div>
        </aside>

        <!-- Área de Visualización -->
        <main class="content-display" style="position: relative;">
            <div id="map-display"></div>
            <div style="position: absolute; top: 30px; right: 30px; z-index: 1000; background: white; padding: 12px 25px; border-radius: 50px; box-shadow: 0 15px 35px rgba(0,0,0,0.12); display: flex; align-items: center; gap: 12px; font-size: 0.85rem; font-weight: 800; color: var(--dark-green); border: 1px solid rgba(var(--dark-green-rgb), 0.1);">
                <div style="width: 12px; height: 12px; background: #4ade80; border-radius: 50%; animation: pulse 2s infinite; box-shadow: 0 0 10px #4ade80;"></div>
                Cobertura Nacional Activa
            </div>
        </main>
    </div>

    <!-- Sección Nuestro Equipo de Trabajo -->
    <section style="padding: 140px 5%; background: #fcfdfc; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -100px; right: -100px; width: 600px; height: 600px; background: radial-gradient(circle, rgba(var(--dark-green-rgb), 0.04) 0%, transparent 70%); border-radius: 50%; z-index: 0;"></div>
        <div style="position: absolute; bottom: -100px; left: -100px; width: 500px; height: 500px; background: radial-gradient(circle, rgba(var(--dark-green-rgb), 0.03) 0%, transparent 70%); border-radius: 50%; z-index: 0;"></div>
        
        <div style="text-align: center; margin-bottom: 100px; position: relative; z-index: 1;">
            <span style="color: var(--dark-green); font-weight: 800; text-transform: uppercase; letter-spacing: 3px; font-size: 0.8rem; background: rgba(var(--dark-green-rgb), 0.08); padding: 10px 25px; border-radius: 50px;">Staff Profesional</span>
            <h2 style="font-size: 3.8rem; color: #1e293b; margin: 30px 0; font-family: 'Poppins', sans-serif; font-weight: 800; letter-spacing: -1px;">Expertos a su Servicio</h2>
            <p style="color: #64748b; font-size: 1.2rem; max-width: 800px; margin: 0 auto; line-height: 1.7;">Cada uno de nuestros ejecutivos cuenta con la experiencia técnica necesaria para potenciar su negocio farmacéutico.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 50px; max-width: 1500px; margin: 0 auto; position: relative; z-index: 1;">
            @foreach($representatives as $rep)
                @php
                    $phone = $rep->telefono ? preg_replace('/[^0-9]/', '', $rep->telefono) : '999999999';
                    $message = urlencode("Hola {$rep->nombre}, deseo realizar una consulta sobre sus productos.");
                    $waLink = "https://wa.me/51{$phone}?text={$message}";
                @endphp
                <div class="rep-card-premium">
                    <div class="rep-card-img-wrapper">
                        @if($rep->imagen)
                            <img src="{{ asset('storage/' . $rep->imagen) }}" alt="{{ $rep->nombre }}">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($rep->nombre) }}&size=500&background=1B5E20&color=fff" alt="{{ $rep->nombre }}">
                        @endif
                        <div class="rep-card-gradient"></div>
                        
                        <div class="status-badge">
                            <div class="status-dot"></div> OFICIAL
                        </div>

                        <div style="position: absolute; bottom: 35px; left: 35px; right: 35px;">
                            <span style="color: #4ade80; font-weight: 800; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 8px; display: block;">Asesor Comercial</span>
                            <h3 style="color: white; margin: 0; font-size: 2rem; font-weight: 800; line-height: 1.1; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">{{ $rep->nombre }}</h3>
                        </div>
                    </div>
                    
                    <div style="padding: 40px;">
                        <div style="margin-bottom: 35px;">
                            <div style="display: flex; align-items: center; gap: 20px; padding: 15px 20px; background: #f8fafc; border-radius: 20px; border: 1px solid #f1f5f9;">
                                <div style="width: 45px; height: 45px; background: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--dark-green); box-shadow: 0 8px 20px rgba(0,0,0,0.06); font-size: 1.1rem;">
                                    <i class="fas fa-map-marked-alt"></i>
                                </div>
                                <div>
                                    <span style="display: block; font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">Área de Cobertura</span>
                                    <strong style="color: #1e293b; font-size: 1rem;">{{ $rep->locations->first()->zona->nombre_zona ?? 'Nacional' }}</strong>
                                </div>
                            </div>
                        </div>
                        
                        <div style="display: flex; gap: 20px;">
                            <a href="tel:{{ $phone }}" class="contact-btn-circle" title="Llamar ahora">
                                <i class="fas fa-phone-alt"></i>
                            </a>
                            <a href="{{ $waLink }}" target="_blank" class="wa-btn-premium">
                                <i class="fab fa-whatsapp" style="font-size: 1.4rem;"></i> WhatsApp
                            </a>
                        </div>
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
        if (!id) return resetView();
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
            btn.innerHTML = '<i class="fab fa-whatsapp" style="font-size: 1.3rem;"></i> Iniciar Chat';
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
