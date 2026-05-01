<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cotización #{{ $quotation->id }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.5; }
        .header { border-bottom: 2px solid #2E7D32; padding-bottom: 20px; margin-bottom: 30px; }
        .company-name { font-size: 24px; font-weight: bold; color: #2E7D32; margin: 0; }
        .company-info { font-size: 12px; color: #666; margin-top: 5px; }
        .quotation-title { font-size: 20px; font-weight: bold; margin-bottom: 20px; text-align: center; background: #f0fdf4; padding: 10px; border-radius: 5px; }
        .grid { display: table; width: 100%; margin-bottom: 20px; }
        .grid-col { display: table-cell; width: 50%; vertical-align: top; }
        .section-title { font-size: 14px; font-weight: bold; border-bottom: 1px solid #eee; margin-bottom: 10px; padding-bottom: 5px; color: #2E7D32; }
        .info-label { font-weight: bold; font-size: 12px; width: 100px; display: inline-block; }
        .info-value { font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #2E7D32; color: white; padding: 10px; font-size: 12px; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #eee; font-size: 12px; }
        .product-img { width: 50px; height: 50px; object-fit: contain; }
        .total-row { background: #f9fafb; font-weight: bold; }
        .footer { margin-top: 50px; font-size: 10px; color: #999; text-align: center; border-top: 1px solid #eee; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">{{ $company['name'] }}</div>
        <div class="company-info">
            RUC: {{ $company['ruc'] }} | {{ $company['address'] }}<br>
            Teléfono: {{ $company['phone'] }} | Email: {{ $company['email'] }}
        </div>
    </div>

    <div class="quotation-title">SOLICITUD DE COTIZACIÓN #{{ str_pad($quotation->id, 6, '0', STR_PAD_LEFT) }}</div>

    <div class="grid">
        <div class="grid-col">
            <div class="section-title">DATOS DEL CLIENTE</div>
            <div><span class="info-label">Nombre:</span> <span class="info-value">{{ $quotation->nombre }} {{ $quotation->apellidos }}</span></div>
            <div><span class="info-label">{{ $quotation->tipo_documento }}:</span> <span class="info-value">{{ $quotation->numero_documento }}</span></div>
            <div><span class="info-label">Teléfono:</span> <span class="info-value">{{ $quotation->telefono }}</span></div>
            <div><span class="info-label">Email:</span> <span class="info-value">{{ $quotation->email }}</span></div>
            <div><span class="info-label">Ciudad:</span> <span class="info-value">{{ $quotation->ciudad }}</span></div>
        </div>
        <div class="grid-col">
            <div class="section-title">DETALLES DE SOLICITUD</div>
            <div><span class="info-label">Fecha:</span> <span class="info-value">{{ $quotation->created_at->format('d/m/Y H:i') }}</span></div>
            <div><span class="info-label">Estado:</span> <span class="info-value">{{ strtoupper($quotation->estado) }}</span></div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>PRODUCTO</th>
                <th>LABORATORIO</th>
                <th style="text-align: center;">CANT.</th>
                <th style="text-align: right;">PRECIO U.</th>
                <th style="text-align: right;">SUBTOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quotation->items as $item)
            <tr>
                <td>
                    {{ $item->product->nombre }}<br>
                    <small style="color: #888;">Cod: {{ $item->product->codigo }}</small>
                </td>
                <td>{{ $item->product->laboratory->descripcion ?? 'N/A' }}</td>
                <td style="text-align: center;">{{ $item->cantidad }}</td>
                <td style="text-align: right;">S/ {{ number_format($item->precio_unitario, 2) }}</td>
                <td style="text-align: right;">S/ {{ number_format($item->precio_unitario * $item->cantidad, 2) }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4" style="text-align: right;">TOTAL ESTIMADO:</td>
                <td style="text-align: right; color: #2E7D32; font-size: 16px;">S/ {{ number_format($quotation->total, 2) }}</td>
            </tr>
        </tbody>
    </table>

    @if($quotation->observaciones)
    <div style="margin-top: 30px;">
        <div class="section-title">OBSERVACIONES</div>
        <div style="font-size: 12px; color: #666; background: #f9fafb; padding: 15px; border-radius: 5px;">
            {{ $quotation->observaciones }}
        </div>
    </div>
    @endif

    <div class="footer">
        Esta es una cotización estimada. Los precios y disponibilidad pueden variar.<br>
        Documento generado automáticamente por Sanchez Pharma - &copy; {{ date('Y') }}
    </div>
</body>
</html>
