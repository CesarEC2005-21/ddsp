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
            
            document.getElementById('detail-id').innerText = `#${String(id).padStart(6, '0')}`;
            
            let itemsHtml = `
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-bottom: 20px;">
                    <a href="/admin/quotations/${id}/pdf" class="btn" style="background: #ef4444; color: white; font-size: 0.85rem;">
                        <i class="fas fa-file-pdf"></i> Exportar PDF
                    </a>
                    <a href="/admin/quotations/${id}/excel" class="btn" style="background: #10b981; color: white; font-size: 0.85rem;">
                        <i class="fas fa-file-excel"></i> Exportar Excel
                    </a>
                </div>

                <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 25px; margin-bottom: 30px;">
                    <div style="background: #f8fafc; padding: 25px; border-radius: 15px; border: 1px solid #e2e8f0;">
                        <h4 style="color: #1e293b; margin-bottom: 15px; font-size: 1rem; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-user-circle" style="color: var(--primary-color);"></i> Información del Cliente
                        </h4>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div>
                                <label style="font-size: 0.75rem; color: #64748b; display: block; text-transform: uppercase; font-weight: 600;">Nombre Completo</label>
                                <span style="font-weight: 600; color: #1e293b;">${data.nombre} ${data.apellidos}</span>
                            </div>
                            <div>
                                <label style="font-size: 0.75rem; color: #64748b; display: block; text-transform: uppercase; font-weight: 600;">${data.tipo_documento}</label>
                                <span style="font-weight: 600; color: #1e293b;">${data.numero_documento}</span>
                            </div>
                            <div>
                                <label style="font-size: 0.75rem; color: #64748b; display: block; text-transform: uppercase; font-weight: 600;">Teléfono</label>
                                <span style="font-weight: 600; color: #1e293b;">${data.telefono}</span>
                            </div>
                            <div>
                                <label style="font-size: 0.75rem; color: #64748b; display: block; text-transform: uppercase; font-weight: 600;">Ciudad</label>
                                <span style="font-weight: 600; color: #1e293b;">${data.ciudad}</span>
                            </div>
                        </div>
                    </div>
                    <div style="background: #fff8e1; padding: 25px; border-radius: 15px; border: 1px solid #fef3c7;">
                        <h4 style="color: #92400e; margin-bottom: 15px; font-size: 1rem; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-sticky-note"></i> Observaciones
                        </h4>
                        <p style="font-size: 0.9rem; color: #92400e; font-style: italic; margin: 0;">"${data.observaciones || 'Sin observaciones por parte del cliente.'}"</p>
                    </div>
                </div>

                <div class="card" style="padding: 0; overflow: hidden; border: 1px solid #e2e8f0;">
                    <table class="admin-table" style="margin: 0;">
                        <thead style="background: #f8fafc;">
                            <tr>
                                <th style="padding: 15px 25px;">Producto / Descripción</th>
                                <th style="text-align: center;">Cantidad</th>
                                <th style="text-align: right;">Precio Unit.</th>
                                <th style="text-align: right; padding-right: 25px;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data.items.map(item => `
                                <tr>
                                    <td style="padding: 15px 25px;">
                                        <div style="font-weight: 600; color: #1e293b;">${item.product.nombre}</div>
                                        <div style="font-size: 0.75rem; color: #64748b;">Código: ${item.product.codigo}</div>
                                    </td>
                                    <td style="text-align: center; font-weight: 500;">${item.cantidad}</td>
                                    <td style="text-align: right; color: #64748b;">S/ ${parseFloat(item.precio_unitario).toFixed(2)}</td>
                                    <td style="text-align: right; font-weight: 700; color: #1e293b; padding-right: 25px;">S/ ${(item.cantidad * item.precio_unitario).toFixed(2)}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                        <tfoot style="background: #f1fdf4;">
                            <tr>
                                <td colspan="3" style="text-align: right; padding: 20px; font-weight: 800; font-size: 1.1rem; color: #1e293b;">TOTAL ESTIMADO:</td>
                                <td style="text-align: right; padding: 20px 25px; font-weight: 800; font-size: 1.4rem; color: var(--primary-color);">S/ ${parseFloat(data.total).toFixed(2)}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            `;
            
            document.getElementById('detail-body').innerHTML = itemsHtml;
            openModal('detailModal');
        } catch (error) {
            console.error(error);
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
