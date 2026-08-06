@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <h2 class="page-title"><i class="fas fa-history"></i> Auditoría de Movimientos</h2>
        <p class="text-muted">Registro detallado de acciones importantes realizadas por los usuarios en la plataforma.</p>
    </div>

    <div class="card" style="margin-bottom: 20px; padding: 20px; background: white; border-radius: 12px; box-shadow: var(--shadow-sm);">
        <form action="{{ route('admin.security.audit') }}" method="GET">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; align-items: end;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Usuario</label>
                    <input type="text" name="usuario" class="form-control" value="{{ request('usuario') }}" placeholder="Nombre de usuario...">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Acción</label>
                    <select name="accion" class="form-control">
                        <option value="">Todas</option>
                        <option value="created_product" {{ request('accion') === 'created_product' ? 'selected' : '' }}>Nuevo Producto</option>
                        <option value="deleted_product" {{ request('accion') === 'deleted_product' ? 'selected' : '' }}>Producto Eliminado</option>
                        <option value="created_laboratory" {{ request('accion') === 'created_laboratory' ? 'selected' : '' }}>Nuevo Laboratorio</option>
                        <option value="deleted_laboratory" {{ request('accion') === 'deleted_laboratory' ? 'selected' : '' }}>Laboratorio Eliminado</option>
                        <option value="created_representative" {{ request('accion') === 'created_representative' ? 'selected' : '' }}>Nuevo Ejecutivo</option>
                        <option value="deleted_representative" {{ request('accion') === 'deleted_representative' ? 'selected' : '' }}>Ejecutivo Eliminado</option>
                        <option value="created_promotion" {{ request('accion') === 'created_promotion' ? 'selected' : '' }}>Nueva Promoción</option>
                        <option value="deleted_promotion" {{ request('accion') === 'deleted_promotion' ? 'selected' : '' }}>Promoción Eliminada</option>
                        <option value="downloaded_backup" {{ request('accion') === 'downloaded_backup' ? 'selected' : '' }}>Descarga Backup</option>
                        <option value="cancelled_quotation" {{ request('accion') === 'cancelled_quotation' ? 'selected' : '' }}>Cotización Cancelada</option>
                        <option value="updated_settings" {{ request('accion') === 'updated_settings' ? 'selected' : '' }}>Ajuste Sistema</option>
                        <option value="general_import" {{ request('accion') === 'general_import' ? 'selected' : '' }}>Importación General</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Fecha Desde</label>
                    <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Fecha Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
                </div>
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;"><i class="fas fa-search"></i> Filtrar</button>
                    @if(request()->anyFilled(['usuario', 'accion', 'fecha_desde', 'fecha_hasta']))
                        <a href="{{ route('admin.security.audit') }}" class="btn" style="background: #f1f5f9; color: #475569;" title="Limpiar"><i class="fas fa-times"></i></a>
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
                        <th>Fecha y Hora</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Acción</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('d/m/Y h:i A') }}</td>
                            <td class="fw-bold">{{ $log->user->name ?? 'Sistema' }}</td>
                            <td>
                                @if($log->user)
                                    <span class="badge badge-gray">{{ strtoupper($log->user->role) }}</span>
                                @else
                                    <span class="badge badge-gray">N/A</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $actionBadges = [
                                        'created_product' => ['color' => 'badge-success', 'icon' => 'fa-box'],
                                        'deleted_product' => ['color' => 'badge-danger', 'icon' => 'fa-trash'],
                                        'created_laboratory' => ['color' => 'badge-success', 'icon' => 'fa-flask'],
                                        'deleted_laboratory' => ['color' => 'badge-danger', 'icon' => 'fa-flask'],
                                        'created_representative' => ['color' => 'badge-success', 'icon' => 'fa-user-tie'],
                                        'deleted_representative' => ['color' => 'badge-danger', 'icon' => 'fa-user-tie'],
                                        'created_executive' => ['color' => 'badge-success', 'icon' => 'fa-user-tie'],
                                        'deleted_executive' => ['color' => 'badge-danger', 'icon' => 'fa-user-tie'],
                                        'created_promotion' => ['color' => 'badge-success', 'icon' => 'fa-bullhorn'],
                                        'deleted_promotion' => ['color' => 'badge-danger', 'icon' => 'fa-bullhorn'],
                                        'downloaded_backup' => ['color' => 'badge-info', 'icon' => 'fa-download'],
                                        'cancelled_quotation' => ['color' => 'badge-danger', 'icon' => 'fa-ban'],
                                        'updated_settings' => ['color' => 'badge-warning', 'icon' => 'fa-cog'],
                                        'general_import' => ['color' => 'badge-primary', 'icon' => 'fa-file-excel']
                                    ];
                                    $badge = $actionBadges[$log->action] ?? ['color' => 'badge-gray', 'icon' => 'fa-info-circle'];
                                    
                                    $actionNames = [
                                        'created_product' => 'Nuevo Producto',
                                        'deleted_product' => 'Producto Eliminado',
                                        'created_laboratory' => 'Nuevo Laboratorio',
                                        'deleted_laboratory' => 'Laboratorio Eliminado',
                                        'created_representative' => 'Nuevo Ejecutivo',
                                        'deleted_representative' => 'Ejecutivo Eliminado',
                                        'created_executive' => 'Nuevo Ejecutivo',
                                        'deleted_executive' => 'Ejecutivo Eliminado',
                                        'created_promotion' => 'Nueva Promoción',
                                        'deleted_promotion' => 'Promoción Eliminada',
                                        'downloaded_backup' => 'Descarga Backup',
                                        'cancelled_quotation' => 'Cotización Cancelada',
                                        'updated_settings' => 'Ajuste Sistema',
                                        'general_import' => 'Importación General'
                                    ];
                                    $actionName = $actionNames[$log->action] ?? $log->action;
                                @endphp
                                <span class="badge {{ $badge['color'] }}">
                                    <i class="fas {{ $badge['icon'] }}"></i> {{ $actionName }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted d-block">{{ $log->description }}</span>
                                @if($log->action === 'general_import' && !empty($log->details))
                                    <button class="btn btn-sm" style="background: #e2e8f0; color: #475569; font-size: 0.75rem; margin-top: 5px; padding: 3px 8px;" onclick="showImportDetails({{ $log->id }})">
                                        <i class="fas fa-eye"></i> Ver Detalle
                                    </button>
                                    <div id="import-details-data-{{ $log->id }}" style="display: none;">
                                        {!! $log->details !!}
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 30px;" class="text-muted">
                                <i class="fas fa-folder-open mb-2" style="font-size: 2rem;"></i><br>
                                No hay registros de auditoría aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3" style="display: flex; justify-content: center;">
            {{ $logs->links('partials.pagination') }}
        </div>
    </div>

    <!-- Modal for Import Details -->
    <div id="importDetailsModal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 12px; width: 90%; max-width: 600px; max-height: 80vh; display: flex; flex-direction: column; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
            <div style="padding: 20px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-size: 1.25rem;"><i class="fas fa-file-excel text-primary"></i> Detalle de Importación</h3>
                <button onclick="document.getElementById('importDetailsModal').style.display='none'" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #64748b;">&times;</button>
            </div>
            <div id="importDetailsContent" style="padding: 20px; overflow-y: auto; flex: 1;">
                <!-- Content will be injected here via JS -->
            </div>
        </div>
    </div>

    <script>
        function showImportDetails(logId) {
            const rawData = document.getElementById('import-details-data-' + logId).innerHTML;
            let data = null;
            try {
                data = JSON.parse(rawData);
            } catch(e) {
                console.error("Error parsing details JSON", e);
                return;
            }

            let html = '';
            
            if (data.new_laboratories && data.new_laboratories.length > 0) {
                html += '<h5 style="margin-top:0; color:#10b981;"><i class="fas fa-flask"></i> Nuevos Laboratorios (' + data.new_laboratories.length + ')</h5>';
                html += '<ul style="font-size:0.9rem; color:#475569; padding-left: 20px;">';
                data.new_laboratories.forEach(lab => {
                    html += '<li>' + lab + '</li>';
                });
                html += '</ul>';
            }

            if (data.new_products && data.new_products.length > 0) {
                html += '<h5 style="margin-top:20px; color:#3b82f6;"><i class="fas fa-box"></i> Nuevos Productos (' + data.new_products.length + ')</h5>';
                html += '<ul style="font-size:0.9rem; color:#475569; padding-left: 20px;">';
                data.new_products.forEach(prod => {
                    html += '<li>' + (typeof prod === 'string' ? prod : (prod.codigo + ' - ' + prod.nombre)) + '</li>';
                });
                html += '</ul>';
            }

            if (data.updated_products && data.updated_products.length > 0) {
                html += '<h5 style="margin-top:20px; color:#f59e0b;"><i class="fas fa-sync"></i> Productos Actualizados (' + data.updated_products.length + ')</h5>';
                html += '<ul style="font-size:0.9rem; color:#475569; padding-left: 20px;">';
                data.updated_products.forEach(prod => {
                    html += '<li>' + (typeof prod === 'string' ? prod : (prod.codigo + ' - ' + prod.nombre)) + '</li>';
                });
                html += '</ul>';
            }
            
            if (!data.new_laboratories?.length && !data.new_products?.length && !data.updated_products?.length) {
                html = '<div class="alert alert-warning">No hubo cambios relevantes en esta importación.</div>';
            }

            document.getElementById('importDetailsContent').innerHTML = html;
            document.getElementById('importDetailsModal').style.display = 'flex';
        }
    </script>
@endsection
