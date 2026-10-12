@extends('layouts.landing')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
/* ═══════════════════════════════════════════════════════
   LABORATORIOS / PRODUCTOS — Premium Redesign
   ═══════════════════════════════════════════════════════ */
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800;900&display=swap');

.pd-hero {
    position: relative;
    min-height: 60vh;
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
    background: #0f172a;
    margin-bottom: 40px;
}
.pd-hero-bg {
    position: absolute; inset: 0;
    background:
        url('{{ ($banner && $banner->image_path) ? asset("storage/".$banner->image_path) : "https://images.unsplash.com/photo-1585435557343-3b092031a831?auto=format&fit=crop&w=1920&q=80" }}')
        center/cover no-repeat;
    filter: brightness(.28) saturate(1.3);
    transform: scale(1.08);
    transition: transform 12s ease-out;
}
.pd-hero-bg.loaded { transform: scale(1); }
.pd-hero-grid {
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(34,197,94,.08) 1px, transparent 1px),
        linear-gradient(90deg, rgba(34,197,94,.08) 1px, transparent 1px);
    background-size: 60px 60px;
    animation: pdGridFloat 20s linear infinite;
}
@keyframes pdGridFloat { to { background-position: 60px 60px; } }
.pd-hero-glow {
    position: absolute; border-radius: 50%;
    filter: blur(80px); opacity: .45;
    animation: pdGlowPulse 6s ease-in-out infinite alternate;
}
.pd-hero-glow.g1 { width: 500px; height: 500px; background: radial-gradient(circle, #22c55e, transparent); top: -150px; right: -100px; }
.pd-hero-glow.g2 { width: 400px; height: 400px; background: radial-gradient(circle, #059669, transparent); bottom: -100px; left: -80px; animation-delay: -3s; }
@keyframes pdGlowPulse { from { opacity:.3; transform:scale(.9); } to { opacity:.6; transform:scale(1.1); } }
#pdParticleCanvas { position: absolute; inset: 0; pointer-events: none; }

.pd-hero-inner {
    position: relative; z-index: 10;
    text-align: center; padding: 0 20px; max-width: 900px;
}
.pd-hero-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(34,197,94,.15); border: 1px solid rgba(34,197,94,.4);
    color: #4ade80; padding: 8px 20px; border-radius: 50px;
    font-size: .8rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
    margin-bottom: 28px; backdrop-filter: blur(10px);
    animation: pdBadgePop .6s .3s both cubic-bezier(.175,.885,.32,1.275);
}
@keyframes pdBadgePop { from { opacity:0; transform:scale(.8) translateY(10px); } to { opacity:1; transform:none; } }

.pd-hero-title {
    font-family: 'Outfit', sans-serif;
    font-size: clamp(3rem, 8vw, 6.5rem);
    font-weight: 900; color: white; line-height: 1; margin-bottom: 12px;
    animation: pdHeroTitle .9s .5s both;
}
@keyframes pdHeroTitle { from { opacity:0; transform:translateY(40px); } to { opacity:1; transform:none; } }
.pd-hero-title .hl {
    background: linear-gradient(135deg, #4ade80 0%, #22c55e 50%, #a3e635 100%);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text; display: block;
}
.pd-hero-sub {
    font-size: clamp(1rem, 2vw, 1.25rem); color: rgba(255,255,255,.7);
    max-width: 600px; margin: 24px auto 0; line-height: 1.7;
    animation: pdHeroTitle 1s .7s both;
}
.pd-hero-scroll {
    position: absolute; bottom: 40px; left: 0; width: 100%;
    display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px;
    color: rgba(255,255,255,.5); font-size: .75rem; letter-spacing: 2px; text-transform: uppercase;
    animation: pdHeroTitle 1s 1s both;
}
.pd-scroll-line {
    width: 1px; height: 50px;
    background: linear-gradient(to bottom, rgba(34,197,94,.8), transparent);
    animation: pdScrollLine 2s ease-in-out infinite;
}
@keyframes pdScrollLine { 0%,100%{ opacity:.3; transform:scaleY(.3) translateY(-10px); } 50%{ opacity:1; transform:scaleY(1) translateY(0); } }


    .swal2-styled.swal2-confirm { background-color: var(--primary-green) !important; }

    /* ═══════════════════════════════════════════════════════
       CATÁLOGO Y FILTROS STYLES
       ═══════════════════════════════════════════════════════ */
    .products-container {
        display: flex;
        gap: 40px;
        padding: 60px 5%;
        max-width: 1400px;
        margin: 0 auto;
        align-items: flex-start;
    }

    /* Filters Sidebar */
    .filters-sidebar {
        flex: 0 0 290px;
        width: 290px;
        background: #ffffff;
        padding: 24px 20px;
        border-radius: 18px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
        position: sticky;
        top: 90px;
        box-sizing: border-box;
    }

    .filters-sidebar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #f1f5f9;
    }

    .filters-sidebar-header h3 {
        margin: 0;
        color: var(--dark-green);
        font-size: 1.15rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .active-filter-badge {
        background: #f0fdf4;
        color: var(--primary-green);
        border: 1px solid #bbf7d0;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 20px;
    }

    .filter-section {
        margin-bottom: 22px;
    }

    .filter-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .filter-section-label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.88rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 8px;
    }

    .btn-toggle-all-labs {
        background: none;
        border: none;
        color: var(--primary-green);
        font-size: 0.78rem;
        font-weight: 700;
        cursor: pointer;
        padding: 0;
        text-decoration: underline;
        transition: color 0.2s;
    }
    .btn-toggle-all-labs:hover { color: var(--dark-green); }

    .filter-search-input-wrap {
        position: relative;
        display: flex;
        align-items: center;
    }

    .filter-search-field {
        width: 100%;
        padding: 10px 36px 10px 14px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.88rem;
        outline: none;
        transition: 0.2s;
        background: #f8fafc;
        box-sizing: border-box;
    }

    .filter-search-field:focus {
        background: #ffffff;
        border-color: var(--primary-green);
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
    }

    .btn-clear-search-field {
        position: absolute;
        right: 10px;
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        font-size: 0.85rem;
        padding: 4px;
    }
    .btn-clear-search-field:hover { color: #ef4444; }

    .filter-labs-list {
        display: flex;
        flex-direction: column;
        gap: 6px;
        max-height: 320px;
        overflow-y: auto;
        padding-right: 4px;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }
    .filter-labs-list::-webkit-scrollbar { width: 5px; }
    .filter-labs-list::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

    .filter-lab-checkbox-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 12px;
        border-radius: 10px;
        border: 1.5px solid #f1f5f9;
        background: #ffffff;
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
    }
    .filter-lab-checkbox-item:hover { border-color: #cbd5e1; background: #f8fafc; }
    .filter-lab-checkbox-item.is-selected { background: #f0fdf4; border-color: var(--primary-green); }

    .lab-check-input { display: none; }

    .custom-check-box {
        width: 18px;
        height: 18px;
        border-radius: 5px;
        border: 2px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }
    .custom-check-box i { font-size: 0.65rem; color: white; display: none; }
    .filter-lab-checkbox-item.is-selected .custom-check-box { background: var(--primary-green); border-color: var(--primary-green); }
    .filter-lab-checkbox-item.is-selected .custom-check-box i { display: block; }

    .lab-label-text {
        font-size: 0.85rem;
        font-weight: 500;
        color: #334155;
        flex: 1;
        line-height: 1.3;
    }
    .filter-lab-checkbox-item.is-selected .lab-label-text { color: var(--dark-green); font-weight: 700; }

    .filter-actions-wrap {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-top: 18px;
        padding-top: 16px;
        border-top: 1.5px solid #f1f5f9;
    }

    .btn-apply-filters {
        width: 100%;
        background: var(--primary-green);
        color: white;
        border: none;
        padding: 12px 18px;
        border-radius: 10px;
        font-weight: 800;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 6px 18px rgba(16, 185, 129, 0.28);
    }
    .btn-apply-filters:hover {
        background: var(--dark-green);
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(16, 185, 129, 0.38);
    }

    .btn-clear-all-filters {
        width: 100%;
        background: #fff;
        color: #ef4444;
        border: 1.5px dashed #fca5a5;
        padding: 10px 16px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none;
        box-sizing: border-box;
    }
    .btn-clear-all-filters:hover {
        background: #fef2f2;
        border-color: #ef4444;
        color: #dc2626;
    }

    .filter-toggle-btn {
        display: none;
        align-items: center;
        gap: 8px;
        background: var(--primary-green);
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: 0.3s;
        width: 100%;
        justify-content: center;
        font-family: inherit;
        font-size: 0.95rem;
        box-shadow: 0 10px 20px rgba(46, 125, 50, 0.2);
    }
    .filter-toggle-btn:hover { transform: translateY(-2px); box-shadow: 0 15px 25px rgba(46, 125, 50, 0.3); }
    .filter-toggle-btn.active i.fa-chevron-down { transform: rotate(180deg); }

    /* Product Grid & Cards */
    .product-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; }
    .product-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); border-color: var(--primary-green); }
    .product-card:hover .product-img-wrapper img { transform: scale(1.1); }

    .qty-selector { display: flex; align-items: center; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; }
    .qty-selector button { border: none; background: transparent; padding: 12px; cursor: pointer; color: #64748b; transition: 0.3s; }
    .qty-selector button:hover { background: #e2e8f0; color: var(--primary-green); }
    .qty-selector input { width: 40px; border: none; background: transparent; text-align: center; font-weight: 800; color: #1e293b; font-size: 1rem; }

    .btn-add-cart { 
        background: var(--primary-green); color: white; border: none; padding: 15px 25px; 
        border-radius: 12px; font-weight: 800; flex: 1; display: flex; align-items: center; 
        justify-content: center; gap: 10px; cursor: pointer; transition: 0.3s; 
        box-shadow: 0 10px 20px rgba(46, 125, 50, 0.2); 
    }
    .btn-add-cart:hover { transform: scale(1.05); box-shadow: 0 15px 30px rgba(46, 125, 50, 0.3); }

    .reveal { opacity: 0; transform: translateY(30px); transition: 0.8s ease-out; }
    .reveal.visible { opacity: 1; transform: translateY(0); }

    /* Custom Pagination */
    .pagination { display: flex; padding-left: 0; list-style: none; justify-content: center; gap: 8px; margin-top: 20px; flex-wrap: wrap; }
    .page-item .page-link { 
        position: relative; display: block; padding: 10px 18px; color: #475569; background-color: #fff; 
        border: 1px solid #e2e8f0; border-radius: 12px; font-weight: 600; text-decoration: none; transition: 0.3s;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }
    .page-item.active .page-link { 
        z-index: 3; color: #fff; background-color: var(--primary-green); border-color: var(--primary-green); 
        box-shadow: 0 10px 15px -3px rgba(46, 125, 50, 0.3);
    }
    .page-item.disabled .page-link { color: #94a3b8; pointer-events: none; background-color: #f8fafc; border-color: #e2e8f0; }
    .page-item .page-link:hover { background-color: #f1f5f9; color: var(--primary-green); transform: translateY(-2px); }

    /* Responsive */
    @media (max-width: 1024px) {
        .product-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .product-grid { grid-template-columns: 1fr; }
        .products-container { flex-direction: column !important; padding: 30px 4% !important; }
        .filters-sidebar { width: 100% !important; flex: auto !important; position: static !important; display: none; }
        .filters-sidebar.open { display: block; }
        .filter-toggle-btn { display: flex !important; }
    }
    @media (max-width: 480px) {
        #floating-cart { width: 55px !important; height: 55px !important; bottom: 20px !important; right: 20px !important; }
        #floating-cart i { font-size: 1.2rem !important; }
        .product-img-wrapper { height: 160px !important; padding: 15px !important; }
        .product-card h3 { font-size: 1rem !important; min-height: 2.5rem !important; margin-bottom: 10px !important; }
        .product-card .btn-add-cart { padding: 10px 15px !important; font-size: 0.9rem !important; }
        .qty-selector input { font-size: 0.9rem !important; width: 30px !important; }
        .qty-selector button { padding: 8px !important; }
    }
</style>
@endpush

@section('content')
<section class="pd-hero">
    <div class="pd-hero-bg" id="pdHeroBg"></div>
    <div class="pd-hero-grid"></div>
    <div class="pd-hero-glow g1"></div>
    <div class="pd-hero-glow g2"></div>
    <canvas id="pdParticleCanvas"></canvas>

    <div class="pd-hero-inner">
        <div class="pd-hero-badge">
            <i class="fas fa-flask"></i>
            Laboratorios Aliados
        </div>
        <h1 class="pd-hero-title">
            Catálogo de
            <span class="hl">Productos</span>
        </h1>
        <p class="pd-hero-sub">
            Explora nuestro catálogo completo de medicamentos y productos farmacéuticos de la más alta calidad y laboratorios certificados.
        </p>
    </div>

</section>

    <section class="products-container" style="display: flex; gap: 40px; padding: 60px 5%; max-width: 1400px; margin: 0 auto; align-items: flex-start;">
        
        <!-- Mobile Filter Toggle -->
        <button id="filterToggle" class="filter-toggle-btn" style="display: none;">
            <i class="fas fa-filter"></i> Filtros <i class="fas fa-chevron-down"></i>
        </button>

        @php
            $selectedLabs = [];
            if (request()->has('labs') && is_array(request('labs'))) {
                $selectedLabs = array_map('intval', request('labs'));
            } elseif (request()->filled('lab')) {
                $selectedLabs = [(int) request('lab')];
            }
            $hasActiveFilters = !empty($selectedLabs) || request()->filled('search');
        @endphp

        <!-- Sidebar Filtros -->
        <aside class="filters-sidebar">
            <div class="filters-sidebar-header">
                <h3><i class="fas fa-sliders"></i> Filtros</h3>
                @if($hasActiveFilters)
                    <span class="active-filter-badge">{{ count($selectedLabs) + (request()->filled('search') ? 1 : 0) }} activo(s)</span>
                @endif
            </div>
            
            <form action="{{ route('products') }}" method="GET" id="catalogFiltersForm">
                <!-- Buscar en productos -->
                <div class="filter-section">
                    <label class="filter-section-label">
                        <i class="fas fa-search"></i> Buscar:
                    </label>
                    <div class="filter-search-input-wrap">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Producto o código..." class="filter-search-field">
                        @if(request('search'))
                            <button type="button" class="btn-clear-search-field" onclick="document.querySelector('.filter-search-field').value=''; document.getElementById('catalogFiltersForm').submit();" title="Borrar búsqueda">
                                <i class="fas fa-times"></i>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Selección múltiple de laboratorios -->
                <div class="filter-section">
                    <div class="filter-section-header">
                        <label class="filter-section-label">
                            <i class="fas fa-flask"></i> Laboratorios:
                        </label>
                        <button type="button" class="btn-toggle-all-labs" id="toggleAllLabsBtn" onclick="toggleAllLabs()">
                            {{ count($selectedLabs) > 0 ? 'Deseleccionar' : 'Todos' }}
                        </button>
                    </div>

                    <div class="filter-labs-list">
                        @foreach($laboratories as $lab)
                        <label class="filter-lab-checkbox-item {{ in_array($lab->id, $selectedLabs) ? 'is-selected' : '' }}" for="lab_{{ $lab->id }}">
                            <input type="checkbox" name="labs[]" value="{{ $lab->id }}" id="lab_{{ $lab->id }}" class="lab-check-input" {{ in_array($lab->id, $selectedLabs) ? 'checked' : '' }} onchange="this.closest('.filter-lab-checkbox-item').classList.toggle('is-selected', this.checked); updateToggleAllBtn();">
                            <span class="custom-check-box">
                                <i class="fas fa-check"></i>
                            </span>
                            <span class="lab-label-text">{{ $lab->descripcion }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="filter-actions-wrap">
                    <button type="submit" class="btn-apply-filters">
                        <i class="fas fa-check-circle"></i> APLICAR FILTROS
                    </button>

                    @if($hasActiveFilters)
                        <a href="{{ route('products') }}" class="btn-clear-all-filters">
                            <i class="fas fa-rotate-left"></i> Limpiar Filtros
                        </a>
                    @else
                        <button type="button" class="btn-clear-all-filters" onclick="clearSelectedLabs()">
                            <i class="fas fa-eraser"></i> Limpiar Selección
                        </button>
                    @endif
                </div>
            </form>
        </aside>

        <!-- Product Grid -->
        <div class="products-main" style="flex: 1;">
            <div class="product-grid">
                @forelse($products as $product)
                <div class="product-card reveal" style="background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.03); display: flex; flex-direction: column; transition: 0.4s; border: 1px solid #f1f5f9; position: relative; min-width: 0;">
                    <!-- Imagen con Efecto -->
                    <a href="{{ route('product.detail', $product->id) }}" class="product-img-wrapper" style="text-decoration: none; display: block; height: 200px; background: #fff; display: flex; align-items: center; justify-content: center; overflow: hidden; padding: 20px;">
                        @if($product->imagen)
                            <img src="{{ asset('storage/' . $product->imagen) }}" alt="{{ $product->nombre }}" loading="lazy" style="max-height: 100%; max-width: 100%; object-fit: contain; transition: 0.5s;">
                        @else
                            <div style="display: flex; flex-direction: column; align-items: center; color: #e2e8f0;">
                                <i class="fas fa-pills" style="font-size: 4rem; margin-bottom: 10px;"></i>
                                <span style="font-weight: 700; font-size: 0.8rem; text-transform: uppercase;">Sin Imagen</span>
                            </div>
                        @endif
                    </a>

                    <!-- Info Premium -->
                    <div style="padding: 10px 20px 25px; flex-grow: 1; display: flex; flex-direction: column; background: white;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                            <p style="font-size: 0.7rem; color: #94a3b8; font-weight: 700; font-family: monospace; letter-spacing: 1px;">#{{ $product->codigo }}</p>
                            @if($product->stock > 0)
                                <span style="color: #10b981; font-size: 0.7rem; font-weight: 800;"><i class="fas fa-check-circle"></i> EN STOCK</span>
                            @endif
                        </div>
                        
                        <a href="{{ route('product.detail', $product->id) }}" style="text-decoration: none;">
                            <h3 style="font-size: 1.05rem; color: #1e293b; font-weight: 700; margin-bottom: 10px; line-height: 1.45; max-height: 4.57rem; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; word-break: break-word;">{{ $product->nombre }}</h3>
                        </a>

                        <div style="display: flex; justify-content: flex-start; align-items: center; padding: 8px 0 12px;">
                            <span style="display: inline-flex; align-items: center; gap: 6px; background: #f1f5f9; color: #475569; padding: 5px 12px; border-radius: 50px; font-size: 0.75rem; font-weight: 700;">
                                @if($product->laboratory && $product->laboratory->logo)
                                    <img src="{{ asset('storage/' . $product->laboratory->logo) }}" alt="" style="width: 14px; height: 14px; object-fit: contain;">
                                @elseif($product->laboratory && $product->laboratory->imagen)
                                    <img src="{{ asset('storage/' . $product->laboratory->imagen) }}" alt="" style="width: 14px; height: 14px; object-fit: contain;">
                                @else
                                    <i class="fas fa-building" style="color: #64748b;"></i>
                                @endif
                                {{ $product->laboratory->descripcion ?? 'Sanchez Pharma' }}
                            </span>
                        </div>
                        
                        <div style="margin-top: auto;">
                            <div style="display: flex; align-items: baseline; gap: 8px; margin-bottom: 20px;">
                                @if($product->is_promotional && $product->promotional_price)
                                    <span style="font-size: 1.2rem; font-weight: 700; color: #94a3b8; text-decoration: line-through;" title="Precio Anterior">S/ {{ number_format($product->precio, 2) }}</span>
                                    <span style="font-size: 1.8rem; font-weight: 900; color: #ef4444;" title="Precio Promoción">S/ {{ number_format($product->promotional_price, 2) }}</span>
                                @else
                                    <span style="font-size: 1.8rem; font-weight: 900; color: var(--primary-green);">S/ {{ number_format($product->precio, 2) }}</span>
                                @endif
                            </div>
                            
                            <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                                <button onclick="addToCart({{ $product->id }}, '{{ $product->nombre }}')" class="btn-add-cart">
                                    AGREGAR
                                </button>
                                
                                <div class="qty-selector">
                                    <button onclick="changeQty({{ $product->id }}, -1)"><i class="fas fa-minus"></i></button>
                                    <input type="text" id="qty-{{ $product->id }}" value="1" readonly>
                                    <button onclick="changeQty({{ $product->id }}, 1)"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 100px 20px; background: white; border-radius: 20px; border: 2px dashed #e2e8f0;">
                    <i class="fas fa-search" style="font-size: 4rem; color: #f1f5f9; margin-bottom: 20px;"></i>
                    <h3 style="color: #64748b;">No hay resultados</h3>
                    <p style="color: #94a3b8;">Intenta ajustar tus filtros de búsqueda.</p>
                </div>
                @endforelse
            </div>

            <div style="margin-top: 50px; display: flex; justify-content: center;">
                {{ $products->appends(request()->query())->links('partials.pagination') }}
            </div>
        </div>
    </section>

    <!-- Floating Cart Button -->
    <a href="{{ route('cart.index') }}" id="floating-cart" style="position: fixed; bottom: 30px; right: 30px; background: var(--primary-green); color: white; width: 65px; height: 65px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 30px rgba(46, 125, 50, 0.4); text-decoration: none; z-index: 1000; transition: 0.3s; border: 3px solid white;">
        <i class="fas fa-shopping-cart" style="font-size: 1.4rem;"></i>
        <span id="cart-badge" style="position: absolute; top: -5px; right: -5px; background: #ef4444; color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: bold; border: 2px solid white; display: {{ count(session('cart', [])) > 0 ? 'flex' : 'none' }};">
            {{ count(session('cart', [])) }}
        </span>
    </a>
@endsection

@push('scripts')
<script>
    function changeQty(id, delta) {
        const input = document.getElementById('qty-' + id);
        let val = parseInt(input.value) + delta;
        if (val < 1) val = 1;
        input.value = val;
    }

    function addToCart(productId, productName) {
        const qty = document.getElementById('qty-' + productId).value;
        
        fetch('{{ route('cart.add') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ product_id: productId, quantity: qty })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update floating badge
                const badge = document.getElementById('cart-badge');
                if (badge) {
                    badge.innerText = data.cart_count;
                    badge.style.display = 'flex';
                }

                // Update navigation badge
                const navBadge = document.getElementById('nav-cart-badge');
                if (navBadge) {
                    navBadge.innerText = data.cart_count;
                    navBadge.style.display = 'flex';
                }
                
                // SweetAlert2 Confirmation
                Swal.fire({
                    title: '¡Producto agregado correctamente!',
                    icon: 'success',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
            }
        });
    }

    /* ─── Hero BG ─── */
    setTimeout(() => document.getElementById('pdHeroBg')?.classList.add('loaded'), 80);

    /* ─── Particles ─── */
    (function() {
        const canvas = document.getElementById('pdParticleCanvas');
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

    // Intersection Observer for Reveal
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

    // Mobile filter toggle
    const filterToggle = document.getElementById('filterToggle');
    const filterSidebar = document.querySelector('.filters-sidebar');
    if (filterToggle && filterSidebar) {
        filterToggle.addEventListener('click', function() {
            filterSidebar.classList.toggle('open');
            this.classList.toggle('active');
            const icon = this.querySelector('.fa-chevron-down');
            if (icon) icon.style.transform = filterSidebar.classList.contains('open') ? 'rotate(180deg)' : '';
        });
    }

    // Filter functions
    function toggleAllLabs() {
        const checkboxes = document.querySelectorAll('.lab-check-input');
        const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
        checkboxes.forEach(cb => {
            cb.checked = !anyChecked;
            cb.closest('.filter-lab-checkbox-item').classList.toggle('is-selected', !anyChecked);
        });
        updateToggleAllBtn();
    }

    function updateToggleAllBtn() {
        const checkboxes = document.querySelectorAll('.lab-check-input');
        const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
        const btn = document.getElementById('toggleAllLabsBtn');
        if (btn) {
            btn.textContent = anyChecked ? 'Deseleccionar' : 'Todos';
        }
    }

    function clearSelectedLabs() {
        const checkboxes = document.querySelectorAll('.lab-check-input');
        checkboxes.forEach(cb => {
            cb.checked = false;
            cb.closest('.filter-lab-checkbox-item').classList.remove('is-selected');
        });
        const searchField = document.querySelector('.filter-search-field');
        if (searchField) searchField.value = '';
        updateToggleAllBtn();
    }

    function showMiniCartNotification(name, qty) {
        // Obsoleto - Usando SweetAlert2
    }
</script>
@endpush
