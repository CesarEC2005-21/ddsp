<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; color: #333; line-height: 1.6; }
        .wrapper { max-width: 650px; margin: 20px auto; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #1b5e20, #2e7d32); padding: 30px; text-align: center; }
        .header h1 { color: #fff; font-size: 22px; margin-bottom: 5px; }
        .header p { color: #a5d6a7; font-size: 14px; }
        .badge { display: inline-block; background: #ff9800; color: #fff; padding: 6px 18px; border-radius: 20px; font-size: 13px; font-weight: bold; margin-top: 10px; }
        .body { padding: 30px; }
        .alert-box { background: #fff8e1; border-left: 4px solid #ff9800; padding: 15px 20px; border-radius: 5px; margin-bottom: 25px; }
        .alert-box p { font-size: 14px; color: #555; }
        .alert-box strong { color: #e65100; }
        .section { margin-bottom: 25px; }
        .section-title { font-size: 13px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #2e7d32; border-bottom: 2px solid #e8f5e9; padding-bottom: 8px; margin-bottom: 12px; }
        .info-grid { display: table; width: 100%; }
        .info-row { display: table-row; }
        .info-label { display: table-cell; font-weight: bold; font-size: 13px; color: #555; width: 140px; padding: 5px 0; vertical-align: top; }
        .info-value { display: table-cell; font-size: 13px; color: #333; padding: 5px 0; }
        table.products { width: 100%; border-collapse: collapse; font-size: 13px; }
        table.products th { background: #2e7d32; color: #fff; padding: 10px 12px; text-align: left; }
        table.products td { padding: 10px 12px; border-bottom: 1px solid #f0f0f0; }
        table.products tr:nth-child(even) td { background: #f9f9f9; }
        .total-row td { font-weight: bold; font-size: 15px; background: #e8f5e9 !important; }
        .total-amount { color: #2e7d32; font-size: 18px; }
        .obs-box { background: #fafafa; border: 1px solid #eee; border-radius: 5px; padding: 12px 15px; font-size: 13px; color: #555; }
        .footer { background: #f8f8f8; padding: 20px 30px; text-align: center; border-top: 1px solid #eee; }
        .footer p { font-size: 12px; color: #999; }
        .cta-link { display: inline-block; margin-top: 15px; background: #2e7d32; color: #fff; padding: 10px 25px; border-radius: 5px; text-decoration: none; font-size: 14px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Header -->
        <div class="header">
            <h1>{{ $company['name'] }}</h1>
            <p>Panel de Administración — Nueva Solicitud Recibida</p>
            <div class="badge">🛒 Nueva Cotización #{{ str_pad($quotation->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>

        <!-- Body -->
        <div class="body">
            <div class="alert-box">
                <p>Se ha recibido una <strong>nueva solicitud de cotización</strong> en tu plataforma. Revisa los detalles a continuación y contáctate con el cliente a la brevedad posible.</p>
            </div>

            <!-- Datos del cliente -->
            <div class="section">
                <div class="section-title">👤 Datos del Cliente</div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Nombre completo:</div>
                        <div class="info-value">{{ $quotation->nombre }} {{ $quotation->apellidos }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">{{ $quotation->tipo_documento }}:</div>
                        <div class="info-value">{{ $quotation->numero_documento }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Teléfono:</div>
                        <div class="info-value">{{ $quotation->telefono }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Email:</div>
                        <div class="info-value">{{ $quotation->email }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Ciudad:</div>
                        <div class="info-value">{{ $quotation->ciudad }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Fecha:</div>
                        <div class="info-value">{{ $quotation->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>

            <!-- Productos solicitados -->
            <div class="section">
                <div class="section-title">📦 Productos Solicitados</div>
                <table class="products">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Laboratorio</th>
                            <th style="text-align:center;">Cant.</th>
                            <th style="text-align:right;">Precio U.</th>
                            <th style="text-align:right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quotation->items as $item)
                        <tr>
                            <td>
                                {{ $item->product->nombre }}<br>
                                <small style="color:#888;">Cód: {{ $item->product->codigo }}</small>
                            </td>
                            <td>{{ $item->product->laboratory->descripcion ?? 'N/A' }}</td>
                            <td style="text-align:center;">{{ $item->cantidad }}</td>
                            <td style="text-align:right;">S/ {{ number_format($item->precio_unitario, 2) }}</td>
                            <td style="text-align:right;">S/ {{ number_format($item->precio_unitario * $item->cantidad, 2) }}</td>
                        </tr>
                        @endforeach
                        <tr class="total-row">
                            <td colspan="4" style="text-align:right; padding-right: 16px;">TOTAL ESTIMADO:</td>
                            <td style="text-align:right;" class="total-amount">S/ {{ number_format($quotation->total, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Observaciones -->
            @if($quotation->observaciones)
            <div class="section">
                <div class="section-title">📝 Observaciones del Cliente</div>
                <div class="obs-box">{{ $quotation->observaciones }}</div>
            </div>
            @endif

            <p style="font-size:13px; color:#555; text-align:center; margin-top:10px;">
                El PDF completo de la cotización está adjunto a este correo.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Este correo fue generado automáticamente por el sistema <strong>{{ $company['name'] }}</strong>.</p>
            <p style="margin-top:5px;">{{ $company['phone'] }} | {{ $company['email'] }}</p>
        </div>
    </div>
</body>
</html>
