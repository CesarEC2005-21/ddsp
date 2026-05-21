@extends('layouts.admin')

@section('content')
<style>
/* ===== REPORT DASHBOARD PREMIUM STYLES ===== */
.rp-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
    border-radius: 24px;
    padding: 40px;
    margin-bottom: 30px;
    color: white;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 30px -10px rgba(15, 23, 42, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.05);
}
.rp-header::before {
    content: '';
    position: absolute;
    top: -80px; right: -80px;
    width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(16,185,129,0.18) 0%, transparent 70%);
    border-radius: 50%;
}
.rp-header::after {
    content: '';
    position: absolute;
    bottom: -60px; left: -60px;
    width: 250px; height: 250px;
    background: radial-gradient(circle, rgba(59,130,246,0.12) 0%, transparent 70%);
    border-radius: 50%;
}
.rp-header-content { position: relative; z-index: 2; }
.rp-header h1 { font-family: 'Poppins', sans-serif; font-size: 2.2rem; font-weight: 800; margin: 0 0 8px; letter-spacing: -0.5px; color: #ffffff; }
.rp-header p { opacity: 0.75; font-size: 1rem; margin: 0; font-weight: 500; color: #ffffff; }
.rp-header .rp-date { 
    position: absolute; right: 40px; top: 50%; transform: translateY(-50%);
    background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.12);
    padding: 10px 24px; border-radius: 50px; font-size: 0.88rem; color: rgba(255,255,255,0.9);
    z-index: 2;
    backdrop-filter: blur(10px);
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* KPI Cards */
.kpi-grid { 
    display: grid; 
    grid-template-columns: repeat(4, 1fr); 
    gap: 20px; 
    margin-bottom: 30px; 
}
.kpi-card {
    background: white;
    border-radius: 20px;
    padding: 26px 24px;
    box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.03), 0 2px 6px -1px rgba(15, 23, 42, 0.02);
    border: 1px solid #f1f5f9;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 16px;
    position: relative;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.kpi-card:hover { 
    transform: translateY(-6px); 
    box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.08), 0 10px 10px -5px rgba(15, 23, 42, 0.03); 
    border-color: rgba(59,130,246,0.15);
}
.kpi-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; width: 4px; height: 100%;
    background: transparent;
    transition: background 0.3s ease;
}
.kpi-card.emerald::before { background: #10b981; }
.kpi-card.blue::before { background: #3b82f6; }
.kpi-card.purple::before { background: #8b5cf6; }
.kpi-card.teal::before { background: #0d9488; }

.kpi-card .kpi-top { display: flex; justify-content: space-between; align-items: flex-start; }
.kpi-icon {
    width: 48px; height: 48px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem;
    transition: all 0.3s ease;
}
.kpi-card:hover .kpi-icon {
    transform: scale(1.1) rotate(5deg);
}
.kpi-value { font-size: 2.1rem; font-weight: 800; color: #0f172a; line-height: 1.1; font-family: 'Poppins', sans-serif; letter-spacing: -1px; }
.kpi-label { font-size: 0.8rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; }
.kpi-sub { font-size: 0.82rem; color: #64748b; font-weight: 500; }
.kpi-bar { height: 6px; border-radius: 3px; background: #f1f5f9; margin-top: 4px; overflow: hidden; }
.kpi-bar-fill { height: 100%; border-radius: 3px; transition: width 1s ease-out; }

/* Status badges row inside kpi */
.kpi-status-row { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 4px; }
.kpi-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 8px; border-radius: 50px; font-size: 0.7rem; font-weight: 700;
}

.kpi-view-btn {
    display: flex; align-items: center; gap: 6px;
    font-size: 0.8rem; font-weight: 700; color: #94a3b8;
    text-decoration: none; margin-top: 6px; transition: all 0.20s;
    border-top: 1px solid #f8fafc; padding-top: 12px;
}
.kpi-card:hover .kpi-view-btn { color: #3b82f6; border-color: #f1f5f9; }

/* Bottom panels */
.rp-panels { display: grid; grid-template-columns: 1.3fr 1fr; gap: 24px; }
.rp-panel { 
    background: white; 
    border-radius: 20px; 
    padding: 30px; 
    box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.03), 0 2px 6px -1px rgba(15, 23, 42, 0.02);
    border: 1px solid #f1f5f9; 
}
.rp-panel-title { 
    font-size: 1.1rem; 
    font-weight: 800; 
    color: #1e293b; 
    margin-bottom: 24px; 
    display: flex; 
    align-items: center; 
    gap: 12px; 
    font-family: 'Poppins', sans-serif;
}
.rp-panel-title .rp-panel-icon { 
    width: 36px; height: 36px; border-radius: 10px; 
    display: flex; align-items: center; justify-content: center; 
    font-size: 0.95rem; 
}

/* Products ranking */
.prod-rank-item {
    display: flex; align-items: center; gap: 16px;
    padding: 16px; border-bottom: 1px solid #f8fafc;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); 
    border-radius: 14px;
}
.prod-rank-item:hover { background: #f8fafc; transform: translateX(6px); }
.prod-rank-item:last-child { border-bottom: none; }
.prod-rank-num { 
    width: 32px; height: 32px; border-radius: 50%; 
    display: flex; align-items: center; justify-content: center; 
    font-size: 0.85rem; font-weight: 800; flex-shrink: 0; 
}
.prod-rank-bar-wrap { flex: 1; min-width: 0; }
.prod-rank-name { font-size: 0.9rem; font-weight: 700; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.prod-rank-lab { font-size: 0.75rem; color: #94a3b8; margin-top: 2px; font-weight: 500; }
.prod-rank-bar { height: 6px; border-radius: 3px; background: #f1f5f9; margin-top: 8px; overflow: hidden; }
.prod-rank-bar-fill { height: 100%; border-radius: 3px; transition: width 1s ease-out; }
.prod-rank-qty { font-size: 1.1rem; font-weight: 800; color: #0f172a; text-align: right; flex-shrink: 0; line-height: 1; }
.prod-rank-qty small { display: block; font-size: 0.68rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; margin-top: 2px; }

/* Lab list */
.lab-item { 
    display: flex; align-items: center; gap: 16px; 
    padding: 16px; border-bottom: 1px solid #f8fafc; 
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); 
    border-radius: 14px; 
}
.lab-item:hover { background: #f8fafc; transform: translateX(6px); }
.lab-item:last-child { border-bottom: none; }
.lab-rank-dot { width: 12px; height: 12px; border-radius: 50%; flex-shrink: 0; }
.lab-name { font-weight: 700; color: #1e293b; font-size: 0.95rem; flex: 1; }
.lab-count { 
    background: #f1f5f9; 
    color: #475569; 
    padding: 6px 14px; 
    border-radius: 50px; 
    font-size: 0.8rem; 
    font-weight: 700; 
}
.lab-item:hover .lab-count {
    background: #eff6ff;
    color: #2563eb;
}

@media (max-width: 1400px) {
    .kpi-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
    .kpi-grid { grid-template-columns: 1fr; }
    .rp-panels { grid-template-columns: 1fr; }
    .rp-header { padding: 25px; }
    .rp-header h1 { font-size: 1.7rem; }
    .rp-header .rp-date { position: relative; right: auto; top: auto; transform: none; display: inline-flex; margin-top: 15px; }
}
</style>

<!-- Header -->
<div class="rp-header">
    <div class="rp-header-content">
        <h1><i class="fas fa-chart-pie" style="color: #10b981; margin-right: 12px;"></i>Panel de Reportes</h1>
        <p>Análisis estratégico y en tiempo real de las operaciones de Sánchez Pharma</p>
    </div>
    <div class="rp-date">
        <i class="far fa-calendar-alt"></i>
        {{ now()->translatedFormat('d \d\e F, Y') }}
    </div>
</div>

@php
    $maxProd = $topProducts->max('total_solicitado') ?: 1;
    $labColors = ['#10b981', '#3b82f6', '#8b5cf6', '#0d9488', '#f59e0b', '#ef4444'];
@endphp

<!-- KPI Cards -->
<div class="kpi-grid">

    <!-- Ingresos Totales -->
    <div class="kpi-card emerald">
        <div class="kpi-top">
            <div>
                <div class="kpi-label">Ingresos Realizados</div>
                <div class="kpi-value" style="color: #10b981; font-size: 1.8rem; margin-top: 4px;">S/ {{ number_format($revenue, 2) }}</div>
                <div class="kpi-sub" style="margin-top:6px;">Pedidos completados</div>
            </div>
            <div class="kpi-icon" style="background: #ecfdf5; color: #10b981;"><i class="fas fa-wallet"></i></div>
        </div>
        <div class="kpi-bar"><div class="kpi-bar-fill" style="width: 100%; background: linear-gradient(90deg, #34d399, #10b981);"></div></div>
        <a href="{{ route('admin.reports.quotations', ['status' => 'completado']) }}" class="kpi-view-btn">
            <i class="fas fa-arrow-right"></i> Ver ventas concretadas
        </a>
    </div>

    <!-- Clientes -->
    <div class="kpi-card blue">
        <div class="kpi-top">
            <div>
                <div class="kpi-label">Clientes Únicos</div>
                <div class="kpi-value" style="color: #3b82f6;">{{ $totalCustomers }}</div>
                <div class="kpi-sub" style="margin-top:6px;">Correos registrados</div>
            </div>
            <div class="kpi-icon" style="background: #eff6ff; color: #3b82f6;"><i class="fas fa-users"></i></div>
        </div>
        <div class="kpi-bar"><div class="kpi-bar-fill" style="width: 100%; background: linear-gradient(90deg, #60a5fa, #3b82f6);"></div></div>
        <a href="{{ route('admin.reports.customers') }}" class="kpi-view-btn">
            <i class="fas fa-arrow-right"></i> Ver detalle de clientes
        </a>
    </div>

    <!-- Cotizaciones -->
    <div class="kpi-card purple">
        <div class="kpi-top">
            <div>
                <div class="kpi-label">Total Cotizaciones</div>
                <div class="kpi-value" style="color: #8b5cf6;">{{ $totalQuotations }}</div>
                <div class="kpi-sub" style="margin-top:6px;">Solicitudes recibidas</div>
            </div>
            <div class="kpi-icon" style="background: #f5f3ff; color: #8b5cf6;"><i class="fas fa-file-invoice-dollar"></i></div>
        </div>
        <div class="kpi-status-row">
            <span class="kpi-badge" style="background: #ecfdf5; color: #059669;"><i class="fas fa-check-circle"></i> {{ $quotationsCompleted }} Ok</span>
            <span class="kpi-badge" style="background: #fffbeb; color: #d97706;"><i class="fas fa-clock"></i> {{ $quotationsPending }} Pend</span>
            <span class="kpi-badge" style="background: #fef2f2; color: #dc2626;"><i class="fas fa-times-circle"></i> {{ $quotationsCancelled }} Can</span>
        </div>
        <a href="{{ route('admin.reports.quotations') }}" class="kpi-view-btn">
            <i class="fas fa-arrow-right"></i> Ver desglose por estado
        </a>
    </div>

    <!-- Historial de Productos -->
    <div class="kpi-card teal">
        <div class="kpi-top">
            <div>
                <div class="kpi-label">Cambios de Precio</div>
                <div class="kpi-value" style="color: #0d9488;">{{ $priceHistoryCount }}</div>
                <div class="kpi-sub" style="margin-top:6px;">Fluctuaciones de catálogo</div>
            </div>
            <div class="kpi-icon" style="background: #f0fdf4; color: #0d9488;"><i class="fas fa-history"></i></div>
        </div>
        <div class="kpi-bar"><div class="kpi-bar-fill" style="width: {{ min(100, $priceHistoryCount * 3) }}%; background: linear-gradient(90deg, #0d9488, #14b8a6);"></div></div>
        <a href="{{ route('admin.reports.products') }}" class="kpi-view-btn">
            <i class="fas fa-arrow-right"></i> Ver historial de precios
        </a>
    </div>

</div>

<!-- Bottom panels: Products + Labs -->
<div class="rp-panels">

    <!-- Productos más solicitados -->
    <div class="rp-panel">
        <div class="rp-panel-title">
            <div class="rp-panel-icon" style="background: #fef2f2; color: #ef4444;"><i class="fas fa-fire"></i></div>
            <span>Productos Más Solicitados</span>
            <span style="margin-left: auto; font-size: 0.75rem; font-weight: 700; color: #64748b; background: #f1f5f9; padding: 4px 12px; border-radius: 50px;">Top {{ $topProducts->count() }}</span>
        </div>
        @forelse($topProducts as $i => $item)
            @php $pct = $maxProd > 0 ? ($item->total_solicitado / $maxProd * 100) : 0; @endphp
            <div class="prod-rank-item">
                <div class="prod-rank-num" style="background: {{ ['#e6f4ea','#eff6ff','#f5f3ff','#e0f2f1','#fffbeb','#fef2f2'][$i] ?? '#f1f5f9' }}; color: {{ $labColors[$i] ?? '#64748b' }};">{{ $i + 1 }}</div>
                <div class="prod-rank-bar-wrap">
                    <div class="prod-rank-name" title="{{ $item->product->nombre ?? 'Producto' }}">{{ $item->product->nombre ?? 'Producto eliminado' }}</div>
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
            <div style="text-align: center; padding: 40px 20px; color: #94a3b8;">
                <i class="fas fa-box-open" style="font-size: 2.5rem; margin-bottom: 12px; opacity: 0.3; display: block; margin-left: auto; margin-right: auto;"></i>
                <p style="margin: 0; font-weight: 600;">Sin datos de productos.</p>
            </div>
        @endforelse
    </div>

    <!-- Laboratorios populares -->
    <div class="rp-panel">
        <div class="rp-panel-title">
            <div class="rp-panel-icon" style="background: #eff6ff; color: #3b82f6;"><i class="fas fa-flask"></i></div>
            <span>Laboratorios Más Activos</span>
        </div>
        @forelse($topLaboratories as $i => $lab)
            <div class="lab-item">
                <div class="lab-rank-dot" style="background: {{ $labColors[$i] ?? '#94a3b8' }};"></div>
                <div class="lab-name">{{ $lab->lab_name }}</div>
                <span class="lab-count">{{ $lab->menciones }} pedidos</span>
            </div>
        @empty
            <div style="text-align: center; padding: 40px 20px; color: #94a3b8;">
                <i class="fas fa-flask" style="font-size: 2.5rem; margin-bottom: 12px; opacity: 0.3; display: block; margin-left: auto; margin-right: auto;"></i>
                <p style="margin: 0; font-weight: 600;">Sin datos de laboratorios.</p>
            </div>
        @endforelse

        <!-- Progress visual -->
        @if($topLaboratories->count() > 0)
            @php $maxLab = $topLaboratories->max('menciones') ?: 1; @endphp
            <div style="margin-top: 30px; padding-top: 24px; border-top: 1px solid #f1f5f9;">
                <div style="font-size: 0.78rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 16px;">Distribución Porcentual</div>
                @foreach($topLaboratories as $i => $lab)
                    @php $percentage = round($lab->menciones / $maxLab * 100); @endphp
                    <div style="margin-bottom: 14px;">
                        <div style="display: flex; justify-content: space-between; font-size: 0.8rem; color: #475569; margin-bottom: 6px; font-weight: 600;">
                            <span>{{ Str::limit($lab->lab_name, 25) }}</span>
                            <span style="font-weight: 800; color: #1e293b;">{{ $percentage }}%</span>
                        </div>
                        <div style="height: 6px; background: #f1f5f9; border-radius: 3px; overflow: hidden;">
                            <div style="width: {{ $lab->menciones / $maxLab * 100 }}%; height: 100%; border-radius: 3px; background: {{ $labColors[$i] ?? '#94a3b8' }};"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
