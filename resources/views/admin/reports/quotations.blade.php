@extends('layouts.admin')

@section('content')
<style>
/* ===== QUOTATIONS REPORT PREMIUM STYLE ===== */
.detail-header { display: flex; align-items: center; gap: 16px; margin-bottom: 28px; }
.back-btn { 
    width: 42px; height: 42px; border-radius: 12px; background: white; 
    border: 1.5px solid #e2e8f0; display: flex; align-items: center; 
    justify-content: center; color: #64748b; text-decoration: none; 
    flex-shrink: 0; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); 
    box-shadow: 0 2px 8px rgba(0,0,0,0.04); 
}
.back-btn:hover { border-color: #8b5cf6; color: #8b5cf6; transform: translateX(-3px); }

.status-tabs { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 22px; }
.status-tab { 
    padding: 10px 22px; border-radius: 50px; font-weight: 700; font-size: 0.85rem; 
    text-decoration: none; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); 
    border: 1.5px solid #e2e8f0; display: inline-flex; align-items: center; gap: 8px;
    background: white; color: #64748b;
}
.status-tab:hover { border-color: #cbd5e1; color: #1e293b; transform: translateY(-1px); }
.status-tab span { 
    background: rgba(15, 23, 42, 0.06); padding: 2px 8px; border-radius: 50px; 
    font-size: 0.72rem; font-weight: 800; color: #475569;
}
.status-tab.active span {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

.filter-bar { 
    background: white; border-radius: 20px; padding: 24px 28px; 
    margin-bottom: 24px; box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.03); 
    border: 1px solid #f1f5f9; 
}
.filter-group { display: flex; flex-direction: column; gap: 8px; }
.filter-group label { font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.8px; }
.filter-group input, .filter-group select { 
    padding: 10px 16px; border: 1.5px solid #e2e8f0; border-radius: 10px; 
    font-size: 0.88rem; color: #334155; outline: none; 
    transition: all 0.2s ease; background: #f8fafc; 
}
.filter-group input:focus, .filter-group select:focus { 
    border-color: #8b5cf6; background: white; 
    box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1); 
}
.filter-btn { 
    padding: 11px 24px; border-radius: 10px; border: none; cursor: pointer; 
    font-weight: 700; font-size: 0.88rem; transition: all 0.2s; 
    display: inline-flex; align-items: center; gap: 8px;
}
.filter-btn-primary { background: #0f172a; color: white; }
.filter-btn-primary:hover { background: #1e293b; transform: translateY(-1px); }
.filter-btn-clear { 
    background: #f1f5f9; color: #64748b; text-decoration: none; 
    display: inline-flex; align-items: center; padding: 11px 20px; 
    border-radius: 10px; font-weight: 700; font-size: 0.88rem; transition: all 0.2s;
}
.filter-btn-clear:hover { background: #e2e8f0; color: #334155; }

.stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 24px; }
.stat-chip { 
    background: white; border: 1px solid #f1f5f9; border-radius: 18px; 
    padding: 20px 24px; box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.03); 
    display: flex; align-items: center; gap: 18px;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}
.stat-chip:hover { transform: translateY(-3px); box-shadow: 0 12px 25px -5px rgba(15, 23, 42, 0.06); }
.stat-chip-icon {
    width: 46px; height: 46px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;
}
.stat-chip .sc-val { font-size: 1.75rem; font-weight: 800; font-family: 'Poppins', sans-serif; color: #0f172a; line-height: 1.1; }
.stat-chip .sc-lbl { font-size: 0.72rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; margin-top: 2px; }

.data-table { 
    background: white; border-radius: 20px; overflow: hidden; 
    box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.03); 
    border: 1px solid #f1f5f9; 
}
.data-table table { width: 100%; border-collapse: collapse; }
.data-table thead th { 
    padding: 18px 24px; font-size: 0.72rem; font-weight: 800; 
    color: #64748b; text-transform: uppercase; letter-spacing: 0.8px; 
    background: #f8fafc; border-bottom: 2px solid #f1f5f9; 
}
.data-table tbody td { padding: 16px 24px; border-bottom: 1px solid #f8fafc; font-size: 0.9rem; color: #334155; vertical-align: middle; }
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tbody tr:hover td { background: #fafcff; }

.q-status-badge {
    padding: 6px 14px; border-radius: 50px; font-size: 0.75rem; 
    font-weight: 800; display: inline-flex; align-items: center; gap: 6px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.02);
}
.q-status-badge.completado { background: #ecfdf5; color: #059669; }
.q-status-badge.pendiente { background: #fffbeb; color: #d97706; }
.q-status-badge.cancelado { background: #fef2f2; color: #dc2626; }

@media (max-width: 992px) {
    .stats-row { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
    .stats-row { grid-template-columns: 1fr; }
    .filter-bar { padding: 18px 20px; }
}
</style>

<div class="detail-header">
    <a href="{{ route('admin.reports.index') }}" class="back-btn"><i class="fas fa-arrow-left"></i></a>
    <div>
        <h2 class="page-title" style="margin: 0; font-size: 1.6rem; font-family: 'Poppins', sans-serif; font-weight: 800;"><i class="fas fa-file-invoice-dollar" style="color: #8b5cf6; margin-right: 8px;"></i>Reporte de Cotizaciones</h2>
        <p class="text-muted" style="margin: 3px 0 0; font-size: 0.9rem;">Gestión, análisis y auditoría integral de cotizaciones solicitadas.</p>
    </div>
</div>

<!-- Tabs de estado -->
<div class="status-tabs">
    <a href="{{ route('admin.reports.quotations') }}"
       class="status-tab {{ !$status ? 'active' : '' }}" 
       style="{{ !$status ? 'background: #0f172a; color: white; border-color: #0f172a;' : '' }}">
        <i class="fas fa-list"></i> Todas las solicitudes
        <span>{{ \App\Models\Quotation::count() }}</span>
    </a>
    <a href="{{ route('admin.reports.quotations', ['status' => 'completado']) }}"
       class="status-tab {{ $status == 'completado' ? 'active' : '' }}"
       style="{{ $status == 'completado' ? 'background: #059669; color: white; border-color: #059669;' : 'border-color: rgba(5, 150, 105, 0.25); color: #059669;' }}">
        <i class="fas fa-check-circle"></i> Completadas
        <span>{{ \App\Models\Quotation::where('estado','completado')->count() }}</span>
    </a>
    <a href="{{ route('admin.reports.quotations', ['status' => 'pendiente']) }}"
       class="status-tab {{ $status == 'pendiente' ? 'active' : '' }}"
       style="{{ $status == 'pendiente' ? 'background: #d97706; color: white; border-color: #d97706;' : 'border-color: rgba(217, 119, 6, 0.25); color: #d97706;' }}">
        <i class="fas fa-clock"></i> Pendientes
        <span>{{ \App\Models\Quotation::where('estado','pendiente')->count() }}</span>
    </a>
    <a href="{{ route('admin.reports.quotations', ['status' => 'cancelado']) }}"
       class="status-tab {{ $status == 'cancelado' ? 'active' : '' }}"
       style="{{ $status == 'cancelado' ? 'background: #dc2626; color: white; border-color: #dc2626;' : 'border-color: rgba(220, 38, 38, 0.25); color: #dc2626;' }}">
        <i class="fas fa-times-circle"></i> Canceladas
        <span>{{ \App\Models\Quotation::where('estado','cancelado')->count() }}</span>
    </a>
</div>

<!-- Filtros adicionales -->
<div class="filter-bar">
    <form method="GET" action="{{ route('admin.reports.quotations') }}" style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap; width: 100%;">
        @if($status)<input type="hidden" name="status" value="{{ $status }}">@endif
        <div class="filter-group" style="flex: 1; min-width: 220px;">
            <label>Buscar cliente</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre, apellido o correo...">
        </div>
        <div class="filter-group" style="min-width: 140px;">
            <label>Fecha desde</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}">
        </div>
        <div class="filter-group" style="min-width: 140px;">
            <label>Fecha hasta</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}">
        </div>
        <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
            <button type="submit" class="filter-btn filter-btn-primary"><i class="fas fa-filter"></i> Filtrar</button>
            @if(request()->anyFilled(['search','date_from','date_to']))
                <a href="{{ route('admin.reports.quotations', $status ? ['status' => $status] : []) }}" class="filter-btn-clear"><i class="fas fa-times"></i> Limpiar</a>
            @endif
        </div>
    </form>
</div>

<!-- Stats rápidas -->
<div class="stats-row">
    <div class="stat-chip" style="border-left: 4px solid #3b82f6;">
        <div class="stat-chip-icon" style="background: #eff6ff; color: #3b82f6;"><i class="fas fa-file-invoice"></i></div>
        <div>
            <div class="sc-val" style="color: #3b82f6;">{{ $quotations->total() }}</div>
            <div class="sc-lbl">Cotizaciones Encontradas</div>
        </div>
    </div>
    <div class="stat-chip" style="border-left: 4px solid #10b981;">
        <div class="stat-chip-icon" style="background: #ecfdf5; color: #10b981;"><i class="fas fa-money-bill-wave"></i></div>
        <div>
            <div class="sc-val" style="color: #10b981;">S/ {{ number_format($totalFiltrado, 2) }}</div>
            <div class="sc-lbl">Importe Filtrado</div>
        </div>
    </div>
    @if(!$status)
        <div class="stat-chip" style="border-left: 4px solid #f59e0b;">
            <div class="stat-chip-icon" style="background: #fffbeb; color: #f59e0b;"><i class="fas fa-hourglass-half"></i></div>
            <div>
                <div class="sc-val" style="color: #d97706;">{{ \App\Models\Quotation::where('estado','pendiente')->count() }}</div>
                <div class="sc-lbl">Pendientes en Cola</div>
            </div>
        </div>
    @else
        <div class="stat-chip" style="border-left: 4px solid #8b5cf6;">
            <div class="stat-chip-icon" style="background: #f5f3ff; color: #8b5cf6;"><i class="fas fa-chart-line"></i></div>
            <div>
                <div class="sc-val" style="color: #8b5cf6;">{{ round($quotations->total() > 0 ? ($quotations->count() / \App\Models\Quotation::count() * 100) : 0) }}%</div>
                <div class="sc-lbl">% Del Total General</div>
            </div>
        </div>
    @endif
</div>

<!-- Tabla -->
<div class="data-table">
    <table>
        <thead>
            <tr>
                <th style="width: 130px;">Código Pedido</th>
                <th style="width: 140px;">Fecha Solicitada</th>
                <th>Cliente / Razón Social</th>
                <th>Correo de Contacto</th>
                <th style="text-align: center; width: 150px;">Estado</th>
                <th style="text-align: right; width: 160px;">Total Cotizado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($quotations as $q)
                <tr>
                    <td>
                        <span style="font-family: monospace; background: #f8fafc; border: 1px solid #e2e8f0; padding: 4px 10px; border-radius: 8px; font-weight: 800; color: #475569; font-size: 0.82rem; display: inline-block;">
                            <i class="fas fa-hashtag" style="font-size: 0.72rem; opacity: 0.6;"></i> {{ str_pad($q->id, 5, '0', STR_PAD_LEFT) }}
                        </span>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: #334155; font-size: 0.9rem;">{{ $q->created_at->format('d/m/Y') }}</div>
                        <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 2px; font-weight: 500;"><i class="far fa-clock" style="margin-right: 2px;"></i> {{ $q->created_at->format('H:i') }} hrs</div>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: #1e293b; font-size: 0.95rem;">{{ $q->nombre }} {{ $q->apellidos }}</div>
                        @if($q->numero_documento)
                            <div style="font-size: 0.73rem; color: #94a3b8; margin-top: 3px; font-weight: 600;">
                                <i class="far fa-id-card" style="margin-right: 2px;"></i> {{ $q->tipo_documento }}: {{ $q->numero_documento }}
                            </div>
                        @endif
                    </td>
                    <td>
                        <span style="font-size: 0.88rem; color: #475569; font-weight: 600;">{{ $q->email }}</span>
                    </td>
                    <td style="text-align: center;">
                        @if($q->estado == 'completado')
                            <span class="q-status-badge completado">
                                <i class="fas fa-check-circle"></i> COMPLETADO
                            </span>
                        @elseif($q->estado == 'pendiente')
                            <span class="q-status-badge pendiente">
                                <i class="fas fa-hourglass-half"></i> PENDIENTE
                            </span>
                        @else
                            <span class="q-status-badge cancelado">
                                <i class="fas fa-ban"></i> CANCELADO
                            </span>
                        @endif
                    </td>
                    <td style="text-align: right; font-weight: 800; color: #0f172a; font-size: 1rem; font-family: 'Poppins', sans-serif;">
                        S/ {{ number_format($q->total, 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 70px 20px; color: #94a3b8;">
                        <i class="fas fa-file-invoice-dollar" style="font-size: 3rem; margin-bottom: 16px; opacity: 0.2; display: block; margin-left: auto; margin-right: auto;"></i>
                        <span style="font-weight: 600; font-size: 0.95rem;">No se encontraron solicitudes de cotización con los filtros aplicados.</span>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="padding: 24px; display: flex; justify-content: center; background: #f8fafc; border-top: 1px solid #f1f5f9;">
        {{ $quotations->appends(request()->query())->links('partials.pagination') }}
    </div>
</div>
@endsection
