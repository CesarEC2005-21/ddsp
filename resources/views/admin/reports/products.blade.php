@extends('layouts.admin')

@section('content')
<style>
.detail-header { display: flex; align-items: center; gap: 16px; margin-bottom: 28px; }
.back-btn { width: 42px; height: 42px; border-radius: 12px; background: white; border: 1.5px solid #e2e8f0; display: flex; align-items: center; justify-content: center; color: #64748b; text-decoration: none; flex-shrink: 0; transition: all 0.2s; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
.back-btn:hover { border-color: var(--primary-color); color: var(--primary-color); }
.filter-bar { background: white; border-radius: 16px; padding: 20px 24px; margin-bottom: 20px; box-shadow: 0 2px 15px rgba(0,0,0,0.04); border: 1px solid #f1f5f9; display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap; }
.filter-group { display: flex; flex-direction: column; gap: 6px; }
.filter-group label { font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
.filter-group input, .filter-group select { padding: 9px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.88rem; color: #334155; outline: none; transition: border 0.2s; background: #f8fafc; }
.filter-group input:focus, .filter-group select:focus { border-color: #10b981; background: white; }
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
.data-table tbody td { padding: 15px 20px; border-bottom: 1px solid #f8fafc; font-size: 0.9rem; color: #334155; vertical-align: middle; }
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tbody tr:hover td { background: #f0fdf4; }
.delta-up { display: inline-flex; align-items: center; gap: 4px; color: #dc2626; font-weight: 800; }
.delta-down { display: inline-flex; align-items: center; gap: 4px; color: #059669; font-weight: 800; }
</style>

<div class="detail-header">
    <a href="{{ route('admin.reports.index') }}" class="back-btn"><i class="fas fa-arrow-left"></i></a>
    <div>
        <h2 class="page-title" style="margin: 0; font-size: 1.5rem;">Historial de Precios</h2>
        <p class="text-muted" style="margin: 0; font-size: 0.88rem;">Seguimiento cronológico de todas las variaciones de precios en el catálogo.</p>
    </div>
</div>

<!-- Filtros -->
<div class="filter-bar">
    <form method="GET" action="{{ route('admin.reports.products') }}" style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap; width: 100%;">
        <div class="filter-group" style="flex: 1; min-width: 200px;">
            <label>Buscar producto</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre del producto...">
        </div>
        <div class="filter-group">
            <label>Tipo de variación</label>
            <select name="tipo">
                <option value="">Todos</option>
                <option value="up" {{ request('tipo') == 'up' ? 'selected' : '' }}>Solo incrementos ↑</option>
                <option value="down" {{ request('tipo') == 'down' ? 'selected' : '' }}>Solo reducciones ↓</option>
            </select>
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
        @if(request()->anyFilled(['search','tipo','date_from','date_to']))
            <a href="{{ route('admin.reports.products') }}" class="filter-btn-clear"><i class="fas fa-times" style="margin-right: 6px;"></i>Limpiar</a>
        @endif
    </form>
</div>

<!-- Stats rápidas -->
<div class="stats-row">
    <div class="stat-chip">
        <div class="sc-val" style="color: #10b981;">{{ $history->total() }}</div>
        <div class="sc-lbl">Registros Encontrados</div>
    </div>
    <div class="stat-chip">
        <div class="sc-val" style="color: #dc2626;">{{ $history->filter(fn($h) => $h->new_price > $h->old_price)->count() }}</div>
        <div class="sc-lbl">Incrementos (Pág.)</div>
    </div>
    <div class="stat-chip">
        <div class="sc-val" style="color: #059669;">{{ $history->filter(fn($h) => $h->new_price < $h->old_price)->count() }}</div>
        <div class="sc-lbl">Reducciones (Pág.)</div>
    </div>
</div>

<!-- Tabla -->
<div class="data-table">
    <table>
        <thead>
            <tr>
                <th>Fecha del Cambio</th>
                <th>Producto</th>
                <th>Laboratorio</th>
                <th style="text-align: right;">Precio Anterior</th>
                <th style="text-align: right;">Precio Nuevo</th>
                <th style="text-align: center;">Variación</th>
            </tr>
        </thead>
        <tbody>
            @forelse($history as $h)
                @php $diff = $h->new_price - $h->old_price; @endphp
                <tr>
                    <td>
                        <div style="font-weight: 600; color: #334155;">{{ $h->created_at->format('d/m/Y') }}</div>
                        <div style="font-size: 0.75rem; color: #94a3b8;">{{ $h->created_at->format('H:i') }} hrs</div>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: #1e293b; max-width: 280px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $h->product->nombre ?? 'Producto eliminado' }}
                        </div>
                        @if($h->product->codigo ?? false)
                            <div style="font-size: 0.72rem; color: #94a3b8; margin-top: 1px; font-family: monospace;">
                                Cód: {{ $h->product->codigo }}
                            </div>
                        @endif
                    </td>
                    <td>
                        <span style="background: #f5f3ff; color: #6d28d9; padding: 4px 10px; border-radius: 8px; font-size: 0.78rem; font-weight: 700;">
                            {{ $h->product->laboratory->descripcion ?? 'N/A' }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <span style="color: #94a3b8; font-weight: 600; text-decoration: line-through; font-size: 0.9rem;">S/ {{ number_format($h->old_price, 2) }}</span>
                    </td>
                    <td style="text-align: right; font-weight: 900; color: #0f172a;">
                        S/ {{ number_format($h->new_price, 2) }}
                    </td>
                    <td style="text-align: center;">
                        @if($diff > 0)
                            <span class="delta-up">
                                <i class="fas fa-arrow-up"></i>
                                +S/ {{ number_format(abs($diff), 2) }}
                            </span>
                        @elseif($diff < 0)
                            <span class="delta-down">
                                <i class="fas fa-arrow-down"></i>
                                -S/ {{ number_format(abs($diff), 2) }}
                            </span>
                        @else
                            <span style="color: #94a3b8; font-size: 0.85rem;">Sin cambio</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 60px; color: #94a3b8;">
                        <i class="fas fa-history" style="font-size: 2.5rem; margin-bottom: 12px; opacity: 0.2; display: block;"></i>
                        No se encontraron registros de historial con los filtros aplicados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div style="padding: 20px; display: flex; justify-content: center; background: #f8fafc; border-top: 1px solid #f1f5f9;">
        {{ $history->appends(request()->query())->links('partials.pagination') }}
    </div>
</div>
@endsection
