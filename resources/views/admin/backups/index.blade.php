@extends('layouts.admin')
@section('content')
    <div class="page-header">
        <h2 class="page-title">Backups del Sistema</h2>
    </div>


    <div class="row">
        <div class="col-md-6">
            <div class="card" style="height: 100%; border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div style="text-align: center; padding: 50px 30px;">
                    <div style="width: 120px; height: 120px; background: #f0fdf4; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px; box-shadow: 0 10px 25px rgba(16, 185, 129, 0.2);">
                        <i class="fas fa-database" style="font-size: 3.5rem; color: var(--primary-green);"></i>
                    </div>
                    <h3 style="margin-bottom: 15px; font-weight: 800; color: #1e293b;">Respaldo Manual</h3>
                    <p style="color: #64748b; margin-bottom: 35px; font-size: 1.05rem;">Genere una copia de seguridad al instante con toda la información crítica del sistema.</p>
                    
                    <form action="{{ route('admin.backups.generate') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary" style="padding: 16px 40px; font-size: 1.1rem; font-weight: 800; border-radius: 50px; box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3); transition: 0.3s; width: 100%;">
                            <i class="fas fa-download" style="margin-right: 8px;"></i> GENERAR Y DESCARGAR BACKUP
                        </button>
                    </form>
                    
                    <div style="margin-top: 25px; font-size: 0.85rem; color: #94a3b8; background: #fff; padding: 10px; border-radius: 10px; border: 1px solid #e2e8f0;">
                        <i class="fas fa-info-circle text-primary"></i> El archivo SQL incluirá estructura y datos. Puede demorar dependiendo de su volumen.
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card" style="height: 100%; border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: white;">
                <div style="padding: 50px 40px;">
                    <div style="display: flex; align-items: center; margin-bottom: 30px;">
                        <i class="fas fa-robot" style="font-size: 2.5rem; color: #38bdf8; margin-right: 20px;"></i>
                        <div>
                            <h3 style="margin: 0; font-weight: 800; color: white;">Backups Automáticos</h3>
                            <span style="color: #94a3b8; font-size: 0.9rem;">Configuración del Servidor</span>
                        </div>
                    </div>
                    
                    <p style="color: #cbd5e1; font-size: 1.1rem; line-height: 1.7; margin-bottom: 30px;">
                        El sistema está configurado para realizar un resguardo completo de la base de datos de manera automatizada para garantizar la integridad de su información.
                    </p>

                    <div style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 20px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 15px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px;">
                            <span style="color: #94a3b8; font-weight: 600;">Frecuencia</span>
                            <strong style="color: #38bdf8;"><i class="fas fa-calendar-week"></i> Semanal</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 15px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px;">
                            <span style="color: #94a3b8; font-weight: 600;">Día de ejecución</span>
                            <strong style="color: white;">Domingo</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #94a3b8; font-weight: 600;">Hora (Servidor)</span>
                            <strong style="color: white;"><i class="far fa-clock"></i> 02:00 AM</strong>
                        </div>
                    </div>
                    
                    <div style="margin-top: 30px; display: flex; align-items: center; gap: 10px; color: #10b981; font-weight: 700;">
                        <i class="fas fa-check-circle" style="font-size: 1.2rem;"></i> Servicio de Cron Job Activo
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
