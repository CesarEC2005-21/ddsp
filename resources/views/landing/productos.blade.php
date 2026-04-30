@extends('layouts.landing')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .swal2-styled.swal2-confirm { background-color: var(--primary-green) !important; }
</style>
@endpush

@section('content')
    <div style="background: linear-gradient(135deg, var(--primary-green), var(--dark-green)); padding: 80px 5%; text-align: center; color: white;">
        <h1 style="font-size: 2.5rem; margin-bottom: 10px;">Catálogo de Productos</h1>
        <p style="opacity: 0.9; font-size: 1.1rem; max-width: 600px; margin: 0 auto;">Distribución farmacéutica con los más altos estándares de calidad.</p>
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
                <div class="product-card" style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); display: flex; flex-direction: column; transition: 0.3s; border: 1px solid #f1f5f9;">
                    <!-- Badge superior -->
                    <div style="padding: 10px 15px;">
                        <span style="font-size: 0.75rem; font-weight: 700; color: var(--primary-green); background: #f0fdf4; padding: 4px 12px; border-radius: 6px; text-transform: uppercase; border: 1px solid #dcfce7;">
                            {{ $product->laboratory->descripcion ?? 'Sanchez Pharma' }}
                        </span>
                    </div>

                    <!-- Imagen -->
                    <a href="{{ route('product.detail', $product->id) }}" style="text-decoration: none; display: block; height: 240px; background: #fff; display: flex; align-items: center; justify-content: center; position: relative; padding: 20px;">
                        @if($product->imagen)
                            <img src="{{ asset('storage/' . $product->imagen) }}" alt="{{ $product->nombre }}" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                        @else
                            <div style="display: flex; flex-direction: column; align-items: center; color: #cbd5e1;">
                                <i class="fas fa-pills" style="font-size: 3rem; margin-bottom: 10px;"></i>
                                <span style="font-weight: 600; font-size: 0.9rem;">Sin Imagen</span>
                            </div>
                        @endif
                    </a>

                    <!-- Info -->
                    <div style="padding: 20px; flex-grow: 1; display: flex; flex-direction: column; background: #fafafa;">
                        <p style="font-size: 0.75rem; color: #94a3b8; margin-bottom: 5px; font-weight: 600; font-family: monospace;">{{ $product->codigo }}</p>
                        <a href="{{ route('product.detail', $product->id) }}" style="text-decoration: none;">
                            <h3 style="font-size: 1.05rem; color: #1e293b; font-weight: 700; margin-bottom: 15px; line-height: 1.3; min-height: 2.6rem;">{{ $product->nombre }}</h3>
                        </a>
                        
                        <div style="margin-bottom: 20px;">
                            <span style="display: block; width: 100%; text-align: center; background: white; color: #64748b; padding: 8px; border-radius: 6px; font-weight: 600; font-size: 0.8rem; border: 1px solid #e2e8f0; text-transform: uppercase; letter-spacing: 0.5px;">
                                {{ $product->laboratory->descripcion ?? 'GENERAL' }}
                            </span>
                        </div>

                        <div style="margin-top: auto;">
                            <p style="font-size: 1.6rem; font-weight: 800; color: #0f172a; margin-bottom: 15px;">S/ {{ number_format($product->precio, 2) }}</p>
                            
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <button onclick="addToCart({{ $product->id }}, '{{ $product->nombre }}')" class="btn" style="background: var(--primary-green); color: white; border: none; padding: 12px 15px; border-radius: 10px; font-weight: 700; flex: 1; display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; transition: 0.3s; box-shadow: 0 4px 10px rgba(46, 125, 50, 0.2);">
                                    <i class="fas fa-shopping-basket"></i> AGREGAR
                                </button>
                                
                                <div style="display: flex; align-items: center; background: white; border-radius: 10px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.03);">
                                    <button onclick="changeQty({{ $product->id }}, -1)" style="border: none; background: transparent; padding: 10px 12px; cursor: pointer; color: #94a3b8;"><i class="fas fa-minus"></i></button>
                                    <input type="text" id="qty-{{ $product->id }}" value="1" readonly style="width: 30px; border: none; background: transparent; text-align: center; font-weight: 700; color: #1e293b; font-size: 0.95rem;">
                                    <button onclick="changeQty({{ $product->id }}, 1)" style="border: none; background: transparent; padding: 10px 12px; cursor: pointer; color: #94a3b8;"><i class="fas fa-plus"></i></button>
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
                {{ $products->appends(request()->query())->links() }}
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
                // Update badge
                const badge = document.getElementById('cart-badge');
                badge.innerText = data.cart_count;
                badge.style.display = 'flex';
                
                // Professional Mini-Cart Notification
                showMiniCartNotification(productName, qty);
            }
        });
    }

    function showMiniCartNotification(name, qty) {
        // Remove existing if any
        const old = document.getElementById('mini-cart-notif');
        if (old) old.remove();

        const notif = document.createElement('div');
        notif.id = 'mini-cart-notif';
        notif.style.cssText = `
            position: fixed; top: 110px; right: 20px; width: 320px; 
            background: white; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            z-index: 10000; padding: 20px; border: 1px solid #f1f5f9;
            animation: slideIn 0.5s cubic-bezier(0.18, 0.89, 0.32, 1.28);
        `;

        notif.innerHTML = `
            <div style="display: flex; gap: 15px; align-items: center; margin-bottom: 20px;">
                <div style="width: 50px; height: 50px; background: #f0fdf4; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--primary-green);">
                    <i class="fas fa-check-circle" style="font-size: 1.5rem;"></i>
                </div>
                <div>
                    <h4 style="margin: 0; color: #1e293b; font-size: 0.95rem;">¡Producto Agregado!</h4>
                    <p style="margin: 3px 0 0 0; font-size: 0.85rem; color: #64748b;">${qty}x ${name}</p>
                </div>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('cart.index') }}" class="btn btn-primary" style="flex: 1; padding: 10px; font-size: 0.85rem; text-align: center; border-radius: 10px;">VER CARRITO</a>
                <button onclick="this.parentElement.parentElement.remove()" class="btn" style="flex: 1; padding: 10px; font-size: 0.85rem; background: #f1f5f9; color: #64748b; border: none; border-radius: 10px; cursor: pointer;">CONTINUAR</button>
            </div>
            <style>
                @keyframes slideIn { from { transform: translateX(120%); } to { transform: translateX(0); } }
            </style>
        `;

        document.body.appendChild(notif);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notif) {
                notif.style.animation = 'slideOut 0.5s forwards';
                setTimeout(() => notif.remove(), 500);
            }
        }, 5000);
    }
</script>
<style>
    @keyframes slideOut { from { transform: translateX(0); opacity: 1; } to { transform: translateX(120%); opacity: 0; } }
</style>
@endpush
