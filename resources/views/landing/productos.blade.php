@extends('layouts.landing')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .swal2-styled.swal2-confirm { background-color: var(--primary-green) !important; }
</style>
@endpush

@section('content')
    <div style="background: linear-gradient(rgba(27, 94, 32, 0.85), rgba(27, 94, 32, 0.95)), url('https://images.unsplash.com/photo-1585435557343-3b092031a831?auto=format&fit=crop&w=1920&q=80') center/cover; color: white; text-align: center; padding: 80px 5%; border-radius: 0 0 50px 50px; margin-bottom: 40px;">
        <h1 style="font-size: 3rem; font-family: 'Poppins', sans-serif; color: white !important; font-weight: 800; margin-bottom: 15px;">Catálogo de Productos</h1>
        <p style="font-size: 1.1rem; opacity: 0.9; color: white; max-width: 600px; margin: 0 auto;">Explora nuestro catálogo completo de medicamentos y productos farmacéuticos de la más alta calidad.</p>
    </div>

    <section class="products-container" style="display: flex; gap: 40px; padding: 60px 5%; max-width: 1400px; margin: 0 auto; align-items: flex-start;">
        
        <!-- Sidebar Filtros -->
        <aside class="filters-sidebar" style="flex: 0 0 280px; background: white; padding: 25px; border-radius: 15px; box-shadow: var(--shadow-md); border: 1px solid #eee; position: sticky; top: 100px;">
            <h3 style="margin-bottom: 20px; color: var(--dark-green); font-size: 1.2rem; border-bottom: 2px solid #eee; padding-bottom: 10px;"><i class="fas fa-filter"></i> Filtros</h3>
            
            <form action="{{ route('products') }}" method="GET">
                <div style="margin-bottom: 25px;">
                    <label style="display: block; margin-bottom: 10px; font-weight: 500; color: #555;">Buscar:</label>
                    <div style="display: flex;">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Producto o código..." style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px 0 0 8px; outline: none;">
                        <button type="submit" style="background: var(--primary-green); color: white; border: none; padding: 0 15px; border-radius: 0 8px 8px 0; cursor: pointer;"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 10px; font-weight: 500; color: #555;">Laboratorios:</label>
                    <div style="max-height: 400px; overflow-y: auto; padding-right: 5px;">
                        <div style="margin-bottom: 8px;">
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 8px; border-radius: 8px; background: {{ request('lab') == '' ? '#f0fdf4' : 'transparent' }}; border: 1px solid {{ request('lab') == '' ? 'var(--primary-green)' : 'transparent' }};">
                                <input type="radio" name="lab" value="" {{ request('lab') == '' ? 'checked' : '' }} onchange="this.form.submit()" style="display: none;">
                                <span style="font-size: 0.95rem; {{ request('lab') == '' ? 'color: var(--primary-green); font-weight: 600;' : 'color: #666;' }}">Todos</span>
                            </label>
                        </div>
                        @foreach($laboratories as $lab)
                        <div style="margin-bottom: 8px;">
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 8px; border-radius: 8px; background: {{ request('lab') == $lab->id ? '#f0fdf4' : 'transparent' }}; border: 1px solid {{ request('lab') == $lab->id ? 'var(--primary-green)' : 'transparent' }};">
                                <input type="radio" name="lab" value="{{ $lab->id }}" {{ request('lab') == $lab->id ? 'checked' : '' }} onchange="this.form.submit()" style="display: none;">
                                <span style="font-size: 0.95rem; {{ request('lab') == $lab->id ? 'color: var(--primary-green); font-weight: 600;' : 'color: #666;' }}">{{ $lab->descripcion }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                @if(request('search') || request('lab'))
                    <a href="{{ route('products') }}" style="display: block; text-align: center; color: #ef4444; text-decoration: none; font-size: 0.9rem; margin-top: 20px; padding: 10px; border: 1px dashed #ef4444; border-radius: 8px;">Limpiar Filtros</a>
                @endif
            </form>
        </aside>

        <!-- Product Grid -->
        <div class="products-main" style="flex: 1;">
            <div class="product-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 30px;">
                @forelse($products as $product)
                <div class="product-card reveal" style="background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.03); display: flex; flex-direction: column; transition: 0.4s; border: 1px solid #f1f5f9; position: relative;">
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
                            <h3 style="font-size: 1.1rem; color: #1e293b; font-weight: 700; margin-bottom: 15px; line-height: 1.4; min-height: 3rem;">{{ $product->nombre }}</h3>
                        </a>
                        
                        <div style="margin-top: auto;">
                            <div style="display: flex; align-items: baseline; gap: 8px; margin-bottom: 20px;">
                                <span style="font-size: 1.8rem; font-weight: 900; color: var(--primary-green);">S/ {{ number_format($product->precio, 2) }}</span>
                                <span style="font-size: 0.8rem; color: #94a3b8; font-weight: 600;">/ unidad</span>
                            </div>
                            
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <button onclick="addToCart({{ $product->id }}, '{{ $product->nombre }}')" class="btn-add-cart">
                                    <i class="fas fa-shopping-basket"></i>
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

    // Intersection Observer for Reveal
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

    function showMiniCartNotification(name, qty) {
        // Obsoleto - Usando SweetAlert2
    }
</script>
<style>
    @keyframes slideOut { from { transform: translateX(0); opacity: 1; } to { transform: translateX(120%); opacity: 0; } }

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
    .pagination { display: flex; padding-left: 0; list-style: none; justify-content: center; gap: 8px; margin-top: 20px; }
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
