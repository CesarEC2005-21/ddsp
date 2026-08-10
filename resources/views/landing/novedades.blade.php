@extends('layouts.landing')

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════
   NOVEDADES — Premium Redesign
   ═══════════════════════════════════════════════════════ */
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800;900&display=swap');

/* ── Variables ── */
:root {
    --promo-color: #ef4444;
    --event-color: #f59e0b;
    --promo-glow:  rgba(239,68,68,.25);
    --event-glow:  rgba(245,158,11,.25);
}

/* ── Scroll reveal ── */
.nr { opacity: 0; transform: translateY(40px); transition: opacity .7s ease, transform .7s ease; }
.nr.in { opacity: 1; transform: none; }

/* ══════════════════════════════════════════════════════
   1. HERO
   ══════════════════════════════════════════════════════ */
.nt-hero {
    position: relative;
    min-height: 60vh;
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
    background: var(--slate-900, #0f172a);
}
.nt-hero-bg {
    position: absolute; inset: 0;
    background:
        url('{{ ($banner && $banner->image_path) ? asset("storage/".$banner->image_path) : "https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=1920&q=80" }}')
        center/cover no-repeat;
    filter: brightness(.28) saturate(1.3);
    transform: scale(1.08);
    transition: transform 12s ease-out;
}
.nt-hero-bg.loaded { transform: scale(1); }
.nt-hero-grid {
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(34,197,94,.08) 1px, transparent 1px),
        linear-gradient(90deg, rgba(34,197,94,.08) 1px, transparent 1px);
    background-size: 60px 60px;
    animation: ntGridFloat 20s linear infinite;
}
@keyframes ntGridFloat { to { background-position: 60px 60px; } }

.nt-hero-glow {
    position: absolute; border-radius: 50%;
    filter: blur(80px); opacity: .45;
    animation: ntGlowPulse 6s ease-in-out infinite alternate;
}
.nt-hero-glow.g1 { width: 500px; height: 500px; background: radial-gradient(circle, #22c55e, transparent); top: -150px; right: -100px; }
.nt-hero-glow.g2 { width: 400px; height: 400px; background: radial-gradient(circle, #059669, transparent); bottom: -100px; left: -80px; animation-delay: -3s; }
@keyframes ntGlowPulse { from { opacity:.3; transform:scale(.9); } to { opacity:.6; transform:scale(1.1); } }

#ntParticleCanvas { position: absolute; inset: 0; pointer-events: none; }

.nt-hero-inner {
    position: relative; z-index: 10;
    text-align: center;
    padding: 0 20px;
    max-width: 900px;
}
.nt-hero-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(34,197,94,.15);
    border: 1px solid rgba(34,197,94,.4);
    color: #4ade80;
    padding: 8px 20px; border-radius: 50px;
    font-size: .8rem; font-weight: 700;
    letter-spacing: 2px; text-transform: uppercase;
    margin-bottom: 28px;
    backdrop-filter: blur(10px);
    animation: ntBadgePop .6s .3s both cubic-bezier(.175,.885,.32,1.275);
}
@keyframes ntBadgePop { from { opacity:0; transform:scale(.8) translateY(10px); } to { opacity:1; transform:none; } }

.nt-hero-title {
    font-family: 'Outfit', sans-serif;
    font-size: clamp(3rem, 8vw, 6.5rem);
    font-weight: 900; color: white;
    line-height: 1; margin-bottom: 12px;
    animation: ntHeroTitle .9s .5s both;
}
@keyframes ntHeroTitle { from { opacity:0; transform:translateY(40px); } to { opacity:1; transform:none; } }

.nt-hero-title .hl {
    background: linear-gradient(135deg, #4ade80 0%, #22c55e 50%, #a3e635 100%);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text; display: block;
}
.nt-hero-sub {
    font-size: clamp(1rem, 2vw, 1.25rem);
    color: rgba(255,255,255,.7);
    max-width: 600px; margin: 24px auto 0;
    line-height: 1.7;
    animation: ntHeroTitle 1s .7s both;
}

/* Scroll hint — igual que Nosotros */
.nt-hero-scroll {
    position: absolute; bottom: 40px; left: 0; width: 100%;
    display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px;
    color: rgba(255,255,255,.5);
    font-size: .75rem; letter-spacing: 2px; text-transform: uppercase;
    animation: ntHeroTitle 1s 1s both;
}
.nt-scroll-line {
    width: 1px; height: 50px;
    background: linear-gradient(to bottom, rgba(34,197,94,.8), transparent);
    animation: ntScrollLine 2s ease-in-out infinite;
}
@keyframes ntScrollLine { 0%,100%{ opacity:.3; transform:scaleY(.3) translateY(-10px); } 50%{ opacity:1; transform:scaleY(1) translateY(0); } }

/* ══════════════════════════════════════════════════════
   2. FILTER BAR
   ══════════════════════════════════════════════════════ */
.nt-filter-wrap {
    position: relative; z-index: 10;
    display: flex; justify-content: center;
    padding: 0 20px;
    margin-top: -28px;
}
.nt-filter-bar {
    background: white;
    padding: 6px;
    border-radius: 50px;
    box-shadow: 0 20px 50px rgba(0,0,0,.14), 0 0 0 1px rgba(226,232,240,.8);
    display: inline-flex; gap: 4px;
}
.nt-filter-btn {
    padding: 11px 28px; border-radius: 50px;
    border: none; background: transparent;
    color: #64748b; font-weight: 700;
    cursor: pointer; transition: all .3s ease;
    font-size: .85rem; font-family: 'Outfit', sans-serif;
    display: inline-flex; align-items: center; gap: 7px;
}
.nt-filter-btn:hover { color: #16a34a; background: #f0fdf4; }
.nt-filter-btn.active {
    background: linear-gradient(135deg, #22c55e, #15803d);
    color: white;
    box-shadow: 0 8px 20px rgba(34,197,94,.35);
}
.nt-count-badge {
    display: inline-flex; align-items: center; justify-content: center;
    width: 20px; height: 20px; border-radius: 50%;
    background: rgba(255,255,255,.25);
    font-size: .7rem; font-weight: 800;
}
.nt-filter-btn:not(.active) .nt-count-badge {
    background: #e2e8f0; color: #64748b;
}

/* ══════════════════════════════════════════════════════
   3. GRID
   ══════════════════════════════════════════════════════ */
.nt-section {
    padding: 60px 5% 100px;
    max-width: 1400px; margin: 0 auto;
}
.nt-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 28px;
}

/* ── Card ── */
.nt-card {
    background: white;
    border-radius: 24px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 20px rgba(0,0,0,.04);
    cursor: pointer;
    transition: transform .4s cubic-bezier(.175,.885,.32,1.275), box-shadow .4s ease, border-color .4s ease;
    display: flex; flex-direction: column;
    position: relative;
}
.nt-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, #22c55e, #15803d);
    opacity: 0;
    transform: scaleX(0);
    transform-origin: left;
    transition: opacity .4s, transform .4s;
}
.nt-card:hover::before { opacity: 1; transform: scaleX(1); }
.nt-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 30px 60px rgba(0,0,0,.12), 0 0 0 1px rgba(34,197,94,.12);
    border-color: rgba(34,197,94,.2);
}

/* ── Card image ── */
.nt-card-img {
    position: relative;
    height: 220px;
    background: #f8fafc;
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
    padding: 24px;
    border-bottom: 1px solid #f1f5f9;
}
.nt-card-img img {
    max-width: 100%; max-height: 100%;
    object-fit: contain;
    transition: transform .5s cubic-bezier(.175,.885,.32,1.275);
}
.nt-card:hover .nt-card-img img { transform: scale(1.07); }

.nt-card-img-bg {
    position: absolute; inset: 0;
    opacity: 0; transition: opacity .4s;
}
.nt-card:hover .nt-card-img-bg { opacity: 1; }
.promo-bg { background: radial-gradient(ellipse at center, rgba(239,68,68,.06), transparent); }
.event-bg { background: radial-gradient(ellipse at center, rgba(245,158,11,.06), transparent); }

.nt-type-pill {
    position: absolute; top: 14px; left: 14px;
    padding: 5px 14px; border-radius: 50px;
    font-size: .65rem; font-weight: 800;
    text-transform: uppercase; letter-spacing: 1px;
    color: white; z-index: 2;
    display: inline-flex; align-items: center; gap: 5px;
}
.promo-pill { background: linear-gradient(135deg, #ef4444, #dc2626); box-shadow: 0 4px 12px rgba(239,68,68,.35); }
.event-pill { background: linear-gradient(135deg, #f59e0b, #d97706); box-shadow: 0 4px 12px rgba(245,158,11,.35); }

.nt-card-img-empty {
    font-size: 3.5rem; color: #e2e8f0;
    transition: color .3s;
}
.nt-card:hover .nt-card-img-empty { color: #22c55e; }

/* ── Card body ── */
.nt-card-body { padding: 24px; flex: 1; display: flex; flex-direction: column; }
.nt-card-lab {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: .72rem; font-weight: 800;
    color: #16a34a; text-transform: uppercase; letter-spacing: 1px;
    background: rgba(34,197,94,.08);
    padding: 4px 12px; border-radius: 50px;
    width: fit-content; margin-bottom: 14px;
}
.nt-card-title {
    font-family: 'Outfit', sans-serif;
    font-size: 1.1rem; font-weight: 800;
    color: #0f172a; line-height: 1.4;
    margin-bottom: 16px;
    display: -webkit-box;
    -webkit-line-clamp: 2; -webkit-box-orient: vertical;
    overflow: hidden;
    transition: color .3s;
}
.nt-card:hover .nt-card-title { color: #16a34a; }

.nt-card-dates {
    display: flex; gap: 8px; flex-wrap: wrap;
    margin-bottom: 20px; margin-top: auto;
}
.nt-date-chip {
    display: inline-flex; align-items: center; gap: 6px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 5px 12px; border-radius: 8px;
    font-size: .78rem; color: #475569; font-weight: 600;
}
.nt-date-chip i { color: #22c55e; font-size: .72rem; }

.nt-card-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding-top: 16px;
    border-top: 1px solid #f1f5f9;
}
.nt-ver-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 22px; border-radius: 10px;
    background: linear-gradient(135deg, #22c55e, #15803d);
    color: white; font-weight: 700; font-size: .85rem;
    border: none; cursor: pointer;
    transition: all .35s ease;
    box-shadow: 0 6px 16px rgba(34,197,94,.3);
}
.nt-ver-btn:hover { transform: scale(1.05); box-shadow: 0 10px 25px rgba(34,197,94,.45); }
.nt-ver-btn i { transition: transform .3s; }
.nt-ver-btn:hover i { transform: translateX(4px); }

.nt-card-arrow {
    width: 36px; height: 36px;
    background: #f8fafc; border: 1px solid #e2e8f0;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: #94a3b8; font-size: .8rem;
    transition: all .35s;
}
.nt-card:hover .nt-card-arrow {
    background: #f0fdf4; border-color: #bbf7d0;
    color: #16a34a; transform: rotate(-45deg);
}

/* ── Empty state ── */
.nt-empty {
    grid-column: 1/-1;
    text-align: center;
    padding: 80px 40px;
    background: white;
    border-radius: 28px;
    border: 2px dashed #e2e8f0;
}
.nt-empty-icon {
    width: 80px; height: 80px;
    background: #f8fafc;
    border-radius: 24px;
    display: flex; align-items: center; justify-content: center;
    font-size: 2rem; color: #cbd5e1;
    margin: 0 auto 20px;
}
.nt-empty p {
    color: #94a3b8; font-size: 1rem;
    font-weight: 600; margin: 0;
}

/* ══════════════════════════════════════════════════════
   4. MODAL
   ══════════════════════════════════════════════════════ */
.nt-modal-overlay {
    position: fixed; inset: 0;
    background: rgba(15,23,42,.88);
    backdrop-filter: blur(12px);
    z-index: 9999;
    display: flex; align-items: center; justify-content: center;
    padding: 20px;
    opacity: 0; visibility: hidden;
    transition: opacity .4s ease, visibility .4s ease;
}
.nt-modal-overlay.open { opacity: 1; visibility: visible; }

.nt-modal {
    background: white;
    width: 100%; max-width: 1020px;
    border-radius: 28px;
    overflow: hidden;
    display: flex;
    max-height: 90vh;
    transform: scale(.92) translateY(24px);
    transition: transform .5s cubic-bezier(.165,.84,.44,1);
    box-shadow: 0 60px 120px rgba(0,0,0,.5);
    position: relative;
}
.nt-modal-overlay.open .nt-modal { transform: none; }

/* ── Modal close ── */
.nt-modal-close {
    position: absolute; top: 20px; right: 20px;
    width: 42px; height: 42px;
    background: rgba(241,245,249,.9);
    backdrop-filter: blur(8px);
    border: none; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: #64748b;
    font-size: .9rem;
    transition: all .3s;
    z-index: 10;
}
.nt-modal-close:hover { background: #ef4444; color: white; transform: rotate(90deg); }

/* ── Modal image pane ── */
.nt-modal-img {
    width: 46%;
    background: #f8fafc;
    display: flex; align-items: center; justify-content: center;
    padding: 40px; position: relative;
    border-right: 1px solid #f1f5f9;
    overflow: hidden;
    cursor: zoom-in;
    flex-shrink: 0;
}
.nt-modal-img.zoomed { cursor: zoom-out; }
.nt-modal-img img {
    max-width: 100%; max-height: 100%;
    object-fit: contain;
    filter: drop-shadow(0 20px 40px rgba(0,0,0,.12));
    transition: transform .4s ease;
}
.nt-modal-img.zoomed img { transform: scale(2.5); }
.nt-zoom-tip {
    position: absolute; bottom: 16px; left: 50%;
    transform: translateX(-50%);
    background: rgba(15,23,42,.65);
    color: white; padding: 5px 14px; border-radius: 20px;
    font-size: .7rem; font-weight: 600;
    display: inline-flex; align-items: center; gap: 6px;
    backdrop-filter: blur(6px);
    pointer-events: none; white-space: nowrap;
}

/* ── Modal info pane ── */
.nt-modal-info {
    flex: 1; padding: 48px 40px;
    overflow-y: auto; display: flex; flex-direction: column;
}
.nt-modal-tag {
    display: inline-flex; align-items: center; gap: 8px;
    font-size: .75rem; font-weight: 800;
    color: #16a34a; letter-spacing: 2px; text-transform: uppercase;
    margin-bottom: 16px;
}
.nt-modal-tag-pill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 12px; border-radius: 50px;
    font-size: .65rem; font-weight: 800;
    color: white; margin-left: 8px;
}
.nt-modal-title {
    font-family: 'Outfit', sans-serif;
    font-size: 1.7rem; font-weight: 900;
    color: #0f172a; line-height: 1.25;
    margin-bottom: 24px;
}
.nt-modal-desc {
    font-size: 1rem; line-height: 1.75;
    color: #475569; margin-bottom: 24px;
    font-weight: 500;
}
.nt-modal-detail-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 16px; padding: 24px;
    margin-bottom: 24px;
}
.nt-modal-detail-label {
    font-size: .72rem; font-weight: 800;
    text-transform: uppercase; letter-spacing: 1.5px;
    color: #94a3b8; margin-bottom: 12px;
}
.nt-modal-detail-text {
    font-size: .95rem; color: #475569;
    line-height: 1.7; white-space: pre-line;
}
.nt-modal-dates {
    display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 24px;
}
.nt-modal-date-chip {
    display: inline-flex; align-items: center; gap: 8px;
    background: #f0fdf4; border: 1px solid #bbf7d0;
    padding: 8px 16px; border-radius: 10px;
    font-size: .85rem; color: #166534; font-weight: 700;
}
.nt-modal-date-chip i { color: #22c55e; }
.nt-modal-actions { margin-top: auto; padding-top: 20px; display: flex; gap: 12px; }
.nt-modal-cta {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 13px 28px; border-radius: 12px;
    font-weight: 700; font-size: .9rem;
    text-decoration: none; border: none; cursor: pointer;
    transition: all .35s ease;
}
.nt-modal-cta-primary {
    background: linear-gradient(135deg, #22c55e, #15803d);
    color: white;
    box-shadow: 0 8px 20px rgba(34,197,94,.35);
}
.nt-modal-cta-primary:hover { transform: translateY(-2px); box-shadow: 0 14px 30px rgba(34,197,94,.5); color: white; }
.nt-modal-footer {
    display: flex; align-items: center; gap: 12px;
    background: #f0fdf4; border: 1px solid #dcfce7;
    border-radius: 14px; padding: 16px 20px;
    margin-top: 20px;
}
.nt-modal-footer i { color: #22c55e; font-size: 1.2rem; }
.nt-modal-footer span { font-size: .85rem; color: #166534; font-weight: 600; line-height: 1.5; }

/* ══════════════════════════════════════════════════════
   5. RESPONSIVE
   ══════════════════════════════════════════════════════ */
@media (max-width: 900px) {
    .nt-modal { flex-direction: column; max-height: 95vh; }
    .nt-modal-img { width: 100%; height: 300px; border-right: none; border-bottom: 1px solid #f1f5f9; padding: 24px; }
    .nt-modal-info { padding: 32px 24px; }
}
@media (max-width: 768px) {
    .nt-grid { grid-template-columns: 1fr; }
    .nt-hero-title { font-size: 2.8rem; }
}
@media (max-width: 480px) {
    .nt-card-img { height: 180px; }
    .nt-modal-title { font-size: 1.4rem; }
}
</style>
@endpush

@section('content')

{{-- ══════════════════════════════════════════
     HERO
     ══════════════════════════════════════════ --}}
<section class="nt-hero">
    <div class="nt-hero-bg" id="ntHeroBg"></div>
    <div class="nt-hero-grid"></div>
    <div class="nt-hero-glow g1"></div>
    <div class="nt-hero-glow g2"></div>
    <canvas id="ntParticleCanvas"></canvas>

    <div class="nt-hero-inner">
        <div class="nt-hero-badge">
            <i class="fas fa-newspaper"></i>
            Droguería &amp; Distribuidora
        </div>
        <h1 class="nt-hero-title">
            Novedades
            <span class="hl">y más</span>
        </h1>
        <p class="nt-hero-sub">
            Promociones exclusivas, eventos y las últimas novedades de Droguería Sánchez Pharma para mantenerte siempre informado.
        </p>
    </div>

    <div class="nt-hero-scroll">
        <div class="nt-scroll-line"></div>
        Descubre más
    </div>
</section>

{{-- ══════════════════════════════════════════
     FILTER BAR
     ══════════════════════════════════════════ --}}
<div class="nt-filter-wrap" style="margin-top: 40px;">
    @php
        $total     = $novedades->count();
        $nPromo    = $novedades->where('tipo','PROMOCION')->count();
        $nEvento   = $novedades->where('tipo','EVENTO')->count();
    @endphp
    <div class="nt-filter-bar">
        <button class="nt-filter-btn active" onclick="filterCards('TODOS', this)" id="btn-todos">
            <i class="fas fa-border-all"></i> Todos
            <span class="nt-count-badge">{{ $total }}</span>
        </button>
        <button class="nt-filter-btn" onclick="filterCards('PROMOCION', this)" id="btn-promo">
            <i class="fas fa-tag"></i> Promociones
            <span class="nt-count-badge">{{ $nPromo }}</span>
        </button>
        <button class="nt-filter-btn" onclick="filterCards('EVENTO', this)" id="btn-evento">
            <i class="fas fa-calendar-star"></i> Eventos
            <span class="nt-count-badge">{{ $nEvento }}</span>
        </button>
    </div>
</div>

{{-- ══════════════════════════════════════════
     CARDS GRID
     ══════════════════════════════════════════ --}}
<div class="nt-section">
    <div class="nt-grid" id="nt-grid">
        @forelse($novedades as $i => $novedad)
        @php
            \Carbon\Carbon::setLocale('es');
            $fi  = \Carbon\Carbon::parse($novedad->fecha_inicial)->translatedFormat('d \d\e F, Y');
            $ff  = \Carbon\Carbon::parse($novedad->fecha_final)->translatedFormat('d \d\e F, Y');
            $esPromo = $novedad->tipo === 'PROMOCION';
            $novedadData = [
                'tipo'        => $novedad->tipo,
                'laboratory'  => $novedad->laboratory->descripcion ?? 'Sanchez Pharma',
                'descripcion' => $novedad->descripcion,
                'detalle'     => $novedad->detalle,
                'imagen'      => asset('storage/'.$novedad->imagen),
                'inicio'      => $fi,
                'fin'         => $ff,
                'product_url' => $novedad->product_id ? route('product.detail', $novedad->product_id) : null,
            ];
        @endphp
        <div class="nt-card nr"
             data-tipo="{{ $novedad->tipo }}"
             style="transition-delay: {{ ($i % 4) * 0.07 }}s"
             onclick='openModal(@json($novedadData))'>

            {{-- Image --}}
            <div class="nt-card-img">
                <div class="nt-card-img-bg {{ $esPromo ? 'promo-bg' : 'event-bg' }}"></div>
                <div class="nt-type-pill {{ $esPromo ? 'promo-pill' : 'event-pill' }}">
                    <i class="fas fa-{{ $esPromo ? 'tag' : 'calendar-star' }}"></i>
                    {{ $novedad->tipo }}
                </div>
                @if($novedad->imagen)
                    <img src="{{ asset('storage/'.$novedad->imagen) }}" alt="{{ $novedad->descripcion }}" loading="lazy">
                @else
                    <div class="nt-card-img-empty"><i class="fas fa-image"></i></div>
                @endif
            </div>

            {{-- Body --}}
            <div class="nt-card-body">
                <div class="nt-card-lab">
                    <i class="fas fa-flask"></i>
                    {{ $novedad->laboratory->descripcion ?? 'Sanchez Pharma' }}
                </div>
                <div class="nt-card-title">{{ $novedad->descripcion }}</div>

                <div class="nt-card-dates">
                    <span class="nt-date-chip">
                        <i class="fas fa-calendar-alt"></i>
                        {{ $fi }}
                    </span>
                    <span class="nt-date-chip">
                        <i class="fas fa-flag-checkered"></i>
                        {{ $ff }}
                    </span>
                </div>

                <div class="nt-card-footer">
                    <button class="nt-ver-btn">
                        Ver más <i class="fas fa-arrow-right"></i>
                    </button>
                    <div class="nt-card-arrow">
                        <i class="fas fa-arrow-up-right"></i>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="nt-empty">
            <div class="nt-empty-icon"><i class="fas fa-newspaper"></i></div>
            <p>No hay novedades disponibles en este momento.</p>
        </div>
        @endforelse
    </div>
</div>

{{-- ══════════════════════════════════════════
     MODAL
     ══════════════════════════════════════════ --}}
<div class="nt-modal-overlay" id="ntModal" onclick="if(event.target===this) closeModal()">
    <div class="nt-modal">
        <button class="nt-modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>

        {{-- Image pane --}}
        <div class="nt-modal-img" id="ntModalImgPane">
            <img src="" id="ntModalImg" alt="Imagen de noticia">
            <div class="nt-zoom-tip"><i class="fas fa-magnifying-glass-plus"></i> Clic para ampliar</div>
        </div>

        {{-- Info pane --}}
        <div class="nt-modal-info">
            <div class="nt-modal-tag">
                <i class="fas fa-flask"></i>
                <span id="ntModalLab"></span>
                <span class="nt-modal-tag-pill" id="ntModalTipoPill"></span>
            </div>
            <h2 class="nt-modal-title" id="ntModalTitle"></h2>
            <p class="nt-modal-desc" id="ntModalDesc"></p>

            <div class="nt-modal-detail-box" id="ntDetailBox">
                <div class="nt-modal-detail-label">Detalles y Condiciones</div>
                <div class="nt-modal-detail-text" id="ntModalDetalle"></div>
            </div>

            <div class="nt-modal-dates">
                <span class="nt-modal-date-chip">
                    <i class="fas fa-calendar-alt"></i>
                    <span id="ntModalStart"></span>
                </span>
                <span class="nt-modal-date-chip">
                    <i class="fas fa-flag-checkered"></i>
                    <span id="ntModalEnd"></span>
                </span>
            </div>

            <div class="nt-modal-actions" id="ntModalActions">
                <a href="#" id="ntModalCatalogBtn" class="nt-modal-cta nt-modal-cta-primary" style="display:none;">
                    <i class="fas fa-shopping-cart"></i> Ver en Catálogo
                </a>
            </div>

            <div class="nt-modal-footer">
                <i class="fas fa-circle-info"></i>
                <span>Consulta disponibilidad con tu ejecutivo de confianza o en nuestras sedes oficiales.</span>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
/* ─── Hero BG: Ken-Burns load ─── */
setTimeout(() => document.getElementById('ntHeroBg')?.classList.add('loaded'), 80);

/* ─── Particle Canvas (igual que Nosotros) ─── */
(function () {
    const canvas = document.getElementById('ntParticleCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    let particles = [];
    function resize() { canvas.width = window.innerWidth; canvas.height = window.innerHeight; }
    resize();
    window.addEventListener('resize', resize);
    for (let i = 0; i < 60; i++) {
        particles.push({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height,
            r: Math.random() * 1.5 + 0.3,
            dx: (Math.random() - .5) * .35,
            dy: -Math.random() * .55 - .15,
            o: Math.random() * .45 + .1
        });
    }
    function draw() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        particles.forEach(p => {
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(74,222,128,${p.o})`;
            ctx.fill();
            p.x += p.dx; p.y += p.dy;
            if (p.y < -5) { p.y = canvas.height + 5; p.x = Math.random() * canvas.width; }
            if (p.x < -5) p.x = canvas.width + 5;
            if (p.x > canvas.width + 5) p.x = -5;
        });
        requestAnimationFrame(draw);
    }
    draw();
})();

/* ─── Scroll reveal ─── */
const ntObs = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            const d = parseFloat(e.target.style.transitionDelay || 0);
            setTimeout(() => e.target.classList.add('in'), d * 1000);
        }
    });
}, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
document.querySelectorAll('.nr').forEach(el => ntObs.observe(el));

/* ─── Filter ─── */
function filterCards(tipo, btn) {
    document.querySelectorAll('.nt-filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.nt-card').forEach(card => {
        const match = tipo === 'TODOS' || card.dataset.tipo === tipo;
        card.style.display = match ? '' : 'none';
    });
}

/* ─── Modal zoom ─── */
const imgPane = document.getElementById('ntModalImgPane');
const modalImg = document.getElementById('ntModalImg');
let zoomed = false;
imgPane.addEventListener('click', () => {
    zoomed = !zoomed;
    imgPane.classList.toggle('zoomed', zoomed);
    if (!zoomed) modalImg.style.transformOrigin = 'center center';
});
imgPane.addEventListener('mousemove', e => {
    if (!zoomed) return;
    const r = imgPane.getBoundingClientRect();
    modalImg.style.transformOrigin = `${((e.clientX-r.left)/r.width*100)}% ${((e.clientY-r.top)/r.height*100)}%`;
});

/* ─── Open Modal ─── */
function openModal(data) {
    // reset zoom
    zoomed = false;
    imgPane.classList.remove('zoomed');
    modalImg.style.transformOrigin = 'center center';

    modalImg.src = data.imagen;
    document.getElementById('ntModalLab').textContent = data.laboratory;

    const pill = document.getElementById('ntModalTipoPill');
    const esPromo = data.tipo === 'PROMOCION';
    pill.textContent = data.tipo;
    pill.style.cssText = esPromo
        ? 'background:linear-gradient(135deg,#ef4444,#dc2626); box-shadow:0 4px 12px rgba(239,68,68,.35);'
        : 'background:linear-gradient(135deg,#f59e0b,#d97706); box-shadow:0 4px 12px rgba(245,158,11,.35);';

    document.getElementById('ntModalTitle').textContent   = data.descripcion;
    document.getElementById('ntModalDesc').textContent    = data.descripcion;
    document.getElementById('ntModalDetalle').textContent = data.detalle || 'No hay detalles adicionales para esta publicación.';
    document.getElementById('ntModalStart').innerHTML = '<strong>Válido desde:</strong> ' + data.inicio;
    document.getElementById('ntModalEnd').innerHTML   = '<strong>Válido hasta:</strong> ' + data.fin;

    const catBtn = document.getElementById('ntModalCatalogBtn');
    catBtn.style.display = data.product_url ? 'inline-flex' : 'none';
    if (data.product_url) {
        catBtn.href = data.product_url;
        catBtn.onclick = function(e) {
            e.stopPropagation();
            window.location.href = data.product_url;
        };
    } else {
        catBtn.onclick = null;
    }

    document.getElementById('ntModal').classList.add('open');
    document.body.style.overflow = 'hidden';
}

/* ─── Close Modal ─── */
function closeModal() {
    document.getElementById('ntModal').classList.remove('open');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
</script>
@endpush
