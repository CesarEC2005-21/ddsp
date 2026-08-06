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
                                        'updated_settings' => ['color' => 'badge-warning', 'icon' => 'fa-cog']
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
                                        'updated_settings' => 'Ajuste Sistema'
                                    ];
                                    $actionName = $actionNames[$log->action] ?? $log->action;
                                @endphp
                                <span class="badge {{ $badge['color'] }}">
                                    <i class="fas {{ $badge['icon'] }}"></i> {{ $actionName }}
                                </span>
                            </td>
                            <td class="text-muted">{{ $log->description }}</td>
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
@endsection
