@extends('layouts.landing')

@section('content')
<div style="background: #f1f5f9; padding: 60px 5%; min-height: 90vh;">
    <div style="max-width: 1200px; margin: 0 auto;">
        
        <!-- Pasos del Proceso -->
        <div style="display: flex; justify-content: center; margin-bottom: 50px; gap: 20px; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="width: 35px; height: 35px; background: var(--primary-green); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">1</span>
                <span style="font-weight: 700; color: #1e293b;">Carrito</span>
            </div>
            <div style="width: 50px; height: 2px; background: #cbd5e1; align-self: center;"></div>
            <div style="display: flex; align-items: center; gap: 10px; opacity: 0.5;">
                <span style="width: 35px; height: 35px; background: #94a3b8; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">2</span>
                <span style="font-weight: 600; color: #1e293b;">Información</span>
            </div>
            <div style="width: 50px; height: 2px; background: #cbd5e1; align-self: center;"></div>
            <div style="display: flex; align-items: center; gap: 10px; opacity: 0.5;">
                <span style="width: 35px; height: 35px; background: #94a3b8; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">3</span>
                <span style="font-weight: 600; color: #1e293b;">Confirmación</span>
            </div>
        </div>

        @if(count($cart) > 0)
        <div style="display: grid; grid-template-columns: 1.6fr 1fr; gap: 30px; align-items: flex-start;">
            
            <!-- Listado de Productos -->
            <div style="background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.04); padding: 30px; border: 1px solid #e2e8f0;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 1px solid #f1f5f9; padding-bottom: 15px;">
                    <h2 style="font-size: 1.4rem; color: #1e293b; margin: 0;">Resumen de solicitud ({{ count($cart) }})</h2>
                    <a href="{{ route('cart.clear') }}" style="color: #ef4444; text-decoration: none; font-size: 0.85rem; font-weight: 600;"><i class="fas fa-trash-alt"></i> Vaciar Carrito</a>
                </div>

                @php $total = 0; @endphp
                @foreach($cart as $id => $details)
                    @php $total += $details['price'] * $details['quantity']; @endphp
                    <div style="display: flex; gap: 20px; padding: 20px 0; border-bottom: 1px solid #f8fafc; align-items: center;">
                        <div style="width: 80px; height: 80px; background: #f8fafc; border-radius: 12px; display: flex; align-items: center; justify-content: center; border: 1px solid #f1f5f9; padding: 5px;">
                            @if($details['image'])
                                <img src="{{ asset('storage/' . $details['image']) }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                            @else
                                <i class="fas fa-pills" style="font-size: 1.5rem; color: #cbd5e1;"></i>
                            @endif
                        </div>
                        <div style="flex-grow: 1;">
                            <div style="display: flex; justify-content: space-between;">
                                <h4 style="margin: 0; color: #1e293b; font-size: 1rem; font-weight: 700;">{{ $details['name'] }}</h4>
                                <button onclick="removeFromCart({{ $id }})" style="color: #cbd5e1; border: none; background: transparent; cursor: pointer; transition: 0.3s;" onmouseover="this.style.color='#ef4444'"><i class="fas fa-times"></i></button>
                            </div>
                            <p style="margin: 4px 0; font-size: 0.8rem; color: #94a3b8; font-family: monospace;">Ref: {{ $details['code'] }}</p>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
                                <div style="display: flex; align-items: center; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; padding: 4px;">
                                    <button onclick="updateCart({{ $id }}, -1)" style="border: none; background: transparent; padding: 4px 10px; cursor: pointer; color: #64748b;">-</button>
                                    <span style="width: 25px; text-align: center; font-weight: 700; color: #1e293b; font-size: 0.9rem;">{{ $details['quantity'] }}</span>
                                    <button onclick="updateCart({{ $id }}, 1)" style="border: none; background: transparent; padding: 4px 10px; cursor: pointer; color: #64748b;">+</button>
                                </div>
                                <div style="text-align: right;">
                                    <span style="font-size: 0.8rem; color: #94a3b8; display: block;">S/ {{ number_format($details['price'], 2) }} c/u</span>
                                    <span style="font-weight: 800; color: #1e293b; font-size: 1.1rem;">S/ {{ number_format($details['price'] * $details['quantity'], 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div style="margin-top: 30px;">
                    <a href="{{ route('products') }}" style="display: inline-flex; align-items: center; gap: 8px; color: #64748b; text-decoration: none; font-weight: 600; font-size: 0.9rem;">
                        <i class="fas fa-chevron-left"></i> Continuar navegando
                    </a>
                </div>
            </div>

            <!-- Formulario de Cotización -->
            <div style="background: white; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.08); padding: 40px; border: 1px solid #e2e8f0; position: sticky; top: 100px;">
                <h3 style="margin-bottom: 25px; color: #1e293b; font-size: 1.4rem; font-weight: 800; text-align: center;">Datos de Contacto</h3>
                
                <form action="{{ route('quotation.store') }}" method="POST" id="quotationForm">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                        <div class="form-group">
                            <label style="display: block; font-size: 0.8rem; margin-bottom: 6px; color: #64748b; font-weight: 600;">Nombres</label>
                            <input type="text" name="nombre" class="form-control" style="width: 100%; padding: 12px; border-radius: 10px; background: #f8fafc;" required>
                        </div>
                        <div class="form-group">
                            <label style="display: block; font-size: 0.8rem; margin-bottom: 6px; color: #64748b; font-weight: 600;">Apellidos</label>
                            <input type="text" name="apellidos" class="form-control" style="width: 100%; padding: 12px; border-radius: 10px; background: #f8fafc;" required>
                        </div>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label style="display: block; font-size: 0.8rem; margin-bottom: 6px; color: #64748b; font-weight: 600;">WhatsApp / Celular (9 dígitos)</label>
                        <input type="tel" name="telefono" pattern="[0-9]{9}" maxlength="9" placeholder="912345678" class="form-control" style="width: 100%; padding: 12px; border-radius: 10px; background: #f8fafc;" required>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 15px; margin-bottom: 15px;">
                        <div class="form-group">
                            <label style="display: block; font-size: 0.8rem; margin-bottom: 6px; color: #64748b; font-weight: 600;">Tipo Doc.</label>
                            <select name="tipo_documento" id="tipo_doc" class="form-control" style="width: 100%; padding: 12px; border-radius: 10px; background: #f8fafc;" onchange="toggleDocLength()">
                                <option value="DNI">DNI</option>
                                <option value="RUC">RUC</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label style="display: block; font-size: 0.8rem; margin-bottom: 6px; color: #64748b; font-weight: 600;">Nro Documento</label>
                            <input type="text" name="numero_documento" id="nro_doc" maxlength="8" class="form-control" style="width: 100%; padding: 12px; border-radius: 10px; background: #f8fafc;" required>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                        <div class="form-group">
                            <label style="display: block; font-size: 0.8rem; margin-bottom: 6px; color: #64748b; font-weight: 600;">Ciudad</label>
                            <input type="text" name="ciudad" class="form-control" style="width: 100%; padding: 12px; border-radius: 10px; background: #f8fafc;" required>
                        </div>
                        <div class="form-group">
                            <label style="display: block; font-size: 0.8rem; margin-bottom: 6px; color: #64748b; font-weight: 600;">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control" style="width: 100%; padding: 12px; border-radius: 10px; background: #f8fafc;" required>
                        </div>
                    </div>

                    <div style="margin-bottom: 30px;">
                        <label style="display: block; font-size: 0.8rem; margin-bottom: 6px; color: #64748b; font-weight: 600;">Observaciones (Opcional)</label>
                        <textarea name="observaciones" class="form-control" style="width: 100%; height: 80px; padding: 12px; border-radius: 10px; background: #f8fafc; resize: none;" placeholder="Ej. Horario de atención o referencia..."></textarea>
                    </div>

                    <div style="background: #f1f5f9; padding: 25px; border-radius: 15px; margin-bottom: 30px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <span style="color: #64748b; font-weight: 600;">Subtotal estimado:</span>
                            <span style="color: #1e293b; font-weight: 700;">S/ {{ number_format($total, 2) }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #cbd5e1; padding-top: 15px;">
                            <span style="color: #1e293b; font-weight: 800; font-size: 1.1rem;">TOTAL:</span>
                            <span style="font-size: 2rem; font-weight: 900; color: var(--primary-green);">S/ {{ number_format($total, 2) }}</span>
                        </div>
                        <input type="hidden" name="total" value="{{ $total }}">
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 18px; font-size: 1.1rem; font-weight: 800; border-radius: 12px; box-shadow: 0 10px 20px rgba(46, 125, 50, 0.2); border: none; cursor: pointer; transition: 0.3s;">
                        CONFIRMAR SOLICITUD DE COTIZACIÓN
                    </button>
                    <p style="text-align: center; font-size: 0.75rem; color: #94a3b8; margin-top: 15px;">
                        <i class="fas fa-lock"></i> Sus datos están protegidos y serán tratados con confidencialidad.
                    </p>
                </form>
            </div>
        </div>
        @else
        <div style="background: white; padding: 120px 5%; border-radius: 30px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.04); border: 1px solid #e2e8f0;">
            <div style="width: 120px; height: 120px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px;">
                <i class="fas fa-shopping-basket" style="font-size: 4rem; color: #cbd5e1;"></i>
            </div>
            <h2 style="color: #1e293b; font-size: 2rem; margin-bottom: 10px;">Tu carrito está esperando</h2>
            <p style="color: #64748b; margin-bottom: 40px; font-size: 1.1rem;">No has agregado productos a tu solicitud de cotización todavía.</p>
            <a href="{{ route('products') }}" class="btn btn-primary" style="padding: 18px 45px; font-size: 1.1rem; border-radius: 15px;">Explorar Productos</a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleDocLength() {
        const type = document.getElementById('tipo_doc').value;
        const input = document.getElementById('nro_doc');
        if (type === 'DNI') {
            input.maxLength = 8;
            input.placeholder = "DNI de 8 dígitos";
        } else {
            input.maxLength = 11;
            input.placeholder = "RUC de 11 dígitos";
        }
    }

    function updateCart(id, delta) {
        let currentQty = parseInt(event.target.parentElement.querySelector('span').innerText);
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
        fetch('{{ route('cart.remove') }}', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id: id })
        }).then(() => location.reload());
    }

    // Inicializar placeholders
    toggleDocLength();
</script>
@endpush
