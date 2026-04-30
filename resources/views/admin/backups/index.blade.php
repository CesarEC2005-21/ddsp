@extends('layouts.admin')
@section('content')
    <div class="page-header">
        <h2 class="page-title">Backups del Sistema</h2>
    </div>

    @if(session('error'))
        <div style="background: #FEE2E2; color: #991B1B; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    <div class="card" style="max-width: 600px;">
        <div style="text-align: center; padding: 40px 20px;">
            <div style="width: 100px; height: 100px; background: #f0fdf4; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                <i class="fas fa-database" style="font-size: 3rem; color: var(--primary-green);"></i>
            </div>
            <h3 style="margin-bottom: 10px;">Respaldo de Base de Datos</h3>
            <p style="color: #64748b; margin-bottom: 30px;">Genere una copia de seguridad completa de su información (productos, usuarios, laboratorios, etc.) en un archivo SQL descargable.</p>
            
            <form action="{{ route('admin.backups.generate') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary" style="padding: 15px 40px; font-size: 1.1rem; font-weight: 700;">
                    <i class="fas fa-download"></i> GENERAR Y DESCARGAR BACKUP
                </button>
            </form>
            
            <p style="margin-top: 20px; font-size: 0.85rem; color: #94a3b8;">
                <i class="fas fa-info-circle"></i> El proceso puede tardar unos segundos dependiendo del tamaño de la base de datos.
            </p>
        </div>
    </div>
@endsection
