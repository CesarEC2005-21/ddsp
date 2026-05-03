@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <h2 class="page-title">Gestión de Cotizaciones (Pedidos)</h2>
    </div>

    @if(session('success'))
        <div style="background: #D1FAE5; color: #065F46; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="card" style="margin-bottom: 20px; padding: 20px; background: white; border-radius: 12px; box-shadow: var(--shadow-sm);">
        <form action="{{ route('admin.quotations.index') }}" method="GET">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Buscar (ID, Nombre, Documento)</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Buscar...">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Estado</label>
                    <select name="estado" class="form-control">
                        <option value="">Todos</option>
                        <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="completado" {{ request('estado') === 'completado' ? 'selected' : '' }}>Completado</option>
                        <option value="cancelado" {{ request('estado') === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;"><i class="fas fa-search"></i> Filtrar</button>
                    @if(request()->anyFilled(['search', 'estado']))
                        <a href="{{ route('admin.quotations.index') }}" class="btn" style="background: #f1f5f9; color: #475569;" title="Limpiar"><i class="fas fa-times"></i></a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Documento</th>
                        <th>Ciudad</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($quotations as $q)
                    <tr>
                        <td>#{{ str_pad($q->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td style="font-weight: 700; color: #1e293b;">{{ $q->nombre }} {{ $q->apellidos }}</td>
                        <td><span style="font-size: 0.8rem; background: #f1f5f9; padding: 3px 8px; border-radius: 5px;">{{ $q->tipo_documento }}: {{ $q->numero_documento }}</span></td>
                        <td>{{ $q->ciudad }}</td>
                        <td style="font-weight: bold; color: var(--primary-green);">S/ {{ number_format($q->total, 2) }}</td>
                        <td>{{ $q->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @php
                                $statusColors = [
                                    'pendiente' => ['bg' => '#FEF3C7', 'text' => '#92400E'],
                                    'completado' => ['bg' => '#D1FAE5', 'text' => '#065F46'],
                                    'cancelado' => ['bg' => '#FEE2E2', 'text' => '#991B1B']
                                ];
                                $color = $statusColors[$q->estado] ?? ['bg' => '#f1f5f9', 'text' => '#64748b'];
                            @endphp
                            <span class="badge" style="background: {{ $color['bg'] }}; color: {{ $color['text'] }}; text-transform: uppercase;">{{ $q->estado }}</span>
                        </td>
                        <td>
                            <button onclick="viewDetails({{ $q->id }})" class="btn" style="background: #f3f4f6; color: #333; padding: 6px 10px;" title="Ver Detalle"><i class="fas fa-eye"></i></button>
                            <button onclick="openStatusModal({{ $q->id }}, '{{ $q->estado }}')" class="btn" style="background: #e0f2fe; color: #0369a1; padding: 6px 10px;" title="Cambiar Estado"><i class="fas fa-sync-alt"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="padding: 30px; text-align: center; color: #888;">No hay cotizaciones registradas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding: 20px; border-top: 1px solid #eee; display: flex; justify-content: center;">
            {{ $quotations->appends(request()->query())->links('partials.pagination') }}
        </div>
    </div>

    <!-- Modal Detalle Cotización -->
    <div id="detailModal" class="modal">
        <div class="modal-content" style="max-width: 900px;">
            <div class="modal-header">
                <h3><i class="fas fa-file-invoice"></i> Detalle de Cotización <span id="detail-id"></span></h3>
                <span class="close-modal" onclick="closeModal('detailModal')">&times;</span>
            </div>
            <div class="modal-body" id="detail-body">
                <!-- Content loaded via JS -->
            </div>
        </div>
    </div>

    <!-- Modal Cambio de Estado -->
    <div id="statusModal" class="modal">
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header">
                <h3>Actualizar Estado</h3>
                <span class="close-modal" onclick="closeModal('statusModal')">&times;</span>
            </div>
            <div class="modal-body">
                <form id="statusForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label class="form-label">Nuevo Estado</label>
                        <select name="estado" id="status-select" class="form-control">
                            <option value="pendiente">Pendiente</option>
                            <option value="completado">Completado</option>
                            <option value="cancelado">Cancelado</option>
                        </select>
                    </div>
                    <div style="margin-top: 20px; display: flex; justify-content: flex-end; gap: 10px;">
                        <button type="button" class="btn" style="background: #eee;" onclick="closeModal('statusModal')">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    async function viewDetails(id) {
        try {
            const response = await fetch(`/admin/quotations/${id}`);
            const data = await response.json();
            
            document.getElementById('detail-id').innerText = `#${String(id).padStart(5, '0')}`;
            
            let itemsHtml = `
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 30px; padding: 20px; background: #f8fafc; border-radius: 15px;">
                    <div>
                        <h4 style="color: var(--primary-green); margin-bottom: 10px;">Información del Cliente</h4>
                        <p><strong>Nombre:</strong> ${data.nombre} ${data.apellidos}</p>
                        <p><strong>${data.tipo_documento}:</strong> ${data.numero_documento}</p>
                        <p><strong>Teléfono:</strong> ${data.telefono}</p>
                        <p><strong>Email:</strong> ${data.email}</p>
                        <p><strong>Ciudad:</strong> ${data.ciudad}</p>
                    </div>
                    <div>
                        <h4 style="color: var(--primary-green); margin-bottom: 10px;">Observaciones</h4>
                        <p>${data.observaciones || 'Sin observaciones'}</p>
                    </div>
                </div>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unit.</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.items.map(item => `
                            <tr>
                                <td>${item.product.nombre}</td>
                                <td>${item.cantidad}</td>
                                <td>S/ ${parseFloat(item.precio_unitario).toFixed(2)}</td>
                                <td style="font-weight: bold;">S/ ${(item.cantidad * item.precio_unitario).toFixed(2)}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: right; font-weight: 800; font-size: 1.2rem;">TOTAL:</td>
                            <td style="font-weight: 800; font-size: 1.2rem; color: var(--primary-green);">S/ ${parseFloat(data.total).toFixed(2)}</td>
                        </tr>
                    </tfoot>
                </table>
            `;
            
            document.getElementById('detail-body').innerHTML = itemsHtml;
            openModal('detailModal');
        } catch (error) {
            alert('Error al cargar detalles');
        }
    }

    function openStatusModal(id, currentStatus) {
        const form = document.getElementById('statusForm');
        form.action = `/admin/quotations/${id}/status`;
        document.getElementById('status-select').value = currentStatus;
        openModal('statusModal');
    }
</script>
@endpush
