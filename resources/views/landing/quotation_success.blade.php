@extends('layouts.landing')

@section('content')
<div style="background: #f8fafc; padding: 100px 5%; min-height: 80vh; display: flex; align-items: center; justify-content: center;">
    <div style="max-width: 600px; background: white; padding: 50px; border-radius: 25px; box-shadow: 0 20px 50px rgba(0,0,0,0.05); text-align: center; border: 1px solid #f1f5f9;">
        <div style="width: 100px; height: 100px; background: #dcfce7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px;">
            <i class="fas fa-check-circle" style="font-size: 4rem; color: #16a34a;"></i>
        </div>
        <h1 style="font-size: 2.2rem; color: #1e293b; margin-bottom: 15px;">¡Solicitud Enviada!</h1>
        <p style="color: #64748b; font-size: 1.1rem; margin-bottom: 40px; line-height: 1.6;">
            Gracias por confiar en <strong>Sanchez Pharma</strong>. Hemos recibido tu solicitud de cotización (ID: #{{ session('quotation_id') }}). 
            Uno de nuestros asesores se pondrá en contacto contigo a la brevedad.
        </p>
        <div style="display: flex; gap: 15px; justify-content: center;">
            <a href="{{ route('products') }}" class="btn btn-primary" style="padding: 15px 30px;">Seguir comprando</a>
            <a href="{{ route('home') }}" class="btn btn-outline" style="padding: 15px 30px; border: 1px solid #ddd; color: #666; text-decoration: none; border-radius: 8px;">Volver al inicio</a>
        </div>
    </div>
</div>
@endsection
