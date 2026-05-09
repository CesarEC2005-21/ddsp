@extends('layouts.landing')

@push('styles')
<style>
    :root {
        --gradient-start: #10b981;
        --gradient-end: #059669;
    }
    
    .reveal { opacity: 0; transform: translateY(40px); transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1); }
    .reveal.visible { opacity: 1; transform: translateY(0); }
    
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    
    .contact-hero {
        background: linear-gradient(135deg, rgba(27, 94, 32, 0.9), rgba(27, 94, 32, 0.95)), 
            url('https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?auto=format&fit=crop&w=1920&q=80') center/cover;
        color: white; padding: 100px 5% 80px; text-align: center; position: relative; overflow: hidden;
        border-radius: 0 0 50px 50px; margin-bottom: 60px;
    }
    .contact-hero::before {
        content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: radial-gradient(circle at 20% 80%, rgba(255,255,255,0.1) 0%, transparent 50%),
                    radial-gradient(circle at 80% 20%, rgba(255,255,255,0.08) 0%, transparent 40%);
    }
    .contact-hero > * { position: relative; z-index: 2; }

    .contact-hero h1 {
        font-size: clamp(2.5rem, 5vw, 3.5rem); font-family: 'Poppins', sans-serif; 
        font-weight: 800; margin-bottom: 15px;
    }
    .contact-hero p {
        font-size: 1.2rem; opacity: 0.9; max-width: 600px; margin: 0 auto; line-height: 1.7;
    }

    .contact-section { padding: 60px 5%; background: #f8fafc; }
    
    .contact-grid {
        display: grid; grid-template-columns: 1fr 1.5fr; gap: 40px; max-width: 1200px; margin: 0 auto;
    }
    
    .info-cards {
        display: grid; gap: 20px;
    }
    .info-card {
        background: white; border-radius: 20px; padding: 30px; display: flex; gap: 20px;
        align-items: flex-start; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;
        transition: all 0.3s ease;
    }
    .info-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(16, 185, 129, 0.15); border-color: #10b981; }
    .info-icon {
        width: 60px; height: 60px; min-width: 60px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 16px;
        display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white;
    }
    .info-content h4 { font-size: 1.1rem; color: #1e293b; font-weight: 700; margin-bottom: 8px; }
    .info-content p { color: #64748b; line-height: 1.6; font-size: 0.95rem; margin: 0; }
    .info-content a { color: #10b981; text-decoration: none; font-weight: 600; }
    .info-content a:hover { text-decoration: underline; }

    .whatsapp-card {
        background: linear-gradient(135deg, #25d366, #128c7e); color: white; border: none;
    }
    .whatsapp-card .info-icon { background: rgba(255,255,255,0.2); }
    .whatsapp-card h4, .whatsapp-card p { color: white; }
    .whatsapp-card p { opacity: 0.9; }

    .contact-form-container {
        background: white; border-radius: 24px; padding: 40px; box-shadow: 0 20px 50px rgba(0,0,0,0.08);
        border: 1px solid #f1f5f9; position: relative; overflow: hidden;
    }
    .contact-form-container::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 6px; 
        background: linear-gradient(90deg, #10b981, #059669, #10b981);
    }
    
    .contact-form-container h3 {
        font-size: 1.8rem; color: #1e293b; font-family: 'Poppins', sans-serif; 
        font-weight: 800; margin-bottom: 30px;
    }
    
    .form-row { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px; }
    .form-group { margin-bottom: 20px; }
    .form-label {
        display: block; margin-bottom: 8px; font-weight: 600; color: #475569; font-size: 0.9rem;
    }
    .form-label span { color: #ef4444; }
    
    .form-input, .form-select, .form-textarea {
        width: 100%; padding: 14px 18px; border: 2px solid #e2e8f0; border-radius: 12px;
        outline: none; transition: all 0.3s ease; font-size: 0.95rem; font-family: inherit;
        background: #f8fafc; color: #1e293b; box-sizing: border-box;
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        border-color: #10b981; background: white; box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
    }
    .form-textarea { min-height: 140px; resize: vertical; }
    
    .form-submit {
        width: 100%; padding: 18px 40px; background: linear-gradient(135deg, #10b981, #059669);
        color: white; border: none; border-radius: 12px; font-size: 1.1rem; font-weight: 700;
        cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center;
        gap: 10px; font-family: inherit; margin-top: 10px;
    }
    .form-submit:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(16, 185, 129, 0.3); }

    @media (max-width: 900px) {
        .contact-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 600px) {
        .form-row { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
    <div class="contact-hero">
        <h1 style="font-size: 3rem; font-family: 'Poppins', sans-serif; font-weight: 800; color: white !important;">Contáctanos</h1>
        <p>Nuestro equipo de ejecutivos está disponible para brindarte asesoría personalizada y responder a todas tus consultas sobre productos farmacéuticos.</p>
    </div>

    <section class="contact-section">
        <div class="contact-grid reveal">
            <div class="info-cards">
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <div class="info-content">
                        <h4>Nuestra Sede</h4>
                        <p>Av. Principal 123, Distrito de Negocios<br>Lima, Perú</p>
                    </div>
                </div>
                
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div class="info-content">
                        <h4>Central Telefónica</h4>
                        <p>
                            <a href="tel:+51987654321">+51 987 654 321</a><br>
                            <a href="tel:+5112345678">01 123-4567</a>
                        </p>
                    </div>
                </div>
                
                <div class="info-card">
                    <div class="info-icon" style="background: linear-gradient(135deg, #EA4335, #DB4437);">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="info-content">
                        <h4>Correo Electrónico</h4>
                        <p>
                            <a href="mailto:ventas@sanchezpharma.com">ventas@sanchezpharma.com</a><br>
                            <a href="mailto:soporte@sanchezpharma.com">soporte@sanchezpharma.com</a>
                        </p>
                    </div>
                </div>
                
                <a href="https://wa.me/51987654321?text=Hola, deseo información sobre los productos" class="info-card whatsapp-card" target="_blank">
                    <div class="info-icon">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <div class="info-content">
                        <h4>WhatsApp Directo</h4>
                        <p>Chatea con nosotros ahora mismo para atención inmediata</p>
                    </div>
                </a>
            </div>

            <div class="contact-form-container">
                <h3>Envíanos un mensaje</h3>
                <form action="{{ route('contact.post') }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nombre del Cliente / Empresa <span>*</span></label>
                            <input type="text" name="empresa" class="form-input" placeholder="Ej. Juan Pérez / Farmacia Salud" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tipo de Documento <span>*</span></label>
                            <div style="display: flex; gap: 10px;">
                                <select name="tipo_doc" id="tipo_doc" class="form-select" style="flex: 0 0 120px;" onchange="updateDocValidation()">
                                    <option value="DNI">DNI (8)</option>
                                    <option value="RUC" selected>RUC (11)</option>
                                </select>
                                <input type="text" name="ruc" id="doc_numero" class="form-input" placeholder="Número de documento" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Correo Electrónico <span>*</span></label>
                            <input type="email" name="email" class="form-input" placeholder="correo@empresa.com" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Teléfono <span>*</span></label>
                            <input type="tel" name="telefono" id="telefono" class="form-input" placeholder="Ej. 987654321" required maxlength="9" pattern="[0-9]{9}">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Asunto <span>*</span></label>
                        <select name="asunto" class="form-select" required>
                            <option value="">Seleccione el motivo de contacto</option>
                            <option value="catalogo">Solicitud de Catálogo y Precios</option>
                            <option value="representante">Solicitar Visita de Representantes</option>
                            <option value="proveedor">Quiero ser Proveedor</option>
                            <option value="otro">Otro Motivo</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Mensaje <span>*</span></label>
                        <textarea name="mensaje" class="form-textarea" placeholder="Especifique los productos o laboratorios de su interés, o detalle su solicitud..." required></textarea>
                    </div>
                    
                    <button type="submit" class="form-submit">
                        <i class="fas fa-paper-plane"></i> Enviar Solicitud
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    function updateDocValidation() {
        const tipo = document.getElementById('tipo_doc').value;
        const input = document.getElementById('doc_numero');
        if (tipo === 'DNI') {
            input.placeholder = 'Ej. 12345678';
            input.maxLength = 8;
            input.pattern = '[0-9]{8}';
        } else {
            input.placeholder = 'Ej. 20123456789';
            input.maxLength = 11;
            input.pattern = '[0-9]{11}';
        }
    }

    document.querySelector('form').addEventListener('submit', function(e) {
        const email = document.getElementsByName('email')[0].value;
        const telefono = document.getElementById('telefono').value;
        const doc = document.getElementById('doc_numero').value;
        const tipo = document.getElementById('tipo_doc').value;

        if (!email.includes('@')) {
            e.preventDefault();
            Swal.fire({ icon: 'error', title: 'Error', text: 'El correo debe contener @' });
            return;
        }

        if (telefono.length !== 9 || isNaN(telefono)) {
            e.preventDefault();
            Swal.fire({ icon: 'error', title: 'Error', text: 'El teléfono debe tener exactamente 9 dígitos' });
            return;
        }

        if (tipo === 'DNI' && doc.length !== 8) {
            e.preventDefault();
            Swal.fire({ icon: 'error', title: 'Error', text: 'El DNI debe tener 8 dígitos' });
            return;
        }

        if (tipo === 'RUC' && doc.length !== 11) {
            e.preventDefault();
            Swal.fire({ icon: 'error', title: 'Error', text: 'El RUC debe tener 11 dígitos' });
            return;
        }
    });

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#10b981'
        });
    @endif
</script>
@endpush