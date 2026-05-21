@extends('layouts.admin')

@section('content')
<style>
/* ===== PRODUCT PRICE HISTORY PREMIUM STYLE ===== */
.detail-header { display: flex; align-items: center; gap: 16px; margin-bottom: 28px; }
.back-btn { 
    width: 42px; height: 42px; border-radius: 12px; background: white; 
    border: 1.5px solid #e2e8f0; display: flex; align-items: center; 
    justify-content: center; color: #64748b; text-decoration: none; 
    flex-shrink: 0; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); 
    box-shadow: 0 2px 8px rgba(0,0,0,0.04); 
}
.back-btn:hover { border-color: #10b981; color: #10b981; transform: translateX(-3px); }

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
    border-color: #10b981; background: white; 
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); 
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
.data-table tbody tr:hover td { background: #f9fbfb; }

.delta-up { 
    display: inline-flex; align-items: center; gap: 4px; 
    background: #fef2f2; color: #dc2626; 
    padding: 5px 12px; border-radius: 50px; 
    font-weight: 800; font-size: 0.82rem; 
}
.delta-down { 
    display: inline-flex; align-items: center; gap: 4px; 
    background: #ecfdf5; color: #059669; 
    padding: 5px 12px; border-radius: 50px; 
    font-weight: 800; font-size: 0.82rem; 
}

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
        <h2 class="page-title" style="margin: 0; font-size: 1.6rem; font-family: 'Poppins', sans-serif; font-weight: 800;"><i class="fas fa-history" style="color: #10b981; margin-right: 8px;"></i>Historial de Precios</h2>
        <p class="text-muted" style="margin: 3px 0 0; font-size: 0.9rem;">Seguimiento y control de variaciones en los precios del catálogo comercial.</p>
    </div>
</div>

<!-- Filtros -->
<div class="filter-bar">
    <form method="GET" action="{{ route('admin.reports.products') }}" style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap; width: 100%;">
        <div class="filter-group" style="flex: 1; min-width: 200px;">
            <label>Buscar producto</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre del producto...">
        </div>
        <div class="filter-group" style="min-width: 160px;">
            <label>Tipo de variación</label>
            <select name="tipo">
                <option value="">Todas las variaciones</option>
                <option value="up" {{ request('tipo') == 'up' ? 'selected' : '' }}>Solo incrementos ↑</option>
                <option value="down" {{ request('tipo') == 'down' ? 'selected' : '' }}>Solo reducciones ↓</option>
            </select>
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
            @if(request()->anyFilled(['search','tipo','date_from','date_to']))
                <a href="{{ route('admin.reports.products') }}" class="filter-btn-clear"><i class="fas fa-times"></i> Limpiar</a>
            @endif
        </div>
    </form>
</div>

<!-- Stats rápidas -->
<div class="stats-row">
    <div class="stat-chip">
        <div class="stat-chip-icon" style="background: #eef2f6; color: #475569;"><i class="fas fa-database"></i></div>
        <div>
            <div class="sc-val" style="color: #475569;">{{ $history->total() }}</div>
            <div class="sc-lbl">Cambios Filtrados</div>
        </div>
    </div>
    <div class="stat-chip">
        <div class="stat-chip-icon" style="background: #fef2f2; color: #dc2626;"><i class="fas fa-arrow-trend-up"></i></div>
        <div>
            <div class="sc-val" style="color: #dc2626;">{{ $history->filter(fn($h) => $h->new_price > $h->old_price)->count() }}</div>
            <div class="sc-lbl">Incrementos (Pág.)</div>
        </div>
    </div>
    <div class="stat-chip">
        <div class="stat-chip-icon" style="background: #ecfdf5; color: #059669;"><i class="fas fa-arrow-trend-down"></i></div>
        <div>
            <div class="sc-val" style="color: #059669;">{{ $history->filter(fn($h) => $h->new_price < $h->old_price)->count() }}</div>
            <div class="sc-lbl">Reducciones (Pág.)</div>
        </div>
    </div>
</div>

<!-- Tabla -->
<div class="data-table">
    <table>
        <thead>
            <tr>
                <th style="width: 160px;">Fecha del Cambio</th>
                <th>Producto</th>
                <th>Laboratorio</th>
                <th style="text-align: right; width: 140px;">Precio Anterior</th>
                <th style="text-align: right; width: 140px;">Precio Nuevo</th>
                <th style="text-align: center; width: 160px;">Variación</th>
            </tr>
        </thead>
        <tbody>
            @forelse($history as $h)
                @php $diff = $h->new_price - $h->old_price; @endphp
                <tr>
                    <td>
                        <div style="font-weight: 700; color: #334155; font-size: 0.9rem;">{{ $h->created_at->format('d/m/Y') }}</div>
                        <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 2px; font-weight: 500;"><i class="far fa-clock" style="margin-right: 2px;"></i> {{ $h->created_at->format('H:i') }} hrs</div>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: #1e293b; max-width: 280px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 0.95rem;" title="{{ $h->product->nombre ?? '' }}">
                            {{ $h->product->nombre ?? 'Producto eliminado' }}
                        </div>
                        @if($h->product->codigo ?? false)
                            <div style="font-size: 0.72rem; color: #94a3b8; margin-top: 3px; font-family: monospace; font-weight: 600;">
                                Cód: {{ $h->product->codigo }}
                            </div>
                        @endif
                    </td>
                    <td>
                        <span style="background: #f5f3ff; color: #6d28d9; padding: 5px 12px; border-radius: 8px; font-size: 0.78rem; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                            <i class="fas fa-flask" style="font-size: 0.7rem; opacity: 0.7;"></i> {{ $h->product->laboratory->descripcion ?? 'N/A' }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <span style="color: #94a3b8; font-weight: 600; text-decoration: line-through; font-size: 0.88rem;">S/ {{ number_format($h->old_price, 2) }}</span>
                    </td>
                    <td style="text-align: right; font-weight: 800; color: #0f172a; font-size: 0.98rem; font-family: 'Poppins', sans-serif;">
                        S/ {{ number_format($h->new_price, 2) }}
                    </td>
                    <td style="text-align: center;">
                        @if($diff > 0)
                            <span class="delta-up">
                                <i class="fas fa-caret-up"></i>
                                +S/ {{ number_format(abs($diff), 2) }}
                            </span>
                        @elseif($diff < 0)
                            <span class="delta-down">
                                <i class="fas fa-caret-down"></i>
                                -S/ {{ number_format(abs($diff), 2) }}
                            </span>
                        @else
                            <span style="color: #94a3b8; font-size: 0.85rem; font-weight: 600;">Sin cambios</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 70px 20px; color: #94a3b8;">
                        <i class="fas fa-history" style="font-size: 3rem; margin-bottom: 16px; opacity: 0.2; display: block; margin-left: auto; margin-right: auto;"></i>
                        <span style="font-weight: 600; font-size: 0.95rem;">No se encontraron variaciones de precios con los filtros aplicados.</span>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="padding: 24px; display: flex; justify-content: center; background: #f8fafc; border-top: 1px solid #f1f5f9;">
        {{ $history->appends(request()->query())->links('partials.pagination') }}
    </div>
</div>
@endsection
