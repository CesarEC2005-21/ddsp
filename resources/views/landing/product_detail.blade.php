@extends('layouts.landing')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="{{ asset('css/landing/product_detail.css') }}">
@endpush

@section('content')
<div class="product-detail-page">
    <div class="breadcrumb-area">
        <div class="breadcrumb-nav">
            <a href="{{ route('home') }}">Inicio</a>
            <i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i>
            <a href="{{ route('products') }}">Catálogo</a>
            <i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i>
            <span>{{ $product->nombre }}</span>
        </div>
    </div>

    <div class="detail-grid">
        <!-- Izquierda: Imagen -->
        <div class="product-gallery">
            <div class="main-img-box">
                @if($product->imagen)
                    <img src="{{ asset('storage/' . $product->imagen) }}" alt="{{ $product->nombre }}">
                @else
                    <div style="text-align: center; color: #cbd5e1;">
                        <i class="fas fa-pills" style="font-size: 8rem; margin-bottom: 20px;"></i>
                        <p style="font-weight: 700; font-size: 1.2rem;">Imagen en proceso</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Derecha: Información -->
        <div class="product-content-side">
            <div class="lab-tag">
                <i class="fas fa-flask"></i>
                {{ $product->laboratory->descripcion ?? 'SANCHEZ PHARMA' }}
            </div>
            
            <h1 class="product-name">{{ $product->nombre }}</h1>
            <div class="product-sku">REF: {{ $product->codigo }}</div>

            <div class="price-display">
                <small>S/</small> {{ number_format($product->precio, 2) }}
            </div>

            <div class="spec-grid">
                <div class="spec-item">
                    <span class="spec-label">Presentación / UM</span>
                    <span class="spec-value">{{ $product->unidadMedida->um ?? 'Unidad' }}</span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Disponibilidad</span>
                    <span class="spec-value" style="color: #10b981;"><i class="fas fa-check-circle"></i> En Stock</span>
                </div>
            </div>

            <div class="desc-section">
                <h3 class="desc-title">Descripción del Producto</h3>
                <div class="desc-text">
                    {{ $product->descripcion ?: 'Este producto ha sido seleccionado cuidadosamente bajo los más altos estándares de calidad farmacéutica para asegurar su eficacia y seguridad en el tratamiento.' }}
                </div>
            </div>

            <div class="action-bar">
                <div class="qty-selector">
                    <button class="qty-btn" onclick="changeQty(-1)"><i class="fas fa-minus"></i></button>
                    <input type="text" id="detail-qty" value="1" readonly class="qty-input">
                    <button class="qty-btn" onclick="changeQty(1)"><i class="fas fa-plus"></i></button>
                </div>
                
                <button onclick="addToCart({{ $product->id }}, '{{ $product->nombre }}')" class="add-to-cart-btn">
                    <i class="fas fa-cart-plus" style="font-size: 1.4rem;"></i> AGREGAR A MI SOLICITUD
                </button>
            </div>

            <div style="margin-top: 40px; background: #fffbeb; border: 1px solid #fef3c7; padding: 20px; border-radius: 20px; display: flex; gap: 15px; align-items: flex-start;">
                <i class="fas fa-info-circle" style="color: #f59e0b; font-size: 1.2rem; margin-top: 2px;"></i>
                <p style="font-size: 0.9rem; color: #92400e; margin: 0; line-height: 1.5;">
                    <b>Nota:</b> La venta de algunos productos farmacéuticos puede requerir receta médica. Consulte con su especialista antes de la adquisición.
                </p>
            </div>
        </div>
    </div>

    <!-- Productos Relacionados -->
    @if($relatedProducts->count() > 0)
    <section style="margin-top: 120px; border-top: 1px solid #f1f5f9; padding-top: 80px;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 5%;">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 50px;">
                <div>
                    <span style="color: var(--primary-green); font-weight: 800; font-size: 0.8rem; text-transform: uppercase;">Sugerencias</span>
                    <h2 style="font-size: 2.2rem; color: #1e293b; margin-top: 10px;">También te puede interesar</h2>
                </div>
                <a href="{{ route('products') }}?lab={{ $product->laboratory_id }}" style="color: var(--primary-green); text-decoration: none; font-weight: 700;">Ver más de este laboratorio &rarr;</a>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 30px;">
                @foreach($relatedProducts as $rel)
                <div style="background: white; border-radius: 25px; padding: 25px; border: 1px solid #f1f5f9; transition: 0.3s; text-align: center;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 15px 30px rgba(0,0,0,0.05)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                    <a href="{{ route('product.detail', $rel->id) }}" style="text-decoration: none;">
                        <div style="height: 200px; margin-bottom: 20px; display: flex; align-items: center; justify-content: center;">
                            <img src="{{ $rel->imagen ? asset('storage/'.$rel->imagen) : 'https://via.placeholder.com/200' }}" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                        </div>
                        <h4 style="color: #1e293b; margin-bottom: 10px; font-size: 1.1rem; font-weight: 700;">{{ $rel->nombre }}</h4>
                        <p style="font-weight: 800; color: var(--primary-green); font-size: 1.2rem;">S/ {{ number_format($rel->precio, 2) }}</p>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</div>
@endsection

@push('scripts')
<script>
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
                // Show custom Mini-Cart Notification (reused from catalog logic)
                if (typeof showMiniCartNotification === 'function') {
                    showMiniCartNotification(productName, qty);
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Producto Agregado!',
                        text: `${qty}x ${productName} se añadió a tu solicitud.`,
                        confirmButtonColor: '#2e7d32'
                    });
                }
            }
        });
    }
</script>
@endpush
