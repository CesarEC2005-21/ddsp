@extends('layouts.admin')

@section('content')
<div class="page-header" style="margin-bottom: 30px;">
    <h2 class="page-title" style="font-size: 2rem; color: #1e293b; margin-bottom: 10px;">
        <i class="fas fa-shield-alt" style="color: #3b82f6;"></i> Control de Accesos
    </h2>
    <p style="color: #64748b;">Monitoreo en tiempo real de sesiones y actividad de usuarios (Excluye Auditoría de Ingeniería).</p>
</div>

<div class="card" style="border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.04); overflow: hidden;">
    <div class="table-container">
        <table class="admin-table" style="border-collapse: separate; border-spacing: 0;">
            <thead style="background: #f8fafc;">
                <tr>
                    <th style="padding: 20px; color: #475569; font-weight: 700; border-bottom: 2px solid #f1f5f9;">Fecha de Sesión</th>
                    <th style="padding: 20px; color: #475569; font-weight: 700; border-bottom: 2px solid #f1f5f9;">Usuario</th>
                    <th style="padding: 20px; color: #475569; font-weight: 700; border-bottom: 2px solid #f1f5f9;">Rol</th>
                    <th style="padding: 20px; color: #475569; font-weight: 700; border-bottom: 2px solid #f1f5f9;">Hora Ingreso</th>
                    <th style="padding: 20px; color: #475569; font-weight: 700; border-bottom: 2px solid #f1f5f9;">Hora Salida</th>
                    <th style="padding: 20px; color: #475569; font-weight: 700; border-bottom: 2px solid #f1f5f9; text-align: center;">Duración</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr style="transition: all 0.2s; cursor: default;">
                        <td style="padding: 20px; border-bottom: 1px solid #f1f5f9;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 40px; height: 40px; background: #eff6ff; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #3b82f6;">
                                    <i class="far fa-calendar-check"></i>
                                </div>
                                <span style="font-weight: 700; color: #1e293b;">{{ \Carbon\Carbon::parse($log->login_at)->format('d/m/Y') }}</span>
                            </div>
                        </td>
                        <td style="padding: 20px; border-bottom: 1px solid #f1f5f9;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 32px; height: 32px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; color: #64748b; font-weight: 800;">
                                    {{ strtoupper(substr($log->user->name ?? 'D', 0, 1)) }}
                                </div>
                                <span style="font-weight: 600; color: #334155;">{{ $log->user->name ?? 'Desconocido' }}</span>
                            </div>
                        </td>
                        <td style="padding: 20px; border-bottom: 1px solid #f1f5f9;">
                            <span style="background: #f1f5f9; color: #475569; padding: 4px 12px; border-radius: 50px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">
                                {{ $log->user->role ?? 'N/A' }}
                            </span>
                        </td>
                        <td style="padding: 20px; border-bottom: 1px solid #f1f5f9; color: #059669; font-weight: 600;">
                            <i class="fas fa-sign-in-alt" style="margin-right: 5px; opacity: 0.5;"></i> {{ \Carbon\Carbon::parse($log->login_at)->format('H:i:s') }}
                        </td>
                        <td style="padding: 20px; border-bottom: 1px solid #f1f5f9;">
                            @if($log->logout_at)
                                <span style="color: #dc2626; font-weight: 600;">
                                    <i class="fas fa-sign-out-alt" style="margin-right: 5px; opacity: 0.5;"></i> {{ \Carbon\Carbon::parse($log->logout_at)->format('H:i:s') }}
                                </span>
                            @else
                                <span style="display: inline-flex; align-items: center; gap: 6px; background: #ecfdf5; color: #059669; padding: 4px 12px; border-radius: 50px; font-size: 0.75rem; font-weight: 800; animation: pulse 2s infinite;">
                                    <span style="width: 6px; height: 6px; background: #059669; border-radius: 50%;"></span> EN LÍNEA
                                </span>
                            @endif
                        </td>
                        <td style="padding: 20px; border-bottom: 1px solid #f1f5f9; text-align: center;">
                            @if($log->duration)
                                <span style="font-family: 'Courier New', Courier, monospace; background: #1e293b; color: #f8fafc; padding: 5px 12px; border-radius: 8px; font-weight: 800; font-size: 0.9rem; letter-spacing: 1px;">
                                    {{ $log->duration }}
                                </span>
                            @else
                                <span style="color: #cbd5e1;">--:--:--</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 60px; text-align: center; color: #94a3b8;">
                            <i class="fas fa-user-shield" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.2;"></i>
                            <p style="margin: 0; font-size: 1.1rem; font-weight: 600;">No hay registros de actividad</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding: 20px; display: flex; justify-content: center; background: #fcfcfc; border-top: 1px solid #f1f5f9;">
        {{ $logs->links('partials.pagination') }}
    </div>
</div>

<style>
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.6; }
        100% { opacity: 1; }
    }
    .admin-table tbody tr:hover {
        background-color: #f8fafc !important;
    }
</style>
@endsection
