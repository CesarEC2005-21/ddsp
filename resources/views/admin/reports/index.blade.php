@extends('layouts.admin')

@section('content')
<div class="page-header" style="margin-bottom: 30px;">
    <h2 class="page-title" style="font-size: 2rem; color: #1e293b; margin-bottom: 10px;">
        <i class="fas fa-chart-line" style="color: var(--primary-color);"></i> Reportes y Análisis
    </h2>
    <p style="color: var(--text-muted);">Estadísticas relevantes sobre las operaciones, ventas y catálogos de Sanchez Pharma.</p>
</div>

<!-- Resumen General -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div class="card" style="background: linear-gradient(135deg, #3b82f6, #2563eb); color: white;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p style="margin: 0; font-size: 0.9rem; opacity: 0.9;">Total Cotizaciones</p>
                <h3 style="margin: 5px 0 0; font-size: 2rem; color: white;">{{ number_format($totalQuotations) }}</h3>
            </div>
            <i class="fas fa-file-invoice" style="font-size: 3rem; opacity: 0.5;"></i>
        </div>
    </div>

    <div class="card" style="background: linear-gradient(135deg, #10b981, #059669); color: white;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p style="margin: 0; font-size: 0.9rem; opacity: 0.9;">Ingresos Estimados (Aprobados)</p>
                <h3 style="margin: 5px 0 0; font-size: 2rem; color: white;">S/ {{ number_format($totalRevenue, 2) }}</h3>
            </div>
            <i class="fas fa-coins" style="font-size: 3rem; opacity: 0.5;"></i>
        </div>
    </div>

    <div class="card" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p style="margin: 0; font-size: 0.9rem; opacity: 0.9;">Cotizaciones Pendientes</p>
                <h3 style="margin: 5px 0 0; font-size: 2rem; color: white;">{{ $pendingQuotations }}</h3>
            </div>
            <i class="fas fa-clock" style="font-size: 3rem; opacity: 0.5;"></i>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
    
    <!-- Lado Izquierdo: Tablas -->
    <div style="display: flex; flex-direction: column; gap: 30px;">
        <!-- Top Productos -->
        <div class="card">
            <h3 style="margin-bottom: 20px; font-size: 1.2rem; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-box-open" style="color: var(--primary-color);"></i> Productos Más Solicitados (Aprobados)
            </h3>
            <table>
                <thead>
                    <tr>
                        <th>CÓDIGO</th>
                        <th>PRODUCTO</th>
                        <th style="text-align: center;">CANTIDAD VENDIDA</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topProducts as $item)
                        <tr>
                            <td><strong>{{ $item->product->codigo ?? '-' }}</strong></td>
                            <td>{{ $item->product->nombre ?? 'Producto Desconocido' }}</td>
                            <td style="text-align: center;">
                                <span class="badge" style="background: #e0e7ff; color: #4338ca; font-size: 0.9rem;">{{ number_format($item->total_vendido) }} und</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" style="text-align: center;">No hay datos suficientes</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Top Laboratorios -->
        <div class="card">
            <h3 style="margin-bottom: 20px; font-size: 1.2rem; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-flask" style="color: var(--primary-color);"></i> Laboratorios Destacados (En Cotizaciones Aprobadas)
            </h3>
            <table>
                <thead>
                    <tr>
                        <th>LABORATORIO</th>
                        <th style="text-align: center;">VOLUMEN SOLICITADO</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topLaboratories as $lab)
                        <tr>
                            <td>{{ $lab->lab_name }}</td>
                            <td style="text-align: center;">
                                <span class="badge" style="background: #dcfce7; color: #15803d; font-size: 0.9rem;">{{ number_format($lab->total_vendido) }} unidades</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="2" style="text-align: center;">No hay datos suficientes</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Lado Derecho: Estadísticas Mensuales y Estados -->
    <div style="display: flex; flex-direction: column; gap: 30px;">
        <div class="card">
            <h3 style="margin-bottom: 20px; font-size: 1.2rem; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-chart-pie" style="color: var(--primary-color);"></i> Estado de Cotizaciones
            </h3>
            <ul style="list-style: none; padding: 0; margin: 0;">
                @foreach($quotationsByStatus as $statusData)
                    <li style="display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px dashed var(--border-color);">
                        <span style="text-transform: capitalize; font-weight: 600;">
                            @if($statusData->estado == 'pendiente') <i class="fas fa-circle" style="color: #f59e0b; font-size: 0.6rem; margin-right: 5px;"></i> 
                            @elseif($statusData->estado == 'aprobado') <i class="fas fa-circle" style="color: #10b981; font-size: 0.6rem; margin-right: 5px;"></i> 
                            @else <i class="fas fa-circle" style="color: #ef4444; font-size: 0.6rem; margin-right: 5px;"></i> @endif
                            {{ $statusData->estado }}
                        </span>
                        <span class="badge" style="background: #f1f5f9; color: #475569;">{{ $statusData->total }} registros</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="card">
            <h3 style="margin-bottom: 20px; font-size: 1.2rem; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-calendar-alt" style="color: var(--primary-color);"></i> Histórico a 6 Meses
            </h3>
            <ul style="list-style: none; padding: 0; margin: 0;">
                @forelse($monthlyQuotations as $month)
                    <li style="display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px dashed var(--border-color);">
                        <span style="font-weight: 600; color: #475569;">{{ \Carbon\Carbon::parse($month->month . '-01')->translatedFormat('F Y') }}</span>
                        <div style="text-align: right;">
                            <div style="font-weight: 700; color: var(--text-dark);">S/ {{ number_format($month->revenue, 2) }}</div>
                            <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $month->count }} cotizaciones</div>
                        </div>
                    </li>
                @empty
                    <li><p style="text-align: center; color: var(--text-muted);">No hay histórico de meses anteriores.</p></li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
