@extends('layouts.admin')

@push('styles')
<style>
    .stat-card { background: white; border-radius: 15px; padding: 25px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); transition: 0.3s; border: 1px solid #f1f5f9; }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(0,0,0,0.1); }
    .stat-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 20px; }
    .chart-container { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid #f1f5f9; }
    .charts-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 30px; }
    @media (max-width: 968px) {
        .charts-grid { grid-template-columns: 1fr; }
        .chart-container { padding: 20px; }
        .charts-grid .chart-container:first-child { order: 1; }
        .charts-grid .chart-container:last-child { order: 2; }
    }
    @media (max-width: 480px) {
        .chart-container { padding: 15px; border-radius: 15px; }
    }
</style>
@endpush

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px; flex-wrap: wrap; gap: 20px;">
        <div>
            <h2 style="font-size: 2rem; color: #1e293b; margin-bottom: 5px;">Dashboard Administrativo</h2>
            <p style="color: #64748b; margin: 0;">Resumen general de las operaciones de Sanchez Pharma.</p>
        </div>
        
        <div style="background: white; padding: 15px 25px; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #f1f5f9;">
            <form action="{{ route('admin.dashboard') }}" method="GET" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Desde</label>
                    <input type="date" name="date_from" value="{{ $dateFrom }}" style="padding: 8px 15px; border: 1px solid #e2e8f0; border-radius: 8px; outline: none; font-size: 0.9rem; color: #334155;">
                </div>
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Hasta</label>
                    <input type="date" name="date_to" value="{{ $dateTo }}" style="padding: 8px 15px; border: 1px solid #e2e8f0; border-radius: 8px; outline: none; font-size: 0.9rem; color: #334155;">
                </div>
                <button type="submit" style="background: #1e293b; color: white; border: none; padding: 9px 20px; border-radius: 8px; font-weight: 700; cursor: pointer; transition: 0.2s;"><i class="fas fa-filter" style="margin-right: 5px;"></i> Filtrar</button>
            </form>
        </div>
    </div>

    <!-- Stat Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 25px; margin-bottom: 40px;">
        <div class="stat-card">
            <div class="stat-icon" style="background: #f0fdf4; color: #166534;"><i class="fas fa-pills"></i></div>
            <h3 style="font-size: 0.9rem; color: #64748b; font-weight: 600; text-transform: uppercase;">Productos</h3>
            <p style="font-size: 2.2rem; font-weight: 800; color: #1e293b; margin: 10px 0;">{{ $stats['products'] }}</p>
            <div style="font-size: 0.85rem; color: #10b981;"><i class="fas fa-arrow-up"></i> Catálogo Activo</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #eff6ff; color: #1e40af;"><i class="fas fa-flask"></i></div>
            <h3 style="font-size: 0.9rem; color: #64748b; font-weight: 600; text-transform: uppercase;">Laboratorios</h3>
            <p style="font-size: 2.2rem; font-weight: 800; color: #1e293b; margin: 10px 0;">{{ $stats['laboratories'] }}</p>
            <div style="font-size: 0.85rem; color: #3b82f6;"><i class="fas fa-check"></i> Marcas aliadas</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #fff7ed; color: #9a3412;"><i class="fas fa-file-invoice-dollar"></i></div>
            <h3 style="font-size: 0.9rem; color: #64748b; font-weight: 600; text-transform: uppercase;">Cotizaciones</h3>
            <p style="font-size: 2.2rem; font-weight: 800; color: #1e293b; margin: 10px 0;">{{ $stats['quotations'] }}</p>
            <div style="font-size: 0.85rem; color: #f59e0b;"><i class="fas fa-coins"></i> S/ {{ number_format($stats['total_quoted'], 2) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #fdf2f8; color: #9d174d;"><i class="fas fa-user-tie"></i></div>
            <h3 style="font-size: 0.9rem; color: #64748b; font-weight: 600; text-transform: uppercase;">Vendedores</h3>
            <p style="font-size: 2.2rem; font-weight: 800; color: #1e293b; margin: 10px 0;">{{ $stats['representatives'] }}</p>
            <div style="font-size: 0.85rem; color: #ec4899;"><i class="fas fa-map-marker-alt"></i> Cobertura Nacional</div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-grid">
        <div class="chart-container">
            <h3 style="margin-bottom: 25px; color: #1e293b;">Tendencia de Solicitudes</h3>
            <canvas id="quotationsChart" height="120"></canvas>
        </div>
        <div class="chart-container">
            <h3 style="margin-bottom: 25px; color: #1e293b;">Top Laboratorios</h3>
            <canvas id="labsChart" height="250"></canvas>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Quotations Chart
    const ctxQ = document.getElementById('quotationsChart').getContext('2d');
    new Chart(ctxQ, {
        type: 'line',
        data: {
            labels: @json($quotationsChart->pluck('label')),
            datasets: [{
                label: 'Cotizaciones',
                data: @json($quotationsChart->pluck('count')),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, grid: { borderDash: [5, 5] } } }
        }
    });

    // Labs Chart
    const ctxL = document.getElementById('labsChart').getContext('2d');
    new Chart(ctxL, {
        type: 'doughnut',
        data: {
            labels: @json($labsChart->pluck('descripcion')),
            datasets: [{
                data: @json($labsChart->pluck('products_count')),
                backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ec4899', '#8b5cf6'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } },
            cutout: '70%'
        }
    });
</script>
@endpush
