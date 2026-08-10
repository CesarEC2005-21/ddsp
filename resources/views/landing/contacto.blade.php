@extends('layouts.landing')

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════
   CONTACTO — Premium Redesign
   ═══════════════════════════════════════════════════════ */
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800;900&display=swap');

/* ── Scroll reveal ── */
.reveal { opacity: 0; transform: translateY(40px); transition: opacity .7s ease, transform .7s ease; }
.reveal.visible { opacity: 1; transform: none; }

/* ══════════════════════════════════════════════════════
   1. HERO
   ══════════════════════════════════════════════════════ */
.ct-hero {
    position: relative;
    min-height: 60vh;
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
    background: #0f172a;
}
.ct-hero-bg {
    position: absolute; inset: 0;
    background:
        url('{{ ($banner && $banner->image_path) ? asset("storage/".$banner->image_path) : "https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?auto=format&fit=crop&w=1920&q=80" }}')
        center/cover no-repeat;
    filter: brightness(.28) saturate(1.3);
    transform: scale(1.08);
    transition: transform 12s ease-out;
}
.ct-hero-bg.loaded { transform: scale(1); }
.ct-hero-grid {
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(34,197,94,.08) 1px, transparent 1px),
        linear-gradient(90deg, rgba(34,197,94,.08) 1px, transparent 1px);
    background-size: 60px 60px;
    animation: ctGridFloat 20s linear infinite;
}
@keyframes ctGridFloat { to { background-position: 60px 60px; } }
.ct-hero-glow {
    position: absolute; border-radius: 50%;
    filter: blur(80px); opacity: .45;
    animation: ctGlowPulse 6s ease-in-out infinite alternate;
}
.ct-hero-glow.g1 { width: 500px; height: 500px; background: radial-gradient(circle, #22c55e, transparent); top: -150px; right: -100px; }
.ct-hero-glow.g2 { width: 400px; height: 400px; background: radial-gradient(circle, #059669, transparent); bottom: -100px; left: -80px; animation-delay: -3s; }
@keyframes ctGlowPulse { from { opacity:.3; transform:scale(.9); } to { opacity:.6; transform:scale(1.1); } }
#ctParticleCanvas { position: absolute; inset: 0; pointer-events: none; }

.ct-hero-inner {
    position: relative; z-index: 10;
    text-align: center; padding: 0 20px; max-width: 900px;
}
.ct-hero-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(34,197,94,.15); border: 1px solid rgba(34,197,94,.4);
    color: #4ade80; padding: 8px 20px; border-radius: 50px;
    font-size: .8rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
    margin-bottom: 28px; backdrop-filter: blur(10px);
    animation: ctBadgePop .6s .3s both cubic-bezier(.175,.885,.32,1.275);
}
@keyframes ctBadgePop { from { opacity:0; transform:scale(.8) translateY(10px); } to { opacity:1; transform:none; } }

.ct-hero-title {
    font-family: 'Outfit', sans-serif;
    font-size: clamp(3rem, 8vw, 6.5rem);
    font-weight: 900; color: white; line-height: 1; margin-bottom: 12px;
    animation: ctHeroTitle .9s .5s both;
}
@keyframes ctHeroTitle { from { opacity:0; transform:translateY(40px); } to { opacity:1; transform:none; } }
.ct-hero-title .hl {
    background: linear-gradient(135deg, #4ade80 0%, #22c55e 50%, #a3e635 100%);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text; display: block;
}
.ct-hero-sub {
    font-size: clamp(1rem, 2vw, 1.25rem); color: rgba(255,255,255,.7);
    max-width: 600px; margin: 24px auto 0; line-height: 1.7;
    animation: ctHeroTitle 1s .7s both;
}
.ct-hero-scroll {
    position: absolute; bottom: 40px; left: 0; width: 100%;
    display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px;
    color: rgba(255,255,255,.5); font-size: .75rem; letter-spacing: 2px; text-transform: uppercase;
    animation: ctHeroTitle 1s 1s both;
}
.ct-scroll-line {
    width: 1px; height: 50px;
    background: linear-gradient(to bottom, rgba(34,197,94,.8), transparent);
    animation: ctScrollLine 2s ease-in-out infinite;
}
@keyframes ctScrollLine { 0%,100%{ opacity:.3; transform:scaleY(.3) translateY(-10px); } 50%{ opacity:1; transform:scaleY(1) translateY(0); } }


    .contact-section { padding: 80px 5%; background: #f8fafc; }
    
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
    .info-content h4 { font-family: 'Outfit', sans-serif; font-size: 1.1rem; color: #1e293b; font-weight: 800; margin-bottom: 8px; }
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
        font-size: 1.8rem; color: #1e293b; font-family: 'Outfit', sans-serif; 
        font-weight: 900; margin-bottom: 30px;
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
        color: white; border: none; border-radius: 12px; font-size: 1.1rem; font-weight: 800; font-family: 'Outfit', sans-serif;
        cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center;
        gap: 10px; margin-top: 10px;
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

<section class="ct-hero">
    <div class="ct-hero-bg" id="ctHeroBg"></div>
    <div class="ct-hero-grid"></div>
    <div class="ct-hero-glow g1"></div>
    <div class="ct-hero-glow g2"></div>
    <canvas id="ctParticleCanvas"></canvas>

    <div class="ct-hero-inner">
        <div class="ct-hero-badge">
            <i class="fas fa-headset"></i>
            Atención al Cliente
        </div>
        <h1 class="ct-hero-title">
            <span class="hl">Contáctanos</span>
        </h1>
        <p class="ct-hero-sub">
            Nuestro equipo de ejecutivos está disponible para brindarte asesoría personalizada y responder a todas tus consultas sobre productos farmacéuticos.
        </p>
    </div>

    <div class="ct-hero-scroll">
        <div class="ct-scroll-line"></div>
        Envíanos un mensaje
    </div>
</section>


    <section class="contact-section">
        <div class="contact-grid reveal">
            <div class="info-cards">
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <div class="info-content">
                        <h4>Nuestra Sede</h4>
                        <p>Av. Puerto de Palos 309, La Victoria<br>Chiclayo, Perú</p>
                    </div>
                </div>
                
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div class="info-content">
                        <h4>Central Telefónica</h4>
                        <p>
                            <a href="tel:+5192911909">+51 922 911 909</a><br>
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

    /* ─── Hero BG ─── */
    setTimeout(() => document.getElementById('ctHeroBg')?.classList.add('loaded'), 80);

    /* ─── Particles ─── */
    (function() {
        const canvas = document.getElementById('ctParticleCanvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        let pts = [];
        function resize() { canvas.width = window.innerWidth; canvas.height = window.innerHeight; }
        resize(); window.addEventListener('resize', resize);
        for (let i = 0; i < 60; i++) pts.push({
            x: Math.random() * canvas.width, y: Math.random() * canvas.height,
            r: Math.random() * 1.5 + .3, dx: (Math.random()-.5)*.35,
            dy: -Math.random()*.55-.15, o: Math.random()*.45+.1
        });
        function draw() {
            ctx.clearRect(0,0,canvas.width,canvas.height);
            pts.forEach(p => {
                ctx.beginPath(); ctx.arc(p.x,p.y,p.r,0,Math.PI*2);
                ctx.fillStyle = `rgba(74,222,128,${p.o})`; ctx.fill();
                p.x+=p.dx; p.y+=p.dy;
                if(p.y<-5){ p.y=canvas.height+5; p.x=Math.random()*canvas.width; }
                if(p.x<-5) p.x=canvas.width+5;
                if(p.x>canvas.width+5) p.x=-5;
            });
            requestAnimationFrame(draw);
        }
        draw();
    })();

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