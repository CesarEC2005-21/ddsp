@extends('layouts.landing')

@push('styles')
<style>
    .reveal { opacity: 0; transform: translateY(40px); transition: all 0.8s cubic-bezier(0.5, 0, 0, 1); }
    .reveal.visible { opacity: 1; transform: translateY(0); }
    
    .about-hero {
        background: linear-gradient(rgba(27, 94, 32, 0.85), rgba(27, 94, 32, 0.95)), url('https://images.unsplash.com/photo-1631815588090-d4bfec5b1ccb?auto=format&fit=crop&w=1920&q=80') center/cover;
        color: white; text-align: center; padding: 80px 5%; border-radius: 0 0 50px 50px; margin-bottom: 60px;
        animation: fadeInDown 1s ease-out;
    }

    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .stats-bar {
        display: grid; grid-template-columns: repeat(4, 1fr); gap: 30px; max-width: 1000px; margin: -40px auto 60px; position: relative; z-index: 10;
    }
    .stat-card {
        background: white; padding: 30px 20px; border-radius: 20px; text-align: center; box-shadow: 0 15px 40px rgba(0,0,0,0.1); border: 1px solid #f1f5f9;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .stat-card:hover { transform: translateY(-8px); box-shadow: 0 25px 50px rgba(16, 185, 129, 0.2); }
    .stat-number { font-size: 2.5rem; font-weight: 900; color: var(--primary-green); line-height: 1; }
    .stat-label { font-size: 0.9rem; color: #64748b; margin-top: 8px; font-weight: 600; }

    .section-padding { padding: 80px 5%; }
    .section-bg { background: #f8fafc; }

    .about-grid {
        display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; max-width: 1200px; margin: 0 auto;
    }
    .about-grid.reverse { direction: rtl; }
    .about-grid.reverse > * { direction: ltr; }

    .about-content { animation: fadeInLeft 0.8s ease-out; }
    .about-content h2 {
        font-size: 2.5rem; color: #1e293b; font-family: 'Poppins', sans-serif; font-weight: 800; margin-bottom: 20px; line-height: 1.2;
    }
    .about-content h2 span { color: var(--primary-green); }
    .about-content p {
        font-size: 1.1rem; color: #64748b; line-height: 1.8; margin-bottom: 20px;
    }

    @keyframes fadeInLeft {
        from { opacity: 0; transform: translateX(-40px); }
        to { opacity: 1; transform: translateX(0); }
    }

    @keyframes fadeInRight {
        from { opacity: 0; transform: translateX(40px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .about-image {
        border-radius: 30px; overflow: hidden; box-shadow: 0 25px 60px rgba(0,0,0,0.15); position: relative;
        animation: fadeInRight 0.8s ease-out;
    }
    .about-image img { 
        width: 100%; height: 400px; object-fit: cover; 
        transition: transform 0.6s ease;
    }
    .about-image:hover img { transform: scale(1.08); }
    .about-image::before {
        content: ''; position: absolute; top: -20px; right: -20px; width: 100%; height: 100%; border: 4px solid var(--primary-green); border-radius: 30px; z-index: -1;
    }

    .values-grid {
        display: grid; grid-template-columns: repeat(5, 1fr); gap: 25px; max-width: 1200px; margin: 0 auto;
    }
    .value-card {
        background: white; padding: 30px 20px; border-radius: 20px; text-align: center; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); border: 1px solid #f1f5f9;
        animation: fadeInUp 0.6s ease-out backwards;
    }
    .value-card:hover { transform: translateY(-12px); box-shadow: 0 25px 50px rgba(0,0,0,0.15); border-color: var(--primary-green); }
    .value-icon {
        width: 70px; height: 70px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 20px; display: flex;
        align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 1.8rem; color: white;
        transition: all 0.4s ease;
    }
    .value-card:hover .value-icon { transform: scale(1.1) rotate(5deg); box-shadow: 0 10px 30px rgba(16, 185, 129, 0.4); }
    .value-card h4 { font-size: 1.1rem; color: #1e293b; font-weight: 700; margin-bottom: 10px; }
    .value-card p { font-size: 0.9rem; color: #64748b; line-height: 1.6; }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .principles-card {
        background: white; border-radius: 25px; padding: 40px; box-shadow: 0 15px 40px rgba(0,0,0,0.08); border: 1px solid #f1f5f9;
    }
    .principles-card h3 {
        font-size: 1.5rem; color: #1e293b; font-weight: 800; margin-bottom: 25px; display: flex; align-items: center; gap: 12px;
    }
    .principles-card h3 i { color: var(--primary-green); }
    .principles-list { list-style: none; padding: 0; }
    .principles-list li {
        padding: 15px 0; border-bottom: 1px solid #f1f5f9; display: flex; align-items: flex-start; gap: 15px; font-size: 1.05rem; color: #475569;
        transition: all 0.3s ease;
    }
    .principles-list li:hover { background: #f8fafc; padding-left: 10px; border-radius: 8px; }
    .principles-list li:last-child { border-bottom: none; }
    .principles-list li i {
        color: var(--primary-green); margin-top: 4px; transition: transform 0.3s ease;
    }
    .principles-list li:hover i { transform: scale(1.3); }

    @media (max-width: 900px) {
        .stats-bar { grid-template-columns: repeat(2, 1fr); }
        .about-grid { grid-template-columns: 1fr; gap: 40px; }
        .values-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 600px) {
        .stat-number { font-size: 2rem; }
        .values-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
    <div class="about-hero">
        <h1 style="font-size: 3rem; font-family: 'Poppins', sans-serif; color: white !important; font-weight: 800;">Nosotros</h1>
        <p style="font-size: 1.1rem; opacity: 0.9; color: white; max-width: 600px; margin: 15px auto 0;">Conoce más sobre nuestra historia, misión y los valores que nos impulsa a mejorar la salud de todos los peruanos.</p>
    </div>

    <div class="stats-bar reveal">
        <div class="stat-card">
            <div class="stat-number">10+</div>
            <div class="stat-label">Años de Experiencia</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">500+</div>
            <div class="stat-label">Productos</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">50+</div>
            <div class="stat-label">Ejecutivos</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">24/7</div>
            <div class="stat-label">Atención</div>
        </div>
    </div>

    <section class="section-padding">
        <div class="about-grid">
            <div class="about-image reveal">
                <img src="{{ asset('img/hero.png') }}" alt="Nuestra Historia">
            </div>
            <div class="about-content reveal">
                <span style="color: var(--primary-green); font-weight: 800; text-transform: uppercase; letter-spacing: 2px; font-size: 0.85rem;">Nuestra Historia</span>
                <h2>Comprometidos con la <span>Salud</span> del Perú</h2>
                <p>{{ $settings['historia'] }}</p>
                <p>Desde nuestros inicios, hemos trabajado incansablemente para construir una red de distribución que llegue a cada rincón del país, garantizando que cada peruano tenga acceso a los medicamentos que necesita.</p>
            </div>
        </div>
    </section>

    <section class="section-padding section-bg">
        <div style="text-align: center; margin-bottom: 60px;" class="reveal">
            <span style="color: var(--primary-green); font-weight: 800; text-transform: uppercase; letter-spacing: 2px; font-size: 0.85rem;">¿Por qué elegirnos?</span>
            <h2 style="font-size: 2.5rem; color: #1e293b; margin: 15px 0; font-family: 'Poppins', sans-serif; font-weight: 800;">Nuestra Esencia</h2>
            <div style="width: 80px; height: 4px; background: var(--primary-green); margin: 0 auto; border-radius: 2px;"></div>
        </div>

        <div class="about-grid reveal">
            <div class="about-content">
                <h2>Nuestra <span>Misión</span></h2>
                <p>{{ $settings['mision'] }}</p>
            </div>
            <div class="about-image">
                <img src="https://images.unsplash.com/photo-1559757175-5700dde675bc?auto=format&fit=crop&w=800&q=80" alt="Misión">
            </div>
        </div>

        <div class="about-grid reverse reveal" style="margin-top: 80px;">
            <div class="about-image">
                <img src="https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?auto=format&fit=crop&w=800&q=80" alt="Visión">
            </div>
            <div class="about-content">
                <h2>Nuestra <span>Visión</span></h2>
                <p>{{ $settings['vision'] }}</p>
            </div>
        </div>
    </section>

    <section class="section-padding">
        <div style="text-align: center; margin-bottom: 60px;" class="reveal">
            <span style="color: var(--primary-green); font-weight: 800; text-transform: uppercase; letter-spacing: 2px; font-size: 0.85rem;">Nuestros Pilares</span>
            <h2 style="font-size: 2.5rem; color: #1e293b; margin: 15px 0; font-family: 'Poppins', sans-serif; font-weight: 800;">Valores Corporativos</h2>
            <div style="width: 80px; height: 4px; background: var(--primary-green); margin: 0 auto; border-radius: 2px;"></div>
        </div>

        <div class="values-grid">
            @foreach(explode(',', $settings['valores']) as $index => $valor)
            <div class="value-card" style="animation-delay: {{ $index * 0.1 }}s">
                <div class="value-icon">
                    @php
                        $valClean = trim($valor);
                        $valLower = strtolower($valClean);
                        $icon = 'star';
                        if (str_contains($valLower, 'compromiso')) $icon = 'hand-holding-heart';
                        elseif (str_contains($valLower, 'honestidad')) $icon = 'shield-alt';
                        elseif (str_contains($valLower, 'innovaci')) $icon = 'lightbulb';
                        elseif (str_contains($valLower, 'servicio')) $icon = 'headset';
                        elseif (str_contains($valLower, 'calidad')) $icon = 'award';
                        elseif (str_contains($valLower, 'ética')) $icon = 'balance-scale';
                        elseif (str_contains($valLower, 'integridad')) $icon = 'user-shield';
                    @endphp
                    <i class="fas fa-{{ $icon }}"></i>
                </div>
                <h4>{{ $valClean }}</h4>
                <p>Guiando cada acción con {{ strtolower($valClean) }} y excelencia</p>
            </div>
            @endforeach
        </div>
    </section>

    <section class="section-padding section-bg">
        <div class="about-grid">
            <div class="principles-card reveal">
                <h3><i class="fas fa-book-open"></i> Principios Institucionales</h3>
                <ul class="principles-list">
                    @foreach(explode("\n", $settings['principios']) as $principio)
                    @if(trim($principio))
                    <li><i class="fas fa-check-circle"></i> {{ trim($principio, '• ') }}</li>
                    @endif
                    @endforeach
                </ul>
            </div>
            <div class="about-content reveal">
                <h2> Nuestros <span>Principios</span></h2>
                <p>Guiamos cada acción y decisión mediante principios sólidos que garantizan la integridad y el compromiso con nuestros clientes y la sociedad.</p>
                <p>Estos principios nos permiten mantener los más altos estándares de calidad en cada aspecto de nuestra operación, desde la selección de productos hasta la entrega final.</p>
            </div>
        </div>
    </section>

    <section style="padding: 80px 5%; text-align: center; background: linear-gradient(135deg, #10b981, #059669);">
        <h2 style="font-size: 2.5rem; color: white; font-family: 'Poppins', sans-serif; font-weight: 800; margin-bottom: 15px;">¿Listo para trabajar con nosotros?</h2>
        <p style="font-size: 1.1rem; color: rgba(255,255,255,0.9); max-width: 600px; margin: 0 auto 30px;">Contáctanos hoy mismo y descubre cómo podemos ayudarte a acceder a medicamentos de calidad.</p>
        <a href="{{ route('contact') }}" style="display: inline-flex; align-items: center; gap: 10px; background: white; color: var(--primary-green); padding: 18px 40px; border-radius: 50px; font-weight: 700; text-decoration: none; font-size: 1.1rem; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
            <i class="fas fa-envelope"></i> Contáctanos
        </a>
    </section>
@endsection

@push('scripts')
<script>
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
</script>
@endpush