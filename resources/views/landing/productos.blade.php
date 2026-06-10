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

    <div class="pd-hero-scroll">
        <div class="pd-scroll-line"></div>
        Explorar catálogo
    </div>
</section>

    <section class="products-container" style="display: flex; gap: 40px; padding: 60px 5%; max-width: 1400px; margin: 0 auto; align-items: flex-start;">
        
        <!-- Mobile Filter Toggle -->
        <button id="filterToggle" class="filter-toggle-btn" style="display: none;">
            <i class="fas fa-filter"></i> Filtros <i class="fas fa-chevron-down"></i>
        </button>

        <!-- Sidebar Filtros -->
        <aside class="filters-sidebar" style="flex: 0 0 280px; background: white; padding: 25px; border-radius: 15px; box-shadow: var(--shadow-md); border: 1px solid #eee; position: sticky; top: 100px;">
            <h3 style="margin-bottom: 20px; color: var(--dark-green); font-size: 1.2rem; border-bottom: 2px solid #eee; padding-bottom: 10px;"><i class="fas fa-filter"></i> Filtros</h3>
            
            <form action="{{ route('products') }}" method="GET">
                <div style="margin-bottom: 25px;">
                    <label style="display: block; margin-bottom: 10px; font-weight: 500; color: #555;">Buscar:</label>
                    <div style="display: flex;">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Producto o código..." style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px 0 0 8px; outline: none;">
                        @if(request('lab'))
                        <input type="hidden" name="lab" value="{{ request('lab') }}">
                        @endif
                        <button type="submit" style="background: var(--primary-green); color: white; border: none; padding: 0 15px; border-radius: 0 8px 8px 0; cursor: pointer;"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
                
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 15px; font-weight: 600; color: #475569;">Laboratorio:</label>
                <div style="display: flex; flex-direction: column; gap: 8px; max-height: 400px; overflow-y: auto; padding-right: 5px; scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent;">
                    <a href="{{ route('products', ['search' => request('search')]) }}" style="display: block; padding: 10px 15px; border-radius: 10px; border: 2px solid {{ !request('lab') ? 'var(--primary-green)' : '#e2e8f0' }}; background: {{ !request('lab') ? '#f0fdf4' : 'white' }}; color: {{ !request('lab') ? 'var(--dark-green)' : '#475569' }}; text-decoration: none; font-weight: 600; transition: 0.2s;">
                        Todos los Laboratorios
                    </a>
                    @foreach($laboratories as $lab)
                    <a href="{{ route('products', ['lab' => $lab->id, 'search' => request('search')]) }}" style="display: block; padding: 10px 15px; border-radius: 10px; border: 2px solid {{ request('lab') == $lab->id ? 'var(--primary-green)' : '#e2e8f0' }}; background: {{ request('lab') == $lab->id ? '#f0fdf4' : 'white' }}; color: {{ request('lab') == $lab->id ? 'var(--dark-green)' : '#475569' }}; text-decoration: none; font-weight: 600; transition: 0.2s;">
                        {{ $lab->descripcion }}
                    </a>
                    @endforeach
                </div>
            </div>

            @if(request('search') || request('lab'))
                <a href="{{ route('products') }}" style="display: block; text-align: center; color: #ef4444; text-decoration: none; font-size: 0.9rem; margin-top: 20px; padding: 10px; border: 1px dashed #ef4444; border-radius: 8px;">Limpiar Filtros</a>
            @endif
        </aside>

        <!-- Product Grid -->
        <div class="products-main" style="flex: 1;">
            <div class="product-grid">
                @forelse($products as $product)
                <div class="product-card reveal" style="background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.03); display: flex; flex-direction: column; transition: 0.4s; border: 1px solid #f1f5f9; position: relative; min-width: 0;">
                    <!-- Tag Premium -->
                    <div style="position: absolute; top: 15px; left: 15px; z-index: 2;">
                        <span style="font-size: 0.7rem; font-weight: 800; color: white; background: var(--primary-green); padding: 5px 12px; border-radius: 50px; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 5px 15px rgba(46, 125, 50, 0.3);">
                            {{ $product->laboratory->descripcion ?? 'Sanchez Pharma' }}
                        </span>
                    </div>

                    <!-- Imagen con Efecto -->
                    <a href="{{ route('product.detail', $product->id) }}" class="product-img-wrapper" style="text-decoration: none; display: block; height: 260px; background: #fff; display: flex; align-items: center; justify-content: center; overflow: hidden; padding: 30px;">
                        @if($product->imagen)
                            <img src="{{ asset('storage/' . $product->imagen) }}" alt="{{ $product->nombre }}" style="max-height: 100%; max-width: 100%; object-fit: contain; transition: 0.5s;">
                        @else
                            <div style="display: flex; flex-direction: column; align-items: center; color: #e2e8f0;">
                                <i class="fas fa-pills" style="font-size: 4rem; margin-bottom: 10px;"></i>
                                <span style="font-weight: 700; font-size: 0.8rem; text-transform: uppercase;">Sin Imagen</span>
                            </div>
                        @endif
                    </a>

                    <!-- Info Premium -->
                    <div style="padding: 25px; flex-grow: 1; display: flex; flex-direction: column; background: white;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                            <p style="font-size: 0.7rem; color: #94a3b8; font-weight: 700; font-family: monospace; letter-spacing: 1px;">#{{ $product->codigo }}</p>
                            @if($product->stock > 0)
                                <span style="color: #10b981; font-size: 0.7rem; font-weight: 800;"><i class="fas fa-check-circle"></i> EN STOCK</span>
                            @endif
                        </div>
                        
                        <a href="{{ route('product.detail', $product->id) }}" style="text-decoration: none;">
                            <h3 style="font-size: 1.1rem; color: #1e293b; font-weight: 700; margin-bottom: 15px; line-height: 1.4; min-height: 3rem; word-break: break-word;">{{ $product->nombre }}</h3>
                        </a>
                        
                        <div style="margin-top: auto;">
                            <div style="display: flex; align-items: baseline; gap: 8px; margin-bottom: 20px;">
                                <span style="font-size: 1.8rem; font-weight: 900; color: var(--primary-green);">S/ {{ number_format($product->precio, 2) }}</span>
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
                    title: '¡Agregado!',
                    text: `${qty}x ${productName} se añadió a tu solicitud.`,
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

    function showMiniCartNotification(name, qty) {
        // Obsoleto - Usando SweetAlert2
    }
</script>
<style>
    .filter-toggle-btn {
        display: none; align-items: center; gap: 8px; background: var(--primary-green); color: white; border: none;
        padding: 12px 20px; border-radius: 12px; font-weight: 700; cursor: pointer; transition: 0.3s;
        width: 100%; justify-content: center; font-family: inherit; font-size: 0.95rem;
        box-shadow: 0 10px 20px rgba(46, 125, 50, 0.2);
    }
    .filter-toggle-btn:hover { transform: translateY(-2px); box-shadow: 0 15px 25px rgba(46, 125, 50, 0.3); }
    .filter-toggle-btn.active i.fa-chevron-down { transform: rotate(180deg); }

    @keyframes slideOut { from { transform: translateX(0); opacity: 1; } to { transform: translateX(120%); opacity: 0; } }

    .product-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; }
    @media (max-width: 1024px) {
        .product-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .product-grid { grid-template-columns: 1fr; }
        .products-container { flex-direction: column !important; }
        .filters-sidebar { width: 100% !important; flex: auto !important; position: static !important; display: none; }
        .filters-sidebar.open { display: block; }
        .filter-toggle-btn { display: flex !important; }
        .hero-catalog { padding: 50px 5% !important; }
        .hero-catalog h1 { font-size: 2rem !important; }
    }
    @media (max-width: 480px) {
        .hero-catalog { padding: 40px 5% !important; border-radius: 0 0 30px 30px !important; }
        .hero-catalog h1 { font-size: 1.6rem !important; }
        .hero-catalog p { font-size: 0.95rem !important; }
        #floating-cart { width: 55px !important; height: 55px !important; bottom: 20px !important; right: 20px !important; }
        #floating-cart i { font-size: 1.2rem !important; }
    }

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

    /* Custom Pagination Styles */
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
</style>
@endpush
