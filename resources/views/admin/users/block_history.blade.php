@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div style="display: flex; align-items: center; gap: 15px;">
            <a href="{{ route('admin.users.index') }}" class="btn" style="background: #1e293b; color: white;"><i class="fas fa-arrow-left"></i> Volver</a>
            <div>
                <h2 class="page-title"><i class="fas fa-history"></i> Historial de Bloqueos</h2>
                <p class="text-muted">Usuario: <strong>{{ $user->name }}</strong> ({{ $user->email }})</p>
            </div>
        </div>
    </div>

    <div class="card" style="margin-bottom: 20px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; padding: 20px;">
            <div>
                <span class="text-muted" style="font-size: 0.8rem; display: block; margin-bottom: 5px;">Nombre Completo</span>
                <span class="fw-bold">{{ $user->name }}</span>
            </div>
            <div>
                <span class="text-muted" style="font-size: 0.8rem; display: block; margin-bottom: 5px;">Rol</span>
                <span class="badge badge-gray">{{ strtoupper($user->role) }}</span>
            </div>
            <div>
                <span class="text-muted" style="font-size: 0.8rem; display: block; margin-bottom: 5px;">Estado Actual</span>
                @if($user->estado)
                    <span class="badge badge-success">ACTIVO</span>
                @else
                    <span class="badge badge-danger">BLOQUEADO</span>
                @endif
            </div>
        </div>
    </div>

    <div class="card">
        <div style="padding: 20px; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-list-ul" style="color: var(--primary);"></i>
            <h3 style="font-size: 1.1rem;">Registro de Acciones</h3>
        </div>
        
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Fecha y Hora</th>
                        <th>Acción</th>
                        <th>Realizado por</th>
                        <th>Razón / Motivo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($user->blockHistories as $history)
                    <tr>
                        <td>{{ $history->created_at->format('d/m/Y H:i:s') }}</td>
                        <td>
                            @if($history->action === 'blocked')
                                <span class="badge badge-danger"><i class="fas fa-lock"></i> BLOQUEO</span>
                            @else
                                <span class="badge badge-success"><i class="fas fa-unlock"></i> DESBLOQUEO</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold">{{ $history->admin->name ?? 'Sistema' }}</div>
                            <div style="font-size: 0.75rem; color: #666;">{{ strtoupper($history->admin->role ?? 'N/A') }}</div>
                        </td>
                        <td style="color: #4b5563;">
                            {{ $history->reason }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="padding: 50px; text-align: center; color: #888;">
                            <div style="margin-bottom: 10px;"><i class="fas fa-inbox" style="font-size: 2rem; opacity: 0.3;"></i></div>
                            No hay registros de bloqueos/desbloqueos para este usuario
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
