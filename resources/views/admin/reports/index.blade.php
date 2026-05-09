@extends('layouts.admin')

@section('content')
<style>
/* ===== REPORT DASHBOARD PREMIUM STYLES ===== */
.rp-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
    border-radius: 20px;
    padding: 35px 40px;
    margin-bottom: 30px;
    color: white;
    position: relative;
    overflow: hidden;
}
.rp-header::before {
    content: '';
    position: absolute;
    top: -80px; right: -80px;
    width: 250px; height: 250px;
    background: radial-gradient(circle, rgba(16,185,129,0.15) 0%, transparent 70%);
    border-radius: 50%;
}
.rp-header::after {
    content: '';
    position: absolute;
    bottom: -60px; left: -60px;
    width: 200px; height: 200px;
    background: radial-gradient(circle, rgba(59,130,246,0.1) 0%, transparent 70%);
    border-radius: 50%;
}
.rp-header-content { position: relative; z-index: 2; }
.rp-header h1 { font-size: 1.8rem; font-weight: 800; margin: 0 0 6px; }
.rp-header p { opacity: 0.65; font-size: 0.95rem; margin: 0; }
.rp-header .rp-date { 
    position: absolute; right: 40px; top: 50%; transform: translateY(-50%);
    background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12);
    padding: 8px 20px; border-radius: 50px; font-size: 0.85rem; color: rgba(255,255,255,0.7);
    z-index: 2;
}

/* KPI Cards */
.kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 28px; }
.kpi-card {
    background: white;
    border-radius: 16px;
    padding: 24px 22px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.04);
    border: 1px solid #f1f5f9;
    display: flex;
    flex-direction: column;
    gap: 12px;
    position: relative;
    overflow: hidden;
    transition: all 0.25s ease;
}
.kpi-card:hover { transform: translateY(-4px); box-shadow: 0 8px 25px rgba(0,0,0,0.08); }
.kpi-card .kpi-top { display: flex; justify-content: space-between; align-items: flex-start; }
.kpi-icon {
    width: 48px; height: 48px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem;
}
.kpi-trend {
    font-size: 0.72rem; font-weight: 700; padding: 3px 10px;
    border-radius: 50px; letter-spacing: 0.3px;
}
.kpi-value { font-size: 2rem; font-weight: 900; color: #0f172a; line-height: 1; }
.kpi-label { font-size: 0.82rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
.kpi-sub { font-size: 0.78rem; color: #64748b; }
.kpi-bar { height: 4px; border-radius: 2px; background: #f1f5f9; margin-top: 4px; }
.kpi-bar-fill { height: 100%; border-radius: 2px; }

/* Status badges row inside kpi */
.kpi-status-row { display: flex; gap: 8px; flex-wrap: wrap; }
.kpi-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 50px; font-size: 0.72rem; font-weight: 800;
}

/* Action card link */
.kpi-link {
    position: absolute; inset: 0; z-index: 1; border-radius: 16px;
    opacity: 0; transition: opacity 0.2s;
}
.kpi-card:hover .kpi-link { opacity: 1; }
.kpi-view-btn {
    display: flex; align-items: center; gap: 6px;
    font-size: 0.78rem; font-weight: 700; color: #94a3b8;
    text-decoration: none; margin-top: 4px; transition: color 0.2s;
}
.kpi-card:hover .kpi-view-btn { color: var(--primary-color); }

/* Bottom panels */
.rp-panels { display: grid; grid-template-columns: 1.4fr 1fr; gap: 24px; }
.rp-panel { background: white; border-radius: 16px; padding: 26px; box-shadow: 0 2px 15px rgba(0,0,0,0.04); border: 1px solid #f1f5f9; }
.rp-panel-title { font-size: 1rem; font-weight: 800; color: #1e293b; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
.rp-panel-title .rp-panel-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; }

/* Products ranking */
.prod-rank-item {
    display: flex; align-items: center; gap: 14px;
    padding: 12px 0; border-bottom: 1px solid #f8fafc;
    transition: background 0.15s;
}
.prod-rank-item:last-child { border-bottom: none; padding-bottom: 0; }
.prod-rank-num { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 900; flex-shrink: 0; }
.prod-rank-bar-wrap { flex: 1; }
.prod-rank-name { font-size: 0.88rem; font-weight: 700; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 280px; }
.prod-rank-lab { font-size: 0.72rem; color: #94a3b8; margin-top: 2px; }
.prod-rank-bar { height: 5px; border-radius: 3px; background: #f1f5f9; margin-top: 6px; }
.prod-rank-bar-fill { height: 100%; border-radius: 3px; }
.prod-rank-qty { font-size: 1rem; font-weight: 900; color: #0f172a; text-align: right; flex-shrink: 0; }
.prod-rank-qty small { display: block; font-size: 0.65rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; }

/* Lab list */
.lab-item { display: flex; align-items: center; gap: 12px; padding: 13px 0; border-bottom: 1px solid #f8fafc; }
.lab-item:last-child { border-bottom: none; }
.lab-rank-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
.lab-name { font-weight: 700; color: #334155; font-size: 0.9rem; flex: 1; }
.lab-count { background: #eff6ff; color: #1d4ed8; padding: 4px 14px; border-radius: 50px; font-size: 0.8rem; font-weight: 800; }

@media (max-width: 1200px) { .kpi-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 768px) { .kpi-grid { grid-template-columns: 1fr; } .rp-panels { grid-template-columns: 1fr; } }
</style>

<!-- Header -->
<div class="rp-header">
    <div class="rp-header-content">
        <h1><i class="fas fa-chart-pie" style="color: #10b981; margin-right: 12px;"></i>Panel de Reportes</h1>
        <p>Análisis estratégico y en tiempo real de las operaciones de Sánchez Pharma</p>
    </div>
    <div class="rp-date"><i class="far fa-calendar-alt" style="margin-right: 8px;"></i>{{ now()->translatedFormat('d \d\e F, Y') }}</div>
</div>

@php
    $maxProd = $topProducts->max('total_solicitado') ?: 1;
    $labColors = ['#3b82f6','#8b5cf6','#10b981','#f59e0b','#ef4444'];
@endphp

<!-- KPI Cards -->
<div class="kpi-grid">

    <!-- Clientes -->
    <div class="kpi-card">
        <div class="kpi-top">
            <div>
                <div class="kpi-label">Clientes Únicos</div>
                <div class="kpi-value" style="color: #3b82f6;">{{ $totalCustomers }}</div>
                <div class="kpi-sub" style="margin-top:6px;">Correos distintos registrados</div>
            </div>
            <div class="kpi-icon" style="background: #eff6ff; color: #3b82f6;"><i class="fas fa-users"></i></div>
        </div>
        <div class="kpi-bar"><div class="kpi-bar-fill" style="width: 100%; background: linear-gradient(90deg, #60a5fa, #3b82f6);"></div></div>
        <a href="{{ route('admin.reports.customers') }}" class="kpi-view-btn">
            <i class="fas fa-arrow-right"></i> Ver detalle completo
        </a>
    </div>

    <!-- Cotizaciones -->
    <div class="kpi-card">
        <div class="kpi-top">
            <div>
                <div class="kpi-label">Total Cotizaciones</div>
                <div class="kpi-value" style="color: #8b5cf6;">{{ $totalQuotations }}</div>
                <div class="kpi-sub" style="margin-top:6px;">Solicitudes recibidas</div>
            </div>
            <div class="kpi-icon" style="background: #f5f3ff; color: #8b5cf6;"><i class="fas fa-file-invoice"></i></div>
        </div>
        <div class="kpi-status-row" style="margin-top: 4px;">
            <span class="kpi-badge" style="background: #ecfdf5; color: #059669;"><i class="fas fa-check-circle"></i> {{ $quotationsCompleted }} Completadas</span>
            <span class="kpi-badge" style="background: #fffbeb; color: #d97706;"><i class="fas fa-clock"></i> {{ $quotationsPending }} Pendientes</span>
            <span class="kpi-badge" style="background: #fef2f2; color: #dc2626;"><i class="fas fa-times-circle"></i> {{ $quotationsCancelled }} Canceladas</span>
        </div>
        <a href="{{ route('admin.reports.quotations') }}" class="kpi-view-btn">
            <i class="fas fa-arrow-right"></i> Ver desglose por estado
        </a>
    </div>

    <!-- Historial de Productos -->
    <div class="kpi-card">
        <div class="kpi-top">
            <div>
                <div class="kpi-label">Cambios de Precio</div>
                <div class="kpi-value" style="color: #10b981;">{{ $priceHistoryCount }}</div>
                <div class="kpi-sub" style="margin-top:6px;">Movimientos registrados en catálogo</div>
            </div>
            <div class="kpi-icon" style="background: #ecfdf5; color: #10b981;"><i class="fas fa-history"></i></div>
        </div>
        <div class="kpi-bar"><div class="kpi-bar-fill" style="width: {{ min(100, $priceHistoryCount * 2) }}%; background: linear-gradient(90deg, #34d399, #10b981);"></div></div>
        <a href="{{ route('admin.reports.products') }}" class="kpi-view-btn">
            <i class="fas fa-arrow-right"></i> Ver historial de precios
        </a>
    </div>

    <!-- Ingresos -->
    <div class="kpi-card">
        <div class="kpi-top">
            <div>
                <div class="kpi-label">Ingresos Estimados</div>
                <div class="kpi-value" style="color: #f59e0b; font-size: 1.5rem;">S/ {{ number_format($revenue, 2) }}</div>
                <div class="kpi-sub" style="margin-top:6px;">Solo pedidos completados</div>
            </div>
            <div class="kpi-icon" style="background: #fffbeb; color: #f59e0b;"><i class="fas fa-coins"></i></div>
        </div>
        <div class="kpi-bar"><div class="kpi-bar-fill" style="width: {{ $totalQuotations > 0 ? ($quotationsCompleted / $totalQuotations * 100) : 0 }}%; background: linear-gradient(90deg, #fbbf24, #f59e0b);"></div></div>
        <a href="{{ route('admin.reports.quotations', ['status' => 'completado']) }}" class="kpi-view-btn">
            <i class="fas fa-arrow-right"></i> Ver cotizaciones completadas
        </a>
    </div>

</div>

<!-- Bottom panels: Products + Labs -->
<div class="rp-panels">

    <!-- Productos más solicitados -->
    <div class="rp-panel">
        <div class="rp-panel-title">
            <div class="rp-panel-icon" style="background: #fef2f2; color: #ef4444;"><i class="fas fa-fire"></i></div>
            Productos Más Solicitados
            <span style="margin-left: auto; font-size: 0.75rem; font-weight: 600; color: #94a3b8; background: #f8fafc; padding: 4px 12px; border-radius: 50px;">Top {{ $topProducts->count() }}</span>
        </div>
        @forelse($topProducts as $i => $item)
            @php $pct = $maxProd > 0 ? ($item->total_solicitado / $maxProd * 100) : 0; @endphp
            <div class="prod-rank-item">
                <div class="prod-rank-num" style="background: {{ ['#eff6ff','#f5f3ff','#ecfdf5','#fffbeb','#fef2f2','#f0f9ff'][$i] ?? '#f8fafc' }}; color: {{ $labColors[$i] ?? '#64748b' }};">{{ $i + 1 }}</div>
                <div class="prod-rank-bar-wrap">
                    <div class="prod-rank-name">{{ $item->product->nombre ?? 'Producto eliminado' }}</div>
                    <div class="prod-rank-lab">{{ $item->product->laboratory->descripcion ?? 'N/A' }}</div>
                    <div class="prod-rank-bar">
                        <div class="prod-rank-bar-fill" style="width: {{ $pct }}%; background: {{ $labColors[$i] ?? '#94a3b8' }};"></div>
                    </div>
                </div>
                <div class="prod-rank-qty">
                    {{ number_format($item->total_solicitado) }}
                    <small>unds.</small>
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 30px; color: #94a3b8;">
                <i class="fas fa-box-open" style="font-size: 2rem; margin-bottom: 10px; opacity: 0.3;"></i>
                <p style="margin: 0;">Sin datos de productos.</p>
            </div>
        @endforelse
    </div>

    <!-- Laboratorios populares -->
    <div class="rp-panel">
        <div class="rp-panel-title">
            <div class="rp-panel-icon" style="background: #f5f3ff; color: #8b5cf6;"><i class="fas fa-flask"></i></div>
            Laboratorios Más Activos
        </div>
        @forelse($topLaboratories as $i => $lab)
            <div class="lab-item">
                <div class="lab-rank-dot" style="background: {{ $labColors[$i] ?? '#94a3b8' }};"></div>
                <div class="lab-name">{{ $lab->lab_name }}</div>
                <span class="lab-count">{{ $lab->menciones }} pedidos</span>
            </div>
        @empty
            <div style="text-align: center; padding: 30px; color: #94a3b8;">
                <i class="fas fa-flask" style="font-size: 2rem; margin-bottom: 10px; opacity: 0.3;"></i>
                <p style="margin: 0;">Sin datos de laboratorios.</p>
            </div>
        @endforelse

        <!-- Progress visual -->
        @if($topLaboratories->count() > 0)
            @php $maxLab = $topLaboratories->max('menciones') ?: 1; @endphp
            <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid #f1f5f9;">
                <div style="font-size: 0.78rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 14px;">Distribución Visual</div>
                @foreach($topLaboratories as $i => $lab)
                    <div style="margin-bottom: 10px;">
                        <div style="display: flex; justify-content: space-between; font-size: 0.78rem; color: #64748b; margin-bottom: 4px;">
                            <span>{{ Str::limit($lab->lab_name, 25) }}</span>
                            <span style="font-weight: 700;">{{ round($lab->menciones / $maxLab * 100) }}%</span>
                        </div>
                        <div style="height: 6px; background: #f1f5f9; border-radius: 3px;">
                            <div style="width: {{ $lab->menciones / $maxLab * 100 }}%; height: 100%; border-radius: 3px; background: {{ $labColors[$i] ?? '#94a3b8' }};"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
