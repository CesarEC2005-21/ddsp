@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <h2 class="page-title">Configuración del Sistema</h2>
    </div>

    @if(session('success'))
        <div style="background: #D1FAE5; color: #065F46; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="card" style="max-width: 800px;">
        <form action="{{ route('admin.settings.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Nombre de la Empresa</label>
                    <input type="text" name="company_name" class="form-control" value="{{ $settings['company_name'] }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">RUC</label>
                    <input type="text" name="company_ruc" class="form-control" value="{{ $settings['company_ruc'] }}" required maxlength="11">
                </div>

                <div class="form-group">
                    <label class="form-label">Teléfono de Contacto</label>
                    <input type="text" name="company_phone" class="form-control" value="{{ $settings['company_phone'] }}" required>
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Dirección Fiscal / Oficina</label>
                    <input type="text" name="company_address" class="form-control" value="{{ $settings['company_address'] }}" required>
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Correo Electrónico Principal</label>
                    <input type="email" name="company_email" class="form-control" value="{{ $settings['company_email'] }}" required>
                </div>
            </div>

            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
                <button type="submit" class="btn btn-primary" style="padding: 12px 30px;">
                    <i class="fas fa-save"></i> Guardar Configuración
                </button>
            </div>
        </form>
    </div>

    <div class="card" style="max-width: 800px; margin-top: 20px; background: #f8fafc; border: 1px dashed #cbd5e1;">
        <h4 style="color: #64748b; margin-bottom: 10px;"><i class="fas fa-info-circle"></i> Uso de estos datos</h4>
        <p style="font-size: 0.9rem; color: #94a3b8; line-height: 1.5;">
            Esta información se utilizará para generar los encabezados de las cotizaciones en PDF y para las notificaciones automáticas enviadas a los clientes vía Email y WhatsApp.
        </p>
    </div>
@endsection
