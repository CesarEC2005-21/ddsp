@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <h2 class="page-title"><i class="fas fa-history"></i> Auditoría de Movimientos</h2>
        <p class="text-muted">Registro detallado de acciones importantes realizadas por los usuarios en la plataforma.</p>
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
                                        'created_executive' => ['color' => 'badge-info', 'icon' => 'fa-user-tie'],
                                        'cancelled_quotation' => ['color' => 'badge-danger', 'icon' => 'fa-ban'],
                                        'updated_settings' => ['color' => 'badge-warning', 'icon' => 'fa-cog']
                                    ];
                                    $badge = $actionBadges[$log->action] ?? ['color' => 'badge-gray', 'icon' => 'fa-info-circle'];
                                    
                                    $actionNames = [
                                        'created_product' => 'Nuevo Producto',
                                        'deleted_product' => 'Producto Eliminado',
                                        'created_executive' => 'Nuevo Ejecutivo',
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
