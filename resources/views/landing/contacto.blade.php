@extends('layouts.landing')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/landing/contacto.css') }}">
<style>
    .contact-header { background: linear-gradient(135deg, #0f172a, #1e293b); color: white; padding: 120px 5% 180px; text-align: center; margin-top: -100px; padding-top: 200px; position: relative; overflow: hidden; }
    .contact-header::after { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0.1; background-image: radial-gradient(#10b981 1px, transparent 1px); background-size: 20px 20px; z-index: 1; }
    .contact-header * { position: relative; z-index: 2; }
    
    .contact-wrapper { max-width: 1200px; margin: -100px auto 100px; display: flex; gap: 40px; padding: 0 5%; flex-wrap: wrap; position: relative; z-index: 10; }
    .contact-info-panel { flex: 1; min-width: 300px; background: linear-gradient(145deg, var(--primary-green), var(--dark-green)); color: white; border-radius: 24px; padding: 50px; box-shadow: 0 20px 40px rgba(27, 94, 32, 0.2); position: relative; overflow: hidden; }
    .contact-info-panel::before { content: ''; position: absolute; width: 300px; height: 300px; background: rgba(255,255,255,0.05); border-radius: 50%; bottom: -100px; right: -100px; border: 20px solid rgba(255,255,255,0.05); }
    .contact-form-panel { flex: 2; min-width: 300px; background: white; border-radius: 24px; padding: 50px; box-shadow: 0 20px 50px rgba(0,0,0,0.08); border: 1px solid #f1f5f9; }
    
    .info-item { display: flex; gap: 20px; margin-bottom: 35px; align-items: flex-start; }
    .info-icon { font-size: 1.5rem; margin-top: 5px; color: #a7f3d0; background: rgba(255,255,255,0.1); width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border-radius: 12px; }
    .info-content h4 { margin: 0 0 8px 0; font-size: 1.15rem; color: white; font-weight: 700; }
    .info-content p { margin: 0; opacity: 0.85; line-height: 1.6; font-size: 0.95rem; }
    
    .social-links { display: flex; gap: 15px; margin-top: 50px; position: relative; z-index: 10; padding-top: 30px; border-top: 1px solid rgba(255,255,255,0.1); }
    .social-links a { width: 45px; height: 45px; border-radius: 12px; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.2rem; transition: 0.3s; text-decoration: none; border: 1px solid rgba(255,255,255,0.05); }
    .social-links a:hover { background: white; color: var(--primary-green); transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    
    .form-input { width: 100%; padding: 16px 20px; border: 2px solid #e2e8f0; border-radius: 12px; outline: none; transition: 0.3s; box-sizing: border-box; font-family: inherit; font-size: 0.95rem; background: #f8fafc; color: #1e293b; }
    .form-input:focus { border-color: var(--primary-green); background: white; box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1); }
    .form-label { display: block; margin-bottom: 8px; font-weight: 600; color: #475569; font-size: 0.9rem; letter-spacing: 0.5px; }
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
            <h3 style="font-size: 1.8rem; margin-bottom: 30px; color: #1e293b; font-family: 'Poppins', sans-serif; font-weight: 700;">Envíanos un mensaje corporativo</h3>
            <form action="#" method="POST">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 25px; margin-bottom: 25px;">
                    <div>
                        <label class="form-label">Nombre de la Empresa / Botica <span style="color: #ef4444;">*</span></label>
                        <input type="text" class="form-input" placeholder="Ej. Farmacia Salud Total" required>
                    </div>
                    <div>
                        <label class="form-label">RUC <span style="color: #ef4444;">*</span></label>
                        <input type="text" class="form-input" placeholder="Ej. 20123456789" required>
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 25px; margin-bottom: 25px;">
                    <div>
                        <label class="form-label">Correo Electrónico <span style="color: #ef4444;">*</span></label>
                        <input type="email" class="form-input" placeholder="correo@empresa.com" required>
                    </div>
                    <div>
                        <label class="form-label">Asunto <span style="color: #ef4444;">*</span></label>
                        <select class="form-input" required>
                            <option value="">Seleccione el motivo de contacto</option>
                            <option value="catalogo">Solicitud de Catálogo y Precios</option>
                            <option value="representante">Visita de Representante</option>
                            <option value="reclamo">Atención al Cliente / Reclamos</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 35px;">
                    <label class="form-label">Detalle su solicitud <span style="color: #ef4444;">*</span></label>
                    <textarea rows="6" class="form-input" placeholder="Especifique los productos o laboratorios de su interés..." required></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="padding: 18px 40px; font-size: 1.1rem; border-radius: 12px; font-weight: 700; cursor: pointer; border: none; width: 100%; box-shadow: 0 10px 20px rgba(46, 125, 50, 0.2); transition: 0.3s; font-family: inherit; display: flex; align-items: center; justify-content: center; gap: 10px;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 15px 25px rgba(46, 125, 50, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 20px rgba(46, 125, 50, 0.2)';">
                    <i class="fas fa-paper-plane"></i> Enviar Solicitud
                </button>
            </form>
        </div>
    </div>
@endsection
