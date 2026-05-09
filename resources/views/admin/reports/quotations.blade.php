@extends('layouts.admin')

@section('content')
<style>
.detail-header { display: flex; align-items: center; gap: 16px; margin-bottom: 28px; }
.back-btn { width: 42px; height: 42px; border-radius: 12px; background: white; border: 1.5px solid #e2e8f0; display: flex; align-items: center; justify-content: center; color: #64748b; text-decoration: none; flex-shrink: 0; transition: all 0.2s; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
.back-btn:hover { border-color: var(--primary-color); color: var(--primary-color); }
.status-tabs { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px; }
.status-tab { padding: 10px 22px; border-radius: 50px; font-weight: 700; font-size: 0.85rem; text-decoration: none; transition: all 0.2s; border: 1.5px solid transparent; display: inline-flex; align-items: center; gap: 8px; }
.filter-bar { background: white; border-radius: 16px; padding: 20px 24px; margin-bottom: 20px; box-shadow: 0 2px 15px rgba(0,0,0,0.04); border: 1px solid #f1f5f9; display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap; }
.filter-group { display: flex; flex-direction: column; gap: 6px; }
.filter-group label { font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
.filter-group input, .filter-group select { padding: 9px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.88rem; color: #334155; outline: none; transition: border 0.2s; background: #f8fafc; }
.filter-group input:focus, .filter-group select:focus { border-color: #8b5cf6; background: white; }
.filter-btn { padding: 10px 22px; border-radius: 10px; border: none; cursor: pointer; font-weight: 700; font-size: 0.88rem; transition: all 0.2s; }
.filter-btn-primary { background: #1e293b; color: white; }
.filter-btn-clear { background: #f1f5f9; color: #64748b; text-decoration: none; display: inline-flex; align-items: center; padding: 10px 18px; border-radius: 10px; font-weight: 700; font-size: 0.88rem; }
.stats-row { display: flex; gap: 14px; margin-bottom: 20px; flex-wrap: wrap; }
.stat-chip { background: white; border: 1px solid #f1f5f9; border-radius: 12px; padding: 14px 20px; flex: 1; min-width: 130px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); }
.stat-chip .sc-val { font-size: 1.5rem; font-weight: 900; }
.stat-chip .sc-lbl { font-size: 0.73rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.4px; margin-top: 2px; }
.data-table { background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 15px rgba(0,0,0,0.04); border: 1px solid #f1f5f9; }
.data-table table { width: 100%; border-collapse: collapse; }
.data-table thead th { padding: 16px 20px; font-size: 0.73rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.8px; background: #f8fafc; border-bottom: 2px solid #f1f5f9; }
.data-table tbody td { padding: 15px 20px; border-bottom: 1px solid #f8fafc; font-size: 0.9rem; color: #334155; }
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tbody tr:hover td { background: #fafbff; }
</style>

<div class="detail-header">
    <a href="{{ route('admin.reports.index') }}" class="back-btn"><i class="fas fa-arrow-left"></i></a>
    <div>
        <h2 class="page-title" style="margin: 0; font-size: 1.5rem;">Reporte de Cotizaciones</h2>
        <p class="text-muted" style="margin: 0; font-size: 0.88rem;">Análisis y seguimiento de todas las solicitudes de cotización recibidas.</p>
    </div>
</div>

<!-- Tabs de estado -->
<div class="status-tabs">
    <a href="{{ route('admin.reports.quotations') }}"
       class="status-tab" style="{{ !$status ? 'background: #1e293b; color: white; border-color: #1e293b;' : 'background: white; color: #64748b; border-color: #e2e8f0;' }}">
        <i class="fas fa-list"></i> Todas
        <span style="background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 50px; font-size: 0.75rem;">{{ \App\Models\Quotation::count() }}</span>
    </a>
    <a href="{{ route('admin.reports.quotations', ['status' => 'completado']) }}"
       class="status-tab" style="{{ $status == 'completado' ? 'background: #059669; color: white; border-color: #059669;' : 'background: white; color: #059669; border-color: #059669;' }}">
        <i class="fas fa-check-circle"></i> Completadas
        <span style="background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 50px; font-size: 0.75rem;">{{ \App\Models\Quotation::where('estado','completado')->count() }}</span>
    </a>
    <a href="{{ route('admin.reports.quotations', ['status' => 'pendiente']) }}"
       class="status-tab" style="{{ $status == 'pendiente' ? 'background: #d97706; color: white; border-color: #d97706;' : 'background: white; color: #d97706; border-color: #d97706;' }}">
        <i class="fas fa-clock"></i> Pendientes
        <span style="background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 50px; font-size: 0.75rem;">{{ \App\Models\Quotation::where('estado','pendiente')->count() }}</span>
    </a>
    <a href="{{ route('admin.reports.quotations', ['status' => 'cancelado']) }}"
       class="status-tab" style="{{ $status == 'cancelado' ? 'background: #dc2626; color: white; border-color: #dc2626;' : 'background: white; color: #dc2626; border-color: #dc2626;' }}">
        <i class="fas fa-times-circle"></i> Canceladas
        <span style="background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 50px; font-size: 0.75rem;">{{ \App\Models\Quotation::where('estado','cancelado')->count() }}</span>
    </a>
</div>

<!-- Filtros adicionales -->
<div class="filter-bar">
    <form method="GET" action="{{ route('admin.reports.quotations') }}" style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap; width: 100%;">
        @if($status)<input type="hidden" name="status" value="{{ $status }}">@endif
        <div class="filter-group" style="flex: 1; min-width: 180px;">
            <label>Buscar cliente / email</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre, apellido o correo...">
        </div>
        <div class="filter-group">
            <label>Fecha desde</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}">
        </div>
        <div class="filter-group">
            <label>Fecha hasta</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}">
        </div>
        <button type="submit" class="filter-btn filter-btn-primary"><i class="fas fa-search" style="margin-right: 6px;"></i>Filtrar</button>
        @if(request()->anyFilled(['search','date_from','date_to']))
            <a href="{{ route('admin.reports.quotations', $status ? ['status' => $status] : []) }}" class="filter-btn-clear"><i class="fas fa-times" style="margin-right: 6px;"></i>Limpiar</a>
        @endif
    </form>
</div>

<!-- Stats rápidas -->
<div class="stats-row">
    <div class="stat-chip">
        <div class="sc-val" style="color: #8b5cf6;">{{ $quotations->total() }}</div>
        <div class="sc-lbl">Registros Encontrados</div>
    </div>
    <div class="stat-chip">
        <div class="sc-val" style="color: #10b981;">S/ {{ number_format($quotations->sum('total'), 2) }}</div>
        <div class="sc-lbl">Monto Esta Página</div>
    </div>
    @if(!$status)
    <div class="stat-chip">
        <div class="sc-val" style="color: #f59e0b;">{{ $quotations->where('estado','pendiente')->count() }}</div>
        <div class="sc-lbl">Pendientes en Página</div>
    </div>
    @endif
</div>

<!-- Tabla -->
<div class="data-table">
    <table>
        <thead>
            <tr>
                <th>N° Pedido</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Correo</th>
                <th style="text-align: center;">Estado</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($quotations as $q)
                <tr>
                    <td>
                        <span style="font-family: monospace; background: #f1f5f9; border: 1px solid #e2e8f0; padding: 4px 10px; border-radius: 8px; font-weight: 800; color: #475569; font-size: 0.85rem;">
                            #{{ str_pad($q->id, 5, '0', STR_PAD_LEFT) }}
                        </span>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: #334155;">{{ $q->created_at->format('d/m/Y') }}</div>
                        <div style="font-size: 0.75rem; color: #94a3b8;">{{ $q->created_at->format('H:i') }} hrs</div>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: #1e293b;">{{ $q->nombre }} {{ $q->apellidos }}</div>
                        @if($q->numero_documento)
                            <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 1px;">{{ $q->tipo_documento }}: {{ $q->numero_documento }}</div>
                        @endif
                    </td>
                    <td style="font-size: 0.85rem; color: #64748b;">{{ $q->email }}</td>
                    <td style="text-align: center;">
                        @if($q->estado == 'completado')
                            <span style="background: #ecfdf5; color: #059669; padding: 5px 14px; border-radius: 50px; font-size: 0.75rem; font-weight: 800; display: inline-flex; align-items: center; gap: 5px;">
                                <i class="fas fa-check-circle"></i> COMPLETADO
                            </span>
                        @elseif($q->estado == 'pendiente')
                            <span style="background: #fffbeb; color: #d97706; padding: 5px 14px; border-radius: 50px; font-size: 0.75rem; font-weight: 800; display: inline-flex; align-items: center; gap: 5px;">
                                <i class="fas fa-clock"></i> PENDIENTE
                            </span>
                        @else
                            <span style="background: #fef2f2; color: #dc2626; padding: 5px 14px; border-radius: 50px; font-size: 0.75rem; font-weight: 800; display: inline-flex; align-items: center; gap: 5px;">
                                <i class="fas fa-times-circle"></i> CANCELADO
                            </span>
                        @endif
                    </td>
                    <td style="text-align: right; font-weight: 900; color: #0f172a; font-size: 1rem;">
                        S/ {{ number_format($q->total, 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 60px; color: #94a3b8;">
                        <i class="fas fa-file-invoice" style="font-size: 2.5rem; margin-bottom: 12px; opacity: 0.2; display: block;"></i>
                        No se encontraron cotizaciones con los filtros aplicados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div style="padding: 20px; display: flex; justify-content: center; background: #f8fafc; border-top: 1px solid #f1f5f9;">
        {{ $quotations->appends(request()->query())->links('partials.pagination') }}
    </div>
</div>
@endsection
