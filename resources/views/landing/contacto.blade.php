@extends('layouts.landing')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/landing/contacto.css') }}">
<style>
    .contact-header { background: #0F172A; color: white; padding: 100px 5% 150px; text-align: center; margin-top: -100px; padding-top: 180px; }
    .contact-wrapper { max-width: 1200px; margin: -100px auto 100px; display: flex; gap: 40px; padding: 0 5%; flex-wrap: wrap; }
    .contact-info-panel { flex: 1; min-width: 300px; background: var(--primary-green); color: white; border-radius: 20px; padding: 50px; box-shadow: var(--shadow-lg); position: relative; overflow: hidden; }
    .contact-info-panel::before { content: ''; position: absolute; width: 300px; height: 300px; background: rgba(255,255,255,0.1); border-radius: 50%; bottom: -100px; right: -100px; }
    .contact-form-panel { flex: 2; min-width: 300px; background: white; border-radius: 20px; padding: 50px; box-shadow: var(--shadow-lg); border: 1px solid #eee; }
    
    .info-item { display: flex; gap: 20px; margin-bottom: 30px; align-items: flex-start; }
    .info-icon { font-size: 1.5rem; margin-top: 5px; }
    .info-content h4 { margin: 0 0 5px 0; font-size: 1.1rem; }
    .info-content p { margin: 0; opacity: 0.9; line-height: 1.5; }
    
    .social-links { display: flex; gap: 15px; margin-top: 50px; position: relative; z-index: 10; }
    .social-links a { width: 45px; height: 45px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.2rem; transition: 0.3s; text-decoration: none; }
    .social-links a:hover { background: white; color: var(--primary-green); transform: translateY(-5px); }
</style>
@endpush

@section('content')
    <div class="contact-header">
        <h1 style="font-size: 3.5rem; margin-bottom: 15px; font-family: 'Poppins', sans-serif;">Hablemos de Negocios</h1>
        <p style="font-size: 1.2rem; opacity: 0.8; max-width: 600px; margin: 0 auto;">¿Interesado en nuestros productos o ser un proveedor? Nuestro equipo especializado está listo para brindarte la mejor asesoría B2B.</p>
    </div>

    <div class="contact-wrapper">
        <!-- Panel de Información -->
        <div class="contact-info-panel">
            <h3 style="font-size: 1.8rem; margin-bottom: 40px; font-family: 'Poppins', sans-serif; position: relative; z-index: 10;">Información de Contacto</h3>
            
            <div class="info-item" style="position: relative; z-index: 10;">
                <i class="fas fa-map-marker-alt info-icon"></i>
                <div class="info-content">
                    <h4>Nuestra Sede Principal</h4>
                    <p>Av. Principal 123, Distrito de Negocios<br>Lima, Perú</p>
                </div>
            </div>
            
            <div class="info-item" style="position: relative; z-index: 10;">
                <i class="fas fa-phone-alt info-icon"></i>
                <div class="info-content">
                    <h4>Central Telefónica</h4>
                    <p>+51 987 654 321<br>01 123-4567</p>
                </div>
            </div>
            
            <div class="info-item" style="position: relative; z-index: 10;">
                <i class="fas fa-envelope info-icon"></i>
                <div class="info-content">
                    <h4>Correo Electrónico</h4>
                    <p>ventas@sanchezpharma.com<br>soporte@sanchezpharma.com</p>
                </div>
            </div>

            <div class="social-links">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                <a href="#"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>

        <!-- Formulario -->
        <div class="contact-form-panel">
            <h3 style="font-size: 1.8rem; margin-bottom: 30px; color: #333; font-family: 'Poppins', sans-serif;">Envíanos un mensaje corporativo</h3>
            <form action="#" method="POST">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #555;">Nombre de la Empresa / Botica *</label>
                        <input type="text" style="width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 10px; outline: none; transition: 0.3s; box-sizing: border-box; font-family: inherit;" placeholder="Ej. Farmacia Salud Total" required>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #555;">RUC *</label>
                        <input type="text" style="width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 10px; outline: none; transition: 0.3s; box-sizing: border-box; font-family: inherit;" placeholder="Ej. 20123456789" required>
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #555;">Correo Electrónico *</label>
                        <input type="email" style="width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 10px; outline: none; transition: 0.3s; box-sizing: border-box; font-family: inherit;" placeholder="correo@empresa.com" required>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #555;">Asunto *</label>
                        <select style="width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 10px; outline: none; transition: 0.3s; background: white; box-sizing: border-box; font-family: inherit; color: #333;" required>
                            <option value="">Seleccione el motivo de contacto</option>
                            <option value="catalogo">Solicitud de Catálogo y Precios</option>
                            <option value="representante">Visita de Representante</option>
                            <option value="reclamo">Atención al Cliente / Reclamos</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 30px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #555;">Detalle su solicitud *</label>
                    <textarea rows="6" style="width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 10px; outline: none; transition: 0.3s; resize: vertical; box-sizing: border-box; font-family: inherit;" placeholder="Especifique los productos o laboratorios de su interés..." required></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="padding: 18px 40px; font-size: 1.1rem; border-radius: 50px; font-weight: 600; cursor: pointer; border: none; width: 100%; max-width: 300px; display: block; margin: 0 auto; box-shadow: 0 10px 20px rgba(46, 125, 50, 0.2); transition: 0.3s; font-family: inherit;">
                    <i class="fas fa-paper-plane" style="margin-right: 10px;"></i> Enviar Solicitud
                </button>
            </form>
        </div>
    </div>
@endsection
