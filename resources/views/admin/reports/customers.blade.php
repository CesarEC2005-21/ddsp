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
.filter-group input:focus, .filter-group select:focus { border-color: #3b82f6; background: white; }
.filter-btn { padding: 10px 22px; border-radius: 10px; border: none; cursor: pointer; font-weight: 700; font-size: 0.88rem; transition: all 0.2s; }
.filter-btn-primary { background: #1e293b; color: white; }
.filter-btn-primary:hover { background: #0f172a; }
.filter-btn-clear { background: #f1f5f9; color: #64748b; text-decoration: none; display: flex; align-items: center; padding: 10px 18px; border-radius: 10px; font-weight: 700; font-size: 0.88rem; }
.stats-row { display: flex; gap: 14px; margin-bottom: 20px; flex-wrap: wrap; }
.stat-chip { background: white; border: 1px solid #f1f5f9; border-radius: 12px; padding: 14px 20px; flex: 1; min-width: 120px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); }
.stat-chip .sc-val { font-size: 1.6rem; font-weight: 900; }
.stat-chip .sc-lbl { font-size: 0.75rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.4px; margin-top: 2px; }
.data-table { background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 15px rgba(0,0,0,0.04); border: 1px solid #f1f5f9; }
.data-table table { width: 100%; border-collapse: collapse; }
.data-table thead th { padding: 16px 20px; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.8px; background: #f8fafc; border-bottom: 2px solid #f1f5f9; }
.data-table tbody td { padding: 15px 20px; border-bottom: 1px solid #f8fafc; font-size: 0.9rem; color: #334155; }
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tbody tr:hover td { background: #fafbff; }
.avatar { width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 0.9rem; color: white; flex-shrink: 0; }
</style>

<div class="detail-header">
    <a href="{{ route('admin.reports.index') }}" class="back-btn"><i class="fas fa-arrow-left"></i></a>
    <div>
        <h2 class="page-title" style="margin: 0; font-size: 1.5rem;">Reporte de Clientes</h2>
        <p class="text-muted" style="margin: 0; font-size: 0.88rem;">Listado consolidado de todos los clientes que han realizado solicitudes.</p>
    </div>
</div>

<!-- Filtros -->
<div class="filter-bar">
    <form method="GET" action="{{ route('admin.reports.customers') }}" style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap; width: 100%;">
        <div class="filter-group" style="flex: 1; min-width: 180px;">
            <label>Buscar nombre o email</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Ej. Juan Pérez o correo@...">
        </div>
        <div class="filter-group">
            <label>Ordenar por</label>
            <select name="sort">
                <option value="total_pedidos" {{ request('sort','total_pedidos') == 'total_pedidos' ? 'selected' : '' }}>Más pedidos</option>
                <option value="total_gastado" {{ request('sort') == 'total_gastado' ? 'selected' : '' }}>Mayor gasto</option>
                <option value="email" {{ request('sort') == 'email' ? 'selected' : '' }}>Email A-Z</option>
            </select>
        </div>
        <button type="submit" class="filter-btn filter-btn-primary"><i class="fas fa-search" style="margin-right: 6px;"></i>Filtrar</button>
        @if(request()->anyFilled(['search','sort']))
            <a href="{{ route('admin.reports.customers') }}" class="filter-btn-clear"><i class="fas fa-times" style="margin-right: 6px;"></i>Limpiar</a>
        @endif
    </form>
</div>

<!-- Stats rápidas -->
<div class="stats-row">
    <div class="stat-chip">
        <div class="sc-val" style="color: #3b82f6;">{{ $customers->total() }}</div>
        <div class="sc-lbl">Clientes Encontrados</div>
    </div>
    <div class="stat-chip">
        <div class="sc-val" style="color: #10b981;">S/ {{ number_format($customers->sum('total_gastado'), 2) }}</div>
        <div class="sc-lbl">Total Cotizado (Página)</div>
    </div>
    <div class="stat-chip">
        <div class="sc-val" style="color: #8b5cf6;">{{ $customers->sum('total_pedidos') }}</div>
        <div class="sc-lbl">Pedidos en Esta Página</div>
    </div>
</div>

<!-- Tabla -->
<div class="data-table">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Documento</th>
                <th>Correo Electrónico</th>
                <th style="text-align: center;">Pedidos</th>
                <th style="text-align: right;">Total Cotizado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $i => $c)
                <tr>
                    <td style="color: #cbd5e1; font-weight: 800; font-size: 0.8rem;">{{ $customers->firstItem() + $i }}</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div class="avatar" style="background: {{ ['#3b82f6','#8b5cf6','#10b981','#f59e0b','#ef4444'][$i % 5] }};">
                                {{ strtoupper(substr($c->nombre ?? '?', 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight: 700; color: #1e293b;">{{ $c->nombre }} {{ $c->apellidos }}</div>
                                <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 1px;">Cliente registrado</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($c->numero_documento)
                            <span style="font-family: monospace; background: #f8fafc; border: 1px solid #e2e8f0; padding: 3px 10px; border-radius: 6px; font-size: 0.82rem; color: #475569;">
                                {{ $c->tipo_documento }}: {{ $c->numero_documento }}
                            </span>
                        @else
                            <span style="color: #e2e8f0;">—</span>
                        @endif
                    </td>
                    <td>
                        <a href="mailto:{{ $c->email }}" style="color: #3b82f6; text-decoration: none; font-size: 0.88rem;">{{ $c->email }}</a>
                    </td>
                    <td style="text-align: center;">
                        <span style="background: #eff6ff; color: #1d4ed8; padding: 5px 16px; border-radius: 50px; font-weight: 900; font-size: 0.88rem;">
                            {{ $c->total_pedidos }}
                        </span>
                    </td>
                    <td style="text-align: right; font-weight: 900; color: #0f172a; font-size: 1rem;">
                        S/ {{ number_format($c->total_gastado, 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 60px; color: #94a3b8;">
                        <i class="fas fa-users" style="font-size: 2.5rem; margin-bottom: 12px; opacity: 0.2; display: block;"></i>
                        No se encontraron clientes con los filtros aplicados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div style="padding: 20px; display: flex; justify-content: center; background: #f8fafc; border-top: 1px solid #f1f5f9;">
        {{ $customers->appends(request()->query())->links('partials.pagination') }}
    </div>
</div>
@endsection
