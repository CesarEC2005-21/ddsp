@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <h2 class="page-title"><i class="fas fa-sign-in-alt"></i> Registro de Accesos</h2>
        <p class="text-muted">Monitoreo de ingresos al sistema (Excluye rol de Ingeniería de Sistemas).</p>
    </div>

    <div class="card">
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Fecha de Ingreso</th>
                        <th>Hora Ingreso</th>
                        <th>Hora Salida</th>
                        <th>Duración</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td class="fw-bold">{{ \Carbon\Carbon::parse($log->login_at)->format('d/m/Y') }}</td>
                            <td class="text-success"><i class="fas fa-arrow-right"></i> {{ \Carbon\Carbon::parse($log->login_at)->format('h:i:s A') }}</td>
                            <td>
                                @if($log->logout_at)
                                    <span class="text-danger"><i class="fas fa-arrow-left"></i> {{ \Carbon\Carbon::parse($log->logout_at)->format('h:i:s A') }}</span>
                                @else
                                    <span class="badge badge-success">Activo Ahora</span>
                                @endif
                            </td>
                            <td>
                                @if($log->duration)
                                    <span class="badge badge-gray"><i class="far fa-clock"></i> {{ $log->duration }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="fw-bold">{{ $log->user->name ?? 'Desconocido' }}</td>
                            <td>
                                @if($log->user)
                                    <span class="badge badge-info">{{ strtoupper($log->user->role) }}</span>
                                @else
                                    <span class="badge badge-gray">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 30px;" class="text-muted">
                                <i class="fas fa-user-clock mb-2" style="font-size: 2rem;"></i><br>
                                No hay registros de acceso.
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
