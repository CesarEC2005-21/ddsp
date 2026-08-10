@extends('layouts.landing')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
/* ═══════════════════════════════════════════════════════
   EJECUTIVOS — Premium Redesign
   ═══════════════════════════════════════════════════════ */
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800;900&display=swap');

/* ── Scroll reveal ── */
.ej-sr { opacity: 0; transform: translateY(40px); transition: opacity .7s ease, transform .7s ease; }
.ej-sr.in { opacity: 1; transform: none; }

/* ══════════════════════════════════════════════════════
   1. HERO
   ══════════════════════════════════════════════════════ */
.ej-hero {
    position: relative;
    min-height: 60vh;
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
    background: #0f172a;
}
.ej-hero-bg {
    position: absolute; inset: 0;
    background:
        url('{{ ($banner && $banner->image_path) ? asset("storage/".$banner->image_path) : "https://images.unsplash.com/photo-1557426272-fc759fdf7a8d?auto=format&fit=crop&w=1920&q=80" }}')
        center/cover no-repeat;
    filter: brightness(.28) saturate(1.3);
    transform: scale(1.08);
    transition: transform 12s ease-out;
}
.ej-hero-bg.loaded { transform: scale(1); }
.ej-hero-grid {
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(34,197,94,.08) 1px, transparent 1px),
        linear-gradient(90deg, rgba(34,197,94,.08) 1px, transparent 1px);
    background-size: 60px 60px;
    animation: ejGridFloat 20s linear infinite;
}
@keyframes ejGridFloat { to { background-position: 60px 60px; } }
.ej-hero-glow {
    position: absolute; border-radius: 50%;
    filter: blur(80px); opacity: .45;
    animation: ejGlowPulse 6s ease-in-out infinite alternate;
}
.ej-hero-glow.g1 { width: 500px; height: 500px; background: radial-gradient(circle, #22c55e, transparent); top: -150px; right: -100px; }
.ej-hero-glow.g2 { width: 400px; height: 400px; background: radial-gradient(circle, #059669, transparent); bottom: -100px; left: -80px; animation-delay: -3s; }
@keyframes ejGlowPulse { from { opacity:.3; transform:scale(.9); } to { opacity:.6; transform:scale(1.1); } }
#ejParticleCanvas { position: absolute; inset: 0; pointer-events: none; }

.ej-hero-inner {
    position: relative; z-index: 10;
    text-align: center; padding: 0 20px; max-width: 900px;
}
.ej-hero-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(34,197,94,.15); border: 1px solid rgba(34,197,94,.4);
    color: #4ade80; padding: 8px 20px; border-radius: 50px;
    font-size: .8rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
    margin-bottom: 28px; backdrop-filter: blur(10px);
    animation: ejBadgePop .6s .3s both cubic-bezier(.175,.885,.32,1.275);
}
@keyframes ejBadgePop { from { opacity:0; transform:scale(.8) translateY(10px); } to { opacity:1; transform:none; } }

.ej-hero-title {
    font-family: 'Outfit', sans-serif;
    font-size: clamp(3rem, 8vw, 6.5rem);
    font-weight: 900; color: white; line-height: 1; margin-bottom: 12px;
    animation: ejHeroTitle .9s .5s both;
}
@keyframes ejHeroTitle { from { opacity:0; transform:translateY(40px); } to { opacity:1; transform:none; } }
.ej-hero-title .hl {
    background: linear-gradient(135deg, #4ade80 0%, #22c55e 50%, #a3e635 100%);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text; display: block;
}
.ej-hero-sub {
    font-size: clamp(1rem, 2vw, 1.25rem); color: rgba(255,255,255,.7);
    max-width: 600px; margin: 24px auto 0; line-height: 1.7;
    animation: ejHeroTitle 1s .7s both;
}
.ej-hero-scroll {
    position: absolute; bottom: 40px; left: 0; width: 100%;
    display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px;
    color: rgba(255,255,255,.5); font-size: .75rem; letter-spacing: 2px; text-transform: uppercase;
    animation: ejHeroTitle 1s 1s both;
}
.ej-scroll-line {
    width: 1px; height: 50px;
    background: linear-gradient(to bottom, rgba(34,197,94,.8), transparent);
    animation: ejScrollLine 2s ease-in-out infinite;
}
@keyframes ejScrollLine { 0%,100%{ opacity:.3; transform:scaleY(.3) translateY(-10px); } 50%{ opacity:1; transform:scaleY(1) translateY(0); } }

/* ══════════════════════════════════════════════════════
   2. MAPA + SIDEBAR LAYOUT
   ══════════════════════════════════════════════════════ */
.ej-layout {
    display: grid;
    grid-template-columns: 360px 1fr;
    gap: 28px;
    padding: 70px 5% 80px;
    max-width: 1500px; margin: 0 auto;
}

/* Sidebar */
.ej-sidebar {
    background: white;
    border-radius: 28px;
    padding: 36px;
    box-shadow: 0 25px 60px rgba(0,0,0,.1);
    border: 1px solid #e2e8f0;
    height: fit-content;
    position: sticky; top: 120px;
}
.ej-sidebar-label {
    font-family: 'Outfit', sans-serif;
    font-size: .72rem; font-weight: 800;
    color: #16a34a; text-transform: uppercase;
    letter-spacing: 2px; margin-bottom: 16px;
    display: flex; align-items: center; gap: 6px;
}
.ej-sidebar-label::before {
    content: ''; display: block;
    width: 20px; height: 2px;
    background: #22c55e; border-radius: 2px;
}
.ej-select-wrap { position: relative; }
.ej-select {
    width: 100%; padding: 16px 16px 16px 48px;
    border-radius: 16px; border: 2px solid #e2e8f0;
    background: #f8fafc; outline: none;
    font-size: .95rem; color: #1e293b;
    cursor: pointer; transition: all .3s;
    font-weight: 600; font-family: 'Outfit', sans-serif;
    appearance: none;
}
.ej-select:focus { border-color: #22c55e; background: white; box-shadow: 0 0 0 4px rgba(34,197,94,.12); }
.ej-select-icon {
    position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
    color: #22c55e; font-size: 1rem; pointer-events: none;
}

/* Rep card in sidebar */
.ej-rep-preview {
    display: none; margin-top: 32px;
    animation: ejFadeUp .5s cubic-bezier(.175,.885,.32,1.275);
}
@keyframes ejFadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:none; } }
.ej-rep-preview-img {
    width: 100%; border-radius: 20px; overflow: hidden;
    border: 3px solid white; box-shadow: 0 20px 40px rgba(0,0,0,.12);
    background: #f8fafc; margin-bottom: 24px; height: 260px;
}
.ej-rep-preview-img img { width: 100%; height: 100%; object-fit: cover; display: block; }
.ej-rep-preview-name {
    font-family: 'Outfit', sans-serif;
    font-size: 1.4rem; font-weight: 800; color: #0f172a; margin-bottom: 4px;
}
.ej-rep-preview-zone {
    font-size: .8rem; font-weight: 700; color: #16a34a;
    text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 20px;
}
.ej-rep-preview-info {
    background: #f8fafc; border: 1px solid #e2e8f0;
    border-radius: 16px; padding: 18px; margin-bottom: 20px;
}
.ej-rep-info-row {
    display: flex; align-items: center; gap: 12px;
    font-size: .9rem; color: #475569; padding: 6px 0;
}
.ej-rep-info-row i { color: #22c55e; width: 16px; }
.ej-wa-btn {
    width: 100%; display: flex; align-items: center; justify-content: center; gap: 10px;
    padding: 16px; background: linear-gradient(135deg, #25D366, #128c7e);
    color: white; border-radius: 16px; text-decoration: none;
    font-weight: 800; font-size: 1rem; font-family: 'Outfit', sans-serif;
    transition: all .35s; box-shadow: 0 12px 28px rgba(37,211,102,.3);
}
.ej-wa-btn:hover { transform: translateY(-3px); box-shadow: 0 20px 40px rgba(37,211,102,.45); color: white; }

/* Map panel */
.ej-map-panel {
    background: white; border-radius: 28px; overflow: hidden;
    box-shadow: 0 25px 60px rgba(0,0,0,.1); border: 1px solid #e2e8f0;
    height: 700px; position: relative;
}
#ej-map { width: 100%; height: 100%; }
.ej-map-badge {
    position: absolute; top: 20px; right: 20px; z-index: 1000;
    background: white; padding: 10px 20px; border-radius: 50px;
    box-shadow: 0 10px 30px rgba(0,0,0,.12);
    display: flex; align-items: center; gap: 10px;
    font-size: .8rem; font-weight: 800; color: #16a34a;
    border: 1px solid #dcfce7;
}
.ej-map-pulse {
    width: 10px; height: 10px; background: #4ade80; border-radius: 50%;
    box-shadow: 0 0 10px #4ade80;
    animation: ejPulse 2s ease-in-out infinite;
}
@keyframes ejPulse { 0%,100%{ transform:scale(1); opacity:1; } 50%{ transform:scale(1.6); opacity:.5; } }

/* ══════════════════════════════════════════════════════
   3. EQUIPO — Cards
   ══════════════════════════════════════════════════════ */
.ej-team-section {
    padding: 80px 5% 120px;
    background: linear-gradient(160deg, #f0fdf4 0%, #f8fafc 50%, #f0fdfa 100%);
    position: relative; overflow: hidden;
}
.ej-team-section::before {
    content: ''; position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 50% 40% at 10% 20%, rgba(34,197,94,.07), transparent),
        radial-gradient(ellipse 40% 30% at 90% 80%, rgba(5,150,105,.06), transparent);
}

.ej-team-header {
    text-align: center; margin-bottom: 70px; position: relative; z-index: 1;
}
.ej-team-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(34,197,94,.1); border: 1px solid rgba(34,197,94,.25);
    color: #16a34a; padding: 7px 20px; border-radius: 50px;
    font-size: .75rem; font-weight: 800; letter-spacing: 2px; text-transform: uppercase;
    margin-bottom: 20px;
}
.ej-team-title {
    font-family: 'Outfit', sans-serif;
    font-size: clamp(2.2rem, 5vw, 3.5rem); font-weight: 900;
    color: #0f172a; line-height: 1.1; margin-bottom: 18px;
}
.ej-team-title .accent { color: #16a34a; }
.ej-team-sub {
    font-size: 1.05rem; color: #64748b; max-width: 600px;
    margin: 0 auto; line-height: 1.75;
}

/* Card grid */
.ej-cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 32px;
    max-width: 1500px; margin: 0 auto;
    position: relative; z-index: 1;
}

/* Card */
.ej-card {
    background: white;
    border-radius: 28px; overflow: hidden;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 20px rgba(0,0,0,.05);
    transition: transform .4s cubic-bezier(.175,.885,.32,1.275), box-shadow .4s ease, border-color .4s;
    display: flex; flex-direction: column;
    position: relative;
}
.ej-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(90deg, #22c55e, #15803d);
    opacity: 0; transform: scaleX(0); transform-origin: left;
    transition: opacity .4s, transform .4s;
}
.ej-card:hover::before { opacity: 1; transform: scaleX(1); }
.ej-card:hover {
    transform: translateY(-12px);
    box-shadow: 0 30px 70px rgba(0,0,0,.13), 0 0 0 1px rgba(34,197,94,.12);
    border-color: rgba(34,197,94,.2);
}

/* Card image */
.ej-card-img {
    height: 300px; position: relative; overflow: hidden;
}
.ej-card-img img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform 1s cubic-bezier(.23,1,.32,1);
}
.ej-card:hover .ej-card-img img { transform: scale(1.08); }
.ej-card-img-gradient {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(15,23,42,.85) 0%, transparent 55%);
}
.ej-card-overlay-info {
    position: absolute; bottom: 24px; left: 24px; right: 24px;
}
.ej-card-role {
    font-size: .72rem; font-weight: 800; color: #4ade80;
    text-transform: uppercase; letter-spacing: 2px; margin-bottom: 6px;
}
.ej-card-name {
    font-family: 'Outfit', sans-serif;
    font-size: 1.6rem; font-weight: 900; color: white; line-height: 1.1;
    text-shadow: 0 2px 10px rgba(0,0,0,.3);
}
.ej-card-status {
    position: absolute; top: 18px; left: 18px;
    background: rgba(255,255,255,.15); backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,.25);
    padding: 7px 14px; border-radius: 50px;
    color: white; font-size: .65rem; font-weight: 800; letter-spacing: 1.5px;
    display: inline-flex; align-items: center; gap: 7px;
}
.ej-status-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: #4ade80; box-shadow: 0 0 8px #4ade80;
    animation: ejPulse 2s ease-in-out infinite;
}

/* Card body */
.ej-card-body { padding: 28px; flex: 1; display: flex; flex-direction: column; }
.ej-card-zone {
    display: flex; align-items: center; gap: 14px;
    background: #f8fafc; border: 1px solid #e2e8f0;
    border-radius: 14px; padding: 14px 18px; margin-bottom: 24px;
}
.ej-zone-icon {
    width: 42px; height: 42px; background: white; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    color: #16a34a; font-size: 1.1rem;
    box-shadow: 0 4px 12px rgba(0,0,0,.06); flex-shrink: 0;
}
.ej-zone-label {
    font-size: .68rem; font-weight: 800; color: #94a3b8;
    text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 3px;
}
.ej-zone-value {
    font-size: .95rem; font-weight: 700; color: #1e293b;
}

.ej-card-actions { display: flex; gap: 12px; margin-top: auto; }
.ej-call-btn {
    width: 50px; height: 50px; background: #f8fafc;
    border: 1px solid #e2e8f0; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    color: #16a34a; font-size: 1.1rem; text-decoration: none;
    transition: all .35s; flex-shrink: 0;
}
.ej-call-btn:hover {
    background: #16a34a; color: white; border-color: #16a34a;
    transform: rotate(15deg) scale(1.1);
    box-shadow: 0 8px 20px rgba(22,163,74,.35);
}
.ej-wa-card-btn {
    flex: 1; display: flex; align-items: center; justify-content: center; gap: 10px;
    padding: 14px; background: linear-gradient(135deg, #25D366, #128c7e);
    color: white; border-radius: 14px; text-decoration: none;
    font-weight: 700; font-size: .9rem; font-family: 'Outfit', sans-serif;
    transition: all .35s; box-shadow: 0 8px 20px rgba(37,211,102,.25);
}
.ej-wa-card-btn:hover {
    transform: translateY(-3px) scale(1.02); color: white;
    box-shadow: 0 16px 35px rgba(37,211,102,.4);
}

/* ══════════════════════════════════════════════════════
   4. RESPONSIVE
   ══════════════════════════════════════════════════════ */
@media (max-width: 1100px) {
    .ej-layout { grid-template-columns: 1fr; }
    .ej-sidebar { position: static; }
    .ej-map-panel { height: 500px; }
}
@media (max-width: 768px) {
    .ej-cards-grid { grid-template-columns: 1fr; }
    .ej-hero-title { font-size: 3rem; }
}
</style>
@endpush

@section('content')

{{-- ══════════════════════════════════════════
     HERO
     ══════════════════════════════════════════ --}}
<section class="ej-hero">
    <div class="ej-hero-bg" id="ejHeroBg"></div>
    <div class="ej-hero-grid"></div>
    <div class="ej-hero-glow g1"></div>
    <div class="ej-hero-glow g2"></div>
    <canvas id="ejParticleCanvas"></canvas>

    <div class="ej-hero-inner">
        <div class="ej-hero-badge">
            <i class="fas fa-users"></i>
            Droguería &amp; Distribuidora
        </div>
        <h1 class="ej-hero-title">
            Nuestra Red de
            <span class="hl">Ejecutivos</span>
        </h1>
        <p class="ej-hero-sub">
            Contamos con profesionales altamente capacitados, distribuidos estratégicamente
            para brindarte atención personalizada en cada región.
        </p>
    </div>

    <div class="ej-hero-scroll">
        <div class="ej-scroll-line"></div>
        Conoce al equipo
    </div>
</section>

{{-- ══════════════════════════════════════════
     MAPA INTERACTIVO
     ══════════════════════════════════════════ --}}
<div class="ej-layout">
    {{-- Sidebar --}}
    <aside class="ej-sidebar ej-sr">
        <div class="ej-sidebar-label"><span>Localiza a tu asesor</span></div>
        <div class="ej-select-wrap">
            <i class="fas fa-search ej-select-icon"></i>
            <select id="select-rep" class="ej-select" onchange="showRep(this.value)">
                <option value="">Explorar todos los ejecutivos</option>
                @foreach($representatives as $rep)
                    <option value="{{ $rep->id }}">{{ $rep->nombre }}</option>
                @endforeach
            </select>
        </div>

        {{-- Rep preview --}}
        <div id="ej-rep-preview" class="ej-rep-preview">
            <div class="ej-rep-preview-img">
                <img id="ej-preview-img" src="" alt="Representante">
            </div>
            <div class="ej-rep-preview-name" id="ej-preview-name"></div>
            <div class="ej-rep-preview-zone" id="ej-preview-zone"></div>
            <div class="ej-rep-preview-info">
                <div class="ej-rep-info-row">
                    <i class="fas fa-phone-alt"></i>
                    <span id="ej-preview-phone"></span>
                </div>
                <div class="ej-rep-info-row">
                    <i class="fas fa-clock"></i>
                    <span>Lun - Vie: 8:00 AM - 6:00 PM</span>
                </div>
            </div>
            <a id="ej-preview-wa" href="#" class="ej-wa-btn">
                <i class="fab fa-whatsapp" style="font-size:1.3rem;"></i> Iniciar Chat
            </a>
        </div>
    </aside>

    {{-- Mapa --}}
    <main class="ej-map-panel ej-sr" style="transition-delay:.15s;">
        <div id="ej-map"></div>
        <div class="ej-map-badge">
            <div class="ej-map-pulse"></div>
            Cobertura Nacional Activa
        </div>
    </main>
</div>

{{-- ══════════════════════════════════════════
     EQUIPO
     ══════════════════════════════════════════ --}}
<section class="ej-team-section">
    <div class="ej-team-header ej-sr">
        <div class="ej-team-badge">
            <i class="fas fa-star"></i> Staff Profesional
        </div>
        <h2 class="ej-team-title">Expertos a su <span class="accent">Servicio</span></h2>
        <p class="ej-team-sub">Cada uno de nuestros ejecutivos cuenta con la experiencia técnica necesaria para potenciar su negocio farmacéutico.</p>
    </div>

    <div class="ej-cards-grid">
        @foreach($representatives as $i => $rep)
        @php
            $phone   = $rep->telefono ? preg_replace('/[^0-9]/', '', $rep->telefono) : '999999999';
            $message = urlencode("Hola {$rep->nombre}, deseo realizar una consulta sobre sus productos.");
            $waLink  = "https://api.whatsapp.com/send?phone=51{$phone}&text={$message}";
            $zona    = $rep->ubicacion ?? 'Nacional';
        @endphp
        <div class="ej-card ej-sr" style="transition-delay: {{ ($i % 3) * 0.1 }}s">
            {{-- Image --}}
            <div class="ej-card-img">
                @if($rep->imagen)
                    <img src="{{ asset('storage/'.$rep->imagen) }}" alt="{{ $rep->nombre }}">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($rep->nombre) }}&size=500&background=1B5E20&color=fff" alt="{{ $rep->nombre }}">
                @endif
                <div class="ej-card-img-gradient"></div>
                <div class="ej-card-status"><div class="ej-status-dot"></div> OFICIAL</div>
                <div class="ej-card-overlay-info">
                    <div class="ej-card-role">Asesor Comercial</div>
                    <div class="ej-card-name">{{ $rep->nombre }}</div>
                </div>
            </div>

            {{-- Body --}}
            <div class="ej-card-body">
                <div class="ej-card-zone">
                    <div class="ej-zone-icon"><i class="fas fa-map-marked-alt"></i></div>
                    <div>
                        <span class="ej-zone-label">Área de Cobertura</span>
                        <span class="ej-zone-value">{{ $zona }}</span>
                    </div>
                </div>

                <div class="ej-card-actions">
                    <a href="tel:{{ $phone }}" class="ej-call-btn" title="Llamar ahora">
                        <i class="fas fa-phone-alt"></i>
                    </a>
                    <a href="{{ $waLink }}" target="_blank" class="ej-wa-card-btn">
                        <i class="fab fa-whatsapp" style="font-size:1.2rem;"></i>
                        WhatsApp
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
/* ─── Hero BG ─── */
setTimeout(() => document.getElementById('ejHeroBg')?.classList.add('loaded'), 80);

/* ─── Particles ─── */
(function() {
    const canvas = document.getElementById('ejParticleCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    let pts = [];
    function resize() { canvas.width = window.innerWidth; canvas.height = window.innerHeight; }
    resize(); window.addEventListener('resize', resize);
    for (let i = 0; i < 60; i++) pts.push({
        x: Math.random() * canvas.width, y: Math.random() * canvas.height,
        r: Math.random() * 1.5 + .3, dx: (Math.random()-.5)*.35,
        dy: -Math.random()*.55-.15, o: Math.random()*.45+.1
    });
    function draw() {
        ctx.clearRect(0,0,canvas.width,canvas.height);
        pts.forEach(p => {
            ctx.beginPath(); ctx.arc(p.x,p.y,p.r,0,Math.PI*2);
            ctx.fillStyle = `rgba(74,222,128,${p.o})`; ctx.fill();
            p.x+=p.dx; p.y+=p.dy;
            if(p.y<-5){ p.y=canvas.height+5; p.x=Math.random()*canvas.width; }
            if(p.x<-5) p.x=canvas.width+5;
            if(p.x>canvas.width+5) p.x=-5;
        });
        requestAnimationFrame(draw);
    }
    draw();
})();

/* ─── Scroll reveal ─── */
const ejObs = new IntersectionObserver(entries => {
    entries.forEach(e => { if(e.isIntersecting) e.target.classList.add('in'); });
}, { threshold: .1, rootMargin: '0px 0px -50px 0px' });
document.querySelectorAll('.ej-sr').forEach(el => ejObs.observe(el));

/* ─── Map ─── */
let map, markersGroup;
const reps = @json($representatives->load('locations.zona'));

document.addEventListener('DOMContentLoaded', () => {
    map = L.map('ej-map', { zoomControl: false }).setView([-9.19, -75.01], 6);
    L.control.zoom({ position: 'topright' }).addTo(map);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; Sanchez Pharma'
    }).addTo(map);
    markersGroup = L.featureGroup().addTo(map);
    showAllMarkers();
});

const colors = ['#22c55e','#3b82f6','#f59e0b','#8b5cf6','#ec4899','#06b6d4','#f97316'];

function createIcon(color) {
    return L.divIcon({
        className: '',
        html: `<div style="width:18px;height:18px;border-radius:50%;background:${color};border:3px solid white;box-shadow:0 4px 12px rgba(0,0,0,.25)"></div>`,
        iconSize: [18,18], iconAnchor: [9,9]
    });
}
function showAllMarkers() {
    markersGroup.clearLayers();
    reps.forEach((r, idx) => {
        r.locations.forEach(loc => {
            L.marker([loc.latitud, loc.longitud], { icon: createIcon(colors[idx % colors.length]) })
             .addTo(markersGroup)
             .on('click', () => showRepSidebar(r));
        });
    });
}
function showRep(id) {
    if (!id) { showAllMarkers(); hideRepSidebar(); return; }
    const r = reps.find(x => x.id == id);
    if (!r) return;
    markersGroup.clearLayers();
    showRepSidebar(r);
    r.locations.forEach(loc => {
        if (loc.latitud && loc.longitud) {
            L.marker([loc.latitud, loc.longitud], {
                icon: createIcon(colors[reps.indexOf(r) % colors.length])
            }).addTo(markersGroup).bindPopup(`<b>${r.nombre}</b><br>${loc.zona?.nombre_zona||''}`);
        }
    });
    if (markersGroup.getLayers().length) map.flyToBounds(L.featureGroup(markersGroup.getLayers()).getBounds().pad(.5));
}
function showRepSidebar(r) {
    const preview = document.getElementById('ej-rep-preview');
    preview.style.display = 'block';
    const imgSrc = r.imagen
        ? (r.imagen.startsWith('http') ? r.imagen : '/storage/' + r.imagen)
        : `https://ui-avatars.com/api/?name=${encodeURIComponent(r.nombre)}&size=400&background=10b981&color=fff`;
    document.getElementById('ej-preview-img').src = imgSrc;
    document.getElementById('ej-preview-name').textContent = r.nombre;
    const zona = r.ubicacion || 'Nacional';
    document.getElementById('ej-preview-zone').textContent = zona;
    document.getElementById('ej-preview-phone').textContent = r.telefono || 'Consultar';
    const phone = r.telefono ? String(r.telefono).replace(/\D/g,'') : '';
    const wa = document.getElementById('ej-preview-wa');
    if (phone) {
        const msg = encodeURIComponent('Hola, quisiera atención para cotizar un pedido por favor.');
        const waUrl = 'https://wa.me/51' + phone + '?text=' + msg;
        wa.href = waUrl;
        wa.onclick = function(e) {
            e.preventDefault();
            window.open(waUrl, '_blank');
        };
        wa.style.display = 'flex';
    } else {
        wa.href = '#';
        wa.onclick = function(e) { e.preventDefault(); };
        wa.style.display = 'none';
    }
}
function hideRepSidebar() {
    document.getElementById('ej-rep-preview').style.display = 'none';
}
</script>
@endpush
