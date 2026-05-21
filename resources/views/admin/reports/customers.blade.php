@extends('layouts.admin')

@section('content')
<style>
/* ===== CUSTOMERS REPORT PREMIUM STYLE ===== */
.detail-header { display: flex; align-items: center; gap: 16px; margin-bottom: 28px; }
.back-btn { 
    width: 42px; height: 42px; border-radius: 12px; background: white; 
    border: 1.5px solid #e2e8f0; display: flex; align-items: center; 
    justify-content: center; color: #64748b; text-decoration: none; 
    flex-shrink: 0; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); 
    box-shadow: 0 2px 8px rgba(0,0,0,0.04); 
}
.back-btn:hover { border-color: #3b82f6; color: #3b82f6; transform: translateX(-3px); }

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
    border-color: #3b82f6; background: white; 
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); 
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

.avatar { 
    width: 42px; height: 42px; border-radius: 12px; 
    display: flex; align-items: center; justify-content: center; 
    font-weight: 800; font-size: 1rem; color: white; flex-shrink: 0; 
    font-family: 'Poppins', sans-serif;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
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
        <h2 class="page-title" style="margin: 0; font-size: 1.6rem; font-family: 'Poppins', sans-serif; font-weight: 800;"><i class="fas fa-users" style="color: #3b82f6; margin-right: 8px;"></i>Reporte de Clientes</h2>
        <p class="text-muted" style="margin: 3px 0 0; font-size: 0.9rem;">Consolidado estratégico de clientes e historial de cotizaciones acumuladas.</p>
    </div>
</div>

<!-- Filtros -->
<div class="filter-bar">
    <form method="GET" action="{{ route('admin.reports.customers') }}" style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap; width: 100%;">
        <div class="filter-group" style="flex: 1; min-width: 220px;">
            <label>Buscar cliente</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre, apellido o correo...">
        </div>
        <div class="filter-group" style="min-width: 180px;">
            <label>Ordenar por</label>
            <select name="sort">
                <option value="total_pedidos" {{ request('sort','total_pedidos') == 'total_pedidos' ? 'selected' : '' }}>Más cotizaciones realizadas</option>
                <option value="total_gastado" {{ request('sort') == 'total_gastado' ? 'selected' : '' }}>Mayor monto cotizado</option>
                <option value="email" {{ request('sort') == 'email' ? 'selected' : '' }}>Correo (A-Z)</option>
            </select>
        </div>
        <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
            <button type="submit" class="filter-btn filter-btn-primary"><i class="fas fa-filter"></i> Filtrar</button>
            @if(request()->anyFilled(['search','sort']))
                <a href="{{ route('admin.reports.customers') }}" class="filter-btn-clear"><i class="fas fa-times"></i> Limpiar</a>
            @endif
        </div>
    </form>
</div>

<!-- Stats rápidas -->
<div class="stats-row">
    <div class="stat-chip">
        <div class="stat-chip-icon" style="background: #eff6ff; color: #3b82f6;"><i class="fas fa-users"></i></div>
        <div>
            <div class="sc-val" style="color: #3b82f6;">{{ $customers->total() }}</div>
            <div class="sc-lbl">Clientes Filtrados</div>
        </div>
    </div>
    <div class="stat-chip">
        <div class="stat-chip-icon" style="background: #ecfdf5; color: #10b981;"><i class="fas fa-wallet"></i></div>
        <div>
            <div class="sc-val" style="color: #10b981;">S/ {{ number_format($customers->sum('total_gastado'), 2) }}</div>
            <div class="sc-lbl">Total Acumulado (Pág)</div>
        </div>
    </div>
    <div class="stat-chip">
        <div class="stat-chip-icon" style="background: #f5f3ff; color: #8b5cf6;"><i class="fas fa-file-alt"></i></div>
        <div>
            <div class="sc-val" style="color: #8b5cf6;">{{ $customers->sum('total_pedidos') }}</div>
            <div class="sc-lbl">Pedidos en Página</div>
        </div>
    </div>
</div>

<!-- Tabla -->
<div class="data-table">
    <table>
        <thead>
            <tr>
                <th style="width: 60px;">#</th>
                <th>Nombre del Cliente</th>
                <th>Documento</th>
                <th>Correo Electrónico</th>
                <th style="text-align: center;">Cotizaciones</th>
                <th style="text-align: right; width: 180px;">Monto Cotizado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $i => $c)
                <tr>
                    <td style="color: #cbd5e1; font-weight: 800; font-size: 0.82rem;">{{ $customers->firstItem() + $i }}</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <div class="avatar" style="background: {{ ['#3b82f6','#8b5cf6','#10b981','#f59e0b','#ef4444'][$i % 5] }};">
                                {{ strtoupper(substr($c->nombre ?? '?', 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight: 700; color: #1e293b; font-size: 0.95rem;">{{ $c->nombre }} {{ $c->apellidos }}</div>
                                <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 2px; font-weight: 500;">Socio Comercial</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($c->numero_documento)
                            <span style="font-family: monospace; background: #f8fafc; border: 1px solid #e2e8f0; padding: 4px 10px; border-radius: 8px; font-size: 0.8rem; color: #475569; font-weight: 600;">
                                <i class="far fa-id-card" style="margin-right: 4px; opacity: 0.7;"></i> {{ $c->tipo_documento }}: {{ $c->numero_documento }}
                            </span>
                        @else
                            <span style="color: #cbd5e1; font-size: 0.9rem;">—</span>
                        @endif
                    </td>
                    <td>
                        <a href="mailto:{{ $c->email }}" style="color: #3b82f6; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                            <i class="far fa-envelope" style="opacity: 0.7;"></i> {{ $c->email }}
                        </a>
                    </td>
                    <td style="text-align: center;">
                        <span style="background: #eff6ff; color: #2563eb; padding: 6px 14px; border-radius: 50px; font-weight: 800; font-size: 0.85rem;">
                            {{ $c->total_pedidos }}
                        </span>
                    </td>
                    <td style="text-align: right; font-weight: 800; color: #0f172a; font-size: 1rem; font-family: 'Poppins', sans-serif;">
                        S/ {{ number_format($c->total_gastado, 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 70px 20px; color: #94a3b8;">
                        <i class="fas fa-users" style="font-size: 3rem; margin-bottom: 16px; opacity: 0.2; display: block; margin-left: auto; margin-right: auto;"></i>
                        <span style="font-weight: 600; font-size: 0.95rem;">No se encontraron clientes registrados con los filtros aplicados.</span>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="padding: 24px; display: flex; justify-content: center; background: #f8fafc; border-top: 1px solid #f1f5f9;">
        {{ $customers->appends(request()->query())->links('partials.pagination') }}
    </div>
</div>
@endsection
