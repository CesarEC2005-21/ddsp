@extends('layouts.landing')

@push('styles')
<style>
    .cart-page { background: #f8fafc; padding: 60px 5%; min-height: 90vh; }
    .cart-container { max-width: 1500px; margin: 0 auto; display: grid; grid-template-columns: 1fr 550px; gap: 20px; }
    
    .cart-items-card { background: white; border-radius: 30px; padding: 25px; box-shadow: 0 10px 40px rgba(0,0,0,0.03); border: 1px solid #f1f5f9; }
    .cart-item { display: flex; gap: 25px; padding: 25px 0; border-bottom: 1px solid #f1f5f9; align-items: center; position: relative; }
    .cart-item:last-child { border-bottom: none; }
    
    .item-img-box { 
        width: 160px; height: 160px; background: #fff; border-radius: 20px; 
        display: flex; align-items: center; justify-content: center; 
        border: 1px solid #f1f5f9; overflow: hidden; padding: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.02);
    }
    .item-img-box img { max-width: 100%; max-height: 100%; object-fit: contain; transition: 0.3s; }
    .cart-item:hover .item-img-box img { transform: scale(1.1); }

    .item-info { flex: 1; }
    .item-title { font-size: 1.2rem; font-weight: 800; color: #1e293b; margin-bottom: 5px; }
    .item-meta { font-size: 0.85rem; color: #94a3b8; font-weight: 600; margin-bottom: 15px; }
    
    .qty-row { display: flex; justify-content: space-between; align-items: center; }
    .qty-spinner { display: flex; align-items: center; background: #f1f5f9; border-radius: 12px; padding: 5px; }
    .qty-spinner button { width: 35px; height: 35px; border-radius: 10px; border: none; background: white; cursor: pointer; color: #1e293b; font-weight: 700; transition: 0.3s; }
    .qty-spinner button:hover { background: var(--primary-green); color: white; }
    .qty-spinner span { width: 40px; text-align: center; font-weight: 800; color: #1e293b; }

    .checkout-card { background: white; border-radius: 30px; padding: 30px; box-shadow: 0 20px 50px rgba(0,0,0,0.08); border: 1px solid #f1f5f9; height: fit-content; position: sticky; top: 120px; }
    .checkout-title { font-size: 1.5rem; font-weight: 900; color: #1e293b; margin-bottom: 30px; text-align: center; }
    
    .form-group { margin-bottom: 20px; }
    .form-label { display: block; font-size: 0.85rem; font-weight: 800; color: #64748b; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; }
    .form-input { 
        width: 100%; padding: 15px 20px; border-radius: 15px; border: 2px solid #f1f5f9; 
        background: #f8fafc; font-size: 1rem; color: #1e293b; outline: none; transition: 0.3s; 
        box-sizing: border-box;
    }
    .form-input:focus { border-color: var(--primary-green); background: white; box-shadow: 0 0 0 4px rgba(46, 125, 50, 0.1); }
    
    .summary-box { background: #f0fdf4; border-radius: 20px; padding: 25px; margin: 30px 0; border: 1px dashed var(--primary-green); }
    .summary-line { display: flex; justify-content: space-between; margin-bottom: 10px; color: #64748b; font-weight: 600; }
    .summary-total { display: flex; justify-content: space-between; align-items: baseline; border-top: 1px solid rgba(46, 125, 50, 0.2); padding-top: 15px; margin-top: 15px; }
    .total-val { font-size: 2.2rem; font-weight: 900; color: var(--primary-green); }

    .btn-confirm { 
        width: 100%; padding: 20px; background: var(--primary-green); color: white; border: none; 
        border-radius: 18px; font-size: 1.1rem; font-weight: 900; cursor: pointer; transition: 0.3s;
        text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 15px 30px rgba(46, 125, 50, 0.25);
    }
    .btn-confirm:hover { transform: translateY(-3px); box-shadow: 0 20px 40px rgba(46, 125, 50, 0.35); background: #1B5E20; }

    @media (max-width: 1100px) {
        .cart-container { grid-template-columns: 1fr; }
        .checkout-card { position: static; }
    }
</style>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
@endpush

@section('content')
<div class="cart-page">
    <div class="cart-container">
        
        @if(count($cart) > 0)
        <!-- Sección de Items -->
        <div class="cart-items-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
                <h2 style="font-size: 1.8rem; font-weight: 900; color: #1e293b;">Tu Carrito <span style="color: #94a3b8; font-size: 1.2rem; font-weight: 600;">({{ count($cart) }} items)</span></h2>
                <a href="{{ route('cart.clear') }}" style="color: #ef4444; text-decoration: none; font-weight: 700; font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-trash-alt"></i> Vaciar Carrito
                </a>
            </div>

            @php $total = 0; @endphp
            @foreach($cart as $id => $details)
                @php $total += $details['price'] * $details['quantity']; @endphp
                <div class="cart-item">
                    <div class="item-img-box">
                        @if($details['image'])
                            <img src="{{ asset('storage/' . $details['image']) }}" alt="{{ $details['name'] }}">
                        @else
                            <i class="fas fa-pills" style="font-size: 2.5rem; color: #e2e8f0;"></i>
                        @endif
                    </div>
                    <div class="item-info">
                        <div style="display: flex; justify-content: space-between;">
                            <h4 class="item-title">{{ $details['name'] }}</h4>
                            <button onclick="removeFromCart({{ $id }})" style="border: none; background: transparent; color: #cbd5e1; cursor: pointer; transition: 0.3s; font-size: 1.2rem;" onmouseover="this.style.color='#ef4444'">&times;</button>
                        </div>
                        <p class="item-meta">CÓDIGO: {{ $details['code'] }} | {{ $details['lab'] }}</p>
                        
                        <div class="qty-row">
                            <div class="qty-spinner">
                                <button onclick="updateCart({{ $id }}, -1, this)">-</button>
                                <span>{{ $details['quantity'] }}</span>
                                <button onclick="updateCart({{ $id }}, 1, this)">+</button>
                            </div>
                            <div style="text-align: right;">
                                <span style="display: block; font-size: 0.8rem; color: #94a3b8; font-weight: 700;">UNIT: S/ {{ number_format($details['price'], 2) }}</span>
                                <span style="font-size: 1.3rem; font-weight: 900; color: #1e293b;">S/ {{ number_format($details['price'] * $details['quantity'], 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <div style="margin-top: 40px; padding-top: 30px; border-top: 2px dashed #f1f5f9;">
                <a href="{{ route('products') }}" style="color: var(--primary-green); text-decoration: none; font-weight: 800; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-arrow-left"></i> Seguir agregando productos
                </a>
            </div>
        </div>

        <!-- Formulario Checkout -->
        <div class="checkout-card">
            <h3 class="checkout-title">Datos de Entrega</h3>
            
            <form action="{{ route('quotation.store') }}" method="POST" id="quotationForm">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label class="form-label">Nombres</label>
                        <input type="text" name="nombre" class="form-input" required placeholder="Juan">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Apellidos</label>
                        <input type="text" name="apellidos" class="form-input" required placeholder="Pérez">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">WhatsApp / Teléfono</label>
                    <input type="tel" name="telefono" pattern="[0-9]{9}" maxlength="9" class="form-input" required placeholder="999888777">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 15px;">
                    <div class="form-group">
                        <label class="form-label">Documento</label>
                        <select name="tipo_documento" id="tipo_doc" class="form-input" onchange="toggleDocLength()">
                            <option value="DNI">DNI</option>
                            <option value="RUC">RUC</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nro Doc</label>
                        <input type="text" name="numero_documento" id="nro_doc" class="form-input" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Ciudad / Localidad</label>
                    <input type="text" name="ciudad" id="ciudad" class="form-input" required placeholder="Ej. Lima, Trujillo..." onchange="geocodeCity(this.value)">
                </div>

                <div class="form-group">
                    <label class="form-label">Dirección Exacta (Seleccione en el mapa)</label>
                    <div id="map" style="height: 250px; width: 100%; border-radius: 15px; border: 2px solid #f1f5f9; margin-bottom: 10px; z-index: 1;"></div>
                    <input type="text" name="direccion_exacta" id="direccion_exacta" class="form-input" required placeholder="Arrastre el pin del mapa o escriba su dirección exacta" onchange="geocodeAddress(this.value)">
                    <input type="hidden" name="latitud" id="latitud">
                    <input type="hidden" name="longitud" id="longitud">
                </div>

                <div class="form-group">
                    <label class="form-label">Correo Electrónico</label>
                    <input type="email" name="email" class="form-input" required placeholder="ejemplo@correo.com">
                </div>

                <div class="form-group">
                    <label class="form-label">Observaciones adicionales</label>
                    <textarea name="observaciones" class="form-input" style="height: 100px; resize: none;" placeholder="Ej. Referencias de ubicación..."></textarea>
                </div>

                <div class="summary-box">
                    <div class="summary-line">
                        <span>Items seleccionados</span>
                        <span style="color: #1e293b;">{{ count($cart) }}</span>
                    </div>
                    <div class="summary-total">
                        <span style="font-weight: 900; color: #1e293b; font-size: 1.1rem;">TOTAL ESTIMADO</span>
                        <div class="total-val">S/ {{ number_format($total, 2) }}</div>
                    </div>
                    <input type="hidden" name="total" value="{{ $total }}">
                </div>

                <button type="submit" class="btn-confirm">
                    Finalizar Solicitud <i class="fas fa-check-circle" style="margin-left: 10px;"></i>
                </button>
                
                <p style="text-align: center; color: #94a3b8; font-size: 0.75rem; margin-top: 20px;">
                    <i class="fas fa-shield-alt"></i> Compra 100% Segura con Sanchez Pharma
                </p>
            </form>
        </div>

        @else
        <div style="grid-column: 1 / -1; background: white; padding: 100px 5%; border-radius: 40px; text-align: center; box-shadow: 0 20px 40px rgba(0,0,0,0.02); border: 1px solid #f1f5f9;">
            <div style="width: 150px; height: 150px; background: #f0fdf4; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px;">
                <i class="fas fa-shopping-cart" style="font-size: 4.5rem; color: var(--primary-green); opacity: 0.4;"></i>
            </div>
            <h2 style="font-size: 2.5rem; font-weight: 900; color: #1e293b; margin-bottom: 15px;">Tu carrito está vacío</h2>
            <p style="color: #64748b; font-size: 1.2rem; max-width: 500px; margin: 0 auto 40px;">Parece que aún no has agregado productos a tu solicitud. Explora nuestro catálogo y encuentra lo que necesitas.</p>
            <a href="{{ route('products') }}" class="btn-confirm" style="display: inline-block; width: auto; padding: 20px 60px; text-decoration: none;">Ir al Catálogo</a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
    // Leaflet Map Init
    let map;
    let marker;

    function initMap() {
        // Default a Lima, Peru
        map = L.map('map').setView([-12.046374, -77.042793], 12);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        marker = L.marker([-12.046374, -77.042793], {draggable: true}).addTo(map);

        marker.on('dragend', function(e) {
            let lat = marker.getLatLng().lat;
            let lng = marker.getLatLng().lng;
            updateAddressFromCoords(lat, lng);
        });

        map.on('click', function(e) {
            let lat = e.latlng.lat;
            let lng = e.latlng.lng;
            marker.setLatLng([lat, lng]);
            updateAddressFromCoords(lat, lng);
        });
    }

    function updateAddressFromCoords(lat, lng) {
        document.getElementById('latitud').value = lat;
        document.getElementById('longitud').value = lng;

        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.display_name) {
                    document.getElementById('direccion_exacta').value = data.display_name;
                }
            })
            .catch(err => console.error("Geocoding error", err));
    }

    function geocodeCity(city) {
        if(!city) return;
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${city}, Peru`)
            .then(response => response.json())
            .then(data => {
                if(data && data.length > 0) {
                    let lat = data[0].lat;
                    let lng = data[0].lon;
                    map.setView([lat, lng], 13);
                    marker.setLatLng([lat, lng]);
                }
            })
            .catch(err => console.error("Geocoding city error", err));
    }

    function geocodeAddress(address) {
        if(!address) return;
        let city = document.getElementById('ciudad').value || 'Peru';
        // Agregamos la ciudad y/o 'Peru' para mejorar la precisión de la búsqueda
        let query = `${address}, ${city}`;
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                if(data && data.length > 0) {
                    let lat = data[0].lat;
                    let lng = data[0].lon;
                    map.setView([lat, lng], 16);
                    marker.setLatLng([lat, lng]);
                    document.getElementById('latitud').value = lat;
                    document.getElementById('longitud').value = lng;
                }
            })
            .catch(err => console.error("Geocoding address error", err));
    }

    // Asegurarse de inicializar cuando el DOM cargue
    document.addEventListener("DOMContentLoaded", function() {
        initMap();
    });

    function toggleDocLength() {
        const type = document.getElementById('tipo_doc').value;
        const input = document.getElementById('nro_doc');
        if (type === 'DNI') {
            input.maxLength = 8;
            input.placeholder = "8 dígitos";
        } else {
            input.maxLength = 11;
            input.placeholder = "11 dígitos";
        }
    }

    async function updateCart(id, delta, btn) {
        let span = btn.parentElement.querySelector('span');
        let currentQty = parseInt(span.innerText);
        let newQty = currentQty + delta;
        if (newQty < 1) return;

        fetch('{{ route('cart.update') }}', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id: id, quantity: newQty })
        }).then(() => location.reload());
    }

    function removeFromCart(id) {
        Swal.fire({
            title: '¿Eliminar producto?',
            text: "Este producto se quitará de tu solicitud.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ route('cart.remove') }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ id: id })
                }).then(() => location.reload());
            }
        });
    }

    toggleDocLength();

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#10b981'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            confirmButtonColor: '#ef4444'
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Error de Validación',
            text: '{{ $errors->first() }}',
            confirmButtonColor: '#ef4444'
        });
    @endif
</script>
@endpush
