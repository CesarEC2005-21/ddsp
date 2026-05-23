@extends('layouts.landing')

@push('styles')
<style>
    :root {
        --brand-green: var(--primary-green, #2E7D32);
        --text-dark: #1e293b;
    }
    .product-detail-page { padding: 60px 5%; max-width: 1300px; margin: 0 auto; background: white; }
    .breadcrumb-area { margin-bottom: 40px; }
    .detail-grid { display: grid; grid-template-columns: 1.2fr 1fr; gap: 60px; align-items: start; }
    
    /* Zoom Effect Container */
    .product-gallery { position: relative; }
    .main-img-box { 
        background: white; border-radius: 30px; padding: 40px; 
        position: relative; overflow: hidden; border: 1px solid #f1f5f9;
        cursor: crosshair;
    }
    .main-img-box img { 
        width: 100%; max-height: 500px; object-fit: contain; 
        transition: transform 0.3s ease-out;
        transform-origin: center center;
    }
    .main-img-box:hover img { transform: scale(1.8); }

    .category-tag { color: var(--brand-green); font-weight: 700; font-size: 0.9rem; margin-bottom: 10px; display: block; }
    .product-name { font-size: 2.2rem; font-weight: 800; color: #1e293b; margin-bottom: 20px; line-height: 1.2; letter-spacing: -0.5px; border-bottom: 2px solid #e2e8f0; padding-bottom: 15px; }
    .product-short-desc { font-size: 1.1rem; color: #64748b; line-height: 1.6; margin-bottom: 30px; }

    /* Accordion Styles */
    .accordion { margin-top: 20px; border-top: 1px solid #e2e8f0; }
    .accordion-item { border-bottom: 1px solid #e2e8f0; }
    .accordion-header { 
        padding: 15px 0; display: flex; justify-content: space-between; align-items: center; 
        cursor: pointer; font-weight: 700; color: var(--text-dark); transition: 0.3s;
    }
    .accordion-header:hover { color: var(--brand-green); }
    .accordion-content { max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; color: #475569; font-size: 0.95rem; line-height: 1.5; }
    .accordion-item.active .accordion-content { max-height: 200px; padding-bottom: 15px; }
    .accordion-item.active .fa-chevron-down { transform: rotate(180deg); }

    /* Price and Presentation Layout */
    .info-row { display: flex; align-items: center; gap: 30px; margin-top: 30px; padding: 20px; background: #f8fafc; border-radius: 15px; flex-wrap: wrap; }
    .pres-label { font-weight: 700; color: var(--text-dark); display: block; margin-bottom: 5px; font-size: 0.9rem; }
    .pres-box { 
        display: inline-block; padding: 8px 15px; border: 1px solid var(--brand-green); 
        border-radius: 8px; color: var(--brand-green); font-weight: 700; background: white;
    }
    .price-tag { font-weight: 900; font-size: 2rem; color: var(--brand-green); margin: 0; }

    .action-row { display: flex; align-items: center; gap: 20px; margin-top: 30px; flex-wrap: wrap; }
    .qty-control { display: flex; align-items: center; border: 2px solid #e2e8f0; border-radius: 8px; overflow: hidden; height: 55px; }
    .qty-control button { width: 45px; border: none; background: white; cursor: pointer; font-size: 1.2rem; color: #64748b; transition: 0.3s; }
    .qty-control button:hover { background: #f1f5f9; color: var(--brand-green); }
    .qty-control input { width: 50px; border: none; text-align: center; font-weight: 700; color: #1e293b; outline: none; font-size: 1.1rem; }
    
    .btn-add { 
        flex: 1; height: 55px; background: var(--brand-green); color: white; border: none; border-radius: 8px; 
        font-weight: 800; font-size: 1.1rem; cursor: pointer; transition: 0.3s; text-transform: uppercase;
        letter-spacing: 1px; box-shadow: 0 10px 20px rgba(46, 125, 50, 0.2);
    }
    .btn-add:hover { background: var(--dark-green, #1B5E20); transform: translateY(-2px); box-shadow: 0 15px 30px rgba(46, 125, 50, 0.3); }

    @media (max-width: 968px) {
        .detail-grid { grid-template-columns: 1fr; gap: 40px; }
        .product-name { font-size: 1.8rem; }
    }
    
    /* Related Products Grid */
    .related-title { font-size: 1.8rem; font-weight: 800; color: var(--text-dark); margin: 60px 0 30px; text-align: center; }
    .related-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 25px; }
    .related-card { background: white; border: 1px solid #e2e8f0; border-radius: 15px; padding: 20px; text-align: center; transition: 0.3s; text-decoration: none; display: block; }
    .related-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); border-color: var(--brand-green); }
    .related-img { height: 150px; width: 100%; object-fit: contain; margin-bottom: 15px; }
    .related-name { font-size: 1rem; color: #1e293b; font-weight: 700; margin-bottom: 10px; line-height: 1.3; }
    .related-price { color: var(--brand-green); font-weight: 800; font-size: 1.2rem; }
</style>
@endpush

@section('content')
<div class="product-detail-page">
    <div class="detail-grid">
        <!-- Izquierda: Imagen con Zoom -->
        <div class="product-gallery">
            <div class="main-img-box" id="zoom-container">
                @if($product->imagen)
                    <img src="{{ asset('storage/' . $product->imagen) }}" alt="{{ $product->nombre }}" id="main-image">
                @else
                    <div style="text-align: center; color: #cbd5e1; padding: 100px 0;">
                        <i class="fas fa-pills" style="font-size: 8rem;"></i>
                    </div>
                @endif
            </div>
            
            <div style="position: absolute; top: 15px; right: 15px; color: #94a3b8; pointer-events: none;">
                <i class="fas fa-search-plus"></i> <span style="font-size: 0.7rem; font-weight: 700;">HOVER PARA ZOOM</span>
            </div>
        </div>

        <!-- Derecha: Información -->
        <div class="product-content-side">
            <span class="category-tag">LABORATORIO: {{ $product->laboratory->descripcion ?? 'Sanchez Pharma' }}</span>
            <h1 class="product-name">{{ $product->nombre }}</h1>
            
            <div class="product-short-desc">
                {{ $product->descripcion ?: 'Producto farmacéutico distribuido bajo rigurosos controles de calidad para garantizar su efectividad y seguridad terapéutica.' }}
            </div>

            <!-- Accordion -->
            <div class="accordion">
                <div class="accordion-item active">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <span>Usos y Beneficios</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="accordion-content">
                        {{ $product->usos ?: 'Consulte a su médico para obtener información detallada sobre el uso de este producto.' }}
                    </div>
                </div>

                <div class="accordion-item">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <span>Composición Técnica</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="accordion-content">
                        {{ $product->composicion ?: 'Composición estándar según farmacopea vigente.' }}
                    </div>
                </div>

                <div class="accordion-item">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <span>Contraindicaciones y Advertencias</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="accordion-content">
                        {{ $product->contraindicaciones ?: 'Hipersensibilidad a los componentes de la fórmula.' }}
                    </div>
                </div>

                @if($product->registro_sanitario)
                <div class="accordion-item">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <span>Registro Sanitario</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="accordion-content">
                        <b>RS:</b> {{ $product->registro_sanitario }}
                    </div>
                </div>
                @endif
            </div>

            <!-- Price and Presentation Row -->
            <div class="info-row">
                <div style="flex: 1;">
                    <span class="pres-label">Presentación:</span>
                    <div class="pres-box">
                        {{ $product->unidadMedida->um ?? 'Unidad' }}
                    </div>
                </div>
                <div style="text-align: right;">
                    <span class="pres-label">Precio Sugerido:</span>
                    <p class="price-tag">S/ {{ number_format($product->precio, 2) }}</p>
                </div>
            </div>

            <div class="action-row">
                <div class="qty-control">
                    <button onclick="changeQty(-1)">-</button>
                    <input type="text" id="detail-qty" value="1" readonly>
                    <button onclick="changeQty(1)">+</button>
                </div>
                <button onclick="addToCart({{ $product->id }}, '{{ $product->nombre }}')" class="btn-add">
                    <i class="fas fa-cart-plus"></i> AGREGAR AL PEDIDO
                </button>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div style="margin-top: 50px; border-top: 1px solid #f1f5f9; padding-top: 20px;">
        <h2 class="related-title">Productos Similares</h2>
        <div class="related-grid">
            @foreach($relatedProducts as $rel)
            <a href="{{ route('product.detail', $rel->id) }}" class="related-card">
                @if($rel->imagen)
                    <img src="{{ asset('storage/' . $rel->imagen) }}" alt="{{ $rel->nombre }}" class="related-img">
                @else
                    <div style="height: 150px; display: flex; align-items: center; justify-content: center; color: #cbd5e1; margin-bottom: 15px;">
                        <i class="fas fa-pills" style="font-size: 4rem;"></i>
                    </div>
                @endif
                <h3 class="related-name">{{ Str::limit($rel->nombre, 50) }}</h3>
                <span class="related-price">S/ {{ number_format($rel->precio, 2) }}</span>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Zoom Logic Improved
    const container = document.getElementById('zoom-container');
    const img = document.getElementById('main-image');

    if(container && img) {
        container.addEventListener('mousemove', (e) => {
            const { left, top, width, height } = container.getBoundingClientRect();
            const x = ((e.pageX - left) / width) * 100;
            const y = ((e.pageY - top - window.scrollY) / height) * 100;
            
            img.style.transformOrigin = `${x}% ${y}%`;
            img.style.transform = 'scale(2.5)';
        });

        container.addEventListener('mouseleave', () => {
            img.style.transform = 'scale(1)';
            img.style.transformOrigin = 'center center';
        });
    }

    function toggleAccordion(header) {
        const item = header.parentElement;
        item.classList.toggle('active');
    }

    function changeQty(delta) {
        const input = document.getElementById('detail-qty');
        let val = parseInt(input.value) + delta;
        if (val < 1) val = 1;
        input.value = val;
    }

    function addToCart(productId, productName) {
        const qty = document.getElementById('detail-qty').value;
        
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
                // Actualizar contadores globales
                const navBadge = document.getElementById('nav-cart-badge');
                if (navBadge) {
                    navBadge.innerText = data.cartCount;
                    navBadge.style.display = 'flex';
                }
                
                const floatingBadge = document.getElementById('cart-badge');
                if (floatingBadge) {
                    floatingBadge.innerText = data.cartCount;
                    floatingBadge.style.display = 'flex';
                }

                Swal.fire({
                    title: '¡Producto Agregado!',
                    text: `${qty}x ${productName} se añadió a tu solicitud.`,
                    icon: 'success',
                    confirmButtonColor: '#2E7D32'
                });
            }
        });
    }
</script>
@endpush
