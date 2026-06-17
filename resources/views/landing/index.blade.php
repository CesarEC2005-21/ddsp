@extends('layouts.landing')

@push('styles')
<link rel="preload" href="{{ asset('img/hero.png') }}" as="image">
<link rel="stylesheet" href="{{ asset('css/landing/home.css') }}">
<link rel="stylesheet" href="{{ asset('css/landing/home_labs.css') }}">
<link rel="stylesheet" href="{{ asset('css/landing/home_products.css') }}">
<style>
/* ═══════════════════════════════════════════════════════
   HOME — Upgrade Styles (overlay over existing CSS)
   ═══════════════════════════════════════════════════════ */
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800;900&display=swap');

/* ── Hero upgrade ── */
.hero-carousel { height: 100vh; min-height: 700px; }

/* Animated gradient border on hero text */
.animate-title {
    font-family: 'Outfit', sans-serif !important;
    font-size: clamp(2.8rem, 7vw, 5.5rem) !important;
    font-weight: 900 !important;
    line-height: 1.05 !important;
    color: white !important;
    text-shadow: 0 4px 30px rgba(0,0,0,0.4);
    letter-spacing: -1px;
}
.animate-title .hl {
    background: linear-gradient(135deg, #4ade80 0%, #22c55e 60%, #a3e635 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    display: inline;
}
.animate-text {
    font-size: clamp(1.05rem, 2vw, 1.3rem) !important;
    color: rgba(255,255,255,.82) !important;
    max-width: 680px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.75 !important;
    letter-spacing: .2px;
}

/* Hero bottom gradient overlay */
.hero-carousel::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 220px;
    background: linear-gradient(to top, rgba(0,0,0,.5), transparent);
    z-index: 5;
    pointer-events: none;
}

/* Scroll indicator */
.hero-scroll-hint {
    position: absolute;
    bottom: 55px; left: 50%;
    transform: translateX(-50%);
    z-index: 20;
    display: flex; flex-direction: column; align-items: center; gap: 8px;
    color: rgba(255,255,255,.55);
    font-size: .7rem; letter-spacing: 2px; text-transform: uppercase;
}
.hero-scroll-mouse {
    width: 24px; height: 38px;
    border: 2px solid rgba(255,255,255,.35);
    border-radius: 12px;
    position: relative;
    display: flex; justify-content: center; padding-top: 7px;
}
.hero-scroll-mouse::before {
    content: '';
    width: 4px; height: 8px;
    background: rgba(255,255,255,.6);
    border-radius: 2px;
    animation: scrollWheel 2s ease-in-out infinite;
}
@keyframes scrollWheel {
    0%   { transform: translateY(0); opacity: 1; }
    80%  { transform: translateY(12px); opacity: 0; }
    100% { transform: translateY(0); opacity: 0; }
}

/* Upgraded hero buttons */
.hero-btns { gap: 16px !important; }
.btn-primary {
    background: linear-gradient(135deg, #22c55e, #15803d) !important;
    border-color: transparent !important;
    box-shadow: 0 12px 30px rgba(34,197,94,.4) !important;
    font-family: 'Outfit', sans-serif !important;
    font-weight: 700 !important;
    padding: 17px 38px !important;
    letter-spacing: .3px;
    transition: all .35s ease !important;
}
.btn-primary:hover {
    transform: translateY(-3px) !important;
    box-shadow: 0 20px 45px rgba(34,197,94,.55) !important;
    color: white !important;
    background: linear-gradient(135deg, #16a34a, #166534) !important;
}
.btn-outline {
    border: 2px solid rgba(255,255,255,.5) !important;
    backdrop-filter: blur(8px) !important;
    font-family: 'Outfit', sans-serif !important;
    font-weight: 700 !important;
    padding: 17px 38px !important;
    transition: all .35s ease !important;
}
.btn-outline:hover {
    background: rgba(255,255,255,.15) !important;
    border-color: rgba(255,255,255,.8) !important;
    transform: translateY(-3px) !important;
    color: white !important;
}

/* Carousel indicators – pill style */
.indicator {
    width: 10px !important; height: 10px !important;
    background: rgba(255,255,255,.35) !important;
    border-radius: 10px !important;
    transition: all .4s ease !important;
}
.indicator.active {
    width: 36px !important;
    background: #22c55e !important;
}

/* ── Feature strip (NEW section between hero & stats) ── */
.feature-strip {
    background: #0f172a;
    padding: 0;
    overflow: hidden;
}
.feature-strip-inner {
    display: flex;
    max-width: 1400px;
    margin: 0 auto;
}
.feature-item {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 18px;
    padding: 30px 36px;
    border-right: 1px solid rgba(255,255,255,.06);
    transition: background .3s;
}
.feature-item:last-child { border-right: none; }
.feature-item:hover { background: rgba(34,197,94,.07); }
.feature-icon {
    width: 50px; height: 50px; flex-shrink: 0;
    background: linear-gradient(135deg, rgba(34,197,94,.2), rgba(5,150,105,.15));
    border: 1px solid rgba(34,197,94,.3);
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem; color: #4ade80;
    transition: all .35s;
}
.feature-item:hover .feature-icon {
    transform: scale(1.1) rotate(-5deg);
    box-shadow: 0 8px 20px rgba(34,197,94,.3);
}
.feature-text strong {
    display: block;
    color: white;
    font-family: 'Outfit', sans-serif;
    font-weight: 700;
    font-size: .95rem;
    margin-bottom: 3px;
}
.feature-text span {
    font-size: .8rem;
    color: rgba(255,255,255,.45);
    line-height: 1.4;
}

/* ── Stats upgrade ── */
.stats {
    background: white !important;
    border-radius: 28px !important;
    border: none !important;
    box-shadow: 0 30px 70px rgba(0,0,0,.1) !important;
    padding: 50px 60px !important;
    margin: -70px auto 0 !important;
    position: relative; z-index: 100;
}
.stat-item {
    position: relative;
}
.stat-item + .stat-item::before {
    content: '';
    position: absolute;
    left: 0; top: 15%; bottom: 15%;
    width: 1px;
    background: #e2e8f0;
}
.stat-item h3 {
    font-family: 'Outfit', sans-serif !important;
    font-size: 3.2rem !important;
    font-weight: 900 !important;
    background: linear-gradient(135deg, #16a34a, #059669);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 8px !important;
    line-height: 1 !important;
}
.stat-item p {
    color: #64748b !important;
    font-size: .8rem !important;
    font-weight: 700 !important;
    letter-spacing: 1.5px !important;
    text-transform: uppercase !important;
}

/* ── Section shared header upgrade ── */
.labs-section-header,
.prod-section-header {
    margin-bottom: 60px !important;
}

/* ── Coverage section upgrade ── */
.coverage-map {
    position: relative;
    padding: 120px 5% !important;
    background: linear-gradient(145deg, #0d2818 0%, #0f3020 50%, #092014 100%) !important;
    overflow: hidden;
}
.coverage-map::before {
    background-image: radial-gradient(rgba(34,197,94,.08) 1px, transparent 1px) !important;
    background-size: 28px 28px !important;
    opacity: 1 !important;
}
.coverage-map .cov-glow {
    position: absolute;
    border-radius: 50%; filter: blur(90px); pointer-events: none;
}
.coverage-map .cov-glow-1 {
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(34,197,94,.2), transparent);
    top: -200px; left: -100px;
    animation: glowPulse 7s ease-in-out infinite alternate;
}
.coverage-map .cov-glow-2 {
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(5,150,105,.15), transparent);
    bottom: -150px; right: -50px;
    animation: glowPulse 5s ease-in-out 2s infinite alternate;
}
@keyframes glowPulse { from { opacity:.4; transform:scale(.9); } to { opacity:.8; transform:scale(1.1); } }

.cov-title {
    font-family: 'Outfit', sans-serif !important;
    font-size: clamp(2.2rem, 5vw, 3.8rem) !important;
    font-weight: 900 !important;
    line-height: 1.1 !important;
    color: white !important;
    margin-bottom: 20px !important;
}
.cov-title .hl {
    background: linear-gradient(135deg, #4ade80, #22c55e);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.cov-sub {
    font-size: 1.1rem !important;
    line-height: 1.75 !important;
    color: rgba(255,255,255,.65) !important;
    margin-bottom: 40px !important;
}
.cov-chips {
    display: flex; gap: 12px; flex-wrap: wrap;
    margin-bottom: 40px;
}
.cov-chip {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(34,197,94,.12);
    border: 1px solid rgba(34,197,94,.3);
    color: #4ade80;
    padding: 8px 18px;
    border-radius: 50px;
    font-size: .82rem; font-weight: 700;
}
.cov-btn {
    display: inline-flex; align-items: center; gap: 12px;
    background: linear-gradient(135deg, #22c55e, #15803d) !important;
    color: white !important;
    padding: 18px 40px !important;
    border-radius: 50px !important;
    font-weight: 800 !important;
    font-family: 'Outfit', sans-serif !important;
    text-decoration: none !important;
    box-shadow: 0 15px 40px rgba(34,197,94,.4) !important;
    transition: all .35s ease !important;
    font-size: 1rem !important;
    border: none !important;
}
.cov-btn:hover {
    transform: translateY(-4px) !important;
    box-shadow: 0 25px 60px rgba(34,197,94,.55) !important;
}
.cov-map-wrap {
    position: relative; z-index: 2;
    width: 520px; height: 520px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.cov-map-ring {
    position: absolute; border-radius: 50%;
    border: 1px solid rgba(34,197,94,.12);
    animation: ringExpand 4.5s ease-out infinite;
}
.cov-map-ring:nth-child(2) { animation-delay: 1.5s; }
.cov-map-ring:nth-child(3) { animation-delay: 3s; }
@keyframes ringExpand {
    0%   { width: 200px; height: 200px; opacity: .6; }
    100% { width: 700px; height: 700px; opacity: 0; }
}
.cov-map-wrap img {
    width: 100%; height: auto;
    filter: drop-shadow(0 0 40px rgba(34,197,94,.35)) brightness(1.15) contrast(1.1);
    position: relative; z-index: 3;
    animation: mapFloat 5s ease-in-out infinite;
}
@keyframes mapFloat {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-12px); }
}

/* ── Gallery upgrade ── */
.gallery {
    background: #f8fafc !important;
    padding: 120px 5% !important;
}
.gallery-header { text-align: center; margin-bottom: 60px; }
.gallery-label {
    display: inline-flex; align-items: center; gap: 8px;
    color: #16a34a; font-size: .75rem; font-weight: 800;
    letter-spacing: 3px; text-transform: uppercase;
    margin-bottom: 14px;
}
.gallery-label::before {
    content: ''; display: block;
    width: 24px; height: 2px;
    background: #22c55e; border-radius: 2px;
}
.gallery-title {
    font-family: 'Outfit', sans-serif !important;
    font-size: clamp(2rem, 4vw, 3rem) !important;
    font-weight: 900 !important;
    color: #0f172a !important;
    margin-bottom: 14px !important;
}
.gallery-title .accent { color: #16a34a; }
.gallery-sub {
    color: #64748b !important;
    font-size: 1rem !important;
    max-width: 600px !important;
    margin: 0 auto !important;
    line-height: 1.75 !important;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr) !important;
    gap: 20px !important;
    max-width: 1400px;
    margin: 0 auto;
}
.gallery-item {
    border-radius: 20px !important;
    height: 380px !important;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,.1) !important;
    transition: transform .4s ease, box-shadow .4s ease !important;
}
.gallery-item:hover {
    transform: scale(1.02) !important;
    box-shadow: 0 25px 60px rgba(0,0,0,.18) !important;
}
.gallery-overlay {
    background: linear-gradient(to top, rgba(15,23,42,.85), rgba(15,23,42,.2)) !important;
    opacity: 0 !important;
    transition: opacity .4s ease !important;
}
.gallery-item:hover .gallery-overlay { opacity: 1 !important; }
.gallery-overlay span {
    font-family: 'Outfit', sans-serif;
    font-size: 1.1rem !important;
    font-weight: 800 !important;
    position: absolute; bottom: 24px; left: 24px;
    background: none !important;
    border-left: 3px solid #22c55e;
    padding-left: 12px;
}

/* ── Responsive overrides ── */
@media (max-width: 1100px) {
    .feature-strip-inner { flex-wrap: wrap; }
    .feature-item { flex: 1 1 50%; border-bottom: 1px solid rgba(255,255,255,.06); }
    .gallery-grid { grid-template-columns: 1fr 1fr !important; }
}
@media (max-width: 768px) {
    .feature-strip-inner { flex-direction: column; }
    .feature-item { flex: 1; border-right: none; border-bottom: 1px solid rgba(255,255,255,.06); }
    .cov-map-wrap { display: none; }
    .gallery-grid { grid-template-columns: 1fr !important; }
    .stats { padding: 35px 24px !important; }
    .stat-item + .stat-item::before { display: none; }
}
</style>
@endpush

@section('content')

{{-- ══════════════════════════════════════════
     HERO CAROUSEL
     ══════════════════════════════════════════ --}}
<section class="hero-carousel">
    <div class="carousel-container">

        {{-- Slide 1 --}}
        <div class="carousel-slide active">
            <div class="slide-bg" style="background-image: linear-gradient(160deg, rgba(0,0,0,.72) 0%, rgba(0,0,0,.45) 60%, rgba(5,46,22,.6) 100%), url('{{ ($banner && $banner->image_path) ? asset("storage/".$banner->image_path) : asset("img/hero.png") }}');"></div>
            <div class="hero-content">
                <h1 class="animate-title">
                    Líderes en Distribución<br>
                    <span class="hl">Farmacéutica</span>
                </h1>
                <p class="animate-text">Abastecemos al Perú con los más altos estándares de calidad. Calidad, confianza y compromiso en cada entrega.</p>
                <div class="hero-btns animate-btns">
                    <a href="{{ route('products') }}" class="btn btn-primary"><i class="fas fa-pills"></i> Nuestros Productos</a>
                    <a href="{{ route('about') }}" class="btn btn-outline"><i class="fas fa-map-marked-alt"></i> Red de Distribución</a>
                </div>
            </div>
        </div>

        {{-- Slide 2 --}}
        <div class="carousel-slide">
            <div class="slide-bg" style="background-image: linear-gradient(160deg, rgba(0,0,0,.72) 0%, rgba(0,0,0,.45) 60%, rgba(5,46,22,.6) 100%), url('{{ ($banner && $banner->hero_image_2) ? asset("storage/".$banner->hero_image_2) : asset("img/hero2.png") }}');"></div>
            <div class="hero-content">
                <h1 class="animate-title">
                    Logística <span class="hl">Especializada</span><br>
                    y Confiable
                </h1>
                <p class="animate-text">Garantizamos la cadena de frío y trazabilidad completa en cada entrega a nivel nacional.</p>
                <div class="hero-btns animate-btns">
                    <a href="{{ route('about') }}" class="btn btn-primary"><i class="fas fa-truck"></i> Ver Cobertura</a>
                </div>
            </div>
        </div>

        {{-- Slide 3 --}}
        <div class="carousel-slide">
            <div class="slide-bg" style="background-image: linear-gradient(160deg, rgba(0,0,0,.72) 0%, rgba(0,0,0,.45) 60%, rgba(5,46,22,.6) 100%), url('{{ ($banner && $banner->hero_image_3) ? asset("storage/".$banner->hero_image_3) : asset("img/hero3.png") }}');"></div>
            <div class="hero-content">
                <h1 class="animate-title">
                    Alianzas que<br>
                    <span class="hl">Cuidan tu Salud</span>
                </h1>
                <p class="animate-text">Trabajamos con los laboratorios más prestigiosos del Perú para garantizar medicamentos de calidad.</p>
                <div class="hero-btns animate-btns">
                    <a href="{{ route('contact') }}" class="btn btn-primary"><i class="fas fa-envelope"></i> Contáctanos</a>
                </div>
            </div>
        </div>

    </div>

    {{-- Controls --}}
    <div class="carousel-controls">
        <button class="prev-slide"><i class="fas fa-chevron-left"></i></button>
        <button class="next-slide"><i class="fas fa-chevron-right"></i></button>
    </div>

    {{-- Indicators --}}
    <div class="carousel-indicators">
        <span class="indicator active" data-index="0"></span>
        <span class="indicator" data-index="1"></span>
        <span class="indicator" data-index="2"></span>
    </div>

    {{-- Scroll hint --}}
    <div class="hero-scroll-hint">
        <div class="hero-scroll-mouse"></div>
        Descubre más
    </div>
</section>



{{-- ══════════════════════════════════════════
     CERTIFICACIONES
     ══════════════════════════════════════════ --}}
@if($certificados->count() > 0)
<section class="certificaciones-section reveal" style="padding: 60px 5% 40px; background: white; text-align: center;">
    <div class="labs-section-header" style="margin-bottom: 40px;">
        <div class="section-badge" style="display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, rgba(46,125,50,0.1), rgba(0,137,123,0.08)); color: #2e7d32; padding: 8px 20px; border-radius: 50px; font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px; box-shadow: 0 4px 12px rgba(46,125,50,0.08);">
            <i class="fas fa-award"></i> Calidad Garantizada
        </div>
        <h2>Nuestras <span>Certificaciones</span></h2>
    </div>

    <div class="cert-grid" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 100px; max-width: 1200px; margin: 0 auto;">
        @foreach($certificados as $cert)
            <div class="cert-item" style="display: flex; flex-direction: column; align-items: center; gap: 20px; width: 190px;">
                <div class="cert-img-wrap" style="width: 150px; height: 150px; display: flex; align-items: center; justify-content: center; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); filter: drop-shadow(0 12px 15px rgba(0,0,0,0.15));">
                    @if($cert->imagen)
                        <img src="{{ asset('storage/' . $cert->imagen) }}" alt="{{ $cert->nombre }}" style="width: 100%; height: 100%; object-fit: contain;">
                    @else
                        <i class="fas fa-certificate" style="font-size: 4rem; color: #cbd5e1;"></i>
                    @endif
                </div>
                <h4 style="font-family: 'Outfit', sans-serif; text-align: center; margin: 0; line-height: 1.3;">
                    @php
                        $name = $cert->nombre;
                        $parts = preg_split('/(?<= de | DE )/i', $name, 2);
                    @endphp
                    @if(count($parts) > 1)
                        <span style="font-size: 0.95rem; font-weight: 600; color: #64748b; display: block; margin-bottom: 4px;">{{ $parts[0] }}</span>
                        <span style="font-size: 1.2rem; font-weight: 800; color: #334155; display: block;">{{ $parts[1] }}</span>
                    @else
                        <span style="font-size: 1.15rem; font-weight: 800; color: #334155; display: block;">{{ $name }}</span>
                    @endif
                </h4>
            </div>
        @endforeach
    </div>
    <style>
        .cert-item:hover .cert-img-wrap { 
            transform: translateY(-12px) scale(1.05); 
            filter: drop-shadow(0 25px 25px rgba(0,0,0,0.25)); 
        }
        .cert-item {
            cursor: default;
        }
    </style>
</section>
@endif

{{-- ══════════════════════════════════════════
     LABORATORIOS TOP
     ══════════════════════════════════════════ --}}
<section class="top-laboratories" style="padding: 120px 5% 80px; background: linear-gradient(160deg, #f0fdf4 0%, #f8fafc 40%, #f0fdfa 100%); text-align: center;">
    <div class="labs-section-header" style="margin-bottom: 50px;">
        <div class="section-badge">
            <i class="fas fa-flask"></i> Nuestras Alianzas
        </div>
        <h2>Laboratorios <span>Destacados</span></h2>
        <p>Colaboramos con laboratorios de clase mundial para asegurar el acceso a medicinas de alta calidad en todo el Perú.</p>
    </div>

    <div class="labs-grid">
        @forelse($topLaboratories as $lab)
            @php
                $labNameLower = strtolower($lab->descripcion);
                $customClass = '';
                $dynamicStyle = '';

                if (str_contains($labNameLower, 'genfar')) {
                    $customClass = 'lab-genfar';
                } elseif (str_contains($labNameLower, 'bayer')) {
                    $customClass = 'lab-bayer';
                } elseif (str_contains($labNameLower, 'portugal')) {
                    $customClass = 'lab-portugal';
                } elseif (str_contains($labNameLower, 'intipharma')) {
                    $customClass = 'lab-intipharma';
                } else {
                    $customClass = 'lab-dinamico lab-dynamic-' . $lab->id;
                    
                    // Extraer colores dinámicamente del logo
                    $colors = ['#009EE3', '#65B32E']; // Fallback
                    if ($lab->logo) {
                        $colors = \App\Helpers\ColorExtractor::extractColors($lab->logo);
                    }
                    $color1 = $colors[0];
                    $color2 = $colors[1];
                    
                    // Asegurar codificación %23 para el SVG
                    $c1 = str_replace('#', '%23', $color1);
                    $c2 = str_replace('#', '%23', $color2);
                    
                    $dynamicStyle = "
                        .lab-card.lab-dynamic-{$lab->id},
                        .lab-card.lab-dynamic-{$lab->id}:hover {
                            background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 300' preserveAspectRatio='none'%3E%3Cdefs%3E%3Cpattern id='dots-dyn-{$lab->id}' x='0' y='0' width='10' height='10' patternUnits='userSpaceOnUse'%3E%3Ccircle cx='2' cy='2' r='1.5' fill='{$c1}' opacity='0.15'/%3E%3C/pattern%3E%3ClinearGradient id='grad-1-dyn-{$lab->id}' x1='0%25' y1='0%25' x2='100%25' y2='0%25'%3E%3Cstop offset='0%25' stop-color='{$c1}'/%3E%3Cstop offset='100%25' stop-color='{$c1}' stop-opacity='0'/%3E%3C/linearGradient%3E%3ClinearGradient id='grad-2-dyn-{$lab->id}' x1='100%25' y1='0%25' x2='0%25' y2='0%25'%3E%3Cstop offset='0%25' stop-color='{$c2}'/%3E%3Cstop offset='100%25' stop-color='{$c2}' stop-opacity='0'/%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='300' height='300' fill='url(%23dots-dyn-{$lab->id})'/%3E%3Cpath d='M0,220 C100,280 200,240 300,260 L300,300 L0,300 Z' fill='url(%23grad-1-dyn-{$lab->id})' opacity='0.8'/%3E%3Cpath d='M0,250 C80,220 150,290 300,230 L300,300 L0,300 Z' fill='{$c1}' opacity='0.5'/%3E%3Cpath d='M300,200 C200,270 100,230 0,270 L0,300 L300,300 Z' fill='url(%23grad-2-dyn-{$lab->id})' opacity='0.8'/%3E%3Cpath d='M300,230 C220,210 150,280 0,240 L0,300 L300,300 Z' fill='{$c2}' opacity='0.5'/%3E%3C/svg%3E\") !important;
                            background-repeat: no-repeat !important;
                            background-position: center bottom !important;
                            background-size: cover !important;
                            background-color: #ffffff !important;
                        }
                        .lab-dynamic-{$lab->id} .lab-action {
                            background: linear-gradient(135deg, {$color1}, {$color2}) !important;
                            box-shadow: 0 8px 20px {$color1}40 !important;
                        }
                    ";
                }
            @endphp
            @if($dynamicStyle)
                <style>{!! $dynamicStyle !!}</style>
            @endif
            <a href="{{ route('products', ['lab' => $lab->id]) }}" class="lab-card-link reveal-lab">
                <div class="lab-card {{ $customClass }}">
                    <div class="lab-logo-container">
                        <div class="wave-bg"></div>
                        @if($lab->logo)
                            <img src="{{ asset('storage/'.$lab->logo) }}" alt="{{ $lab->descripcion }}">
                        @else
                            <div class="lab-placeholder"><i class="fas fa-flask"></i></div>
                        @endif
                    </div>
                    <div class="lab-info">
                        <h4>{{ $lab->descripcion }}</h4>
                        <span class="lab-action">Ver Catálogo <i class="fas fa-arrow-right"></i></span>
                    </div>
                </div>
            </a>
        @empty
            <div class="labs-empty">
                <p>Descubre pronto nuestras marcas aliadas.</p>
            </div>
        @endforelse
    </div>
</section>

{{-- ══════════════════════════════════════════
     PRODUCTOS DESTACADOS
     ══════════════════════════════════════════ --}}
<section class="featured-products" style="padding: 100px 5%; background: white;">
    <div class="prod-section-header" style="text-align: center; margin-bottom: 50px;">
        <div class="prod-badge">
            <i class="fas fa-star"></i> Selección Especial
        </div>
        <h2>Productos <span>Destacados</span></h2>
        <div class="prod-divider"></div>
    </div>

    <div class="prod-grid">
        @forelse($featuredProducts as $product)
            <div class="prod-card">
                <a href="{{ route('product.detail', $product->id) }}" class="prod-image-wrap" style="text-decoration:none; display:flex;">
                    <span class="prod-badge"><i class="fas fa-star"></i> DESTACADO</span>
                    @if($product->imagen)
                        <img src="{{ asset('storage/'.$product->imagen) }}" alt="{{ $product->nombre }}">
                    @else
                        <div class="prod-image-placeholder">
                            <i class="fas fa-pills"></i>
                            <span>Sin Imagen</span>
                        </div>
                    @endif
                </a>
                <div class="prod-info">
                    <span class="prod-laboratory"><i class="fas fa-flask"></i> {{ $product->laboratory->descripcion ?? 'Sanchez Pharma' }}</span>
                    <a href="{{ route('product.detail', $product->id) }}" style="text-decoration:none;">
                        <h4 class="prod-name">{{ $product->nombre }}</h4>
                    </a>
                    <span class="prod-code">#{{ $product->codigo }}</span>
                    <div class="prod-footer">
                        <span class="prod-price"><span class="currency">S/</span> {{ number_format($product->precio, 2) }}</span>
                        <a href="{{ route('product.detail', $product->id) }}" class="prod-btn">
                            Detalles <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="prod-empty">
                <p>Pronto verás aquí nuestra selección de productos destacados.</p>
            </div>
        @endforelse
    </div>

    <div class="prod-cta">
        <a href="{{ route('products') }}" class="btn-catalog">
            Ver Todo el Catálogo <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</section>

{{-- ══════════════════════════════════════════
     COBERTURA — Premium Dark
     ══════════════════════════════════════════ --}}
<section class="coverage-map" style="display:flex; align-items:center; justify-content:center; gap:80px; flex-wrap:wrap; position:relative;">
    <div class="cov-glow cov-glow-1"></div>
    <div class="cov-glow cov-glow-2"></div>
    <div style="position:absolute; inset:0; background-image:radial-gradient(rgba(34,197,94,.07) 1px, transparent 1px); background-size:28px 28px; pointer-events:none; z-index:1;"></div>

    <div style="max-width:560px; position:relative; z-index:2;">
        <div style="display:inline-flex; align-items:center; gap:8px; color:#4ade80; font-size:.75rem; font-weight:800; letter-spacing:3px; text-transform:uppercase; margin-bottom:18px;">
            <span style="width:24px; height:2px; background:#22c55e; border-radius:2px; display:block;"></span>
            Cobertura Nacional
        </div>
        <h2 class="cov-title">
            Logística de <span class="hl">clase mundial</span><br>a su alcance
        </h2>
        <p class="cov-sub">
            Nuestro compromiso va más allá de la entrega. Garantizamos la trazabilidad y calidad de cada fármaco mediante una red de representantes altamente capacitados en todo el país.
        </p>
        <div class="cov-chips">
            <span class="cov-chip"><i class="fas fa-check-circle"></i> Cadena de frío</span>
            <span class="cov-chip"><i class="fas fa-check-circle"></i> Trazabilidad total</span>
            <span class="cov-chip"><i class="fas fa-check-circle"></i> BPA certificado</span>
        </div>
        <a href="{{ route('contact') }}" class="cov-btn">
            <i class="fas fa-shield-halved"></i> Asegurar Calidad
        </a>
    </div>

    <div class="cov-map-wrap" style="position:relative; z-index:2; flex-shrink:0;">
        <div class="cov-map-ring"></div>
        <div class="cov-map-ring"></div>
        <div class="cov-map-ring"></div>
        <img src="{{ asset('img/mapa_peru.png') }}" alt="Mapa de cobertura Sánchez Pharma — Perú">
    </div>
</section>

{{-- ══════════════════════════════════════════
     GALERÍA
     ══════════════════════════════════════════ --}}
<section class="gallery reveal" style="padding:120px 5%; background:#f8fafc;">
    <div class="gallery-header">
        <div class="gallery-label"><span>Nuestras Instalaciones</span></div>
        <h2 class="gallery-title">Infraestructura de <span class="accent">Excelencia</span></h2>
        <p class="gallery-sub">Operamos bajo estrictos protocolos de Buenas Prácticas de Almacenamiento (BPA) para garantizar la calidad de cada producto.</p>
    </div>

    <div class="gallery-grid">
        <div class="gallery-item" style="background-image:url('{{ ($banner && $banner->gallery_image_1) ? asset("storage/".$banner->gallery_image_1) : asset("img/logistica.png") }}'); background-size:cover; background-position:center;">
            <div class="gallery-overlay"><span>Logística Avanzada</span></div>
        </div>
        <div class="gallery-item" style="background-image:url('{{ ($banner && $banner->gallery_image_2) ? asset("storage/".$banner->gallery_image_2) : asset("img/calidad.png") }}'); background-size:cover; background-position:center;">
            <div class="gallery-overlay"><span>Calidad Garantizada</span></div>
        </div>
        <div class="gallery-item" style="background-image:url('{{ ($banner && $banner->gallery_image_3) ? asset("storage/".$banner->gallery_image_3) : asset("img/transporte.png") }}'); background-size:cover; background-position:center;">
            <div class="gallery-overlay"><span>Cobertura Nacional</span></div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
/* ─── Carousel ─── */
const slides     = document.querySelectorAll('.carousel-slide');
const indicators = document.querySelectorAll('.indicator');
const nextBtn    = document.querySelector('.next-slide');
const prevBtn    = document.querySelector('.prev-slide');
let current = 0, timer;

function showSlide(idx) {
    slides.forEach(s => s.classList.remove('active'));
    indicators.forEach(i => i.classList.remove('active'));
    current = (idx + slides.length) % slides.length;
    slides[current].classList.add('active');
    indicators[current].classList.add('active');
}

nextBtn.addEventListener('click', () => { showSlide(current + 1); resetTimer(); });
prevBtn.addEventListener('click', () => { showSlide(current - 1); resetTimer(); });
indicators.forEach((ind, i) => ind.addEventListener('click', () => { showSlide(i); resetTimer(); }));

function resetTimer() { clearInterval(timer); timer = setInterval(() => showSlide(current + 1), 6000); }
resetTimer();

/* ─── Stats Counter ─── */
const statsObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        entry.target.querySelectorAll('.stat-item h3').forEach(el => {
            const target = parseInt(el.dataset.target);
            const suffix = el.dataset.suffix || '';
            if (!target) return;
            const dur = 2000, start = performance.now();
            function step(ts) {
                const p = Math.min((ts - start) / dur, 1);
                const ease = 1 - Math.pow(1 - p, 4);
                el.textContent = Math.round(target * ease) + suffix;
                if (p < 1) requestAnimationFrame(step);
            }
            requestAnimationFrame(step);
        });
        statsObserver.unobserve(entry.target);
    });
}, { threshold: 0.5 });

const statsEl = document.querySelector('.stats');
if (statsEl) statsObserver.observe(statsEl);

/* ─── Scroll Reveal ─── */
const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('active'); });
}, { threshold: 0.1 });
document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

/* ─── Labs stagger ─── */
const labsObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        entry.target.querySelectorAll('.lab-card-link').forEach((card, i) => {
            card.style.opacity = '0'; card.style.transform = 'translateY(40px)';
            setTimeout(() => {
                card.style.transition = 'all 0.7s cubic-bezier(0.175,0.885,0.32,1.275)';
                card.style.opacity = '1'; card.style.transform = 'translateY(0)';
            }, i * 120);
        });
        labsObserver.unobserve(entry.target);
    });
}, { threshold: 0.15 });

const labsGrid = document.querySelector('.labs-grid');
if (labsGrid) {
    labsGrid.querySelectorAll('.lab-card-link').forEach(c => { c.style.opacity='0'; c.style.transform='translateY(40px)'; });
    labsObserver.observe(labsGrid);
}

/* ─── Products stagger ─── */
const prodObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        entry.target.querySelectorAll('.prod-card').forEach((card, i) => {
            card.style.opacity = '0'; card.style.transform = 'translateY(40px)';
            setTimeout(() => {
                card.style.transition = 'all 0.7s cubic-bezier(0.175,0.885,0.32,1.275)';
                card.style.opacity = '1'; card.style.transform = 'translateY(0)';
            }, i * 120);
        });
        prodObserver.unobserve(entry.target);
    });
}, { threshold: 0.1 });

const prodGrid = document.querySelector('.prod-grid');
if (prodGrid) {
    prodGrid.querySelectorAll('.prod-card').forEach(c => { c.style.opacity='0'; c.style.transform='translateY(40px)'; });
    prodObserver.observe(prodGrid);
}

/* ─── Feature strip hover glow ─── */
document.querySelectorAll('.feature-item').forEach(item => {
    item.addEventListener('mouseenter', () => item.style.background = 'rgba(34,197,94,.07)');
    item.addEventListener('mouseleave', () => item.style.background = '');
});
</script>
@endpush
